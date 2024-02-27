<?php

namespace App\Repositories\Administracion;

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
}
