<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\NivelModalidad;
use Illuminate\Support\Facades\DB;

class NivelModalidadRepositorio
{
    public static function distinct_tipo()
    {
        $query = NivelModalidad::whereNotNull('tipo')
            ->distinct()
            ->select('tipo')
            ->orderBy('tipo')
            ->get();
        return $query;
    }

    public static function buscarportipo($tipo)
    {
        return NivelModalidad::where('tipo', $tipo)->get();
    }
}
