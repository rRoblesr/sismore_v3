<?php

namespace App\Repositories\Salud;

use App\Models\Salud\CalidadCriterio;
use Illuminate\Support\Facades\DB;

class CalidadCriterioRepositorio
{
    public static function red_select($importacion)
    {
        $query = CalidadCriterio::from('sal_calidad_criterio as cc')
            ->join('sal_red as r', 'r.id', '=', 'cc.red_id')
            ->where('cc.importacion_id', $importacion)
            ->whereIn('cc.red_id', [9, 10, 11, 12])
            ->select('r.id', 'r.codigo', 'r.nombre')
            ->groupBy('r.id', 'r.codigo', 'r.nombre')
            ->get();
        return $query;
    }

    public static function microrred_select_mensual($anio, $mes, $red)
    {
        $query = CalidadCriterio::from('sal_calidad_criterio as cc')
            ->join('sal_microrred as mr', 'mr.id', '=', 'cc.microred_id')
            ->join('par_importacion as i', 'i.id', '=', 'cc.importacion_id')
            ->whereYear('i.fechaActualizacion', $anio)
            ->whereMonth('i.fechaActualizacion', $mes)
            ->whereIn('cc.red_id', [9, 10, 11, 12]);
        if ($red > 0) {
            $query = $query->where('mr.red_id', $red);
        }
        $query = $query->select('mr.id', 'mr.codigo', 'mr.nombre')->groupBy('mr.id', 'mr.codigo', 'mr.nombre')->get();
        return $query;
    }

    public static function TableroCalidadEESS_tabla01($importacion, $red, $microrred, $eess)
    {
        $filtros = function ($query) use ($red, $microrred,$eess) {
            if ($red > 0) $query->where('red_id', $red);
            if ($microrred > 0) $query->where('microred_id', $microrred);
            if ($eess > 0) $query->where('establecimiento_id', $eess);
        };
        return CalidadCriterio::where('importacion_id', $importacion)
            ->whereIn('red_id', [9, 10, 11, 12])
            ->join('sal_calidad_criterio_nombres as c', 'c.id', '=', 'sal_calidad_criterio.criterio')
            ->select(
                'c.id as criterio_id',
                'c.nombre as criterio',
                DB::raw('count(*) as total'),
                DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
            )
            ->whereNotIn('criterio', [7, 8, 9])
            ->tap($filtros)
            ->groupBy('criterio_id', 'criterio', 'c.nombre')
            ->orderBy('pos')
            ->get();
    }

    public static function TableroCalidadEESS_Criterio_tabla01($importacion, $criterio, $edad, $red, $microrred)
    {
        $filtros = function ($query) use ($edad, $red, $microrred) {
            if ($red > 0) $query->where('red_id', $red);
            if ($microrred > 0) $query->where('microred_id', $microrred);
            if ($edad > 0) {
                if ($edad == 1) {
                    $query->whereIn('tipo_edad', ['D', 'M']);
                } else {
                    $query->where('tipo_edad', 'A')->where('edad', $edad - 1);
                }
            }
        };
        return CalidadCriterio::where('importacion_id', $importacion)
            ->where('criterio', $criterio)
            ->tap($filtros)
            ->whereIn('red_id', [9, 10, 11, 12])
            ->get();
    }

    public static function TableroCalidadEESS_Criterio_anal1($importacion, $criterio, $edad, $red, $microrred)
    {
        $filtros = function ($query) use ($edad, $red, $microrred) {
            if ($red > 0) $query->where('red_id', $red);
            if ($microrred > 0) $query->where('microred_id', $microrred);
            if ($edad > 0) {
                if ($edad == 1) {
                    $query->whereIn('tipo_edad', ['D', 'M']);
                } else {
                    $query->where('tipo_edad', 'A')->where('edad', $edad - 1);
                }
            }
        };
        return CalidadCriterio::select(
            DB::raw('case when tipo_edad in("D","M") then 1 else edad+1 end as edades_id'),
            DB::raw('case when tipo_edad in("D","M") then "< 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
            DB::raw('count(*) as total'),
        )
            ->where('importacion_id', $importacion)
            ->where('criterio', $criterio)
            ->whereIn('red_id', [9, 10, 11, 12])
            ->tap($filtros)
            ->groupBy('edades_id', 'edades')
            ->get();
    }

    public static function TableroCalidadEESS_Criterio_anal2($importacion, $criterio, $edad, $red, $microrred)
    {
        $filtros = function ($query) use ($edad, $red, $microrred) {
            if ($red > 0) $query->where('red_id', $red);
            if ($microrred > 0) $query->where('microred_id', $microrred);
            if ($edad > 0) {
                if ($edad == 1) {
                    $query->whereIn('tipo_edad', ['D', 'M']);
                } else {
                    $query->where('tipo_edad', 'A')->where('edad', $edad - 1);
                }
            }
        };
        return CalidadCriterio::select(
            DB::raw('case when tipo_edad in("D","M") then 1 else edad+1 end as edades_id'),
            DB::raw('case when tipo_edad in("D","M") then "< 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
            DB::raw('count(*) as total'),
        )
            ->where('importacion_id', $importacion)
            ->where('criterio', $criterio)
            ->whereIn('red_id', [9, 10, 11, 12])
            ->tap($filtros)
            ->groupBy('edades_id', 'edades')
            ->get();
    }
}
