<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporMatriculaGeneral;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaDetalle;
use App\Models\Educacion\MatriculaGeneral;
use App\Models\Educacion\MatriculaGeneralDetalle;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\ImporMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporMatriculaGeneralController extends Controller
{
    public $fuente = 34;
    public static $FUENTE = 34;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = $this->fuente;
        return view('educacion.ImporGeneral.Importar', compact('fuente'));
        //$imp = Importacion::where(['fuenteimportacion_id' => 8, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        //$/mat = Matricula::where('importacion_id', $imp->id)->first();
        //$mensaje = "";
        ///$anios = Anio::orderBy('anio', 'desc')->get();
        ///return view('educacion.ImporMatricula.Importar', compact('mensaje', 'anios', 'mat'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 8, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        $mensaje = "";
        return view('educacion.ImporMatriculaGeneral.Exportar', compact('mensaje', 'imp', 'mat'));
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

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        try {
            foreach ($array as $value) {
                foreach ($value as $celda => $row) {
                    if ($celda > 0) break;
                    $cadena =
                        $row['id_anio'] .
                        $row['cod_mod'] .
                        $row['id_mod'] .
                        $row['id_nivel'] .
                        $row['id_gestion'] .
                        $row['id_sexo'] .
                        $row['fecha_nacimiento'] .
                        $row['pais_nacimiento'] .
                        $row['lengua_materna'] .
                        $row['segunda_lengua'] .
                        $row['id_discapacidad'] .
                        $row['discapacidad'] .
                        $row['situacion_matricula'] .
                        $row['estado_matricula'] .
                        $row['fecha_matricula'] .
                        $row['id_grado'] .
                        $row['grado'] .
                        $row['id_seccion'] .
                        $row['seccion'] .
                        $row['fecha_registro'] .
                        $row['fecha_retiro'] .
                        $row['motivo_retiro'] .
                        $row['sf_regular'] .
                        $row['sf_recuperacion'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $this->json_output(403, $mensaje);
        }
        /* $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, $array ); */
        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                // 'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                // 'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);
            $anio = Anio::where('anio', date('Y', strtotime($request['fechaActualizacion'])))->first();

            $matricula = MatriculaGeneral::Create([
                'importacion_id' => $importacion->id,
                'anio_id' => $anio->id
            ]);

            //$fecha_php = date('Y-m-d', strtotime('1899-12-30 +' . $fecha_excel . ' days'));

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    ImporMatriculaGeneral::Create([
                        'importacion_id' => $importacion->id,
                        'id_anio' => $row['id_anio'],
                        'cod_mod' => $row['cod_mod'],
                        'id_mod' => $row['id_mod'],
                        'id_nivel' => $row['id_nivel'],
                        'id_gestion' => $row['id_gestion'],
                        'id_sexo' => $row['id_sexo'],
                        'fecha_nacimiento' => $row['fecha_nacimiento'],
                        'pais_nacimiento' => $row['pais_nacimiento'],
                        'lengua_materna' => $row['lengua_materna'],
                        'segunda_lengua' => $row['segunda_lengua'],
                        'id_discapacidad' => $row['id_discapacidad'],
                        'discapacidad' => $row['discapacidad'],
                        'situacion_matricula' => $row['situacion_matricula'],
                        'estado_matricula' => $row['estado_matricula'],
                        'fecha_matricula' => $row['fecha_matricula'],
                        'id_grado' => $row['id_grado'],
                        'grado' => $row['grado'],
                        'id_seccion' => $row['id_seccion'],
                        'seccion' => $row['seccion'],
                        'fecha_registro' => $row['fecha_registro'],
                        'fecha_retiro' => $row['fecha_retiro'],
                        'motivo_retiro' => $row['motivo_retiro'],
                        'sf_regular' => $row['sf_regular'],
                        'sf_recuperacion' => $row['sf_recuperacion']
                    ]);
                }
            }
        } catch (Exception $e) {
            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call edu_pa_procesarImporMatriculaGeneral(?,?,?)', [$importacion->id, $matricula->id, date('Y-m-d', strtotime($importacion->fechaActualizacion))]);
        } catch (Exception $e) {
            $mensaje = "Error al procesar la normalizacion de datos." . $e;
            $tipo = 'danger';
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

            $ent = Entidad::select('adm_entidad.*');
            $ent = $ent->join('adm_entidad as v2', 'v2.dependencia', '=', 'adm_entidad.id');
            $ent = $ent->join('adm_entidad as v3', 'v3.dependencia', '=', 'v2.id');
            $ent = $ent->where('v3.id', $value->entidad);
            $ent = $ent->first();

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
                $ent ? $ent->apodo : '',
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
        $permitidos = [3, 8, 9, 10, 11];
        $data = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
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
            ->rawColumns(['fechaActualizacion', 'estado', 'accion', 'created_at', 'nombrecompleto'])
            ->toJson();
    }

    public function ListaImportada($importacion_id)
    {
        $data = ImporMatriculaGeneral::where('importacion_id', $importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = ImporMatriculaRepositorio::Listar_Por_Importacion_id($importacion_id);
        return  datatables()->of($padronWebLista)->toJson();
    }

    public function eliminar($id)
    {
        $impor = Importacion::find($id);
        if ($impor->estado == 'PE') {
            ImporMatriculaGeneral::where('importacion_id', $id)->delete();
            MatriculaGeneral::where('importacion_id', $id)->delete();
        } else {
            ImporMatriculaGeneral::where('importacion_id', $id)->delete();
            $matricula = MatriculaGeneral::where('importacion_id', $id)->first();
            if ($matricula) {
                MatriculaGeneralDetalle::where('matriculageneral_id', $matricula->id)->delete();
                $matricula->delete();
            }
        }
        $impor->delete();
        return response()->json(array('status' => true));
    }

    public function download()
    {
        //$name = 'SIAGIE MATRICULAS ' . date('Y-m-d') . '.xlsx';
        //return Excel::download(new ImporPadronSiagieExport, $name);
    }
}
