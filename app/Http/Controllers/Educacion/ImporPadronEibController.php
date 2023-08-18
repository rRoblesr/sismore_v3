<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Educacion\ImporPadronEib;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\PadronEIB;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\PadronEIBRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporPadronEibController extends Controller
{
    public $fuente = 12;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();
        return view('educacion.ImporPadronEIB.Importar', compact('mensaje', 'anios'));
    }

    function json_output($status = 200, $msg = 'OK!!', $data = null)
    {
        header('Content-Type:application/json');
        echo json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ]);
        die;
    }

    public function guardar(Request $request)
    {
        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, $this->fuente);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos procesados para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        try {
            foreach ($array as $value) {
                foreach ($value as $celda => $row) {
                    if ($celda > 0) break;
                    $cadena =
                        $row['periodo'] .
                        $row['ugel'] .
                        $row['provincia'] .
                        $row['distrito'] .
                        $row['centro_poblado'] .
                        $row['cod_mod'] .
                        $row['cod_local'] .
                        $row['institucion_educativa'] .
                        $row['cod_nivelmod'] .
                        $row['nivel_modalidad'] .
                        $row['forma_atencion'] .
                        $row['lengua_uno'] .
                        $row['lengua_dos'] .
                        $row['lengua_3'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $this->json_output(403, $mensaje);
        }
        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $padronEIB = ImporPadronEib::Create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['periodo'],
                        //'dre' => $row['dre'],
                        'ugel' => $row['ugel'],
                        'departamento' => 'UCAYALI', // $row['departamento'],
                        'provincia' => $row['provincia'],
                        'distrito' => $row['distrito'],
                        'centro_poblado' => $row['centro_poblado'],
                        'cod_mod' => $row['cod_mod'],
                        'cod_local' => $row['cod_local'],
                        'institucion_educativa' => $row['institucion_educativa'],
                        'cod_nivelmod' => $row['cod_nivelmod'],
                        'nivel_modalidad' => $row['nivel_modalidad'],
                        'forma_atencion' => $row['forma_atencion'],
                        //'cod_lengua' => $row['cod_lengua'],
                        'lengua_uno' => $row['lengua_uno'],
                        'lengua_dos' => $row['lengua_dos'],
                        'lengua_3' => $row['lengua_3'],/*  */
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call edu_pa_procesarPadronEIB(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e;
            $this->json_output(400, $mensaje);
        }
        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
        $data = [];
        foreach ($query as $key => $value) {
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            $ape = '';
            if (strlen($value->capellido1) > 0) {
                $xx = explode(' ', $value->capellido1 . ' ' . $value->capellido2); 
                $ape = $xx[0];
            }

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $ape,
                date("d/m/Y", strtotime($value->created_at)),
                $value->comentario,
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data
        );
        return response()->json($result);
    }

    public function ListarDTImportFuenteTodosx()
    {
        $permitidos = [3, 8, 9, 10, 11];
        $data = ImportacionRepositorio::Listar_FuenteTodos('8');
        return datatables()
            ->of($data)
            ->editColumn('fechaActualizacion', '{{date("d/m/Y",strtotime($fechaActualizacion))}}')
            ->editColumn('created_at', '{{date("d/m/Y",strtotime($created_at))}}')
            ->editColumn('estado', function ($query) {
                return $query->estado == "PR" ? "PROCESADO" : ($query->estado == "PE" ? "PENDIENTE" : "ELIMINADO");
            })
            ->addColumn('accion', function ($oo) {
                if (date('Y-m-d', strtotime($oo->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
                    $msn = '<button type="button" onclick="geteliminar(' . $oo->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
                else
                    $msn = '';
                return $msn;
            })
            ->addColumn('nombrecompleto', function ($oo) {
                $nom = '';
                if (strlen($oo->cnombre) > 0) {
                    $xx = explode(' ', $oo->cnombre);
                    $nom = $xx[0];
                }
                $ape = '';
                if (strlen($oo->capellido1) > 0) {
                    $xx = explode(' ', $oo->capellido1 . ' ' . $oo->capellido2); 
                    $ape = $xx[0];
                }
                return $nom . ' ' . $ape;
            })
            ->rawColumns(['fechaActualizacion', 'estado', 'accion', 'created_at', 'nombrecompleto'])
            ->toJson();
    }

    public function ListaImportada(Request $request, $importacion_id) //(Request $request, $importacion_id)
    {
        $data = PadronEIBRepositorio::listaImportada($importacion_id);
        return DataTables::of($data)
            ->addColumn('accion', function ($oo) {
                /* if ($oo->ingreso == 1)
                    return '<button type="button" onclick="borrarmanual(' . $oo->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
                else */
                return '';
            })
            ->rawColumns(['accion'])
            ->make(true);
    }

    public function eliminar($id)
    {
        /* $entidad = Importacion::find($id);
        $entidad->estado = 'EL';
        $entidad->save(); */

        PadronEIB::where('importacion_id', $id)->delete();
        ImporPadronEib::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();

        return response()->json(['status' => true]);
    }

    public function ajax_cargarnivel(Request $rq)
    {
        $id = $rq->get('ugel');
        $nivel = PadronEIB::select('v3.id', 'v3.nombre')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'edu_padron_eib.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->where('edu_padron_eib.importacion_id', $rq->get('importacion'));
        if ($id > 0)
            $nivel = $nivel->where('v2..Ugel_id', $id);
        $nivel = $nivel->distinct()->orderBy('nombre', 'asc')->get();
        return response()->json(compact('nivel'));
    }
}
