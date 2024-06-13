<?php

namespace App\Repositories\Salud;

use App\Models\Salud\Establecimiento;
use Illuminate\Support\Facades\DB;

class EstablecimientoRepositorio
{

    public static function listar($sector, $municipio)
    {
        $query = Establecimiento::from('sal_establecimiento as es')->select('re.nombre as red', 'mi.nombre as microred', 'es.cod_unico', 'es.nombre_establecimiento as eess')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->where('es.estado', 'ACTIVO')->where('re.codigo','!=', '00');
        if ($municipio > 0) $query = $query->where('ub.id', $municipio);
        // ->where('re.codigo', '03')
        $query = $query->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }
}
