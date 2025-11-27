<?php

namespace App\Repositories\Educacion;

use Illuminate\Support\Facades\DB;

class CuboPadronEIBRepositorio
{
    public static function select_anios()
    {
        return DB::table('edu_cubo_padron_eib')
            ->select('anio_pw')
            ->distinct()
            ->orderBy('anio_pw', 'asc')
            ->pluck('anio_pw');
    }

    public static function select_gestion($anio)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('edu_ugel as tg', 'ceib.tipogestion_id', '=', 'tg.id')
            ->select('tg.id', 'tg.nombre')
            ->where('ceib.anio_pw', $anio)
            ->distinct()
            ->pluck('tg.nombre', 'tg.id');
    }

    public static function select_provincia($anio, $ugel)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('par_ubigeo as u', 'ceib.provincia_id', '=', 'u.id')
            ->select('u.id', 'u.nombre')
            ->where('ceib.anio_pw', $anio)
            ->when($ugel > 0, fn($query) => $query->where('ceib.tipogestion_id', $ugel))
            ->distinct()
            ->pluck('u.nombre', 'u.id');
    }

    public static function select_distrito($anio, $ugel, $provincia)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('par_ubigeo as u', 'ceib.distrito_id', '=', 'u.id')
            ->select('u.id', 'u.nombre')
            ->where('ceib.anio_pw', $anio)
            ->when($ugel > 0, fn($query) => $query->where('ceib.tipogestion_id', $ugel))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->distinct()
            ->pluck('u.nombre', 'u.id');
    }

    public static function reportesreporte_head($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib')
            ->selectRaw('count(*) as servicios, SUM(matriculados) AS matriculados, SUM(docentes) AS docentes, SUM(auxiliar) AS auxiliar, 
                         SUM(administrativo) AS administrativo, SUM(promotor) AS promotor, count(distinct lengua_id) AS lengua')
            ->where('anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('distrito_id', $distrito))
            ->groupBy('anio_pw')
            ->first();
    }

    public static function reportesreporte_anal1($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('par_ubigeo as p', 'p.id', '=', 'ceib.provincia_id')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select(
                DB::raw("
                    CASE 
                        WHEN p.codigo = '2501' THEN 'pe-uc-cp'
                        WHEN p.codigo = '2502' THEN 'pe-uc-at'
                        WHEN p.codigo = '2503' THEN 'pe-uc-pa'
                        WHEN p.codigo = '2504' THEN 'pe-uc-pr'
                    END AS codigo
                "),
                'p.nombre as provincia',
                DB::raw('COUNT(*) as conteo')
            )
            ->groupBy('p.codigo', 'p.nombre')
            ->get();
    }

    public static function reportesreporte_anal2($gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select(
                'anio_pw as anio',
                DB::raw('sum(matriculados) as matriculados')
            )
            ->groupBy('anio_pw')
            ->get();
    }

    public static function reportesreporte_anal3($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('par_ubigeo as p', 'p.id', '=', 'ceib.provincia_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ceib.nivelmodalidad_id')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL' ELSE nm.nombre END AS name"), DB::raw('sum(ceib.matriculados) as y')])
            ->groupBy(DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL'  ELSE nm.nombre  END"))
            ->get();
    }

    public static function reportesreporte_anal4($gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ceib.nivelmodalidad_id')
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([
                'anio_pw as anio',
                DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL' ELSE nm.nombre END AS name"),
                DB::raw('sum(matriculados) as y')
            ])
            ->groupBy(
                'anio_pw',
                DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL'  ELSE nm.nombre  END")
            )
            ->orderBy('anio_pw')
            ->get();
    }

    public static function reportesreporte_anal5($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([
                DB::raw('sum(ceib.nombrado) as nombrado'),
                DB::raw('sum(ceib.contratado) as contratado'),
            ])
            ->first();
    }



    public static function reportesreporte_anal6($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ceib.nivelmodalidad_id')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([
                DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL' ELSE nm.nombre END AS NIVEL"),
                DB::raw('sum(ceib.nombrado) as NOMBRADOS'),
                DB::raw('sum(ceib.contratado) as CONTRATADOS'),
            ])
            ->groupBy(DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL'  ELSE nm.nombre  END"))
            ->get();
    }

    public static function reportesreporte_tabla1($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ceib.nivelmodalidad_id')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([
                'ceib.forma_atencion',
                DB::raw('count(*) as ts'),
                DB::raw('count(case when ceib.area_id=1 then ceib.modular end) as tsu'),
                DB::raw('count(case when ceib.area_id=2 then ceib.modular end) as tsr'),
                DB::raw('sum(ceib.matriculados) as tm'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.matriculados end) as tmu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.matriculados end) as tmr'),
                DB::raw('sum(ceib.docentes) as td'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.docentes end) as tdu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.docentes end) as tdr'),
                DB::raw('sum(ceib.auxiliar) as ta'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.auxiliar end) as tau'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.auxiliar end) as tar'),
                DB::raw('sum(ceib.promotor) as tp'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.promotor end) as tpu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.promotor end) as tpr'),
            ])
            ->groupBy('forma_atencion')
            ->get();
    }


    public static function reportesreporte_tabla2($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ceib.nivelmodalidad_id')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([
                DB::raw("CASE 
                            WHEN LOWER(nm.nombre) LIKE '%programa%' THEN 'PRONOEI' 
                            ELSE nm.nombre 
                        END AS nivel_modalidad"),
                DB::raw('count(*) as ts'),
                DB::raw('count(case when ceib.area_id=1 then ceib.modular end) as tsu'),
                DB::raw('count(case when ceib.area_id=2 then ceib.modular end) as tsr'),
                DB::raw('sum(ceib.matriculados) as tm'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.matriculados end) as tmu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.matriculados end) as tmr'),
                DB::raw('sum(ceib.docentes) as td'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.docentes end) as tdu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.docentes end) as tdr'),
                DB::raw('sum(ceib.auxiliar) as ta'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.auxiliar end) as tau'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.auxiliar end) as tar'),
                DB::raw('sum(ceib.promotor) as tp'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.promotor end) as tpu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.promotor end) as tpr'),
            ])
            ->groupBy(DB::raw("CASE 
                                    WHEN LOWER(nm.nombre) LIKE '%programa%' THEN 'PRONOEI' 
                                    ELSE nm.nombre 
                                END"))
            ->orderBy(DB::raw(" CASE 
                                    WHEN nivel_modalidad = 'INICIAL - CUNA-JARDIN' THEN 1
                                    WHEN nivel_modalidad = 'INICIAL-JARDIN' THEN 2
                                    WHEN nivel_modalidad = 'PRONOEI' THEN 3
                                    WHEN nivel_modalidad = 'PRIMARIA' THEN 4
                                    WHEN nivel_modalidad = 'SECUNDARIA' THEN 5
                                    ELSE 6
                                END"))
            ->get();
    }


    public static function reportesreporte_tabla3($anio, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_cubo_padron_eib as ceib')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ceib.nivelmodalidad_id')
            ->join('par_lengua as l', 'l.id', '=', 'ceib.lengua_id')
            ->where('ceib.anio_pw', $anio)
            ->when($gestion > 0, fn($query) => $query->where('ceib.tipogestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('ceib.provincia_id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('ceib.distrito_id', $distrito))
            ->select([
                'l.nombre as lengua',
                DB::raw('count(*) as ts'),
                DB::raw('count(case when ceib.area_id=1 then ceib.modular end) as tsu'),
                DB::raw('count(case when ceib.area_id=2 then ceib.modular end) as tsr'),
                DB::raw('sum(ceib.matriculados) as tm'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.matriculados end) as tmu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.matriculados end) as tmr'),
                DB::raw('sum(ceib.docentes) as td'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.docentes end) as tdu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.docentes end) as tdr'),
                DB::raw('sum(ceib.auxiliar) as ta'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.auxiliar end) as tau'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.auxiliar end) as tar'),
                DB::raw('sum(ceib.promotor) as tp'),
                DB::raw('sum(case when ceib.area_id=1 then ceib.promotor end) as tpu'),
                DB::raw('sum(case when ceib.area_id=2 then ceib.promotor end) as tpr'),
            ])
            ->groupBy('l.nombre')
            ->get();
    }
}
