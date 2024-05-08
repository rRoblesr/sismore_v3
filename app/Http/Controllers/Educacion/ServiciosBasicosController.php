<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\AvanceMatricula1Export;
use App\Exports\BasicaAlternativaExport;
use App\Exports\BasicaEspecialExport;
use App\Exports\BasicaRegularExport;
use App\Exports\NivelEducativoEBAExport;
use App\Exports\NivelEducativoEBEExport;
use App\Exports\NivelEducativoEBRExport;
use App\Exports\NivelEducativoExport;
use App\Exports\ServiciosBasicosExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\ImporServiciosBasicos;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Educacion\ServiciosBasicosRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ServiciosBasicosController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $mess = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal__()
    {
        $actualizado = '';
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporServiciosBasicosController::$FUENTE);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;
        $anios = ImportacionRepositorio::anios_porfuente(ImporServiciosBasicosController::$FUENTE);
        $aniomax = ImportacionRepositorio::aniosMax_porfuente(ImporServiciosBasicosController::$FUENTE)->anio;
        $ugel = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        $gestion = [["id" => 12, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        $area = Area::select('id', 'nombre')->get();
        $fecha = '';
        return view('educacion.ServiciosBasicos.Principal', compact('anios', 'aniomax', 'actualizado', 'ugel', 'area', 'fecha'));
    }

    public function principalTabla__(Request $rq)
    {
        switch ($rq->div) {
            case 'head':

                $valor2 = ServiciosBasicosRepositorio::principalTabla($rq->div . '2', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                $valor3 = ServiciosBasicosRepositorio::principalTabla($rq->div . '3', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                $valor1 = 100 * $valor3 / $valor2;
                $valor4 = $valor2 - $valor3;

                $valor1 = number_format($valor1, 1);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                if ($rq->servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($rq->servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($rq->servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($rq->servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($rq->servicio == 5) {
                    $tservicio = 'Internet';
                }


                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'tservicio'));

            case 'anal1':
                $info = ServiciosBasicosRepositorio::principalTabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);

                return response()->json(compact('info'));
            case 'tabla1':
                if ($rq->servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($rq->servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($rq->servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($rq->servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($rq->servicio == 5) {
                    $tservicio = 'Internet';
                }
                $base = ServiciosBasicosRepositorio::principalTabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->con = 0;
                    $foot->sin = 0;
                    $foot->indicador = 0;

                    foreach ($base as $key => $value) {
                        $value->indicador = round($value->indicador, 1);
                        $foot->total += $value->total;
                        $foot->con += (int)$value->con;
                        $foot->sin += (int)$value->sin;
                    }
                    $foot->indicador = round($foot->total > 0 ? 100 * $foot->con / $foot->total : 0, 1);
                }
                // return response()->json(compact('base', 'foot'));
                $excel = view('educacion.ServiciosBasicos.PrincipalTabla1', compact('base', 'foot', 'tservicio'))->render();

                // $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                // $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel'));

            case 'tabla2':
                if ($rq->servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($rq->servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($rq->servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($rq->servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($rq->servicio == 5) {
                    $tservicio = 'Internet';
                }
                $base = ServiciosBasicosRepositorio::principalTabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->con = 0;
                    $foot->sin = 0;
                    $foot->indicador = 0;
                    $foot->EBRtotal = 0;
                    $foot->EBRcon = 0;
                    $foot->EBRsin = 0;
                    $foot->EBEtotal = 0;
                    $foot->EBEcon = 0;
                    $foot->EBEsin = 0;
                    $foot->EBAtotal = 0;
                    $foot->EBAcon = 0;
                    $foot->EBAsin = 0;

                    foreach ($base as $key => $value) {
                        $value->indicador = round($value->indicador, 1);
                        $foot->total += $value->total;
                        $foot->con += (int)$value->con;
                        $foot->sin += (int)$value->sin;
                        $foot->EBRtotal += $value->EBRtotal;
                        $foot->EBRcon += $value->EBRcon;
                        $foot->EBRsin += $value->EBRsin;
                        $foot->EBEtotal += $value->EBEtotal;
                        $foot->EBEcon += $value->EBEcon;
                        $foot->EBEsin += $value->EBEsin;
                        $foot->EBAtotal += $value->EBAtotal;
                        $foot->EBAcon += $value->EBAcon;
                        $foot->EBAsin += $value->EBAsin;
                    }
                    $foot->indicador = round($foot->total > 0 ? 100 * $foot->con / $foot->total : 0, 1);
                }
                // return response()->json(compact('base', 'foot'));
                $excel = view('educacion.ServiciosBasicos.PrincipalTabla2', compact('base', 'foot', 'tservicio'))->render();

                // $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                // $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel'));

            case 'tabla3':
                $base = ServiciosBasicosRepositorio::principalTabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->servicio);
                $foot = [];
                if ($base->count() > 0) {
                }
                // return response()->json(compact('base', 'foot'));
                $excel = view('educacion.ServiciosBasicos.PrincipalTabla3', compact('base', 'foot'))->render();

                // $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                // $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel'));

            default:
                return [];
        }
    }



    public function aguapotable()
    {
        $actualizado = '';
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporServiciosBasicosController::$FUENTE);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;
        $anios = ImportacionRepositorio::anios_porfuente(ImporServiciosBasicosController::$FUENTE);
        $aniomax = ImportacionRepositorio::aniosMax_porfuente(ImporServiciosBasicosController::$FUENTE)->anio;
        // $ugel = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        // $gestion = [["id" => 12, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];

        $provincia = UbigeoRepositorio::provincia('25');
        $area = Area::select('id', DB::raw('upper(nombre) as nombre'))->get();
        $fecha = '';
        return view('educacion.ServiciosBasicos.AguaPotable', compact('anios', 'aniomax', 'actualizado', 'provincia', 'area', 'fecha'));
    }

    public function aguapotableTabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                // $imp = Importacion::where('fuenteImportacion_id', ImporServiciosBasicosController::$FUENTE)->where(DB::raw('year(fechaActualizacion)'), $rq->anio)->get();
                $valor2 = ServiciosBasicosRepositorio::aguapotableTabla($rq->div . '2', $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $valor3 = ServiciosBasicosRepositorio::aguapotableTabla($rq->div . '3', $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $valor1 = 100 * $valor3 / $valor2;
                $valor4 = $valor2 - $valor3;

                $valor1 = number_format($valor1, 1);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                if ($rq->servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($rq->servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($rq->servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($rq->servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($rq->servicio == 5) {
                    $tservicio = 'Internet';
                }


                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'tservicio'));

            case 'anal1':
                $data = ServiciosBasicosRepositorio::aguapotableTabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $info['categoria'] = [];
                $dx1 = [];
                $dx2 = [];
                $dx3 = [];
                $alto = 0;
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->anio;
                    $dx1[] = (float)$value->y;
                    $dx2[] = (float)$value->x;
                    $dx3[] = (float)$value->z;
                    $alto = (int)$value->y > $alto ? (int)$value->y : $alto;
                    $alto = (int)$value->x > $alto ? (int)$value->x : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denuminador',  'data' => $dx1];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => 'Indicador',   'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx3];
                return response()->json(compact('info', 'alto'));


            case 'anal2':
                $data = ServiciosBasicosRepositorio::aguapotableTabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $info['categoria'] = [];
                $dx1 = [];
                $dx2 = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                    $dx1[] = (int)$value->y;
                    $dx2[] = (int)$value->x;
                }

                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Locales Escolares', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'L.E con Agua', 'data' => $dx1];
                return response()->json(compact('info'));

            case 'anal3':
                $data = ServiciosBasicosRepositorio::aguapotableTabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $info['categoria'] = [];
                $dx1 = [];
                $dx2 = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->name;
                    $dx1[] = (int)$value->y;
                    $dx2[] = (int)$value->x - (int)$value->y;
                }

                $info['series'][] = ['name' => 'L.E con Agua', 'data' => $dx1];
                $info['series'][] = ['name' => 'Locales Escolares', 'data' => $dx2];

                return response()->json(compact('info'));
            case 'tabla1':
                if ($rq->servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($rq->servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($rq->servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($rq->servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($rq->servicio == 5) {
                    $tservicio = 'Internet';
                }
                $base = ServiciosBasicosRepositorio::aguapotableTabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->con = 0;
                    $foot->sin = 0;
                    $foot->indicador = 0;

                    foreach ($base as $key => $value) {
                        $value->indicador = round($value->indicador, 1);
                        $foot->total += $value->total;
                        $foot->con += (int)$value->con;
                        $foot->sin += (int)$value->sin;
                    }
                    $foot->indicador = round($foot->total > 0 ? 100 * $foot->con / $foot->total : 0, 1);
                }
                switch ($rq->vista) {
                    case 1:
                        $tablax = 'tabla1vista1';
                        break;
                    case 2:
                        $tablax = 'tabla1vista2';
                        break;
                    case 3:
                        $tablax = 'tabla1vista3';
                        break;
                    case 4:
                        $tablax = 'tabla1vista4';
                        break;
                    case 5:
                        $tablax = 'tabla1vista5';
                        break;
                    default:
                        break;
                }
                $xxx = Ubigeo::find($rq->distrito);
                $dis = $xxx ? $xxx->nombre : '';
                $excel = view('educacion.ServiciosBasicos.AguaPotableTabla1', compact('base', 'foot', 'tservicio', 'tablax', 'dis'))->render();
                return response()->json(compact('excel'));

            case 'tabla2':
                if ($rq->servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($rq->servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($rq->servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($rq->servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($rq->servicio == 5) {
                    $tservicio = 'Internet';
                }
                $base = ServiciosBasicosRepositorio::aguapotableTabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->con = 0;
                    $foot->sin = 0;
                    $foot->indicador = 0;
                    $foot->EBRtotal = 0;
                    $foot->EBRcon = 0;
                    $foot->EBRsin = 0;
                    $foot->EBEtotal = 0;
                    $foot->EBEcon = 0;
                    $foot->EBEsin = 0;
                    $foot->EBAtotal = 0;
                    $foot->EBAcon = 0;
                    $foot->EBAsin = 0;

                    foreach ($base as $key => $value) {
                        $value->indicador = round($value->indicador, 1);
                        $foot->total += $value->total;
                        $foot->con += (int)$value->con;
                        $foot->sin += (int)$value->sin;
                        $foot->EBRtotal += $value->EBRtotal;
                        $foot->EBRcon += $value->EBRcon;
                        $foot->EBRsin += $value->EBRsin;
                        $foot->EBEtotal += $value->EBEtotal;
                        $foot->EBEcon += $value->EBEcon;
                        $foot->EBEsin += $value->EBEsin;
                        $foot->EBAtotal += $value->EBAtotal;
                        $foot->EBAcon += $value->EBAcon;
                        $foot->EBAsin += $value->EBAsin;
                    }
                    $foot->indicador = round($foot->total > 0 ? 100 * $foot->con / $foot->total : 0, 1);
                }
                $excel = view('educacion.ServiciosBasicos.AguaPotableTabla2', compact('base', 'foot', 'tservicio'))->render();
                return response()->json(compact('excel'));

            case 'tabla3':
                $base = ServiciosBasicosRepositorio::principalTabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->area,  $rq->servicio);
                $foot = [];
                switch ($rq->vista) {
                    case 1:
                        $tablax = 'tabla3vista1';
                        break;
                    case 2:
                        $tablax = 'tabla3vista2';
                        break;
                    case 3:
                        $tablax = 'tabla3vista3';
                        break;
                    case 4:
                        $tablax = 'tabla3vista4';
                        break;
                    case 5:
                        $tablax = 'tabla3vista5';
                        break;
                    default:
                        break;
                }
                $excel = view('educacion.ServiciosBasicos.AguaPotableTabla3', compact('base', 'foot', 'tablax'))->render();
                return response()->json(compact('excel'));

            default:
                return [];
        }
    }

    public function principalTablaExport($div, $anio, $provincia, $distrito, $area, $servicio)
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRProvincia($anio == 3 ? 3 : $anioy->id, $provincia, $distrito,  $area);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($div, $anio, $provincia, $distrito,  $area);
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
                return compact('base', 'foot');

            case 'tabla2':
                if ($servicio == 1) {
                    $tservicio = 'Agua';
                } else if ($servicio == 2) {
                    $tservicio = 'Desague';
                } else if ($servicio == 3) {
                    $tservicio = 'Luz';
                } else if ($servicio == 4) {
                    $tservicio = 'Tres Servicios';
                } else if ($servicio == 5) {
                    $tservicio = 'Internet';
                }
                $base = ServiciosBasicosRepositorio::principalTabla($div, $anio, $provincia, $distrito,  $area,  $servicio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->con = 0;
                    $foot->sin = 0;
                    $foot->indicador = 0;
                    $foot->EBRtotal = 0;
                    $foot->EBRcon = 0;
                    $foot->EBRsin = 0;
                    $foot->EBEtotal = 0;
                    $foot->EBEcon = 0;
                    $foot->EBEsin = 0;
                    $foot->EBAtotal = 0;
                    $foot->EBAcon = 0;
                    $foot->EBAsin = 0;

                    foreach ($base as $key => $value) {
                        $value->indicador = round($value->indicador, 1);
                        $foot->total += $value->total;
                        $foot->con += (int)$value->con;
                        $foot->sin += (int)$value->sin;
                        $foot->EBRtotal += $value->EBRtotal;
                        $foot->EBRcon += $value->EBRcon;
                        $foot->EBRsin += $value->EBRsin;
                        $foot->EBEtotal += $value->EBEtotal;
                        $foot->EBEcon += $value->EBEcon;
                        $foot->EBEsin += $value->EBEsin;
                        $foot->EBAtotal += $value->EBAtotal;
                        $foot->EBAcon += $value->EBAcon;
                        $foot->EBAsin += $value->EBAsin;
                    }
                    $foot->indicador = round($foot->total > 0 ? 100 * $foot->con / $foot->total : 0, 1);
                }
                return  compact('base', 'foot', 'tservicio');

            case 'tabla3':
                $base = ServiciosBasicosRepositorio::principalTabla($div, $anio, $provincia, $distrito,  $area,  $servicio);
                $foot = [];
                return  compact('base', 'foot');
            default:
                return [];
        }
    }

    public function principalDownload($div, $anio, $provincia, $distrito, $area, $servicio)
    {
        if ($anio) {
            /* if ($div == 'tabla1') {
                $name = 'Basica_regular_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaRegularExport($div, $anio, $ugel, $gestion, $area, $provincia), $name);
            } */
            if ($div == 'tabla3') {
                $name = 'Servicio Basico ' . date('Y-m-d') . '.xlsx';
                return Excel::download(new ServiciosBasicosExport($div, $anio, $provincia, $distrito, $area, $servicio), $name);
            } else {
                $name = 'Servicio basico ' . date('Y-m-d') . '.xlsx';
                return Excel::download(new ServiciosBasicosExport($div, $anio, $provincia, $distrito, $area, $servicio), $name);
            }
        }
    }
}
