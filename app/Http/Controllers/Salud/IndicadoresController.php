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
use App\Models\Salud\ImporPadronActas;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\SFLRepositorio;
use App\Repositories\Parametro\IndicadorGeneralMetaRepositorio;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class IndicadoresController extends Controller
{
    public $mes = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
    public $mesname = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
    public static $pacto1_anio = 2023;
    public static $pacto1_mes = 5;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function PactoRegional()
    {
        $sector = 14;
        $instrumento = 8;
        $indsal = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        $sector = 4;
        $instrumento = 8;
        $indedu = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        $sector = 18;
        $instrumento = 8;
        $indviv = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);

        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('DIT-SAL-01');
        $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($ind->id);
        $provincia = UbigeoRepositorio::provincia('25');

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
        // // return response()->json(compact('imp'));
        $aniomax = $imp->anio;

        return view('salud.Indicadores.PactoRegional', compact('indsal', 'indedu', 'indviv', 'anio', 'provincia', 'aniomax'));
    }

    public function PactoRegionalActualizar2(Request $rq)
    {
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('DITSALUD01');
        $gls = IndicadorGeneralMetaRepositorio::getPacto1GLS($ind->id, $rq->anio);
        $gl = IndicadorGeneralMetaRepositorio::getPacto1GL($ind->id, $rq->anio);

        $pacto['DIT-SAL-01'] = [];
        $pacto['DIT-SAL-01']['avance'] =  round(100 * ($gl > 0 ? $gls / $gl : 0));
        $pacto['DIT-SAL-01']['actualizado'] = 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
        $pacto['DIT-SAL-01']['meta'] = '100%';
        $pacto['DIT-SAL-01']['cumple'] = $gls == $gl;

        $pacto['DIT-SAL-02'] = [];
        $pacto['DIT-SAL-02']['avance'] = rand(0, 100); //100 * ($gl > 0 ? $gls / $gl : 0);
        $pacto['DIT-SAL-02']['actualizado'] = 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
        $pacto['DIT-SAL-02']['meta'] = rand(0, 100);
        $pacto['DIT-SAL-02']['cumple'] = rand(0, 1);

        $pacto['DIT-SAL-03'] = [];
        $pacto['DIT-SAL-03']['avance'] = rand(0, 100); //100 * ($gl > 0 ? $gls / $gl : 0);
        $pacto['DIT-SAL-03']['actualizado'] = 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
        $pacto['DIT-SAL-03']['meta'] = rand(0, 100);
        $pacto['DIT-SAL-03']['cumple'] = rand(0, 1);

        $pacto['DIT-SAL-04'] = [];
        $pacto['DIT-SAL-04']['avance'] = rand(0, 100); //100 * ($gl > 0 ? $gls / $gl : 0);
        $pacto['DIT-SAL-04']['actualizado'] = 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
        $pacto['DIT-SAL-04']['meta'] = rand(0, 100);
        $pacto['DIT-SAL-04']['cumple'] = rand(0, 1);

        $pacto['DIT-SAL-05'] = [];
        $pacto['DIT-SAL-05']['avance'] = rand(0, 100); //100 * ($gl > 0 ? $gls / $gl : 0);
        $pacto['DIT-SAL-05']['actualizado'] = 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
        $pacto['DIT-SAL-05']['meta'] = rand(0, 100);
        $pacto['DIT-SAL-05']['cumple'] = rand(0, 1);

        return response()->json(compact('pacto'));
    }

    public function PactoRegionalActualizar(Request $rq)
    {
        $imp = null;
        $gls = 0;
        $gl = 0;
        switch ($rq->codigo) {
            case 'DIT-SAL-01':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo($rq->codigo);
                $gls = IndicadorGeneralMetaRepositorio::getPacto1GLS($ind->id, $rq->anio);
                $gl = IndicadorGeneralMetaRepositorio::getPacto1GL($ind->id, $rq->anio);
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-SAL-02':
                $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo($rq->codigo);
                $gls = IndicadorGeneralMetaRepositorio::getSalPacto2GLS($ind->id, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gl = IndicadorGeneralMetaRepositorio::getSalPacto2GL($ind->id, $rq->anio, 0, $rq->provincia, $rq->distrito);
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-SAL-03':
                $gls = 0;
                $gl = 0;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-SAL-04':
                $gls = 0;
                $gl = 0;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-SAL-05':
                $gls = 0;
                $gl = 0;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-EDU-01':
                $gls = 75;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-EDU-02':
                $gl = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0)->count();
                $gls = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1)->count();
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-EDU-03':
                $gls = 102;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-EDU-04':
                $gls = 25;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-VIV-01':
                $gls = 25;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-VIV-02':
                $gls = 50;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-VIV-03':
                $gls = 75;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-VIV-04':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-ART-01':
                $gls = 25;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-ART-02':
                $gls = 50;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-ART-03':
                $gls = 75;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            case 'DIT-ART-04':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                break;
            default:
                break;
        }

        $avance =  round(100 * ($gl > 0 ? $gls / $gl : 0));
        $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
        $meta = '100%';

        $cumple = $gls >= $gl;

        return response()->json(compact('avance', 'actualizado', 'meta', 'cumple', 'num', 'den'));
    }

    public function PactoRegionalDetalle($indicador_id)
    {
        $ind = IndicadorGeneral::find($indicador_id);
        switch ($ind->codigo) {
            case 'DIT-SAL-01':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto1', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));
            case 'DIT-SAL-02':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_2']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto2Anios($indicador_id);
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto2', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));
            case 'DIT-SAL-03':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto3', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));
                // return '';
            case 'DIT-SAL-04':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto4', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));
                // return '';
            case 'DIT-SAL-05':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto4', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));
                // return '';

            case 'DIT-EDU-01':
                return '';
            case 'DIT-EDU-02':
                $ff = SFL::select(DB::raw('max(fecha_registro) as ff'))->first();
                $actualizado = 'Actualizado al ' . date('d', strtotime($ff->ff)) . ' de ' . $this->mesname[date('m', strtotime($ff->ff)) - 1] . ' del ' . date('Y', strtotime($ff->ff));
                $anio = SFL::distinct()->select(DB::raw('year(fecha_registro) as anio'))->orderBy('anio')->get();
                $provincia = UbigeoRepositorio::provincia('25');
                $area = Area::all();
                $aniomax = $anio->max('anio');
                return view('salud.Indicadores.PactoRegionalEduPacto2', compact('actualizado', 'anio', 'provincia', 'aniomax', 'area', 'ind'));
            case 'DIT-EDU-03':
                return '';
            case 'DIT-EDU-04':
                return '';
            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    public function PactoRegionalSalPacto1Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        switch ($rq->div) {
            case 'head':
                $gls = IndicadorGeneralMetaRepositorio::getPacto1GLS($rq->indicador, $rq->anio);
                $gl = IndicadorGeneralMetaRepositorio::getPacto1GL($rq->indicador, $rq->anio);
                $gln = $gl - $gls;
                $ri = number_format(100 * ($gl > 0 ? $gls / $gl : 0));
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, $rq->distrito);
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax = $base->max('name');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {
                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax) {
                        $mm->y = 0;
                        foreach ($base as $bb) {
                            if ($bb->name == $mm->codigo) {
                                $mm->y = (int)$bb->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y = null;
                    }
                }
                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->mes;
                    $value->y = $value->y;
                    if ($key == 0)
                        $vv = $value->y;
                    if ($key > 0) {
                        if ($value->y) {
                            $value->y += $vv;
                            $vv = $value->y;
                        }
                    }
                    $info['dat'][] = $value->y;
                }
                return response()->json(compact('info', 'mes', 'base', 'mesmax'));
            case 'anal2':
                $base1 = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, $rq->distrito);
                $base2 = IndicadorGeneralMetaRepositorio::getPacto1Mensual2($rq->anio, $rq->distrito);
                $info = [];
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax1 = $base1->max('name');
                $mesmax2 = $base2->max('mes');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {

                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax1) {
                        $mm->y1 = 0;
                        foreach ($base1 as $bb1) {
                            if ($bb1->name == $mm->codigo) {
                                $mm->y1 = (int)$bb1->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y1 = null;
                    }

                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax2) {
                        $mm->y2 = 0;
                        foreach ($base2 as $bb2) {
                            if ($bb2->mes == $mm->codigo) {
                                $mm->y2 = (int)$bb2->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y2 = null;
                    }
                    $info['cat'][] = $mm->mes;
                    $info['dat'][] = $mm->y1;
                    $info['dat2'][] = $mm->y2;
                }
                return response()->json(compact('info', 'base1', 'base2', 'mes'));
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($rq->indicador, $rq->anio, $rq->mes);

                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla2($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla2', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));


            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto2Reports(Request $rq)
    {
        $ndis = $rq->distrito > 0 ? Ubigeo::find($rq->distrito)->nombre : '';
        switch ($rq->div) {
            case 'head':
                $gls = IndicadorGeneralMetaRepositorio::getSalPacto2GLS($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gl = IndicadorGeneralMetaRepositorio::getSalPacto2GL($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gln = intval($gl) - intval($gls);
                $ri = number_format(100 * ($gl > 0 ? $gls / $gl : 0), 1);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2Mensual($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                // return response()->json(compact('base'));
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax = $rq->anio == date('Y') ? date('m') : 12; //$base->max('name');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {
                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax) {
                        $mm->y = 0;
                        foreach ($base as $bb) {
                            if ($bb->name == $mm->codigo) {
                                $mm->y = (float)$bb->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y = null;
                    }
                }
                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->mes;
                    // $value->y = $value->y;
                    // if ($key == 0)
                    //     $vv = $value->y;
                    // if ($key > 0) {
                    //     if ($value->y) {
                    //         $value->y += $vv;
                    //         $vv = $value->y;
                    //     }
                    // }
                    $info['dat'][] = $value->y;
                }
                return response()->json(compact('info', 'mes', 'base', 'mesmax'));
            case 'anal2':
                // $base1 = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, $rq->distrito);
                // $base2 = IndicadorGeneralMetaRepositorio::getPacto1Mensual2($rq->anio, $rq->distrito);
                // $info = [];
                // $mes = Mes::select('codigo', 'abreviado as mes')->get();
                // $mesmax1 = $base1->max('name');
                // $mesmax2 = $base2->max('mes');
                // $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                // foreach ($mes as $mm) {

                //     if ($mm->codigo >= $limit && $mm->codigo <= $mesmax1) {
                //         $mm->y1 = 0;
                //         foreach ($base1 as $bb1) {
                //             if ($bb1->name == $mm->codigo) {
                //                 $mm->y1 = (int)$bb1->y;
                //                 break;
                //             }
                //         }
                //     } else {
                //         $mm->y1 = null;
                //     }

                //     if ($mm->codigo >= $limit && $mm->codigo <= $mesmax2) {
                //         $mm->y2 = 0;
                //         foreach ($base2 as $bb2) {
                //             if ($bb2->mes == $mm->codigo) {
                //                 $mm->y2 = (int)$bb2->y;
                //                 break;
                //             }
                //         }
                //     } else {
                //         $mm->y2 = null;
                //     }
                //     $info['cat'][] = $mm->mes;
                //     $info['dat'][] = $mm->y1;
                //     $info['dat2'][] = $mm->y2;
                // }
                // return response()->json(compact('info', 'base1', 'base2', 'mes'));
                return response()->json([]);
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla1($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $foot = clone $base[0];
                $foot->valor = 0;
                $foot->num = 0;
                $foot->den = 0;
                foreach ($base as $key => $value) {
                    $foot->valor += $value->valor;
                    $foot->num += $value->num;
                    $foot->den += $value->den;
                }
                $foot->valor = round($foot->valor / 19, 1);
                $foot->ind = round(100 * $foot->num / $foot->den, 1);
                $foot->cumple = $foot->ind >= $foot->valor ? 1 : 0;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla1', compact('base', 'foot', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla2($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $foot = clone $base[0];
                $foot->num = 0;
                $foot->den = 0;
                $foot->ind = 0;
                foreach ($base as $key => $value) {
                    $foot->num += $value->num;
                    $foot->den += $value->den;
                }
                $foot->ind = round(100 * $foot->num / $foot->den, 1);
                $foot->cumple = 0;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla2', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla3($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                foreach ($base as $key => $value) {
                    $value->unico = str_pad($value->unico, 8, '0', STR_PAD_LEFT);
                }
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla3', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));
            case 'tabla4':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla4($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla4', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));
            case 'tabla2tabla1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla2tabla1($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->red);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla2tabla1', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto2Export($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        switch ($div) {
            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla3($indicador, $anio, $mes, $provincia, $distrito);
                foreach ($base as $key => $value) {
                    $value->unico = str_pad($value->unico, 8, '0', STR_PAD_LEFT);
                }
                return compact('base');
            case 'tabla4':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla4($indicador, $anio, $mes, $provincia, $distrito);
                $aniob = $anio;
                return compact('base', 'aniob');
            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto2download($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($anio > 0) {
            switch ($div) {
                case 'tabla3':
                    $name = 'Listado de establecimientos de salud ' . date('Y-m-d') . '.xlsx';
                    break;
                case 'tabla4':
                    $name = 'Evaluación de cumplimiento' . date('Y-m-d') . '.xlsx';
                    break;
                default:
                    $name = 'sin nombre.xlsx';
                    break;
            }

            return Excel::download(new pactoregional1Export($div, $indicador, $anio, $mes, $provincia, $distrito), $name);
        }
    }

    public function PactoRegionalSalPacto3Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        switch ($rq->div) {
            case 'head':
                $gls = IndicadorGeneralMetaRepositorio::getPacto1GLS($rq->indicador, $rq->anio);
                $gl = IndicadorGeneralMetaRepositorio::getPacto1GL($rq->indicador, $rq->anio);
                $gln = $gl - $gls;
                $ri = number_format(100 * ($gl > 0 ? $gls / $gl : 0));
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, $rq->distrito);
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax = $base->max('name');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {
                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax) {
                        $mm->y = 0;
                        foreach ($base as $bb) {
                            if ($bb->name == $mm->codigo) {
                                $mm->y = (int)$bb->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y = null;
                    }
                }
                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->mes;
                    $value->y = $value->y;
                    if ($key == 0)
                        $vv = $value->y;
                    if ($key > 0) {
                        if ($value->y) {
                            $value->y += $vv;
                            $vv = $value->y;
                        }
                    }
                    $info['dat'][] = $value->y;
                }
                return response()->json(compact('info', 'mes', 'base', 'mesmax'));
            case 'anal2':
                $base1 = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, $rq->distrito);
                $base2 = IndicadorGeneralMetaRepositorio::getPacto1Mensual2($rq->anio, $rq->distrito);
                $info = [];
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax1 = $base1->max('name');
                $mesmax2 = $base2->max('mes');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {

                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax1) {
                        $mm->y1 = 0;
                        foreach ($base1 as $bb1) {
                            if ($bb1->name == $mm->codigo) {
                                $mm->y1 = (int)$bb1->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y1 = null;
                    }

                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax2) {
                        $mm->y2 = 0;
                        foreach ($base2 as $bb2) {
                            if ($bb2->mes == $mm->codigo) {
                                $mm->y2 = (int)$bb2->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y2 = null;
                    }
                    $info['cat'][] = $mm->mes;
                    $info['dat'][] = $mm->y1;
                    $info['dat2'][] = $mm->y2;
                }
                return response()->json(compact('info', 'base1', 'base2', 'mes'));
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($rq->indicador, $rq->anio, $rq->mes);

                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla2($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla2', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));


            default:
                return [];
        }
    }

    public function PactoRegionalEduPacto2Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        switch ($rq->div) {
            case 'head':
                $loc = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0)->count();
                $ssa = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1)->count();
                $nsa = $loc - $ssa; //SFLRepositorio::listado_iiee($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1)->count();
                $rin = number_format(100 * ($loc > 0 ? $ssa / $loc : 0));
                return response()->json(['rq' => $rq->all(), 'loc' => $loc, 'ssa' => $ssa, 'nsa' => $nsa, 'rin' => $rin]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2anal1($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0);
                $info['series'] = [];
                $dx1 = [];
                $dx2 = [];
                foreach ($base as $keyi => $ii) {
                    $info['categoria'][] = $ii->provincia;
                    $dx1[$keyi] = (int)$ii->t1;
                    $dx2[$keyi] = (int)$ii->tt - (int)$ii->t1;
                }
                // $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'SANEADO',  'data' => $dx2];
                // $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'NO SANEADO', 'data' => $dx3];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' =>    'SANEADO', 'data' => $dx1];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'NO SANEADO', 'data' => $dx2];
                return response()->json(compact('info', 'base'));
                // return [];
            case 'anal2':
                $tt = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0)->count();
                $ssa = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1)->count();
                $nsa = $tt - $ssa;
                $info = [];
                $info[] = ['name' => 'SANEADO', 'y' => $ssa];
                $info[] = ['name' => 'NO SANEADO', 'y' => $nsa];
                // $info = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                // $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
                // return [];
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla1($rq->indicador, $rq->anio);

                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                // $excel = DB::select('call edu_pa_sfl_porlocal_distrito(?,?,?,?)', [0, 0, 0, 0]);
                // return response()->json(compact('excel'));
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla2($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla2', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla3($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla3', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));
            case 'tabla4':
                $base = SFLRepositorio::get_iiee(2024, 0, 0, 0, 0);
                // $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla3($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla4', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            default:
                return [];
        }
    }

    public function exportarPDF($id)
    {
        $ind = IndicadorGeneral::select('codigo', 'ficha_tecnica')->where('id', $id)->first();
        if ($ind->ficha_tecnica) {
            header('Content-Type: application/pdf');
            echo base64_decode($ind->ficha_tecnica);

            // $b64d = base64_decode($ind->ficha_tecnica);
            // $pdf = fopen('aaa.pdf', 'w');
            // fwrite($pdf, $b64d);
            // fclose($pdf);
            // echo $b64d;
            //echo file_put_contents("aaaa.pdf", base64_decode($ind->ficha_tecnica));
        } else {
            echo 'archivo PDF no encontrado';
        }
    }


    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
