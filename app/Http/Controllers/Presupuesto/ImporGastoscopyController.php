<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\ImporGastos;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ImporGastosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        return view('presupuesto.ImporGastos.Importar', compact('mensaje'));
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
        /* $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo); */

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, 13);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, 13);
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
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    if ($key > 0) break;
                    $cadena =  $cadena .
                        $row['anio'] .
                        $row['mes'] .
                        $row['cod_tipo_gob'] .
                        $row['tipo_gobierno'] .
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
                'fuenteImportacion_id' => 13, // valor predeterminado
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
                        'cod_tipo_gob' => $row['cod_tipo_gob'],
                        'tipo_gobierno' => $row['tipo_gobierno'],
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

        /* try {
            $procesar = DB::select('call edu_pa_procesarImporMatricula(?,?)', [$importacion->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e->getMessage();
            $this->json_output(400, $mensaje);
        } */

        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
    }
    /*

    public function importarListado($importacion_id)
    {
        return view('Vivienda.PadronEmapacopsa.ListaImportada', compact('importacion_id'));
    }
    public function importarListadoDT($importacion_id)
    {
        $Lista = PadronEmapacopsaRepositorio::ListarImportados($importacion_id);

        return  datatables()->of($Lista)->toJson();
    }
    public function importarAprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
        return  view('Vivienda.PadronEmapacopsa.Aprobar', compact('importacion_id', 'importacion'));
    }

    public function importarAprobarGuardar($importacion_id)
    {
        $procesar = DB::select('call viv_pa_procesarEmapacopsa(?)', [$importacion_id]);// que sera esto :o :o :o  XDXDXD
        return view('correcto');
    } */
}
