<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\PoblacionProyectada;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionProyectadaRepositorio
{
    public static function conteo($anio, $departamento)
    {
        $query = PoblacionProyectada::where('anio', $anio);
        if ($departamento != '')
            $query = $query->where('departamento', $departamento);
        return $query->sum('total');
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