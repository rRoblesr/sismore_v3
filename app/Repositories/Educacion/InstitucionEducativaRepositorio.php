<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\InstitucionEducativa;
use Illuminate\Support\Facades\DB;

class InstitucionEducativaRepositorio
{

    public static function InstitucionEducativa_porCodModular($codModular)
    {
        $data = InstitucionEducativa::select('id')
            ->where("codModular", "=", $codModular)
            ->where("anexo", "=", 0)
            ->get();

        return $data;
    }
    public static function buscariiee2($term)
    {
        $query = InstitucionEducativa::select(
            'edu_institucioneducativa.id',
            'edu_institucioneducativa.codModular as codigo_modular',
            'edu_institucioneducativa.es_eib as estado',
            'edu_institucioneducativa.codLocal as codigo_local',
            'edu_institucioneducativa.nombreInstEduc as iiee',
            'v5.nombre as provincia',
            'v4.nombre as distrito',
            'v3.nombre as centro_poblado',
            'v6.codigo as codigo_nivel',
            'v6.nombre as nivel_modalidad',
            'v7.nombre as ugel',
        )
            ->join('par_centropoblado as v3', 'v3.id', '=', 'edu_institucioneducativa.centropoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_nivelmodalidad as v6', 'v6.id', '=', 'edu_institucioneducativa.nivelmodalidad_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'edu_institucioneducativa.Ugel_id')
            //->where('edu_institucioneducativa.es_eib','!=','SI')
            ->where(DB::raw("concat(' ',edu_institucioneducativa.codModular,edu_institucioneducativa.nombreInstEduc)"), 'like', "%$term%")
            ->get();
        return $query;
    }

    public static function buscariiee_id($id)
    {
        $query = InstitucionEducativa::select(
            'edu_institucioneducativa.id',
            'edu_institucioneducativa.codModular as codigo_modular',
            'edu_institucioneducativa.es_eib as estado',
            'edu_institucioneducativa.codLocal as codigo_local',
            'edu_institucioneducativa.nombreInstEduc as iiee',
            'v5.nombre as provincia',
            'v4.nombre as distrito',
            'v3.nombre as centro_poblado',
            'v6.codigo as codigo_nivel',
            'v6.nombre as nivel_modalidad',
            'v7.nombre as ugel',
        )
            ->join('par_centropoblado as v3', 'v3.id', '=', 'edu_institucioneducativa.centropoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_nivelmodalidad as v6', 'v6.id', '=', 'edu_institucioneducativa.nivelmodalidad_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'edu_institucioneducativa.Ugel_id')
            ->where('edu_institucioneducativa.id', $id)
            ->get();
        return $query;
    }
}
