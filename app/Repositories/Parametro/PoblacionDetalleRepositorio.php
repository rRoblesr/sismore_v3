<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionDetalleRepositorio
{
    public static function total3a5($anio_id)
    {
        $query = PoblacionDetalle::select(DB::raw('sum(total) as conteo'))
            ->join('par_poblacion as pp', 'pp.id', '=', 'par_poblacion_detalle.poblacion_id')
            ->where('pp.anio_id', $anio_id)->whereIn('edad', ['e3', 'e4', 'e5'])->where('ubigeo', 'like', '25%')->first()->conteo;
        return $query;
    }

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
