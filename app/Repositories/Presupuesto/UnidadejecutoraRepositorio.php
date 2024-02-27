<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\UnidadEjecutora;
use Illuminate\Support\Facades\DB;

class UnidadejecutoraRepositorio
{
    public static function gobiernoslocales($anio, $articulo, $ue, $cg) //base detallee
    {
        $query = UnidadEjecutora::select('pres_unidadejecutora.id', 'pres_unidadejecutora.abreviatura as nombre', 'pres_unidadejecutora.codigo_ue as codigo')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();
        return [];
    }
}
