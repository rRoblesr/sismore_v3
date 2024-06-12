<?php

namespace App\Repositories\Administracion;

use App\Models\Administracion\Entidad;
use Illuminate\Support\Facades\DB;

class EntidadRepositorio
{
    public static function getEntidadOficina($oficina)
    {
        $data = DB::table('adm_entidad as v1')
            ->join('adm_entidad as v2', 'v2.dependencia', '=', 'v1.id')
            ->select(
                'v1.nombre as entidad',
                'v1.abreviado as entidad_abre',
                'v2.nombre as oficina',
                'v2.abreviado as oficina_abre'
            )
            ->where('v2.id', $oficina)
            ->get()->first();
        return $data;
    }

    public static function migas($oficina)
    {
        $query = Entidad::select(
            'adm_entidad.id as oficina',
            'adm_entidad.nombre as oficinan',
            'ee.id as entidad',
            'ee.nombre as entidadn',
            'ee.codigo',
            'te.id as tipo',
            'ss.id as sector',
        )
            ->join('adm_entidad as ee', 'ee.id', '=', 'adm_entidad.dependencia')
            ->join('adm_tipo_entidad as te', 'te.id', '=', 'ee.tipoentidad_id')
            ->join('pres_sector as ss', 'ss.id', '=', 'te.sector_id')
            ->where('adm_entidad.id', $oficina)
            ->first();
        return $query;
    }

    public static function entidades($sector)
    {
        $query = Entidad::from('adm_entidad as ee')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'ee.tipoentidad_id')->where('te.sector_id', '=', $sector);
            })
            ->join('par_ubigeo as uu', 'uu.codigo', '=', 'ee.codigo')
            ->select('uu.id', 'ee.codigo', 'ee.nombre', 'ee.abreviado')
            ->orderBy('ee.nombre')->get();
        return $query;
    }
}
