<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionDiresa;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\PoblacionProyectada;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
use App\Repositories\Parametro\PoblacionProyectadaRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class PoblacionController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function poblacionprincipal()
    {
        $anios = PoblacionProyectada::distinct()->select('anio')->get();
        $provincia = UbigeoRepositorio::provincia_select('25');

        return view('parametro.Poblacion.Principal', compact('anios',  'provincia'));
    }

    public function poblacionprincipaltabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $card1 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, ''));
                $card2 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, 'UCAYALI'));
                $card3 = PoblacionProyectadaRepositorio::conteo(2024, '');
                $card4 = PoblacionProyectadaRepositorio::conteo(2024, '');

                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $info = PoblacionDiresa::from('par_poblacion_diresa as pd')->join('par_ubigeo as dd', 'dd.id', '=', 'pd.ubigeo_id')
                    ->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia')->select('pp.nombre', DB::raw('SUM(pd.total) conteo'))->groupBy('pp.nombre')->get();

                return response()->json(compact('info'));
            case 'anal2':
                $data = PoblacionDiresa::from('par_poblacion_diresa as pd')->select('pd.rango', 'pd.sexo', DB::raw('SUM(pd.total) conteo'))
                    ->whereNotIn('rango', ['6-11 meses', '28 dias', '0-5 meses', 'gestantes', 'nacimientos'])->groupBy('rango', 'sexo')->orderBy('rango')->get();
                $info['categoria'] = [];
                $info['men'] = [];
                $info['women'] = [];
                foreach ($data->unique('rango') as $key => $value) {
                    $info['categoria'][] = $value->rango == '85 y más' ? '85 - +' : $value->rango;
                }
                foreach ($data as $key => $value) {
                    if ($value->sexo == 'HOMBRE')
                        $info['men'][] = -(int)$value->conteo;
                    else
                        $info['women'][] = (int)$value->conteo;
                }
                return response()->json(compact('info', 'data'));
            case 'anal3':
                $data = PoblacionPN::from('par_poblacion_padron_nominal as pn')->join('par_sexo as s', 's.id', '=', 'pn.sexo_id')
                    ->select('pn.anio', 's.nombre as sexo', DB::raw('sum(0a+1a+2a+3a+4a+5a) as conteo'))->where('anio', '>', 2018)->groupBy('anio', 'sexo')->get();
                $info['categoria'] = [];
                $info['men'] = [];
                $info['women'] = [];
                $rango = '';
                foreach ($data->unique('anio') as $value) {
                    $info['categoria'][] = $value->anio;
                }
                foreach ($info['categoria'] as $key => $value) {
                    if ($key == 0) $rango .= $value . ' - ';
                    if ($key == count($info['categoria']) - 1) $rango .= $value;
                }
                foreach ($data as $key => $value) {
                    if ($value->sexo == 'HOMBRE')
                        $info['men'][] = (int)$value->conteo;
                    else
                        $info['women'][] = (int)$value->conteo;
                }
                return response()->json(compact('info', 'rango', 'data'));
            case 'anal4':
                $data = PoblacionPN::from('par_poblacion_padron_nominal as pn')->join('par_sexo as s', 's.id', '=', 'pn.sexo_id')
                    ->select('pn.anio', 's.nombre as sexo', DB::raw('sum(0a) as c0a'), DB::raw('sum(1a) as c1a'), DB::raw('sum(2a) as c2a'), DB::raw('sum(3a) as c3a'), DB::raw('sum(4a) as c4a'), DB::raw('sum(5a) as c5a'))
                    ->where('anio', 2024)->groupBy('anio', 'sexo')->get();
                $info['categoria'] = ['<1A', '1A', '2A', '3A', '4A', '5A'];
                $info['men'] = [(int)$data[0]->c0a, (int)$data[0]->c1a, (int)$data[0]->c2a, (int)$data[0]->c3a, (int)$data[0]->c4a, (int)$data[0]->c5a];
                $info['women'] = [(int)$data[1]->c0a, (int)$data[1]->c1a, (int)$data[1]->c2a, (int)$data[1]->c3a, (int)$data[1]->c4a, (int)$data[1]->c5a];

                return response()->json(compact('info', 'data'));

            case 'tabla1':
                $base = PoblacionPN::from('par_poblacion_padron_nominal as pn')->join('par_ubigeo as u', 'u.id', '=', 'pn.ubigeo_id')
                    ->select(
                        'pn.anio',
                        'u.nombre as distrito',
                        DB::raw('sum(0a+1a+2a+3a+4a+5a) as total'),
                        DB::raw('sum(0a) as c0a'),
                        DB::raw('sum(1a) as c1a'),
                        DB::raw('sum(2a) as c2a'),
                        DB::raw('sum(3a) as c3a'),
                        DB::raw('sum(4a) as c4a'),
                        DB::raw('sum(5a) as c5a'),
                        DB::raw('sum("28dias") as ee1'),
                        DB::raw('sum("0_5meses") as ee2'),
                        DB::raw('sum("6_11meses") as ee3')
                    )
                    ->where('anio', 2024)->groupBy('anio', 'distrito')->get();
                // $aniox = Anio::find($rq->anio);
                // $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                // $meta = MatriculaGeneralRepositorio::metaEBRProvincia($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area);
                // $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->c0a = 0;
                    $foot->c1a = 0;
                    $foot->c2a = 0;
                    $foot->c3a = 0;
                    $foot->c4a = 0;
                    $foot->c5a = 0;
                    $foot->ee1 = 0;
                    $foot->ee2 = 0;
                    $foot->ee3 = 0;

                    foreach ($base as $key => $value) {
                        $value->total = 0;
                        $foot->total += $value->total;
                        $foot->c0a += $value->c0a;
                        $foot->c1a += $value->c1a;
                        $foot->c2a += $value->c2a;
                        $foot->c3a += $value->c3a;
                        $foot->c4a += $value->c4a;
                        $foot->c5a += $value->c5a;
                        $foot->ee1 += $value->ee1;
                        $foot->ee2 += $value->ee2;
                        $foot->ee3 += $value->ee3;
                    }
                    // $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = ''; //view('educacion.MatriculaGeneral.BasicaRegularTabla1', compact('base', 'foot'))->render();

                // $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                // $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel','foot', 'base'));
            case 'tabla1x':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRProvincia($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRDistrito($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }

                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla2', compact('base', 'foot'))->render();
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla2x':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRDistrito($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }

                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla2', compact('base', 'foot'))->render();
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla3':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRCentroPoblado($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->centropoblado == $mm->centropoblado) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla3', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            case 'tabla3x':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRCentroPoblado($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->centropoblado == $mm->centropoblado) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla3', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));
            default:
                return [];
        }
    }
}
