<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Imports\IndicadoresImport;
use App\Models\Educacion\Ece;
use App\Models\Educacion\EceResultado;
use App\Models\Educacion\Grado;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\Materia;
use App\Models\Parametro\Anio;
use App\Models\Ubigeo;
use App\Repositories\Educacion\EceRepositorio;
use App\Repositories\Educacion\GradoRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class EceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function importar()
    {
        $materias = Materia::all();
        $nivels = GradoRepositorio::buscar_nivel1();
        $anios = Anio::orderBy('anio', 'desc')->get();
        //return $anios;
        //$eces=EceRepositorio::listar_importaciones();
        //return $eces;
        return view('educacion.Ece.importar', compact('nivels', 'materias', 'anios'));
    }
    public function importarGuardar(Request $request)
    {
        $this->validate($request, ['file' => 'required|mimes:xls,xlsx',]);
        $archivo = $request->file('file');
        $array = (new IndicadoresImport)->toArray($archivo);
        if (count($array) > 1)
            return back()->with('messageError', 'Por favor considere solo una hoja de excel en el libro');
        if (count($array) < 1)
            return back()->with('messageError', 'El  libro no tiene hojas');
        if ($request->tipo == 0) {
            try {
                foreach ($array as $value) {
                    foreach ($value as $key => $row) {
                        $cadena = $row['codigo_modular'] . $row['programados'] . $row['materia'] . $row['evaluados'] . $row['previo'] . $row['inicio'] . $row['proceso'] . $row['satisfactorio'] . $row['media_promedio'];
                        if ($key > 0) break;
                    }
                }
            } catch (Exception $e) {
                $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
                return back()->with('messageError', $mensaje);
            }
        } else {
            try {
                foreach ($array as $value) {
                    foreach ($value as $key => $row) {
                        $cadena = $row['codigo_modular'] . $row['programados'] . $row['materia'] . $row['evaluados'] . $row['inicio'] . $row['proceso'] . $row['lengua_evaluada'] . $row['satisfactorio'] . $row['media_promedio'];
                        if ($key > 0) break;
                    }
                }
            } catch (Exception $e) {
                $mensaje = "Formato de archivo EIB no reconocido, porfavor verifique si el formato es el correcto";
                return back()->with('messageError', $mensaje);
            }
        }
        $errores['tipo'] = '1';
        $errores['msn'] = 'Importacion Exitosa';
        /*Buscar colegios no agregados*/
        $noagregados = [];
        foreach ($array as $key => $value) {
            foreach ($value as $key2 => $row) {
                $insedu = InstitucionEducativa::where('codModular', $row['codigo_modular'])->where('estado', 'AC')->first();
                if (!$insedu) {
                    $noagregados[] = $row['codigo_modular'];
                }
            }
        }
        if (count($noagregados) > 0) {
            $errores['tipo'] = '0';
            $errores['msn'] = 'ERROR EN LA IMPORTACION';
            return view('educacion.Ece.Error1', compact('noagregados', 'errores'));
        }

        /** agregar excel al sistema */
        try {
            if (count($array) > 0) {
                $importacion = Importacion::Create([
                    'fuenteImportacion_id' => $request->fuenteImportacion, // valor predeterminado
                    'usuarioId_Crea' => auth()->user()->id,
                    'usuarioId_Aprueba' => null,
                    'fechaActualizacion' => $request->fechaActualizacion,
                    'comentario' => $request->comentario,
                    'estado' => 'PE'
                ]);
                $ece = Ece::Create([
                    'anio_id' => $request->anio,
                    'tipo' => $request->tipo,
                    'grado_id' => $request->grado,
                    'importacion_id' => $importacion->id,
                ]);
                foreach ($array as $key => $value) {
                    foreach ($value as $key2 => $row) {
                        $insedu = InstitucionEducativa::where('codModular', $row['codigo_modular'])->first();
                        if ($request->tipo == 0) {
                            $eceresultado = EceResultado::Create([
                                'ece_id' => $ece->id,
                                'institucioneducativa_id' => $insedu->id,
                                'materia_id' => $row['materia'],
                                'programados' => $row['programados'],
                                'evaluados' => $row['evaluados'],
                                'previo' => $row['previo'],
                                'inicio' => $row['inicio'],
                                'proceso' => $row['proceso'],
                                'mediapromedio' => $row['media_promedio'],
                                'satisfactorio' => $row['satisfactorio'],
                            ]);
                        } else {
                            if ($row['evaluados'] != null)
                                if ($row['evaluados'] != '') {
                                    $eceresultado = EceResultado::Create([
                                        'ece_id' => $ece->id,
                                        'institucioneducativa_id' => $insedu->id,
                                        'materia_id' => $row['materia'],
                                        'lengua' => $row['lengua_evaluada'],
                                        'programados' => $row['programados'],
                                        'evaluados' => $row['evaluados'],
                                        //'previo' => '0',
                                        'inicio' => $row['inicio'],
                                        'proceso' => $row['proceso'],
                                        'mediapromedio' => $row['media_promedio'],
                                        'satisfactorio' => $row['satisfactorio'],
                                    ]);
                                }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $mensaje = "Error en la carga de datos, comuniquese con el administrador del sistema \n" . $e->getMessage();
            EceResultado::where('ece_id', $ece->id)->delete(); //elimina eceresultados cargados
            $ece->delete(); //elimina ece creada
            $importacion->delete(); // elimina la importacion creada
            return view('Vivienda.PadronEmapacopsa.Importar', compact('mensaje'));
        }
        //return back()->with('message', 'IMPORTACION EXITOSA');
        return redirect()->route('ece.importar.listado', $importacion->id);
    }
    public function importarAprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
        $ece = EceRepositorio::buscar_ece1($importacion_id);
        $resultados = EceRepositorio::listar_eceresultado1($ece->id);
        return view('educacion.Ece.Aprobar', compact('importacion', 'ece', 'resultados', 'importacion_id'));
    }
    public function importarAprobarGuardar(Importacion $importacion)
    {
        $importacion->estado = 'PR';
        $importacion->save();
        return back()->with('message', 'Importacion Aprobada Correctamente');
    }
    public function importarListado($importacion_id)
    {
        return view('educacion.Ece.ListaImportada', compact('importacion_id'));
    }
    public function importarListadoDT($importacion_id)
    {
        $Lista = EceRepositorio::ListarImportados($importacion_id);
        return  datatables()->of($Lista)->toJson();
    }
    public function ListarEceImportadosDT()
    {
        $eces = EceRepositorio::listar_importaciones();
        return datatables()
            ->of($eces)
            ->editColumn('fecha', '{{date("d-m-Y",strtotime($fecha))}}')
            ->editColumn('tipo', '{{$tipo==0?"":"SI"}}')
            //->addColumn('acciones','<button type="button" onclick="eliminarImportacion({{$importacion_id}})" class="btn btn-danger btn-xs">E</button>')
            ->addColumn('acciones', function ($obj) {
                /*if ($obj->estado == 'PR')
                    return '<button type="button" onclick="eliminarImportacion(' . $obj->importacion_id . ')" class="btn btn-danger btn-xs">E</button>';*/
                return '';
            })
            ->rawColumns(['fecha', 'tipo', 'acciones'])
            ->toJson();
    }
    public function EliminarImportados($id)
    {
        $query = Importacion::find($id);
        $query->estado = 'EL';
        $query->save();
        //$status=true;
        //return response()->json(compact('status'));
        return response()->json(['status' => true]);
    }
    public function cargargrados(Request $request)
    {
        $grados = GradoRepositorio::buscar_grados1($request->nivel);
        return response()->json(compact('grados'));
    }
}
