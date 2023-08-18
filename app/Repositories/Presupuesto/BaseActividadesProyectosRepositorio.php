<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseActividadesProyectos;
use App\Models\Presupuesto\BaseActividadesProyectosDetalle;
use App\Models\Presupuesto\BaseSiafWebDetalle;
use Illuminate\Support\Facades\DB;

class BaseActividadesProyectosRepositorio
{

    public static function listar_regiones($base)
    {
        $query = BaseActividadesProyectosDetalle::where('pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id', $base)
            ->join('pres_gobiernos_regionales as v2', 'v2.id', '=', 'pres_base_actividadesproyectos_detalle.gobiernosregionales_id')
            ->select(
                DB::raw('v2.corto as name'),
                DB::raw('v2.codigo'),
                DB::raw('round(100*pres_base_actividadesproyectos_detalle.devengado/pres_base_actividadesproyectos_detalle.pim,5) as y'),
            )
            ->orderBy('y', 'desc')
            ->get();
        return $query;
    }

    public static function listar_regiones_actividad($baseAP, $baseP)
    {
        $query = DB::table(DB::raw("(select tb1.name,tb1.codigo,tb1.pim-tb2.pim as pim ,tb1.devengado-tb2.devengado as devengado from (
            select
                v2.corto as name,
                v2.codigo,
                pres_base_actividadesproyectos_detalle.pim,
                pres_base_actividadesproyectos_detalle.devengado
            from pres_base_actividadesproyectos_detalle
            inner join pres_gobiernos_regionales as v2 on v2.id = pres_base_actividadesproyectos_detalle.gobiernosregionales_id
            where pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id = $baseAP order by v2.codigo asc
            ) as tb1
        inner join (
            select
                v2.corto as name,
                v2.codigo,
                pres_base_proyectos_detalle.pim,
                pres_base_proyectos_detalle.devengado
            from pres_base_proyectos_detalle
            inner join pres_gobiernos_regionales as v2 on v2.id = pres_base_proyectos_detalle.gobiernosregionales_id
            where pres_base_proyectos_detalle.baseproyectos_id = $baseP order by v2.codigo asc
        ) as tb2 on tb2.codigo=tb1.codigo) as tb3"))
        ->select('*',DB::raw('round(100*devengado/pim,5) as y'))
            ->orderBy('y', 'desc')->get();
        return $query;
    }

    public static function baseids_fecha_max($anio)
    { //year(curdate())
        $query = DB::table(DB::raw("(select v1.id from pres_base_actividadesproyectos v1
            join par_importacion v3 on v3.id=v1.importacion_id
            join (select anio,mes,max(dia) as dia from pres_base_actividadesproyectos where anio=$anio group by anio,mes) as v2 on v2.anio=v1.anio and v2.mes=v1.mes and v2.dia=v1.dia
            where v1.anio=$anio and v3.estado='PR') as tb"))->get();
        $array = [];
        foreach ($query as $key => $value) {
            $array[] = $value->id;
        }
        return $array;
    }

    public static function listado_ejecucion($reg) //base detallee
    {
        $query = BaseActividadesProyectosDetalle::whereIn('pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id', $reg)
            ->join('pres_base_actividadesproyectos as v2', 'v2.id', '=', 'pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id')
            ->join('pres_gobiernos_regionales as v3', 'v3.id', '=', 'pres_base_actividadesproyectos_detalle.gobiernosregionales_id')
            ->select(
                'v2.mes as mes',
                'v3.departamento as dep',
                DB::raw('round(100*(pres_base_actividadesproyectos_detalle.devengado)/(pres_base_actividadesproyectos_detalle.pim),5) as eje'),
            )
            ->orderBy('mes', 'asc')
            ->orderBy('eje', 'desc')
            ->get();
        return $query;
    }

    public static function listado_ejecucion_actividad($reg1, $reg2) //base detallee
    {
        $x1 = '';
        foreach ($reg1 as $key => $value) {
            $x1 .= '' . $value;
            if (count($reg1) - 1 == $key)
                $x1 .= '';
            else
                $x1 .= ',';
        }
        $x2 = '';
        foreach ($reg2 as $key => $value) {
            $x2 .= '' . $value;
            if (count($reg2) - 1 == $key)
                $x2 .= '';
            else
                $x2 .= ',';
        }
        $query = DB::table(DB::raw("(
        select tb1.mes,tb1.dep,tb1.pim-tb2.pim as pim,tb1.devengado-tb2.devengado as devengado from (
            select v2.mes, v3.departamento as dep, pres_base_actividadesproyectos_detalle.devengado, pres_base_actividadesproyectos_detalle.pim
            from pres_base_actividadesproyectos_detalle
            inner join pres_base_actividadesproyectos as v2 on v2.id = pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id
            inner join pres_gobiernos_regionales as v3 on v3.id = pres_base_actividadesproyectos_detalle.gobiernosregionales_id
            where pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id in ($x1) order by mes asc, dep desc
        ) as tb1 inner join (
            select v2.mes, v3.departamento as dep, pres_base_proyectos_detalle.devengado, pres_base_proyectos_detalle.pim
            from pres_base_proyectos_detalle
            inner join pres_base_proyectos as v2 on v2.id = pres_base_proyectos_detalle.baseproyectos_id
            inner join pres_gobiernos_regionales as v3 on v3.id = pres_base_proyectos_detalle.gobiernosregionales_id
            where pres_base_proyectos_detalle.baseproyectos_id in ($x2) order by mes asc, dep desc
        ) as tb2 on tb2.mes=tb1.mes and tb2.dep=tb1.dep  ) as tb3"))
        ->select('*',DB::raw('round(100*devengado/pim,5) as eje'))
            ->orderBy('mes', 'asc')->orderBy('eje', 'desc')
            ->get();
        return $query;
    }
}
