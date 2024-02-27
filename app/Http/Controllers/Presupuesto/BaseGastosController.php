<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\BaseGastosExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\GenericaGasto;
use App\Models\Presupuesto\Sector;
use App\Models\Presupuesto\SubGenericaGasto;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BaseGastosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* nivel gobiernos */
    public function nivelgobiernos()
    {
        $gobs = TipoGobierno::where('id', '!=', 4)->orderBy('pos', 'asc')->get();
        $mensaje = "";
        return view('Presupuesto.BaseGastos.NivelGobiernos', compact('mensaje', 'gobs'));
    }

    public function cargarsector(Request $rq)
    {
        $sectors = Sector::where('tipogobierno_id', $rq->get('gobierno'))->get(); //BaseGastosRepositorio::cargarsector($rq->get('gobierno'));
        return response()->json(compact('sectors'));
    }

    public function cargarue(Request $rq)
    {
        $ues = UnidadEjecutora::select('pres_unidadejecutora.*')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->where('v2.sector_id', $rq->get('sector'))
            ->get(); //BaseGastosRepositorio::cargarue($rq->get('gobierno'), $rq->get('sector'));
        return response()->json(compact('ues'));
    }

    public function cargarsubgenerica(Request $rq)
    {
        if ($rq->get('generica') > 0) {
            $sg = SubGenericaGasto::where('generica_id', $rq->get('generica'))->get();
        } else {
            $sg = SubGenericaGasto::all();
        }

        foreach ($sg as $key => $vv) {
            $gg = GenericaGasto::find($vv->generica_id);
            $vv->codigo = '2.' . $gg->codigo . '.' . $vv->codigo;
        }
        return response()->json(compact('sg'));
    }

    public function nivelgobiernosgrafica01(Request $rq)
    {
        $base = BaseGastosRepositorio::pim_anio_categoriagasto($rq->get('gobierno'), $rq->get('sector'), $rq->get('ue'));
        $puntos['subtitulo'] = 'Pim por Año según Categoria Gasto';
        $puntos['categoria'] = [];
        $puntos['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        $vs1 = 0;
        $vs2 = 0;
        $vs3 = 0;
        foreach ($base as $key => $ba) {
            $puntos['categoria'][] = $ba->ano;
            $dx1[] = $ba->pim1;
            $dx2[] = $ba->pim2;
            $dx3[] = $ba->pim3;
            $vs1 += $ba->pim1;
            $vs2 += $ba->pim2;
            $vs3 += $ba->pim3;
        }
        if ($vs1 > 0)
            $puntos['series'][] = ['name' => 'GASTO CORRIENTE', 'color' => '#ef5350',  'data' => $dx1];
        if ($vs2 > 0)
            $puntos['series'][] = ['name' => 'GASTO DE CAPITAL', 'color' => '#317eeb',  'data' => $dx2];
        if ($vs3 > 0)
            $puntos['series'][] = ['name' => 'SERVICIO DE LA DEUDA', 'color' => '#7e57c2',  'data' => $dx3];
        //return $data;
        return response()->json(compact('puntos'));
    }

    public function nivelgobiernosgrafica02(Request $rq)
    {
        $data = BaseGastosRepositorio::pim_anio_categoriapresupuestal($rq->get('gobierno'), $rq->get('sector'), $rq->get('ue'));
        $puntos['subtitulo'] = 'Pim por Año según Categoria Presupuestal';
        $puntos['categoria'] = [];
        $puntos['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($data as $key => $ba) {
            $puntos['categoria'][] = $ba->ano;
            $dx1[] = $ba->pim1;
            $dx2[] = $ba->pim2;
            $dx3[] = $ba->pim3;
        }
        $puntos['series'][] = ['name' => 'ACCIONES CENTRALES', 'color' => '#7e57c2',  'data' => $dx1];
        $puntos['series'][] = ['name' => 'APNOP', 'color' => '#317eeb',  'data' => $dx2];
        $puntos['series'][] = ['name' => 'PROGRAMA PRESUPUESTAL', 'color' => '#ef5350',  'data' => $dx3];
        return response()->json(compact('puntos'));
    }

    public function nivelgobiernostabla01(Request $rq)
    {
        $body = BaseGastosRepositorio::pim_anio_fuentefimanciamiento($rq->get('gobierno'), $rq->get('sector'), $rq->get('ue'));
        $foot = ['pim_2014' => 0, 'pim_2015' => 0, 'pim_2016' => 0, 'pim_2017' => 0, 'pim_2018' => 0, 'pim_2019' => 0, 'pim_2020' => 0, 'pim_2021' => 0, 'pim_2022' => 0];
        foreach ($body as $key => $value) {
            $foot['pim_2014'] += $value->pim_2014;
            $foot['pim_2015'] += $value->pim_2015;
            $foot['pim_2016'] += $value->pim_2016;
            $foot['pim_2017'] += $value->pim_2017;
            $foot['pim_2018'] += $value->pim_2018;
            $foot['pim_2019'] += $value->pim_2019;
            $foot['pim_2020'] += $value->pim_2020;
            $foot['pim_2021'] += $value->pim_2021;
            $foot['pim_2022'] += $value->pim_2022;
        }
        return view("Presupuesto.BaseGastos.NivelGobiernosTabla1", compact('body', 'foot'));
    }

    public function nivelgobiernostabla02(Request $rq)
    {
        $body = BaseGastosRepositorio::pim_anio_generica($rq->get('gobierno'), $rq->get('sector'), $rq->get('ue'));
        $foot = ['pim_2014' => 0, 'pim_2015' => 0, 'pim_2016' => 0, 'pim_2017' => 0, 'pim_2018' => 0, 'pim_2019' => 0, 'pim_2020' => 0, 'pim_2021' => 0, 'pim_2022' => 0];
        foreach ($body as $key => $value) {
            $foot['pim_2014'] += $value->pim_2014;
            $foot['pim_2015'] += $value->pim_2015;
            $foot['pim_2016'] += $value->pim_2016;
            $foot['pim_2017'] += $value->pim_2017;
            $foot['pim_2018'] += $value->pim_2018;
            $foot['pim_2019'] += $value->pim_2019;
            $foot['pim_2020'] += $value->pim_2020;
            $foot['pim_2021'] += $value->pim_2021;
            $foot['pim_2022'] += $value->pim_2022;
        }
        return view("Presupuesto.BaseGastos.NivelGobiernosTabla2", compact('body', 'foot'));
    }


    /* fin nivel gobiernos */

    /* niveles de gobiernos */
    public function nivelesgobiernos()
    {
        $impG = Importacion::where('fuenteimportacion_id', ImporGastosController::$FUENTE)->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $bgs = BaseGastos::where('importacion_id', $impG->id)->first();
        $impI = Importacion::where('fuenteimportacion_id', ImporIngresosController::$FUENTE)->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $opt1 = BaseGastosRepositorio::total_pim($bgs->id);
        $card1['pim'] = $opt1->pim;
        $card1['eje'] = $opt1->eje;

        $opt1 = BaseGastosRepositorio::pim_tipogobierno($bgs->id);
        $card2['pim'] = $opt1[1]->pim;
        $card2['eje'] = $opt1[1]->eje;
        $card3['pim'] = $opt1[2]->pim;
        $card3['eje'] = $opt1[2]->eje;
        $card4['pim'] = $opt1[0]->pim;
        $card4['eje'] = $opt1[0]->eje;

        return view('Presupuesto.BaseGastos.NivelesGobiernos', compact('card1', 'card2', 'card3', 'card4', 'impG', 'impI', 'bgs'));
    }

    public function nivelesgobiernoscards(Request $rq)
    {
        switch ($rq->div) {
            case 'anal1':
                $info = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                return response()->json(compact('info'));
            case 'anal2':
                $info = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                $data['categoria'] = ['GOBIERNO NACIONAL', 'GOBIERNOS REGIONALES', 'GOBIERNOS LOCALES'];
                $data['series'] = [];
                $dx1 = [];
                $dx2 = [];
                $dx3 = [];
                foreach ($info as $key => $value) {
                    //$dx1[] = $value->y1; //pia
                    $dx2[] = $value->y2; //pim
                    $dx3[] = round($value->y3, 2); //devengado
                }
                //$data['series'][] = ['name' => 'PIA', 'color' => '#7C7D7D', 'data' => $dx1];
                $data['series'][] = ['name' => 'PIM', 'color' => '#317eeb', 'data' => $dx2];
                $data['series'][] = ['name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('data'));
            case 'anal3':
                $info = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                return response()->json(compact('info'));
            case 'anal4':
                $info = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                $data['categoria'] = ['GOBIERNO NACIONAL', 'GOBIERNOS REGIONALES', 'GOBIERNOS LOCALES'];
                $data['series'] = [];
                $dx1 = [];
                $dx2 = [];
                $dx3 = [];
                foreach ($info as $key => $value) {
                    $dx1[] = $value->y1; //pia
                    $dx2[] = $value->y2; //pim
                    $dx3[] = round($value->y3, 2); //devengado
                }
                //$data['series'][] = ['name' => 'PIA', 'color' => '#7C7D7D', 'data' => $dx1];
                $data['series'][] = ['name' => 'PIM', 'color' => '#317eeb', 'data' => $dx2];
                $data['series'][] = ['name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('data'));
            case 'anal5':
                $base = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                //return $base;
                $data['categoria'] = [];
                $data['series'] = [];
                $dx1 = [];
                $dx2 = [];
                $dx3 = [];
                foreach ($base as $key => $ba) {
                    $data['categoria'][] = $ba->ano;
                    $dx1[] = $ba->pim1;
                    $dx2[] = $ba->pim2;
                    $dx3[] = $ba->pim3;
                }
                $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
                $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
                $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('data'));

            case 'anal6':
                $base = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                $data['categoria'] = [];
                $data['series'] = [];
                $dx1 = [];
                $dx2 = [];
                $dx3 = [];
                foreach ($base as $key => $ba) {
                    $data['categoria'][] = $ba->ano;
                    $dx1[] = $ba->pim1;
                    $dx2[] = $ba->pim2;
                    $dx3[] = $ba->pim3;
                }
                $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
                $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
                $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('data'));
            case 'anal7':
                $base = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                $data['categoria'] = [];
                $data['series'] = [];
                $dx1 = [];
                $dx2 = [];
                $dx3 = [];
                foreach ($base as $key => $ba) {
                    $data['categoria'][] = $ba->ano;
                    $dx1[] = $ba->pim1;
                    $dx2[] = $ba->pim2;
                    $dx3[] = $ba->pim3;
                }
                $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
                $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
                $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('data'));

            case 'table1':
                $body = BaseGastosRepositorio::nivelesgobiernoscards($rq->div, $rq->basegastos_id);
                $foot = ['gnp' => 0, 'gnd' => 0, 'gnne' => 0, 'glp' => 0, 'gld' => 0, 'glne' => 0, 'grp' => 0, 'grd' => 0, 'grne' => 0, 'ttp' => 0, 'ttd' => 0, 'ttne' => 0];
                foreach ($body as $key => $value) {
                    $foot['gnp'] += $value->gnp;
                    $foot['gnd'] += $value->gnd;
                    $foot['gnne'] += $value->gnne;
                    $foot['glp'] += $value->glp;
                    $foot['gld'] += $value->gld;
                    $foot['glne'] += $value->glne;
                    $foot['grp'] += $value->grp;
                    $foot['grd'] += $value->grd;
                    $foot['grne'] += $value->grne;
                    $foot['ttp'] += $value->ttp;
                    $foot['ttd'] += $value->ttd;
                    $foot['ttne'] += $value->ttne;
                }
                $table = view("presupuesto.BaseGastos.NivelesGobiernosTabla1", compact('body', 'foot'))->render();
                return response()->json(compact('table'));
            default:
                return [];
        }
    }

    public function nivelesgobiernosExportExcel($div)
    {
        switch ($div) {
            case 'table1':
                $body = BaseGastosRepositorio::nivelesgobiernoscards($div, 0);
                $foot = ['gnp' => 0, 'gnd' => 0, 'gnne' => 0, 'glp' => 0, 'gld' => 0, 'glne' => 0, 'grp' => 0, 'grd' => 0, 'grne' => 0, 'ttp' => 0, 'ttd' => 0, 'ttne' => 0];
                foreach ($body as $key => $value) {
                    $foot['gnp'] += $value->gnp;
                    $foot['gnd'] += $value->gnd;
                    $foot['gnne'] += $value->gnne;
                    $foot['glp'] += $value->glp;
                    $foot['gld'] += $value->gld;
                    $foot['glne'] += $value->glne;
                    $foot['grp'] += $value->grp;
                    $foot['grd'] += $value->grd;
                    $foot['grne'] += $value->grne;
                    $foot['ttp'] += $value->ttp;
                    $foot['ttd'] += $value->ttd;
                    $foot['ttne'] += $value->ttne;
                }
                return compact('body', 'foot');
            case 'table2':
                // $base = ImporCensoDocenteRepositorio::_1AReportes($div, $anio, $provincia, $distrito, $gestion, 0);
                // if ($base->count() > 0) {
                //     $foot = clone $base[0];
                //     $foot->td = 0;
                //     $foot->tt = 0;
                //     $foot->tth = 0;
                //     $foot->ttm = 0;
                //     $foot->ttn = 0;
                //     $foot->ttc = 0;
                //     $foot->pub = 0;
                //     $foot->pri = 0;
                //     $foot->urb = 0;
                //     $foot->rur = 0;

                //     foreach ($base as $key => $value) {
                //         $value->avance = $value->td > 0 ? (100 * $value->tt / $value->td) : 0;
                //         $foot->td += $value->td;
                //         $foot->tt += $value->tt;
                //         $foot->tth += $value->tth;
                //         $foot->ttm += $value->ttm;
                //         $foot->ttn += $value->ttn;
                //         $foot->ttc += $value->ttc;
                //         $foot->pub += $value->pub;
                //         $foot->pri += $value->pri;
                //         $foot->urb += $value->urb;
                //         $foot->rur += $value->rur;
                //     }
                //     $foot->avance = $foot->td > 0 ? (100 * $foot->tt / $foot->td) : 0;
                //     return compact('base', 'foot');
                // } else {
                //     $base = [];
                //     $foot = null;
                //     return compact('base', 'foot');
                // }

            default:
                return [];
        }
    }

    /* fin niveles de gobiernos */


    public function download($div, $basegastos)
    {
        $name = 'tabla ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new BaseGastosExport($div, $basegastos), $name);
    }
}
