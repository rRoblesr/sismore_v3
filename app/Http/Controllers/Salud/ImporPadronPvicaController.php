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
use App\Models\Salud\ImporPadronPvica;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ImporPadronPvicaController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public $fuente = 47;
    public static $FUENTE = 47;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* metodo para la vista del formulario para importar */
    public function importar()
    {
        $mensaje = "";
        return view('salud.ImporPadronPvica.Importar', compact('mensaje'));
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
    public function guardar(Request $request)
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
                        $row['ubigeo_ccpp'] .
                        $row['nombre_ccpp'] .
                        $row['departamento_cpp'] .
                        $row['distrito_ccpp'] .
                        $row['ambito_ccpp'] .
                        $row['nombre_ipress'] .
                        $row['diresa_ipress'] .
                        $row['red_ipress'] .
                        $row['microred_ipress'] .
                        $row['ubicacion_lugar_muestreo'] .
                        $row['nombre_lugar_muestreo'] .
                        $row['horas_dia_continuidad'] .
                        $row['dias_semana_continuidad'] .
                        $row['cloro_parametros_decreto'] .
                        $row['conductividad_parametros_decreto'] .
                        $row['ph_parametros_decreto'] .
                        $row['temperatura_parametros_decreto'] .
                        $row['turbiedad_parametros_decreto'];
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
                'estado' => 'PE'
            ]);


            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $nuevo = ImporPadronPvica::Create([
                        'importacion_id' => $importacion->id,
                        'ubigeo_ccpp' => $row['ubigeo_ccpp'],
                        'nombre_ccpp' => $row['nombre_ccpp'],
                        'departamento_cpp' => $row['departamento_cpp'],
                        'distrito_ccpp' => $row['distrito_ccpp'],
                        'ambito_ccpp' => $row['ambito_ccpp'],
                        'nombre_ipress' => $row['nombre_ipress'],
                        'diresa_ipress' => $row['diresa_ipress'],
                        'red_ipress' => $row['red_ipress'],
                        'microred_ipress' => $row['microred_ipress'],
                        'ubicacion_lugar_muestreo' => $row['ubicacion_lugar_muestreo'],
                        'nombre_lugar_muestreo' => $row['nombre_lugar_muestreo'],
                        'horas_dia_continuidad' => $row['horas_dia_continuidad'] != 'null' ? $row['horas_dia_continuidad'] : null,
                        'dias_semana_continuidad' => $row['dias_semana_continuidad'] != 'null' ? $row['dias_semana_continuidad'] : null,
                        'cloro_parametros_decreto' => $row['cloro_parametros_decreto'],
                        'conductividad_parametros_decreto' => $row['conductividad_parametros_decreto'],
                        'ph_parametros_decreto' => $row['ph_parametros_decreto'],
                        'temperatura_parametros_decreto' => $row['temperatura_parametros_decreto'],
                        'turbiedad_parametros_decreto' => $row['turbiedad_parametros_decreto'],
                    ]);
                }
            }
            $importacion->estado = 'PR';
            $importacion->save();
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
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
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

    /* metodo para cargar una importacion especifica */
    public function ListaImportada(Request $rq)
    {
        $data = ImporPadronEstablecimiento::all();
        return DataTables::of($data)->make(true);
    }

    /* metodo para eliminar una importacion */
    public function eliminar($id)
    {
        ImporPadronPvica::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }
}
