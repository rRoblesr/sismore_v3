<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Administracion\Entidad;
use App\Models\Presupuesto\ImporConsultaAmigable;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Yajra\DataTables\DataTables;

class ImporConsultaAmigableController extends Controller
{
    public $fuente = 51;
    public static $FUENTE = 51;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporConsultaAmigable.Importar', compact('mensaje', 'fuente'));
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

    private function validarTiposRequeridos($array, array $requeridos = [1, 2, 3])
    {
        $tiposEncontrados = [];
        foreach ($array as $sheet) {
            foreach ($sheet as $row) {
                $tipo = $row['tipo'] ?? null;
                if ($tipo === null || $tipo === '') {
                    continue;
                }
                $tiposEncontrados[(int)$tipo] = true;
            }
        }
        return array_values(array_diff($requeridos, array_keys($tiposEncontrados)));
    }

    private function tipoDescripcion($tipo)
    {
        switch ((int)$tipo) {
            case 1:
                return 'ACTIVIDADES/PROYECTOS';
            case 2:
                return 'ACTIVIDADES';
            case 3:
                return 'PROYECTOS';
            default:
                return (string)$tipo;
        }
    }

    public function importarGuardar(Request $request)
    {
        ini_set('memory_limit', '-1');  //control de memoria
        set_time_limit(0);              //control de tiempo de ejecucion

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

        $faltantes = $this->validarTiposRequeridos($array, [1, 2, 3]);
        if (count($faltantes) > 0) {
            $this->json_output(400, 'Error: el Excel debe contener los tipos 1, 2 y 3. Faltan: ' . implode(', ', $faltantes));
        }

        $cadena = '';
        try {
            foreach ($array as $value) {
                foreach ($value as $key => $row) {
                    if ($key > 0) break;
                    $cadena =  $cadena .
                        ($row['anio'] ?? '') .
                        ($row['mes'] ?? '') .
                        ($row['dia'] ?? '') .
                        ($row['tipo'] ?? '') .
                        $row['cod_gob_reg'] .
                        $row['gobiernos_regionales'] .
                        $row['pia'] .
                        $row['pim'] .
                        $row['certificacion'] .
                        $row['compromiso_anual'] .
                        $row['compromiso_mensual'] .
                        $row['devengado'] .
                        $row['girado'] .
                        $row['avance'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto." . $e->getMessage();
            $this->json_output(403, $mensaje);
        }

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                // 'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                // 'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    ImporConsultaAmigable::create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['anio'] ?? null,
                        'mes' => $row['mes'] ?? null,
                        'dia' => $row['dia'] ?? null,
                        'tipo' => $row['tipo'] ?? null,
                        'cod_gob_reg' => $row['cod_gob_reg'] ?? null,
                        'gobiernos_regionales' => $row['gobiernos_regionales'] ?? null,
                        'pia' => $row['pia'] ?? null,
                        'pim' => $row['pim'] ?? null,
                        'certificacion' => $row['certificacion'] ?? null,
                        'compromiso_anual' => $row['compromiso_anual'] ?? null,
                        'compromiso_mensual' => $row['compromiso_mensual'] ?? null,
                        'devengado' => $row['devengado'] ?? null,
                        'girado' => $row['girado'] ?? null,
                        'avance' => isset($row['avance']) ? (float)$row['avance'] : null,
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje, $array);
        }

        $mensaje = "Archivo excel subido correctamente. Pendiente de procesar.";
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
            $boton = '';
            $boton .= '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs mr-1" title="Ver detalle"><i class="fa fa-eye"></i> </button>';
            $boton .= '<a href="' . route('imporconsultaamigable.exportar', $value->id) . '" class="btn btn-success btn-xs mr-1" title="Descargar CSV"><i class="fa fa-file-csv"></i></a>';
            
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11) {
                $boton .= '<button type="button" onclick="abrirModalActualizar(' . $value->id . ', \'' . (date('Y-m-d', strtotime($value->fechaActualizacion))) . '\')" class="btn btn-warning btn-xs mr-1" title="Actualizar Archivo"><i class="fa fa-upload"></i></button>';
                $boton .= '<button type="button" onclick="geteliminar(' . $value->id . ')" id="eliminar' . $value->id . '" class="btn btn-danger btn-xs mr-1" title="Eliminar"><i class="fa fa-trash"></i> </button>';
                // $boton .= '<button type="button" onclick="abrirProcesos(' . $value->id . ')" class="btn btn-info btn-xs mr-1" title="Procesar"><i class="fa fa-cogs"></i></button>';
            }
            $usuario = trim($nom . ' ' . $ape);
            $data[] = array(
                $key + 1,
                'CONSULTA AMIGABLE',
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                //$value->fuente . $value->id,
                $usuario == '' ? 'SERVIDOR' : $usuario,
                ($ent ? $ent->abreviado : 'OTI'),
                date("d/m/Y", strtotime($value->created_at)),
                //$value->comentario,
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
                $boton
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
        $data = ImporConsultaAmigable::where('importacion_id', $importacion_id);
        return DataTables::of($data)->make(true);
    }

    public function eliminar($id)
    {
        ImporConsultaAmigable::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();

        return response()->json(array('status' => true));
    }

    public function exportarExcel($id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Consulta_Amigable_" . $id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = [
            'anio',
            'mes',
            'dia',
            'tipo',
            'cod_gob_reg',
            'gobiernos_regionales',
            'pia',
            'pim',
            'certificacion',
            'compromiso_anual',
            'compromiso_mensual',
            'devengado',
            'girado',
            'avance'
        ];

        $headings = [
            'ANIO',
            'MES',
            'DIA',
            'TIPO',
            'COD_GOB_REG',
            'GOBIERNOS_REGIONALES',
            'PIA',
            'PIM',
            'CERTIFICACION',
            'COMPROMISO_ANUAL',
            'COMPROMISO_MENSUAL',
            'DEVENGADO',
            'GIRADO',
            'AVANCE'
        ];

        $self = $this;
        $callback = function () use ($id, $columns, $headings, $self) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
            fputcsv($file, $headings);

            ImporConsultaAmigable::where('importacion_id', $id)
                ->select($columns)
                ->chunk(2000, function ($proyectos) use ($file, $columns, $self) {
                    foreach ($proyectos as $proyecto) {
                        $row = [];
                        foreach ($columns as $col) {
                            $value = $proyecto->{$col};
                            if ($col === 'tipo') {
                                $value = $self->tipoDescripcion($value);
                            }
                            $row[] = $value;
                        }
                        fputcsv($file, $row);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importarActualizar(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $importacion_id = $request->importacion_id;
        $fechaActualizacion = $request->fechaActualizacion;
        $importacion = Importacion::find($importacion_id);

        if (!$importacion) {
            return $this->json_output(400, 'Importación no encontrada.');
        }

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        if (count($array) != 1) {
            return $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        $faltantes = $this->validarTiposRequeridos($array, [1, 2, 3]);
        if (count($faltantes) > 0) {
            return $this->json_output(400, 'Error: el Excel debe contener los tipos 1, 2 y 3. Faltan: ' . implode(', ', $faltantes));
        }

        try {
            ImporConsultaAmigable::where('importacion_id', $importacion_id)->delete();

            // 2. Actualizar fecha de importación
            $importacion->fechaActualizacion = $fechaActualizacion;
            $importacion->usuarioId_Crea = auth()->user()->id;
            $importacion->estado = 'PE';
            $importacion->save();

            // 3. Procesar nuevos datos
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    ImporConsultaAmigable::Create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['anio'] ?? null,
                        'mes' => $row['mes'] ?? null,
                        'dia' => $row['dia'] ?? null,
                        'tipo' => $row['tipo'] ?? null,
                        'cod_gob_reg' => $row['cod_gob_reg'] ?? null,
                        'gobiernos_regionales' => $row['gobiernos_regionales'] ?? null,
                        'pia' => $row['pia'] ?? null,
                        'pim' => $row['pim'] ?? null,
                        'certificacion' => $row['certificacion'] ?? null,
                        'compromiso_anual' => $row['compromiso_anual'] ?? null,
                        'compromiso_mensual' => $row['compromiso_mensual'] ?? null,
                        'devengado' => $row['devengado'] ?? null,
                        'girado' => $row['girado'] ?? null,
                        'avance' => isset($row['avance']) ? (float)$row['avance'] : null,
                    ]);
                }
            }

            return $this->json_output(200, 'Importación actualizada correctamente. Pendiente de procesar.');

        } catch (Exception $e) {
            return $this->json_output(500, 'Error al actualizar Excel: ' . $e->getMessage());
        }
    }

    public function procesarBase($importacion_id)
    {
        try {
            $importacion = Importacion::find($importacion_id);
            if (!$importacion) return response()->json(['status' => false, 'msg' => 'Importación no encontrada'], 404);

            $conteo = ImporConsultaAmigable::where('importacion_id', $importacion_id)->count();
            if ($conteo <= 0) {
                return response()->json(['status' => false, 'msg' => 'No hay registros importados para procesar.'], 400);
            }

            $importacion->estado = 'PR';
            $importacion->save();

            return response()->json(['status' => true, 'msg' => 'Importación procesada correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Error al procesar importación: ' . $e->getMessage()], 500);
        }
    }

    public function verificarBase($importacion_id)
    {
        $importacion = Importacion::find($importacion_id);
        $detalle = ImporConsultaAmigable::where('importacion_id', $importacion_id)->count();

        return response()->json([
            'status' => true,
            'msg' => 'Conteo obtenido.',
            'base' => $importacion && $importacion->estado === 'PR' ? 1 : 0,
            'detalle' => $detalle,
        ]);
    }

    public function descargarBaseProcesada($importacion_id)
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Consulta_Amigable_Procesada_" . $importacion_id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'anio',
            'mes',
            'dia',
            'tipo',
            'cod_gob_reg',
            'gobiernos_regionales',
            'pia',
            'pim',
            'certificacion',
            'compromiso_anual',
            'compromiso_mensual',
            'devengado',
            'girado',
            'avance',
        ];

        $headings = [
            'ANIO',
            'MES',
            'DIA',
            'TIPO',
            'COD_GOB_REG',
            'GOBIERNOS_REGIONALES',
            'PIA',
            'PIM',
            'CERTIFICACION',
            'COMPROMISO_ANUAL',
            'COMPROMISO_MENSUAL',
            'DEVENGADO',
            'GIRADO',
            'AVANCE',
        ];

        $self = $this;
        $callback = function () use ($importacion_id, $columns, $headings, $self) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headings);

            ImporConsultaAmigable::where('importacion_id', $importacion_id)
                ->select($columns)
                ->chunk(2000, function ($detalles) use ($file, $columns, $self) {
                    foreach ($detalles as $detalle) {
                        $row = [];
                        foreach ($columns as $col) {
                            $value = $detalle->{$col};
                            if ($col === 'tipo') {
                                $value = $self->tipoDescripcion($value);
                            }
                            $row[] = $value;
                        }
                        fputcsv($file, $row);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
