<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseGastosDetalle;
use Illuminate\Support\Facades\DB;

class BaseGastosRepositorio
{
    public static function fechasActualicacion_anos_max()
    {
        $fechasb = DB::table(DB::raw("(
            select
                w1.id
            from pres_base_gastos as w1
            inner join par_importacion as w2 on w2.id = w1.importacion_id
            inner join (
                select max(x2.fechaActualizacion) as fecha from pres_base_gastos as x1
                inner join par_importacion as x2 on x2.id = x1.importacion_id
                where x2.estado = 'PR'
                group by anio
                        ) as w3 on w3.fecha=w2.fechaActualizacion
            where w2.estado = 'PR'    ) as tb
            "))->get();
        $fechas = [];
        foreach ($fechasb as $key => $value) {
            $fechas[] = $value->id;
        }
        return $fechas;
    }
    public static function total_pim($imp)
    {
        $query = BaseGastosDetalle::where('basegastos_id', $imp)->select(DB::raw('sum(pim) as pim'), DB::raw('100*sum(devengado)/sum(pim) as eje'))->first();
        return $query;
    }

    public static function pim_tipogobierno($bg)
    {
        $query = BaseGastosDetalle::select(
            'v5.id',
            'v5.tipogobierno as gobiernos',
            DB::raw('sum(pres_base_gastos_detalle.pim) as pim'),
            DB::raw('100*sum(pres_base_gastos_detalle.devengado)/sum(pres_base_gastos_detalle.pim) as eje')
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->where('w2.estado', 'PR')
            ->where('pres_base_gastos_detalle.basegastos_id', $bg);
        $query = $query->groupBy('id', 'gobiernos')->get();
        return $query;
    }

    public static function pim_tipogobierno2($bg_id)
    {
        $info = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg_id)
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->select(
                'v5.id',
                'v5.tipogobierno as name',
                DB::raw('sum(pres_base_gastos_detalle.pim) as y'),
            )
            ->groupBy('id', 'name')
            ->orderBy('v5.pos', 'asc')
            ->get();

        return $info;
    }

    public static function inversiones_pim_tipogobierno($bg_id)
    {
        $info = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg_id)
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id')
            ->select(
                'v5.id',
                'v5.tipogobierno as name',
                DB::raw('sum(pres_base_gastos_detalle.pim) as y'),
            )
            ->where('v6.codigo', '2')
            ->groupBy('id', 'name')
            ->orderBy('v5.pos', 'asc')
            ->get();
        $color = ['#7e57c2', '#317eeb', '#ef5350'];
        foreach ($info as $key => $value) {
            $value->color = $color[$key];
        }
        return $info;
    }

    public static function pim_anios_tipogobierno()
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'w2.id',
            'w1.anio as ano',
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.pim,0)) as pim1"),
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.pim,0)) as pim2"),
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.pim,0)) as pim3"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->where('w2.estado', 'PR')
            ->whereIn('w1.id', $fechas)
            ->orderBy('ano', 'asc');
        $query = $query->groupBy('id', 'ano')->get();

        return $query;
    }

    public static function inversion_pim_anios_tipogobierno()
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'w2.id',
            'w1.anio as ano',
            'v5.tipogobierno as tipo',
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.pim,0)) as pim1"),
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.pim,0)) as pim2"),
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.pim,0)) as pim3"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id')
            ->where('w2.estado', 'PR')
            ->where('v6.codigo', '2')
            ->whereIn('w1.id', $fechas)
            ->orderBy('ano', 'asc');
        $query = $query->groupBy('id', 'ano', 'tipo')->get();
        return $query;
    }
    public static function activades_pim_anios_tipogobierno()
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'w2.id',
            'w1.anio as ano',
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.pim,0)) as pim1"),
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.pim,0)) as pim2"),
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.pim,0)) as pim3"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id')
            ->where('w2.estado', 'PR')
            ->where('v6.codigo', '3')
            ->whereIn('w1.id', $fechas)
            ->orderBy('ano', 'asc');
        $query = $query->groupBy('id', 'ano')->get();
        return $query;
    }
    public static function pim_pia_devengado_tipogobierno($bg_id)
    {
        $query = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg_id)
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->select(
                'v5.id',
                'v5.tipogobierno as name',
                DB::raw('sum(pres_base_gastos_detalle.pia) as y1'),
                DB::raw('sum(pres_base_gastos_detalle.pim) as y2'),
                DB::raw('sum(pres_base_gastos_detalle.devengado) as y3'),
            )
            ->groupBy('id', 'name')
            ->orderBy('v5.pos', 'asc')
            ->get();
        return $query;
    }

    public static function inversion_pim_pia_devengado_tipogobierno($bg_id)
    {
        $query = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg_id)
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id')
            ->select(
                'v5.id',
                'v5.tipogobierno as name',
                DB::raw('round(sum(pres_base_gastos_detalle.pia)) as y1'),
                DB::raw('round(sum(pres_base_gastos_detalle.pim)) as y2'),
                DB::raw('round(sum(pres_base_gastos_detalle.devengado)) as y3'),
            )
            ->where('v6.codigo', '2')
            ->groupBy('id', 'name')
            ->orderBy('v5.pos', 'asc')
            ->get();
        return $query;
    }

    public static function pim_ejecutado_noejecutado_tipogobierno()
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'w2.id',
            'w1.anio as ano',
            DB::raw("sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.pim,0)) as gnp"),
            DB::raw("ROUND(sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.devengado,0)),2) as gnd"),
            DB::raw("ROUND(sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.pim,0))-sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_gastos_detalle.devengado,0)),2) as gnne"),

            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.pim,0)) as glp"),
            DB::raw("ROUND(sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.devengado,0)),2) as gld"),
            DB::raw("ROUND(sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.pim,0))-sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_gastos_detalle.devengado,0)),2) as glne"),

            DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.pim,0)) as grp"),
            DB::raw("ROUND(sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.devengado,0)),2) as grd"),
            DB::raw("ROUND(sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.pim,0))-sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_gastos_detalle.devengado,0)),2) as grne"),

            DB::raw("sum(pres_base_gastos_detalle.pim) as ttp"),
            DB::raw("ROUND(sum(pres_base_gastos_detalle.devengado),2) as ttd"),
            DB::raw("ROUND(sum(pres_base_gastos_detalle.pim)-sum(pres_base_gastos_detalle.devengado),2) as ttne"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id')
            ->where('w2.estado', 'PR')
            ->whereIn('w1.id', $fechas);
        $query = $query->groupBy('id', 'ano')->orderBy('ano','asc')->get();

        return $query;
    }

    public static function cargarsector($tipogobierno) //no usando
    {
        $query = BaseGastos::select('v7.*')
            ->join('par_anio as v2', 'v2.id', '=', 'pres_base_gastos.anio_id')
            ->join('par_importacion as v3', 'v3.id', '=', 'pres_base_gastos.importacion_id')
            ->join('pres_pliego as v4', 'v4.id', '=', 'pres_base_gastos.pliego_id')
            ->join('pres_unidadejecutora as v5', 'v5.id', '=', 'v4.unidadejecutora_id')
            ->join('pres_tipo_gobierno as v6', 'v6.id', '=', 'v5.tipogobierno')
            ->join('pres_sector as v7', 'v7.id', '=', 'pres_base_gastos.sector_id')
            ->where('v3.estado', 'PR')->where('v6.id', $tipogobierno)
            ->distinct()
            ->get();
        return $query;
    }

    public static function cargarue($tipogobierno, $sector) //no usando
    {
        $query = BaseGastos::select('v5.id', 'v5.unidad_ejecutora')
            ->join('par_anio as v2', 'v2.id', '=', 'pres_base_gastos.anio_id')
            ->join('par_importacion as v3', 'v3.id', '=', 'pres_base_gastos.importacion_id')
            ->join('pres_pliego as v4', 'v4.id', '=', 'pres_base_gastos.pliego_id')
            ->join('pres_unidadejecutora as v5', 'v5.id', '=', 'v4.unidadejecutora_id')
            ->join('pres_tipo_gobierno as v6', 'v6.id', '=', 'v5.tipogobierno')
            ->join('pres_sector as v7', 'v7.id', '=', 'pres_base_gastos.sector_id')
            ->where('v3.estado', 'PR')->where('v6.id', $tipogobierno)->where('v7.id', $sector)
            ->distinct()->orderBy('unidad_ejecutora', 'asc')
            ->get();
        return $query;
    }

    public static function pim_anio_categoriagasto($gob, $sec, $ue)
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'w2.id',
            'w1.anio as ano',
            DB::raw("sum(IF(v6.id=1,pres_base_gastos_detalle.pim,0)) as pim1"),
            DB::raw("sum(IF(v6.id=2,pres_base_gastos_detalle.pim,0)) as pim2"),
            DB::raw("sum(IF(v6.id=3,pres_base_gastos_detalle.pim,0)) as pim3"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_categoriagasto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.categoriagasto_id')
            ->where('w2.estado', 'PR')
            ->whereIn('w1.id', $fechas)
            ->orderBy('ano', 'asc');
        if ($gob != 0) $query = $query->where('v5.id', $gob);
        if ($sec != 0) $query = $query->where('v4.id', $sec);
        if ($ue != 0) $query = $query->where('v2.id', $ue);
        $query = $query->groupBy('id', 'ano')->get();
        return $query;
    }

    public static function pim_anio_categoriapresupuestal($gob, $sec, $ue)
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'w2.id',
            'w1.anio as ano',
            DB::raw("sum(IF(v6.id=38,pres_base_gastos_detalle.pim,0)) as pim1"),
            DB::raw("sum(IF(v6.id=39,pres_base_gastos_detalle.pim,0)) as pim2"),
            DB::raw("sum(IF(v6.id!=38 and v6.id!=39,pres_base_gastos_detalle.pim,0)) as pim3"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_categoriapresupuestal as v6', 'v6.id', '=', 'pres_base_gastos_detalle.categoriapresupuestal_id')
            ->where('w2.estado', 'PR')
            ->whereIn('w1.id', $fechas);
        if ($gob != 0) $query = $query->where('v5.id', $gob);
        if ($sec != 0) $query = $query->where('v4.id', $sec);
        if ($ue != 0) $query = $query->where('v2.id', $ue);
        $query = $query->groupBy('id', 'ano')->orderBy('ano', 'asc')->get();
        return $query;
    }

    public static function pim_anio_fuentefimanciamiento($gob, $sec, $ue)
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'v8.codigo as cod',
            'v8.nombre as ff',
            DB::raw("sum(IF(w1.anio=2014,pres_base_gastos_detalle.pim,0)) as pim_2014"),
            DB::raw("sum(IF(w1.anio=2015,pres_base_gastos_detalle.pim,0)) as pim_2015"),
            DB::raw("sum(IF(w1.anio=2016,pres_base_gastos_detalle.pim,0)) as pim_2016"),
            DB::raw("sum(IF(w1.anio=2017,pres_base_gastos_detalle.pim,0)) as pim_2017"),
            DB::raw("sum(IF(w1.anio=2018,pres_base_gastos_detalle.pim,0)) as pim_2018"),
            DB::raw("sum(IF(w1.anio=2019,pres_base_gastos_detalle.pim,0)) as pim_2019"),
            DB::raw("sum(IF(w1.anio=2020,pres_base_gastos_detalle.pim,0)) as pim_2020"),
            DB::raw("sum(IF(w1.anio=2021,pres_base_gastos_detalle.pim,0)) as pim_2021"),
            DB::raw("sum(IF(w1.anio=2022,pres_base_gastos_detalle.pim,0)) as pim_2022"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_recursos_gastos as v6', 'v6.id', '=', 'pres_base_gastos_detalle.recursosgastos_id')
            ->join('pres_rubro as v7', 'v7.id', '=', 'v6.rubro_id')
            ->join('pres_fuentefinanciamiento as v8', 'v8.id', '=', 'v7.fuentefinanciamiento_id')
            ->where('w2.estado', 'PR')
            ->whereIn('w1.id', $fechas);
        if ($gob != 0) $query = $query->where('v5.id', $gob);
        if ($sec != 0) $query = $query->where('v4.id', $sec);
        if ($ue != 0) $query = $query->where('v2.id', $ue);
        $query = $query->groupBy('cod', 'ff')->get();
        return $query;
    }

    public static function pim_anio_generica($gob, $sec, $ue)
    {
        $fechas = BaseGastosRepositorio::fechasActualicacion_anos_max();
        $query = BaseGastosDetalle::select(
            'v0.codigo as cod',
            'v0.nombre as ff',
            DB::raw("sum(IF(w1.anio=2014,pres_base_gastos_detalle.pim,0)) as pim_2014"),
            DB::raw("sum(IF(w1.anio=2015,pres_base_gastos_detalle.pim,0)) as pim_2015"),
            DB::raw("sum(IF(w1.anio=2016,pres_base_gastos_detalle.pim,0)) as pim_2016"),
            DB::raw("sum(IF(w1.anio=2017,pres_base_gastos_detalle.pim,0)) as pim_2017"),
            DB::raw("sum(IF(w1.anio=2018,pres_base_gastos_detalle.pim,0)) as pim_2018"),
            DB::raw("sum(IF(w1.anio=2019,pres_base_gastos_detalle.pim,0)) as pim_2019"),
            DB::raw("sum(IF(w1.anio=2020,pres_base_gastos_detalle.pim,0)) as pim_2020"),
            DB::raw("sum(IF(w1.anio=2021,pres_base_gastos_detalle.pim,0)) as pim_2021"),
            DB::raw("sum(IF(w1.anio=2022,pres_base_gastos_detalle.pim,0)) as pim_2022"),
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_gastos_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v7', 'v7.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v8', 'v8.id', '=', 'v7.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v9', 'v9.id', '=', 'v8.subgenerica_id')
            ->join('pres_generica_gastos as v0', 'v0.id', '=', 'v9.generica_id')
            ->where('w2.estado', 'PR')
            ->whereIn('w1.id', $fechas);
        if ($gob != 0) $query = $query->where('v5.id', $gob);
        if ($sec != 0) $query = $query->where('v4.id', $sec);
        if ($ue != 0) $query = $query->where('v2.id', $ue);
        $query = $query->groupBy('cod', 'ff')->get();
        return $query;
    }

    public static function xxx()
    {
    }
}
