<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Imports\ImporGastosImport;
use App\Exports\ImporGastosExport;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseGastosDetalle;
use App\Models\Administracion\Entidad;
use App\Models\Presupuesto\ImporGastos;
use App\Repositories\Presupuesto\ImporGastosRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporGastosController extends Controller
{
    public $fuente = 13;
    public static $FUENTE = 13;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importarActualizar(Request $request)
    {
        if ($request->input('tipo_archivo') === 'xlsx') {
            return $this->importarActualizarExcel($request);
        }
        return $this->importarActualizarCSV($request);
    }

    public function importarActualizarCSV(Request $request)
    {
        ini_set('memory_limit', '-1'); // Evitar uso de memoria ilimitada para prevenir OOM
        set_time_limit(0); // Aumentar tiempo de ejecución a infinito para archivos grandes

        // OPTIMIZACIÓN DE MEMORIA: Desactivar el registro de consultas SQL
        DB::disableQueryLog();

        // Validaciones
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt',
            'importacion_id' => 'required|exists:par_importacion,id',
            'fechaActualizacion' => 'required'
        ]);

        $importacion_id = $request->importacion_id;
        $fechaActualizacion = $request->fechaActualizacion;

        // Verificar si existe la importación y pertenece a la fuente correcta
        $importacion = Importacion::where('id', $importacion_id)
            ->where('fuenteImportacion_id', $this->fuente)
            ->first();

        if (!$importacion) {
            return $this->json_output(400, 'Importación no encontrada o no válida.');
        }

        // Verificar si la nueva fecha ya existe en otra importación (excluyendo la actual)
        $existeMismaFecha = Importacion::where('fechaActualizacion', $fechaActualizacion)
            ->where('fuenteImportacion_id', $this->fuente)
            ->where('id', '!=', $importacion_id)
            ->where('estado', '!=', 'EL')
            ->exists();

        if ($existeMismaFecha) {
            return $this->json_output(400, 'Ya existe otra importación con la misma fecha de versión.');
        }

        // Procesar archivo
        $archivo = $request->file('file');

        // Validación básica de archivo vacío
        if ($archivo->getSize() == 0) {
            return $this->json_output(400, 'El archivo CSV está vacío.');
        }

        try {
            // 1. Eliminar datos anteriores (Optimizado para evitar error de bloqueo de tablas - Error 1206)
            $baseGastosIds = BaseGastos::where('importacion_id', $importacion_id)->pluck('id');

            // Paso 2: Eliminar Detalle en lotes pequeños
            foreach ($baseGastosIds->chunk(200) as $chunk) {
                BaseGastosDetalle::whereIn('basegastos_id', $chunk)->delete();
            }

            // Paso 3: Eliminar Base en lotes pequeños
            foreach ($baseGastosIds->chunk(200) as $chunk) {
                BaseGastos::whereIn('id', $chunk)->delete();
            }

            // Eliminar tabla temporal también por lotes
            $imporGastosIds = ImporGastos::where('importacion_id', $importacion_id)->pluck('id');
            foreach ($imporGastosIds->chunk(500) as $chunk) {
                ImporGastos::whereIn('id', $chunk)->delete();
            }

            // 2. Actualizar fecha de importación
            DB::transaction(function () use ($importacion, $fechaActualizacion) {
                $importacion->fechaActualizacion = $fechaActualizacion;
                $importacion->usuarioId_Crea = auth()->user()->id;
                $importacion->save();
            });

            // 3. Procesar nuevos datos usando Chunk Reading
            try {
                // Configurar separador CSV personalizado
                $separator = $request->input('csv_separator', ',');
                Config::set('excel.imports.csv.delimiter', $separator);

                Excel::import(new ImporGastosImport($importacion), $archivo);

                // VERIFICACIÓN DE DATOS IMPORTADOS
                $cantidadRegistros = ImporGastos::where('importacion_id', $importacion->id)->count();

                if ($cantidadRegistros == 0) {
                    return $this->json_output(400, 'Error: No se importaron registros. Verifique si el archivo está vacío o si el SEPARADOR CSV es correcto.');
                }
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                return $this->json_output(400, 'Error de validación en el archivo CSV: ' . $e->getMessage());
            } catch (Exception $e) {
                return $this->json_output(400, 'Error al leer el archivo CSV: ' . $e->getMessage());
            }

            $importacion->estado = 'PE'; // Pendiente de procesamiento
            $importacion->save();

            // Procesar normalización (ETL)
            try {
                $procesar = DB::select('call pres_pa_procesarImporGastos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
            } catch (Exception $e) {
                $importacion->estado = 'EL';
                $importacion->save();
                return $this->json_output(400, "Error al procesar la normalizacion de datos (ImporGastos): " . $e->getMessage());
            }

            try {
                $procesar = DB::select('call pres_pa_procesar_cubo_gasto(?)', [$importacion->id]);
            } catch (Exception $e) {
                return $this->json_output(400, "Error al procesar la normalizacion de datos (CuboGasto): " . $e->getMessage());
            }

            return $this->json_output(200, 'Importación CSV actualizada y procesada correctamente.');
        } catch (Exception $e) {
            Log::error('Error en importarActualizarCSV: ' . $e->getMessage());
            return $this->json_output(500, 'Error al actualizar CSV: ' . $e->getMessage());
        }
    }

    public function importarActualizarExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        DB::disableQueryLog();

        // Validaciones
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
            'importacion_id' => 'required|exists:par_importacion,id',
            'fechaActualizacion' => 'required'
        ]);

        $importacion_id = $request->importacion_id;
        $fechaActualizacion = $request->fechaActualizacion;

        // Verificar si existe la importación y pertenece a la fuente correcta
        $importacion = Importacion::where('id', $importacion_id)
            ->where('fuenteImportacion_id', $this->fuente)
            ->first();

        if (!$importacion) {
            return $this->json_output(400, 'Importación no encontrada o no válida.');
        }

        // Verificar si la nueva fecha ya existe en otra importación
        $existeMismaFecha = Importacion::where('fechaActualizacion', $fechaActualizacion)
            ->where('fuenteImportacion_id', $this->fuente)
            ->where('id', '!=', $importacion_id)
            ->where('estado', '!=', 'EL')
            ->exists();

        if ($existeMismaFecha) {
            return $this->json_output(400, 'Ya existe otra importación con la misma fecha de versión.');
        }

        // Procesar archivo
        $archivo = $request->file('file');

        if ($archivo->getSize() == 0) {
            return $this->json_output(400, 'El archivo Excel está vacío.');
        }

        try {
            // 1. Eliminar datos anteriores
            $baseGastosIds = BaseGastos::where('importacion_id', $importacion_id)->pluck('id');

            // Paso 2: Eliminar Detalle en lotes pequeños
            foreach ($baseGastosIds->chunk(200) as $chunk) {
                BaseGastosDetalle::whereIn('basegastos_id', $chunk)->delete();
            }

            // Paso 3: Eliminar Base en lotes pequeños
            foreach ($baseGastosIds->chunk(200) as $chunk) {
                BaseGastos::whereIn('id', $chunk)->delete();
            }

            // Eliminar tabla temporal también por lotes
            $imporGastosIds = ImporGastos::where('importacion_id', $importacion_id)->pluck('id');
            foreach ($imporGastosIds->chunk(500) as $chunk) {
                ImporGastos::whereIn('id', $chunk)->delete();
            }

            // 2. Actualizar fecha de importación
            DB::transaction(function () use ($importacion, $fechaActualizacion) {
                $importacion->fechaActualizacion = $fechaActualizacion;
                $importacion->usuarioId_Crea = auth()->user()->id;
                $importacion->save();
            });

            // 3. Procesar nuevos datos usando Chunk Reading
            try {
                Excel::import(new ImporGastosImport($importacion), $archivo);

                // VERIFICACIÓN DE DATOS IMPORTADOS
                $cantidadRegistros = ImporGastos::where('importacion_id', $importacion->id)->count();

                if ($cantidadRegistros == 0) {
                    return $this->json_output(400, 'Error: No se importaron registros. Verifique si el archivo está vacío.');
                }
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                return $this->json_output(400, 'Error de validación en el archivo Excel: ' . $e->getMessage());
            } catch (Exception $e) {
                return $this->json_output(400, 'Error al leer el archivo Excel: ' . $e->getMessage());
            }

            $importacion->estado = 'PE'; // Pendiente de procesamiento
            $importacion->save();

            // Procesar normalización (ETL)
            try {
                $procesar = DB::select('call pres_pa_procesarImporGastos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
            } catch (Exception $e) {
                $importacion->estado = 'EL';
                $importacion->save();
                return $this->json_output(400, "Error al procesar la normalizacion de datos (ImporGastos): " . $e->getMessage());
            }

            try {
                $procesar = DB::select('call pres_pa_procesar_cubo_gasto(?)', [$importacion->id]);
            } catch (Exception $e) {
                return $this->json_output(400, "Error al procesar la normalizacion de datos (CuboGasto): " . $e->getMessage());
            }

            return $this->json_output(200, 'Importación Excel actualizada y procesada correctamente.');
        } catch (Exception $e) {
            Log::error('Error en importarActualizarExcel: ' . $e->getMessage());
            return $this->json_output(500, 'Error al actualizar Excel: ' . $e->getMessage());
        }
    }

    public function importar()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporGastos.Importar', compact('mensaje', 'fuente'));
    }

    public function importar2()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporGastos.Importar2', compact('mensaje', 'fuente'));
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

    public function importarGuardar(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        /* $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo); */

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, $this->fuente);
        if ($existeMismaFecha != null) {
            $mensaje = "El Archivo ya se encuentra cargado con la misma fecha";
            $this->json_output(400, $mensaje);
        }

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        //Excel::import(new ImporGastosImport, $archivo);//
        $array = (new tablaXImport)->toArray($archivo);

        Log::info('ImportarGuardar Start. Array count: ' . count($array));

        if (empty($array)) {
            return response()->json(['message' => 'El archivo Excel está vacío o no se pudo leer.'], 400);
        }

        // Normalización para manejo de hojas múltiples o simple
        // Si el primer elemento tiene claves de texto (encabezados), es una sola hoja.
        // Si el primer elemento es un array numérico, son múltiples hojas.
        $isSingleSheet = false;
        if (count($array) > 0) {
            $firstItem = reset($array);

            // Debug Logging
            Log::info('First item type: ' . gettype($firstItem));
            if (is_array($firstItem)) {
                Log::info('First item keys sample: ' . json_encode(array_keys($firstItem)));
                if (isset($firstItem[0])) {
                    Log::info('First item[0] keys sample: ' . json_encode(array_keys($firstItem[0])));
                }
            }

            if (is_array($firstItem) && !isset($firstItem[0]) && (isset($firstItem['anio']) || isset($firstItem['mes']))) {
                $isSingleSheet = true;
            }
        }

        if ($isSingleSheet) {
            $array = [$array]; // Envolver en array para que el doble foreach funcione
        }

        $importacion = Importacion::Create([
            'fuenteImportacion_id' => $this->fuente,
            'usuarioId_Crea' => auth()->user()->id,
            //'usuarioId_Aprueba' => null,
            'fechaActualizacion' => $request['fechaActualizacion'],
            //'comentario' => $request['comentario'],
            'estado' => 'PE'
        ]);

        $insertedCount = 0;

        try {
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    if (!isset($row['anio'])) continue; // Saltar filas vacías o sin encabezado correcto

                    $gastos = ImporGastos::Create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['anio'],
                        'mes' => $row['mes'],
                        'cod_niv_gob' => $row['cod_niv_gob'],
                        'nivel_gobierno' => $row['nivel_gobierno'],
                        'cod_sector' => $row['cod_sector'],
                        'sector' => $row['sector'],
                        'cod_pliego' => $row['cod_pliego'],
                        'pliego' => $row['pliego'],
                        'cod_ubigeo' => $row['cod_ubigeo'],
                        'sec_ejec' => $row['sec_ejec'],
                        'cod_ue' => $row['cod_ue'],
                        'unidad_ejecutora' => $row['unidad_ejecutora'],
                        'sec_func' => $row['sec_func'],
                        'cod_cat_pres' => $row['cod_cat_pres'],
                        'categoria_presupuestal' => $row['categoria_presupuestal'],
                        'tipo_prod_proy' => $row['tipo_prod_proy'],
                        'cod_prod_proy' => $row['cod_prod_proy'],
                        'producto_proyecto' => $row['producto_proyecto'],
                        'tipo_act_acc_obra' => $row['tipo_act_acc_obra'],
                        'cod_act_acc_obra' => $row['cod_act_acc_obra'],
                        'actividad_accion_obra' => $row['actividad_accion_obra'],
                        'cod_fun' => $row['cod_fun'],
                        'funcion' => $row['funcion'],
                        'cod_div_fun' => $row['cod_div_fun'],
                        'division_funcional' => $row['division_funcional'],
                        'cod_gru_fun' => $row['cod_gru_fun'],
                        'grupo_funcional' => $row['grupo_funcional'],
                        'meta' => $row['meta'],
                        'cod_fina' => $row['cod_fina'],
                        'finalidad' => $row['finalidad'],
                        'cod_fue_fin' => $row['cod_fue_fin'],
                        'fuente_financiamiento' => $row['fuente_financiamiento'],
                        'cod_rub' => $row['cod_rub'],
                        'rubro' => $row['rubro'],
                        'cod_tipo_rec' => $row['cod_tipo_rec'],
                        'tipo_recurso' => $row['tipo_recurso'],
                        'cod_cat_gas' => $row['cod_cat_gas'],
                        'categoria_gasto' => $row['categoria_gasto'],
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
                        'certificado' => $row['certificado'],
                        'compromiso_anual' => $row['compromiso_anual'],
                        'compromiso_mensual' => $row['compromiso_mensual'],
                        'devengado' => $row['devengado'],
                        'girado' => $row['girado'],
                    ]);
                    $insertedCount++;
                }
            }

            if ($insertedCount == 0) {
                $importacion->estado = 'EL';
                $importacion->save();
                $this->json_output(400, 'El archivo no contiene registros válidos o los encabezados no coinciden. Se han procesado 0 filas.');
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call pres_pa_procesarImporGastos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
    }

    public function importarGuardar2(Request $request)
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
            $mensaje = "El Archivo ya se encuentra cargado con la misma fecha";
            $this->json_output(400, $mensaje);
        }

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);

        $archivo = $request->file('file');
        Excel::import(new ImporGastosImport($this->fuente, $request['fechaActualizacion'], $request['comentario']), $archivo);

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
            $ent = Entidad::find($value->entidad);
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

            if ($value->cnombre == NULL) {
                $nom = 'SERVIDOR';
                $ape = '';
                $ent = new Entidad();
                $ent->abreviado = 'OTI';
            }
            $boton = '';
            $boton .= '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs mr-1" title="Ver detalle"><i class="fa fa-eye"></i></button>';
            $boton .= '<a href="' . route('imporgastos.exportar', $value->id) . '" class="btn btn-success btn-xs mr-1" title="Exportar a CSV"><i class="fa fa-file-csv"></i></a>';

            $boton .= '<button type="button" onclick="abrirModalActualizar(' . $value->id . ', \'' . (date('Y-m-d', strtotime($value->fechaActualizacion))) . '\')" class="btn btn-warning btn-xs mr-1" title="Actualizar Archivo"><i class="fa fa-upload"></i></button>';

            if (auth()->user()->id == 49) {
                $fechaIso = date('Y-m-d\TH:i', strtotime($value->fechaActualizacion));
                $registroIso = $value->updated_at ? date('Y-m-d\TH:i', strtotime($value->updated_at)) : '';
                $estado = $value->estado;
                $boton .= '<button type="button" onclick="abrirModalEditarImportacion(' . $value->id . ', \'' . $fechaIso . '\', \'' . $estado . '\', \'' . $registroIso . '\')" class="btn btn-secondary btn-xs mr-1" title="Editar Registro"><i class="fa fa-edit"></i></button>';
                $boton .= '<button type="button" onclick="geteliminar(' . $value->id . ')" id="eliminar' . $value->id . '" class="btn btn-danger btn-xs mr-1" title="Eliminar"><i class="fa fa-trash"></i></button>';
                $boton .= '<button type="button" onclick="abrirProcesosG(' . $value->id . ')" class="btn btn-info btn-xs mr-1" title="Procesar"><i class="fa fa-cogs"></i></button>';
            }

            $data[] = array(
                $key + 1,
                'GASTO PRESUPUESTAL',
                date("d/m/Y H:i:s", strtotime($value->fechaActualizacion)),
                $nom . ' ' . $ape,
                ($ent ? $ent->abreviado : ''),
                date("d/m/Y H:i:s", strtotime($value->updated_at)),
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

    public function importacionMetaList()
    {
        if ((int) auth()->user()->id !== 49) {
            abort(403);
        }

        $rows = Importacion::query()
            ->where('fuenteImportacion_id', $this->fuente)
            ->where('estado', '!=', 'EL')
            ->orderBy('fechaActualizacion', 'desc')
            ->limit(50)
            ->get(['id', 'fechaActualizacion', 'estado', 'updated_at']);

        return response()->json($rows);
    }

    public function importacionMetaUpdate(Request $request)
    {
        if ((int) auth()->user()->id !== 49) {
            abort(403);
        }

        $this->validate($request, [
            'importacion_id' => 'required|exists:par_importacion,id',
            'fechaActualizacion' => 'required|date',
            'fechaRegistro' => 'nullable|date',
            'estado' => 'required|in:PR,PE,EL',
        ]);

        $importacionId = (int) $request->input('importacion_id');
        $fechaActualizacion = $request->input('fechaActualizacion');
        $fechaRegistro = $request->input('fechaRegistro');
        $estado = $request->input('estado');

        $importacion = Importacion::where('id', $importacionId)
            ->where('fuenteImportacion_id', $this->fuente)
            ->first();

        if (!$importacion) {
            return response()->json(['status' => 404, 'msg' => 'Importación no encontrada o no válida.'], 404);
        }

        $existeMismaFecha = Importacion::where('fechaActualizacion', $fechaActualizacion)
            ->where('fuenteImportacion_id', $this->fuente)
            ->where('id', '!=', $importacionId)
            ->where('estado', '!=', 'EL')
            ->exists();

        if ($existeMismaFecha) {
            return response()->json(['status' => 400, 'msg' => 'Ya existe otra importación con la misma fecha de versión.'], 400);
        }

        $importacion->fechaActualizacion = $fechaActualizacion;
        $importacion->estado = $estado;
        $importacion->usuarioId_Crea = auth()->user()->id;
        if (!empty($fechaRegistro)) {
            $importacion->updated_at = $fechaRegistro;
        }
        $importacion->timestamps = false;
        $importacion->save();

        return response()->json(['status' => 200, 'msg' => 'Importación actualizada.']);
    }

    public function ListaImportada(Request $request, $importacion_id)
    {
        $data = ImporGastosRepositorio::listaImportada($importacion_id);
        return DataTables::of($data)->make(true);
    }

    public function exportarExcel($id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Gasto_Presupuestal_" . $id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = [
            'anio',
            'mes',
            'cod_niv_gob',
            'nivel_gobierno',
            'cod_sector',
            'sector',
            'cod_pliego',
            'pliego',
            'cod_ubigeo',
            'sec_ejec',
            'cod_ue',
            'unidad_ejecutora',
            'sec_func',
            'cod_cat_pres',
            'categoria_presupuestal',
            'tipo_prod_proy',
            'cod_prod_proy',
            'producto_proyecto',
            'tipo_act_acc_obra',
            'cod_act_acc_obra',
            'actividad_accion_obra',
            'cod_fun',
            'funcion',
            'cod_div_fun',
            'division_funcional',
            'cod_gru_fun',
            'grupo_funcional',
            'meta',
            'cod_fina',
            'finalidad',
            'cod_fue_fin',
            'fuente_financiamiento',
            'cod_rub',
            'rubro',
            'cod_tipo_rec',
            'tipo_recurso',
            'cod_cat_gas',
            'categoria_gasto',
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
            'certificado',
            'compromiso_anual',
            'compromiso_mensual',
            'devengado',
            'girado'
        ];

        $headings = [
            'ANIO',
            'MES',
            'COD_NIV_GOB',
            'NIVEL_GOBIERNO',
            'COD_SECTOR',
            'SECTOR',
            'COD_PLIEGO',
            'PLIEGO',
            'COD_UBIGEO',
            'SEC_EJEC',
            'COD_UE',
            'UNIDAD_EJECUTORA',
            'SEC_FUNC',
            'COD_CAT_PRES',
            'CATEGORIA_PRESUPUESTAL',
            'TIPO_PROD_PROY',
            'COD_PROD_PROY',
            'PRODUCTO_PROYECTO',
            'TIPO_ACT_ACC_OBRA',
            'COD_ACT_ACC_OBRA',
            'ACTIVIDAD_ACCION_OBRA',
            'COD_FUN',
            'FUNCION',
            'COD_DIV_FUN',
            'DIVISION_FUNCIONAL',
            'COD_GRU_FUN',
            'GRUPO_FUNCIONAL',
            'META',
            'COD_FINA',
            'FINALIDAD',
            'COD_FUE_FIN',
            'FUENTE_FINANCIAMIENTO',
            'COD_RUB',
            'RUBRO',
            'COD_TIPO_REC',
            'TIPO_RECURSO',
            'COD_CAT_GAS',
            'CATEGORIA_GASTO',
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
            'CERTIFICADO',
            'COMPROMISO_ANUAL',
            'COMPROMISO_MENSUAL',
            'DEVENGADO',
            'GIRADO'
        ];

        $callback = function () use ($id, $columns, $headings) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel compatibility with UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $headings);

            ImporGastos::where('importacion_id', $id)
                ->select($columns)
                ->chunk(2000, function ($gastos) use ($file) {
                    foreach ($gastos as $gasto) {
                        fputcsv($file, $gasto->toArray());
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function procesarBase($importacion_id)
    {
        try {
            DB::select('call pres_pa_procesarImporGastos(?,?)', [$importacion_id, null]);
            return response()->json(['status' => true, 'msg' => 'Base de gastos procesada correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Error al procesar base de gastos: ' . $e->getMessage()], 500);
        }
    }

    public function procesarCubo($importacion_id)
    {
        try {
            DB::select('call pres_pa_procesar_cubo_gasto(?)', [$importacion_id]);
            return response()->json(['status' => true, 'msg' => 'Cubo de gastos procesado correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Error al procesar cubo de gastos: ' . $e->getMessage()], 500);
        }
    }

    public function verificarBase($importacion_id)
    {
        $base = BaseGastos::where('importacion_id', $importacion_id)->first();
        if (!$base) {
            return response()->json([
                'status' => true,
                'msg' => 'Sin base generada aún.',
                'base' => 0,
                'detalle' => 0,
                'anio' => null,
            ]);
        }
        $detalle = BaseGastosDetalle::where('basegastos_id', $base->id)->count();
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
        $anios = DB::table('pres_impor_gastos')
            ->where('importacion_id', $importacion_id)
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($anios->isEmpty()) {
            $anioAlt = DB::table('par_importacion')->where('id', $importacion_id)->value(DB::raw('YEAR(fechaActualizacion)'));
            if ($anioAlt) $anios = collect([(int)$anioAlt]);
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
            $count = DB::table('pres_cubo_gasto')->where('anio', (int)$ax)->count();
            $detalles[] = ['anio' => (int)$ax, 'registros' => (int)$count];
            $total += $count;
        }

        return response()->json([
            'status' => true,
            'msg' => 'Conteo obtenido.',
            'anios' => $detalles,
            'total' => (int)$total,
            'lista_anios' => $anios->toArray(),
        ]);
    }

    public function descargarBaseProcesada($importacion_id)
    {
        $bg = BaseGastos::where('importacion_id', $importacion_id)->first();
        if (!$bg) {
            abort(404, 'No existe base procesada para esta importación.');
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Base_Gastos_Procesada_" . $importacion_id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'basegastos_id',
            'unidadejecutora_id',
            'ubigeo_id',
            'meta_id',
            'categoriapresupuestal_id',
            'productoproyecto_id',
            'recursosgastos_id',
            'categoriagasto_id',
            'especificadetalle_id',
            'meta',
            'pia',
            'pim',
            'certificado',
            'compromiso_anual',
            'compromiso_mensual',
            'devengado',
            'girado',
        ];

        $headings = [
            'BASE_ID',
            'UNIDAD_EJECUTORA_ID',
            'UBIGEO_ID',
            'META_ID',
            'CATEGORIA_PRESUPUESTAL_ID',
            'PRODUCTO_PROYECTO_ID',
            'RECURSOS_GASTOS_ID',
            'CATEGORIA_GASTO_ID',
            'ESPECIFICA_DETALLE_ID',
            'META',
            'PIA',
            'PIM',
            'CERTIFICADO',
            'COMPROMISO_ANUAL',
            'COMPROMISO_MENSUAL',
            'DEVENGADO',
            'GIRADO',
        ];

        $callback = function () use ($bg, $columns, $headings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headings);

            BaseGastosDetalle::where('basegastos_id', $bg->id)
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
        $anios = DB::table('pres_impor_gastos')
            ->where('importacion_id', $importacion_id)
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($anios->isEmpty()) {
            $anioAlt = DB::table('par_importacion')->where('id', $importacion_id)->value(DB::raw('YEAR(fechaActualizacion)'));
            if ($anioAlt) {
                $anios = collect([(int)$anioAlt]);
            }
        }

        if ($anios->isEmpty()) {
            abort(404, 'No se identificó el año asociado a la importación.');
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Cubo_Gastos_Procesado_" . $importacion_id . ".csv",
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
        ];

        $aniosArray = $anios->map(function ($a) {
            return (int)$a;
        })->toArray();

        $callback = function () use ($aniosArray, $columns, $headings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headings);

            DB::table('pres_cubo_gasto')
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
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        try {
            DB::beginTransaction();

            $importacion = Importacion::find($id);

            if (!$importacion) {
                return response()->json(['status' => false, 'message' => 'Importación no encontrada'], 404);
            }

            $bg = BaseGastos::where('importacion_id', $id)->first();

            // Eliminar detalles de gastos importados
            ImporGastos::where('importacion_id', $id)->delete();

            if ($bg) {
                BaseGastosDetalle::where('basegastos_id', $bg->id)->delete();
                $bg->delete();
            }

            $importacion->delete();

            DB::commit();
            return response()->json(array('status' => true));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
}
