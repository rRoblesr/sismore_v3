<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseIngresos;
use App\Models\Presupuesto\BaseIngresosDetalle;
use App\Models\Administracion\Entidad;
use App\Models\Presupuesto\ImporIngresos;
use App\Repositories\Educacion\ImporGastosRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporIngresosController extends Controller
{
    public $fuente = 15;
    public static $FUENTE = 15;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporIngresos.Importar', compact('mensaje', 'fuente'));
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
        /* $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo); */

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, 15);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, 15);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos procesados para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        //Excel::import(new ImporGastosImport, $archivo);//
        $array = (new tablaXImport)->toArray($archivo);

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        $cadena = '';
        try {
            foreach ($array as $value) {
                foreach ($value as $key => $row) {
                    if ($key > 0) break;
                    $cadena =  $cadena .
                        $row['anio'] .
                        $row['mes'] .
                        $row['cod_niv_gob'] .
                        $row['nivel_gobierno'] .
                        $row['cod_sector'] .
                        $row['sector'] .
                        $row['cod_pliego'] .
                        $row['pliego'] .
                        $row['cod_ubigeo'] .
                        $row['sec_ejec'] .
                        $row['cod_ue'] .
                        $row['unidad_ejecutora'] .
                        $row['cod_fue_fin'] .
                        $row['fuente_financiamiento'] .
                        $row['cod_rub'] .
                        $row['rubro'] .
                        $row['cod_tipo_rec'] .
                        $row['tipo_recurso'] .
                        $row['cod_tipo_trans'] .
                        $row['cod_gen'] .
                        $row['generica'] .
                        $row['cod_subgen'] .
                        $row['subgenerica'] .
                        $row['cod_subgen_det'] .
                        $row['subgenerica_detalle'] .
                        $row['cod_esp'] .
                        $row['especifica'] .
                        $row['cod_esp_det'] .
                        $row['especifica_detalle'] .
                        $row['pia'] .
                        $row['pim'] .
                        $row['recaudado'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto." . $e->getMessage();
            $this->json_output(403, $mensaje);
        }

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => 15, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                // 'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                // 'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $gastos = ImporIngresos::Create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['anio'],
                        'mes' => $row['mes'],
                        'cod_tipo_gob' => $row['cod_niv_gob'],
                        'tipo_gobierno' => $row['nivel_gobierno'],
                        'cod_sector' => $row['cod_sector'],
                        'sector' => $row['sector'],
                        'cod_pliego' => $row['cod_pliego'],
                        'pliego' => $row['pliego'],
                        'cod_ubigeo' => $row['cod_ubigeo'],
                        'sec_ejec' => $row['sec_ejec'],
                        'cod_ue' => $row['cod_ue'],
                        'unidad_ejecutora' => $row['unidad_ejecutora'],
                        'cod_fue_fin' => $row['cod_fue_fin'],
                        'fuente_financiamiento' => $row['fuente_financiamiento'],
                        'cod_rub' => $row['cod_rub'],
                        'rubro' => $row['rubro'],
                        'cod_tipo_rec' => $row['cod_tipo_rec'],
                        'tipo_recurso' => $row['tipo_recurso'],
                        'cod_tipo_trans' => $row['cod_tipo_trans'],
                        'cod_gen' => $row['cod_gen'],
                        'generica' => $row['generica'],
                        'cod_subgen' => $row['cod_subgen'],
                        'subgenerica' => $row['subgenerica'],
                        'cod_subgen_det' => $row['cod_subgen_det'],
                        'subgenerica_detalle' => $row['subgenerica_detalle'],
                        'cod_esp' => $row['cod_esp'],
                        'especifica' => $row['especifica'],
                        'cod_esp_det' => $row['cod_esp_det'],
                        'especifica_detalle' => $row['especifica_detalle'],
                        'pia' => $row['pia'],
                        'pim' => $row['pim'],
                        'recaudado' => $row['recaudado']
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
            $procesar = DB::select('call pres_pa_procesarImporIngresos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
    }

    public function importarUpdate(Request $request, $importacion_id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
            'fechaActualizacion' => 'required|date',
        ]);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        try {
            $importacion = Importacion::find($importacion_id);
            if (!$importacion) {
                $this->json_output(400, "Importación no encontrada");
            }

            // Eliminar registros anteriores de esta importación
            ImporIngresos::where('importacion_id', $importacion_id)->delete();

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    ImporIngresos::Create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['anio'],
                        'mes' => $row['mes'],
                        'cod_tipo_gob' => $row['cod_niv_gob'],
                        'tipo_gobierno' => $row['nivel_gobierno'],
                        'cod_sector' => $row['cod_sector'],
                        'sector' => $row['sector'],
                        'cod_pliego' => $row['cod_pliego'],
                        'pliego' => $row['pliego'],
                        'cod_ubigeo' => $row['cod_ubigeo'],
                        'sec_ejec' => $row['sec_ejec'],
                        'cod_ue' => $row['cod_ue'],
                        'unidad_ejecutora' => $row['unidad_ejecutora'],
                        'cod_fue_fin' => $row['cod_fue_fin'],
                        'fuente_financiamiento' => $row['fuente_financiamiento'],
                        'cod_rub' => $row['cod_rub'],
                        'rubro' => $row['rubro'],
                        'cod_tipo_rec' => $row['cod_tipo_rec'],
                        'tipo_recurso' => $row['tipo_recurso'],
                        'cod_tipo_trans' => $row['cod_tipo_trans'],
                        'cod_gen' => $row['cod_gen'],
                        'generica' => $row['generica'],
                        'cod_subgen' => $row['cod_subgen'],
                        'subgenerica' => $row['subgenerica'],
                        'cod_subgen_det' => $row['cod_subgen_det'],
                        'subgenerica_detalle' => $row['subgenerica_detalle'],
                        'cod_esp' => $row['cod_esp'],
                        'especifica' => $row['especifica'],
                        'cod_esp_det' => $row['cod_esp_det'],
                        'especifica_detalle' => $row['especifica_detalle'],
                        'pia' => $row['pia'],
                        'pim' => $row['pim'],
                        'recaudado' => $row['recaudado']
                    ]);
                }
            }

            // Actualizar fecha de actualización
            $importacion->fechaActualizacion = $request->fechaActualizacion;
            $importacion->usuarioId_Crea = auth()->user()->id;
            $importacion->updated_at = now();
            $importacion->save();
        } catch (Exception $e) {
            $mensaje = "Error en la actualización de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call pres_pa_procesarImporIngresos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $mensaje = "Error al procesar la normalizacion de datos." . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call pres_pa_procesar_cubo_ingreso(?)', [$importacion->id]);
        } catch (Exception $e) {
            $mensaje = "Error al procesar la normalizacion de datos." . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        $mensaje = "Archivo excel actualizado y procesado correctamente.";
        $this->json_output(200, $mensaje, '');
    }


    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = ImportacionRepositorio::Listar_FuenteTodos('15');
        $data = [];
        foreach ($query as $key => $value) {
            $ent = Entidad::find($value->entidad);
            $usuario = '';
            $area = '';

            if (strlen(trim($value->cnombre)) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
                $ape = '';
                if (strlen($value->capellido1) > 0) {
                    $yy = explode(' ', $value->capellido1 . ' ' . $value->capellido2);
                    $ape = $yy[0];
                }
                $usuario = $nom . ' ' . $ape;
                $area = $ent ? $ent->abreviado : '';
            } else {
                $usuario = 'SERVIDOR';
                $area = 'OTI';
            }

            $boton = '';
            $boton .= '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs mr-1" title="Ver detalle"><i class="fa fa-eye"></i></button>';
            $boton .= '<a href="' . route('imporingresos.exportar', $value->id) . '" class="btn btn-success btn-xs mr-1" title="Exportar a CSV"><i class="fa fa-file-csv"></i></a>';
            
            if (auth()->user()->id == 49) {
                $boton .= '<button type="button" onclick="abrirProcesos(' . $value->id . ')" class="btn btn-info btn-xs mr-1" title="Procesar"><i class="fa fa-cogs"></i></button>';
            }
            
            $boton .= '<button type="button" onclick="abrirModalActualizar(' . $value->id . ', \'' . (date('Y-m-d', strtotime($value->fechaActualizacion))) . '\')" class="btn btn-warning btn-xs mr-1" title="Actualizar Archivo"><i class="fa fa-upload"></i></button>';

            if (auth()->user()->id == 49) {
                $boton .= '<button type="button" onclick="geteliminar(' . $value->id . ')" id="eliminar' . $value->id . '" class="btn btn-danger btn-xs mr-1" title="Eliminar"><i class="fa fa-trash"></i></button>';
            }
            $data[] = array(
                $key + 1,
                'INGRESO',
                (date('H:i:s', strtotime($value->fechaActualizacion)) == '00:00:00' ? date('d/m/Y', strtotime($value->fechaActualizacion)) : date('d/m/Y H:i:s', strtotime($value->fechaActualizacion))),
                /* $value->fuente . $value->id, */
                $usuario,
                $area,
                date("d/m/Y H:i:s", strtotime($value->updated_at)),
                /* $value->comentario, */
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
                $boton,
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

    public function ListaImportada(Request $request, $importacion_id)
    {
        $data = ImporIngresos::where('importacion_id', $importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function exportarExcel($id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Ingreso_Presupuestal_" . $id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = [
            'anio',
            'mes',
            'cod_tipo_gob',
            'tipo_gobierno',
            'cod_sector',
            'sector',
            'cod_pliego',
            'pliego',
            'cod_ubigeo',
            'sec_ejec',
            'cod_ue',
            'unidad_ejecutora',
            'cod_fue_fin',
            'fuente_financiamiento',
            'cod_rub',
            'rubro',
            'cod_tipo_rec',
            'tipo_recurso',
            'cod_tipo_trans',
            'cod_gen',
            'generica',
            'cod_subgen',
            'subgenerica',
            'cod_subgen_det',
            'subgenerica_detalle',
            'cod_esp',
            'especifica',
            'cod_esp_det',
            'especifica_detalle',
            'pia',
            'pim',
            'recaudado'
        ];

        $headings = [
            'ANIO',
            'MES',
            'COD_TIPO_GOB',
            'TIPO_GOBIERNO',
            'COD_SECTOR',
            'SECTOR',
            'COD_PLIEGO',
            'PLIEGO',
            'COD_UBIGEO',
            'SEC_EJEC',
            'COD_UE',
            'UNIDAD_EJECUTORA',
            'COD_FUE_FIN',
            'FUENTE_FINANCIAMIENTO',
            'COD_RUB',
            'RUBRO',
            'COD_TIPO_REC',
            'TIPO_RECURSO',
            'COD_TIPO_TRANS',
            'COD_GEN',
            'GENERICA',
            'COD_SUBGEN',
            'SUBGENERICA',
            'COD_SUBGEN_DET',
            'SUBGENERICA_DETALLE',
            'COD_ESP',
            'ESPECIFICA',
            'COD_ESP_DET',
            'ESPECIFICA_DETALLE',
            'PIA',
            'PIM',
            'RECAUDADO'
        ];

        $callback = function () use ($id, $columns, $headings) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel compatibility with UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $headings);

            ImporIngresos::where('importacion_id', $id)
                ->select($columns)
                ->chunk(2000, function ($ingresos) use ($file) {
                    foreach ($ingresos as $ingreso) {
                        fputcsv($file, $ingreso->toArray());
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function procesarBase($importacion_id)
    {
        try {
            DB::select('call pres_pa_procesarImporIngresos(?,?)', [$importacion_id, null]);
            return response()->json(['status' => true, 'msg' => 'Base de ingresos procesada correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Error al procesar base de ingresos: ' . $e->getMessage()], 500);
        }
    }

    public function procesarCubo($importacion_id)
    {
        try {
            DB::select('call pres_pa_procesar_cubo_ingreso(?)', [$importacion_id]);
            return response()->json(['status' => true, 'msg' => 'Cubo de ingresos procesado correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Error al procesar cubo de ingresos: ' . $e->getMessage()], 500);
        }
    }

    public function verificarBase($importacion_id)
    {
        $base = BaseIngresos::where('importacion_id', $importacion_id)->first();
        if (!$base) {
            return response()->json([
                'status' => true,
                'msg' => 'Sin base generada aún.',
                'base' => 0,
                'detalle' => 0,
                'anio' => null,
            ]);
        }
        $detalle = BaseIngresosDetalle::where('baseingresos_id', $base->id)->count();
        return response()->json([
            'status' => true,
            'msg' => 'Conteo obtenido.',
            'base' => 1,
            'detalle' => $detalle,
            'anio' => (int)$base->anio,
        ]);
    }

    public function verificarCubo($importacion_id)
    {
        $anios = ImporIngresos::where('importacion_id', $importacion_id)
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($anios->isEmpty()) {
            $anioAlt = Importacion::where('id', $importacion_id)->value(DB::raw('YEAR(fechaActualizacion)'));
            if ($anioAlt) {
                $anios = collect([(int)$anioAlt]);
            }
        }

        if ($anios->isEmpty()) {
            return response()->json([
                'status' => true,
                'msg' => 'No se identificó el año de la importación.',
                'anios' => [],
                'total' => 0,
            ]);
        }

        $detalles = [];
        $total = 0;
        foreach ($anios as $ax) {
            $count = DB::table('pres_cubo_ingreso')->where('anio', (int)$ax)->count();
            $detalles[] = ['anio' => (int)$ax, 'registros' => (int)$count];
            $total += $count;
        }

        return response()->json([
            'status' => true,
            'msg' => 'Conteo obtenido.',
            'anios' => $detalles,
            'total' => (int)$total,
        ]);
    }

    public function descargarBaseProcesada($importacion_id)
    {
        $base = BaseIngresos::where('importacion_id', $importacion_id)->first();
        if (!$base) {
            abort(404, 'No existe base procesada para esta importación.');
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Base_Ingresos_Procesada_" . $importacion_id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'baseingresos_id',
            'unidadejecutora_id',
            'ubigeo_id',
            'recursosingreso_id',
            'especificadetalle_id',
            'pia',
            'pim',
            'recaudado',
        ];

        $headings = [
            'BASE_ID',
            'UNIDAD_EJECUTORA_ID',
            'UBIGEO_ID',
            'RECURSOS_INGRESO_ID',
            'ESPECIFICA_DETALLE_ID',
            'PIA',
            'PIM',
            'RECAUDADO',
        ];

        $callback = function () use ($base, $columns, $headings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headings);

            BaseIngresosDetalle::where('baseingresos_id', $base->id)
                ->select($columns)
                ->chunk(2000, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        fputcsv($file, $row->toArray());
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function descargarCuboProcesado($importacion_id)
    {
        $anios = ImporIngresos::where('importacion_id', $importacion_id)
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($anios->isEmpty()) {
            $anioAlt = Importacion::where('id', $importacion_id)->value(DB::raw('YEAR(fechaActualizacion)'));
            if ($anioAlt) {
                $anios = collect([(int)$anioAlt]);
            }
        }

        if ($anios->isEmpty()) {
            abort(404, 'No se identificó el año asociado a la importación.');
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Cubo_Ingresos_Procesado_" . $importacion_id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'anio',
            'mes',
            'unidadejecutora_id',
            'fuentefinanciamiento_id',
            'rubro_id',
            'pia',
            'pim',
            'certificado',
            'compromiso_anual',
            'devengado',
            'girado',
            'recaudado',
        ];

        $headings = [
            'ANIO',
            'MES',
            'UNIDAD_EJECUTORA_ID',
            'FUENTE_FINANCIAMIENTO_ID',
            'RUBRO_ID',
            'PIA',
            'PIM',
            'CERTIFICADO',
            'COMPROMISO_ANUAL',
            'DEVENGADO',
            'GIRADO',
            'RECAUDADO',
        ];

        $aniosArray = $anios->map(function ($a) {
            return (int)$a;
        })->toArray();

        $callback = function () use ($aniosArray, $columns, $headings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headings);

            DB::table('pres_cubo_ingreso')
                ->whereIn('anio', $aniosArray)
                ->select($columns)
                ->orderBy('anio')
                ->orderBy('mes')
                ->chunk(2000, function ($rows) use ($file) {
                    foreach ($rows as $row) {
                        fputcsv($file, (array)$row);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function eliminar($id)
    {
        try {
            DB::beginTransaction();

            $importacion = Importacion::find($id);
            if (!$importacion) {
                return response()->json(['status' => false, 'message' => 'Importación no encontrada'], 404);
            }

            $bi = BaseIngresos::where('importacion_id', $id)->first();
            ImporIngresos::where('importacion_id', $id)->delete();
            if ($bi) {
                BaseIngresosDetalle::where('baseingresos_id', $bi->id)->delete();
                $bi->delete();
            }
            $importacion->delete();

            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }

    public function ejecutarETL()
    {
        # $batPath = 'C:\\xampp\\htdocs\\aaapanel007\\sismore_v5\\ejecutar_etl_web.bat';
        $batPath = base_path('ejecutar_etl_web.bat');

        if (!file_exists($batPath)) {
            return response()->json(['status' => false, 'msg' => 'El archivo .bat no existe en la ruta especificada: ' . $batPath]);
        }

        try {
            $output = [];
            $return_var = 0;
            exec("cmd /c \"$batPath\"", $output, $return_var);

            if ($return_var === 0) {
                return response()->json(['status' => true, 'msg' => 'Proceso ejecutado correctamente.', 'output' => $output]);
            } else {
                return response()->json(['status' => false, 'msg' => 'Error al ejecutar el proceso.', 'output' => $output]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Excepción: ' . $e->getMessage()]);
        }
    }
}
