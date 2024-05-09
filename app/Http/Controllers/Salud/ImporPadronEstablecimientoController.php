<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\PoblacionDetalle;
use App\Models\Salud\ImporPadronEstablecimiento;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ImporPadronEstablecimientoController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public $fuente = 41;
    public static $FUENTE = 41;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* metodo para la vista del formulario para importar */
    public function importar()
    {
        $mensaje = "";
        return view('salud.ImporPadronEstablecimiento.Importar', compact('mensaje'));
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
                        $row['cod_unico'] .
                        $row['nombre_establecimiento'] .
                        $row['responsable'] .
                        $row['direccion'] .
                        $row['telefono'] .
                        $row['horario'] .
                        $row['doc_categorizacion'] .
                        $row['numero_documento'] .
                        $row['inicio_actividad'] .
                        $row['categoria'] .
                        $row['estado'] .
                        $row['institucion'] .
                        $row['clasificacion_eess'] .
                        $row['tipo_eess'] .
                        $row['cod_disa'] .
                        $row['disa'] .
                        $row['cod_red'] .
                        $row['red'] .
                        $row['cod_microrred'] .
                        $row['microrred'] .
                        $row['sec_ejec'] .
                        $row['ubigeo'] .
                        $row['norte'] .
                        $row['este'] .
                        $row['cota'] .
                        $row['camas'];
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
                    $nuevo = ImporPadronEstablecimiento::Create([
                        'importacion_id' => $importacion->id,
                        'cod_unico' => $row['cod_unico'],
                        'nombre_establecimiento' => $row['nombre_establecimiento'],
                        'responsable' => $row['responsable'],
                        'direccion' => $row['direccion'],
                        'telefono' => $row['telefono'],
                        'horario' => $row['horario'],
                        'doc_categorizacion' => $row['doc_categorizacion'],
                        'numero_documento' => $row['numero_documento'],
                        'inicio_actividad' => $row['inicio_actividad'],
                        'categoria' => $row['categoria'],
                        'estado' => $row['estado'],
                        'institucion' => $row['institucion'],
                        'clasificacion_eess' => $row['clasificacion_eess'],
                        'tipo_eess' => $row['tipo_eess'],
                        'cod_disa' => $row['cod_disa'],
                        'disa' => $row['disa'],
                        'cod_red' => $row['cod_red'],
                        'red' => $row['red'],
                        'cod_microrred' => $row['cod_microrred'],
                        'microrred' => $row['microrred'],
                        'sec_ejec' => $row['sec_ejec'],
                        'ubigeo' => $row['ubigeo'],
                        'norte' => $row['norte'],
                        'este' => $row['este'],
                        'cota' => $row['cota'],
                        'camas' => $row['camas']
                    ]);
                }
            }
        } catch (Exception $e) {
            // $importacion->estado = 'EL';
            // $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema - " . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        // try {
        //     DB::select('call par_pa_procesarImporPoblacion(?,?)', [$importacion->id, $poblacion->id]);
        // } catch (Exception $e) {
        //     $importacion->estado = 'EL';
        //     $importacion->save();

        //     $mensaje = "Error al procesar la normalizacion de datos." . $e;
        //     $tipo = 'danger';
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
        $data = PoblacionDetalle::where('pp.importacion_id', $rq->importacion_id)
            ->join('par_poblacion as pp', 'pp.id', '=', 'par_poblacion_detalle.poblacion_id')
            ->join('par_ubigeo as uu', 'uu.id', '=', 'par_poblacion_detalle.ubigeo_id')
            ->select('uu.codigo', 'par_poblacion_detalle.sexo', 'par_poblacion_detalle.edad', 'par_poblacion_detalle.total')->get();
        return DataTables::of($data)->make(true);
    }

    /* metodo para eliminar una importacion */
    public function eliminar($id)
    {
        // $poblacion = Poblacion::where('importacion_id', $id)->first();
        // PoblacionDetalle::where('poblacion_id', $poblacion->id)->delete();
        // $poblacion->delete();
        // Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }
}
