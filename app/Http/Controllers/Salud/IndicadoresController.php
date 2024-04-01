<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Anio;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Salud\ImporPadronActas;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\IndicadorGeneralMetaRepositorio;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

class IndicadoresController extends Controller
{
    public $mes = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
    public $mesname = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
    public static $pacto1_anio = 2023;
    public static $pacto1_mes = 5;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PactoRegional()
    {
        $sector = 14;
        $instrumento = 8;
        $inds = IndicadorGeneral::select('id', 'codigo', 'nombre', 'descripcion', 'numerador', 'denominador', 'instrumento_id', 'tipo_id', 'dimension_id', 'unidad_id', 'frecuencia_id', 'fuente_dato', 'anio_base', 'valor_base', 'sector_id', 'oficina_id', 'estado')->where('sector_id', $sector)->where('instrumento_id', $instrumento)->where('estado', '0')->get();
        return view('salud.Indicadores.PactoRegional', compact('inds'));
    }

    public function PactoRegionalDetalle($indicador_id)
    {
        $ind = IndicadorGeneral::find($indicador_id);
        switch ($ind->codigo) {
            case 'DITSALUD01':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = 2023;
                return view('salud.Indicadores.PactoRegionalDetalle1', compact('actualizado', 'anio', 'provincia', 'aniomax', 'ind'));
            case 'DITSALUD02':
                return '';
            case 'DITSALUD03':
                return '';
            case 'DITSALUD04':
                return '';
            case 'DITSALUD05':
                return '';
            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    public function PactoRegionalDetalleReports(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $ri = 0;
                $gl = IndicadorGeneralMetaRepositorio::getPacto1GL($rq->indicador, $rq->anio);
                $gls = IndicadorGeneralMetaRepositorio::getPacto1GLS($rq->indicador, $rq->anio);
                $gln = IndicadorGeneralMetaRepositorio::getPacto1GLN($rq->indicador, $rq->anio);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, 0);
                $info = [];
                foreach ($base as $key => $value) {
                    $info['cat'][] = $this->mes[$value->name - 1];
                    $value->y = (int)$value->y;
                }
                foreach ($base as $key => $value) {
                    if ($key == 0)
                        $vv = (int)$value->y;
                    if ($key > 0) {
                        $value->y += $vv;
                        $vv = (int)$value->y;
                    }
                    $info['dat'][] = $value->y;
                }
                return response()->json(compact('info'));
            case 'anal2':
                $base = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, 0);
                $base2 = IndicadorGeneralMetaRepositorio::getPacto1Mensual2($rq->anio, 0);
                $info = [];
                foreach ($base as $key => $value) {
                    $info['cat'][] = $this->mes[$value->name - 1];
                    $info['dat'][] = (int)$value->y;
                }
                foreach ($base2 as $key => $value) {
                    $info['dat2'][] = (int)$value->y;
                }
                return response()->json(compact('info'));
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($rq->indicador, $rq->anio);

                $excel = view('salud.Indicadores.PactoRegionalDetalle1tabla1', compact('base'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla2($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalDetalle1tabla2', compact('base','aniob'))->render();
                return response()->json(compact('excel', 'base'));


            default:
                return [];
        }
    }

    public function exportarPDF($id)
    {
        $ind = IndicadorGeneral::select('codigo', 'ficha_tecnica')->where('id', $id)->first();
        if ($ind->ficha_tecnica) {
            header('Content-Type: application/pdf');
            echo base64_decode($ind->ficha_tecnica);

            // $b64d = base64_decode($ind->ficha_tecnica);
            // $pdf = fopen('aaa.pdf', 'w');
            // fwrite($pdf, $b64d);
            // fclose($pdf);
            // echo $b64d;
            //echo file_put_contents("aaaa.pdf", base64_decode($ind->ficha_tecnica));
        } else {
            echo 'archivo PDF no encontrado';
        }
    }


    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
