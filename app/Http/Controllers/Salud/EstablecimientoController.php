<?php

namespace App\Http\Controllers\Salud;

use App\Exports\pactoregional1Export;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\SFL;
use App\Models\Parametro\Anio;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\ImporPadronActas;
use App\Models\Salud\PadronActas;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\SFLRepositorio;
use App\Repositories\Parametro\IndicadorGeneralMetaRepositorio;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Salud\EstablecimientoRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EstablecimientoController extends Controller
{
    public $mes = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
    public $mesname = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
    public static $pacto1_anio = 2023;
    public static $pacto1_mes = 5;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function cargarRed(Request $rq)
    {
        $red = EstablecimientoRepositorio::listRed($rq->sector, $rq->municipio);

        return response()->json(compact('red'));
    }

    public function cargarMicrored(Request $rq)
    {
        $micro = EstablecimientoRepositorio::listMicrored($rq->sector, $rq->municipio, $rq->red);

        return response()->json(compact('micro'));
    }
    
    public function cargarEESS(Request $rq)
    {
        $eess = EstablecimientoRepositorio::listEESS($rq->sector, $rq->municipio, 0);

        return response()->json(compact('eess'));
    }

    public function ajax_edit($id)
    {
        $eess = Establecimiento::find($id);

        return response()->json(compact('eess'));
    }

    public function registro_listarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = EstablecimientoRepositorio::listar(2, $rq->municipio, $rq->red, $rq->microred);
        $data = [];
        foreach ($query as $key => $value) {
            if ($rq->registrador == 0) {
                $nactas = PadronActas::whereBetween('fecha_envio', [$rq->fechai, $rq->fechaf])->where('establecimiento_id', $value->id)->select(DB::raw('sum(nro_archivos) as nactas'))->get();
            } else {
                $nactas = PadronActas::where('fecha_envio', $rq->fechaf)->where('establecimiento_id', $value->id)->select(DB::raw('sum(nro_archivos) as nactas'))->get();
            }

            $boton = '';
            if (session('usuario_sector') == 2 && session('usuario_nivel') == 1) {
                $boton .= '<button class="btn btn-xs btn-success waves-effect waves-light" data-toggle="modal" data-target="#modal_form"
                    onclick="abrirnuevo()"></i> Registrar</button>';
            } else {
                $boton .= '<button class="btn btn-xs btn-primary waves-effect waves-light" data-toggle="modal" data-target="#modal_registros"
                    onclick="verdatos(' . $value->id . ')"><i class="far fa-eye"></i> Registros</button>';
            }
            $data[] = array(
                $key + 1,
                $value->red,
                $value->microred,
                sprintf('%08d', $value->cod_unico),
                $value->eess,
                $nactas->count() > 0 ? ($nactas->first()->nactas > 0 ? $nactas->first()->nactas : 0)  : 0,
                $boton,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "municipio" =>  $rq->municipio,
            "query" => $query,
        );
        return response()->json($result);
    }
}
