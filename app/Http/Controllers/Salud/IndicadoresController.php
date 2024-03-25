<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Anio;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

class IndicadoresController extends Controller
{
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
                $anio = Anio::orderBy('anio')->get();
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

                // $valor2 = ServiciosBasicosRepositorio::principalTabla($rq->div . '2', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                // $valor3 = ServiciosBasicosRepositorio::principalTabla($rq->div . '3', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                // $valor1 = 100 * $valor3 / $valor2;
                // $valor4 = $valor2 - $valor3;
                // $valor1 = number_format($valor1, 1);
                // $valor2 = number_format($valor2, 0);
                // $valor3 = number_format($valor3, 0);
                // $valor4 = number_format($valor4, 0);
                // if ($rq->servicio == 1) {
                //     $tservicio = 'Agua';
                // } else if ($rq->servicio == 2) {
                //     $tservicio = 'Desague';
                // } else if ($rq->servicio == 3) {
                //     $tservicio = 'Luz';
                // } else if ($rq->servicio == 4) {
                //     $tservicio = 'Tres Servicios';
                // } else if ($rq->servicio == 5) {
                //     $tservicio = 'Internet';
                // }
                // return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'tservicio'));
                return [];

            case 'anal1':
                return response()->json(['ss' => 234324]);
            case 'tabla1':
                $aa = Anio::find($rq->anio);
                $base = IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicador)->where('anio', $aa->anio)
                    ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
                // return response()->json(['rq' => $rq->all(), 'base' => $aa]);

                $excel = view('salud.Indicadores.PactoRegionalDetalle1tabla1', compact('base'))->render();
                return response()->json(compact('excel'));



            default:
                return [];
        }
    }

    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
