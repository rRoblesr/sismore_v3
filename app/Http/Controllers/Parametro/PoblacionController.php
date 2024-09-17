<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\CentroPoblado;
use App\Models\Parametro\EtapaVida;
use App\Models\Parametro\PoblacionDiresa;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\PoblacionProyectada;
use App\Models\Parametro\PueblosIndigenas;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\PoblacionDiresaRepositorio;
use App\Repositories\Parametro\PoblacionPNRepositorio;
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
    public $pe_states = [
        '01' => 'pe-am',
        '02' => 'pe-an',
        '03' => 'pe-ap',
        '04' => 'pe-ar',
        '05' => 'pe-ay',
        '06' => 'pe-cj',
        '07' => 'pe-3341',
        '08' => 'pe-cs',
        '09' => 'pe-hv',
        '10' => 'pe-hc',
        '11' => 'pe-ic',
        '12' => 'pe-ju',
        '13' => 'pe-ll',
        '14' => 'pe-lb',
        '15' => 'pe-lr',
        '16' => 'pe-lo',
        '17' => 'pe-md',
        '18' => 'pe-mq',
        '19' => 'pe-pa',
        '20' => 'pe-pi',
        '21' => 'pe-cl',
        '22' => 'pe-sm',
        '23' => 'pe-ta',
        '24' => 'pe-tu',
        '25' => 'pe-uc',
        '26' => 'pe-145'
    ];

    public $pe_pv = [
        '2501' => 'pe-uc-cp',
        '2502' => 'pe-uc-at',
        '2503' => 'pe-uc-pa',
        '2504' => 'pe-uc-pr',
    ];

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
                $card1 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, '', 0, 0));
                $card2 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, '25', 0, 0));
                $card3 = number_format(CentroPoblado::count());
                $card4 = number_format(PueblosIndigenas::count());

                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $info = PoblacionDiresa::from('par_poblacion_diresa as pd')->join('par_ubigeo as dd', 'dd.id', '=', 'pd.ubigeo_id')
                    ->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia')->select('pp.nombre', DB::raw('SUM(pd.total) conteo'))->groupBy('pp.nombre')->get();
                return response()->json(compact('info'));
            case 'anal2':
                $data = PoblacionDiresa::from('par_poblacion_diresa as pd')->select('pd.grupo_etareo as rango', 'pd.sexo', DB::raw('SUM(pd.total) conteo'))
                    ->whereNotIn('grupo_etareo', ['28 dias', '0-5 meses', '6-11 meses',  'gestantes', 'nacimientos'])->groupBy('rango', 'sexo')->orderBy('rango')->get();
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
                // $data = PoblacionPN::from('par_poblacdddion_padron_nominal as pn')->join('par_sexo as s', 's.id', '=', 'pn.sexo_id')
                //     ->select('pn.anio', 's.nombre as sexo', DB::raw('sum(0a+1a+2a+3a+4a+5a) as conteo'))->where('anio', '>', 2018)->groupBy('anio', 'sexo')->get();
                $anio = date('Y');
                $data = PoblacionPN::from('par_poblacion_padron_nominal as pn')
                    ->join('par_sexo as s', 's.id', '=', 'pn.sexo_id')
                    ->select('pn.anio', 's.nombre as sexo', DB::raw('sum(0a+1a+2a+3a+4a+5a) as conteo'))
                    ->where(function ($q) use ($anio) {
                        $q->where('pn.anio', '<', $anio)->where('pn.mes_id', '=', 12);
                    })
                    ->orWhere(function ($q) use ($anio) {
                        $q->where('pn.anio', '=', $anio)->where('pn.mes_id', '=', PoblacionPN::where('anio', $anio)->max('mes_id'));
                    })
                    ->groupBy('anio', 'sexo')->get();
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
                    ->where('anio', 2024)->where('mes_id', 5)->groupBy('anio', 'sexo')->get();
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
                        DB::raw('sum(IF(sexo_id=1,0a+1a+2a+3a+4a+5a,0)) as th'),
                        DB::raw('sum(IF(sexo_id=2,0a+1a+2a+3a+4a+5a,0)) as tm'),
                        DB::raw('sum(0a) as c0a'),
                        DB::raw('sum(1a) as c1a'),
                        DB::raw('sum(2a) as c2a'),
                        DB::raw('sum(3a) as c3a'),
                        DB::raw('sum(4a) as c4a'),
                        DB::raw('sum(5a) as c5a'),
                        DB::raw('sum(28dias) as ee1'),
                        DB::raw('sum(0_5meses) as ee2'),
                        DB::raw('sum(6_11meses) as ee3')
                    )
                    ->where('anio', 2024)->where('mes_id', 5)->groupBy('anio', 'distrito')->get();

                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
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
                        $foot->total += $value->total;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
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
                }
                $excel = view('parametro.Poblacion.PrincipalTabla1', compact('base', 'foot'))->render();

                return response()->json(compact('excel', 'foot', 'base'));

            default:
                return [];
        }
    }

    public function poblacionprincipalperu()
    {
        $anios = PoblacionProyectada::distinct()->select('anio')->get();
        $departamento = PoblacionProyectada::distinct()->select('codigo', 'departamento')->where('anio', date('Y'))->get();
        $etapavida = EtapaVida::all();

        return view('parametro.Poblacion.Peru', compact('anios',  'departamento', 'etapavida'));
    }

    public function poblacionprincipalperutabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $card1 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, $rq->departamento, $rq->etapavida, $rq->sexo));
                $card2 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, $rq->departamento, $rq->etapavida, 1));
                $card3 = number_format(PoblacionProyectadaRepositorio::conteo($rq->anio, $rq->departamento, $rq->etapavida, 2));
                $card4 = number_format(PoblacionProyectadaRepositorio::conteo05($rq->anio, $rq->departamento, $rq->etapavida, $rq->sexo));

                // $card4 = number_format(PoblacionPNRepositorio::conteomesmax($rq->anio,  $rq->departamento, '00', 0, 0));
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));

            case 'anal1':
                $data = PoblacionProyectadaRepositorio::conteo_departamento($rq->anio, 0);
                $info = [];
                foreach ($data as $key => $value) {
                    $info[] = [$this->pe_states[$value->codigo], (int)$value->conteo];
                }
                // $info[] = ['pe-145', 0];
                return response()->json(compact('info', 'data'));

            case 'anal2':
                $data = PoblacionProyectadaRepositorio::grupoetareo_sexo($rq->anio, $rq->departamento, $rq->etapavida);
                $info['categoria'] = [];
                $info['men'] = [];
                $info['women'] = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->grupo_etareo == '80 y más' ? '80 - +' : $value->grupo_etareo;
                    $info['men'][] = -(int)$value->hconteo;
                    $info['women'][] = (int)$value->mconteo;
                }
                return response()->json(compact('info', 'data'));

            case 'anal3':
                $data = PoblacionProyectadaRepositorio::conteo_anios($rq->departamento);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['punto'] = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->anio;
                    $info['serie'][] = (int)$value->conteo;
                    $info['punto'][] = [mktime(0, 0, 0, 1, 1, (int)$value->anio) * 1000, (int)$value->conteo];
                    // $info['punto'][] = [(int)$value->anio, (int)$value->conteo];
                }
                return response()->json(compact('info'));

            case 'anal4':
                $data = PoblacionProyectadaRepositorio::conteo05_anios($rq->departamento);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['punto'] = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->anio;
                    $info['serie'][] = (int)$value->conteo;
                    $info['punto'][] = [mktime(0, 0, 0, 1, 1, (int)$value->anio) * 1000, (int)$value->conteo];
                }
                return response()->json(compact('info'));

            case 'tabla1':
                $base = PoblacionProyectadaRepositorio::conteo_departamento_etapavida($rq->anio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->conteo = 0;
                    $foot->hconteo = 0;
                    $foot->mconteo = 0;
                    $foot->ev1 = 0;
                    $foot->ev2 = 0;
                    $foot->ev3 = 0;
                    $foot->ev4 = 0;
                    $foot->ev5 = 0;
                    foreach ($base as $key => $value) {
                        $foot->conteo += $value->conteo;
                        $foot->hconteo += $value->hconteo;
                        $foot->mconteo += $value->mconteo;
                        $foot->ev1 += $value->ev1;
                        $foot->ev2 += $value->ev2;
                        $foot->ev3 += $value->ev3;
                        $foot->ev4 += $value->ev4;
                        $foot->ev5 += $value->ev5;
                    }
                }
                $anio = $rq->anio;
                $excel = view('parametro.Poblacion.PeruTabla1', compact('base', 'foot', 'anio'))->render();
                return response()->json(compact('excel', 'foot', 'base'));

            case 'tabla1x':
                $base = PoblacionProyectadaRepositorio::conteo_anios_tabla1($rq->anio);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->total = 0;
                    $foot->c2024t = 0;
                    $foot->c2024h = 0;
                    $foot->c2024m = 0;
                    $foot->c2021 = 0;
                    $foot->c2022 = 0;
                    $foot->c2023 = 0;
                    $foot->c2024 = 0;
                    $foot->c2025 = 0;
                    $foot->c2026 = 0;
                    $foot->c2027 = 0;
                    $foot->c2028 = 0;
                    $foot->c2029 = 0;
                    $foot->c2030 = 0;
                    foreach ($base as $key => $value) {
                        $foot->total += $value->total;
                        $foot->c2024t += $value->c2024t;
                        $foot->c2024h += $value->c2024h;
                        $foot->c2024m += $value->c2024m;
                        $foot->c2021 += $value->c2021;
                        $foot->c2022 += $value->c2022;
                        $foot->c2023 += $value->c2023;
                        $foot->c2024 += $value->c2024;
                        $foot->c2025 += $value->c2025;
                        $foot->c2026 += $value->c2026;
                        $foot->c2027 += $value->c2027;
                        $foot->c2028 += $value->c2028;
                        $foot->c2029 += $value->c2029;
                        $foot->c2030 += $value->c2030;
                    }
                }
                $anio = $rq->anio;
                $excel = view('parametro.Poblacion.PeruTabla1', compact('base', 'foot', 'anio'))->render();
                return response()->json(compact('excel', 'foot', 'base'));

            default:
                return [];
        }
    }

    public function poblacionprincipalucayali()
    {
        $anios = ImportacionRepositorio::anios_porfuente(ImporPoblacionDiresaController::$FUENTE);
        $aniomax = $anios->max('anio');
        $provincia = PoblacionDiresa::from('par_poblacion_diresa as pd')->distinct()->select('pv.id', 'pv.codigo', 'pv.nombre')
            ->join('par_importacion as im', function ($join) use ($aniomax) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $aniomax);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')->get();
        return view('parametro.Poblacion.PeruUcayali', compact('anios',  'provincia'));
    }

    public function poblacionprincipalucayalitabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $card1 = number_format(PoblacionDiresaRepositorio::conteo_suma($rq->anio, $rq->provincia, $rq->distrito, 0));
                $card2 = number_format(PoblacionDiresaRepositorio::conteo_suma($rq->anio, $rq->provincia, $rq->distrito, 1));
                $card3 = number_format(PoblacionDiresaRepositorio::conteo_suma($rq->anio, $rq->provincia, $rq->distrito, 2));
                $card4 = number_format(PoblacionDiresaRepositorio::conteo05_suma($rq->anio, $rq->provincia, $rq->distrito, 0));

                // $card4 = number_format(PoblacionPNRepositorio::conteomesmax($rq->anio,  $rq->departamento, '00', 0, 0));
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));

            case 'anal1':
                $data = PoblacionDiresaRepositorio::conteo_provincia_suma($rq->anio, $rq->distrito, 0);
                $info = [];
                foreach ($data as $key => $value) {
                    $info[] = [$this->pe_pv[$value->codigo], (int)$value->conteo];
                }
                // $info[] = ['pe-145', 0];
                return response()->json(compact('info', 'data'));

            case 'anal2':
                $data = PoblacionDiresaRepositorio::grupoetareo_sexo($rq->anio, $rq->provincia, $rq->distrito, 0);
                $info['categoria'] = [];
                $info['men'] = [];
                $info['women'] = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->grupo_etareo == '85 y más' ? '85 - +' : $value->grupo_etareo;
                    $info['men'][] = -(int)$value->hconteo;
                    $info['women'][] = (int)$value->mconteo;
                }
                return response()->json(compact('info', 'data'));

            case 'anal3':
                $data = PoblacionDiresaRepositorio::conteo_anios_suma($rq->provincia, $rq->distrito, 0);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'Población';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->anio;
                    $info['serie'][0]['data'][] = (int)$value->conteo;
                }
                return response()->json(compact('info', 'data'));

            case 'anal4':
                $data = PoblacionDiresaRepositorio::etapavida($rq->anio, $rq->provincia, $rq->distrito, 0);
                $info = [];
                $info[0]['name'] = 'Población';
                foreach ($data as $key => $value) {
                    $info[0]['data'][] = ["name" => $value->etapa_vida, "y" => (int)$value->conteo];
                }
                return response()->json(compact('info'));

            case 'anal5':
                $data = PoblacionDiresaRepositorio::conteo_anios_suma($rq->provincia, $rq->distrito, 0);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'][] = 'Hombre';
                $info['serie'][1]['name'][] = 'Mujer';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->anio;
                    $info['serie'][0]['data'][] = (int)$value->hconteo;
                    $info['serie'][1]['data'][] = (int)$value->mconteo;
                }
                return response()->json(compact('info'));

            case 'anal6':
                $data = PoblacionDiresaRepositorio::etapavida($rq->anio, $rq->provincia, $rq->distrito, 0);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'Hombre';
                $info['serie'][1]['name'] = 'Mujer';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->etapa_vida;
                    $info['serie'][0]['data'][] = ["name" => $value->nombre, "y" => (int)$value->hconteo];
                    $info['serie'][1]['data'][] = ["name" => $value->nombre, "y" => (int)$value->mconteo];
                }
                return response()->json(compact('info', 'data'));

            case 'tabla1':
                $base = PoblacionDiresaRepositorio::listar_distrito_sexo_edad($rq->anio, $rq->provincia, $rq->distrito, 0);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->conteo = 0;
                    $foot->hconteo = 0;
                    $foot->mconteo = 0;
                    $foot->ev1 = 0;
                    $foot->ev2 = 0;
                    $foot->ev3 = 0;
                    $foot->ev4 = 0;
                    $foot->ev5 = 0;
                    $foot->nacimiento = 0;
                    $foot->gestante = 0;
                    $foot->fertiles = 0;
                    foreach ($base as $key => $value) {
                        $foot->conteo += $value->conteo;
                        $foot->hconteo += $value->hconteo;
                        $foot->mconteo += $value->mconteo;
                        $foot->ev1 += $value->ev1;
                        $foot->ev2 += $value->ev2;
                        $foot->ev3 += $value->ev3;
                        $foot->ev4 += $value->ev4;
                        $foot->ev5 += $value->ev5;
                        $foot->nacimiento += $value->nacimiento;
                        $foot->gestante += $value->gestante;
                        $foot->fertiles += $value->fertiles;
                    }
                }
                $anio = $rq->anio;
                $excel = view('parametro.Poblacion.PeruUcayaliTabla1', compact('base', 'foot', 'anio'))->render();
                return response()->json(compact('excel', 'foot', 'base'));

            default:
                return [];
        }
    }

    public function poblacionprincipalucayalipn()
    {
        $anios = PoblacionPN::distinct()->select('anio')->get();

        return view('parametro.Poblacion.PeruUcayaliPN', compact('anios',));
    }

    public function poblacionprincipalucayalitablapn(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $card1 = number_format(PoblacionPNRepositorio::conteo2($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0));
                $card2 = number_format(PoblacionPNRepositorio::conteo2($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 1));
                $card3 = number_format(PoblacionPNRepositorio::conteo2($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 2));
                $card4 = number_format(PoblacionPNRepositorio::conteo_cnv($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0, 1));
                // $rr = $rq->all();
                // $card4 = number_format(PoblacionPNRepositorio::conteomesmax($rq->anio,  $rq->departamento, '00', 0, 0));
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));

            case 'anal1':
                $data = PoblacionPNRepositorio::conteo_anios_sexo($rq->mes, $rq->provincia, $rq->distrito);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'][] = 'Hombre';
                $info['serie'][1]['name'][] = 'Mujer';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->anio;
                    $info['serie'][0]['data'][] = (int)$value->hconteo;
                    $info['serie'][1]['data'][] = (int)$value->mconteo;
                }
                return response()->json(compact('info'));

            case 'anal2':
                $data = PoblacionPNRepositorio::conteo_edad_sexo($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info['categoria'] = ['<1 año', '1 año', '2 años', '3 años', '4 años', '5 años'];
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'Hombre';
                $info['serie'][1]['name'] = 'Mujer';
                foreach ($data as $key => $value) {
                    // $info['categoria'][] = '' . $value->sexo;
                    $info['serie'][$key]['data'] = [(int)$value->edad0, (int)$value->edad1, (int)$value->edad2, (int)$value->edad3, (int)$value->edad4, (int)$value->edad5];
                    // $info['serie'][1]['data'][] = ;
                }
                return response()->json(compact('info', 'data'));

            case 'anal3':
                // $data = PoblacionPNRepositorio::conteo_anios_sexo($rq->mes, $rq->provincia, $rq->distrito);
                // $info['categoria'] = [];
                // $info['serie'] = [];
                // foreach ($data as $key => $value) {
                //     $info['categoria'][] = '' . $value->anio;
                //     $info['serie'][] = (int)$value->conteo;
                // }
                // return response()->json(compact('info', 'data'));

                $data = PoblacionPNRepositorio::conteo_anios_sexo($rq->mes, $rq->provincia, $rq->distrito);
                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'][] = 'Hombre';
                // $info['serie'][1]['name'][] = 'Mujer';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->anio;
                    $info['serie'][0]['data'][] = (int)$value->conteo;
                    // $info['serie'][1]['data'][] = (int)$value->mconteo;
                }
                return response()->json(compact('info'));

            case 'anal4':
                $data = PoblacionPNRepositorio::conteo_mes($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info['categoria'] = [];
                $info['serie'] = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->abreviado;
                    $info['serie'][] = $value->conteo != null ? (int)$value->conteo : $value->conteo;
                }
                return response()->json(compact('info', 'data'));

                // $data = PoblacionPNRepositorio::conteo_mes($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                // $info['serie'] = [];
                // $info['serie'][0]['name'] = 'Hombre';
                // foreach ($data as $key => $value) {
                //     $info['categoria'][] = '' . $value->abreviado;
                //     $info['serie'][0]['data'][] = $value->conteo != null ? (int)$value->conteo : $value->conteo;
                //     // $info['serie'][1]['data'][] = ;
                // }
                // return response()->json(compact('info', 'data'));
            case 'tabla1':
                $base = PoblacionPNRepositorio::conteo_seguro_edades($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);

                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->conteo = 0;
                    $foot->hconteo = 0;
                    $foot->mconteo = 0;
                    $foot->edad0 = 0;
                    $foot->edad1 = 0;
                    $foot->edad2 = 0;
                    $foot->edad3 = 0;
                    $foot->edad4 = 0;
                    $foot->edad5 = 0;
                    $foot->edad28 = 0;
                    $foot->edad05 = 0;
                    $foot->edad611 = 0;
                    foreach ($base as $key => $value) {
                        $foot->conteo += $value->conteo;
                        $foot->hconteo += $value->hconteo;
                        $foot->mconteo += $value->mconteo;
                        $foot->edad0 += $value->edad0;
                        $foot->edad1 += $value->edad1;
                        $foot->edad2 += $value->edad2;
                        $foot->edad3 += $value->edad3;
                        $foot->edad4 += $value->edad4;
                        $foot->edad5 += $value->edad5;
                        $foot->edad28 += $value->edad28;
                        $foot->edad05 += $value->edad05;
                        $foot->edad611 += $value->edad611;
                    }
                }
                $anio = $rq->anio;
                $excel = view('parametro.Poblacion.PeruUcayaliPNTabla1', compact('base', 'foot', 'anio'))->render();
                return response()->json(compact('excel', 'foot', 'base'));

            case 'tabla2':
                $base = PoblacionPNRepositorio::conteo_distrito_edades($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);

                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->conteo = 0;
                    $foot->hconteo = 0;
                    $foot->mconteo = 0;
                    $foot->edad0 = 0;
                    $foot->edad1 = 0;
                    $foot->edad2 = 0;
                    $foot->edad3 = 0;
                    $foot->edad4 = 0;
                    $foot->edad5 = 0;
                    $foot->edad28 = 0;
                    $foot->edad05 = 0;
                    $foot->edad611 = 0;
                    foreach ($base as $key => $value) {
                        $foot->conteo += $value->conteo;
                        $foot->hconteo += $value->hconteo;
                        $foot->mconteo += $value->mconteo;
                        $foot->edad0 += $value->edad0;
                        $foot->edad1 += $value->edad1;
                        $foot->edad2 += $value->edad2;
                        $foot->edad3 += $value->edad3;
                        $foot->edad4 += $value->edad4;
                        $foot->edad5 += $value->edad5;
                        $foot->edad28 += $value->edad28;
                        $foot->edad05 += $value->edad05;
                        $foot->edad611 += $value->edad611;
                    }
                }
                $anio = $rq->anio;
                $excel = view('parametro.Poblacion.PeruUcayaliPNTabla2', compact('base', 'foot', 'anio'))->render();
                return response()->json(compact('excel', 'foot', 'base'));

            default:
                return [];
        }
    }

    public function poblacionprincipalucayalitablapnmes(Request $rq)
    {
        $mes = PoblacionPN::from('par_poblacion_padron_nominal as pn')->distinct()->select('m.id', 'm.mes')
            ->join('par_mes as m', 'm.id', '=', 'pn.mes_id')->where('pn.anio', $rq->anio)->get();
        $mesmax = $mes->max('id');
        $mesactual = (int)date('m');
        $selected = $mesmax == $mesactual ? $mesactual : ($mesmax <= $mesactual ? $mesmax : $mesactual);

        return response()->json(compact('mes', 'selected'));
    }

    public function poblacionprincipalucayalitablapnprovincia(Request $rq)
    {
        $provincia = PoblacionPN::from('par_poblacion_padron_nominal as pn')->distinct()->select('pv.id', 'pv.nombre')
            ->join('par_mes as m', 'm.id', '=', 'pn.mes_id')
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->where('pn.anio', $rq->anio);
        if ($rq->mes) $provincia = $provincia->where('pn.mes_id', $rq->mes);
        $provincia = $provincia->get();
        return response()->json(compact('provincia'));
    }

    public function poblacionprincipalucayalitablapndistrito(Request $rq)
    {
        $distrito = PoblacionPN::from('par_poblacion_padron_nominal as pn')->distinct()->select('ds.id', 'ds.nombre')
            ->join('par_mes as m', 'm.id', '=', 'pn.mes_id')
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->where('pn.anio', $rq->anio);
        if ($rq->mes) $distrito = $distrito->where('pn.mes_id', $rq->mes);
        if ($rq->provincia) $distrito = $distrito->where('pv.id', $rq->provincia);
        $distrito = $distrito->get();
        return response()->json(compact('distrito'));
    }
}
