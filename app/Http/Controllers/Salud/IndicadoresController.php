<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Anio;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Repositories\Parametro\IndicadorGeneralMetaRepositorio;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

class IndicadoresController extends Controller
{
    public $mes = ['ENE', 'FEB', 'MAR', 'ABR', 'MAYO', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
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
                $actualizado = 'Actualizado al 29 de febrero del 2023';
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
                $gls = 0;
                $gln = 0;
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
                    foreach ($base as $key => $valuex) {
                        if ($valuex->name == $value->name) {
                            $info['dat2'][] = (int)$value->y;
                        }
                    }
                }
                return response()->json(compact('info', 'base2'));
            case 'tabla1':
                // $aa = Anio::find($rq->anio);
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($rq->indicador, $rq->anio);
                // return response()->json(['rq' => $rq->all(), 'base' => $aa]);

                $excel = view('salud.Indicadores.PactoRegionalDetalle1tabla1', compact('base'))->render();
                return response()->json(compact('excel', 'base'));



            default:
                return [];
        }
    }

    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
