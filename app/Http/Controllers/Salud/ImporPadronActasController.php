<?php

namespace App\Http\Controllers\Salud;

use App\Exports\ImporPadronSiagieExport;
use App\Exports\RegistroActasHomologadasEESSExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporCensoDocente;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto3PadronMaterno;
use App\Models\Salud\CuboPacto4Padron12Meses;
use App\Models\Salud\DataPacto1;
use App\Models\Salud\DataPacto3;
use App\Models\Salud\ImporPadronActas;
use App\Models\Salud\ImporPadronAnemia;
use App\Models\Salud\PadronActas;
use App\Repositories\Administracion\EntidadRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\IndicadorGeneralMetaRepositorio;
use App\Repositories\Salud\EstablecimientoRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporPadronActasController extends Controller
{
    public static $FUENTE = ['pacto_1' => 36, 'pacto_2' => 37, 'pacto_3' => 38, 'pacto_4' => 39, 'pacto_5' => 40];
    public $fuente = 36;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        // $var = '23/12/1987';
        // $date = str_replace('/', '-', $var);
        // echo date('Y-m-d', strtotime($date));

        // $date = DateTime::createFromFormat('d/m/Y', "3/12/1987");
        // echo $date->format('Y-m-d');

        // $query= Ubigeo::where('codigo','like','25%')->whereRaw('length(codigo)=6')->pluck('id','codigo');
        // $query= Ubigeo::where('codigo','like','25%')->whereRaw('length(codigo)=4')->pluck('id','codigo');
        // return $query;
        $fuentes = FuenteImportacion::whereIn('id', [36, 37, 38, 39, 40])->get();
        return view('salud.ImporPadronActas.Importar', compact('fuentes'));
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

            case ImporPadronActasController::$FUENTE['pacto_1']:
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
                                'distrito' => $row['distrito'] == 'RAIMONDI' ? 'RAYMONDI' : $row['distrito'],
                                'fecha_inicial' => $this->cambiarFormat($row['fecha_inicial']),
                                'fecha_final' => $this->cambiarFormat($row['fecha_final']),
                                'fecha_envio' => $this->cambiarFormat($row['fecha_envio']),
                                'dni_usuario_envio' => $row['dni_usuario_envio'],
                                'primer_apellido' => $row['primer_apellido'],
                                'segundo_apellido' => $row['segundo_apellido'],
                                'prenombres' => $row['prenombres'],
                                'numero_archivos' => $row['numero_archivos'],
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
                    $this->json_output(400, $mensaje);
                }

                try {
                    DB::select('call sal_pa_procesarPacto1(?,?)', [$importacion->id, date('Y', strtotime($rq->fechaActualizacion))]);
                } catch (Exception $e) {
                    $mensaje = "Error al procesar la normalizacion de datos." . $e;
                    $tipo = 'danger';
                    $this->json_output(400, $mensaje);
                }

                $mensaje = "Archivo excel subido y Procesado correctamente .";
                $this->json_output(200, $mensaje, '');

                break;

            case ImporPadronActasController::$FUENTE['pacto_2']:
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
                                $row['anio'] .
                                $row['mes'] .
                                $row['ubigeo'] .
                                $row['cod_unico'] .
                                $row['num_doc'] .
                                $row['fecha_nac'] .
                                $row['seguro'] .
                                $row['fecha_dx'] .
                                $row['fecha_supt1'] .
                                $row['num_supt1'] .
                                $row['fecha_supt3'] .
                                $row['num_supt3'] .
                                $row['fecha_recup'] .
                                $row['num_recup'] .
                                $row['fecha_dosaje'] .
                                $row['num_dosaje'] .
                                $row['den'] .
                                $row['num'];
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
                        'estado' => 'PR'
                    ]);

                    foreach ($array as $key => $value) {
                        foreach ($value as $row) {
                            ImporPadronAnemia::Create([
                                'importacion_id' => $importacion->id,
                                'anio' => $row['anio'],
                                'mes' => $row['mes'],
                                'ubigeo' => Ubigeo::where('codigo', $row['ubigeo'])->first()->id,
                                'cod_unico' => $row['cod_unico'],
                                'num_doc' => $row['num_doc'],
                                'fecha_nac' =>  $this->fechaExcel($row['fecha_nac']),
                                'seguro' => $row['seguro'],
                                'fecha_dx' =>  $this->fechaExcel($row['fecha_dx']),
                                'fecha_supt1' => $this->fechaExcel($row['fecha_supt1']),
                                'num_supt1' => $row['num_supt1'],
                                'fecha_supt3' => $this->fechaExcel($row['fecha_supt3']),
                                'num_supt3' => $row['num_supt3'],
                                'fecha_recup' => $this->fechaExcel($row['fecha_recup']),
                                'num_recup' => $row['num_recup'],
                                'fecha_dosaje' => $this->fechaExcel($row['fecha_dosaje']),
                                'num_dosaje' => $row['num_dosaje'],
                                'den' => $row['den'],
                                'num' => $row['num']
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    $importacion->estado = 'PE';
                    $importacion->save();

                    $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
                    $this->json_output(400, $mensaje);
                }

                // try {
                //     DB::select('call sal_pa_procesarPacto1(?,?)', [$importacion->id, date('Y', strtotime($rq->fechaActualizacion))]);
                // } catch (Exception $e) {
                //     $mensaje = "Error al procesar la normalizacion de datos." . $e;
                //     $tipo = 'danger';
                //     $this->json_output(400, $mensaje);
                // }

                $mensaje = "Archivo excel subido y Procesado correctamente .";
                $this->json_output(200, $mensaje, '');

                break;

            case 3333:
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
                                $row['anio'] .
                                $row['mes'] .
                                $row['num_doc'] .
                                $row['fecha_parto'] .
                                $row['semana_nac'] .
                                $row['gest_37sem'] .
                                $row['codigo_unico'] .
                                $row['red'] .
                                $row['microred'] .
                                $row['eess_parto'] .
                                $row['provincia'] .
                                $row['ubigeo_distrito'] .
                                $row['distrito'] .
                                $row['denominador'] .
                                $row['numerador'] .
                                $row['num_exam_hb'] .
                                $row['num_exam_sifilis'] .
                                $row['num_exam_vih'] .
                                $row['num_exam_bacteriuria'] .
                                $row['num_perfil_obstetrico'] .
                                $row['num_exam_aux'] .
                                $row['num_apn1_1trim'] .
                                $row['num_apn1_2trim'] .
                                $row['num_apn2_2trim'] .
                                $row['num_apn1_3trim'] .
                                $row['num_apn2_3trim'] .
                                $row['num_apn3_3trim'] .
                                $row['num_apn'] .
                                $row['num_entrega1_sfaf'] .
                                $row['num_entrega2_sfaf'] .
                                $row['num_entrega3_sfaf'] .
                                $row['num_entrega4_sfaf'] .
                                $row['num_entrega5_sfaf'] .
                                $row['num_entrega_sfaf'];
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
                        'estado' => 'PR'
                    ]);

                    foreach ($array as $key => $value) {
                        foreach ($value as $row) {
                            CuboPacto3PadronMaterno::Create([
                                'importacion_id' => $importacion->id,
                                'anio' => $row['anio'],
                                'mes' => $row['mes'],
                                'num_doc' => $row['num_doc'],
                                'fecha_parto' => $row['fecha_parto'],
                                'semana_nac' => $row['semana_nac'],
                                'gest_37sem' => $row['gest_37sem'],
                                'codigo_unico' => $row['codigo_unico'],
                                'red' => $row['red'],
                                'microred' => $row['microred'],
                                'eess_parto' => $row['eess_parto'],
                                'provincia' => $row['provincia'],
                                'ubigeo_distrito' => $row['ubigeo_distrito'],
                                'distrito' => $row['distrito'],
                                'denominador' => $row['denominador'],
                                'numerador' => $row['numerador'],
                                'num_exam_hb' => $row['num_exam_hb'],
                                'num_exam_sifilis' => $row['num_exam_sifilis'],
                                'num_exam_vih' => $row['num_exam_vih'],
                                'num_exam_bacteriuria' => $row['num_exam_bacteriuria'],
                                'num_perfil_obstetrico' => $row['num_perfil_obstetrico'],
                                'num_exam_aux' => $row['num_exam_aux'],
                                'num_apn1_1trim' => $row['num_apn1_1trim'],
                                'num_apn1_2trim' => $row['num_apn1_2trim'],
                                'num_apn2_2trim' => $row['num_apn2_2trim'],
                                'num_apn1_3trim' => $row['num_apn1_3trim'],
                                'num_apn2_3trim' => $row['num_apn2_3trim'],
                                'num_apn3_3trim' => $row['num_apn3_3trim'],
                                'num_apn' => $row['num_apn'],
                                'num_entrega1_sfaf' => $row['num_entrega1_sfaf'],
                                'num_entrega2_sfaf' => $row['num_entrega2_sfaf'],
                                'num_entrega3_sfaf' => $row['num_entrega3_sfaf'],
                                'num_entrega4_sfaf' => $row['num_entrega4_sfaf'],
                                'num_entrega5_sfaf' => $row['num_entrega5_sfaf'],
                                'num_entrega_sfaf' => $row['num_entrega_sfaf']
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    $importacion->estado = 'PE';
                    $importacion->save();
                    $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
                    $this->json_output(400, $mensaje);
                }

                // try {
                //     DB::select('call sal_pa_procesarPacto1(?,?)', [$importacion->id, date('Y', strtotime($rq->fechaActualizacion))]);
                // } catch (Exception $e) {
                //     $mensaje = "Error al procesar la normalizacion de datos." . $e;
                //     $tipo = 'danger';
                //     $this->json_output(400, $mensaje);
                // }

                $mensaje = "Archivo excel subido y Procesado correctamente .";
                $this->json_output(200, $mensaje, '');

                break;

            case ImporPadronActasController::$FUENTE['pacto_3']:

                if (ImportacionRepositorio::Importacion_PE($rq->fechaActualizacion, ImporPadronActasController::$FUENTE['pacto_3']) !== null) {
                    return $this->json_output(400, "Error, ya existe un archivo pendiente de aprobación para la fecha ingresada");
                }

                if (ImportacionRepositorio::Importacion_PR($rq->fechaActualizacion, ImporPadronActasController::$FUENTE['pacto_3']) !== null) {
                    return $this->json_output(400, "Error, ya existe un archivo procesado para la fecha ingresada");
                }

                $this->validate($rq, ['file' => 'required|mimes:xls,xlsx']);
                $archivo = $rq->file('file');
                $array = (new tablaXImport)->toArray($archivo);

                $encabezadosEsperados = [
                    'anio',
                    'mes',
                    'num_doc',
                    'fecha_parto',
                    'semana_nac',
                    'gest_37sem',
                    'codigo_unico',
                    'red',
                    'microred',
                    'eess_parto',
                    'provincia',
                    'ubigeo_distrito',
                    'distrito',
                    'denominador',
                    'numerador',
                    'num_exam_hb',
                    'num_exam_sifilis',
                    'num_exam_vih',
                    'num_exam_bacteriuria',
                    'num_perfil_obstetrico',
                    'num_exam_aux',
                    'num_apn1_1trim',
                    'num_apn1_2trim',
                    'num_apn2_2trim',
                    'num_apn1_3trim',
                    'num_apn2_3trim',
                    'num_apn3_3trim',
                    'num_apn',
                    'num_entrega1_sfaf',
                    'num_entrega2_sfaf',
                    'num_entrega3_sfaf',
                    'num_entrega4_sfaf',
                    'num_entrega5_sfaf',
                    'num_entrega_sfaf'
                ];

                $encabezadosArchivo = array_keys($array[0][0]);

                $faltantes = array_diff($encabezadosEsperados, $encabezadosArchivo);
                if (!empty($faltantes)) {
                    return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado. Faltan columnas esperadas.', $faltantes);
                }

                try {
                    DB::beginTransaction();

                    $importacion = Importacion::create([
                        'fuenteImportacion_id' => ImporPadronActasController::$FUENTE['pacto_3'],
                        'usuarioId_Crea' => auth()->user()->id,
                        'fechaActualizacion' => $rq->fechaActualizacion,
                        'estado' => 'PR'
                    ]);

                    $distritos = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=6')->pluck('id', 'codigo');
                    $provincias = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=4')->pluck('id', 'codigo');

                    $batchSize = 500;
                    $dataBatch = [];

                    foreach ($array[0] as $row) {
                        $dataBatch[] = [
                            'importacion_id' => $importacion->id,
                            'anio' => $row['anio'],
                            'mes' => $row['mes'],
                            'num_doc' => $row['num_doc'],
                            'fecha_parto' => $row['fecha_parto'],
                            'semana_nac' => $row['semana_nac'],
                            'gest_37sem' => $row['gest_37sem'],
                            'codigo_unico' => $row['codigo_unico'],
                            'red' => $row['red'],
                            'microred' => $row['microred'],
                            'eess_parto' => $row['eess_parto'],
                            'provincia' => $row['provincia'],
                            'ubigeo_distrito' => $row['ubigeo_distrito'],
                            'distrito' => $row['distrito'],
                            'distrito_id' => $distritos[$row['ubigeo_distrito']] ?? null,
                            'provincia_id' => $provincias[substr($row['ubigeo_distrito'], 0, 4)] ?? null,
                            'denominador' => $row['denominador'],
                            'numerador' => $row['numerador'],
                            'num_exam_hb' => $row['num_exam_hb'],
                            'num_exam_sifilis' => $row['num_exam_sifilis'],
                            'num_exam_vih' => $row['num_exam_vih'],
                            'num_exam_bacteriuria' => $row['num_exam_bacteriuria'],
                            'num_perfil_obstetrico' => $row['num_perfil_obstetrico'],
                            'num_exam_aux' => $row['num_exam_aux'],
                            'num_apn1_1trim' => $row['num_apn1_1trim'],
                            'num_apn1_2trim' => $row['num_apn1_2trim'],
                            'num_apn2_2trim' => $row['num_apn2_2trim'],
                            'num_apn1_3trim' => $row['num_apn1_3trim'],
                            'num_apn2_3trim' => $row['num_apn2_3trim'],
                            'num_apn3_3trim' => $row['num_apn3_3trim'],
                            'num_apn' => $row['num_apn'],
                            'num_entrega1_sfaf' => $row['num_entrega1_sfaf'],
                            'num_entrega2_sfaf' => $row['num_entrega2_sfaf'],
                            'num_entrega3_sfaf' => $row['num_entrega3_sfaf'],
                            'num_entrega4_sfaf' => $row['num_entrega4_sfaf'],
                            'num_entrega5_sfaf' => $row['num_entrega5_sfaf'],
                            'num_entrega_sfaf' => $row['num_entrega_sfaf']
                        ];

                        if (count($dataBatch) >= $batchSize) {
                            CuboPacto3PadronMaterno::insert($dataBatch);
                            $dataBatch = [];
                        }
                    }

                    if (!empty($dataBatch)) {
                        CuboPacto3PadronMaterno::insert($dataBatch);
                    }

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
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
                //     DB::select('call sal_pa_procesarCalidadReporte(?)', [$importacion->id]);
                // } catch (Exception $e) {
                //     // Si ocurre un error, actualizar el estado a 'PE' (pendiente) si es necesario
                //     $importacion->estado = 'PE';
                //     $importacion->save();

                //     $mensaje = "Error al procesar la normalizacion de datos sal_pa_procesarCalidadReporte. " . $e->getMessage();
                //     return $this->json_output(400, $mensaje);
                // }

                $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
                break;
            case ImporPadronActasController::$FUENTE['pacto_4']:

                if (ImportacionRepositorio::Importacion_PE($rq->fechaActualizacion, ImporPadronActasController::$FUENTE['pacto_4']) !== null) {
                    return $this->json_output(400, "Error, ya existe un archivo pendiente de aprobación para la fecha ingresada");
                }

                if (ImportacionRepositorio::Importacion_PR($rq->fechaActualizacion, ImporPadronActasController::$FUENTE['pacto_4']) !== null) {
                    return $this->json_output(400, "Error, ya existe un archivo procesado para la fecha ingresada");
                }

                $this->validate($rq, ['file' => 'required|mimes:xls,xlsx']);
                $archivo = $rq->file('file');
                $array = (new tablaXImport)->toArray($archivo);

                $encabezadosEsperados = [
                    'anio',
                    'mes',
                    'codigo_disa',
                    'codigo_red',
                    'codigo_unico',
                    'tipo_documento',
                    'numero_documento_identidad',
                    'nombre_nino',
                    'tipo_seguro',
                    'fecha_nacimiento',
                    'edad_mes',
                    'edad_dias',
                    'fecha_inicio',
                    'fecha_final',
                    'num_dni30d',
                    'num_dni60d',
                    'num_cred_rn',
                    'num_cred_mensual',
                    'cumple_cred',
                    'num_neumo',
                    'num_rota',
                    'num_polio',
                    'num_penta',
                    'cumple_vacuna',
                    'cumple_esq_4m',
                    'cumple_esq_6m',
                    'cumple_suplemento',
                    'cumple_dosaje_hb',
                    'cumple_dni_enitido_30d',
                    'cumple_dni_enitido_60d',
                    'den',
                    'num',
                    'numero_documento_madre',
                    'nombre_madre',
                    'nrocel_madre',
                    'ubigeo',
                    'provincia',
                    'distrito',
                    'red',
                    'microred',
                    'eess',
                ];

                $encabezadosArchivo = array_keys($array[0][0]);

                $faltantes = array_diff($encabezadosEsperados, $encabezadosArchivo);
                if (!empty($faltantes)) {
                    return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado. Faltan columnas esperadas.', $faltantes);
                }

                try {
                    DB::beginTransaction();

                    $importacion = Importacion::create([
                        'fuenteImportacion_id' => ImporPadronActasController::$FUENTE['pacto_4'],
                        'usuarioId_Crea' => auth()->user()->id,
                        'fechaActualizacion' => $rq->fechaActualizacion,
                        'estado' => 'PR'
                    ]);

                    $distritos = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=6')->pluck('id', 'codigo');
                    $provincias = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=4')->pluck('id', 'codigo');

                    $batchSize = 500;
                    $dataBatch = [];

                    foreach ($array[0] as $row) {
                        $dataBatch[] = [
                            'importacion_id' => $importacion->id,
                            'anio' => $row['anio'],
                            'mes' => $row['mes'],
                            'codigo_disa' => $row['codigo_disa'] == 'NULL' ? null : $row['codigo_disa'],
                            'codigo_red' => $row['codigo_red'] == 'NULL' ? null : $row['codigo_red'],
                            'codigo_unico' => $row['codigo_unico'] == 'NULL' ? null : $row['codigo_unico'],
                            'tipo_documento' => $row['tipo_documento'],
                            'numero_documento_identidad' => $row['numero_documento_identidad'],
                            'nombre_nino' => $row['nombre_nino'],
                            'tipo_seguro' => $row['tipo_seguro'],
                            'fecha_nacimiento' => null, // $row['fecha_nacimiento'] == 'NULL' ? null : $row['fecha_nacimiento'],
                            'edad_mes' => $row['edad_mes'],
                            'edad_dias' => $row['edad_dias'],
                            'fecha_inicio' => null, //$row['fecha_inicio'] == 'NULL' ? null : $row['fecha_inicio'],
                            'fecha_final' => null, //$row['fecha_final'] == 'NULL' ? null : $row['fecha_final'],
                            'num_dni30d' => $row['num_dni30d'],
                            'num_dni60d' => $row['num_dni60d'],
                            'num_cred_rn' => $row['num_cred_rn'],
                            'num_cred_mensual' => $row['num_cred_mensual'],
                            'cumple_cred' => $row['cumple_cred'],
                            'num_neumo' => $row['num_neumo'],
                            'num_rota' => $row['num_rota'],
                            'num_polio' => $row['num_polio'],
                            'num_penta' => $row['num_penta'],
                            'cumple_vacuna' => $row['cumple_vacuna'],
                            'cumple_esq_4m' => $row['cumple_esq_4m'],
                            'cumple_esq_6m' => $row['cumple_esq_6m'],
                            'cumple_suplemento' => $row['cumple_suplemento'],
                            'cumple_dosaje_hb' => $row['cumple_dosaje_hb'],
                            'cumple_dni_enitido_30d' => $row['cumple_dni_enitido_30d'],
                            'cumple_dni_enitido_60d' => $row['cumple_dni_enitido_60d'],
                            'den' => $row['den'],
                            'num' => $row['num'],
                            'numero_documento_madre' => $row['numero_documento_madre'] == 'NULL' ? null : $row['numero_documento_madre'],
                            'nombre_madre' => $row['nombre_madre'] == 'NULL' ? null : $row['nombre_madre'],
                            'nrocel_madre' => $row['nrocel_madre'] == 'NULL' ? null : $row['nrocel_madre'],
                            'ubigeo' => $row['ubigeo'] == 'NULL' ? null : $row['ubigeo'],
                            'provincia' => $row['provincia'] == 'NULL' ? null : $row['provincia'],
                            'distrito' => $row['distrito'] == 'NULL' ? null : $row['distrito'],
                            'red' => $row['red'] == 'NULL' ? null : $row['red'],
                            'microred' => $row['microred'] == 'NULL' ? null : $row['microred'],
                            'eess' => $row['eess'] == 'NULL' ? null : $row['eess'],
                        ];

                        if (count($dataBatch) >= $batchSize) {
                            CuboPacto4Padron12Meses::insert($dataBatch);
                            $dataBatch = [];
                        }
                    }

                    if (!empty($dataBatch)) {
                        CuboPacto4Padron12Meses::insert($dataBatch);
                    }

                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
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
                //     DB::select('call sal_pa_procesarCalidadReporte(?)', [$importacion->id]);
                // } catch (Exception $e) {
                //     // Si ocurre un error, actualizar el estado a 'PE' (pendiente) si es necesario
                //     $importacion->estado = 'PE';
                //     $importacion->save();

                //     $mensaje = "Error al procesar la normalizacion de datos sal_pa_procesarCalidadReporte. " . $e->getMessage();
                //     return $this->json_output(400, $mensaje);
                // }

                $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
                break;
            default:
                break;
        }
    }

    public function cambiarFormat($ff)
    {
        if ($ff) {
            if (strlen($ff) > 7) {
                $date = DateTime::createFromFormat('d/m/Y', $ff);
                return $date->format('Y-m-d');
            }
        }
        return null;
    }

    public function fechaExcel($ff)
    {
        if ($ff) {
            if ($ff != 'NULL') {
                $unix = (intval($ff) - 25569) * 86400;
                $php = new DateTime("@$unix");
                return $php->format('Y-m-d');
            }
        }
        return null;
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $query = ImportacionRepositorio::Listar_FuenteTodos($rq->fuente);
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
                $boton = '<button type="button" onclick="geteliminar(' . $rq->fuente . ',' . $value->id . ')" class="btn btn-danger btn-xs" id="eliminar' . $value->id . '"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $rq->fuente . ',' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
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
            "data" => $data,
            "rq" => $rq->all()
        );
        return response()->json($result);
    }

    public function ListaImportada(Request $rq) //(Request $request, $importacion_id)
    {
        $data = ImporPadronActas::where('importacion_id', $rq->importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function eliminar($fuente, $id)
    {
        // public static $FUENTE = ['pacto_1' => 36, 'pacto_2' => 37, 'pacto_3' => 38, 'pacto_4' => 39, 'pacto_5' => 40];
        switch ($fuente) {
            case 36:
                ImporPadronActas::where('importacion_id', $id)->delete();
                DataPacto1::where('importacion_id', $id)->delete();
                Importacion::find($id)->delete();
                break;
            case 37:
                ImporPadronAnemia::where('importacion_id', $id)->truncate();
                Importacion::find($id)->delete();
                break;
            case 38:
                CuboPacto3PadronMaterno::where('importacion_id', $id)->truncate();
                Importacion::find($id)->delete();
                break;
            case 39:
                break;
            case 40:
                break;
            default:
                break;
        }
        return response()->json(array('status' => true));
    }

    public function exportar()
    {
        /* $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        $mensaje = "";
        return view('educacion.ImporPoblacion.Exportar', compact('mensaje', 'imp', 'mat')); */
    }

    public function download()
    {
        // $name = 'SIAGIE MATRICULAS ' . date('Y-m-d') . '.xlsx';
        // return Excel::download(new ImporPadronSiagieExport, $name);
        return [];
    }

    public function registro()
    {
        // return session()->all();
        // $anio = [2023, 2024, 2025, 2026];
        $sector = 2;
        // return session()->all();       
        $ent = EntidadRepositorio::migas(auth()->user()->entidad);
        if (session('usuario_sector') == 2 && session('usuario_nivel') == 7) {
            $muni = EntidadRepositorio::entidades(2, session('usuario_codigo_institucion'));
            $registrador = session('usuario_codigo_institucion');
            $usuario = $muni->first();
        } else {
            $muni = EntidadRepositorio::entidades(2, 0);
            $registrador = 0;
            $usuario = false;
        }
        $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios(17);

        // return $usuario;
        // session('usuario_codigo_institucion');
        // return session()->all();
        // return session('usuario_id');

        return view('salud.ImporPadronActas.registro', compact('anio', 'muni', 'registrador', 'usuario', 'ent'));
    }

    public function registro_listarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = PadronActas::where('fecha_envio', $rq->fechaf)->where('establecimiento_id', $rq->eess)->get();
        $data = [];
        foreach ($query as $key => $value) {
            // $nactas = PadronActas::where('fecha_envio', $rq->fechaf)->where('establecimiento_id', $value->id)->select(DB::raw('sum(nro_archivos) as nactas'))->get()->first();
            $boton = '';
            $boton .= '<button class="btn btn-xs btn-danger waves-effect waves-light" onclick="eliminarseguimiento(' . $value->id . ')"><i class="fa fa-trash"></i></button>';

            $data[] = array(
                $key + 1,
                $value->fecha_inicial,
                $value->fecha_final,
                $value->nro_archivos,
                $boton,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            // "municipio" =>  $rq->municipio,
            // "query" => $query,
        );
        return response()->json($result);
    }

    public function registro_listarDT2(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = PadronActas::from('sal_padron_actas as pa')
            ->select('pa.id', 'es.cod_unico', 'es.nombre_establecimiento as eess', 'pa.fecha_inicial', 'pa.fecha_final', 'pa.fecha_envio', 'pa.nro_archivos')
            ->join('sal_establecimiento as es', 'es.id', '=', 'pa.establecimiento_id')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->where('es.ubigeo_id', $rq->municipio);
        if ($rq->eess) {
            $query = $query->where('pa.establecimiento_id', $rq->eess);
        }
        $query = $query->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('eess')->orderBy('fecha_envio')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $boton = '';
            $boton .= '<button class="btn btn-xs btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal_form" onclick="editseguimiento(' . $value->id . ')"><i class="fa fa-pen"></i></button>&nbsp;';
            $boton .= '<button class="btn btn-xs btn-danger waves-effect waves-light" onclick="eliminarseguimiento(' . $value->id . ')"><i class="fa fa-trash"></i></button>';

            $data[] = array(
                $key + 1,
                sprintf('%08d', $value->cod_unico),
                $value->eess,
                date('d/m/Y', strtotime($value->fecha_inicial)),
                date('d/m/Y', strtotime($value->fecha_final)),
                // $value->fecha_final,
                // $value->fecha_envio,
                date('d/m/Y', strtotime($value->fecha_envio)),
                $value->nro_archivos,
                $boton,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            // "municipio" =>  $rq->municipio,
            // "query" => $query,
        );
        return response()->json($result);
    }


    private function _registro_validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->mfeess == 0) {
            $data['inputerror'][] = 'mfeess';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->mffechai == '') {
            $data['inputerror'][] = 'mffechai';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->mffechaf == '') {
            $data['inputerror'][] = 'mffechaf';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->mffechae == '') {
            $data['inputerror'][] = 'mffechae';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->mfarchivos > 0) {
        } else {
            $data['inputerror'][] = 'mfarchivos';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function registro_add(Request $rq)
    {
        $this->_registro_validate($rq);
        $padron = PadronActas::Create([
            'ubigeo_id' => $rq->mfubigeo,
            'establecimiento_id' => $rq->mfeess,
            'usuario_id' => auth()->user()->id,
            'fecha_inicial' => $rq->mffechai,
            'fecha_final' => $rq->mffechaf,
            'fecha_envio' => $rq->mffechae,
            'nro_archivos' => $rq->mfarchivos,
        ]);
        return response()->json(array('status' => true, 'msn' => $rq->all(), 'padron' => $padron));
    }

    public function registro_find($id)
    {
        $pd = PadronActas::find($id);
        return response()->json(array('pd' => $pd));
    }

    public function registro_update(Request $rq)
    {
        $this->_registro_validate($rq);
        $pd = PadronActas::find($rq->mfid);
        // $pd->ubigeo_id = $rq->mfubigeo;
        $pd->establecimiento_id = $rq->mfeess;
        $pd->usuario_id = auth()->user()->id;
        $pd->fecha_inicial = $rq->mffechai;
        $pd->fecha_final = $rq->mffechaf;
        $pd->fecha_envio = $rq->mffechae;
        $pd->nro_archivos = $rq->mfarchivos;
        $pd->save();

        return response()->json(array('status' => true, 'msn' => $rq->all(), 'padron' => $pd));
    }

    public function registro_delete($id)
    {
        $padron = PadronActas::find($id);
        $padron->delete();
        return response()->json(array('status' => true));
    }

    public function registro_listarDT_resumen(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = PadronActas::where('fecha_envio', 'like', $rq->fecha . '%')->where('establecimiento_id', $rq->eess)->get();
        // $query = PadronActas::whereBetween('fecha_envio', [$rq->fechai, $rq->fechaf])->where('establecimiento_id', $rq->eess)->get();
        // $query = PadronActas::where('establecimiento_id', $rq->eess)->get();
        $data = [];
        foreach ($query as $key => $value) {
            $boton = '';
            if ($rq->registrador > 0) {
                $boton .= '<button class="btn btn-xs btn-primary waves-effect waves-light" onclick="modificar_acta(' . $value->id . ')"><i class="fa fa-pen"></i></button>&nbsp;';
                $boton .= '<button class="btn btn-xs btn-danger waves-effect waves-light" onclick="eliminar_acta(' . $value->id . ')"><i class="fa fa-trash"></i></button>';
            }
            $data[] = array(
                $key + 1,
                $value->fecha_inicial,
                $value->fecha_final,
                $value->fecha_envio,
                $value->nro_archivos,
                $boton
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "municipio" =>  $rq->municipio,
            "query" => $query,
        );
        return response()->json($result);
    }

    public function registro_download($div, $anio, $municipio, $red, $microred, $fechai, $fechaf, $registrador)
    {
        $name = 'Padron Actas por Establecimientos ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new RegistroActasHomologadasEESSExport($div, $anio,  $municipio, $red, $microred, $fechai, $fechaf, $registrador), $name);
    }

    public function registro_alerta(Request $rq)
    {
        $eess = EstablecimientoRepositorio::listEESS($rq->sector, $rq->municipio, 0, 0)->count();
        $pd = PadronActas::join('sal_establecimiento as ee', 'ee.id', '=', 'sal_padron_actas.establecimiento_id')->get()->count();
        return response()->json(array('eess' => $eess, 'pd' => $pd));
    }
}
