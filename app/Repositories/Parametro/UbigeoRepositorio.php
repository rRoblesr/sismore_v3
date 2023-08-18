<?php

namespace App\Repositories\Parametro;

use App\Models\Ubigeo;

class UbigeoRepositorio
{
    public static function buscar_provincia1()
    {
        $query = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
        return $query;
    }
    public static function buscar_distrito1($provincia)
    { //$distritos = Ubigeo::where('codigo', 'like', $provincia . '%')->whereRaw('LENGTH(codigo)=6')->get();
        $query = Ubigeo::where('dependencia', $provincia)->get();
        return $query;
    }

    public static function buscar_PorNombre($nombre)
    { 
        //$distritos = Ubigeo::where('codigo', 'like', $provincia . '%')->whereRaw('LENGTH(codigo)=6')->get();
        $query = Ubigeo::where('nombre', $nombre)->get();

        return $query;
    }
}
