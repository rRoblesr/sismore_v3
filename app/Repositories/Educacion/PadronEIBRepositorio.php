<?php

namespace App\Repositories\Educacion;

use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Http\Controllers\Educacion\ImporPadronEibController;
use App\Http\Controllers\Educacion\ImporPadronWebController;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\PadronEIB;
use Illuminate\Support\Facades\DB;

class PadronEIBRepositorio
{
    public static function listaImportada($id)
    {
        /* $query = DB::table(DB::raw("(
            select
                v1.id,
                v2.anio,
                v5.nombre dre,
                v4.nombre ugel,
                'UCAYALI' departamento,
                v8.nombre provincia,
                v7.nombre distrito,
                v6.nombre centro_poblado,
                v3.codModular cod_mod,
                v3.codLocal cod_local,
                v3.nombreInstEduc institucion_educativa,
                v9.codigo cod_nivelmod,
                v9.nombre nivel_modalidad,
                v1.forma_atencion,
                v1.cod_lengua,
                v1.lengua_uno,
                v1.lengua_dos,
                v1.lengua_3,
                v1.ingreso
            from edu_padron_eib v1
            join par_anio v2 on v2.id=v1.anio_id
            join edu_institucioneducativa v3 on v3.id=v1.institucioneducativa_id
            join edu_ugel v4 on v4.id=v3.Ugel_id
            join edu_ugel v5 on v5.id=v4.dependencia
            join edu_centropoblado v6 on v6.id=v3.CentroPoblado_id
            join par_ubigeo v7 on v7.id=v6.Ubigeo_id
            join par_ubigeo v8 on v8.id=v7.dependencia
            join edu_nivelmodalidad v9 on v9.id=v3.NivelModalidad_id
            where v1.importacion_id=$id order by v1.id desc
        ) as tb"))->get(); */
        $query = PadronEIB::select(
            'edu_padron_eib.id',
            'v2.anio',
            'v5.nombre as dre',
            'v4.nombre as ugel',
            //DB::raw("UCAYALI as departamento"),
            'v8.nombre as provincia',
            'v7.nombre as distrito',
            'v6.nombre as centro_poblado',
            'v3.codModular as cod_mod',
            'v3.codLocal as cod_local',
            'v3.nombreInstEduc as institucion_educativa',
            'v9.codigo as cod_nivelmod',
            'v9.nombre as nivel_modalidad',
            'edu_padron_eib.forma_atencion',
            'va.nombre as lengua1',
            'vb.nombre as lengua2',
            'vc.nombre as lengua3',
        )
            ->join('par_anio as v2', 'v2.id', '=', 'edu_padron_eib.anio_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_eib.institucioneducativa_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v3.Ugel_id')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_centropoblado as v6', 'v6.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v7', 'v7.id', '=', 'v6.Ubigeo_id')
            ->join('par_ubigeo as v8', 'v8.id', '=', 'v7.dependencia')
            ->join('edu_nivelmodalidad as v9', 'v9.id', '=', 'v3.NivelModalidad_id')
            ->join('par_lengua as va', 'va.id', '=', 'edu_padron_eib.lengua1_id', 'left')
            ->join('par_lengua as vb', 'vb.id', '=', 'edu_padron_eib.lengua2_id', 'left')
            ->join('par_lengua as vc', 'vc.id', '=', 'edu_padron_eib.lengua3_id', 'left')
            ->where('edu_padron_eib.importacion_id', $id)
            ->orderBy('edu_padron_eib.id', 'desc')
            ->get();
        return $query;
    }

    public static function listaImportada2($anio, $ugel, $nivel)
    {
        $query = PadronEIB::select(
            'edu_padron_eib.id',
            'v2.anio',
            //'v5.nombre as dre',
            'v4.nombre as ugel',
            //DB::raw("UCAYALI as departamento"),
            'v8.nombre as provincia',
            'v7.nombre as distrito',
            'v6.nombre as centro_poblado',
            'v3.codModular as cod_mod',
            'v3.codLocal as cod_local',
            'v3.nombreInstEduc as institucion_educativa',
            'v9.codigo as cod_nivelmod',
            'v9.nombre as nivel_modalidad',
            'edu_padron_eib.forma_atencion',
            'va.nombre as lengua1',
            'vb.nombre as lengua2',
            'vc.nombre as lengua3',
        )
            ->join('par_anio as v2', 'v2.id', '=', 'edu_padron_eib.anio_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_eib.institucioneducativa_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v3.Ugel_id')
            //->join('edu_ugel as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_centropoblado as v6', 'v6.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v7', 'v7.id', '=', 'v6.Ubigeo_id')
            ->join('par_ubigeo as v8', 'v8.id', '=', 'v7.dependencia')
            ->join('edu_nivelmodalidad as v9', 'v9.id', '=', 'v3.NivelModalidad_id')
            ->join('par_lengua as va', 'va.id', '=', 'edu_padron_eib.lengua1_id', 'left')
            ->join('par_lengua as vb', 'vb.id', '=', 'edu_padron_eib.lengua2_id', 'left')
            ->join('par_lengua as vc', 'vc.id', '=', 'edu_padron_eib.lengua3_id', 'left')
            //->where('edu_padron_eib.importacion_id', $id)
            ->orderBy('edu_padron_eib.id', 'desc');
        if ($anio != 0) $query = $query->where('v2.id', $anio);
        if ($nivel != 0) $query = $query->where('v9.id', $nivel);
        if ($ugel != 0) $query = $query->where('v4.id', $ugel);
        $query = $query->get();
        return $query;
    }

    public static function maxid()
    {
        return PadronEIB::select('v1.id')
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_padron_eib.importacion_id')
            ->orderBy('v1.fechaActualizacion', 'desc')->first()->id;
    }

    public static function rango_anios_segun_eib()
    {
        $minYear = Importacion::where('fuenteImportacion_id', ImporPadronEibController::$FUENTE)->where('estado', 'PR')->min(\DB::raw('YEAR(fechaActualizacion)'));
        $maxYear = Importacion::where('fuenteImportacion_id', ImporPadronWebController::$FUENTE)->where('estado', 'PR')->max(\DB::raw('YEAR(fechaActualizacion)'));
        return range($minYear, $maxYear);
    }

    public static function generarMapaAniosEib()
    {
        // Obtener rango global (puedes usar tu método existente o este simple)
        $minYear = \DB::table('par_importacion')->whereIn('fuenteImportacion_id', [ImporPadronEibController::$FUENTE, ImporMatriculaGeneralController::$FUENTE])->where('estado', 'PR')->min(\DB::raw('YEAR(fechaActualizacion)'));

        $maxYear = \DB::table('par_importacion')
            ->whereIn('fuenteImportacion_id', [ImporPadronEibController::$FUENTE, ImporMatriculaGeneralController::$FUENTE])
            ->where('estado', 'PR')
            ->max(\DB::raw('YEAR(fechaActualizacion)'));

        $minYear = $minYear ?: 2019;
        $maxYear = max($maxYear ?: 2025, 2025);

        $mapa = [];
        for ($year = $maxYear; $year >= $minYear; $year--) {
            $mapa[$year] = self::mapearAnioAEib($year);
        }

        return $mapa;
    }
}
