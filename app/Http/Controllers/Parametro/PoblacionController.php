<?php

namespace App\Http\Controllers\Parametro;

use App\Exports\ImporPadronSiagieExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporMatricula;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Poblacion;
use App\Models\Parametro\PoblacionDetalle;
use App\Repositories\Educacion\ImporMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
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
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $actualizado = ''; // 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        //$provincia = UbigeoRepositorio::provincia25();
        $ugel = MatriculaGeneralRepositorio::ugels();
        $area = MatriculaGeneralRepositorio::areas();

        $fecha = '';

        return view('parametro.Poblacion.Principal', compact('anios', 'aniomax', 'actualizado', 'ugel', 'area', 'fecha'));
    }

    public function poblacionprincipaltabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh = MatriculaGeneralRepositorio::basicaregulartabla('mhead', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $valor1 = (int)$mh->conteo;
                $valor2 = (int)$mh->conteoi;
                $valor3 = (int)$mh->conteop;
                $valor4 = (int)$mh->conteos;
                $aa = Anio::find($rq->anio);
                $aav =  -1 + (int)$aa->anio;
                $aa = Anio::where('anio', $aav)->first();
                $mh = MatriculaGeneralRepositorio::metaEBR($rq->anio == 3 ? 3 : $aa->id, $rq->ugel, $rq->gestion,  $rq->area);
                $valor1x = (int)$mh->conteo;
                $valor2x = (int)$mh->conteoi;
                $valor3x = (int)$mh->conteop;
                $valor4x = (int)$mh->conteos;

                $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 0, 1);
                $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 0, 1);
                $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 0, 1);
                $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 0, 1);

                $valor1 = number_format($valor1, 0);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
            case 'anal1':
                $datax = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['series'] = [];
                $alto = 0;
                $btotal = 0;
                $anioi = 0;
                $aniof = 0;
                // foreach ($datax as $key => $value) {
                $dx2[] = null;
                $dx3[] = null;
                $dx4[] = null;
                // }
                foreach ($datax as $keyi => $ii) {
                    $info['categoria'][] = $ii->anio;
                    $n = (int)$ii->conteo;
                    $d = $ii->anio == 2018 ? $n : (int)$datax[$keyi - 1]->conteo;
                    $dx3[$keyi] = $n;
                    $dx4[$keyi] = $d > 0 ? round(100 * $n / $d, 1) : 0;
                    $alto = $n > $alto ? $n : $alto;
                    if ($keyi == 0) $anioi = $ii->anio;
                    if ($keyi == $datax->count() - 1) $aniof = $ii->anio;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Avance', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;

                $reg['fuente'] = 'Siagie - MINEDU';
                $reg['periodo'] = "período $anioi - $aniof";
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $periodo = Mes::select('codigo', 'abreviado as mes', DB::raw('0 as conteo'))->get();
                $datax = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                $mesmax = $datax->max('mes');
                foreach ($periodo as $key => $pp) {
                    $info['cat'][$key] = $pp->mes;
                    if ($pp->codigo > $mesmax) {
                        $info['dat'][$key] = null;
                    } else {
                        $info['dat'][$key] = 0;
                        foreach ($datax as $dd) {
                            if ($dd->mes == $pp->codigo) {
                                $info['dat'][$key] = $key > 0 ? $info['dat'][$key - 1] + $dd->conteo : $dd->conteo;
                                break;
                            }
                        }
                    }
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'datax'));
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
