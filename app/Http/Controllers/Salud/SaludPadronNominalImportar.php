<?php

namespace App\Http\Controllers\Salud;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SaludPadronJuntosImportacion;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\Poblacion;
use App\Models\Salud\PadronJuntos;
use App\Models\Parametro\PoblacionDetalle;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SaludPadronNominalImportar extends Controller
{
    public $fuente = 36;
    public function __construct()
    {
        $this->middleware('auth');
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

    public function index()
    {
        return view('salud.padron.importar');
    }

    public function listarHistorial()
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        $query = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
        $data = [];
        foreach ($query as $key => $value) {
            $ent = Entidad::find($value->entidad);
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
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

    public function cargarPadron(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        /* se esta  */
        /*$existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }*/

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
                        $row['centro_poblado_res'] .
                        $row['fecha_nacimiento_mo'] .
                        $row['departamento_eess_ultima_atencion'] .
                        $row['fecha_control_rn1'];
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
                'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            $poblacion = Poblacion::Create([
                'importacion_id' => $importacion->id,
                'anio_id' => Anio::where('anio', date('Y', strtotime($importacion->fechaActualizacion)))->first()->id,
                'created_at' => date('Y-m-d h:i:s'),
            ]);

            $errores = [];
            $columnNames = array_keys($array[0][0]);
            PadronJuntos::truncate();
            $id = 10;
            foreach ($array as $key => $value) {

                foreach ($value as $row) {
                    $data = ['id' => $id++];

                    foreach ($columnNames as $columnName) {
                        if (strpos($columnName, 'fecha_') !== false) {
                            if (!empty($row[$columnName])) {
                                date_default_timezone_set('UTC');
                                $unix_date = ($row[$columnName] - 25569) * 86400;
                                $data[$columnName] = date("d/m/Y", $unix_date);
                            } else {
                                $data[$columnName] = null;
                            }
                        } else {
                            $data[$columnName] = $row[$columnName];
                        }
                    }
                    //echo "x".var_dump($data)."<br>";
                    PadronJuntos::create($data);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema - " . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            DB::select('call par_pa_procesarImporPoblacion(?,?)', [$importacion->id, $poblacion->id]);
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


    public function cargarPadron2(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls', // Validación para asegurar que se suba un archivo Excel
        ]);

        $archivo = $request->file('archivo_excel');

        Excel::import(new SaludPadronJuntosImportacion, $archivo); // Utiliza la clase de importación para importar los datos del archivo Excel

        return redirect()->back()->with('success', 'Datos importados exitosamente.');
    }
}
