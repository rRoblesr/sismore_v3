<?php

namespace App\Http\Controllers\Salud;

use App\Exports\EstablecimientoExport;
use App\Exports\TableroCalidadExport;
use App\Http\Controllers\Controller;
use App\Models\Parametro\Mes;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\PadronActas;
use App\Repositories\Educacion\ImportacionRepositorio;
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

    public function cargarRedSelect($red)
    {
        $micro = DB::select("SELECT * FROM sal_red where id in(
        SELECT DISTINCT red_id from sal_microrred where id in( 
        SELECT DISTINCT microrred_id FROM `sal_establecimiento` 
        where red_id=$red and cod_disa=34 and categoria in ('I-1','I-2','I-3','I-4') and institucion in ('GOBIERNO REGIONAL','MINSA') and estado='ACTIVO'))");
        return  response()->json($micro);
    }

    public function cargarMicroredSelect($red)
    {
        $micro = DB::select("SELECT * from sal_microrred where id in( 
        SELECT DISTINCT microrred_id FROM `sal_establecimiento` 
        where red_id=$red and cod_disa=34 and categoria in ('I-1','I-2','I-3','I-4') and institucion in ('GOBIERNO REGIONAL','MINSA') and estado='ACTIVO')");
        return  response()->json($micro);
    }

    public function cargarEESSSelect($microred)
    {
        $micro = DB::select("SELECT * FROM `sal_establecimiento` 
        where microrred_id=$microred and cod_disa=34 and categoria in ('I-1','I-2','I-3','I-4') and institucion in ('GOBIERNO REGIONAL','MINSA') and estado='ACTIVO' 
        order by nombre_establecimiento");
        return  response()->json($micro);
    }

    public function cargarMicroredUcayaliSelect($red)
    {
        $micro = EstablecimientoRepositorio::listMicrorredUcayali_select($red);
        return  response()->json($micro);
    }

    public function cargarEESS(Request $rq)
    {
        $eess = EstablecimientoRepositorio::listEESS($rq->sector, $rq->municipio, $rq->red, $rq->microred);
        return response()->json(compact('eess'));
    }

    public static function ubigeo_provincia_select()
    {
        return Establecimiento::distinct()->from('sal_establecimiento as e')
            ->select('p.id', 'p.codigo', 'p.nombre')
            ->join('par_ubigeo as d', 'd.id', '=', 'e.ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->where('e.cod_disa', 34)
            ->where('e.estado', 'ACTIVO')
            ->get();
    }

    public static function ubigeo_distrito_select($provincia)
    {
        $filtros = function ($query) use ($provincia) {
            if ($provincia > 0) $query->where('p.id', $provincia);
        };
        return Establecimiento::distinct()->from('sal_establecimiento as e')
            ->select('d.id', 'd.codigo', 'd.nombre')
            ->join('par_ubigeo as d', 'd.id', '=', 'e.ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->where('e.cod_disa', 34)
            ->where('e.estado', 'ACTIVO')
            ->tap($filtros)
            ->get();
    }

    public static function ubigeo_red_select($provincia, $distrito)
    {
        $filtros = function ($query) use ($provincia, $distrito) {
            if ($provincia > 0) $query->where('p.id', $provincia);
            if ($distrito > 0) $query->where('d.id', $distrito);
        };
        return Establecimiento::distinct()->from('sal_establecimiento as e')
            ->select('r.id', 'r.codigo', 'r.nombre')
            ->join('par_ubigeo as d', 'd.id', '=', 'e.ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->where('e.cod_disa', 34)
            ->where('e.estado', 'ACTIVO')
            ->tap($filtros)
            ->get();
    }

    public static function ubigeo_microrred_select($provincia, $distrito, $red)
    {
        $filtros = function ($query) use ($provincia, $distrito, $red) {
            if ($provincia > 0) $query->where('p.id', $provincia);
            if ($distrito > 0) $query->where('d.id', $distrito);
            if ($red > 0) $query->where('r.id', $red);
        };
        return Establecimiento::distinct()->from('sal_establecimiento as e')
            ->select('m.id', 'm.codigo', 'm.nombre')
            ->join('par_ubigeo as d', 'd.id', '=', 'e.ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->where('e.cod_disa', 34)
            ->where('e.estado', 'ACTIVO')
            ->tap($filtros)
            ->get();
    }

    public function ajax_edit($id)
    {
        $eess = Establecimiento::find($id);
        return response()->json(compact('eess'));
    }

    public function ajax_codunico02($cod_unico)
    {
        $eess = Establecimiento::select('codigo_unico', 'nombre_establecimiento as nombre')->where('cod_unico', $cod_unico)->get()->first();
        return response()->json($eess);
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
            // "municipio" =>  $rq->municipio,
            // "query" => $query,
        );
        return response()->json($result);
    }

    public function registro_listarDT2(Request $rq)
    {
        $mes = Mes::all();
        $anioA = date('Y');
        $mesA = $rq->anio == date('Y') ? date('m') : 12;

        $tabla = '<table id="tabla2" class="table table-striped table-bordered font-12">
                <thead class="cabecera-dataTable table-success-0 text-white">
                    <tr>
                        <th class="text-center">Nº</th>
                        <th class="text-center">MUNICIPALIDAD</th>
                        <th class="text-center">CODIGO IPRESS</th>
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
                    $conteo = PadronActas::from('sal_padron_actas as pa')->where('pa.establecimiento_id', $value->id)->where('pa.fecha_envio', 'like', $rq->anio . '-' . str_pad($mm->codigo, 2, '0', STR_PAD_LEFT) . '-%')->sum('pa.nro_archivos');
                    if ($conteo == 0) {
                        $tabla .= '<td class="text-center text-danger"><button type="button" class="btn btn-xs btn-outline-danger waves-effect" onclick="abrir_actas_registadas(' . $value->id . ',' . $rq->registrador . ',`' . $value->eess . '`,' . $mm->codigo . ')">' . $conteo . '</button></td>';
                    } else {
                        $tabla .= '<td class="text-center text-primary font-weight-bold"><button type="button" class="btn btn-xs btn-outline-primary waves-effect" onclick="abrir_actas_registadas(' . $value->id . ',' . $rq->registrador . ',`' . $value->eess . '`,' . $mm->codigo . ')">' . $conteo . '</button></td>';
                    }
                } else {
                    $tabla .= '<td class="text-center"></td>';
                }
            }

            $conteo = PadronActas::from('sal_padron_actas as pa')->where('pa.establecimiento_id', $value->id)->where('pa.fecha_envio', 'like', $rq->anio . '-%')->sum('pa.nro_archivos');
            if ($conteo == 0) {
                $tabla .= '<td class="text-center text-danger table-purple">' . $conteo . '</td>';
            } else {
                $tabla .= '<td class="text-center text-primary font-weight-bold table-purple">' . $conteo . '</td>';
            }
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
                if ($conteo == 0) {
                    $tabla .= ' <td class="text-center text-white font-weight-bold">' . $conteo . '</td>';
                } else {
                    $tabla .= ' <td class="text-center text-dark font-weight-bold">' . $conteo . '</td>';
                }
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

    public function autocompletarEntidad(Request $rq)
    {
        $term = $rq->term;
        switch ($rq->tipoentidad) {
            case '2':
                $query = DB::table('sal_red')
                    ->where('cod_disa', '34')->where(function ($q) use ($term) {
                        $q->where('nombre', 'like', '%' . $term . '%')->orWhere('codigo', 'like', '%' . $term . '%');
                    })->get();

                $data = $query->count() > 0
                    ? $query->map(fn($value) => ['label' => $value->codigo . ' ' . $value->nombre, 'id' => $value->id])->toArray()
                    : [['label' => 'SIN REGISTROS', 'id' => 0]];
                break;
            case '3':
                $query = DB::table('sal_microrred as m')->join('sal_red as r', 'r.id', '=', 'm.red_id')->select('m.id', 'm.codigo', 'm.nombre', 'r.nombre as red')
                    ->where('r.cod_disa', '34')->where(function ($q) use ($term) {
                        $q->where('m.nombre', 'like', '%' . $term . '%')->orWhere('m.codigo', 'like', '%' . $term . '%');
                    })->get();

                $data = $query->count() > 0
                    ? $query->map(fn($value) => ['label' => $value->codigo . ' ' . $value->nombre . ' | ' . $value->red, 'id' => $value->id])->toArray()
                    : [['label' => 'SIN REGISTROS', 'id' => 0]];
                break;
            case '4':
                $query = EstablecimientoRepositorio::queAtiendenAutocompletar($term);
                $data = $query->count() > 0
                    ? $query->map(fn($value) => ['label' => str_pad($value->cod_unico, 8, '0', STR_PAD_LEFT) . ' | ' . $value->nombre_establecimiento, 'id' => $value->id])->toArray()
                    : [['label' => 'SIN REGISTROS', 'id' => 0]];
                break;

            default:
                # code...
                break;
        }


        return response()->json($data);
    }


    /* DASHBOARD */

    public function dashboard()
    {
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronEstablecimientoController::$FUENTE);
        $provincias = $this->ubigeo_provincia_select(); //UbigeoRepositorio::provincia_select('25');
        $red = EstablecimientoRepositorio::listRedUcayali_select();
        $actualizado = 'Actualizado al ' . $imp->dia . '/' . str_pad($imp->mes, 2, '0', STR_PAD_LEFT) . '/' . $imp->anio;
        return view('salud.Establecimiento.dashboard', compact('actualizado', 'provincias', 'red'));
    }

    public function dashboardContenido(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $data = EstablecimientoRepositorio::dashboardContenidoHead($rq->div, $rq->provincia, $rq->distrito, $rq->red, $rq->microrred);
                $card1 = number_format($data['card1'], 0);
                $card2 = number_format($data['card2'], 0);
                $card3 = number_format($data['card3'], 0);
                $card4 = number_format($data['card4'], 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));

            case 'tabla1':
                $base = EstablecimientoRepositorio::dashboardContenidoTabla1($rq->div, $rq->provincia, $rq->distrito, $rq->red, $rq->microrred);
                $excel = view('salud.Establecimiento.dashboardTabla1', compact('base'))->render();
                return response()->json(compact('base', 'excel'));

            case 'tabla2':
                $base = EstablecimientoRepositorio::dashboardContenidoTabla2($rq->div, $rq->provincia, $rq->distrito, $rq->red, $rq->microrred);
                $excel = view('salud.Establecimiento.dashboardTabla2', compact('base'))->render();
                return response()->json(compact('base', 'excel'));


            case 'tabla3':
                $base = EstablecimientoRepositorio::dashboardContenidoTabla3($rq->div, $rq->provincia, $rq->distrito, $rq->red, $rq->microrred);
                $excel = view('salud.Establecimiento.dashboardTabla3', compact('base'))->render();
                return response()->json(compact('excel'));

            default:
                # code...
                return [];
        }
    }

    public function dashboardContenidoDownload($div, $provincia, $distrito, $red, $microrred)
    {
        switch ($div) {
            case 'tabla1':
                $base = EstablecimientoRepositorio::dashboardContenidoTabla1($div, $provincia, $distrito, $red, $microrred);
                return compact('base');

            case 'tabla2':
                $base = EstablecimientoRepositorio::dashboardContenidoTabla2($div, $provincia, $distrito, $red, $microrred);
                return compact('base');

            case 'tabla3':
                $base = EstablecimientoRepositorio::dashboardContenidoTabla3($div, $provincia, $distrito, $red, $microrred);
                return compact('base');

            default:
                # code...
                return [];
        }
    }

    public function dashboardExport($div, $provincia, $distrito, $red, $microrred)
    {
        switch ($div) {
            case 'tabla1':
                $name = 'Número de establecimientos de salud y centros de apoyo activos por institucion, según categoría a nivel regional.xlsx';
                break;
            case 'tabla2':
                $name = 'Número de establecimientos de salud y centros de apoyo activos por distritos, según categoría a nivel regional.xlsx';
                break;
            case 'tabla3':
                $name = 'Listado de establecimiento de salud de ucayali.xlsx';
                break;
            default:
                $name = '';
                break;
        }
        return Excel::download(new EstablecimientoExport($div, $provincia, $distrito, $red, $microrred), $name);
    }
}
