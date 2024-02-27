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


    public static function nivelesgobiernoscards($div, $bg)
    {
        switch ($div) {
            case 'anal1':
                $info = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg)
                    ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id', 'left')
                    ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id', 'left')
                    ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id', 'left')
                    ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id', 'left')
                    ->select(
                        'v5.id',
                        'v5.tipogobierno as name',
                        DB::raw('sum(pres_base_gastos_detalle.pim) as y'),
                    )
                    ->groupBy('id', 'name')
                    ->orderBy('v5.pos', 'asc')
                    ->get();

                return $info;
            case 'anal2':
                $query = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg)
                    ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id', 'left')
                    ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id', 'left')
                    ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id', 'left')
                    ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id', 'left')
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
                return $query;
            case 'anal3':
                $info = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg)
                    ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id', 'left')
                    ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id', 'left')
                    ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id', 'left')
                    ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id', 'left')
                    ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id', 'left')
                    ->select(
                        'v5.id',
                        'v5.tipogobierno as name',
                        DB::raw('sum(pres_base_gastos_detalle.pim) as y'),
                    )
                    ->where('v6.codigo', '2')
                    ->groupBy('id', 'name')
                    ->orderBy('v5.pos', 'asc')
                    ->get();
                // $color = ['#7e57c2', '#317eeb', '#ef5350'];
                // foreach ($info as $key => $value) {
                //     $value->color = $color[$key];
                // }
                return $info;
            case 'anal4':
                // $query = BaseGastosDetalle::where('pres_base_gastos_detalle.basegastos_id', $bg)
                //     ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id', 'left')
                //     ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id', 'left')
                //     ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id', 'left')
                //     ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id', 'left')
                //     ->join('pres_producto_proyecto as v6', 'v6.id', '=', 'pres_base_gastos_detalle.productoproyecto_id', 'left')
                //     ->select(
                //         'v5.id',
                //         'v5.tipogobierno as name',
                //         DB::raw('round(sum(pres_base_gastos_detalle.pia)) as y1'),
                //         DB::raw('round(sum(pres_base_gastos_detalle.pim)) as y2'),
                //         DB::raw('round(sum(pres_base_gastos_detalle.devengado)) as y3'),
                //     )
                //     ->where('v6.codigo', '2')
                //     ->groupBy('id', 'name')
                //     ->orderBy('v5.pos', 'asc')
                //     ->get();


                $query = DB::table(DB::raw("(
                    select
                    bgd.pia, bgd.pim, bgd.devengado, bgd.unidadejecutora_id
                    from pres_base_gastos_detalle as bgd
                    inner join (
                        select bg2.id
                        from pres_base_gastos as bg2
                        inner join par_importacion as imp2 on imp2.id = bg2.importacion_id
                        where imp2.estado = 'PR' and bg2.id=$bg
                    ) as bg3 on bg3.id = bgd.basegastos_id
                ) as bgd"))
                    ->select(
                        'tgob.id',
                        'tgob.tipogobierno as name',
                        DB::raw('round(sum(bgd.pia)) as y1'),
                        DB::raw('round(sum(bgd.pim)) as y2'),
                        DB::raw('round(sum(bgd.devengado)) as y3'),
                    )
                    ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id', 'left')
                    ->join('pres_pliego as plie', 'plie.id', '=', 'ue.pliego_id', 'left')
                    ->join('pres_sector as sec', 'sec.id', '=', 'plie.sector_id', 'left')
                    ->join('pres_tipo_gobierno as tgob', 'tgob.id', '=', 'sec.tipogobierno_id', 'left');
                $query = $query->groupBy('id', 'name')->get();

                return $query;
            case 'anal5':
                $query = DB::table(DB::raw('(
                    select
                        bgd.pim, bgd.unidadejecutora_id, bg3.anio
                    from pres_base_gastos_detalle as bgd
                    inner join (
                        select bg2.id, bg2.anio
                        from pres_base_gastos as bg2
                        inner join par_importacion as imp2 on imp2.id = bg2.importacion_id
                        inner join (
                            select max(imp1.fechaActualizacion) as fecha
                            from pres_base_gastos as bg1
                            inner join par_importacion as imp1 on imp1.id = bg1.importacion_id
                            where imp1.estado = "PR"
                            group by anio
                        ) as fa on fa.fecha=imp2.fechaActualizacion
                    ) as bg3 on bg3.id = bgd.basegastos_id
                ) as bgd'))
                    ->select(
                        'bgd.anio as ano',
                        DB::raw("sum(IF(tgob.id=2,bgd.pim,0)) as pim1"),
                        DB::raw("sum(IF(tgob.id=3,bgd.pim,0)) as pim2"),
                        DB::raw("sum(IF(tgob.id=1,bgd.pim,0)) as pim3"),
                    )
                    ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id', 'left')
                    ->join('pres_pliego as plie', 'plie.id', '=', 'ue.pliego_id', 'left')
                    ->join('pres_sector as sec', 'sec.id', '=', 'plie.sector_id', 'left')
                    ->join('pres_tipo_gobierno as tgob', 'tgob.id', '=', 'sec.tipogobierno_id', 'left')
                    ->orderBy('ano', 'asc');
                $query = $query->groupBy('ano')->get();

                return $query;
            case 'anal6':
                $query = DB::table(DB::raw('(
                    select
                        bgd.pim, bgd.unidadejecutora_id, bg3.anio
                    from pres_base_gastos_detalle as bgd
                    inner join (
                        select bg2.id, bg2.anio
                        from pres_base_gastos as bg2
                        inner join par_importacion as imp2 on imp2.id = bg2.importacion_id
                        inner join (
                            select max(imp1.fechaActualizacion) as fecha
                            from pres_base_gastos as bg1
                            inner join par_importacion as imp1 on imp1.id = bg1.importacion_id
                            where imp1.estado = "PR"
                            group by anio
                        ) as fa on fa.fecha=imp2.fechaActualizacion
                    ) as bg3 on bg3.id = bgd.basegastos_id
                    where bgd.productoproyecto_id=2
                ) as bgd'))
                    ->select(
                        'bgd.anio as ano',
                        DB::raw("sum(IF(tgob.id=2,bgd.pim,0)) as pim1"),
                        DB::raw("sum(IF(tgob.id=3,bgd.pim,0)) as pim2"),
                        DB::raw("sum(IF(tgob.id=1,bgd.pim,0)) as pim3"),
                    )
                    ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id', 'left')
                    ->join('pres_pliego as plie', 'plie.id', '=', 'ue.pliego_id', 'left')
                    ->join('pres_sector as sec', 'sec.id', '=', 'plie.sector_id', 'left')
                    ->join('pres_tipo_gobierno as tgob', 'tgob.id', '=', 'sec.tipogobierno_id', 'left')
                    ->orderBy('ano', 'asc');
                $query = $query->groupBy('ano')->get();

                return $query;
            case 'anal7':
                $query = DB::table(DB::raw('(
                    select
                        bgd.pim, bgd.unidadejecutora_id, bg3.anio
                    from pres_base_gastos_detalle as bgd
                    inner join (
                        select bg2.id, bg2.anio
                        from pres_base_gastos as bg2
                        inner join par_importacion as imp2 on imp2.id = bg2.importacion_id
                        inner join (
                            select max(imp1.fechaActualizacion) as fecha
                            from pres_base_gastos as bg1
                            inner join par_importacion as imp1 on imp1.id = bg1.importacion_id
                            where imp1.estado = "PR"
                            group by anio
                        ) as fa on fa.fecha=imp2.fechaActualizacion
                    ) as bg3 on bg3.id = bgd.basegastos_id
                    where bgd.productoproyecto_id=1
                ) as bgd'))
                    ->select(
                        'bgd.anio as ano',
                        DB::raw("sum(IF(tgob.id=2,bgd.pim,0)) as pim1"),
                        DB::raw("sum(IF(tgob.id=3,bgd.pim,0)) as pim2"),
                        DB::raw("sum(IF(tgob.id=1,bgd.pim,0)) as pim3"),
                    )
                    ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id', 'left')
                    ->join('pres_pliego as plie', 'plie.id', '=', 'ue.pliego_id', 'left')
                    ->join('pres_sector as sec', 'sec.id', '=', 'plie.sector_id', 'left')
                    ->join('pres_tipo_gobierno as tgob', 'tgob.id', '=', 'sec.tipogobierno_id', 'left')
                    ->orderBy('ano', 'asc');
                $query = $query->groupBy('ano')->get();

                return $query;
            case 'table1':
                $query = DB::table(DB::raw('(
                    select
                        bgd.pim, bgd.devengado, bgd.unidadejecutora_id, bg3.anio
                    from pres_base_gastos_detalle as bgd
                    inner join (
                        select bg2.id, bg2.anio
                        from pres_base_gastos as bg2
                        inner join par_importacion as imp2 on imp2.id = bg2.importacion_id
                        inner join (
                            select max(imp1.fechaActualizacion) as fecha
                            from pres_base_gastos as bg1
                            inner join par_importacion as imp1 on imp1.id = bg1.importacion_id
                            where imp1.estado = "PR"
                            group by anio
                        ) as fa on fa.fecha=imp2.fechaActualizacion
                    ) as bg3 on bg3.id = bgd.basegastos_id
                    where bgd.productoproyecto_id=2
                ) as bgd'))
                    ->select(
                        'bgd.anio as ano',
                        DB::raw("sum(bgd.pim) as ttp"),
                        DB::raw("sum(bgd.devengado) as ttd"),
                        DB::raw("sum(bgd.pim)-sum(bgd.devengado) as ttne"),

                        DB::raw("sum(IF(tgob.id=1,bgd.pim,0)) as glp"),
                        DB::raw("sum(IF(tgob.id=1,bgd.devengado,0)) as gld"),
                        DB::raw("sum(IF(tgob.id=1,bgd.pim,0))-sum(IF(tgob.id=1,bgd.devengado,0)) as glne"),

                        DB::raw("sum(IF(tgob.id=2,bgd.pim,0)) as gnp"),
                        DB::raw("sum(IF(tgob.id=2,bgd.devengado,0)) as gnd"),
                        DB::raw("sum(IF(tgob.id=2,bgd.pim,0))-sum(IF(tgob.id=2,bgd.devengado,0)) as gnne"),

                        DB::raw("sum(IF(tgob.id=3,bgd.pim,0)) as grp"),
                        DB::raw("sum(IF(tgob.id=3,bgd.devengado,0)) as grd"),
                        DB::raw("sum(IF(tgob.id=3,bgd.pim,0))-sum(IF(tgob.id=3,bgd.devengado,0)) as grne"),


                    )
                    ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id', 'left')
                    ->join('pres_pliego as plie', 'plie.id', '=', 'ue.pliego_id', 'left')
                    ->join('pres_sector as sec', 'sec.id', '=', 'plie.sector_id', 'left')
                    ->join('pres_tipo_gobierno as tgob', 'tgob.id', '=', 'sec.tipogobierno_id', 'left')
                    ->orderBy('ano', 'asc');
                $query = $query->groupBy('ano')->get();

                return $query;
            default:
                return [];
        }
    }

    public static function pim_tipogobierno($bg)
    {
        // $query = BaseGastosDetalle::select(
        //     'v5.id',
        //     'v5.tipogobierno as gobiernos',
        //     DB::raw('sum(pres_base_gastos_detalle.pim) as pim'),
        //     DB::raw('100*sum(pres_base_gastos_detalle.devengado)/sum(pres_base_gastos_detalle.pim) as eje')
        // )
        //     ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
        //     ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
        //     ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
        //     ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
        //     ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
        //     ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
        //     ->where('w2.estado', 'PR')
        //     ->where('pres_base_gastos_detalle.basegastos_id', $bg);
        // $query = $query->groupBy('id', 'gobiernos')->get();
        // return $query;

        $query = DB::table(DB::raw("(
            select
                bgd.pim, bgd.devengado, bgd.unidadejecutora_id
            from pres_base_gastos_detalle as bgd
            inner join (
                select bg2.id
                from pres_base_gastos as bg2
                inner join par_importacion as imp2 on imp2.id = bg2.importacion_id
                where imp2.estado = 'PR' and bg2.id=$bg
            ) as bg3 on bg3.id = bgd.basegastos_id
        ) as bgd"))
            ->select(
                'tgob.id',
                'tgob.tipogobierno as gobiernos',
                DB::raw('sum(bgd.pim) as pim'),
                DB::raw('100*sum(bgd.devengado)/sum(bgd.pim) as eje')
            )
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id', 'left')
            ->join('pres_pliego as plie', 'plie.id', '=', 'ue.pliego_id', 'left')
            ->join('pres_sector as sec', 'sec.id', '=', 'plie.sector_id', 'left')
            ->join('pres_tipo_gobierno as tgob', 'tgob.id', '=', 'sec.tipogobierno_id', 'left');
        $query = $query->groupBy('id', 'gobiernos')->get();

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
