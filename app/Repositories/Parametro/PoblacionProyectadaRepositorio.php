<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\PoblacionProyectada;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionProyectadaRepositorio
{
    public static function conteo($anio, $departamento, $etapavida, $sexo = 0)
    {
        $query = PoblacionProyectada::from('par_poblacion_proyectada as pr')->where('pr.anio', $anio);
        if ($etapavida > 0)
            $query = $query->join('par_grupoedad as ge', function ($join) use ($etapavida) {
                $join->on('ge.edad', '=', 'pr.edad')->where('ge.etapavida', $etapavida);
            });
        if ($departamento > '00')
            $query = $query->where('pr.codigo', $departamento);
        if ($sexo == 1)
            return $query->sum('pr.hombre');
        if ($sexo == 2)
            return $query->sum('pr.mujer');
        else
            return $query->sum('pr.total');
    }

    public static function conteo05($anio, $departamento, $etapavida, $sexo)
    {
        $query = PoblacionProyectada::from('par_poblacion_proyectada as pr')->where('pr.anio', $anio)->whereIn('pr.edad', [0, 1, 2, 3, 4, 5]);
        if ($etapavida > 0)
            $query = $query->join('par_grupoedad as ge', function ($join) use ($etapavida) {
                $join->on('ge.edad', '=', 'pr.edad')->where('ge.etapavida', $etapavida);
            });
        if ($departamento > '00')
            $query = $query->where('pr.codigo', $departamento);
        if ($sexo == 1)
            return $query->sum('pr.hombre');
        if ($sexo == 2)
            return $query->sum('pr.mujer');
        else
            return $query->sum('pr.total');
    }

    public static function conteo_anio_etapa($anio, $departamento)
    {
        $query = PoblacionProyectada::from('par_poblacion_proyectada as pr')->where('pr.anio', $anio);
        $query = $query->join('par_grupoedad as ge', function ($join) {
            $join->on('ge.edad', '=', 'pr.edad');
        });
        if ($departamento > '00')
            $query = $query->where('pr.codigo', $departamento);

        return $query->select('ge.etapavida', DB::raw('sum(total) as conteo'), DB::raw('sum(hombre) as hconteo'), DB::raw('sum(mujer) as mconteo'))->groupBy('ge.etapavida')->get();
    }

    public static function grupoetareo_sexo($anio, $departamento, $etapavida)
    {
        $query = PoblacionProyectada::from('par_poblacion_proyectada as pr')->select('pr.grupo_etareo', DB::raw('SUM(pr.hombre) hconteo'), DB::raw('SUM(pr.mujer) mconteo'))
            ->where('pr.anio', $anio);
        if ($etapavida > 0)
            $query = $query->join('par_grupoedad as ge', function ($join) use ($etapavida) {
                $join->on('ge.edad', '=', 'pr.edad')->where('ge.etapavida', $etapavida);
            });
        if ($departamento > '00')
            $query = $query->where('pr.codigo', $departamento);
        $query = $query->groupBy('pr.grupo_etareo')->orderBy('pr.grupo_etareo')->get();
        return $query;
    }

    public static function conteo_anios($departamento)
    {
        $query = PoblacionProyectada::select('anio', DB::raw('SUM(total) conteo'), DB::raw('SUM(hombre) hconteo'), DB::raw('SUM(mujer) mconteo'))->where('anio', '>', 2020);
        if ($departamento > '00')
            $query = $query->where('codigo', $departamento);
        $query = $query->groupBy('anio')->orderBy('anio')->get();
        return $query;
    }

    public static function conteo05_anios($departamento)
    {
        $query = PoblacionProyectada::select('anio', DB::raw('SUM(total) conteo'), DB::raw('SUM(hombre) hconteo'), DB::raw('SUM(mujer) mconteo'))->where('anio', '>', 2020)->whereIn('edad', [0, 1, 2, 3, 4, 5]);
        if ($departamento > '00')
            $query = $query->where('codigo', $departamento);
        $query = $query->groupBy('anio')->orderBy('anio')->get();
        return $query;
    }

    public static function conteo_anios_tabla1($anio)
    {
        $query = PoblacionProyectada::select(
            'departamento',
            DB::raw("sum(IF(anio=$anio,total,0)) as c2024t"),
            DB::raw("sum(IF(anio=$anio,hombre,0)) as c2024h"),
            DB::raw("sum(IF(anio=$anio,mujer,0)) as c2024m"),
            DB::raw('sum(IF(anio=2021,total,0)) as c2021'),
            DB::raw('sum(IF(anio=2022,total,0)) as c2022'),
            DB::raw('sum(IF(anio=2023,total,0)) as c2023'),
            DB::raw('sum(IF(anio=2024,total,0)) as c2024'),
            DB::raw('sum(IF(anio=2025,total,0)) as c2025'),
            DB::raw('sum(IF(anio=2026,total,0)) as c2026'),
            DB::raw('sum(IF(anio=2027,total,0)) as c2027'),
            DB::raw('sum(IF(anio=2028,total,0)) as c2028'),
            DB::raw('sum(IF(anio=2029,total,0)) as c2029'),
            DB::raw('sum(IF(anio=2030,total,0)) as c2030')
        )->where('anio', '>', 2020)->groupBy('departamento')->get();
        return $query;
    }

    public static function conteo_departamento($anio, $sexo)
    {
        return  $query = PoblacionProyectada::select('codigo', 'departamento', DB::raw('sum(total) as conteo'))->where('anio', $anio)->groupBy('codigo', 'departamento')->get();
    }


    // public static function conteo_provincia($anio, $departamento)
    // {
    //     $query = PoblacionProyectada::where('anio', $anio);
    //     if ($departamento != '')
    //         $query = $query->where('departamento', $departamento);
    //     return $query->groupBy('provincia')->sum('total');
    // }

    public static function total6a11($anio_id)
    {
        $query = PoblacionDetalle::select(DB::raw('sum(total) as conteo'))
            ->join('par_poblacion as pp', 'pp.id', '=', 'par_poblacion_detalle.poblacion_id')
            ->where('pp.anio_id', $anio_id)->whereIn('edad', ['e6', 'e7', 'e8', 'e9', 'e10', 'e11'])->where('ubigeo', 'like', '25%')->first()->conteo;
        return $query;
    }

    public static function total12a16($anio_id)
    {
        $query = PoblacionDetalle::select(DB::raw('sum(total) as conteo'))
            ->join('par_poblacion as pp', 'pp.id', '=', 'par_poblacion_detalle.poblacion_id')
            ->where('pp.anio_id', $anio_id)->whereIn('edad', ['e12', 'e13', 'e14', 'e15', 'e16'])->where('ubigeo', 'like', '25%')->first()->conteo;
        return $query;
    }
}
