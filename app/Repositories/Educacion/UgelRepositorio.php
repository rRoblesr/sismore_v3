<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use Illuminate\Support\Facades\DB;

class UgelRepositorio
{
    public static function listar()
    {
        $query = Ugel::all();
        return $query;
    }
    public static function listar_opt()
    {
        $query = Ugel::select('id','codigo','nombre')->where('estado','AC')->orderBy('nombre','asc')->get();
        return $query;
    }
}
