<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Imports\ImporGastosImport;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseProyectosDetalle;
use App\Models\Administracion\Entidad;
use App\Models\Presupuesto\ImporGastos;
use App\Models\Presupuesto\ImporProyectos;
use App\Models\Presupuesto\ImporSiafWeb;
use App\Repositories\Educacion\ImporGastosRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporProyectosController extends Controller
{
    public $fuente = 25;
    public static $FUENTE = 25;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporProyectos.Importar', compact('mensaje', 'fuente'));
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

        $cadena = '';
        try {
            foreach ($array as $value) {
                foreach ($value as $key => $row) {
                    if ($key > 0) break;
                    $cadena =  $cadena .
                        //$row['departamento_inei'] .
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
                    $gastos = ImporProyectos::Create([
                        'importacion_id' => $importacion->id,
                        //'departamento_inei' => $row['departamento_inei'],
                        'cod_gob_reg' => $row['cod_gob_reg'],
                        'gobiernos_regionales' => $row['gobiernos_regionales'],
                        'pia' => $row['pia'],
                        'pim' => $row['pim'],
                        'certificacion' => $row['certificacion'],
                        'compromiso_anual' => $row['compromiso_anual'],
                        'compromiso_mensual' => $row['compromiso_mensual'],
                        'devengado' => $row['devengado'],
                        'girado' => $row['girado'],
                        'avance' => (float)$row['avance'],
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje, $array);
        }

        try {
            $procesar = DB::select('call pres_pa_procesarProyectos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e->getMessage();
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
            $boton .= '<a href="' . route('imporproyectos.exportar', $value->id) . '" class="btn btn-success btn-xs mr-1" title="Descargar CSV"><i class="fa fa-file-csv"></i></a>';
            
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11) {
                $boton .= '<button type="button" onclick="abrirModalActualizar(' . $value->id . ', \'' . (date('Y-m-d', strtotime($value->fechaActualizacion))) . '\')" class="btn btn-warning btn-xs mr-1" title="Actualizar Archivo"><i class="fa fa-upload"></i></button>';
                $boton .= '<button type="button" onclick="geteliminar(' . $value->id . ')" id="eliminar' . $value->id . '" class="btn btn-danger btn-xs mr-1" title="Eliminar"><i class="fa fa-trash"></i> </button>';
                $boton .= '<button type="button" onclick="abrirProcesos(' . $value->id . ')" class="btn btn-info btn-xs mr-1" title="Procesar"><i class="fa fa-cogs"></i></button>';
            }
            $usuario = trim($nom . ' ' . $ape);
            $data[] = array(
                $key + 1,
                'PROYECTO',
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
        $data = ImporProyectos::where('importacion_id', $importacion_id);
        return DataTables::of($data)->make(true);
    }

    public function eliminar($id)
    {
        /* $entidad = Importacion::find($id);
        $entidad->estado = 'EL';
        $entidad->save(); */

        $bp = BaseProyectos::where('importacion_id', $id)->first();
        ImporProyectos::where('importacion_id', $id)->delete();
        if ($bp) {
            BaseProyectosDetalle::where('baseproyectos_id', $bp->id)->delete();
            BaseProyectos::find($bp->id)->delete();
        }
        Importacion::find($id)->delete();

        return response()->json(array('status' => true));
    }

    public function exportarExcel($id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Proyectos_" . $id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = [
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

        $callback = function () use ($id, $columns, $headings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
            fputcsv($file, $headings);

            ImporProyectos::where('importacion_id', $id)
                ->select($columns)
                ->chunk(2000, function ($proyectos) use ($file) {
                    foreach ($proyectos as $proyecto) {
                        fputcsv($file, $proyecto->toArray());
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

        try {
            // 1. Eliminar datos anteriores
            $bp = BaseProyectos::where('importacion_id', $importacion_id)->first();
            if ($bp) {
                BaseProyectosDetalle::where('baseproyectos_id', $bp->id)->delete();
                BaseProyectos::find($bp->id)->delete();
            }
            ImporProyectos::where('importacion_id', $importacion_id)->delete();

            // 2. Actualizar fecha de importación
            $importacion->fechaActualizacion = $fechaActualizacion;
            $importacion->usuarioId_Crea = auth()->user()->id;
            $importacion->estado = 'PE';
            $importacion->save();

            // 3. Procesar nuevos datos
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    ImporProyectos::Create([
                        'importacion_id' => $importacion->id,
                        'cod_gob_reg' => $row['cod_gob_reg'],
                        'gobiernos_regionales' => $row['gobiernos_regionales'],
                        'pia' => $row['pia'],
                        'pim' => $row['pim'],
                        'certificacion' => $row['certificacion'],
                        'compromiso_anual' => $row['compromiso_anual'],
                        'compromiso_mensual' => $row['compromiso_mensual'],
                        'devengado' => $row['devengado'],
                        'girado' => $row['girado'],
                        'avance' => (float)$row['avance'],
                    ]);
                }
            }

            // 4. Procesar normalización (ETL)
            try {
                DB::select('call pres_pa_procesarProyectos(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
            } catch (Exception $e) {
                $importacion->estado = 'EL';
                $importacion->save();
                return $this->json_output(400, "Error al procesar la normalizacion de datos: " . $e->getMessage());
            }

            return $this->json_output(200, 'Importación actualizada y procesada correctamente.');

        } catch (Exception $e) {
            return $this->json_output(500, 'Error al actualizar Excel: ' . $e->getMessage());
        }
    }

    public function procesarBase($importacion_id)
    {
        try {
            // Asumimos que el usuario que crea es el actual, o podríamos buscarlo de la importación
            $importacion = Importacion::find($importacion_id);
            if (!$importacion) return response()->json(['status' => false, 'msg' => 'Importación no encontrada'], 404);

            DB::select('call pres_pa_procesarProyectos(?,?)', [$importacion_id, $importacion->usuarioId_Crea]);
            return response()->json(['status' => true, 'msg' => 'Base de proyectos procesada correctamente.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'msg' => 'Error al procesar base de proyectos: ' . $e->getMessage()], 500);
        }
    }

    public function verificarBase($importacion_id)
    {
        $base = BaseProyectos::where('importacion_id', $importacion_id)->first();
        if (!$base) {
            return response()->json([
                'status' => true,
                'msg' => 'Sin base generada aún.',
                'base' => 0,
                'detalle' => 0,
            ]);
        }
        $detalle = BaseProyectosDetalle::where('baseproyectos_id', $base->id)->count();
        return response()->json([
            'status' => true,
            'msg' => 'Conteo obtenido.',
            'base' => 1,
            'detalle' => $detalle,
        ]);
    }

    public function descargarBaseProcesada($importacion_id)
    {
        $bp = BaseProyectos::where('importacion_id', $importacion_id)->first();
        if (!$bp) {
            abort(404, 'No existe base procesada para esta importación.');
        }

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Base_Proyectos_Procesada_" . $importacion_id . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'gobiernosregionales_id',
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
            'GOBIERNOS_REGIONALES_ID',
            'PIA',
            'PIM',
            'CERTIFICACION',
            'COMPROMISO_ANUAL',
            'COMPROMISO_MENSUAL',
            'DEVENGADO',
            'GIRADO',
            'AVANCE',
        ];

        $callback = function () use ($bp, $columns, $headings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headings);

            BaseProyectosDetalle::where('baseproyectos_id', $bp->id)
                ->select($columns)
                ->chunk(2000, function ($detalles) use ($file) {
                    foreach ($detalles as $detalle) {
                        fputcsv($file, $detalle->toArray());
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
