<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Imports\Educacion\ImporNexusImport;
use App\Imports\tablaXImport;
use Illuminate\Http\Request;
use App\Models\Administracion\Entidad;
use App\Models\educacion\ImporNexus;
use App\Models\Educacion\Importacion;
use App\Repositories\Educacion\ImporNexusRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporNexusController extends Controller
{
    public $fuente = 2;
    public static $FUENTE = 2;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = $this->fuente;
        return view('educacion.ImporGeneral.Importar', compact('fuente'));
        // return view('educacion.ImporNexus.Importar', compact('fuente'));

        //$mensaje = "";return view('educacion.CuadroAsigPersonal.Importar', compact('mensaje'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mensaje = "";
        return view('educacion.CuadroAsigPersonal.Exportar', compact('mensaje', 'imp'));
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
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'fechaActualizacion' => 'required|date_format:Y-m-d',
        ]);

        $fechaActualizacion = Carbon::createFromFormat('Y-m-d', $request->fechaActualizacion)->startOfDay();
        $usuarioId = auth()->user()->id;

        $importacionExistente = Importacion::where('fuenteImportacion_id', $this->fuente)
            ->whereDate('fechaActualizacion', $fechaActualizacion)
            ->whereIn('estado', ['PE', 'PR'])
            ->first();

        if ($importacionExistente) {
            return response()->json([
                'error' => 'Ya existe una importación pendiente o procesada para esta fuente y fecha.',
                'importacion_id' => $importacionExistente->id,
                'estado' => $importacionExistente->estado,
            ], 422); // 422 Unprocessable Entity
        }

        $importacion = Importacion::create([
            'fuenteImportacion_id' => $this->fuente,
            'usuarioId_Crea' => $usuarioId,
            'fechaActualizacion' => $fechaActualizacion,
            'estado' => 'PE',
        ]);

        try {
            Excel::import(new ImporNexusImport($importacion->id), $request->file('file'), null, \Maatwebsite\Excel\Excel::XLSX, 0);
            $importacion->update(['estado' => 'PR']);
            
            return response()->json([
                'message' => 'Archivo importado exitosamente.',
                'importacion_id' => $importacion->id,
                'total_registros' => DB::table('edu_impor_nexus')->where('importacion_id', $importacion->id)->count(),
            ], 200);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => 'Archivo inválido: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            $importacion->update(['estado' => 'EL']);
            return response()->json([
                'error' => 'Error al importar el archivo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function guardar_x(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

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

        //$this->json_output(200, 'aqui tamos', $array);

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        try {
            foreach ($array as $value) {
                foreach ($value as $celda => $row) {
                    if ($celda > 0) break;
                    $cadena =
                        $row['unidad_ejecutora'] .
                        $row['ugel'] .
                        $row['provincia'] .
                        $row['distrito'] .
                        $row['tipo_ie'] .
                        $row['gestion'] .
                        $row['zona'] .
                        $row['codmod_ie'] .
                        $row['codigo_local'] .
                        $row['clave8'] .
                        $row['nivel_educativo'] .
                        $row['institucion_educativa'] .
                        $row['jec'] .
                        $row['codigo_plaza'] .
                        $row['tipo_trabajador'] .
                        $row['sub_tipo_trabajador'] .
                        $row['cargo'] .
                        $row['situacion_laboral'] .
                        $row['motivo_vacante'] .
                        $row['categoria_remunerativa'] .
                        $row['descripcion_escala'] .
                        $row['jornada_laboral'] .
                        $row['estado'] .
                        $row['fecha_inicio'] .
                        $row['fecha_termino'] .
                        $row['tipo_registro'] .
                        $row['ley'] .
                        $row['fecha_ingreso_nomb'] .
                        $row['documento'] .
                        $row['codmod_docente'] .
                        $row['apellido_paterno'] .
                        $row['apellido_materno'] .
                        $row['nombres'] .
                        $row['fecha_nacimiento'] .
                        $row['sexo'] .
                        $row['regimen_pensionario'] .
                        $row['fecha_afiliacion_rp'] .
                        $row['codigo_essalud'] .
                        $row['afp'] .
                        $row['codigo_afp'] .
                        $row['fecha_afiliacion_afp'] .
                        $row['fecha_devengue_afp'] .
                        $row['mencion'] .
                        $row['centro_estudios'] .
                        $row['tipo_estudios'] .
                        $row['estado_estudios'] .
                        $row['especialidad_profesional'] .
                        $row['grado'] .
                        $row['celular'] .
                        $row['email'] .
                        $row['especialidad'] .
                        $row['fecha_resolucion'] .
                        $row['numero_resolucion'] .
                        $row['desc_superior'] .
                        $row['numero_contrato_cas'] .
                        $row['numero_adenda_cas'] .
                        $row['preventiva'] .
                        $row['referencia_preventiva'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar.<br>" . $e;
            $this->json_output(403, $mensaje);
        }

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                // 'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request->fechaActualizacion,
                // 'comentario' => $request->comentario,
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    if ($row['unidad_ejecutora'] != NULL) {
                        $CuadroAsigPersonal = CuadroAsigPersonal::Create([
                            'importacion_id' => $importacion->id,
                            'unidad_ejecutora' => $row['unidad_ejecutora'],
                            'organo_intermedio' => $row['ugel'],
                            'provincia' => $row['provincia'],
                            'distrito' => $row['distrito'],
                            'tipo_ie' => $row['tipo_ie'],
                            'gestion' => $row['gestion'],
                            'zona' => $row['zona'],
                            'codmod_ie' => $row['codmod_ie'],
                            'codigo_local' => $row['codigo_local'],
                            'clave8' => $row['clave8'],
                            'nivel_educativo' => $row['nivel_educativo'],
                            'institucion_educativa' => $row['institucion_educativa'],
                            'jec' => $row['jec'],
                            'codigo_plaza' => $row['codigo_plaza'],
                            'tipo_trabajador' => $row['tipo_trabajador'],
                            'sub_tipo_trabajador' => $row['sub_tipo_trabajador'],
                            'cargo' => $row['cargo'],
                            'situacion_laboral' => $row['situacion_laboral'],
                            'motivo_vacante' => $row['motivo_vacante'],
                            'categoria_remunerativa' => $row['categoria_remunerativa'],
                            'descripcion_escala' => $row['descripcion_escala'],
                            'jornada_laboral' => $row['jornada_laboral'],
                            'estado' => $row['estado'],
                            'fecha_inicio' => $row['fecha_inicio'],
                            'fecha_termino' => $row['fecha_termino'],
                            'tipo_registro' => $row['tipo_registro'],
                            'ley' => $row['ley'],
                            'fecha_ingreso' => $row['fecha_ingreso_nomb'],
                            'documento_identidad' => $row['documento'],
                            'codigo_modular' => $row['codmod_docente'],
                            'apellido_paterno' => $row['apellido_paterno'],
                            'apellido_materno' => $row['apellido_materno'],
                            'nombres' => $row['nombres'],
                            'fecha_nacimiento' => $row['fecha_nacimiento'],
                            'sexo' => $row['sexo'],
                            'regimen_pensionario' => $row['regimen_pensionario'],
                            'fecha_afiliacion_rp' => $row['fecha_afiliacion_rp'],
                            'codigo_essalud' => $row['codigo_essalud'],
                            'afp' => $row['afp'],
                            'codigo_afp' => $row['codigo_afp'],
                            'fecha_afiliacion_afp' => $row['fecha_afiliacion_afp'],
                            'fecha_devengue_afp' => $row['fecha_devengue_afp'],
                            'mencion' => $row['mencion'],
                            'centro_estudios' => $row['centro_estudios'],
                            'tipo_estudios' => $row['tipo_estudios'],
                            'estado_estudios' => $row['estado_estudios'],
                            'especialidad_profesional' => $row['especialidad_profesional'],
                            'grado' => $row['grado'],
                            'celular' => $row['celular'],
                            'email' => $row['email'],
                            'especialidad' => $row['especialidad'],
                            'fecha_resolucion' => $row['fecha_resolucion'],
                            'numero_resolucion' => $row['numero_resolucion'],
                            'desc_superior' => $row['desc_superior'],
                            'numero_contrato_cas' => $row['numero_contrato_cas'],
                            'numero_adenda_cas' => $row['numero_adenda_cas'],
                            'preventiva' => $row['preventiva'],
                            'referencia_preventiva' => $row['referencia_preventiva'],
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema .<br>" . $e;
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call edu_pa_procesarCuadroAsigPersonal(?,?)', [$importacion->id, auth()->user()->id]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos.<br>" . $e;
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

            $ent = Entidad::find($value->entidad);

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs" id="eliminar' . $value->id . '"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $ape,
                $ent ? $ent->abreviado : '',
                date("d/m/Y", strtotime($value->created_at)),

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
        $data = ImportacionRepositorio::Listar_FuenteTodos('2');
        return datatables()
            ->of($data)
            ->editColumn('fechaActualizacion', '{{date("d/m/Y",strtotime($fechaActualizacion))}}')
            ->editColumn('created_at', '{{date("d/m/Y",strtotime($created_at))}}')
            ->editColumn('estado', function ($query) {
                return $query->estado == "PR" ? "PROCESADO" : ($query->estado == "PE" ? "PENDIENTE" : "ELIMINADO");
            })
            ->addColumn('accion', function ($oo) {
                if (date('Y-m-d', strtotime($oo->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
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
            ->rawColumns(['fechaActualizacion', 'estado', 'accion', 'nombrecompleto'])
            ->toJson();
    }

    public function ListaImportada($importacion_id)
    {
        $data = CuadroAsigPersonal::where('importacion_id', $importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $Lista = ImporNexusRepositorio::Listar_Por_Importacion_id($importacion_id);

        return  datatables()->of($Lista)->toJson();;
    }

    public function procesar($importacion_id)
    {
        $procesar = DB::select('call edu_pa_procesarCuadroAsigPersonal(?,?)', [$importacion_id, auth()->user()->id]);
        return view('correcto');
    }

    public function eliminarx($id)
    {
        ImporNexus::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }

    public function eliminar($id)
    {
        try {
            // Eliminar registros relacionados
            ImporNexus::where('importacion_id', $id)->delete();

            // Eliminar cabecera
            $importacion = Importacion::findOrFail($id);
            $importacion->delete();

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar importación ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['status' => false], 500);
        }
    }

    public function download()
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $name = 'NEXUS ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronNexusExport, $name);
    }
}
