<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseModificacion;
use App\Models\Presupuesto\BaseModificacionDetalle;
use App\Models\Presupuesto\Entidad;
use App\Models\Presupuesto\ImporModificaciones;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporModificacionesController extends Controller
{
    public $fuente = 26;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporModificaciones.Importar', compact('mensaje', 'fuente'));
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
                        $row['cod_pliego'] .
                        $row['cod_ue'] .
                        $row['notas'] .
                        $row['fecha_solicitud'] .
                        $row['fecha_aprobacion'] .
                        $row['cod_tipo_mod'] .
                        $row['tipo_modificacion'] .
                        $row['documento'] .
                        $row['referencia'] .
                        $row['dispositivo_legal'] .
                        $row['tipo_ingreso'] .
                        $row['excepcion_limite'] .
                        $row['justificacion'] .
                        $row['tipo_financiamiento'] .
                        $row['entidad_origen'] .
                        $row['tipo_presupuesto'] .
                        $row['sec_func'] .
                        $row['cod_cat_pres'] .
                        $row['tipo_prod_proy'] .
                        $row['cod_prod_proy'] .
                        $row['tipo_act_acc_obra'] .
                        $row['cod_act_acc_obra'] .
                        $row['meta'] .
                        $row['cod_fina'] .
                        $row['cod_rub'] .
                        $row['cod_cat_gas'] .
                        $row['cod_tipo_trans'] .
                        $row['cod_gen'] .
                        $row['cod_subgen'] .
                        $row['cod_subgen_det'] .
                        $row['cod_esp'] .
                        $row['cod_esp_det'] .
                        $row['anulacion'] .
                        $row['credito'];
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
                'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $gastos = ImporModificaciones::Create([
                        'importacion_id' => $importacion->id,
                        'cod_pliego' => $row['cod_pliego'],
                        'cod_ue' => $row['cod_ue'],
                        'notas' => $row['notas'],
                        'fecha_solicitud' => $row['fecha_solicitud'],
                        'fecha_aprobacion' => $row['fecha_aprobacion'],
                        'cod_tipo_mod' => $row['cod_tipo_mod'],
                        'tipo_modificacion' => $row['tipo_modificacion'],
                        'documento' => $row['documento'],
                        'referencia' => $row['referencia'],
                        'dispositivo_legal' => $row['dispositivo_legal'],
                        'tipo_ingreso' => $row['tipo_ingreso'],
                        'excepcion_limite' => $row['excepcion_limite'],
                        'justificacion' => $row['justificacion'],
                        'tipo_financiamiento' => $row['tipo_financiamiento'],
                        'entidad_origen' => $row['entidad_origen'],
                        'tipo_presupuesto' => $row['tipo_presupuesto'],
                        'sec_func' => $row['sec_func'],
                        'cod_cat_pres' => $row['cod_cat_pres'],
                        'tipo_prod_proy' => $row['tipo_prod_proy'],
                        'cod_prod_proy' => $row['cod_prod_proy'],
                        'tipo_act_acc_obra' => $row['tipo_act_acc_obra'],
                        'cod_act_acc_obra' => $row['cod_act_acc_obra'],
                        'meta' => $row['meta'],
                        'cod_fina' => $row['cod_fina'],
                        'cod_rub' => $row['cod_rub'],
                        'cod_cat_gas' => $row['cod_cat_gas'],
                        'cod_tipo_trans' => $row['cod_tipo_trans'],
                        'cod_gen' => $row['cod_gen'],
                        'cod_subgen' => $row['cod_subgen'],
                        'cod_subgen_det' => $row['cod_subgen_det'],
                        'cod_esp' => $row['cod_esp'],
                        'cod_esp_det' => $row['cod_esp_det'],
                        'anulacion' => $row['anulacion'],
                        'credito' => $row['credito']
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
            $procesar = DB::select('call pres_pa_procesarImporModificaciones(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
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

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" id="eliminar' . $value->id . '" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                'MODIFICACIÓN',
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                //$value->fuente . $value->id,
                $nom . ' ' . $ape,
                ($ent ? $ent->abreviado : ''),
                date("d/m/Y", strtotime($value->created_at)),
                //$value->comentario,
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
                $boton /* . '&nbsp;' . $boton2, */
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
        /* $data = ImporGastosRepositorio::listaImportada($importacion_id);
        return DataTables::of($data)->make(true); */
        return null;
    }

    public function eliminar($id)
    {
        /* $entidad = Importacion::find($id);
        $entidad->estado = 'EL';
        $entidad->save(); */

        $bm = BaseModificacion::where('importacion_id', $id)->first();
        ImporModificaciones::where('importacion_id', $id)->delete();
        if ($bm) {
            BaseModificacionDetalle::where('basemodificacion_id', $bm->id)->delete();
            BaseModificacion::find($bm->id)->delete();
        }
        Importacion::find($id)->delete();

        return response()->json(array('status' => true));
    }
}
