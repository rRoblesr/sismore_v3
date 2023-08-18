<?php

namespace App\Repositories\Presupuesto;

use Illuminate\Support\Facades\DB;

class EntidadRepositorio
{
    public static function getEntidadOficina($oficina)
    {
        $data = DB::table('adm_entidad as v1')
            ->join('adm_entidad as v2', 'v2.dependencia', '=', 'v1.id')
            //->join('adm_ent//idad as v3', 'v3.dependencia', '=', 'v2.id')
            ->select(
                'v1.nombre as entidad',
                'v1.apodo as entidad_abre',
                //'v2.nombre as gerente',
                //'v2.apodo as gerente_abre',
                'v2.nombre as oficina',
                'v2.apodo as oficina_abre'
            )
            ->where('v2.id', $oficina)
            ->get()->first();
        return $data;
    }
}
