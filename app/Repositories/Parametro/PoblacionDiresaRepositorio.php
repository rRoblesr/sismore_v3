<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\PoblacionDiresa;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionDiresaRepositorio
{


    public static function conteo_suma($anio, $provincia, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')
            ->join('par_importacion as im', function ($join) use ($anio) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $anio);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->whereNotIn('edad', ['28 dias', '0-5 meses', '6-11 meses', 'nacimientos', 'gestantes']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        return $query->sum('pd.total');
    }

    public static function conteo_provincia_suma($anio, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')
            ->select('pv.codigo', 'pv.nombre as provincia', DB::raw('sum(total) as conteo'))
            ->join('par_importacion as im', function ($join) use ($anio) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $anio);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->whereNotIn('edad', ['28 dias', '0-5 meses', '6-11 meses', 'nacimientos', 'gestantes']);

        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        return $query->groupBy('codigo', 'provincia')->get();
    }

    public static function conteo05_suma($anio, $provincia, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')
            ->join('par_importacion as im', function ($join) use ($anio) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $anio);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->whereIn('edad', ['0', '1', '2', '3', '4', '5']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        return $query->sum('pd.total');
    }

    public static function grupoetareo_sexo($anio, $provincia, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')->select('pd.grupo_etareo', DB::raw('SUM(if(sexo_id=1,pd.total,0)) hconteo'), DB::raw('SUM(if(sexo_id=2,pd.total,0)) mconteo'))
            ->join('par_importacion as im', function ($join) use ($anio) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $anio);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->whereNotIn('edad', ['28 dias', '0-5 meses', '6-11 meses', 'nacimientos', 'gestantes']);;
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        $query = $query->groupBy('grupo_etareo')->orderBy('grupo_etareo')->get();
        return $query;
    }

    public static function conteo_anios_suma($provincia, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')
            ->select(DB::raw('year(fechaActualizacion) as anio'), DB::raw('sum(total) as conteo'), DB::raw('SUM(if(sexo_id=1,pd.total,0)) hconteo'), DB::raw('SUM(if(sexo_id=2,pd.total,0)) mconteo'))
            ->join('par_importacion as im', 'im.id', '=', 'pd.importacion_id')
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->whereNotIn('edad', ['28 dias', '0-5 meses', '6-11 meses', 'nacimientos', 'gestantes']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        return $query->groupBy('anio')->get();
    }

    public static function etapavida($anio, $provincia, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')->select('pd.etapa_vida', DB::raw('SUM(pd.total) conteo'), DB::raw('SUM(if(sexo_id=1,pd.total,0)) hconteo'), DB::raw('SUM(if(sexo_id=2,pd.total,0)) mconteo'))
            ->join('par_importacion as im', function ($join) use ($anio) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $anio);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->whereNotIn('edad', ['28 dias', '0-5 meses', '6-11 meses', 'nacimientos', 'gestantes']);;
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        $query = $query->groupBy('etapa_vida')->orderByRaw('FIELD("NIÑO","ADOLECENTE","JOVEN","ADULTO","ADULTO MAYOR")')->get();
        return $query;
    }

    public static function listar_distrito_sexo_edad($anio, $provincia, $distrito, $sexo = 0)
    {
        $query = PoblacionDiresa::from('par_poblacion_diresa as pd')->select(
            'ds.nombre as distrito',
            DB::raw('SUM(if(              edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as conteo'),
            DB::raw('SUM(if(sexo_id=1 and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as hconteo'),
            DB::raw('SUM(if(sexo_id=2 and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as mconteo'),
            DB::raw('SUM(if(etapa_vida="NIÑO"         and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as ev1'),
            DB::raw('SUM(if(etapa_vida="ADOLESCENTE"  and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as ev2'),
            DB::raw('SUM(if(etapa_vida="JOVEN"        and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as ev3'),
            DB::raw('SUM(if(etapa_vida="ADULTO"       and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as ev4'),
            DB::raw('SUM(if(etapa_vida="ADULTO MAYOR" and edad not in("28 dias","0-5 meses","6-11 meses","nacimientos","gestantes"),pd.total,0)) as ev5'),
            DB::raw('SUM(if(edad="28 dias",pd.total,0))   as nacimiento'),
            DB::raw('SUM(if(edad="gestantes",pd.total,0)) as gestante'),
            DB::raw('SUM(if(sexo_id=2 and grupo_etareo in("10-14","15-19","20-24","25-29","30-34","35-39","40-44","45-49"),pd.total,0)) as fertiles'),
        )
            ->join('par_importacion as im', function ($join) use ($anio) {
                $join->on('im.id', '=', 'pd.importacion_id')->where(DB::raw('year(fechaActualizacion)'), $anio);
            })
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pd.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia');
        // ->whereNotIn('edad', ['28 dias', '0-5 meses', '6-11 meses', 'nacimientos', 'gestantes']);;
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        if ($sexo > 0) $query = $query->where('sexo_id', $sexo);
        $query = $query->groupBy('distrito')->get();
        return $query;
    }
}
