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
        if ($tipo > 0)
            return NivelModalidad::where('tipo', $tipo)->where('id', '!=', 15)->orderBy('tipo')->get();
        return NivelModalidad::where('id', '!=', 15)->orderBy('tipo')->get();
    }
}
