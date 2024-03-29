<?php

namespace App\Http\Controllers\Salud;

use App\Exports\ImporPadronSiagieExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporCensoDocente;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Salud\ImporPadronActas;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporPadronActasController extends Controller
{
    public static $FUENTE = ['pacto_1' => 36, 'pacto_2' => 0, 'pacto_3' => 0, 'pacto_4' => 0, 'pacto_5' => 0];
    public $fuente = 36;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        // $fuente = $this->fuente;
        $fuentes = FuenteImportacion::whereIn('id', [36])->get();
        return view('salud.ImporPadronActas.Importar', compact('fuentes'));
        //$mensaje = "";return view('educacion.ImporCensoDocente.Importar', compact('mensaje'));
    }

    public function exportar()
    {
        /* $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        $mensaje = "";
        return view('educacion.ImporPoblacion.Exportar', compact('mensaje', 'imp', 'mat')); */
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

    public function guardar(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        // $this->json_output(400, $rq->all());

        switch ($rq->fuente) {
            case 36:
                $existeMismaFecha = ImportacionRepositorio::Importacion_PE($rq->fechaActualizacion, $rq->fuente);
                if ($existeMismaFecha != null) {
                    $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
                    $this->json_output(400, $mensaje);
                }

                $existeMismaFecha = ImportacionRepositorio::Importacion_PR($rq->fechaActualizacion, $rq->fuente);
                if ($existeMismaFecha != null) {
                    $mensaje = "Error, Ya existe archivos procesados para la fecha de versión ingresada";
                    $this->json_output(400, $mensaje);
                }

                $this->validate($rq, ['file' => 'required|mimes:xls,xlsx']);
                $archivo = $rq->file('file');
                $array = (new tablaXImport)->toArray($archivo);

                if (count($array) != 1) {
                    $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
                }

                try {
                    foreach ($array as $value) {
                        foreach ($value as $celda => $row) {
                            if ($celda > 0) break;
                            $cadena =
                                $row['nombre_municipio'] .
                                $row['departamento'] .
                                $row['provincia'] .
                                $row['distrito'] .
                                $row['fecha_inicial'] .
                                $row['fecha_final'] .
                                $row['fecha_envio'] .
                                $row['dni_usuario_envio'] .
                                $row['primer_apellido'] .
                                $row['segundo_apellido'] .
                                $row['prenombres'] .
                                $row['numero_archivos'];
                        }
                    }
                } catch (Exception $e) {
                    $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
                    $this->json_output(403, $mensaje);
                }

                try {
                    $importacion = Importacion::Create([
                        'fuenteImportacion_id' => $rq->fuente, // valor predeterminado
                        'usuarioId_Crea' => auth()->user()->id,
                        'fechaActualizacion' => $rq->fechaActualizacion,
                        'estado' => 'PE'
                    ]);

                    foreach ($array as $key => $value) {
                        foreach ($value as $row) {
                            ImporPadronActas::Create([
                                'importacion_id' => $importacion->id,
                                'nombre_municipio' => $row['nombre_municipio'],
                                'departamento' => $row['departamento'],
                                'provincia' => $row['provincia'],
                                'distrito' => $row['distrito'],
                                'fecha_inicial' => $row['fecha_inicial'],
                                'fecha_final' => $row['fecha_final'],
                                'fecha_envio' => $row['fecha_envio'],
                                'dni_usuario_envio' => $row['dni_usuario_envio'],
                                'primer_apellido' => $row['primer_apellido'],
                                'segundo_apellido' => $row['segundo_apellido'],
                                'prenombres' => $row['prenombres'],
                                'numero_archivos' => $row['numero_archivos'],

                            ]);
                        }
                    }
                } catch (Exception $e) {
                    // $importacion->estado = 'EL';
                    // $importacion->save();

                    $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
                    $this->json_output(400, $mensaje);
                }

                // try {
                //     DB::select('call par_pa_procesarImporTableta(?,?,?)', [$importacion->id, $tableta->id, $importacion->usuarioId_Crea]);
                // } catch (Exception $e) {
                //     $importacion->estado = 'EL';
                //     $importacion->save();

                //     $mensaje = "Error al procesar la normalizacion de datos." . $e;
                //     $tipo = 'danger';
                //     $this->json_output(400, $mensaje);
                // }

                $mensaje = "Archivo excel subido y Procesado correctamente .";
                $this->json_output(200, $mensaje, '');
                break;

            default:
                # code...
                break;
        }
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

    public function ListaImportada($importacion_id) //(Request $request, $importacion_id)
    {
        $data = ImporCensoDocente::where('importacion_id', $importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    /* public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = ImporMatriculaRepositorio::Listar_Por_Importacion_id($importacion_id);
        return  datatables()->of($padronWebLista)->toJson();
    } */

    public function eliminar($id)
    {
        //$tableta = Tableta::where('importacion_id', $id)->first();
        //TabletaDetalle::where('tableta_id', $tableta->id)->delete();
        //$tableta->delete();
        ImporCensoDocente::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }

    public function download()
    {
        $name = 'SIAGIE MATRICULAS ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronSiagieExport, $name);
    }
}
