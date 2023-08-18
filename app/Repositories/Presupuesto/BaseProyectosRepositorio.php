<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseProyectosDetalle;
use Illuminate\Support\Facades\DB;

class BaseProyectosRepositorio
{

    public static function listar_regiones($base)
    {
        $query = BaseProyectosDetalle::where('pres_base_proyectos_detalle.baseproyectos_id', $base)
            ->join('pres_gobiernos_regionales as v2', 'v2.id', '=', 'pres_base_proyectos_detalle.gobiernosregionales_id')
            ->select(
                DB::raw('v2.corto as name'),
                //DB::raw('"#317eeb" as color'),
                DB::raw('v2.codigo'),
                DB::raw('round(100*pres_base_proyectos_detalle.devengado/pres_base_proyectos_detalle.pim,5) as y'),
            )
            ->orderBy('y', 'desc')
            ->get();
        return $query;
    }

    public static function baseids_fecha_max($anio)
    { //year(curdate())
        $query = DB::table(DB::raw("(select v1.id from pres_base_proyectos v1
            join par_importacion v3 on v3.id=v1.importacion_id
            join (select anio,mes,max(dia) as dia from pres_base_proyectos where anio=$anio group by anio,mes) as v2 on v2.anio=v1.anio and v2.mes=v1.mes and v2.dia=v1.dia
            where v1.anio=$anio and v3.estado='PR') as tb"))->get();
        $array = [];
        foreach ($query as $key => $value) {
            $array[] = $value->id;
        }
        return $array;
    }

    public static function suma_ejecucion($reg) //base detallee
    {
        $query = BaseProyectosDetalle::whereIn('pres_base_proyectos_detalle.baseproyectos_id', $reg)
            ->join('pres_base_proyectos as v2', 'v2.id', '=', 'pres_base_proyectos_detalle.baseproyectos_id')
            ->select(
                'v2.mes as mes',
                'v3.departamento as dep',
                DB::raw('round(100*sum(pres_base_proyectos_detalle.devengado)/sum(pres_base_proyectos_detalle.pim),2) as y'),
            )
            ->orderBy('mes','asc')
            ->orderBy('eje','desc')
            ->get();
        return $query;
    }

    public static function listado_ejecucion($reg) //base detallee
    {
        $query = BaseProyectosDetalle::whereIn('pres_base_proyectos_detalle.baseproyectos_id', $reg)
            ->join('pres_base_proyectos as v2', 'v2.id', '=', 'pres_base_proyectos_detalle.baseproyectos_id')
            ->join('pres_gobiernos_regionales as v3', 'v3.id', '=', 'pres_base_proyectos_detalle.gobiernosregionales_id')
            ->select(
                'v2.mes as mes',
                'v3.departamento as dep',
                DB::raw('round(100*(pres_base_proyectos_detalle.devengado)/(pres_base_proyectos_detalle.pim),5) as eje'),
            )
            ->orderBy('mes','asc')
            ->orderBy('eje','desc')
            ->get();
        return $query;
    }
}
