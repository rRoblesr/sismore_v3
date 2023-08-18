<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Imports\ImporGastosImport;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\BaseSiafWebDetalle;
use App\Models\Presupuesto\Entidad;
use App\Models\Presupuesto\ImporGastos;
use App\Models\Presupuesto\ImporSiafWeb;
use App\Repositories\Educacion\ImporGastosRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporSiafWebController extends Controller
{
    public $fuente = 24;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = FuenteImportacion::find($this->fuente);
        $mensaje = "";
        return view('presupuesto.ImporSiafWeb.Importar', compact('mensaje', 'fuente'));
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
                        $row['sec_ejec'] .
                        $row['cod_ue'] .
                        $row['sec_func'] .
                        $row['cod_cat_pres'] .
                        $row['tipo_prod_proy'] .
                        $row['cod_prod_proy'] .
                        $row['tipo_act_acc_obra'] .
                        $row['cod_act_acc_obra'] .
                        $row['cod_fun'] .
                        $row['cod_div_fun'] .
                        $row['cod_gru_fun'] .
                        $row['meta'] .
                        $row['cod_fina'] .
                        $row['unidad_medida'] .
                        $row['valor'] .
                        $row['cod_rub'] .
                        $row['cod_cat_gas'] .
                        $row['cod_tipo_trans'] .
                        $row['cod_gen'] .
                        $row['cod_subgen'] .
                        $row['cod_subgen_det'] .
                        $row['cod_esp'] .
                        $row['cod_esp_det'] .
                        $row['pia'] .
                        $row['pim'] .
                        $row['certificado'] .
                        $row['compromiso_anual'] .
                        $row['devengado'];
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
                    $gastos = ImporSiafWeb::Create([
                        'importacion_id' => $importacion->id,
                        'sec_ejec' => $row['sec_ejec'],
                        'cod_ue' => $row['cod_ue'],
                        'sec_func' => $row['sec_func'],
                        'cod_cat_pres' => $row['cod_cat_pres'],
                        'tipo_prod_proy' => $row['tipo_prod_proy'],
                        'cod_prod_proy' => $row['cod_prod_proy'],
                        'tipo_act_acc_obra' => $row['tipo_act_acc_obra'],
                        'cod_act_acc_obra' => $row['cod_act_acc_obra'],
                        'cod_fun' => $row['cod_fun'],
                        'cod_div_fun' => $row['cod_div_fun'],
                        'cod_gru_fun' => $row['cod_gru_fun'],
                        'meta' => $row['meta'],
                        'cod_fina' => $row['cod_fina'],
                        'unidad_medida' => $row['unidad_medida'],
                        'valor' => $row['valor'],
                        'cod_rub' => $row['cod_rub'],
                        'cod_cat_gas' => $row['cod_cat_gas'],
                        'cod_tipo_trans' => $row['cod_tipo_trans'],
                        'cod_gen' => $row['cod_gen'],
                        'cod_subgen' => $row['cod_subgen'],
                        'cod_subgen_det' => $row['cod_subgen_det'],
                        'cod_esp' => $row['cod_esp'],
                        'cod_esp_det' => $row['cod_esp_det'],
                        'pia' => $row['pia'],
                        'pim' => $row['pim'],
                        'certificado' => $row['certificado'],
                        'compromiso_anual' => $row['compromiso_anual'],
                        'devengado' => $row['devengado'],
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
            $procesar = DB::select('call pres_pa_procesarImporSiafWeb(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
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
                'GASTO',
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
        $data = ImporGastosRepositorio::listaImportada($importacion_id);
        return DataTables::of($data)->make(true);
    }

    public function eliminar($id)
    {
        /* $entidad = Importacion::find($id);
        $entidad->estado = 'EL';
        $entidad->save(); */

        $bs = BaseSiafWeb::where('importacion_id', $id)->first();
        ImporSiafWeb::where('importacion_id', $id)->delete();
        if ($bs) {
            BaseSiafWebDetalle::where('basesiafweb_id', $bs->id)->delete();
            BaseSiafWeb::find($bs->id)->delete();
        }
        Importacion::find($id)->delete();

        return response()->json(array('status' => true));
    }
}
