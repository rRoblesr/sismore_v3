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
        $eess = EstablecimientoRepositorio::listEESS($rq->sector, $rq->municipio, $rq->red, $rq->microred);

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

    public function registro_listarDT2(Request $rq)
    {
        $mes = Mes::all();
        $anioA = date('Y');
        $mesA = $rq->anio == date('Y') ? date('m') : 12;

        $tabla = '<table id="tabla2" class="table table-sm table-striped table-bordered font-12">
                <thead class="cabecera-dataTable table-success-0 text-white">
                    <tr>
                        <th class="text-center">Nº</th>
                        <th class="text-center">MUNICIPALIDAD</th>
                        <th class="text-center">CODIGO UNICO</th>
                        <th class="text-center">ESTABLECIMIENTO</th>';
        foreach ($mes as $key => $mm) {
            $tabla .= '<th class="text-center">' . $mm->abreviado . '</th>';
        }

        $tabla .= '     <th class="text-center">TOTAL</th>
                    </tr>
                </thead>
                <tbody>';

        $query = EstablecimientoRepositorio::listarMunicipalidades(2, $rq->municipio, $rq->red, $rq->microred);

        foreach ($query as $key => $value) {
            $tabla .= '<tr>';
            $tabla .= '<td class="text-center">' . ($key + 1) . '</td>';
            $tabla .= '<td class="text-left">' . $value->muni . '</td>';
            $tabla .= '<td class="text-center">' . sprintf('%08d', $value->cod_unico) . '</td>';
            $tabla .= '<td class="text-left table-success text-dark">' . $value->eess . '</td>';
            foreach ($mes as $mm) {
                if ($mm->codigo <= $mesA) {
                    $conteo = PadronActas::from('sal_padron_actas as pa')
                        ->join('sal_establecimiento as es', 'es.id', '=', 'pa.establecimiento_id')
                        ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
                        ->join('sal_red as re', 're.id', '=', 'mi.red_id')
                        ->where('pa.establecimiento_id', $value->id)
                        ->where('pa.fecha_envio', 'like', $rq->anio . '-' . str_pad($mm->codigo, 2, '0', STR_PAD_LEFT) . '-%');
                    $conteo = $conteo->sum('pa.nro_archivos');
                    if ($conteo == 0) $tabla .= '<td class="text-center text-danger">' . $conteo . '</td>';
                    else $tabla .= '<td class="text-center text-primary font-weight-bold">' . $conteo . '</td>';
                } else {
                    $tabla .= '<td class="text-center"></td>';
                }
            }

            $conteo = PadronActas::from('sal_padron_actas as pa')
                ->join('sal_establecimiento as es', 'es.id', '=', 'pa.establecimiento_id')
                ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
                ->join('sal_red as re', 're.id', '=', 'mi.red_id')
                ->where('pa.establecimiento_id', $value->id)
                ->where('pa.fecha_envio', 'like', $rq->anio . '-%');
            $conteo = $conteo->sum('pa.nro_archivos');

            if ($conteo == 0) $tabla .= '<td class="text-center text-danger table-purple">' . $conteo . '</td>';
            else $tabla .= '<td class="text-center text-primary font-weight-bold table-purple">' . $conteo . '</td>';
            // $tabla .= '<td class="text-center">' . $conteo . '</td>';
            $tabla .= '</tr>';
        }

        $tabla .= '</tbody>';
        $tabla .= '<tfoot class="table-success-0 text-white">
                    <tr>
                        <td class="text-center" colspan="4">TOTAL DE ACTAS POR MES</td>';

        foreach ($mes as $key => $mm) {
            if ($mm->codigo <= $mesA) {
                $conteo = PadronActas::where('sal_padron_actas.fecha_envio', 'like', $rq->anio . '-' . str_pad($mm->codigo, 2, '0', STR_PAD_LEFT) . '-%');
                if ($rq->municipio > 0) $conteo = $conteo->where('sal_padron_actas.ubigeo_id', '=', $rq->municipio);
                $conteo = $conteo->sum('sal_padron_actas.nro_archivos');
                if ($conteo == 0) $tabla .= ' <td class="text-center text-white font-weight-bold">' . $conteo . '</td>';
                else $tabla .= ' <td class="text-center text-dark font-weight-bold">' . $conteo . '</td>';
            } else {
                $tabla .= '<td class="text-center"></td>';
            }
        }

        $conteo = PadronActas::where('sal_padron_actas.fecha_envio', 'like', $rq->anio . '-%');
        if ($rq->municipio > 0) $conteo = $conteo->where('sal_padron_actas.ubigeo_id', '=', $rq->municipio);
        $conteo = $conteo->sum('sal_padron_actas.nro_archivos');
        $tabla .= '     <td class="text-center text-dark font-weight-bold">' . $conteo . '</td>
                    </tr>
                </tfoot></table>';
        return response()->json(['query' => $query, 'tabla' => $tabla, 'rq' => $rq->all()]);
    }
}
