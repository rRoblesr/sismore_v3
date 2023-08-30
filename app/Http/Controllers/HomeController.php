<?php

namespace App\Http\Controllers;

use App\Models\Administracion\Sistema;
use App\Models\Administracion\UsuarioPerfil;
use App\Models\Educacion\Area;
use App\Models\Educacion\CentroPoblado;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\TipoGestion;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Presupuesto\BaseActividadesProyectos;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseIngresos;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\ProductoProyecto;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Ubigeo;
use App\Models\Vivienda\CentroPobladoDatass;
use App\Repositories\Administracion\MenuRepositorio;
use App\Repositories\Administracion\SistemaRepositorio;
use App\Repositories\Administracion\UsuarioPerfilRepositorio;
use App\Repositories\Administracion\UsuarioRepositorio;
use App\Repositories\Educacion\CuadroAsigPersonalRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\InstEducativaRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Educacion\NivelModalidadRepositorio;
use App\Repositories\Educacion\PadronWebRepositorio;
use App\Repositories\Educacion\PlazaRepositorio;
use App\Repositories\Educacion\TabletaRepositorio;
use App\Repositories\Educacion\UgelRepositorio;
use App\Repositories\Presupuesto\BaseActividadesProyectosRepositorio;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseIngresosRepositorio;
use App\Repositories\Presupuesto\BaseProyectosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Vivienda\CentroPobladoDatassRepositorio;
use App\Repositories\Vivienda\CentroPobladoRepositotio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Vivienda\DatassRepositorio;
use App\Utilities\Utilitario;

class HomeController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sistemas = SistemaRepositorio::Listar_porUsuario(auth()->user()->id);

        $usuper = UsuarioPerfilRepositorio::get_porusuariosistema(auth()->user()->id, '4');

        session()->put(['usuario_id' => auth()->user()->id]);
        session()->put(['total_sistema' => $sistemas->count()]);
        session()->put(['perfil_id' => $usuper ? $usuper->perfil_id : 0]);
        session()->put(['sistemas' => $sistemas]);

        $usuario = UsuarioRepositorio::Usuario(auth()->user()->id);

        if ($usuario->first() != null) {
            session(['dnisismore$' => $usuario->first()->dni]);
            session(['passwordsismore$' => $usuario->first()->password]);
        }

        // return session('dnisismore$');
        //if ($sistemas->count() == 1) {
        return $this->sistema_acceder($sistemas->first()->sistema_id);
        //}
        // return session('usuario_id');
        //return view('Access', compact(('sistemas')));
    }

    public function sistema_acceder($sistema_id)
    {
        if (!session()->has('perfil_id')) {
            $sistemas = SistemaRepositorio::Listar_porUsuario(auth()->user()->id);
            session()->put(['sistemas' => $sistemas]);
        }
        // session()->forget('sistema_id');
        // session()->forget('sistema_nombre');
        // session()->forget('menuNivel01');
        // session()->forget('menuNivel02');

        session(['sistema_id' => $sistema_id]);

        $sistema = Sistema::find($sistema_id);
        session(['sistema_nombre' => $sistema->nombre]);

        $menuNivel01 = MenuRepositorio::Listar_Nivel01_porUsuario_Sistema(auth()->user()->id, $sistema_id);
        session(['menuNivel01' => $menuNivel01]);

        $menuNivel02 = MenuRepositorio::Listar_Nivel02_porUsuario_Sistema(auth()->user()->id, $sistema_id);
        session(['menuNivel02' => $menuNivel02]);


        $nimp = ImportacionRepositorio::noti_importaciones($sistema_id, date('Y'));
        session(['nimp' => $nimp]);
        $ncon = $nimp->count();
        session(['ncon' => $ncon]);
        /* $nfue = ImportacionRepositorio::noti_fuentes($sistema_id, date('Y'));
        session(['nfue' => $nfue]);
        $ncon = 0;
        foreach ($nfue as $key => $value) {
            $ncon += $value->conteo;
        }
        session(['ncon' => $ncon]); */

        switch ($sistema_id) {
            case (1):
                return $this->educacion($sistema_id);
                break;
            case (2):
                return $this->vivienda($sistema_id);
                break;
            case (3):
                return $this->salud($sistema_id);
                break;
            case (4):
                return $this->administracion($sistema_id);
                break;
            case (5):
                return $this->presupuesto($sistema_id);
                break;
            case (6):
                return $this->trabajo($sistema_id);
                break;
            default:
                return 'Ruta de sistema no establecida';
                break;
        }
    }

    public function accesopublico()
    {
        $sistemas = Sistema::where('id', '!=', 4)->get();
        session(['sistemas' => $sistemas]);
        session(['sistema_id' => 1]);
        return $this->educacion_publico(1);
    }

    public function accesopublicomodulo($sistema_id)
    { //session()->put(['total_sistema' => $sistemas->count()]);
        session()->put(['sistema_id' => $sistema_id]);
        switch ($sistema_id) {
            case (1):
                return $this->educacion_publico($sistema_id);
                break;
            case (2):
                return $this->vivienda_publico($sistema_id);
                break;
            case (3):
                return $this->salud_publico($sistema_id);
                break;
                //case (4):return $this->administracion($sistema_id);break;
            case (5):
                return $this->presupuesto_publico($sistema_id);
                break;
            case (6):
                return $this->trabajo_publico($sistema_id);
                break;
            default:
                return 'Ruta de sistema no establecida';
                break;
        }
    }

    public function administracion($sistema_id)
    {
        $sistemas = SistemaRepositorio::listar_sistemasconusuarios(1);
        //return $sistemas;
        return view('home', compact('sistema_id', 'sistemas'));
    }

    public function trabajo($sistema_id)
    {
        return view('home', compact('sistema_id'));
    }

    public function trabajo_publico($sistema_id)
    {
        return view('homepublico', compact('sistema_id'));
    }

    public function presupuesto($sistema_id)
    {
        //$actualizado = '';
        $impSW = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseSW = BaseSiafWeb::where('importacion_id', $impSW->id)->first();
        $anio = $baseSW->anio;
        $opt1 = BaseSiafWebRepositorio::pia_pim_certificado_devengado($baseSW->id, 0);

        $card1['pim'] = $opt1->pia;
        $card1['eje'] = $opt1->eje_pia;
        $card2['pim'] = $opt1->pim;
        $card2['eje'] = $opt1->eje_pim;
        $card3['pim'] = $opt1->cer;
        $card3['eje'] = $opt1->eje_cer;
        $card4['pim'] = $opt1->dev;
        $card4['eje'] = $opt1->eje_dev;

        $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
        $opt2 = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);

        $impG = $impSW;

        $anios = BaseSiafWebRepositorio::anios();

        $strSiafWeb = strtotime($impSW->fechaActualizacion);
        $actualizado = 'Actualizado al ' . date('d', $strSiafWeb) . ' de ' . $this->mes[date('m', $strSiafWeb) - 1] . ' del ' . date('Y', $strSiafWeb);

        $titulo = 'Ejecución Presupuestal del Gobierno Regional de Ucayali';

        return view('home', compact('sistema_id', 'card1', 'card2', 'card3', 'card4',  'anio', 'baseAP', 'impG', 'anios', 'actualizado', 'titulo'));
    }

    public function presupuestocuadros(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $impSW = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
        $baseSW = BaseSiafWeb::where('importacion_id', $impSW->id)->first();
        $opt1 = BaseSiafWebRepositorio::pia_pim_certificado_devengado($baseSW->id, $articulo);

        $card1['pim'] = $opt1->pia;
        $card1['eje'] = $opt1->eje_pia;
        $card2['pim'] = $opt1->pim;
        $card2['eje'] = $opt1->eje_pim;
        $card3['pim'] = $opt1->cer;
        $card3['eje'] = $opt1->eje_cer;
        $card4['pim'] = $opt1->dev;
        $card4['eje'] = $opt1->eje_dev;

        return response()->json(compact('card1', 'card2', 'card3', 'card4'));
    }

    public function presupuesto_publico($sistema_id)
    {
        $impSW = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseSW = BaseSiafWeb::where('importacion_id', $impSW->id)->first();
        $anio = $baseSW->anio;
        $opt1 = BaseSiafWebRepositorio::pia_pim_certificado_devengado($baseSW->id, 0);

        $card1['pim'] = $opt1->pia;
        $card1['eje'] = $opt1->eje_pia;
        $card2['pim'] = $opt1->pim;
        $card2['eje'] = $opt1->eje_pim;
        $card3['pim'] = $opt1->cer;
        $card3['eje'] = $opt1->eje_cer;
        $card4['pim'] = $opt1->dev;
        $card4['eje'] = $opt1->eje_dev;

        $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
        $opt2 = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);

        $impG = $impSW;

        $strSiafWeb = strtotime($impSW->fechaActualizacion);
        $actualizado = 'Actualizado al ' . date('d', $strSiafWeb) . ' de ' . $this->mes[date('m', $strSiafWeb) - 1] . ' del ' . date('Y', $strSiafWeb);

        $anios = BaseSiafWebRepositorio::anios();

        $titulo = 'Ejecución Presupuestal del Gobierno Regional de Ucayali';

        return view('homepublico', compact('sistema_id', 'card1', 'card2', 'card3', 'card4',  'anio', 'baseAP', 'impG', 'anios', 'actualizado', 'titulo'));
    }

    public function presupuestografica1($importacion_id)
    {
        $info = BaseGastosRepositorio::pim_tipogobierno2($importacion_id);
        return response()->json(compact('info'));
    }

    public function presupuestografica2(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $datax = [
            465 => 'pe-145',
            440 => 'pe-am',
            441 => 'pe-an',
            442 => 'pe-ap',
            443 => 'pe-ar',
            444 => 'pe-ay',
            445 => 'pe-cj',
            464 => 'pe-3341',
            446 => 'pe-cs',
            447 => 'pe-hv',
            448 => 'pe-hc',
            449 => 'pe-ic',
            450 => 'pe-ju',
            451 => 'pe-ll',
            452 => 'pe-lb',
            463 => 'pe-lr',
            453 => 'pe-lo',
            454 => 'pe-md',
            455 => 'pe-mq',
            456 => 'pe-pa',
            457 => 'pe-pi',
            458 => 'pe-cl',
            459 => 'pe-sm',
            460 => 'pe-ta',
            461 => 'pe-tu',
            462 => 'pe-uc',
        ];
        $data = [];
        if ($articulo == 0) {
            $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
            $info = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);
        } else if ($articulo == 1) {
            $impP = Importacion::where('fuenteimportacion_id', '25')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $baseP = BaseProyectos::where('importacion_id', $impP->id)->first();
            $info = BaseProyectosRepositorio::listar_regiones($baseP->id);
        } else { //2
            $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();

            $impP = Importacion::where('fuenteimportacion_id', '25')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $baseP = BaseProyectos::where('importacion_id', $impP->id)->first();

            $info = BaseActividadesProyectosRepositorio::listar_regiones_actividad($baseAP->id, $baseP->id);
        }

        foreach ($info as $key => $value1) {
            $hc_key = $datax[$value1->codigo];
            $data[] = [$hc_key, $key + 1];
            $value1->color = $value1->codigo == 462 ? '#ef5350' : '#317eeb';
            $value1->y = round($value1->y, 2);
        }
        return response()->json(compact('info', 'data'));
    }

    public function presupuestografica2_($importacion_id)
    {
        $info = BaseGastosRepositorio::inversiones_pim_tipogobierno($importacion_id);
        return response()->json(compact('info'));
    }

    public function presupuestografica3(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        if ($articulo == 0) {
            $array = BaseActividadesProyectosRepositorio::baseids_fecha_max($anio);
            $base = BaseActividadesProyectosRepositorio::listado_ejecucion($array);
        } else if ($articulo == 1) {
            $array = BaseProyectosRepositorio::baseids_fecha_max($anio);
            $base = BaseProyectosRepositorio::listado_ejecucion($array);
        } else { //2
            $array1 = BaseActividadesProyectosRepositorio::baseids_fecha_max($anio);
            $array2 = BaseProyectosRepositorio::baseids_fecha_max($anio);
            $base = BaseActividadesProyectosRepositorio::listado_ejecucion_actividad($array1, $array2);
        }

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        for ($i = 1; $i < 13; $i++) {
            $puesto = 1;
            foreach ($base as $key => $value) {
                if ($value->mes == $i) {
                    if ($value->dep == 25) {
                        $info['series'][$value->mes - 1] = $puesto;
                    }
                    $puesto++;
                }
            }
        }

        return response()->json(compact('info'));
    }

    public function presupuestografica4(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($anio);
        if ($articulo == 0) {
            $base = BaseSiafWebRepositorio::suma_pim($array, 0);
        } else if ($articulo == 1) {
            $base = BaseSiafWebRepositorio::suma_pim($array, 1);
        } else { //2
            $base = BaseSiafWebRepositorio::suma_pim($array, 2);
        }

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $info['series'][$value->name - 1] = $value->y;
        }
        return response()->json(compact('info'));
    }

    public function presupuestografica5(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($anio);
        if ($articulo == 0) {
            $base = BaseSiafWebRepositorio::suma_certificado($array, 0);
        } else if ($articulo == 1) {
            $base = BaseSiafWebRepositorio::suma_certificado($array, 1);
        } else { //2
            $base = BaseSiafWebRepositorio::suma_certificado($array, 2);
        }

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        $monto = 0;
        foreach ($base as $key => $value) {
            $value->y -= $monto;
            $info['series'][$value->name - 1] = $value->y;
            $monto = $value->y + $monto;
            $value->color = ($value->y < 0 ? '#ef5350' : '#317eeb');
        }
        return response()->json(compact('info'));
    }

    public function presupuestografica6(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        if ($articulo == 0) {
            $base = BaseSiafWebRepositorio::suma_devengado($array, 0);
        } else if ($articulo == 1) {
            $base = BaseSiafWebRepositorio::suma_devengado($array, 1);
        } else { //2
            $base = BaseSiafWebRepositorio::suma_devengado($array, 2);
        }

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        $monto = 0;
        foreach ($base as $key => $value) {
            $value->y -= $monto;
            $info['series'][$value->name - 1] = $value->y;
            $monto = $value->y + $monto;
            $value->color = ($value->y < 0 ? '#ef5350' : '#317eeb');
        }
        return response()->json(compact('info'));
    }

    public function presupuestografica7(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        if ($articulo == 0) {
            $query = BaseSiafWebRepositorio::suma_xxxx($array, 0);
        } else if ($articulo == 1) {
            $query = BaseSiafWebRepositorio::suma_xxxx($array, 1);
        } else { //2
            $query = BaseSiafWebRepositorio::suma_xxxx($array, 2);
        }
        $info['series'] = [];
        //$dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx4 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx5 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            //$dx1[$key] = $value->y1; //pia
            $dx2[$key] = $value->y2; //pim
            $dx3[$key] = $value->y3; //devengado
            $dx4[$key] = $value->y4; //devengado
            $dx5[$key] = $value->y5; //devengado
        }
        //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#7C7D7D', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'CERTIFICADO', 'color' => '#317eeb', 'data' => $dx2];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx3];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%AVANCE CERT', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#317eeb', 'data' => $dx4];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx5];
        return response()->json(compact('info'));
    }

    public function presupuestografica4_($importacion_id)
    {
        $base = BaseGastosRepositorio::pim_anios_tipogobierno();
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
    }

    public function presupuestografica5_($importacion_id)
    {
        $base = BaseGastosRepositorio::inversion_pim_anios_tipogobierno();
        $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($base as $key => $ba) {
            if ($ba->tipo == 'GOBIERNO NACIONAL') {
                $data['categoria'][] = $ba->ano;
                $dx1[] = $ba->pim1;
            }
            if ($ba->tipo == 'GOBIERNOS REGIONALES')
                $dx2[] = $ba->pim2;
            if ($ba->tipo == 'GOBIERNOS LOCALES')
                $dx3[] = $ba->pim3;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
        $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
        $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('data'));
    }

    public function presupuestografica6_($importacion_id)
    {
        $base = BaseIngresosRepositorio::pim_anios_tipogobierno($importacion_id);
        $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($base as $key => $ba) {
            if ($ba->tipo == 'GOBIERNO NACIONAL') {
                $data['categoria'][] = $ba->ano;
                $dx1[] = $ba->pim1;
            }
            if ($ba->tipo == 'GOBIERNOS REGIONALES')
                $dx2[] = $ba->pim2;
            if ($ba->tipo == 'GOBIERNOS LOCALES')
                $dx3[] = $ba->pim3;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
        $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
        $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('data'));
    }

    public function presupuestografica7_()
    {
        $base = BaseGastosRepositorio::activades_pim_anios_tipogobierno();
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
    }

    public function presupuestotabla1($importacion_id)
    {
        $info = BaseGastosRepositorio::pim_pia_devengado_tipogobierno($importacion_id);
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
    }

    public function presupuestotabla2($importacion_id)
    {
        $info = BaseGastosRepositorio::inversion_pim_pia_devengado_tipogobierno($importacion_id);
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
    }

    public function presupuestotabla3($importacion_id)
    {
        $info = BaseIngresosRepositorio::pim_pia_devengado_tipogobierno($importacion_id);
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
        $data['series'][] = ['name' => 'RECAUDACIÓN', 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('data'));
    }

    public function presupuestotabla()
    {
        $body = BaseGastosRepositorio::pim_ejecutado_noejecutado_tipogobierno();
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
        return view("presupuesto.inicioPresupuestohometabla1", compact('body', 'foot'));
    }

    public function vivienda2($sistema_id)
    {
        $datos = DatassRepositorio::datos_PorDepartamento(516, 1);

        $suma1 = 0;
        $suma2 = 0;
        $puntos = [];

        //->sortByDesc('hombres') solo para dar una variacion a los colores del grafico
        foreach ($datos as $key => $item) {
            $suma1 += $item->INDICADOR_SI_porcentaje;;
            $suma2 += $item->INDICADOR_NO_porcentaje;;
        }

        $puntos[] = ['name' => 'SI', 'y' => floatval($suma1)];
        $puntos[] = ['name' => 'NO', 'y' => floatval($suma2)];

        $contenedor = 'Grafico_IndicadorRegional'; //nombre del contenedor para el grafico
        $titulo_grafico = 'Grafico_IndicadorRegional';


        return view(
            'home',
            ["dataCircular" => json_encode($puntos)],
            compact('contenedor', 'titulo_grafico')
        );
    }

    public function vivienda($sistema_id)
    {

        $vUrl = "https://datastudio.google.com/embed/reporting/6c73c567-559b-4dd6-8608-64a0b502c85c/page/XXx8C";
        // $imp = Importacion::select(DB::raw('max(id) as maximo'))->where('fuenteimportacion_id', '7')->where('estado', 'PR')->first();
        $importacion = ImportacionRepositorio::ImportacionMax_porfuente('7');

        $importacion_id = $importacion->id;

        $fechaVersion = Utilitario::fecha_formato_texto_completo($importacion->fechaActualizacion);
        return view('home', compact('sistema_id', 'importacion_id', 'vUrl', 'fechaVersion'));
    }

    public function vivienda_publico($sistema_id)
    {

        $vUrl = "https://datastudio.google.com/embed/reporting/6c73c567-559b-4dd6-8608-64a0b502c85c/page/XXx8C";
        // $imp = Importacion::select(DB::raw('max(id) as maximo'))->where('fuenteimportacion_id', '7')->where('estado', 'PR')->first();
        $importacion = ImportacionRepositorio::ImportacionMax_porfuente('7');

        $importacion_id = $importacion->id;

        $fechaVersion = Utilitario::fecha_formato_texto_completo($importacion->fechaActualizacion);
        return view('homepublico', compact('sistema_id', 'importacion_id', 'vUrl', 'fechaVersion'));
    }

    // public function vivienda3($sistema_id)
    // {

    //     $datos = DatassRepositorio::datos_PorProvincia(516,1);

    //     $categoria1 = [];
    //     $categoria2 = [];
    //     $categoria_nombres = [];

    //     // array_merge concatena los valores del arreglo, mientras recorre el foreach
    //     foreach ($datos as $key => $lista) {
    //         $categoria1 = array_merge($categoria1, [intval($lista->INDICADOR_SI_porcentaje)]);
    //         $categoria2 = array_merge($categoria2, [intval($lista->INDICADOR_NO_porcentaje)]);
    //         $categoria_nombres[] = $lista->Provincia;
    //     }

    //     $name_Y1 = "Chrome";
    //     $name_Y2 = "Safari";
    //     $titulo_grafico = 'Grafico_IndicadorRegional';



    //     $puntos[] = ['name' => $name_Y1, 'y' => floatval(61),'drilldown' => 'Chrome'];
    //     $puntos[] = ['name' => $name_Y2, 'y' => floatval(33),'drilldown' => 'Safari'];


    //     $puntosHijos[] = ['name' => 'hijo 1', 'y' => floatval(88) ];
    //     $puntosHijos[] = ['name' => 'hijo 2', 'y' => floatval(66) ];



    //     $imp = Importacion::select(DB::raw('max(id) as maximo'))->where('fuenteimportacion_id', '7')->where('estado', 'PR')->first();
    //     $importacion_id = ImportacionRepositorio::Max_porfuente('7');
    //     if ($importacion_id) {
    //         $data[] = ['name' => 'Centros Poblados - CP', 'y' => CentroPobladoDatassRepositorio::listar_centroPoblado($importacion_id)->conteo];
    //         $data[] = ['name' => 'CP. Con Sistema de agua', 'y' => CentroPobladoRepositotio::ListarSINO_porIndicador(0, 0, 20, $importacion_id)['indicador'][0]->y];
    //         $data[] = ['name' => 'CP. Con Disposicion Escretas', 'y' => CentroPobladoRepositotio::ListarSINO_porIndicador(0, 0, 23, $importacion_id)['indicador2'][0]->y];
    //         $data[] = ['name' => 'CP. Con Sistema Cloración', 'y' => CentroPobladoRepositotio::ListarSINO_porIndicador(0, 0, 21, $importacion_id)['indicador2'][0]->y];

    //         $sumas = CentroPobladoDatassRepositorio::sumas_dashboard($importacion_id);
    //         $data2[] = ['name' => 'Población Ambito Rural', 'y' => $sumas->poblacion];/* total_poblacion */
    //         $data2[] = ['name' => 'Con Cobertura de Agua', 'y' => $sumas->con_agua];/* poblacion_con_servicio_agua */
    //         $data2[] = ['name' => 'Viviendas', 'y' => $sumas->con_conexion];/* total_viviendas */
    //         $data2[] = ['name' => 'Con Servicio de Agua', 'y' => $sumas->con_conexion];/* viviendas_con_conexion */

    //         $grafica[] = CentroPobladoRepositotio::listarporprovincias($importacion_id);/* total de centro poblado por provincia */
    //         $grafica[] = CentroPobladoRepositotio::listarporprovinciasconsistemaagua($importacion_id);/* total de centro poblado con servicio de agua(sistema_agua) */

    //         $grafica2[] = CentroPobladoRepositotio::ListarSINO_porIndicador(0, 0, 20, $importacion_id)['indicador'];
    //         $grafica2[] = CentroPobladoRepositotio::ListarSINO_porIndicador(0, 0, 23, $importacion_id)['indicador2'];

    //         return view('home',
    //         ["dataGrafico" => json_encode($puntos),"dataGraficoHijo" => json_encode($puntosHijos)],
    //          compact('sistema_id', 'importacion_id', 'data', 'data2', 'grafica', 'grafica2'));
    //     } else {
    //         return view('home', compact('sistema_id', 'importacion_id'));
    //     }
    // }


    public function educacion($sistema_id)
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imppadweb = ImportacionRepositorio::Max_yearPadronWeb(); //padron web $imp
        $impsiagie = ImportacionRepositorio::Max_yearSiagieMatricula(); //siagie $imp2
        $impidnexus = ImportacionRepositorio::Max_porfuente(2); //nexus $imp3

        $impidpadweb = $imppadweb->first()->id;
        $impidsiagie = $impsiagie->first()->mat;

        $strSiagie = strtotime($impsiagie->first()->fecha);
        $actualizado = 'Actualizado al ' . date('d', $strSiagie) . ' de ' . $this->mes[date('m', $strSiagie) - 1] . ' del ' . date('Y', $strSiagie);

        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        //return $flt['ges'] = TipoGestion::select('id',DB::raw('case when codigo="3" then "PRIVADA" ELSE "PUBLICA" end as nombre'))->whereNull('dependencia')->get();
        $ambitos = Area::all();

        return  view('home', compact(
            'impidpadweb',
            'impidsiagie',
            'impidnexus',
            'actualizado',
            'tipo_acceso',
            'provincias',
            'distritos',
            'ambitos',
        ));
    }

    public function educacion2($sistema_id)
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::Max_yearPadronWeb(); //padron web
        $imp2 = ImportacionRepositorio::Max_yearSiagieMatricula(); //siagie
        $imp3 = ImportacionRepositorio::Max_porfuente(2); //nexus

        if ($imp->count() > 0 && $imp2->count() > 0 && $imp3 != null) {
            $importacion_id = $imp->first()->id;
            $matricula_id = $imp2->first()->mat;

            $info['se'] = PadronWebRepositorio::count_institucioneducativa($imp->first()->id);
            $info['le'] = PadronWebRepositorio::count_localesescolares($imp->first()->id);
            $info['tm'] = MatriculaDetalleRepositorio::count_matriculados($imp2->first()->mat);
            $info['do'] = PlazaRepositorio::count_docente($imp3);

            $info['dt0'] = MatriculaDetalleRepositorio::listar_estudiantesMatriculadosDeEducacionBasicaPorUgel($imp2);
            $info['dt1'] = PadronWebRepositorio::listar_totalServicosLocalesSecciones($imp);

            //$strPadronWeb = strtotime($imp->fecha);
            $strSiagie = strtotime($imp2->first()->fecha);
            //$strNexus = strtotime($imp3->fecha);$actualizado = '';
            $actualizado = 'Actualizado al ' . date('d', $strSiagie) . ' de ' . $this->mes[date('m', $strSiagie) - 1] . ' del ' . date('Y', $strSiagie);

            $flt['pro'] = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
            $flt['dis'] = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
            //return $flt['ges'] = TipoGestion::select('id',DB::raw('case when codigo="3" then "PRIVADA" ELSE "PUBLICA" end as nombre'))->whereNull('dependencia')->get();
            $flt['amb'] = Area::all();

            return  view('home', compact('importacion_id', 'info', 'imp', 'matricula_id', 'actualizado', 'tipo_acceso', 'flt'));
        } else {
            $importacion_id = null;
            $importables['padron_web'] = $imp->count() == 0;
            $importables['siagie_matricula'] = $imp2->count() == 0;
            $importables['nexus_minedu'] = $imp3 == null;
            return  view('home', compact('importacion_id', 'importables', 'actualizado'));
        }
    }

    public function educacion_publico($sistema_id)
    {
        $actualizado = '';
        $tipo_acceso = 1;
        $imp = ImportacionRepositorio::Max_yearPadronWeb(); //padron web
        $imp2 = ImportacionRepositorio::Max_yearSiagieMatricula(); //siagie
        $imp3 = ImportacionRepositorio::Max_porfuente(2); //nexus

        if ($imp->count() > 0 && $imp2->count() > 0 && $imp3 != null) {
            $importacion_id = $imp->first()->id;
            $matricula_id = $imp2->first()->mat;

            $info['se'] = PadronWebRepositorio::count_institucioneducativa($imp->first()->id);
            $info['le'] = PadronWebRepositorio::count_localesescolares($imp->first()->id);
            $info['tm'] = MatriculaDetalleRepositorio::count_matriculados($imp2->first()->mat);
            $info['do'] = PlazaRepositorio::count_docente($imp3);

            $info['dt0'] = MatriculaDetalleRepositorio::listar_estudiantesMatriculadosDeEducacionBasicaPorUgel($imp2);
            $info['dt1'] = PadronWebRepositorio::listar_totalServicosLocalesSecciones($imp);
            //$strPadronWeb = strtotime($imp->fecha);
            $strSiagie = strtotime($imp2->first()->fecha);
            //$strNexus = strtotime($imp3->fecha);
            $actualizado = 'Actualizado al ' . date('d', $strSiagie) . ' de ' . $this->mes[date('m', $strSiagie) - 1] . ' del ' . date('Y', $strSiagie);
            return  view('homepublico', compact('importacion_id', 'info', 'imp', 'matricula_id', 'actualizado'));
        } else {
            $importacion_id = null;
            $importables['padron_web'] = $imp->count() == 0;
            $importables['siagie_matricula'] = $imp2->count() == 0;
            $importables['nexus_minedu'] = $imp3 == null;
            return  view('homepublico', compact('importacion_id', 'importables', 'actualizado'));
        }
    }

    public function educaciongrafica1()
    {
        $siagie[] = ['name' => 2018, 'y' => 189154];
        $siagie[] = ['name' => 2019, 'y' => 197907];
        $siagie[] = ['name' => 2020, 'y' => 203980];
        $siagie[] = ['name' => 2021, 'y' => 15450];
        $siagie[] = ['name' => 2022, 'y' => 18066];
        $siagie[] = ['name' => 2023, 'y' => 0];
        $info1 = MatriculaDetalleRepositorio::estudiantes_matriculadosEBR_EBE_anual();
        foreach ($info1 as $value) {
            foreach ($siagie as $pos => $value1) {
                if ($value1['name'] == $value->name) {
                    $siagie[$pos]['y'] += $value->y;
                }
            }
        }
        $info = $siagie;
        return response()->json(compact('info', 'info1'));
    }

    public function educaciongrafica2()
    {
        $nexus[] = ['name' => 2018, 'y' => 9780];
        $nexus[] = ['name' => 2019, 'y' => 10161];
        $nexus[] = ['name' => 2020, 'y' => 10433];
        $nexus[] = ['name' => 2021, 'y' => 826];
        $nexus[] = ['name' => 2022, 'y' => 920];
        $nexus[] = ['name' => 2023, 'y' => 0];
        $info1 = PlazaRepositorio::docentes_conteo_anual();
        foreach ($info1 as $value) {
            foreach ($nexus as $pos => $value1) {
                if ($value1['name'] == $value->name) {
                    $nexus[$pos]['y'] += $value->y;
                }
            }
        }
        $info = $nexus;
        return response()->json(compact('info'));
    }

    public function educaciongrafica3()
    {
        $info = MatriculaDetalleRepositorio::estudiantes_matriculados_segungenero();
        return response()->json(compact('info'));
    }

    public function educaciongrafica4()
    {
        $info = PlazaRepositorio::docentes_segungenero_anual();
        return response()->json(compact('info'));
    }

    public function educaciongrafica5()
    {
        $info = MatriculaDetalleRepositorio::estudiantes_matriculados_segunareageografica();
        return response()->json(compact('info'));
    }

    public function educaciongrafica6()
    {
        $info =  PlazaRepositorio::docentes_segunareageograficas();
        return response()->json(compact('info'));
    }

    public function educacionx($sistema_id)
    {
        $instituciones_activas = 0;
        $instituciones_inactivas = 0;
        $instituciones_total = 0;

        $titulados_inicial = 0;
        $titulados_primaria = 0;
        $titulados_secundaria = 0;
        $titulados_sum = 0;
        $noTitulados = 0;
        $porcentajeTitulados = 0;
        $porcentajeInstituciones_activas = 0;
        $localesEducativos = 0;
        $locales_tieneInternet = 0;
        $porcentajeLocales_tieneInternet = 0;

        $data = DB::select('call edu_pa_dashboard()');

        foreach ($data as $key => $item) {
            $instituciones_activas  = $item->instituciones_activas;
            $instituciones_inactivas  = $item->instituciones_inactivas;

            $instituciones_total = $instituciones_activas + $instituciones_inactivas;

            $titulados_inicial = $item->titulados_inicial;
            $titulados_primaria = $item->titulados_primaria;
            $titulados_secundaria = $item->titulados_secundaria;
            $titulados_sum = $titulados_inicial + $titulados_primaria + $titulados_secundaria;
            $noTitulados = $item->noTitulados;

            $porcentajeTitulados = round(($titulados_sum * 100 / ($titulados_sum + $noTitulados)), 2);

            $porcentajeInstituciones_activas = round(($instituciones_activas * 100 / ($instituciones_activas + $instituciones_inactivas)), 2);

            $locales_tieneInternet = $item->locales_tieneInternet;
            $localesEducativos = $item->locales_tieneInternet + $item->locales_no_tieneInternet;
            $porcentajeLocales_tieneInternet = round(($locales_tieneInternet * 100 / $localesEducativos), 2);
        }

        $par_medidor1_max =  $instituciones_total;

        $tabletas_ultimaActualizacion = TabletaRepositorio::tabletas_ultimaActualizacion()->first();

        $fechaTableta = $tabletas_ultimaActualizacion->fechaActualizacion;

        //{{number_format($sum_cero_nivel_hombre,0)}}

        $par_medidor1_max = 100;
        $par_medidor1_data =  number_format((($tabletas_ultimaActualizacion->total_Recepcionadas * 100) / $tabletas_ultimaActualizacion->total_aDistribuir), 2);

        $par_medidor2_max = 100;
        $par_medidor2_data =  number_format((($tabletas_ultimaActualizacion->total_Asignadas * 100) / $tabletas_ultimaActualizacion->total_aDistribuir), 2);


        return view('home', compact(
            'par_medidor1_max',
            'par_medidor1_data',
            'par_medidor2_max',
            'par_medidor2_data',
            'sistema_id',
            'instituciones_activas',
            'titulados_inicial',
            'titulados_primaria',
            'titulados_secundaria',
            'titulados_sum',
            'porcentajeTitulados',
            'porcentajeInstituciones_activas',
            'localesEducativos',
            'locales_tieneInternet',
            'porcentajeLocales_tieneInternet'
        ));
    }

    public function salud($sistema_id)
    {
        $impSW = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseSW = BaseSiafWeb::where('importacion_id', $impSW->id)->first();
        $anio = $baseSW->anio;
        $opt1 = BaseSiafWebRepositorio::pia_pim_certificado_devengado($baseSW->id, 0);
        //return $opt1;
        $card1['pim'] = $opt1->pia;
        $card1['eje'] = $opt1->eje_pia;
        $card2['pim'] = $opt1->pim;
        $card2['eje'] = $opt1->eje_pim;
        $card3['pim'] = $opt1->cer;
        $card3['eje'] = $opt1->eje_cer;
        $card4['pim'] = $opt1->dev;
        $card4['eje'] = $opt1->eje_dev;

        $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
        $opt2 = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);
        //return $opt2;

        return view('home', compact('sistema_id', 'card1', 'card2', 'card3', 'card4', 'impSW', 'anio', 'baseAP'));
    }

    public function salud_publico($sistema_id)
    {
        $impSW = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseSW = BaseSiafWeb::where('importacion_id', $impSW->id)->first();
        $anio = $baseSW->anio;
        $opt1 = BaseSiafWebRepositorio::pia_pim_certificado_devengado($baseSW->id, 0);
        //return $opt1;
        $card1['pim'] = $opt1->pia;
        $card1['eje'] = $opt1->eje_pia;
        $card2['pim'] = $opt1->pim;
        $card2['eje'] = $opt1->eje_pim;
        $card3['pim'] = $opt1->cer;
        $card3['eje'] = $opt1->eje_cer;
        $card4['pim'] = $opt1->dev;
        $card4['eje'] = $opt1->eje_dev;

        $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
        $opt2 = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);
        //return $opt2;

        return view('homepublico', compact('sistema_id', 'card1', 'card2', 'card3', 'card4', 'impSW', 'anio', 'baseAP'));
    }


    public function AEI_tempo()
    {
        $data = DB::select('call edu_pa_indicadorAEI()');

        $titulados_inicial = 0;
        $total_inicial = 0;
        $porcentajeTitulados_inicial = 0;

        $bilingues = 0;

        foreach ($data as $key => $item) {
            $titulados_inicial  = $item->titulados_inicial;
            $total_inicial = $item->total_inicial;

            $porcentajeTitulados_inicial =  round($titulados_inicial * 100 / ($total_inicial), 2);

            $bilingues = $item->bilingues;
        }

        return view('homeAEI', compact('titulados_inicial', 'porcentajeTitulados_inicial', 'bilingues'));
    }
}
