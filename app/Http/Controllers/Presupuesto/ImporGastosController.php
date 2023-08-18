<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Imports\ImporGastosImport;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseGastosDetalle;
use App\Models\Presupuesto\Entidad;
use App\Models\Presupuesto\ImporGastos;
use App\Repositories\Educacion\ImporGastosRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ImporGastosController extends Controller
{
    public $fuente = 13;
    public function __construct()
    {
        $this->middleware('auth');
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

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        $cadena = '';
        try {
            foreach ($array as $value) {
                foreach ($value as $key => $row) {
                    if ($key > 0) break;
                    $cadena =  $cadena .
                        $row['anio'] .
                        $row['mes'] .
                        $row['cod_niv_gob'] .
                        $row['nivel_gobierno'] .
                        $row['cod_sector'] .
                        $row['sector'] .
                        $row['cod_pliego'] .
                        $row['pliego'] .
                        $row['cod_ubigeo'] .
                        $row['sec_ejec'] .
                        $row['cod_ue'] .
                        $row['unidad_ejecutora'] .
                        $row['sec_func'] .
                        $row['cod_cat_pres'] .
                        $row['categoria_presupuestal'] .
                        $row['tipo_prod_proy'] .
                        $row['cod_prod_proy'] .
                        $row['producto_proyecto'] .
                        $row['tipo_act_acc_obra'] .
                        $row['cod_act_acc_obra'] .
                        $row['actividad_accion_obra'] .
                        $row['cod_fun'] .
                        $row['funcion'] .
                        $row['cod_div_fun'] .
                        $row['division_funcional'] .
                        $row['cod_gru_fun'] .
                        $row['grupo_funcional'] .
                        $row['meta'] .
                        $row['cod_fina'] .
                        $row['finalidad'] .
                        $row['cod_fue_fin'] .
                        $row['fuente_financiamiento'] .
                        $row['cod_rub'] .
                        $row['rubro'] .
                        $row['cod_tipo_rec'] .
                        $row['tipo_recurso'] .
                        $row['cod_cat_gas'] .
                        $row['categoria_gasto'] .
                        $row['cod_tipo_trans'] .
                        $row['cod_gen'] .
                        $row['generica'] .
                        $row['cod_subgen'] .
                        $row['subgenerica'] .
                        $row['cod_subgen_det'] .
                        $row['subgenerica_detalle'] .
                        $row['cod_esp'] .
                        $row['especifica'] .
                        $row['cod_esp_det'] .
                        $row['especifica_detalle'] .
                        $row['pia'] .
                        $row['pim'] .
                        $row['certificado'] .
                        $row['compromiso_anual'] .
                        $row['compromiso_mensual'] .
                        $row['devengado'] .
                        $row['girado'];
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
                }
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

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" id="eliminar' . $value->id . '" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                'GASTO PRESUPUESTAL',
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                /* $value->fuente, */
                $nom . ' ' . $ape,
                ($ent ? $ent->abreviado : ''),
                date("d/m/Y", strtotime($value->created_at)),
                /* $value->comentario, */
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
        /*  $imp = Importacion::find($id);
        $imp->estado = 'EL';
        $imp->save(); */

        $bg = BaseGastos::where('importacion_id', $id)->first();
        ImporGastos::where('importacion_id', $id)->delete();
        if ($bg) {
            BaseGastosDetalle::where('basegastos_id', $bg->id)->delete();
            BaseGastos::find($bg->id)->delete();
        }
        Importacion::find($id)->delete();

        return response()->json(array('status' => true));
    }
}
