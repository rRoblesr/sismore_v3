<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\SuperiorPedagogicoExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImporCensoMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SuperiorPedagogicoController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $cedula = '5A';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ugel()
    {
        $ugel = ImporCensoMatriculaRepositorio::ugels($this->cedula);
        return response()->json(compact('ugel'));
    }

    public function area()
    {
        $area = ImporCensoMatriculaRepositorio::area($this->cedula);
        return response()->json(compact('area'));
    }

    public function iiee(Request $rq)
    {
        $ie = ImporCensoMatriculaRepositorio::iiee($rq->anio, $this->cedula);
        return response()->json(compact('ie'));
    }

    public function principal()
    {
        $actualizado = '';

        $impor = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE); //nexus $imp3

        $strTableta = strtotime($impor->fechaActualizacion);
        $actualizado = 'Actualizado al ' . date('d', $strTableta) . ' de ' . $this->mes[date('m', $strTableta) - 1] . ' del ' . date('Y', $strTableta);

        $anios = ImporCensoMatriculaRepositorio::anios();
        $maxAnio = ImporCensoMatriculaRepositorio::anioMax();

        return view('educacion.SuperiorPedagogico.Principal', compact(
            'actualizado',
            'maxAnio',
            'anios',
        ));
    }

    public function principalHead(Request $rq)
    {
        $valor1 = ImporCensoMatriculaRepositorio::_5APrincipalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 1);
        $valor2 = ImporCensoMatriculaRepositorio::_5APrincipalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 2);
        $valor3 = ImporCensoMatriculaRepositorio::_5APrincipalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 3);
        $valor4 = ImporCensoMatriculaRepositorio::_5APrincipalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 4);
        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);
        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
    }

    public function principalTabla(Request $rq)
    {
        switch ($rq->div) {
            case 'anal1':
                $banio = 0;
                $btotal = 0;
                $imps = ImporCensoMatriculaRepositorio::listarAnios6();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                    if ($key == 0) {
                        $inicio = ImporCensoMatriculaRepositorio::_5ATotalEstudianteAnio($value->anio - 1, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion);
                        if ($inicio) {
                            $banio = $inicio->anio;
                            $btotal = (int)$inicio->total;
                        } else {
                            $banio = 0;
                            $btotal = 0;
                        }
                    }
                }

                $totales = ImporCensoMatriculaRepositorio::_5AReportes($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 1);

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($imps as $keyi => $ii) {
                    $alto = $btotal > $alto ? $btotal : $alto;
                    $estadoii = false;
                    foreach ($totales as $keyj => $jj) {
                        if ($ii->anio == $jj->anio) {
                            if ($ii->anio == 2018) {
                                $dx2[$keyi] = $btotal;
                                $dx3[$keyi] = (int)$jj->total;
                                $dx4[$keyi] = round(100 * (int)$jj->total / $btotal, 1);
                                $btotal = (int)$jj->total;
                            } else {
                                $dx2[$keyi] = $btotal;
                                $dx3[$keyi] = (int)$jj->total;
                                $dx4[$keyi] = $btotal > 0 ? round(100 * (int)$jj->total / $btotal, 1) : 0;
                                $btotal = (int)$jj->total;
                            }
                            $estadoii = true;
                            break;
                        }
                    }
                    if (!$estadoii) {
                        $dx2[$keyi] = (int)$btotal;
                        $dx3[$keyi] = 0;
                        $dx4[$keyi] = round(0, 1);
                        $btotal = 0;
                    }
                }


                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx2];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;

                $foot['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE);
                $foot['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'foot')); //, 'totales', 'imps', 'inicio'
            case 'anal2':
                $imps = ImporCensoMatriculaRepositorio::listarAnios6();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $totales = ImporCensoMatriculaRepositorio::_5AReportes($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 2);

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }

                foreach ($imps as $keyi => $ii) {

                    $estadoii = false;
                    foreach ($totales as $keyj => $jj) {
                        if ($ii->anio == $jj->anio) {
                            $alto = (int)$jj->at > $alto ? (int)$jj->at : $alto;
                            $dx2[$keyi] = (int)$jj->at;
                            $alto = (int)$jj->t > $alto ? (int)$jj->t : $alto;
                            $dx3[$keyi] = (int)$jj->t;
                            $btotal = (int)$jj->total;
                            $estadoii = true;
                            break;
                        }
                    }
                    if (!$estadoii) {
                        $dx2[$keyi] = 0;
                        $dx3[$keyi] = 0;
                        $dx4[$keyi] = round(0, 1);
                        $btotal = 0;
                    }
                }

                $info['series'][] = ['name' => 'Postulante', 'data' => $dx2];
                $info['series'][] = ['name' => 'Ingresante', 'data' => $dx3];
                $info['maxbar'] = $alto;

                $foot['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE);
                $foot['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'totales', 'imps', 'foot'));
            case 'anal3':
                $info = ImporCensoMatriculaRepositorio::_5AReportes($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 3);
                $categoria = [];
                $hh = [];
                $mm = [];
                foreach ($info as $key => $value) {
                    $categoria[] = $value->name;
                    $hh[] = (int)$value->h;
                    $mm[] = (int)$value->m;
                }
                $series[] = ['name' => 'Hombres', 'data' => $hh];
                $series[] = ['name' => 'Mujer', 'data' => $mm];
                foreach ($info as $key => $value) {
                    $value->y = (int)$value->h + (int)$value->m;
                }

                $foot['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE);
                $foot['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('categoria', 'series', 'foot'));
            case 'anal4':
                $info = ImporCensoMatriculaRepositorio::_5AReportes($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 4);
                $categoria = [];
                $hh = [];
                $mm = [];
                foreach ($info as $key => $value) {
                    if ((int)$value->h + (int)$value->m > 0) {
                        $categoria[] = $value->name;
                        $hh[] = (int)$value->h;
                        $mm[] = (int)$value->m;
                    }
                }
                $series[] = ['name' => 'Hombre', 'data' => $hh];
                $series[] = ['name' => 'Mujer', 'data' => $mm];
                foreach ($info as $key => $value) {
                    $value->y = (int)$value->h + (int)$value->m;
                }

                $foot['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE);
                $foot['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('categoria', 'series', 'foot'));
            case 'tabla1':
                $base = ImporCensoMatriculaRepositorio::_5AReportes($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion, 5);
                $distrito = Ubigeo::where('codigo', 'like', '25%')->where(DB::raw('length(codigo)'), 6)->get();
                $gestion = DB::table('censo_gestion')->get();
                $area = DB::table('censo_area')->get();
                $docentes = ImporCensoMatriculaRepositorio::_5ATotalDocentesAnioModular($rq->anio, $rq->provincia, $rq->distrito, $rq->iiee,  $rq->area, $rq->gestion, 1);
                $meta = ImporCensoMatriculaRepositorio::_5ATotalEstudiantesAnioMeta($rq->anio - 1, $rq->provincia, $rq->distrito, $rq->iiee, $rq->area, $rq->gestion);
                foreach ($base as $key => $bb) {
                    foreach ($gestion as $key => $gg) {
                        if ($bb->gestion == $gg->codigo) {
                            $bb->gestion = $gg->nombre;
                            break;
                        }
                    }
                    foreach ($area as $key => $aa) {
                        if ($bb->area == $aa->codigo) {
                            $bb->area = $aa->nombre;
                            break;
                        }
                    }
                    foreach ($meta as $key => $mm) {
                        if ($bb->modular == $mm->modular) {
                            $bb->meta = $mm->meta;
                            break;
                        }
                    }
                    foreach ($distrito as $key => $di) {
                        if ($bb->distrito == $di->codigo) {
                            $bb->distrito = $di->nombre;
                            break;
                        }
                    }
                    $bb->indicador = $bb->meta > 0 ? 100 * ($bb->at + $bb->t) / $bb->meta : 100;
                    $n = 0;
                    $c = 0;
                    $existe = false;
                    foreach ($docentes as $key => $dd) {
                        if ($bb->modular == $dd->modular) {
                            $n = $dd->n;
                            $c = $dd->c;
                            $existe = true;
                            break;
                        }
                    }
                    if (!$existe) {
                        $n = 0;
                        $c = 0;
                    }
                    $bb->n = $n;
                    $bb->c = $c;
                }

                $foot = clone $base[0];
                $foot->meta = 0;
                $foot->at = 0;
                $foot->t = 0;
                $foot->indicador = 0;
                $foot->c = 0;
                $foot->n = 0;
                foreach ($base as $key => $value) {
                    $foot->meta += $value->meta;
                    $foot->at += $value->at;
                    $foot->t += $value->t;
                    $foot->c += $value->c;
                    $foot->n += $value->n;
                }
                $foot->indicador = $foot->meta > 0 ? 100 * ($foot->at + $foot->t) / $foot->meta : 100;
                $excel = view('educacion.SuperiorPedagogico.PrincipalTabla1excel', compact('base', 'foot'))->render();

                $foot['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE);
                $foot['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('excel', 'foot'));
            default:
                return [];
        }
    }

    public function download($ano, $ugel, $area, $gestion)
    {
        if ($ano) {
            $name = 'Superior_Pedagogico_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SuperiorPedagogicoExport($ano, $ugel, $area, $gestion), $name);
        }
    }
}
