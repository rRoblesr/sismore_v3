<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\BaseGastosExport;
use App\Exports\SiafWebRPT1Export;
use App\Exports\SiafWebRPT2Export;
use App\Exports\SiafWebRPT3Export;
use App\Exports\SiafWebRPT4Export;
use App\Exports\SiafWebRPT5Export;
use App\Exports\SiafWebRPT6Export;
use App\Exports\SiafWebRPT7Export;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\BaseSiafWebDetalle;
use App\Models\Presupuesto\CategoriaGasto;
use App\Models\Presupuesto\FuenteFinanciamiento;
use App\Models\Presupuesto\Funcion;
use App\Models\Presupuesto\GenericaGasto;
use App\Models\Presupuesto\ProductoProyecto;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Repositories\Educacion\CuboPadronEIBRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\PadronEIBRepositorio;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BaseSiafWebController extends Controller
{
    public $mesc = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $mesa = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte1()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->get();
        $articulo = ProductoProyecto::all();
        $categoria = CategoriaGasto::all();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte1', compact('ano', 'articulo',  'categoria', 'impG', 'actualizado'));
    }

    public function reporte1tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_unidadejecutora_anio_acticulo_funcion_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('categoria'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'eje1' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->cert / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        $foot['eje1'] = $foot['pim'] > 0 ? number_format(100 * $foot['cert'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte1Tabla1", compact('body', 'foot'));
    }

    public function reporte1download($ano, $articulo, $categoria)
    {
        if ($ano > 0) {
            $name = 'EJECUCIÓN DE GASTOS, SEGÚN UNIDADES EJECUTORAS ' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT1Export($ano, $articulo, $categoria), $name);
        }
    }

    public function reporte1grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt1_pim_devengado_acumulado_ejecucion_mensual($array, $rq->get('articulo'), $rq->get('categoria'), $rq->get('ue'));
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte2()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->orderBy('anio', 'desc')->get();
        $articulo = ProductoProyecto::all();
        $ue = BaseSiafWebRepositorio::UE_poranios(0);
        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte2', compact('ano', 'articulo',  'ue', 'impG', 'actualizado'));
    }

    public function reporte2tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_categoria_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'), $rq->get('tc'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'eje1' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->cert / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        $foot['eje1'] = $foot['pim'] > 0 ? number_format(100 * $foot['cert'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte2Tabla1", compact('body', 'foot'));
    }

    public function reporte2download($ano, $articulo, $ue, $tc)
    {
        if ($ano > 0) {
            $name = 'EJECUCIÓN DE GASTOS, SEGÚN CATEGORÍA PRESUPUESTAL ' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT2Export($ano, $articulo, $ue, $tc), $name);
        }
    }

    public function reporte2grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt2_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('tipocategoria'),
            $rq->get('categoriapresupuestal')
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte3()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->groupBy('anio')->get();
        $anio = $ano->max('anio');
        $articulo = ProductoProyecto::all();
        $ue = BaseSiafWebRepositorio::UE_poranios(0);
        $ff = FuenteFinanciamiento::all();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte3', compact('ano', 'anio', 'articulo',  'ue', 'impG', 'ff', 'actualizado'));
    }

    public function reporte3tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_productoproyecto_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'), $rq->get('ff'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'eje1' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->cert / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        $foot['eje1'] = $foot['pim'] > 0 ? number_format(100 * $foot['cert'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte3Tabla1", compact('body', 'foot'));
    }

    public function reporte3download($ano, $articulo, $ue, $ff)
    {
        if ($ano > 0) {
            $name = 'EJECUCIÓN DE GASTOS, SEGÚN PRODUCTO Y PROYECTO' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT3Export($ano, $articulo, $ue, $ff), $name);
        }
    }

    public function reporte3grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt3_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulos'),
            $rq->get('ue'),
            $rq->get('codigo'),
            $rq->get('articulo'),
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }


    public function reporte4()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->get();
        $articulo = ProductoProyecto::all();
        $ue = BaseSiafWebRepositorio::UE_poranios(0);
        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte4', compact('ano', 'articulo',  'ue', 'impG', 'actualizado'));
    }

    public function reporte4tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_funcion_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'eje1' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->cert / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        $foot['eje1'] = $foot['pim'] > 0 ? number_format(100 * $foot['cert'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte4Tabla1", compact('body', 'foot'));
    }

    public function reporte4download($ano, $articulo, $ue)
    {
        if ($ano > 0) {
            $name = 'EJECUCIÓN DE GASTOS, SEGÚN FUNCIÓN ' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT4Export($ano, $articulo, $ue), $name);
        }
    }

    public function reporte4grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt4_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('funcion'),
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }


    public function reporte5()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->get();
        $articulo = ProductoProyecto::all();
        $ue = BaseSiafWebRepositorio::UE_poranios(0);
        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte5', compact('ano', 'articulo',  'ue', 'impG', 'actualizado'));
    }

    public function reporte5tabla01(Request $rq)
    {
        $data = BaseSiafWebRepositorio::listar_fuentefinanciamiento_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'));
        /*  $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0; */
        return view("Presupuesto.BaseSiafWeb.Reporte5Tabla1", ['head' => $data['head'], 'body' => $data['body'], 'foot' => $data['foot']]);
    }

    public function reporte5download($ano, $articulo, $ue)
    {
        if ($ano > 0) {
            $name = 'EJECUCIÓN DE GASTOS, SEGÚN FUENTE DE FINANCIAMIENTO ' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT5Export($ano, $articulo, $ue), $name);
        }
    }

    public function reporte5grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt5_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('rubro'),
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte5grafica2(Request $rq)
    {
        $info['categoria'] = [];
        $basesiafweb_id = BaseSiafWebRepositorio::baseids_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt5_pim_devengado_acumulado_ejecucion_mensual2(
            $basesiafweb_id,
            $rq->get('articulo'),
            $rq->get('ue'),
        );
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($query as $key => $value) {
            $info['categoria'][] = $value->name;
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'] = [];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte5grafica3(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt5_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('rubro'),
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte6()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->where('v2.estado', 'PR')->orderBy('anio')->get();
        $anio = $ano->max('anio');
        $articulo = ProductoProyecto::all();
        $ue = BaseSiafWebRepositorio::UE_poranios(0);
        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte6', compact('ano', 'anio', 'articulo', 'ue', 'impG', 'actualizado'));
    }

    public function reporte6tabla01(Request $rq)
    {
        $data = BaseSiafWebRepositorio::listar_generica_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'));
        $body = $data['body'];
        $head = $data['head'];
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'eje1' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->cert / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        $foot['eje1'] = $foot['pim'] > 0 ? number_format(100 * $foot['cert'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte6Tabla1", compact('head', 'body', 'foot'));
    }

    public function reporte6download($ano, $articulo, $ue)
    {
        if ($ano > 0) {
            $base = BaseSiafWeb::select('pres_base_siafweb.id')
                ->join('par_importacion as v1', 'v1.id', '=', 'pres_base_siafweb.importacion_id')
                ->where(DB::raw('year(v1.fechaActualizacion)'), $ano)
                ->orderBy('v1.fechaActualizacion', 'desc')->first();
            $ue = BaseSiafWebDetalle::distinct()
                ->select('v1.*')
                ->join('pres_unidadejecutora as v1', 'v1.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
                ->where('pres_base_siafweb_detalle.basesiafweb_id', $base->id);
            if ($articulo > 0) $ue = $ue->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
            $ue = $ue->get();

            $name = 'GENÉRICAS_POR_UNIDAD_EJECUTORA_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT6Export($ano, $articulo, $ue, $base->id), $name);
        }
    }

    public function reporte6grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt6_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('generica'),
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte6grafica2(Request $rq)
    {
        $info['categoria'] = [];
        $basesiafweb_id = BaseSiafWebRepositorio::baseids_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt6_pim_devengado_acumulado_ejecucion_mensual2(
            $basesiafweb_id,
            $rq->get('articulo'),
            $rq->get('ue'),
        );
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($query as $key => $value) {
            $info['categoria'][] = $value->name;
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'] = [];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function reporte7()
    {
        $ano = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->orderBy('anio')->get();
        $anio = $ano->max('anio');
        $articulo = ProductoProyecto::all();
        $ue = BaseSiafWebRepositorio::UE_poranios(0);
        $ff = FuenteFinanciamiento::all();
        //$ff = Funcion::orderBy('nombre', 'asc')->get();
        $gg = GenericaGasto::orderBy('codigo', 'asc')->get();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('24', date('Y', strtotime($impG->fechaActualizacion)));
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));

        return view('Presupuesto.BaseSiafWeb.Reporte7', compact('ano', 'anio', 'articulo', 'ue', 'ff', 'gg', 'impG', 'actualizado'));
    }

    public function reporte7tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_subgenerica_anio_acticulo_ue_categoria(
            $rq->get('anio'),
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('ff'),
            $rq->get('generica'),
            //$rq->get('sg'),
            $rq->get('partidas')
        );
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'eje1' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->cert / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        $foot['eje1'] = $foot['pim'] > 0 ? number_format(100 * $foot['cert'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte7Tabla1", compact('body', 'foot'));
    }

    public function reporte7download($ano, $articulo, $ue, $ff, $gg, $partidas)
    {
        if ($ano > 0) {
            $name = 'Ejecución de Gastos, según Especifica Detalle ' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SiafWebRPT7Export($ano, $articulo, $ue, $ff, $gg, $partidas), $name);
        }
    }

    public function reporte7grafica1(Request $rq)
    {
        $info['categoria'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        //$info['categoria'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $array = BaseSiafWebRepositorio::baseids_fecha_max($rq->get('anio'));
        $query = BaseSiafWebRepositorio::rpt7_pim_devengado_acumulado_ejecucion_mensual(
            $array,
            $rq->get('articulo'),
            $rq->get('ue'),
            $rq->get('ff'),
            $rq->get('generica'),
            $rq->get('sg'),
            $rq->get('ed'),
        );
        $info['series'] = [];
        $dx1 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx2 = [null, null, null, null, null, null, null, null, null, null, null, null];
        $dx3 = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($query as $key => $value) {
            $dx1[$key] = $value->pim; //pim
            $dx2[$key] = $value->devengado; //devengado
            $dx3[$key] = $value->ejecucion; //ejecucion
        }
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'PIM', 'color' => '#317eeb', 'data' => $dx1];
        $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'DEVENGADO', 'color' => '#ef5350', 'data' => $dx2];
        $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%EJECUCIÓN',  'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('info'));
    }

    public function cargar_productoproyecto(Request $rq)
    {
        $base = BaseSiafWeb::select('pres_base_siafweb.id')
            ->join('par_importacion as v1', 'v1.id', '=', 'pres_base_siafweb.importacion_id')
            ->where(DB::raw('year(v1.fechaActualizacion)'), $rq->anio)
            ->orderBy('v1.fechaActualizacion', 'desc')->first();
        $productoproyecto = BaseSiafWebDetalle::distinct()
            ->select('v1.*')
            ->join('pres_producto_proyecto as v1', 'v1.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->where('pres_base_siafweb_detalle.basesiafweb_id', $base->id)->get();
        return response()->json(compact('productoproyecto'));
    }

    public function cargar_unidadejecutora(Request $rq)
    {
        // $base = BaseSiafWeb::select('pres_base_siafweb.id')
        //     ->join('par_importacion as v1', 'v1.id', '=', 'pres_base_siafweb.importacion_id')
        //     ->where(DB::raw('year(v1.fechaActualizacion)'), $rq->anio)
        //     ->orderBy('v1.fechaActualizacion', 'desc')->first();
        // $ue = BaseSiafWebDetalle::distinct()
        //     ->select('v1.*')
        //     ->join('pres_unidadejecutora as v1', 'v1.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
        //     ->where('pres_base_siafweb_detalle.basesiafweb_id', $base->id)->get();
        $ue = BaseSiafWebRepositorio::UE_poranios($rq->anio);
        return response()->json(compact('ue'));
    }

    public function reportes()
    {
        $anios =  CuboPadronEIBRepositorio::select_anios();
        $aniomax = $anios->max();
        $anioeib = PadronEIBRepositorio::getYearMapping($aniomax);
        return view('presupuesto.BaseSiafWeb.EduReportes', compact('anios', 'aniomax', 'anioeib'));
    }

    public function reportesreporte(Request $rq)
    {
        $div = $rq->div;
        switch ($rq->div) {
            case 'head':
                $data = CuboPadronEIBRepositorio::reportesreporte_head($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $card1 = number_format($data->servicios, 0);
                $card2 = number_format($data->lengua, 0);
                $card3 = number_format($data->matriculados, 0);
                $card4 = number_format($data->docentes, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $pe_pv = [
                    '2501' => 'pe-uc-cp',
                    '2502' => 'pe-uc-at',
                    '2503' => 'pe-uc-pa',
                    '2504' => 'pe-uc-pr',
                ];
                $info = [];
                $valores = [];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal1($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $total = $data->sum('conteo');
                foreach ($data as $key => $value) {
                    $info[] = [$value->codigo, round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                    $valores[$value->codigo] = ['num' => (int)$value->conteo, 'dem' => $total, 'ind' => round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                }
                return response()->json(compact('info', 'valores', 'data'));

            case 'anal2':
                $info = [
                    'categoria' => [],
                    'series' => [
                        ['type' => 'column', 'yAxis' => 0, 'data' => [], 'name' => 'Matriculados'],
                        ['type' => 'spline', 'yAxis' => 1, 'data' => [], 'name' => '%Avance'],
                    ],
                    'maxbar' => 0,
                ];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal2($rq->gestion, $rq->provincia, $rq->distrito);
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->anio;
                    $info['series'][0]['data'][] = (int)$value->matriculados;
                    $info['series'][1]['data'][] = $key == 0 ? 0 : round($data[$key - 1]->matriculados > 0 ? 100 * $value->matriculados / $data[$key - 1]->matriculados : 0, 1);
                    $info['maxbar'] = max($info['maxbar'], (int)$value->matriculados);
                }
                return response()->json(compact('info', 'data'));

            case 'anal3':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal3($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                foreach ($data as $key => $value) {
                    $value->y = (int)$value->y;
                    $value->color = $color[$key % count($color)];
                }
                return response()->json($data);

            case 'anal4':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $info = [
                    'cat' => [],
                    'dat' => [
                        ['name' => 'inicial',    'data' => [], 'color' => $color[0]],
                        ['name' => 'primaria',   'data' => [], 'color' => $color[1]],
                        ['name' => 'secundaria', 'data' => [], 'color' => $color[2]],
                    ]
                ];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal4($rq->gestion, $rq->provincia, $rq->distrito);
                foreach ($data->unique('anio') as $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data as $value) {
                    switch (strtolower($value->name)) {
                        case 'inicial':
                            $info['dat'][0]['data'][] = (int)$value->y;
                            break;
                        case 'primaria':
                            $info['dat'][1]['data'][] = (int)$value->y;
                            break;
                        case 'secundaria':
                            $info['dat'][2]['data'][] = (int)$value->y;
                            break;
                    }
                }
                return response()->json($info);
            case 'anal5':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data2 = CuboPadronEIBRepositorio::reportesreporte_anal5($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $data = [
                    ["name" => "CONTRATADO", "y" => (int)$data2->contratado, "color" => "#43beac"],
                    ["name" => "NOMBRADO", "y" => (int)$data2->nombrado, "color" => "#ffc107"]
                ];
                return response()->json($data);
            case 'anal6':
                $data = CuboPadronEIBRepositorio::reportesreporte_anal6($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $niveles = ['INICIAL', 'PRIMARIA', 'SECUNDARIA'];
                $series = [
                    'NOMBRADOS'  => ['name' => 'NOMBRADOS',  'color' => '#43beac', 'data' => array_fill(0, count($niveles), 0)],
                    'CONTRATADOS' => ['name' => 'CONTRATADOS', 'color' => '#ffc107', 'data' => array_fill(0, count($niveles), 0)],
                ];
                foreach ($data as $key => $value) {
                    $nivel = $value->NIVEL;
                    $nombrados = (int) $value->NOMBRADOS;
                    $contratados = (int) $value->CONTRATADOS;

                    // Buscar índice del nivel en el array definido
                    $idx = array_search($nivel, $niveles);
                    if ($idx !== false) {
                        $series['NOMBRADOS']['data'][$idx] = $nombrados;
                        $series['CONTRATADOS']['data'][$idx] = $contratados;
                    }
                }
                $response = [
                    'categories' => $niveles,
                    'series' => array_values($series), // convierte a array indexado: [serie1, serie2]
                    'xx' => $data,
                ];

                return response()->json($response);

            case 'tabla1':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla1($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->forma_atencion = 'TOTAL';
                    // Servicios educativos
                    $foot->ts = $base->sum('ts');
                    $foot->tsr = $base->sum('tsr');
                    $foot->tsu = $base->sum('tsu');
                    // Estudiantes matriculados
                    $foot->tm = $base->sum('tm');
                    $foot->tmr = $base->sum('tmr');
                    $foot->tmu = $base->sum('tmu');
                    // Personal docente
                    $foot->td = $base->sum('td');
                    $foot->tdr = $base->sum('tdr');
                    $foot->tdu = $base->sum('tdu');
                    // Auxiliar de educación
                    $foot->ta = $base->sum('ta');
                    $foot->tar = $base->sum('tar');
                    $foot->tau = $base->sum('tau');
                    // PEC
                    $foot->tp = $base->sum('tp');
                    $foot->tpr = $base->sum('tpr');
                    $foot->tpu = $base->sum('tpu');
                }
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla2':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla2($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->nivel_modalidad = 'TOTAL';
                    // Servicios educativos
                    $foot->ts = $base->sum('ts');
                    $foot->tsr = $base->sum('tsr');
                    $foot->tsu = $base->sum('tsu');
                    // Estudiantes matriculados
                    $foot->tm = $base->sum('tm');
                    $foot->tmr = $base->sum('tmr');
                    $foot->tmu = $base->sum('tmu');
                    // Personal docente
                    $foot->td = $base->sum('td');
                    $foot->tdr = $base->sum('tdr');
                    $foot->tdu = $base->sum('tdu');
                    // Auxiliar de educación
                    $foot->ta = $base->sum('ta');
                    $foot->tar = $base->sum('tar');
                    $foot->tau = $base->sum('tau');
                    // PEC
                    $foot->tp = $base->sum('tp');
                    $foot->tpr = $base->sum('tpr');
                    $foot->tpu = $base->sum('tpu');
                }
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla3':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla3($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->lengua = 'TOTAL';
                    // Servicios educativos
                    $foot->ts = $base->sum('ts');
                    $foot->tsr = $base->sum('tsr');
                    $foot->tsu = $base->sum('tsu');
                    // Estudiantes matriculados
                    $foot->tm = $base->sum('tm');
                    $foot->tmr = $base->sum('tmr');
                    $foot->tmu = $base->sum('tmu');
                    // Personal docente
                    $foot->td = $base->sum('td');
                    $foot->tdr = $base->sum('tdr');
                    $foot->tdu = $base->sum('tdu');
                    // Auxiliar de educación
                    $foot->ta = $base->sum('ta');
                    $foot->tar = $base->sum('tar');
                    $foot->tau = $base->sum('tau');
                    // PEC
                    $foot->tp = $base->sum('tp');
                    $foot->tpr = $base->sum('tpr');
                    $foot->tpu = $base->sum('tpu');
                }
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla4':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla4($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base'))->render();
                return response()->json(compact('excel', 'base'));
                // return response()->json(compact('div', 'base', 'foot'));
            default:
                # code...
                return [];
        }
    }
}
