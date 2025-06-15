<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\CensoDocenteInicialExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImporCensoDocenteRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CensoDocenteController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $mess = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function PersonalDocente()
    {
        $actualizado = '';

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
        $importacion_id = $imp->id;
        $anioMax = $imp->anio;
        $actualizado = 'Fuente: Censo Educativo, Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;
        $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteimportacion_id', ImporCensoDocenteController::$FUENTE)->orderBy('anio', 'asc')->get();
        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();

        return  view(
            'educacion.CensoDocente.PersonalDocente',
            compact(
                'importacion_id',
                'anioMax',
                'anios',
                'provincias',
                'distritos',
                'actualizado',
            )
        );
    }

    public function PersonalDocenteTabla(Request $rq)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($rq->div) {
            case 'head':
                $base = ImporCensoDocenteRepositorio::PersonaDocente('head', $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                $valor1 = (int)$base->docentes;
                $valor2 = (int)$base->directores;
                $valor3 = (int)$base->subdirectores;
                $valor4 = (int)$base->auxiliares;

                $card1 = number_format($valor1, 0);
                $card2 = number_format($valor2, 0);
                $card3 = number_format($valor3, 0);
                $card4 = number_format($valor4, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));

            case 'anal1':
                $data = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                foreach ($data['anios'] as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }
                $dx = $data['docentes'];
                //$nx = $data['titulados'];

                $info['series'] = [];
                $alto = 0;

                $dx2[] = null;
                $dx3[] = null;
                $dx4[] = null;
                foreach ($dx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    //$dx3[$key] = (int)$dx[$key]->d;
                    //$dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    //$alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    //$alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx3];
                //$info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'anal2':
                $query = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                $v1 =  (int)$query->dh;
                $v2 =  (int)$query->dm;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'anal3':
                $data = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                foreach ($data['anios'] as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }
                $dx = $data['docentes'];
                //$nx = $data['titulados'];

                $info['series'] = [];
                $alto = 0;

                $dx2[] = null;
                $dx3[] = null;
                //$dx4[] = null;
                foreach ($dx as $key => $value) {
                    $dx2[$key] = (int)$value->dn;
                    $dx3[$key] = (int)$value->dc;
                    //$dx3[$key] = (int)$dx[$key]->d;
                    //$dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    //$alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    //$alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => 'Nombrados',   'data' => $dx2];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => 'Contratados',  'data' => $dx3];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));

            case 'anal4':
                $query = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                $v1 =  (int)$query->dn;
                $v2 =  (int)$query->dc;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'anal5':
                $data = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                foreach ($data['anios'] as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }
                $dx = $data['docentes'];
                //$nx = $data['titulados'];

                $info['series'] = [];
                $alto = 0;

                $dx2[] = null;
                $dx3[] = null;
                //$dx4[] = null;
                foreach ($dx as $key => $value) {
                    $dx2[$key] = (int)$value->pub;
                    $dx3[$key] = (int)$value->pri;
                    //$dx3[$key] = (int)$dx[$key]->d;
                    //$dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    //$alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    //$alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => 'Público',   'data' => $dx2];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => 'Privado',  'data' => $dx3];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));

            case 'anal6':
                $query = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, 0);
                $v1 =  (int)$query->pub;
                $v2 =  (int)$query->pri;
                $puntos[] = ['name' => 'Público', 'y' => $v1];
                $puntos[] = ['name' => 'Privado', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'tabla1':
                $base = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tpubc = 0;
                    $foot->tpubn = 0;
                    $foot->tpric = 0;
                    $foot->tprin = 0;
                    $foot->turbc = 0;
                    $foot->turbn = 0;
                    $foot->trurc = 0;
                    $foot->trurn = 0;
                    $foot->tt = 0;

                    foreach ($base as $key => $value) {
                        $foot->tpubc += $value->tpubc;
                        $foot->tpubn += $value->tpubn;
                        $foot->tpric += $value->tpric;
                        $foot->tprin += $value->tprin;
                        $foot->turbc += $value->turbc;
                        $foot->turbn += $value->turbn;
                        $foot->trurc += $value->trurc;
                        $foot->trurn += $value->trurn;
                        $foot->tt += $value->tt;
                    }
                    $excel = view('educacion.CensoDocente.PersonalDocenteTable1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('educacion.CensoDocente.PersonalDocenteTable1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }

            case 'tabla2':
                $base = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area);
                $head = [];
                $foot = [];
                if ($base->count() > 0) {
                    $ii = 0;
                    foreach ($base->unique('modalidad') as $key => $vv) {
                        $head[$ii++] = clone $vv;
                    }

                    foreach ($head as $key => $value) {
                        $value->tpubc = 0;
                        $value->tpubn = 0;
                        $value->tpric = 0;
                        $value->tprin = 0;
                        $value->turbc = 0;
                        $value->turbn = 0;
                        $value->trurc = 0;
                        $value->trurn = 0;
                        $value->tt = 0;
                    }

                    $foot = clone $base[0];
                    $foot->tpubc = 0;
                    $foot->tpubn = 0;
                    $foot->tpric = 0;
                    $foot->tprin = 0;
                    $foot->turbc = 0;
                    $foot->turbn = 0;
                    $foot->trurc = 0;
                    $foot->trurn = 0;
                    $foot->tt = 0;

                    foreach ($base as $key => $value) {
                        foreach ($head as $key => $hh) {
                            if ($hh->modalidad == $value->modalidad) {
                                $hh->tpubc += $value->tpubc;
                                $hh->tpubn += $value->tpubn;
                                $hh->tpric += $value->tpric;
                                $hh->tprin += $value->tprin;
                                $hh->turbc += $value->turbc;
                                $hh->turbn += $value->turbn;
                                $hh->trurc += $value->trurc;
                                $hh->trurn += $value->trurn;
                                $hh->tt += $value->tt;
                            }
                        }

                        $foot->tpubc += $value->tpubc;
                        $foot->tpubn += $value->tpubn;
                        $foot->tpric += $value->tpric;
                        $foot->tprin += $value->tprin;
                        $foot->turbc += $value->turbc;
                        $foot->turbn += $value->turbn;
                        $foot->trurc += $value->trurc;
                        $foot->trurn += $value->trurn;
                        $foot->tt += $value->tt;
                    }
                    // return compact('head', 'base', 'foot');
                    $excel = view('educacion.CensoDocente.PersonalDocenteTable2excel', compact('head', 'base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('educacion.CensoDocente.PersonalDocenteTable2excel', compact('head', 'base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            case 'tabla3':
                $base = ImporCensoDocenteRepositorio::PersonaDocente($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }
                    // return compact('base', 'foot');
                    $excel = view('educacion.CensoDocente.PersonalDocenteTable3excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel', 'base', 'foot'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('educacion.CensoDocente.PersonalDocenteTable3excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
        }
    }

    public function PersonalDocenteExport($div, $anio, $provincia, $distrito, $gestion)
    {
        switch ($div) {
            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_1AReportes($div, $anio, $provincia, $distrito, $gestion, 0);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->avance = $value->total ? (100 * $value->tt / $value->total) : 0;
                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }

                    $foot->avance = $foot->total ? (100 * $foot->tt / $foot->total) : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }
            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_1AReportes($div, $anio, $provincia, $distrito, $gestion, 0);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $value->avance = $value->td > 0 ? (100 * $value->tt / $value->td) : 0;
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    $foot->avance = $foot->td > 0 ? (100 * $foot->tt / $foot->td) : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }

            default:
                return [];
        }
    }

    public function PersonalDocenteDownload($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'DOCENTE CON TÍTULO EN EDUCACIÓN INICIAL' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocenteInicialExport('ctabla1', $anio, $provincia, $distrito, $gestion), $name);
        }
    }
}
