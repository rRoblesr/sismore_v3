<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\ImporPadronSiagieExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporMatricula;
use App\Models\Educacion\ImporTableta;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\Tableta;
use App\Models\Educacion\TabletaDetalle;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\Poblacion;
use App\Models\Parametro\PoblacionDetalle;
use App\Repositories\Educacion\ImporMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporTabletaController extends Controller
{
    public static $FUENTE = 9;
    public $fuente = 9;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        return view('educacion.ImporTableta.Importar', compact('mensaje'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        $mensaje = "";
        return view('educacion.ImporPoblacion.Exportar', compact('mensaje', 'imp', 'mat'));
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
                        $row['ugel'] .
                        $row['provincia'] .
                        $row['distrito'] .
                        $row['cod_mod'] .
                        $row['institucion_educativa'] .
                        $row['estado'] .
                        $row['tabletas_programadas'] .
                        $row['cargadores_programadas'] .
                        $row['tabletas_chip'] .
                        $row['tabletas_pecosa'] .
                        $row['cargadores_pecosa'] .
                        $row['tabletas_pecosa_siga'] .
                        $row['cargadores_pecosa_siga'] .
                        $row['tabletas_entregadas_sigema'] .
                        $row['cargadores_entregadas_sigema'] .
                        $row['tabletas_recepcionadas'] .
                        $row['cargadores_recepcionadas'] .
                        $row['tabletas_asignadas'] .
                        $row['tabletas_asignadas_estudiantes'] .
                        $row['tabletas_asignadas_docentes'] .
                        $row['cargadores_asignadas'] .
                        $row['cargadores_asignadas_estudiantes'] .
                        $row['cargadores_asignadas_docentes'] .
                        $row['tabletas_devueltas'] .
                        $row['cargadores_devueltos'] .
                        $row['tabletas_perdidas'] .
                        $row['cargadores_perdidos'];
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

            $tableta = Tableta::Create([
                'importacion_id' => $importacion->id,
                'anio_id' => Anio::where('anio', date('Y', strtotime($importacion->fechaActualizacion)))->first()->id,
                'created_at' => date('Y-m-d h:i:s'),
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $padronTableta = ImporTableta::Create([
                        'importacion_id' => $importacion->id,
                        'ugel' => $row['ugel'],
                        'provincia' => $row['provincia'],
                        'distrito' => $row['distrito'],
                        'cod_mod' => $row['cod_mod'],
                        'institucion_educativa' => $row['institucion_educativa'],
                        'estado' => $row['estado'],
                        'tabletas_programadas' => $row['tabletas_programadas'],
                        'cargadores_programadas' => $row['cargadores_programadas'],
                        'tabletas_chip' => $row['tabletas_chip'],
                        'tabletas_pecosa' => $row['tabletas_pecosa'],
                        'cargadores_pecosa' => $row['cargadores_pecosa'],
                        'tabletas_pecosa_siga' => $row['tabletas_pecosa_siga'],
                        'cargadores_pecosa_siga' => $row['cargadores_pecosa_siga'],
                        'tabletas_entregadas_sigema' => $row['tabletas_entregadas_sigema'],
                        'cargadores_entregadas_sigema' => $row['cargadores_entregadas_sigema'],
                        'tabletas_recepcionadas' => $row['tabletas_recepcionadas'],
                        'cargadores_recepcionadas' => $row['cargadores_recepcionadas'],
                        'tabletas_asignadas' => $row['tabletas_asignadas'],
                        'tabletas_asignadas_estudiantes' => $row['tabletas_asignadas_estudiantes'],
                        'tabletas_asignadas_docentes' => $row['tabletas_asignadas_docentes'],
                        'cargadores_asignadas' => $row['cargadores_asignadas'],
                        'cargadores_asignadas_estudiantes' => $row['cargadores_asignadas_estudiantes'],
                        'cargadores_asignadas_docentes' => $row['cargadores_asignadas_docentes'],
                        'tabletas_devueltas' => $row['tabletas_devueltas'],
                        'cargadores_devueltos' => $row['cargadores_devueltos'],
                        'tabletas_perdidas' => $row['tabletas_perdidas'],
                        'cargadores_perdidos' => $row['cargadores_perdidos'],
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
            DB::select('call par_pa_procesarImporTableta(?,?,?)', [$importacion->id, $tableta->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

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

    public function ListaImportada($importacion_id) //(Request $request, $importacion_id)
    {
        $data = ImporTableta::where('importacion_id', $importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = ImporMatriculaRepositorio::Listar_Por_Importacion_id($importacion_id);
        return  datatables()->of($padronWebLista)->toJson();
    }

    public function eliminar($id)
    {
        $tableta = Tableta::where('importacion_id', $id)->first();
        TabletaDetalle::where('tableta_id', $tableta->id)->delete();
        $tableta->delete();
        ImporTableta::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }

    public function download()
    {
        $name = 'SIAGIE MATRICULAS ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronSiagieExport, $name);
    }
}
