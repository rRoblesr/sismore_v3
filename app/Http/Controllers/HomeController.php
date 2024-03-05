<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Educacion\ImporCensoDocenteController;
use App\Http\Controllers\Educacion\ImporCensoMatriculaController;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Http\Controllers\Educacion\ImporServiciosBasicosController;
use App\Http\Controllers\Presupuesto\ImporActividadesProyectosController;
use App\Http\Controllers\Presupuesto\ImporProyectosController;
use App\Http\Controllers\Presupuesto\ImporSiafWebController;
use App\Models\Administracion\Sistema;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Presupuesto\BaseActividadesProyectos;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Administracion\MenuRepositorio;
use App\Repositories\Administracion\SistemaRepositorio;
use App\Repositories\Administracion\UsuarioPerfilRepositorio;
use App\Repositories\Administracion\UsuarioRepositorio;
use App\Repositories\Educacion\ImporCensoDocenteRepositorio;
use App\Repositories\Educacion\ImporCensoMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
use App\Repositories\Educacion\PadronWebRepositorio;
use App\Repositories\Educacion\PlazaRepositorio;
use App\Repositories\Educacion\ServiciosBasicosRepositorio;
use App\Repositories\Educacion\TabletaRepositorio;
use App\Repositories\Presupuesto\BaseActividadesProyectosRepositorio;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseIngresosRepositorio;
use App\Repositories\Presupuesto\BaseProyectosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Vivienda\DatassRepositorio;
use App\Utilities\Utilitario;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public static $SISTEMA_ADMINISTRADOR_ID = 4;
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        /* echo 'errorrrr';
        try {
            \DB::connection()->getPdo();
            // echo \DB::connection()->getDatabaseName();
            // return;

        } catch (\Exception $e) {
            // echo 'error';
            // return;
            return view('errores.404');
        } */
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sistemas = SistemaRepositorio::Listar_porUsuario(auth()->user()->id);
        $usuper = UsuarioPerfilRepositorio::get_porusuariosistema(auth()->user()->id, HomeController::$SISTEMA_ADMINISTRADOR_ID);
        $perfils = UsuarioPerfilRepositorio::get_porusuario(auth()->user()->id);
        // return compact('sistemas','usuper','perfils');

        session()->put(['usuario_id' => auth()->user()->id]);
        session()->put(['total_sistema' => $sistemas->count()]);
        session()->put(['perfil_administrador_id' => $usuper ? $usuper->perfil_id : 0]);
        session()->put(['perfils' => $perfils]);
        session()->put(['sistemas' => $sistemas]);

        // return Schema::hasTable('edu_area')?'existe':'no existe';
        // return Schema::hasTable('par_lengua');//?'existe':'no existe';

        // $usuario = UsuarioRepositorio::Usuario(auth()->user()->id);
        // if ($usuario->first() != null) {
        //     session(['dnisismore$' => $usuario->first()->dni]);
        //     session(['passwordsismore$' => $usuario->first()->password]);
        // }

        return $this->sistema_acceder(ucwords($sistemas->first()->nombre));
    }

    public function sistema_acceder($sistema_nombre)
    {
        $osistema = Sistema::where('nombre', $sistema_nombre)->first();
        $sistema_id = $osistema->id;

        if (!session()->has('perfils')) {
            $sistemas = SistemaRepositorio::Listar_porUsuario(auth()->user()->id);
            $perfils = UsuarioPerfilRepositorio::get_porusuario(auth()->user()->id);
            $usuper = UsuarioPerfilRepositorio::get_porusuariosistema(auth()->user()->id, HomeController::$SISTEMA_ADMINISTRADOR_ID);
            session()->put(['sistemas' => $sistemas]);
            session()->put(['perfils' => $perfils]);
            session()->put(['perfil_administrador_id' => $usuper ? $usuper->perfil_id : 0]);
            session()->put(['total_sistema' => $sistemas->count()]);
        }

        // session()->forget('sistema_id');
        // session()->forget('sistema_nombre');
        // session()->forget('menuNivel01');
        // session()->forget('menuNivel02');

        session(['perfil_sistema_id' => session('perfils')->where('sistema_id', $sistema_id)->first()->perfil_id]);
        session(['sistema_id' => $sistema_id]);
        session(['sistema_nombre' => $osistema->nombre]);

        $menuNivel01 = MenuRepositorio::Listar_Nivel01_porUsuario_Sistema(auth()->user()->id, $sistema_id);
        session(['menuNivel01' => $menuNivel01]);

        $menuNivel02 = MenuRepositorio::Listar_Nivel02_porUsuario_Sistema(auth()->user()->id, $sistema_id);
        session(['menuNivel02' => $menuNivel02]);

        $nimp = ImportacionRepositorio::noti_importaciones($sistema_id, date('Y'));
        session(['nimp' => $nimp]);
        $ncon = $nimp->count();
        session(['ncon' => $ncon]);

        switch ($sistema_id) {
            case (1):
                return $this->educacion($sistema_id);
            case (2):
                return $this->vivienda($sistema_id);
            case (3):
                return $this->salud($sistema_id);
            case (4):
                return $this->administracion($sistema_id);
            case (5):
                return $this->presupuesto($sistema_id);
            case (6):
                return $this->trabajo($sistema_id);
            default:
                return 'Ruta de sistema no establecida';
        }
    }

    public function accesopublico()
    {
        $sistemas = Sistema::select('id as sistema_id', 'nombre', 'icono')->where('id', '!=', 4)->orderBy('pos')->get();
        if ($sistemas) {
            session(['sistemas_publico' => $sistemas]);
            session(['sistema_publico_id' => $sistemas->first()->sistema_id]);
            return $this->accesopublicomodulo($sistemas->first()->nombre);
        } else {
        }
    }

    public function accesopublicomodulo($sistema_nombre)
    {
        $sistema_id = Sistema::where('nombre', $sistema_nombre)->first()->id;
        session()->put(['sistema_publico_id' => $sistema_id]);
        switch ($sistema_id) {
            case (1):
                return $this->educacion_publico($sistema_id);
            case (2):
                return $this->vivienda_publico($sistema_id);
                break;
            case (3):
                return $this->salud_publico($sistema_id);
                //case (4):return $this->administracion($sistema_id);break;
            case (5):
                return $this->presupuesto_publico($sistema_id);
            case (6):
                return $this->trabajo_publico($sistema_id);
            default:
                return 'Ruta de sistema no establecida';
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
        if (!Schema::hasTable('tra_pea')) {
            return view('paginabloqueado'); //
        }
        return view('home', compact('sistema_id'));
    }

    public function trabajo_publico($sistema_id)
    {
        return view('homepublico', compact('sistema_id'));
    }

    public function presupuesto($sistema_id)
    {
        if (!Schema::hasTable('pres_base_siafweb')) {
            return view('paginabloqueado'); //
        }
        //$actualizado = '';
        $impSW = Importacion::where('fuenteimportacion_id', ImporSiafWebController::$FUENTE)->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
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

        $impAP = Importacion::where('fuenteimportacion_id', ImporActividadesProyectosController::$FUENTE)->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
        $opt2 = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);

        $anios = BaseSiafWebRepositorio::anios();

        $strSiafWeb = strtotime($impSW->fechaActualizacion);
        $actualizado = 'Actualizado al ' . date('d', $strSiafWeb) . ' de ' . $this->mes[date('m', $strSiafWeb) - 1] . ' del ' . date('Y', $strSiafWeb);

        $titulo = 'Modulo Presupuesto'; // 'Ejecución Presupuestal del Gobierno Regional de Ucayali';

        return view('home', compact('sistema_id', 'card1', 'card2', 'card3', 'card4',  'anio', 'baseAP',  'anios', 'actualizado', 'titulo'));
    }

    public function presupuestocuadros(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $impSW = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
        $baseSW = BaseSiafWeb::where('importacion_id', $impSW->id)->first();
        $opt1 = BaseSiafWebRepositorio::pia_pim_certificado_devengado($baseSW->id, $articulo);

        $card1['pim'] = number_format($opt1->pia, 0);
        $card1['eje'] = $opt1->eje_pia;
        $card2['pim'] = number_format($opt1->pim, 0);
        $card2['eje'] = $opt1->eje_pim;
        $card3['pim'] = number_format($opt1->cer, 0);
        $card3['eje'] = $opt1->eje_cer;
        $card4['pim'] = number_format($opt1->dev, 0);
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
        $reg = ['fue' => '', 'fec' => ''];
        if ($articulo == 0) {
            // $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $impAP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporActividadesProyectosController::$FUENTE, $anio);
            $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();
            $info = BaseActividadesProyectosRepositorio::listar_regiones($baseAP->id);
            $reg = ['fue' => $impAP->fuente, 'fec' => date('d/m/Y', strtotime($impAP->fecha))];
        } else if ($articulo == 1) {
            // $impP = Importacion::where('fuenteimportacion_id', '25')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $impP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporProyectosController::$FUENTE, $anio);
            $baseP = BaseProyectos::where('importacion_id', $impP->id)->first();
            $info = BaseProyectosRepositorio::listar_regiones($baseP->id);
            $reg = ['fue' => $impP->fuente, 'fec' => date('d/m/Y', strtotime($impP->fecha))];
        } else { //2
            // $impAP = Importacion::where('fuenteimportacion_id', '16')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $impAP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporActividadesProyectosController::$FUENTE, $anio);
            $baseAP = BaseActividadesProyectos::where('importacion_id', $impAP->id)->first();

            // $impP = Importacion::where('fuenteimportacion_id', '25')->where('estado', 'PR')->where(DB::raw('year(fechaActualizacion)'), $anio)->orderBy('fechaActualizacion', 'desc')->first();
            $impP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporProyectosController::$FUENTE, $anio);
            $baseP = BaseProyectos::where('importacion_id', $impP->id)->first();

            $info = BaseActividadesProyectosRepositorio::listar_regiones_actividad($baseAP->id, $baseP->id);
            $reg = ['fue' => $impAP->fuente, 'fec' => date('d/m/Y', strtotime($impAP->fecha))];
        }

        foreach ($info as $key => $value1) {
            $hc_key = $datax[$value1->codigo];
            $data[] = [$hc_key, $key + 1];
            $value1->color = $value1->codigo == 462 ? '#ef5350' : '#317eeb';
            $value1->y = round($value1->y, 2);
        }
        return response()->json(compact('info', 'data', 'reg'));
    }

    public function presupuestografica3(Request $rq)
    {
        $anio = $rq->get('anio');
        $articulo = $rq->get('articulo');

        $mes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        if ($articulo == 0) {
            $array = BaseActividadesProyectosRepositorio::baseids_fecha_max($anio);
            $base = BaseActividadesProyectosRepositorio::listado_ejecucion($array);

            $impAP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporActividadesProyectosController::$FUENTE, $anio);
            $reg = ['fue' => $impAP->fuente, 'fec' => date('d/m/Y', strtotime($impAP->fecha))];
        } else if ($articulo == 1) {
            $array = BaseProyectosRepositorio::baseids_fecha_max($anio);
            $base = BaseProyectosRepositorio::listado_ejecucion($array);

            $impP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporProyectosController::$FUENTE, $anio);
            $reg = ['fue' => $impP->fuente, 'fec' => date('d/m/Y', strtotime($impP->fecha))];
        } else { //2
            $array1 = BaseActividadesProyectosRepositorio::baseids_fecha_max($anio);
            $array2 = BaseProyectosRepositorio::baseids_fecha_max($anio);
            $base = BaseActividadesProyectosRepositorio::listado_ejecucion_actividad($array1, $array2);

            $impAP = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporActividadesProyectosController::$FUENTE, $anio);
            $reg = ['fue' => $impAP->fuente, 'fec' => date('d/m/Y', strtotime($impAP->fecha))];
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

        return response()->json(compact('info', 'reg'));
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

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporSiafWebController::$FUENTE, $anio);
        $reg = ['fue' => $imp->fuente, 'fec' => date('d/m/Y', strtotime($imp->fecha))];

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $info['series'][$value->name - 1] = $value->y;
        }
        return response()->json(compact('info', 'reg'));
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
        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporSiafWebController::$FUENTE, $anio);
        $reg = ['fue' => $imp->fuente, 'fec' => date('d/m/Y', strtotime($imp->fecha))];

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        $monto = 0;
        foreach ($base as $key => $value) {
            $value->y -= $monto;
            $info['series'][$value->name - 1] = $value->y;
            $monto = $value->y + $monto;
            $value->color = ($value->y < 0 ? '#ef5350' : '#317eeb');
        }
        return response()->json(compact('info', 'reg'));
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

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporSiafWebController::$FUENTE, $anio);
        $reg = ['fue' => $imp->fuente, 'fec' => date('d/m/Y', strtotime($imp->fecha))];

        $info['categoria'] = $mes;
        $info['series'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        $monto = 0;
        foreach ($base as $key => $value) {
            $value->y -= $monto;
            $info['series'][$value->name - 1] = $value->y;
            $monto = $value->y + $monto;
            $value->color = ($value->y < 0 ? '#ef5350' : '#317eeb');
        }
        return response()->json(compact('info', 'reg'));
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

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio(ImporSiafWebController::$FUENTE, $anio);
        $reg = ['fue' => $imp->fuente, 'fec' => date('d/m/Y', strtotime($imp->fecha))];

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
        return response()->json(compact('info', 'reg'));
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
        if (!Schema::hasTable('viv_centropoblado_datass')) {
            return view('paginabloqueado'); //
        }

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

    public function educacion($sistema_id)
    { //return Schema::hasTable('edu_area')?'existe':'no existe';
        if (!Schema::hasTable('edu_area')) {
            return view('paginabloqueado'); //viv_centropoblado_datass
        }

        $actualizado = '';
        $tipo_acceso = 0;
        $imgd = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
        $anio = $imgd->anio;

        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $ambitos = Area::all();

        return  view('home', compact(
            'tipo_acceso',
            'provincias',
            'distritos',
            'ambitos',
            'anio',
        ));
    }

    public function panelControlEduacionHead(Request $rq)
    {
        $anio = MatriculaGeneralRepositorio::anioId();
        $xx = MatriculaGeneralRepositorio::indicador01head($anio, $rq->provincia, $rq->distrito,  $rq->tipogestion, 1);
        $valor1 = $xx->basica;
        $valor2 = $xx->ebr;
        $valor3 = $xx->ebe;
        $valor4 = $xx->eba;
        $aa = Anio::find($anio);
        $aav =  -1 + (int)$aa->anio;
        $aa = Anio::where('anio', $aav)->first();
        $xx = MatriculaGeneralRepositorio::indicador01head($aa->id, $rq->provincia, $rq->distrito,  $rq->tipogestion, 1);
        $valor1x = $xx->basica;
        $valor2x = $xx->ebr;
        $valor3x = $xx->ebe;
        $valor4x = $xx->eba;

        $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 0, 1);
        $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 0, 1);
        $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 0, 1);
        $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 0, 1);

        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);

        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
    }

    public function panelControlEduacionGraficas(Request $rq)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($rq->div) {
            case 'anal1':
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }
                $dx = DB::table()->get();

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info'));

            case 'sganal1':
                $info['categoria'] = [2018, 2019, 2020, 2021, 2022, 2023];
                $info['series'] = [];
                $dx3 = [14000, 15000, 16000, 18000, 19000, 19000];
                $dx4 = [50, 60, 70, 80, 90, 100];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => '', 'color' => '#5eb9aa', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];

                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('info'));

            case 'anal2':
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));
            case 'sganal2':
                $data['cat'] = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL'];
                $data['dat'] = [80000, 100000, 120000, 140000, 180000, 200000, 210000];

                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('data'));

            case 'anal3':
                $info['categoria'] = [2018, 2019, 2020, 2021, 2022, 2023];
                $info['series'] = [];
                $dx3 = [14000, 15000, 16000, 18000, 19000, 19000];
                $dx4 = [50, 60, 70, 80, 90, 100];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => '', 'color' => '#5eb9aa', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('info'));

            case 'sganal3':
                $puntos = [
                    ["name" => "Hombre", "y" => 51, "yx" => 51],
                    ["name" => "Mujer", "y" => 40, "yx" => 40],
                ];
                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';
                return response()->json(compact('puntos', 'info'));

            case 'anal4':
                $info['categoria'] = [2018, 2019, 2020, 2021, 2022, 2023];
                $info['series'] = [];
                $dx3 = [14000, 15000, 16000, 18000, 19000, 19000];
                $dx4 = [50, 60, 70, 80, 90, 100];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => '', 'color' => '#5eb9aa', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];

                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('info'));

            case 'sganal4':
                $puntos = [
                    ["name" => "Urbano", "y" => 71],
                    ["name" => "Rural", "y" => 29],
                ];
                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';
                return response()->json(compact('puntos', 'info'));

            case 'dtanal1':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion) in (2018,2019),(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                        IF(year(par_importacion.fechaActualizacion) not in (2018,2019),v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion) in (2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $info['indicador'] = round(100 * $nx->d / $dx->d, 1);
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));

            case 'dtanal2':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion) in (2018,2019),(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion) not in (2018,2019),v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion) in (2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                $info['indicador'] = round(100 * $nx->d / $dx->d, 1);
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));

            case 'dtanal3':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion) in (2018,2019),(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion) not in (2018,2019),v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion) in (2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                $info['indicador'] = round(100 * $nx->d / $dx->d, 1);
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));

            case 'siagie001':
                $data = MatriculaGeneralRepositorio::basicaregularopcion2('siagie001', $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion);
                $info['cat'] = [];
                $info['dat'] = [];
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = [];
                }
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key][] = $value->conteo;
                        }
                    }
                }
                $info['dat'] = [];
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$key]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'censodocente001':
                $data = ImporCensoDocenteRepositorio::basicaregular('censodocente001', $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0);
                $info['cat'] = [];
                $info['dat'] = [];
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = [];
                }
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key][] = (int)$value->conteo;
                        }
                    }
                }
                $info['dat'] = [];
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$key]];
                }

                $reg['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills001':
                $anio = Anio::where('anio', $rq->anio - 1)->first();
                $data1 = MatriculaGeneralRepositorio::estudiantesModeloEIB($anio->id, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $anio = Anio::where('anio', $rq->anio)->first();
                $data2 = MatriculaGeneralRepositorio::estudiantesModeloEIB($anio->id, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $info['indicador'] = round(100 * $data2 / $data1, 0);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills002':
                $data1 = ImporCensoMatriculaRepositorio::_5ATotalEstudianteAnio($rq->anio - 1, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $data2 = ImporCensoMatriculaRepositorio::_5ATotalEstudianteAnio($rq->anio, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $info['indicador'] = round(100 * $data2->total / $data1->total, 0);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills003':
                $data1 = ImporCensoMatriculaRepositorio::_6ATotalEstudianteAnio($rq->anio - 1, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $data2 = ImporCensoMatriculaRepositorio::_6ATotalEstudianteAnio($rq->anio, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $info['indicador'] = round(100 * $data2->total / $data1->total, 0);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills004':
                $data1 = ImporCensoMatriculaRepositorio::_7ATotalEstudianteAnio($rq->anio - 1, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $data2 = ImporCensoMatriculaRepositorio::_7ATotalEstudianteAnio($rq->anio, $rq->provincia, $rq->distrito, 0, $rq->area,  $rq->gestion);
                $info['indicador'] = round(100 * $data2->total / $data1->total, 0);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills005':
                $data1 = ImporCensoMatriculaRepositorio::_9ATotalEstudianteAnio($rq->anio - 1, $rq->provincia, $rq->distrito, 0,  $rq->gestion, 0, 6);
                $data2 = ImporCensoMatriculaRepositorio::_9ATotalEstudianteAnio($rq->anio, $rq->provincia, $rq->distrito, 0,  $rq->gestion, 0, 6);
                $info['indicador'] = round(100 * $data2->total / $data1->total, 0);

                $reg['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoMatriculaController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'skills006':
                $info['indicador'] = ServiciosBasicosRepositorio::indicador($rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area, 4);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills007':
                $info['indicador'] = ServiciosBasicosRepositorio::indicador($rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area, 1);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));


            case 'skills008':
                $info['indicador'] = ServiciosBasicosRepositorio::indicador($rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area, 2);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'skills009':
                $info['indicador'] = ServiciosBasicosRepositorio::indicador($rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area, 3);

                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = ''; // date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'skills010':
                $info['indicador'] = ServiciosBasicosRepositorio::indicador($rq->anio, $rq->provincia, $rq->distrito, $rq->gestion, $rq->area, 5);

                $reg['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporServiciosBasicosController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'tabla1':
                $aniox = Anio::where('anio', $rq->anio)->first();
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaUgel($anioy->id, $rq->provincia, $rq->distrito,  $rq->gestion, 0);
                $base = MatriculaGeneralRepositorio::educacionbasicasexougel($aniox->id, $rq->provincia, $rq->distrito,  $rq->gestion, 0, 0);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->EBRth = 0;
                    $foot->EBRtm = 0;
                    $foot->EBEth = 0;
                    $foot->EBEtm = 0;
                    $foot->EBAth = 0;
                    $foot->EBAtm = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $mm) {
                            if ($value->ugel == $mm->ugel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->EBRth += $value->EBRth;
                        $foot->EBRtm += $value->EBRtm;
                        $foot->EBEth += $value->EBEth;
                        $foot->EBEtm += $value->EBEtm;
                        $foot->EBAth += $value->EBAth;
                        $foot->EBAtm += $value->EBAtm;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                // return response()->json(compact('base', 'foot'));
                $excel = view('educacion.Dashboard.Table1', compact('base', 'foot'))->render();

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));

            default:
                return response()->json([]);
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

    public function salud($sistema_id)
    {
        return view('home', []);
    }

    public function salud_publico($sistema_id)
    {
        return view('homepublico', []);
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

    public function educacionvacio()
    {
        return view('educacion.paginavacio');
    }
}
