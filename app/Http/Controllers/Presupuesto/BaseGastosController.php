<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\BaseGastosExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\CuboGasto;
use App\Models\Presupuesto\FuenteFinanciamiento;
use App\Models\Presupuesto\GenericaGasto;
use App\Models\Presupuesto\Rubro;
use App\Models\Presupuesto\Sector;
use App\Models\Presupuesto\SubGenericaGasto;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebDetalleRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $impG = Importacion::where('fuenteimportacion_id', ImporGastosController::$FUENTE)
            ->where('estado', 'PR')
            ->orderBy('fechaActualizacion', 'desc')
            ->get(); // Get all PR imports

        $bgs = null;
        $impG_selected = null;

        foreach ($impG as $imp) {
            $bgs = BaseGastos::where('importacion_id', $imp->id)->first();
            if ($bgs) {
                $impG_selected = $imp;
                break; // Found the latest import with valid BaseGastos
            }
        }

        // If no valid BaseGastos found, fallback to just the first import (will error out but clearer than arbitrary crash)
        if (!$impG_selected && $impG->isNotEmpty()) {
            $impG_selected = $impG->first();
        }

        $impG = $impG_selected; // Restore variable name used in view

        $impI = Importacion::where('fuenteimportacion_id', ImporIngresosController::$FUENTE)->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        if (!$bgs) {
            return "No se encontró información base procesada para mostrar. Por favor verifique que la importación de gastos se haya procesado correctamente (pres_base_gastos).";
        }

        $opt1 = BaseGastosRepositorio::total_pim($bgs->id);
        $card1['pim'] = $opt1->pim;
        $card1['eje'] = $opt1->eje;

        $opt1 = BaseGastosRepositorio::pim_tipogobierno($bgs->id);
        $card2 = ['pim' => 0, 'eje' => 0]; // Gobierno Nacional
        $card3 = ['pim' => 0, 'eje' => 0]; // Gobierno Regional
        $card4 = ['pim' => 0, 'eje' => 0]; // Gobiernos Locales

        foreach ($opt1 as $row) {
            $nombre = strtoupper(trim((string)($row->gobiernos ?? '')));
            if ($nombre === '') continue;

            if (strpos($nombre, 'NACIONAL') !== false) {
                $card2['pim'] = $row->pim ?? 0;
                $card2['eje'] = $row->eje ?? 0;
                continue;
            }
            if (strpos($nombre, 'REGIONAL') !== false) {
                $card3['pim'] = $row->pim ?? 0;
                $card3['eje'] = $row->eje ?? 0;
                continue;
            }
            if (strpos($nombre, 'LOCAL') !== false) {
                $card4['pim'] = $row->pim ?? 0;
                $card4['eje'] = $row->eje ?? 0;
                continue;
            }
        }

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

    public function seguimiento()
    {
        $anios = CuboGasto::distinct()->select('anio')->orderBy('anio', 'asc')->get();
        $aniomax = $anios->max('anio');
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporGastosController::$FUENTE);
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $mesNombre = $meses[max(1, min(12, (int)$imp->mes)) - 1];
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $mesNombre . ' del ' . $imp->anio;
        $mensaje = "";
        return view('Presupuesto.BaseGastos.Seguimiento', compact('mensaje', 'anios', 'aniomax', 'actualizado', 'mesNombre'));
    }

    public function seguimientoActualizado($anio)
    {
        $imp = Importacion::select(
            'id',
            'fechaActualizacion',
            DB::raw('year(fechaActualizacion) as anio'),
            DB::raw('month(fechaActualizacion) as mes'),
            DB::raw('day(fechaActualizacion) as dia')
        )
            ->where('fuenteimportacion_id', ImporGastosController::$FUENTE)
            ->where('estado', 'PR')
            ->whereYear('fechaActualizacion', (int)$anio)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();

        if (!$imp) {
            return response()->json(['actualizado' => null]);
        }

        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $mesNombre = $meses[max(1, min(12, (int)$imp->mes)) - 1];
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $mesNombre . ' del ' . $imp->anio;

        return response()->json(compact('actualizado'));
    }

    public function seguimientoSelectUe($anio)
    {
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = CuboGasto::join('pres_unidadejecutora as ue', 'ue.id', '=', 'pres_cubo_gasto.unidadejecutora_id')
            ->where('pres_cubo_gasto.anio', $anio);
        if (!empty($uePermitidas)) {
            $query = $query->whereIn('ue.id', $uePermitidas);
        }
        $query = $query->select('ue.id', DB::raw("CONCAT(ue.codigo_ue,' ',ue.nombre_ejecutora) as nombre"))
            ->distinct()
            ->orderBy('ue.codigo_ue', 'asc');
        return $query->pluck('nombre', 'id');
    }

    public function seguimientoSelectFuente($anio, $ue)
    {
        $query = CuboGasto::join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'pres_cubo_gasto.fuentefinanciamiento_id')
            ->where('pres_cubo_gasto.anio', $anio);
        if ((int)$ue > 0) {
            $query = $query->where('pres_cubo_gasto.unidadejecutora_id', $ue);
        }
        $query = $query->select('ff.id', DB::raw("CONCAT(ff.codigo,' ',ff.nombre) as nombre"))
            ->distinct()
            ->orderBy('ff.codigo', 'asc');
        return $query->pluck('nombre', 'id');
    }

    public function seguimientoSelectRubro($anio, $ue, $ff)
    {
        $query = CuboGasto::join('pres_rubro as r', 'r.id', '=', 'pres_cubo_gasto.rubro_id')
            ->where('pres_cubo_gasto.anio', $anio);
        if ((int)$ue > 0) {
            $query = $query->where('pres_cubo_gasto.unidadejecutora_id', $ue);
        }
        if ((int)$ff > 0) {
            $query = $query->where('pres_cubo_gasto.fuentefinanciamiento_id', $ff);
        }
        $query = $query->select('r.id', DB::raw("CONCAT(r.codigo,' ',r.nombre) as nombre"))
            ->distinct()
            ->orderBy('r.codigo', 'asc');
        return $query->pluck('nombre', 'id');
    }

    public function seguimientoReportes(Request $rq)
    {
        $anio = (int)$rq->get('anio');
        $ue = (int)$rq->get('ue', 0);
        $ff = (int)$rq->get('ff', 0);
        $rubro = (int)$rq->get('rubro', 0);

        switch ($rq->div) {
            case 'anal1':
                $query = DB::table('pres_cubo_gasto as ci')
                    ->where('ci.anio', $anio);
                if ($ue > 0) {
                    $query = $query->where('ci.unidadejecutora_id', $ue);
                }
                if ($ff > 0) {
                    $query = $query->where('ci.fuentefinanciamiento_id', $ff);
                }
                if ($rubro > 0) {
                    $query = $query->where('ci.rubro_id', $rubro);
                }
                $row_gasto = $query
                    ->selectRaw('SUM(ci.pim) as pim')
                    ->first();

                $query = DB::table('pres_cubo_ingreso as ci')
                    ->where('ci.anio', $anio);
                if ($ue > 0) {
                    $query = $query->where('ci.unidadejecutora_id', $ue);
                }
                if ($ff > 0) {
                    $query = $query->where('ci.fuentefinanciamiento_id', $ff);
                }
                if ($rubro > 0) {
                    $query = $query->where('ci.rubro_id', $rubro);
                }
                $row_ingreso = $query
                    ->selectRaw('SUM(ci.recaudado) as devengado')
                    ->first();

                $data = new \stdClass();
                $data->pim = $row_gasto && $row_gasto->pim ? round($row_gasto->pim, 0) : 0;
                $data->devengado = $row_ingreso && $row_ingreso->devengado ? round($row_ingreso->devengado, 0) : 0;
                $data->avance = $data->pim > 0 ? round(100 * $data->devengado / $data->pim, 1) : 0;

                return response()->json($data);

            case 'anal2':
                $gastoSub = DB::table('pres_cubo_gasto as cg')
                    ->where('cg.anio', $anio)
                    ->when($ue > 0, fn($q) => $q->where('cg.unidadejecutora_id', $ue))
                    ->when($ff > 0, fn($q) => $q->where('cg.fuentefinanciamiento_id', $ff))
                    ->when($rubro > 0, fn($q) => $q->where('cg.rubro_id', $rubro))
                    ->groupBy('cg.rubro_id')
                    ->selectRaw('cg.rubro_id, SUM(cg.pim) as pim');

                $ingresoSub = DB::table('pres_cubo_ingreso as ci')
                    ->where('ci.anio', $anio)
                    ->when($ue > 0, fn($q) => $q->where('ci.unidadejecutora_id', $ue))
                    ->when($ff > 0, fn($q) => $q->where('ci.fuentefinanciamiento_id', $ff))
                    ->when($rubro > 0, fn($q) => $q->where('ci.rubro_id', $rubro))
                    ->groupBy('ci.rubro_id')
                    ->selectRaw('ci.rubro_id, SUM(ci.recaudado) as devengado');

                $data = DB::table('pres_rubro as r')
                    ->leftJoinSub($gastoSub, 'g', function ($join) {
                        $join->on('g.rubro_id', '=', 'r.id');
                    })
                    ->leftJoinSub($ingresoSub, 'i', function ($join) {
                        $join->on('i.rubro_id', '=', 'r.id');
                    })
                    ->when($rubro > 0, fn($q) => $q->where('r.id', $rubro))
                    ->where(function ($q) {
                        $q->whereNotNull('g.pim')->orWhereNotNull('i.devengado');
                    })
                    ->selectRaw('
                        r.id,
                        r.codigo,
                        r.nombre,
                        COALESCE(g.pim, 0) as pim,
                        COALESCE(i.devengado, 0) as devengado
                    ')
                    ->orderBy('r.codigo')
                    ->get();

                $info = [
                    'categorias' => [],
                    'series' => [
                        ['data' => [], 'type' => 'column', 'yAxis' => 0, 'name' => 'PIM'],
                        ['data' => [], 'type' => 'column', 'yAxis' => 0, 'name' => 'RECAUDADO'],
                        ['data' => [], 'type' => 'line', 'yAxis' => 1, 'name' => '% Ejecución'],
                    ],
                ];

                foreach ($data as $item) {
                    $info['categorias'][] = $item->codigo . ' ' . $item->nombre;
                    $info['series'][0]['data'][] = (int)round($item->pim);
                    $info['series'][1]['data'][] = (int)round($item->devengado);
                    $info['series'][2]['data'][] = $item->pim > 0 ? round(100 * $item->devengado / $item->pim, 1) : 0;
                }

                return response()->json(compact('info', 'data'));

            case 'tabla1':
                $gastoSub = DB::table('pres_cubo_gasto as cg')
                    ->where('cg.anio', $anio)
                    ->when($ue > 0, fn($q) => $q->where('cg.unidadejecutora_id', $ue))
                    ->when($ff > 0, fn($q) => $q->where('cg.fuentefinanciamiento_id', $ff))
                    ->when($rubro > 0, fn($q) => $q->where('cg.rubro_id', $rubro))
                    ->groupBy('cg.unidadejecutora_id')
                    ->selectRaw('cg.unidadejecutora_id, SUM(cg.pia) as pia, SUM(cg.pim) as pim, SUM(cg.devengado) as devengado, SUM(cg.girado) as girado');

                $ingresoSub = DB::table('pres_cubo_ingreso as ci')
                    ->where('ci.anio', $anio)
                    ->when($ue > 0, fn($q) => $q->where('ci.unidadejecutora_id', $ue))
                    ->when($ff > 0, fn($q) => $q->where('ci.fuentefinanciamiento_id', $ff))
                    ->when($rubro > 0, fn($q) => $q->where('ci.rubro_id', $rubro))
                    ->groupBy('ci.unidadejecutora_id')
                    ->selectRaw('ci.unidadejecutora_id, SUM(ci.recaudado) as recaudado');

                $base = DB::table('pres_unidadejecutora as ue')
                    ->leftJoinSub($gastoSub, 'g', function ($join) {
                        $join->on('g.unidadejecutora_id', '=', 'ue.id');
                    })
                    ->leftJoinSub($ingresoSub, 'i', function ($join) {
                        $join->on('i.unidadejecutora_id', '=', 'ue.id');
                    })
                    ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
                    ->where(function ($q) {
                        $q->whereNotNull('g.pim')->orWhereNotNull('i.recaudado');
                    })
                    ->selectRaw("
                        ue.id,
                        ue.codigo_ue,
                        ue.nombre_ejecutora,
                        COALESCE(g.pia,0) as pia,
                        COALESCE(g.pim,0) as pim,
                        COALESCE(i.recaudado,0) as recaudado,
                        COALESCE(g.devengado,0) as devengado,
                        COALESCE(g.girado,0) as girado
                    ")
                    ->orderBy('ue.codigo_ue')
                    ->get();

                foreach ($base as $item) {
                    $item->avance = $item->pim > 0 ? round(100 * $item->recaudado / $item->pim, 1) : 0;
                    $item->saldo_recaudado = $item->pim - $item->recaudado;
                    $item->saldo_financiero = $item->recaudado - $item->devengado;
                }

                $foot = [
                    'pia' => $base->sum('pia'),
                    'pim' => $base->sum('pim'),
                    'recaudado' => $base->sum('recaudado'),
                    'devengado' => $base->sum('devengado'),
                    'girado' => $base->sum('girado'),
                ];
                $foot['avance'] = $foot['pim'] > 0 ? round(100 * $foot['recaudado'] / $foot['pim'], 1) : 0;
                $foot['saldo_recaudado'] = $foot['pim'] - $foot['recaudado'];
                $foot['saldo_financiero'] = $foot['recaudado'] - $foot['devengado'];

                $excel = view('Presupuesto.BaseGastos.SeguimientoTabla1', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            case 'tabla2':
                $q2 = DB::table('pres_cubo_ingreso as ci')
                    ->join('pres_especificadetalle_ingreso as e', function ($join) {
                        $join->on('e.id', '=', 'ci.especificadetalle_id');
                    })
                    ->where('ci.anio', $anio)
                    ->when($ue > 0, fn($q) => $q->where('ci.unidadejecutora_id', $ue))
                    ->when($ff > 0, fn($q) => $q->where('ci.fuentefinanciamiento_id', $ff))
                    ->when($rubro > 0, fn($q) => $q->where('ci.rubro_id', $rubro))
                    ->groupBy('ci.especificadetalle_id','ci.clasificador', 'e.nombre')
                    ->orderBy('ci.clasificador')
                    ->selectRaw("
                        ci.especificadetalle_id,
                        ci.clasificador,
                        e.nombre as nombre,
                        sum(ci.pia) as pia,
                        sum(ci.pim) as pim,
                        sum(ci.recaudado) as recaudado
                    ");
                $base2 = $q2->get();
                foreach ($base2 as $item) {
                    $item->avance = $item->pim > 0 ? round(100 * $item->recaudado / $item->pim, 1) : 0;
                    $item->saldo = $item->pim - $item->recaudado;

                    $parts = explode('.', (string)$item->clasificador);
                    if (count($parts) >= 6) {
                        $item->cod_gen = $parts[1];
                        $item->cod_subgen = $parts[2];
                        $item->cod_subgen_det = $parts[3];
                        $item->cod_esp = $parts[4];
                        $item->cod_esp_det = $parts[5];
                    } else {
                        $item->cod_gen = null;
                        $item->cod_subgen = null;
                        $item->cod_subgen_det = null;
                        $item->cod_esp = null;
                        $item->cod_esp_det = null;
                    }
                }
                $foot2 = [
                    'pia' => $base2->sum('pia'),
                    'pim' => $base2->sum('pim'),
                    'recaudado' => $base2->sum('recaudado'),
                ];
                $foot2['avance'] = $foot2['pim'] > 0 ? round(100 * $foot2['recaudado'] / $foot2['pim'], 1) : 0;
                $foot2['saldo'] = $foot2['pim'] - $foot2['recaudado'];

                $excel = view('Presupuesto.BaseGastos.SeguimientoTabla2', ['base' => $base2, 'foot' => $foot2])->render();
                return response()->json(compact('excel', 'base2', 'foot2'));

            default:
                return [];
        }
    }

    public function seguimientoreporte(Request $rq)
    {
        return $this->seguimientoReportes($rq);
    }

    public function seguimientoreportedownloadexcel($div, $anio, $ue, $cg, $ff, $cp)
    {
        if ($div === 'tabla0101') {
            $anio = (int)$anio;
            $ue = (int)$ue;
            $ff = (int)$ff;
            $rubro = (int)$cp;

            $anios = DB::table('pres_cubo_ingreso')
                ->where('unidadejecutora_id', $ue)
                ->where('anio', '<=', $anio)
                ->when($ff > 0, fn($q) => $q->where('fuentefinanciamiento_id', $ff))
                ->when($rubro > 0, fn($q) => $q->where('rubro_id', $rubro))
                ->distinct()
                ->orderBy('anio', 'desc')
                // ->limit(8)
                ->pluck('anio');

            $rows = [];
            // $rows[] = ['Año', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Total'];

            foreach ($anios as $ax) {
                $row = DB::table('pres_cubo_ingreso')
                    ->where('unidadejecutora_id', $ue)
                    ->where('anio', $ax)
                    ->when($ff > 0, fn($q) => $q->where('fuentefinanciamiento_id', $ff))
                    ->when($rubro > 0, fn($q) => $q->where('rubro_id', $rubro))
                    ->selectRaw("
                        SUM(CASE WHEN mes=1 THEN recaudado ELSE 0 END) as ene,
                        SUM(CASE WHEN mes=2 THEN recaudado ELSE 0 END) as feb,
                        SUM(CASE WHEN mes=3 THEN recaudado ELSE 0 END) as mar,
                        SUM(CASE WHEN mes=4 THEN recaudado ELSE 0 END) as abr,
                        SUM(CASE WHEN mes=5 THEN recaudado ELSE 0 END) as may,
                        SUM(CASE WHEN mes=6 THEN recaudado ELSE 0 END) as jun,
                        SUM(CASE WHEN mes=7 THEN recaudado ELSE 0 END) as jul,
                        SUM(CASE WHEN mes=8 THEN recaudado ELSE 0 END) as ago,
                        SUM(CASE WHEN mes=9 THEN recaudado ELSE 0 END) as sep,
                        SUM(CASE WHEN mes=10 THEN recaudado ELSE 0 END) as oct,
                        SUM(CASE WHEN mes=11 THEN recaudado ELSE 0 END) as nov,
                        SUM(CASE WHEN mes=12 THEN recaudado ELSE 0 END) as dic
                    ")
                    ->first();

                $rowArr = (array)$row;
                $total = 0;
                foreach (['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'] as $m) {
                    $total += isset($rowArr[$m]) ? (float)$rowArr[$m] : 0;
                }

                $rows[] = [
                    (int)$ax,
                    (float)($rowArr['ene'] ?? 0),
                    (float)($rowArr['feb'] ?? 0),
                    (float)($rowArr['mar'] ?? 0),
                    (float)($rowArr['abr'] ?? 0),
                    (float)($rowArr['may'] ?? 0),
                    (float)($rowArr['jun'] ?? 0),
                    (float)($rowArr['jul'] ?? 0),
                    (float)($rowArr['ago'] ?? 0),
                    (float)($rowArr['sep'] ?? 0),
                    (float)($rowArr['oct'] ?? 0),
                    (float)($rowArr['nov'] ?? 0),
                    (float)($rowArr['dic'] ?? 0),
                    (float)$total,
                ];
            }

            $name = 'Seguimiento_Mensual_UE_' . $ue . '_' . $anio . '_' . date('Y-m-d') . '.xlsx';
            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithStyles {
                private $rows;
                private $headers;

                public function __construct($rows)
                {
                    $this->rows = $rows;
                    $this->headers = ['Año', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Total'];
                }
                public function headings(): array
                {
                    return [
                        ['RECAUDADO MENSUALIZADO (S/.)'],
                        $this->headers
                    ];
                }
                public function array(): array
                {
                    return $this->rows;
                }
                public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
                {
                    $lastRow = count($this->rows) + 2;

                    $titleStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ];

                    $headerStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    ];

                    $sheet->mergeCells('A1:N1');
                    $sheet->getStyle('A1')->applyFromArray($titleStyle);
                    $sheet->getStyle('A2:N2')->applyFromArray($headerStyle);
                    
                    $sheet->getStyle('A1:N' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                    $sheet->getStyle('B3:N' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
                }
            };
            return Excel::download($export, $name);
        } elseif ($div === 'tabla0201') {
            $anio = (int)$anio;
            $ue = (int)$ue;
            $ff = (int)$ff;
            $rubro = (int)$cp;
            $especificadetalle_id = (int)$cg;

            $anios = DB::table('pres_cubo_ingreso')
                ->where('especificadetalle_id', $especificadetalle_id)
                ->where('anio', '<=', $anio)
                ->when($ue > 0, fn($q) => $q->where('unidadejecutora_id', $ue))
                ->when($ff > 0, fn($q) => $q->where('fuentefinanciamiento_id', $ff))
                ->when($rubro > 0, fn($q) => $q->where('rubro_id', $rubro))
                ->groupBy('anio')
                ->orderBy('anio', 'desc')
                ->limit(8)
                ->selectRaw("
                    anio,
                    SUM(recaudado) as total,
                    SUM(CASE WHEN mes=1 THEN recaudado ELSE 0 END) as ene,
                    SUM(CASE WHEN mes=2 THEN recaudado ELSE 0 END) as feb,
                    SUM(CASE WHEN mes=3 THEN recaudado ELSE 0 END) as mar,
                    SUM(CASE WHEN mes=4 THEN recaudado ELSE 0 END) as abr,
                    SUM(CASE WHEN mes=5 THEN recaudado ELSE 0 END) as may,
                    SUM(CASE WHEN mes=6 THEN recaudado ELSE 0 END) as jun,
                    SUM(CASE WHEN mes=7 THEN recaudado ELSE 0 END) as jul,
                    SUM(CASE WHEN mes=8 THEN recaudado ELSE 0 END) as ago,
                    SUM(CASE WHEN mes=9 THEN recaudado ELSE 0 END) as sep,
                    SUM(CASE WHEN mes=10 THEN recaudado ELSE 0 END) as oct,
                    SUM(CASE WHEN mes=11 THEN recaudado ELSE 0 END) as nov,
                    SUM(CASE WHEN mes=12 THEN recaudado ELSE 0 END) as dic
                ")
                ->get();

            $rows = [];
            
            // Totals
            $tEne = 0; $tFeb = 0; $tMar = 0; $tAbr = 0; $tMay = 0; $tJun = 0;
            $tJul = 0; $tAgo = 0; $tSep = 0; $tOct = 0; $tNov = 0; $tDic = 0; $tTotal = 0;

            foreach ($anios as $item) {
                $rows[] = [
                    (int)$item->anio,
                    (float)$item->ene,
                    (float)$item->feb,
                    (float)$item->mar,
                    (float)$item->abr,
                    (float)$item->may,
                    (float)$item->jun,
                    (float)$item->jul,
                    (float)$item->ago,
                    (float)$item->sep,
                    (float)$item->oct,
                    (float)$item->nov,
                    (float)$item->dic,
                    (float)$item->total,
                ];

                $tEne += (float)$item->ene;
                $tFeb += (float)$item->feb;
                $tMar += (float)$item->mar;
                $tAbr += (float)$item->abr;
                $tMay += (float)$item->may;
                $tJun += (float)$item->jun;
                $tJul += (float)$item->jul;
                $tAgo += (float)$item->ago;
                $tSep += (float)$item->sep;
                $tOct += (float)$item->oct;
                $tNov += (float)$item->nov;
                $tDic += (float)$item->dic;
                $tTotal += (float)$item->total;
            }

            // Add Total Row
            $rows[] = [
                'TOTAL',
                $tEne, $tFeb, $tMar, $tAbr, $tMay, $tJun,
                $tJul, $tAgo, $tSep, $tOct, $tNov, $tDic, $tTotal
            ];

            $name = 'Seguimiento_Mensual_Clasificador_' . $especificadetalle_id . '_' . $anio . '_' . date('Y-m-d') . '.xlsx';
            
            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithStyles {
                private $rows;
                private $headers;

                public function __construct($rows)
                {
                    $this->rows = $rows;
                    $this->headers = ['Año', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic', 'Total'];
                }
                public function headings(): array
                {
                    return [
                        ['RECAUDADO MENSUALIZADO'],
                        $this->headers
                    ];
                }
                public function array(): array
                {
                    return $this->rows;
                }
                public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
                {
                    $lastRow = count($this->rows) + 2; // +2 because headings has 2 rows (Title + Columns)

                    // Title Style (Row 1)
                    $titleStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ];

                    // Header Style (Row 2)
                    $headerStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    ];

                    // Total Style (Last Row)
                    $totalStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    ];

                    $sheet->mergeCells('A1:N1');
                    $sheet->getStyle('A1')->applyFromArray($titleStyle);
                    $sheet->getStyle('A2:N2')->applyFromArray($headerStyle);
                    $sheet->getStyle('A' . $lastRow . ':N' . $lastRow)->applyFromArray($totalStyle);
                    
                    $sheet->getStyle('A1:N' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    
                    // Number format for values (columns B to N) starting from row 3
                    $sheet->getStyle('B3:N' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
                }
            };
            return Excel::download($export, $name);
        }

        if ($div === 'tabla2') {
            $anio = (int)$anio;
            $ue = (int)$ue;
            $ff = (int)$ff;
            $rubro = (int)$cp;

            $q2 = DB::table('pres_cubo_ingreso as ci')
                ->join('pres_especificadetalle_ingreso as e', function ($join) {
                    $join->on('e.id', '=', 'ci.especificadetalle_id');
                })
                ->where('ci.anio', $anio)
                ->when($ue > 0, fn($q) => $q->where('ci.unidadejecutora_id', $ue))
                ->when($ff > 0, fn($q) => $q->where('ci.fuentefinanciamiento_id', $ff))
                ->when($rubro > 0, fn($q) => $q->where('ci.rubro_id', $rubro))
                ->groupBy('ci.especificadetalle_id', 'ci.clasificador', 'e.nombre')
                ->orderBy('ci.clasificador')
                ->selectRaw("
                    ci.especificadetalle_id,
                    ci.clasificador,
                    e.nombre as nombre,
                    sum(ci.pia) as pia,
                    sum(ci.pim) as pim,
                    sum(ci.recaudado) as recaudado
                ");
            $base2 = $q2->get();

            $rows = [];
            $headers = ['#', 'Especifica Detalle', 'PIA', 'PIM', 'Recaudado', '% Ejecución', 'Saldo'];
            // $rows[] = $headers;
            $i = 1;
            foreach ($base2 as $item) {
                $avance = $item->pim > 0 ? round(100 * $item->recaudado / $item->pim, 1) : 0;
                $saldo = $item->pim - $item->recaudado;
                $rows[] = [
                    $i++,
                    $item->clasificador . ' ' . $item->nombre,
                    (float)$item->pia,
                    (float)$item->pim,
                    (float)$item->recaudado,
                    $avance,
                    (float)$saldo,
                ];
            }

            $totPia = $base2->sum('pia');
            $totPim = $base2->sum('pim');
            $totRecaudado = $base2->sum('recaudado');
            $totAvance = $totPim > 0 ? round(100 * $totRecaudado / $totPim, 1) : 0;
            $totSaldo = $totPim - $totRecaudado;

            $rows[] = [
                '',
                'TOTAL',
                $totPia,
                $totPim,
                $totRecaudado,
                $totAvance,
                $totSaldo,
            ];

            $name = 'Seguimiento_Especifica_' . date('Y-m-d') . '.xlsx';
            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithStyles {
                private $rows;
                private $headers;

                public function __construct($rows)
                {
                    $this->rows = $rows;
                    $this->headers = ['#', 'Especifica Detalle', 'PIA', 'PIM', 'Recaudado', '% Ejecución', 'Saldo'];
                }
                public function headings(): array
                {
                    return [
                        ['AVANCE DE LA RECAUDACIÓN DEL INGRESO PRESUPUESTAL POR ESPECIFICA DETALLE'],
                        $this->headers
                    ];
                }
                public function array(): array
                {
                    return $this->rows;
                }
                public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
                {
                    $lastRow = count($this->rows) + 2;

                    // Title Style (Row 1)
                    $titleStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ];

                    // Header Style (Row 2)
                    $headerStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    ];

                    // Total Style (Last Row)
                    $totalStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    ];

                    $sheet->mergeCells('A1:G1');
                    $sheet->getStyle('A1')->applyFromArray($titleStyle);
                    $sheet->getStyle('A2:G2')->applyFromArray($headerStyle);
                    $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray($totalStyle);

                    $sheet->getStyle('A1:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // Number format for values (columns C to E, G) starting from row 3
                    $sheet->getStyle('C3:E' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle('G3:G' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');

                    // Percentage format for column F (% Ejecución)
                    $sheet->getStyle('F3:F' . $lastRow)->getNumberFormat()->setFormatCode('0.0');
                }
            };
            return Excel::download($export, $name);
        }

        if ($div === 'tabla1') {
            $anio = (int)$anio;
            $ue = (int)$ue;
            $ff = (int)$ff;
            $rubro = (int)$cp; // reutilizamos 'cp' como rubro desde el frontend

            $gastoSub = DB::table('pres_cubo_gasto as cg')
                ->where('cg.anio', $anio)
                ->when($ue > 0, fn($q) => $q->where('cg.unidadejecutora_id', $ue))
                ->when($ff > 0, fn($q) => $q->where('cg.fuentefinanciamiento_id', $ff))
                ->when($rubro > 0, fn($q) => $q->where('cg.rubro_id', $rubro))
                ->groupBy('cg.unidadejecutora_id')
                ->selectRaw('cg.unidadejecutora_id, SUM(cg.pia) as pia, SUM(cg.pim) as pim, SUM(cg.devengado) as devengado, SUM(cg.girado) as girado');

            $ingresoSub = DB::table('pres_cubo_ingreso as ci')
                ->where('ci.anio', $anio)
                ->when($ue > 0, fn($q) => $q->where('ci.unidadejecutora_id', $ue))
                ->when($ff > 0, fn($q) => $q->where('ci.fuentefinanciamiento_id', $ff))
                ->when($rubro > 0, fn($q) => $q->where('ci.rubro_id', $rubro))
                ->groupBy('ci.unidadejecutora_id')
                ->selectRaw('ci.unidadejecutora_id, SUM(ci.recaudado) as recaudado');

            $base = DB::table('pres_unidadejecutora as ue')
                ->leftJoinSub($gastoSub, 'g', function ($join) {
                    $join->on('g.unidadejecutora_id', '=', 'ue.id');
                })
                ->leftJoinSub($ingresoSub, 'i', function ($join) {
                    $join->on('i.unidadejecutora_id', '=', 'ue.id');
                })
                ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
                ->where(function ($q) {
                    $q->whereNotNull('g.pim')->orWhereNotNull('i.recaudado');
                })
                ->selectRaw("
                ue.codigo_ue,
                ue.nombre_ejecutora,
                COALESCE(g.pia,0) as pia,
                COALESCE(g.pim,0) as pim,
                COALESCE(i.recaudado,0) as recaudado,
                COALESCE(g.devengado,0) as devengado,
                COALESCE(g.girado,0) as girado
            ")
                ->orderBy('ue.codigo_ue')
                ->get();

            $rows = [];
            $headers = ['#', 'Unidad Ejecutora', 'PIA', 'PIM', 'Recaudado', '% Ejecución', 'Devengado', 'Girado', 'Saldo Recaudado', 'Saldo Financiero'];
            // $rows[] = $headers;
            $i = 1;
            foreach ($base as $item) {
                $avance = $item->pim > 0 ? round(100 * $item->recaudado / $item->pim, 1) : 0;
                $saldo_recaudado = $item->pim - $item->recaudado;
                $saldo_financiero = $item->recaudado - $item->devengado;
                $rows[] = [
                    $i++,
                    $item->codigo_ue . ' ' . $item->nombre_ejecutora,
                    (float)$item->pia,
                    (float)$item->pim,
                    (float)$item->recaudado,
                    $avance,
                    (float)$item->devengado,
                    (float)$item->girado,
                    (float)$saldo_recaudado,
                    (float)$saldo_financiero,
                ];
            }

            $totPia = (float)$base->sum('pia');
            $totPim = (float)$base->sum('pim');
            $totRecaudado = (float)$base->sum('recaudado');
            $totDevengado = (float)$base->sum('devengado');
            $totGirado = (float)$base->sum('girado');
            $totAvance = $totPim > 0 ? round(100 * $totRecaudado / $totPim, 1) : 0;
            $totSaldoRecaudado = $totPim - $totRecaudado;
            $totSaldoFinanciero = $totRecaudado - $totDevengado;

            $rows[] = [
                '',
                'TOTAL',
                $totPia,
                $totPim,
                $totRecaudado,
                $totAvance,
                $totDevengado,
                $totGirado,
                $totSaldoRecaudado,
                $totSaldoFinanciero,
            ];

            $name = 'Seguimiento_UE_' . date('Y-m-d') . '.xlsx';
            $export = new class($rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithStyles {
                private $rows;
                private $headers;

                public function __construct($rows)
                {
                    $this->rows = $rows;
                    $this->headers = ['#', 'Unidad Ejecutora', 'PIA', 'PIM', 'Recaudado', '% Ejecución', 'Devengado', 'Girado', 'Saldo Recaudado', 'Saldo Financiero'];
                }
                public function headings(): array
                {
                    return [
                        ['AVANCE DE LA RECAUDACIÓN DEL INGRESO PRESUPUESTAL Y GASTO PRESUPUESTAL POR UNIDADES EJECUTORAS'],
                        $this->headers
                    ];
                }
                public function array(): array
                {
                    return $this->rows;
                }
                public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
                {
                    $lastRow = count($this->rows) + 2;

                    // Title Style (Row 1)
                    $titleStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ];

                    // Header Style (Row 2)
                    $headerStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    ];

                    // Total Style (Last Row)
                    $totalStyle = [
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                    ];

                    $sheet->mergeCells('A1:J1');
                    $sheet->getStyle('A1')->applyFromArray($titleStyle);
                    $sheet->getStyle('A2:J2')->applyFromArray($headerStyle);
                    $sheet->getStyle('A' . $lastRow . ':J' . $lastRow)->applyFromArray($totalStyle);

                    $sheet->getStyle('A1:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // Number format for values (columns C to J) starting from row 3
                    $sheet->getStyle('C3:E' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
                    $sheet->getStyle('G3:J' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');

                    // Percentage format for column F (% Ejecución)
                    $sheet->getStyle('F3:F' . $lastRow)->getNumberFormat()->setFormatCode('0.0');
                }
            };
            return Excel::download($export, $name);
        }

        $name = 'REPORTE_SEGUIMIENTO.xlsx';
        $export = new class implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function headings(): array
            {
                return ['Mensaje'];
            }
            public function array(): array
            {
                return [['Export no implementado para esta tabla']];
            }
        };
        return Excel::download($export, $name);
    }

    public function seguimientoTabla0101($anio, $ue, $ff, $rubro)
    {
        $anios = DB::table('pres_cubo_ingreso')
            ->where('unidadejecutora_id', $ue)
            ->where('anio', '<=', (int)$anio)
            ->when((int)$ff > 0, fn($q) => $q->where('fuentefinanciamiento_id', (int)$ff))
            ->when((int)$rubro > 0, fn($q) => $q->where('rubro_id', (int)$rubro))
            ->distinct()
            ->orderBy('anio', 'desc')
            ->limit(8)
            ->pluck('anio');

        $body = [];
        foreach ($anios as $ax) {
            $row = DB::table('pres_cubo_ingreso')
                ->where('unidadejecutora_id', $ue)
                ->where('anio', $ax)
                ->when((int)$ff > 0, fn($q) => $q->where('fuentefinanciamiento_id', (int)$ff))
                ->when((int)$rubro > 0, fn($q) => $q->where('rubro_id', (int)$rubro))
                ->selectRaw("
                    SUM(CASE WHEN mes=1 THEN recaudado ELSE 0 END) as ene,
                    SUM(CASE WHEN mes=2 THEN recaudado ELSE 0 END) as feb,
                    SUM(CASE WHEN mes=3 THEN recaudado ELSE 0 END) as mar,
                    SUM(CASE WHEN mes=4 THEN recaudado ELSE 0 END) as abr,
                    SUM(CASE WHEN mes=5 THEN recaudado ELSE 0 END) as may,
                    SUM(CASE WHEN mes=6 THEN recaudado ELSE 0 END) as jun,
                    SUM(CASE WHEN mes=7 THEN recaudado ELSE 0 END) as jul,
                    SUM(CASE WHEN mes=8 THEN recaudado ELSE 0 END) as ago,
                    SUM(CASE WHEN mes=9 THEN recaudado ELSE 0 END) as sep,
                    SUM(CASE WHEN mes=10 THEN recaudado ELSE 0 END) as oct,
                    SUM(CASE WHEN mes=11 THEN recaudado ELSE 0 END) as nov,
                    SUM(CASE WHEN mes=12 THEN recaudado ELSE 0 END) as dic
                ")
                ->first();

            $rowArr = (array)$row;
            $total = 0;
            foreach (['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'] as $m) {
                $total += isset($rowArr[$m]) ? (float)$rowArr[$m] : 0;
            }

            $body[] = (object)array_merge($rowArr, [
                'anio' => $ax,
                'total' => $total,
            ]);
        }

        $excel = view('Presupuesto.BaseGastos.SeguimientoTabla0101', compact('body', 'anio', 'ue', 'ff', 'rubro'))->render();
        return response()->json(compact('excel'));
    }

    public function seguimientoTabla0201($anio, $codes, $ue, $ff, $rubro)
    {
        $especificadetalle_id = (int)$codes;

        $body = DB::table('pres_cubo_ingreso')
            ->where('especificadetalle_id', $especificadetalle_id)
            ->where('anio', '<=', (int)$anio)
            ->when((int)$ue > 0, fn($q) => $q->where('unidadejecutora_id', (int)$ue))
            ->when((int)$ff > 0, fn($q) => $q->where('fuentefinanciamiento_id', (int)$ff))
            ->when((int)$rubro > 0, fn($q) => $q->where('rubro_id', (int)$rubro))
            ->groupBy('anio')
            ->orderBy('anio', 'desc')
            ->limit(8)
            ->selectRaw("
                anio,
                SUM(recaudado) as total,
                SUM(CASE WHEN mes=1 THEN recaudado ELSE 0 END) as ene,
                SUM(CASE WHEN mes=2 THEN recaudado ELSE 0 END) as feb,
                SUM(CASE WHEN mes=3 THEN recaudado ELSE 0 END) as mar,
                SUM(CASE WHEN mes=4 THEN recaudado ELSE 0 END) as abr,
                SUM(CASE WHEN mes=5 THEN recaudado ELSE 0 END) as may,
                SUM(CASE WHEN mes=6 THEN recaudado ELSE 0 END) as jun,
                SUM(CASE WHEN mes=7 THEN recaudado ELSE 0 END) as jul,
                SUM(CASE WHEN mes=8 THEN recaudado ELSE 0 END) as ago,
                SUM(CASE WHEN mes=9 THEN recaudado ELSE 0 END) as sep,
                SUM(CASE WHEN mes=10 THEN recaudado ELSE 0 END) as oct,
                SUM(CASE WHEN mes=11 THEN recaudado ELSE 0 END) as nov,
                SUM(CASE WHEN mes=12 THEN recaudado ELSE 0 END) as dic
            ")
            ->get();

        $excel = view(
            'Presupuesto.BaseGastos.SeguimientoTabla0101',
            [
                'body' => $body,
                'anio' => $anio,
                'ue' => $ue,
                'ff' => $ff,
                'rubro' => $rubro,
                'codes' => $codes,
            ]
        )->render();
        return response()->json(compact('excel'));
    }
}
