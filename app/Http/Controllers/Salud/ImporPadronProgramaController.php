<?php

namespace App\Http\Controllers\Salud;

use App\Exports\Salud\PadronProgramaErroresExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Salud\ImporPadronPrograma;
use App\Models\Salud\PadronProgramaB;
use App\Models\Salud\PadronProgramaH;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Utilities\Utilitario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
    function json_output($status = 200, $msg = 'OK!!', $importacion_id = null, $data = null)
    {
        header('Content-Type:application/json');
        echo json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'importacion_id' => $importacion_id
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
            'tipo_doc_menor',
            'num_doc_menor',
            'ape_pat_menor',
            'ape_mat_menor',
            'nombre_menor',
            'sexo_menor',
            'fec_nac_menor',
            'telefono',
            'direccion',
            'referencia',
            'ubigeo_distrito',
            'ubigeo_ccpp',
            'latitud',
            'longitud',
            'num_doc_apoderado',
            'ape_pat_apoderado',
            'ape_mat_apoderado',
            'nombre_apoderado',
        ];

        $encabezadosArchivo = array_keys($array[0][0]);

        $faltantes = array_diff($encabezadosEsperados, $encabezadosArchivo);
        if (!empty($faltantes)) {
            return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado. Faltan columnas esperadas.', $faltantes);
        }

        // ***********************************************
        // $tamaniosMaximos = [
        //     'servicio' => 10,
        //     'anio' => 4,
        //     'mes' => 2,
        //     'tipo_doc_menor' => 40,
        //     'num_doc_menor' => 8,
        //     'ape_pat_menor' => 30,
        //     'ape_mat_menor' => 30,
        //     'nombre_menor' => 40,
        //     'sexo_menor' => 1,
        //     'fec_nac_menor' => 10,
        //     'telefono' => 9,
        //     'direccion' => 300,
        //     'referencia' => 300,
        //     'ubigeo_distrito' => 6,
        //     'ubigeo_ccpp' => 10,
        //     'latitud' => 19,
        //     'longitud' => 18,
        //     'num_doc_apoderado' => 12,
        //     'ape_pat_apoderado' => 25,
        //     'ape_mat_apoderado' => 35,
        //     'nombre_apoderado' => 40,
        // ];

        // $errores = [];

        // foreach ($array[0] as $filaIndex => $fila) {
        //     foreach ($fila as $columna => $valor) {
        //         if (isset($tamaniosMaximos[$columna])) {
        //             $longitudValor = mb_strlen(trim($valor)); // Evita espacios innecesarios

        //             if ($longitudValor > $tamaniosMaximos[$columna]) {
        //                 $errores[] = "Error en fila " . ($filaIndex + 1) . ", columna '$columna': el tamaño máximo es " . $tamaniosMaximos[$columna] . ", pero se encontró uno de $longitudValor.";
        //             }
        //         }
        //     }
        // }

        // // Si hay errores, devolverlos
        // if (!empty($errores)) {
        //     return $this->json_output(400, 'Error: Algunas columnas exceden el tamaño permitido.', $errores);
        // }


        // ***********************************************

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
                    'tipo_doc_m' => $row['tipo_doc_menor'],
                    'num_doc_m' => $row['num_doc_menor'],
                    'ape_pat_m' => $row['ape_pat_menor'],
                    'ape_mat_m' => $row['ape_mat_menor'],
                    'nombre_m' => $row['nombre_menor'],
                    'sexo_m' => $row['sexo_menor'], // == 'M' ? 0 : 1,
                    'fec_nac_m' => $row['fec_nac_menor'], //Utilitario::textDateToMySQL($row['fec_nac_menor']),
                    'telefono' => $row['telefono'],
                    'direccion' => $row['direccion'],
                    'referencia' => $row['referencia'],
                    'ubigeo_distrito' => $row['ubigeo_distrito'],
                    'ubigeo_ccpp' => $row['ubigeo_ccpp'],
                    'latitud' => $row['latitud'],
                    'longitud' => $row['longitud'],
                    'num_doc_a' => $row['num_doc_apoderado'],
                    'ape_pat_a' => $row['ape_pat_apoderado'],
                    'ape_mat_a' => $row['ape_mat_apoderado'],
                    'nombre_a' => $row['nombre_apoderado'],
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

            return $this->json_output(400, "Error en la carga de datos: " . $e->getMessage(), $importacion->id);
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

        try {
            DB::select('call sal_pa_procesarPadronProgramasx(?)', [$importacion->id]);
        } catch (Exception $e) {
            // Si ocurre un error, actualizar el estado a 'PE' (pendiente) si es necesario
            $importacion->estado = 'PE';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos sal_pa_procesarPadronProgramas. " . $e->getMessage();
            return $this->json_output(400, $mensaje, $importacion->id);
        }

        $this->json_output(200, "Archivo Excel subido y procesado correctamente.", $importacion->id);
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
            $registros = PadronProgramaB::where('importacion_id', $value->id)->count();
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            $btn = '';

            $btn .= '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-success btn-xs" title="VER LISTA DE REGISTROS ACEPTADOS"><i class="fa fa-eye"></i> </button>&nbsp;';
            $btn .= '<button type="button" onclick="monitor2(' . $value->id . ')" class="btn btn-warning btn-xs" title="VER LISTA DE REGISTROS ACEPTADOS"><i class="fa fa-eye"></i> </button>&nbsp;';
            // $btn .= '<a href="' . route('imporpadronprograma.exportar.padron', ['importacion_id' => $value->id]) . '" class="btn btn-warning btn-xs" title="DESCARGAR LISTA DE REGISTROS CON ERRORES"><i class="fa fa-download"></i></a>&nbsp;';
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || in_array(session('perfil_administrador_id'), [3, 8, 9, 10, 11])) {
                $btn .= '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs" title="ELIMINAR REGISTRO"><i class="fa fa-trash"></i> </button>&nbsp;';
            }

            $data[] = array(
                $key + 1,
                $nom . ' ' . $value->capellido1,
                $programas[$padron->programa - 1] ?? 'No Definido',
                $padron->servicio,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $registros,
                // date("d/m/Y", strtotime($value->created_at)),
                $value->estado == "PR" ? "PROCESADO" : "PENDIENTE",
                $btn,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            // "conteo" => $conteo
        );
        return response()->json($result);
    }

    /* metodo para cargar una importacion especifica */
    public function ListaImportada(Request $rq)
    {
        $data = PadronProgramaB::from('sal_padron_programa_b as b')->where('b.importacion_id', $rq->importacion_id)
            ->select(
                'h.programa',
                'h.servicio',
                'h.anio',
                'h.mes',
                'b.tipo_doc',
                'b.num_doc_m',
                'b.ape_pat_m',
                'b.ape_mat_m',
                'b.nombre_m',
                DB::raw('IF(b.sexo=1,"M","F") as sexo'),
                'b.fec_nac_m',
                'b.telefono',
                'b.direccion',
                'b.referencia',
                'b.ubigeo',
                'b.ubigeo_ccpp',
                'b.latitud',
                'b.longitud',
                'b.num_doc_a',
                'b.ape_pat_a',
                'b.ape_mat_a',
                'b.nombre_a',
            )
            ->join('sal_padron_programa_h as h', 'h.importacion_id', '=', 'b.importacion_id')
            ->get();
        return DataTables::of($data)->make(true);
    }

    /* metodo para cargar una importacion especifica */
    public function ListaImportada2(Request $rq)
    {
        $data = ImporPadronPrograma::where('importacion_id', $rq->importacion_id)
            ->select(
                'servicio',
                'anio',
                'mes',
                'tipo_doc_m',
                'num_doc_m',
                'ape_pat_m',
                'ape_mat_m',
                'nombre_m',
                'sexo_m',
                'fec_nac_m',
                'edad_m',
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
            )
            ->where('estado_guardado', '0')
            ->get();
        return DataTables::of($data)->make(true);
    }

    /* metodo para eliminar una importacion */
    public function eliminar($id)
    {
        ImporPadronPrograma::where('importacion_id', $id)->delete();
        PadronProgramaB::where('importacion_id', $id)->delete();
        PadronProgramaH::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }

    public function exportarPadron(Request $request)
    {
        $importacion_id = $request->get('importacion_id');
        if (!$importacion_id) {
            return redirect()->back()->with('error', 'Falta el parámetro importacion_id.');
        }

        $filename = 'padron_export_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new PadronProgramaErroresExport($importacion_id), $filename);
    }

    public function errores($importacion)
    {
        $error = ImporPadronPrograma::where('importacion_id', $importacion)->where('estado_guardado','0')->count();
        $ok = PadronProgramaB::where('importacion_id', $importacion)->count();
        return response()->json(array('status' => true, 'error' => $error, 'ok' => $ok, 'total' => $error + $ok));
    }
}
