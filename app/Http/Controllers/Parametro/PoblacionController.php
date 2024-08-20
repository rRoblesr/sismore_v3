<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionDiresa;
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
                $info = PoblacionDiresa::from('par_poblacion_diresa as pd')->select('pd.rango', 'pd.sexo', DB::raw('SUM(pd.total) conteo'))->groupBy('rango', 'sexo')->orderBy('rango')->get();

                return response()->json(compact('info'));
            case 'anal3':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                $anioi = 0;
                $aniof = 0;
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = [];
                }
                foreach ($data as $key => $value) {
                    foreach ($info['dat'] as $key2 => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key2][] = $value->conteo;
                        }
                    }
                    if ($key == 0) $anioi = $value->anio;
                    if ($key == $data->count() - 1) $aniof = $value->anio;
                }
                $info['dat'] = [];
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$key]];
                }

                $reg['periodo'] = "$anioi - $aniof";
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal4':
                $info = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal5':
                $info = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                }
                $ii = 0;
                foreach ($data->unique('eib') as $key => $value) {
                    $xx[$value->eib] = [];
                    $xa[$ii++] = $value->eib;
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => []];
                }
                foreach ($data as $key => $value) {
                    $xx[$value->eib][] = $value->conteo;
                }

                $xy = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $va = $xx[$xa[0]][$key];
                    $vb = $xx[$xa[1]][$key];
                    $vap = round(100 * $va / ($va + $vb), 0);
                    $vbp = round(100 * $vb / ($va + $vb), 0);
                    $xy[$xa[0]][$key] = $vap;
                    $xy[$xa[1]][$key] = $vbp;
                }

                $info['series'] = [];
                foreach ($data->unique('eib') as $key => $value) {
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => $xy[$value->eib]];
                }
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));

            case 'anal7':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal8':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
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
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla1', compact('base', 'foot'))->render();

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
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
