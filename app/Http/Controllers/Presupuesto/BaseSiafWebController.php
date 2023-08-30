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
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BaseSiafWebController extends Controller
{
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

        return view('Presupuesto.BaseSiafWeb.Reporte1', compact('ano', 'articulo',  'categoria', 'impG'));
    }

    public function reporte1tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_unidadejecutora_anio_acticulo_funcion_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('categoria'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
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
        $ue = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        return view('Presupuesto.BaseSiafWeb.Reporte2', compact('ano', 'articulo',  'ue', 'impG'));
    }

    public function reporte2tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_categoria_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'), $rq->get('tc'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
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
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->get();
        $articulo = ProductoProyecto::all();
        $ue = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();
        $ff = FuenteFinanciamiento::all();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        return view('Presupuesto.BaseSiafWeb.Reporte3', compact('ano', 'articulo',  'ue', 'impG', 'ff'));
    }

    public function reporte3tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_productoproyecto_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'), $rq->get('ff'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
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
        $ue = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        return view('Presupuesto.BaseSiafWeb.Reporte4', compact('ano', 'articulo',  'ue', 'impG'));
    }

    public function reporte4tabla01(Request $rq)
    {
        $body = BaseSiafWebRepositorio::listar_funcion_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'));
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
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
        $ue = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        return view('Presupuesto.BaseSiafWeb.Reporte5', compact('ano', 'articulo',  'ue', 'impG'));
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
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')->where('v2.estado', 'PR')->get();
        $articulo = ProductoProyecto::all();
        $ue = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        return view('Presupuesto.BaseSiafWeb.Reporte6', compact('ano', 'articulo', 'ue', 'impG'));
    }

    public function reporte6tabla01(Request $rq)
    {
        $data = BaseSiafWebRepositorio::listar_generica_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'));
        $body = $data['body'];
        $head = $data['head'];
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
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
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->orderBy('anio', 'asc')->get();
        $articulo = ProductoProyecto::all();
        $ue = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();
        $ff = FuenteFinanciamiento::all();
        //$ff = Funcion::orderBy('nombre', 'asc')->get();
        $gg = GenericaGasto::orderBy('codigo', 'asc')->get();

        $impG = Importacion::where('fuenteimportacion_id', '24')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        return view('Presupuesto.BaseSiafWeb.Reporte7', compact('ano', 'articulo', 'ue', 'ff', 'gg', 'impG'));
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
        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
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
        $base = BaseSiafWeb::select('pres_base_siafweb.id')
            ->join('par_importacion as v1', 'v1.id', '=', 'pres_base_siafweb.importacion_id')
            ->where(DB::raw('year(v1.fechaActualizacion)'), $rq->anio)
            ->orderBy('v1.fechaActualizacion', 'desc')->first();
        $ue = BaseSiafWebDetalle::distinct()
            ->select('v1.*')
            ->join('pres_unidadejecutora as v1', 'v1.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->where('pres_base_siafweb_detalle.basesiafweb_id', $base->id)->get();
        return response()->json(compact('ue'));
    }
}
