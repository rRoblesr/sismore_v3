<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\Ubigeo;

class UbigeoRepositorio
{
    // public static function provincia25()
    // {
    //     $query = Ubigeo::select('v2.*')
    //         ->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')
    //         ->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
    //     return $query;
    // }
    // public static function distrito25($provincia)
    // {
    //     $query = Ubigeo::select('v3.*')
    //         ->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')
    //         ->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')
    //         ->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25');
    //     if ($provincia > 0)
    //         $query = $query->where('v3.dependencia', $provincia);
    //     $query = $query->get();
    //     return $query;
    // }

    public static function provincia($codigo)
    {
        $query = Ubigeo::select('v2.*')
            ->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')
            ->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', $codigo)->get();
        return $query;
    }
    public static function distrito($codigo, $provincia)
    {
        $query = Ubigeo::select('v3.*')
            ->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')
            ->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')
            ->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', $codigo);
        if ($provincia > 0) $query = $query->where('v3.dependencia', $provincia);
        $query = $query->get();
        return $query;
    }

    public static function buscar_PorNombre($nombre)
    {
        $query = Ubigeo::where('nombre', $nombre)->get();
        return $query;
    }
}
