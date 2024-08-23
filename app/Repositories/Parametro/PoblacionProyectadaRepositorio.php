<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\PoblacionProyectada;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionProyectadaRepositorio
{
    public static function conteo($anio, $departamento, $sexo = 0)
    {
        $query = PoblacionProyectada::where('anio', $anio);
        if ($departamento > '00')
            $query = $query->where('codigo', $departamento);
        if ($sexo == 1)
            return $query->sum('hombre');
        if ($sexo == 2)
            return $query->sum('mujer');
        else
            return $query->sum('total');
    }

    public static function conteo05($anio, $departamento, $sexo)
    {
        $query = PoblacionProyectada::where('anio', $anio)->whereIn('edad', [0, 1, 2, 3, 4, 5]);
        if ($departamento > '00')
            $query = $query->where('codigo', $departamento);
        if ($sexo == 1)
            return $query->sum('hombre');
        if ($sexo == 2)
            return $query->sum('mujer');
        else
            return $query->sum('total');
    }

    public static function grupoetareo_sexo($anio, $departamento)
    {
        $query = PoblacionProyectada::select('grupo_etareo', DB::raw('SUM(hombre) hconteo'), DB::raw('SUM(mujer) mconteo'))
            ->where('anio', $anio);
        if ($departamento > '00')
            $query = $query->where('codigo', $departamento);
        $query = $query->groupBy('grupo_etareo')->orderBy('grupo_etareo')->get();
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

    public static function conteo_anios_tabla1()
    {
        $query = PoblacionProyectada::select(
            'departamento',
            DB::raw('sum(IF(anio=2024,total,0)) as c2024h'),
            DB::raw('sum(IF(anio=2024,total,0)) as c2024m'),
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
