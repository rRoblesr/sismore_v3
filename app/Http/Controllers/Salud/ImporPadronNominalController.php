<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Imports\tablaYImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\PoblacionDetalle;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\ImporPadronEstablecimiento;
use App\Models\Salud\ImporPadronNominal;
use App\Models\Salud\PadronNominal;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporPadronNominalController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public $fuente = 45;
    public static $FUENTE = 45;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* metodo para la vista del formulario para importar */
    public function importar()
    {
        $mensaje = "";
        return view('salud.ImporPadronNominal.Importar', compact('mensaje'));
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

    public function guardar_novaletodavia(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');

        // Encabezados esperados
        $encabezadosEsperados = [
            'padron',
            'cnv',
            'cui',
            'dni',
            'num_doc',
            'tipo_doc',
            'apellido_paterno',
            'apellido_materno',
            'nombre',
            'genero',
            'fecha_nacimiento',
            'direccion',
            'ubigeo',
            'centro_poblado',
            'area_ccpp',
            'cui_nacimiento',
            'cui_atencion',
            'seguro',
            'programa_social',
            'visita',
            'menor_encontrado',
            'codigo_ie',
            'nombre_ie',
            'tipo_doc_madre',
            'num_doc_madre',
            'apellido_paterno_madre',
            'apellido_materno_madre',
            'nombres_madre',
            'celular_madre',
            'grado_instruccion',
            'lengua_madre'
        ];

        // Procesar encabezados y datos en fragmentos
        try {
            DB::beginTransaction();

            // Crear la importación
            $importacion = Importacion::create([
                'fuenteImportacion_id' => $this->fuente,
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $request->fechaActualizacion,
                'estado' => 'PR'
            ]);

            $batchSize = 500;
            $dataBatch = [];

            // Cargar el archivo en fragmentos
            Excel::import(new class($encabezadosEsperados, $importacion->id, $dataBatch, $batchSize)
            implements ToModel, WithHeadingRow, WithChunkReading {
                private $encabezadosEsperados, $importacion_id, $dataBatch, $batchSize;

                public function __construct($encabezadosEsperados, $importacion_id, &$dataBatch, $batchSize)
                {
                    $this->encabezadosEsperados = $encabezadosEsperados;
                    $this->importacion_id = $importacion_id;
                    $this->dataBatch = &$dataBatch;
                    $this->batchSize = $batchSize;
                }

                public function model(array $row)
                {
                    // Verificar encabezados solo en el primer fragmento
                    if (empty($this->dataBatch)) {
                        $encabezadosArchivo = array_keys($row);
                        $faltantes = array_diff($this->encabezadosEsperados, $encabezadosArchivo);

                        if (!empty($faltantes)) {
                            throw new Exception('Error: Faltan columnas esperadas.');
                        }
                    }

                    // Preparar cada fila para inserción masiva
                    $this->dataBatch[] = [
                        'importacion_id' => $this->importacion_id,
                        'padron' => $row['padron'],
                        'cnv' => $row['cnv'],
                        'cui' => $row['cui'],
                        'dni' => $row['dni'],
                        'num_doc' => $row['num_doc'],
                        'tipo_doc' => $row['tipo_doc'],
                        'apellido_paterno' => $row['apellido_paterno'],
                        'apellido_materno' => $row['apellido_materno'],
                        'nombre' => $row['nombre'],
                        'genero' => $row['genero'],
                        'fecha_nacimiento' => $row['fecha_nacimiento'],
                        'direccion' => $row['direccion'],
                        'ubigeo' => $row['ubigeo'],
                        'centro_poblado' => $row['centro_poblado'],
                        'area_ccpp' => $row['area_ccpp'],
                        'cui_nacimiento' => $row['cui_nacimiento'],
                        'cui_atencion' => $row['cui_atencion'],
                        'seguro' => $row['seguro'],
                        'programa_social' => $row['programa_social'],
                        'visita' => $row['visita'],
                        'menor_encontrado' => $row['menor_encontrado'],
                        'codigo_ie' => $row['codigo_ie'],
                        'nombre_ie' => $row['nombre_ie'],
                        'tipo_doc_madre' => $row['tipo_doc_madre'],
                        'num_doc_madre' => $row['num_doc_madre'],
                        'apellido_paterno_madre' => $row['apellido_paterno_madre'],
                        'apellido_materno_madre' => $row['apellido_materno_madre'],
                        'nombres_madre' => $row['nombres_madre'],
                        'celular_madre' => $row['celular_madre'],
                        'grado_instruccion' => $row['grado_instruccion'],
                        'lengua_madre' => $row['lengua_madre']
                    ];

                    // Insertar cada lote
                    if (count($this->dataBatch) >= $this->batchSize) {
                        ImporPadronNominal::insert($this->dataBatch);
                        $this->dataBatch = [];
                    }
                }

                public function chunkSize(): int
                {
                    return $this->batchSize;
                }
            }, $archivo);

            // Insertar cualquier fila restante
            if (!empty($dataBatch)) {
                ImporPadronNominal::insert($dataBatch);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->json_output(400, "Error en la carga de datos: " . $e->getMessage());
        }

        return $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
    }

    public function guardar(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        // Verificar si existe una importación con la misma fecha
        if (ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente) !== null) {
            return $this->json_output(400, "Error, ya existe un archivo pendiente de aprobación para la fecha ingresada");
        }

        if (ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, $this->fuente) !== null) {
            return $this->json_output(400, "Error, ya existe un archivo procesado para la fecha ingresada");
        }

        // Validar el archivo cargado
        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        // if (count($array) !== 1) {
        //     return $this->json_output(400, 'Error: El archivo debe contener una única hoja');
        // }

        // Encabezados esperados
        $encabezadosEsperados = [
            'padron',
            'cnv',
            'cui',
            'dni',
            'num_doc',
            'tipo_doc',
            'apellido_paterno',
            'apellido_materno',
            'nombre',
            'genero',
            'fecha_nacimiento',
            'direccion',
            'ubigeo',
            'centro_poblado',
            'centro_poblado_nombre',
            'area_ccpp',
            'cui_nacimiento',
            'cui_atencion',
            'seguro',
            'programa_social',
            'visita',
            'menor_encontrado',
            'tipo_doc_madre',
            'num_doc_madre',
            'apellido_paterno_madre',
            'apellido_materno_madre',
            'nombres_madre',
            'celular_madre',
            'grado_instruccion',
            'lengua_madre'
        ];

        // Validar encabezados del archivo
        // $encabezadosArchivo = array_keys($array[0][0]);
        // // if ($encabezadosArchivo !== $encabezadosEsperados) {
        // //     return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado', $encabezadosArchivo);
        // // }

        // if (array_diff($encabezadosArchivo, $encabezadosEsperados) || array_diff($encabezadosEsperados, $encabezadosArchivo)) {
        //     return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado o contienen columnas adicionales', $encabezadosArchivo);
        // }

        // Obtener encabezados del archivo
        $encabezadosArchivo = array_keys($array[0][0]);

        // Validar que todos los encabezados esperados están presentes
        $faltantes = array_diff($encabezadosEsperados, $encabezadosArchivo);
        if (!empty($faltantes)) {
            return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado. Faltan columnas esperadas.', $faltantes);
        }

        try {
            DB::beginTransaction(); // Iniciar transacción

            // Crear la importación
            $importacion = Importacion::create([
                'fuenteImportacion_id' => $this->fuente,
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $request->fechaActualizacion,
                'estado' => 'PR'
            ]);

            // Preparar los datos para la inserción masiva
            $batchSize = 500; // Tamaño del lote de inserción
            $dataBatch = []; // Arreglo para almacenar cada lote de inserciones

            foreach ($array[0] as $row) {
                $dataBatch[] = [
                    'importacion_id' => $importacion->id,
                    'padron' => $row['padron'],
                    'cnv' => $row['cnv'],
                    'cui' => $row['cui'],
                    'dni' => $row['dni'],
                    'num_doc' => $row['num_doc'],
                    'tipo_doc' => $row['tipo_doc'],
                    'apellido_paterno' => $row['apellido_paterno'],
                    'apellido_materno' => $row['apellido_materno'],
                    'nombre' => $row['nombre'],
                    'genero' => $row['genero'],
                    'fecha_nacimiento' => $row['fecha_nacimiento'],
                    'direccion' => $row['direccion'],
                    'ubigeo' => $row['ubigeo'],
                    'centro_poblado' => $row['centro_poblado'],
                    'centro_poblado_nombre' => $row['centro_poblado_nombre'],
                    'area_ccpp' => $row['area_ccpp'],
                    'cui_nacimiento' => $row['cui_nacimiento'],
                    'cui_atencion' => $row['cui_atencion'],
                    'seguro' => $row['seguro'],
                    'programa_social' => $row['programa_social'],
                    'visita' => $row['visita'],
                    'menor_encontrado' => $row['menor_encontrado'],
                    // 'codigo_ie' => $row['codigo_ie'],
                    // 'nombre_ie' => $row['nombre_ie'],
                    'tipo_doc_madre' => $row['tipo_doc_madre'],
                    'num_doc_madre' => $row['num_doc_madre'],
                    'apellido_paterno_madre' => $row['apellido_paterno_madre'],
                    'apellido_materno_madre' => $row['apellido_materno_madre'],
                    'nombres_madre' => $row['nombres_madre'],
                    'celular_madre' => $row['celular_madre'],
                    'grado_instruccion' => $row['grado_instruccion'],
                    'lengua_madre' => $row['lengua_madre']
                ];

                // Si alcanzamos el tamaño del lote, hacemos una inserción masiva
                if (count($dataBatch) >= $batchSize) {
                    ImporPadronNominal::insert($dataBatch);
                    $dataBatch = []; // Limpiar el lote después de la inserción
                }
            }

            // Insertar cualquier fila restante que no haya sido procesada
            if (!empty($dataBatch)) {
                ImporPadronNominal::insert($dataBatch);
            }

            DB::commit(); // Confirmar la transacción
        } catch (Exception $e) {
            DB::rollBack(); // Revertir en caso de error
            $importacion->estado = 'PE';
            $importacion->save();

            return $this->json_output(400, "Error en la carga de datos: " . $e->getMessage());
        }

        try {
            DB::select('call sal_pa_procesarControlCalidadColumnas(?)', [$importacion->id]);
        } catch (Exception $e) {
            // Si ocurre un error, actualizar el estado a 'PE' (pendiente) si es necesario
            $importacion->estado = 'PE';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos. " . $e->getMessage();
            return $this->json_output(400, $mensaje);
        }

        return $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
    }

    public function guardar_xxx2(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        // Verificar existencia de importaciones previas con la misma fecha
        if (ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente) !== null) {
            return $this->json_output(400, "Error, ya existe un archivo pendiente de aprobación para la fecha ingresada");
        }

        if (ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, $this->fuente) !== null) {
            return $this->json_output(400, "Error, ya existe un archivo procesado para la fecha ingresada");
        }

        // Validar el archivo cargado
        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        // if (count($array) !== 1) {
        //     return $this->json_output(400, 'Error: El archivo debe contener una única hoja');
        // }

        try {
            DB::beginTransaction(); // Inicia la transacción

            // Crea la importación
            $importacion = Importacion::create([
                'fuenteImportacion_id' => $this->fuente,
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $request->fechaActualizacion,
                'estado' => 'PR'
            ]);

            // Inserción de filas
            foreach ($array[0] as $row) {
                ImporPadronNominal::create([
                    'importacion_id' => $importacion->id,
                    'padron' => $row['padron'],
                    'cnv' => $row['cnv'],
                    'cui' => $row['cui'],
                    'dni' => $row['dni'],
                    'num_doc' => $row['num_doc'],
                    'tipo_doc' => $row['tipo_doc'],
                    'apellido_paterno' => $row['apellido_paterno'],
                    'apellido_materno' => $row['apellido_materno'],
                    'nombre' => $row['nombre'],
                    'genero' => $row['genero'],
                    'fecha_nacimiento' => $row['fecha_nacimiento'],
                    'direccion' => $row['direccion'],
                    'ubigeo' => $row['ubigeo'],
                    'centro_poblado' => $row['centro_poblado'],
                    'area_ccpp' => $row['area_ccpp'],
                    'cui_nacimiento' => $row['cui_nacimiento'],
                    'cui_atencion' => $row['cui_atencion'],
                    'seguro' => $row['seguro'],
                    'programa_social' => $row['programa_social'],
                    'visita' => $row['visita'],
                    'menor_encontrado' => $row['menor_encontrado'],
                    'codigo_ie' => $row['codigo_ie'],
                    'nombre_ie' => $row['nombre_ie'],
                    'tipo_doc_madre' => $row['tipo_doc_madre'],
                    'num_doc_madre' => $row['num_doc_madre'],
                    'apellido_paterno_madre' => $row['apellido_paterno_madre'],
                    'apellido_materno_madre' => $row['apellido_materno_madre'],
                    'nombres_madre' => $row['nombres_madre'],
                    'celular_madre' => $row['celular_madre'],
                    'grado_instruccion' => $row['grado_instruccion'],
                    'lengua_madre' => $row['lengua_madre']
                ]);
            }

            DB::commit(); // Confirma la transacción
        } catch (Exception $e) {
            DB::rollBack(); // Revierte la transacción en caso de error
            $importacion->estado = 'PE';
            $importacion->save();

            return $this->json_output(400, "Error en la carga de datos: " . $e->getMessage());
        }

        return $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
    }

    /*  metodo que carga el excel y ejecuta un procedimiento almacenado*/
    public function guardar_xxx(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        /* se esta  */
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
                        $row['padron'] .
                        $row['cnv'] .
                        $row['cui'] .
                        $row['dni'] .
                        $row['num_doc'] .
                        $row['tipo_doc'] .
                        $row['apellido_paterno'] .
                        $row['apellido_materno'] .
                        $row['nombre'] .
                        $row['genero'] .
                        $row['fecha_nacimiento'] .
                        $row['direccion'] .
                        $row['ubigeo'] .
                        $row['centro_poblado'] .
                        $row['area_ccpp'] .
                        $row['cui_nacimiento'] .
                        $row['cui_atencion'] .
                        $row['seguro'] .
                        $row['programa_social'] .
                        $row['visita'] .
                        $row['menor_encontrado'] .
                        $row['codigo_ie'] .
                        $row['nombre_ie'] .
                        $row['tipo_doc_madre'] .
                        $row['num_doc_madre'] .
                        $row['apellido_paterno_madre'] .
                        $row['apellido_materno_madre'] .
                        $row['nombres_madre'] .
                        $row['celular_madre'] .
                        $row['grado_instruccion'] .
                        $row['lengua_madre'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $this->json_output(403, $mensaje);
        }

        /* ajustar fecha */
        $anio = Anio::where('anio', date('Y'))->first();
        if (!$anio) {
            Anio::Create(['anio' => date('Y')]);
        }
        /* fin ajuste */

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'estado' => 'PR'
            ]);


            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $nuevo = ImporPadronNominal::Create([
                        'importacion_id' => $importacion->id,
                        'padron' => $row['padron'],
                        'cnv' => $row['cnv'],
                        'cui' => $row['cui'],
                        'dni' => $row['dni'],
                        'num_doc' => $row['num_doc'],
                        'tipo_doc' => $row['tipo_doc'],
                        'apellido_paterno' => $row['apellido_paterno'],
                        'apellido_materno' => $row['apellido_materno'],
                        'nombre' => $row['nombre'],
                        'genero' => $row['genero'],
                        'fecha_nacimiento' => $row['fecha_nacimiento'],
                        'direccion' => $row['direccion'],
                        'ubigeo' => $row['ubigeo'],
                        'centro_poblado' => $row['centro_poblado'],
                        'area_ccpp' => $row['area_ccpp'],
                        'cui_nacimiento' => $row['cui_nacimiento'],
                        'cui_atencion' => $row['cui_atencion'],
                        'seguro' => $row['seguro'],
                        'programa_social' => $row['programa_social'],
                        'visita' => $row['visita'],
                        'menor_encontrado' => $row['menor_encontrado'],
                        'codigo_ie' => $row['codigo_ie'],
                        'nombre_ie' => $row['nombre_ie'],
                        'tipo_doc_madre' => $row['tipo_doc_madre'],
                        'num_doc_madre' => $row['num_doc_madre'],
                        'apellido_paterno_madre' => $row['apellido_paterno_madre'],
                        'apellido_materno_madre' => $row['apellido_materno_madre'],
                        'nombres_madre' => $row['nombres_madre'],
                        'celular_madre' => $row['celular_madre'],
                        'grado_instruccion' => $row['grado_instruccion'],
                        'lengua_madre' => $row['lengua_madre']
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'PE';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema - " . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        // try {
        //     DB::select('call sal_pa_procesarPadronEstablecimiento(?,?)', [$importacion->id, auth()->user()->id]);
        // } catch (Exception $e) {
        //     $mensaje = "Error al procesar la normalizacion de datos." . $e;
        //     $this->json_output(400, $mensaje);
        // }
        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
    }

    /* metodo para listar las importaciones */
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
            $boton = '';
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || in_array(session('perfil_administrador_id'), [3, 8, 9, 10, 11])) {
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';
            }
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
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
            "data" => $data
        );
        return response()->json($result);
    }

    /* metodo para cargar una importacion especifica */
    public function ListaImportada(Request $rq)
    {
        $data = ImporPadronNominal::where('importacion_id', $rq->importacion_id);
        return DataTables::of($data)->make(true);
    }

    /* metodo para eliminar una importacion */
    public function eliminar($id)
    {
        // $poblacion = Poblacion::where('importacion_id', $id)->first();
        // PoblacionDetalle::where('poblacion_id', $poblacion->id)->delete();
        // $poblacion->delete();
        ImporPadronNominal::where('importacion_id', $id)->delete();
        PadronNominal::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }
}
