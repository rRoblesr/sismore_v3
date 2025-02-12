<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\PoblacionDetalle;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\ImporPadronEstablecimiento;
use App\Models\Salud\ImporPadronPrograma;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Utilities\Utilitario;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ImporPadronProgramaController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public $fuente = 49;
    public static $FUENTE = 49;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* metodo para la vista del formulario para importar */
    public function importar()
    {
        $mensaje = "";
        return view('salud.ImporPadronPrograma.Importar', compact('mensaje'));
    }

    /* metodo para tener una salida de respuesta de la carga del excel */
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

    /*  metodo que carga el excel y ejecuta un procedimiento almacenado*/
    public function guardar(Request $rq)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        // if (ImportacionRepositorio::Importacion_PE($rq->fechaActualizacion, $this->fuente) !== null) {
        //     return $this->json_output(400, "Error, ya existe un archivo pendiente de aprobación para la fecha ingresada");
        // }

        // if (ImportacionRepositorio::Importacion_PR($rq->fechaActualizacion, $this->fuente) !== null) {
        //     return $this->json_output(400, "Error, ya existe un archivo procesado para la fecha ingresada");
        // }

        $this->validate($rq, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $rq->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        $encabezadosEsperados = [
            'servicio',
            'anio',
            'mes',
            'tipo_doc',
            'num_doc_m',
            'ape_pat_m',
            'ape_mat_m',
            'nombre_m',
            'sexo',
            'fec_nac_m',
            'telefono',
            'direccion',
            'referencia',
            'ubigeo_distrito',
            'ubigeo_ccpp',
            'latitud',
            'longitud',
            'num_doc_a',
            'ape_pat_a',
            'ape_mat_a',
            'nombre_a',
        ];

        $encabezadosArchivo = array_keys($array[0][0]);

        $faltantes = array_diff($encabezadosEsperados, $encabezadosArchivo);
        if (!empty($faltantes)) {
            return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado. Faltan columnas esperadas.', $faltantes);
        }

        try {
            DB::beginTransaction();

            $importacion = Importacion::create([
                'fuenteImportacion_id' => $this->fuente,
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $rq->fechaActualizacion,
                'estado' => 'PR'
            ]);

            // $distritos = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=6')->pluck('id', 'codigo');
            // $provincias = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=4')->pluck('id', 'codigo');

            $batchSize = 500;
            $dataBatch = [];

            foreach ($array[0] as $row) {
                $dataBatch[] = [
                    'importacion_id' => $importacion->id,
                    'programa' => $rq->programa,
                    'servicio' => $row['servicio'],
                    'anio' => $row['anio'],
                    'mes' => $row['mes'],
                    'tipo_doc' => $row['tipo_doc'],
                    'num_doc_m' => $row['num_doc_m'],
                    'ape_pat_m' => $row['ape_pat_m'],
                    'ape_mat_m' => $row['ape_mat_m'],
                    'nombre_m' => $row['nombre_m'],
                    'sexo' => $row['sexo'] == 'M' ? 0 : 1,
                    'fec_nac_m' => Utilitario::textDateToMySQL($row['fec_nac_m']),
                    'telefono' => $row['telefono'],
                    'direccion' => $row['direccion'],
                    'referencia' => $row['referencia'],
                    'ubigeo_distrito' => $row['ubigeo_distrito'],
                    'ubigeo_ccpp' => $row['ubigeo_ccpp'],
                    'latitud' => $row['latitud'],
                    'longitud' => $row['longitud'],
                    'num_doc_a' => $row['num_doc_a'],
                    'ape_pat_a' => $row['ape_pat_a'],
                    'ape_mat_a' => $row['ape_mat_a'],
                    'nombre_a' => $row['nombre_a'],
                ];

                if (count($dataBatch) >= $batchSize) {
                    ImporPadronPrograma::insert($dataBatch);
                    $dataBatch = [];
                }
            }

            if (!empty($dataBatch)) {
                ImporPadronPrograma::insert($dataBatch);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            // dd($dataBatch);
            $importacion->estado = 'PE';
            $importacion->save();

            return $this->json_output(400, "Error en la carga de datos: " . $e->getMessage());
        }

        // try {
        //     DB::select('call sal_pa_procesarControlCalidadColumnas(?)', [$importacion->id]);
        // } catch (Exception $e) {
        //     // Si ocurre un error, actualizar el estado a 'PE' (pendiente) si es necesario
        //     $importacion->estado = 'PE';
        //     $importacion->save();

        //     $mensaje = "Error al procesar la normalizacion de datos sal_pa_procesarControlCalidadColumnas. " . $e->getMessage();
        //     return $this->json_output(400, $mensaje);
        // }

        // try {
        //     DB::select('call sal_pa_procesarPadronEstablecimiento(?,?)', [$importacion->id, auth()->user()->id]);
        // } catch (Exception $e) {
        //     // Si ocurre un error, actualizar el estado a 'PE' (pendiente) si es necesario
        //     $importacion->estado = 'PE';
        //     $importacion->save();

        //     $mensaje = "Error al procesar la normalizacion de datos sal_pa_procesarCalidadReporte. " . $e->getMessage();
        //     return $this->json_output(400, $mensaje);
        // }

        $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
    }

    /* metodo para listar las importaciones */
    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $programas = ['CUNAMAS', 'JUNTOS'];
        // $conteo=ImporPadronPrograma::select('importacion_id',DB::raw('count(*) as conteo'))->groupBy('importacion_id')->get();
        $conteo = ImporPadronPrograma::select('importacion_id', DB::raw('count(*) as conteo'))->groupBy('importacion_id')->pluck('conteo', 'importacion_id');
        $query = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
        $data = [];
        foreach ($query as $key => $value) {
            $ent = Entidad::find($value->entidad);
            $padron = ImporPadronPrograma::where('importacion_id', $value->id)->first();
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || in_array(session('perfil_administrador_id'), [3, 8, 9, 10, 11]))
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $programas[$padron->programa - 1] ?? 'No Definido',
                $nom . ' ' . $value->capellido1,
                $ent ? $ent->abreviado : '',
                date("d/m/Y", strtotime($value->created_at)),
                $value->estado == "PR" ? "PROCESADO" : "PENDIENTE",
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "conteo" => $conteo
        );
        return response()->json($result);
    }

    /* metodo para cargar una importacion especifica */
    public function ListaImportada(Request $rq)
    {
        $data = ImporPadronPrograma::all();
        return DataTables::of($data)->make(true);
    }

    /* metodo para eliminar una importacion */
    public function eliminar($id)
    {
        ImporPadronPrograma::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }
}
