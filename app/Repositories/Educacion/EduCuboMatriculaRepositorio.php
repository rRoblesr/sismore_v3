<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\EduCuboMatricula;
use Illuminate\Support\Facades\DB;

class EduCuboMatriculaRepositorio
{
    public static function total_anio($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio);

        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            $query->where('id_gestion', $gestion);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->count();
    }

    public static function modalidad_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('modalidad', DB::raw('count(id) as conteo'))->where('anio', $anio);

        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            $query->where('id_gestion', $gestion);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        $resultados = $query->groupBy('modalidad')
            ->orderBy('id_mod')
            ->get();

        return $resultados;
    }

    public static function ebr_nivel_incial_primaria_secundaria($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = DB::table('edu_cubo_matricula as ecm')
            ->select(
                'anio',
                DB::raw("
                CASE 
                    WHEN id_nivel IN ('A2', 'A3', 'A5') THEN 'INICIAL' 
                    WHEN id_nivel = 'B0' THEN 'PRIMARIA' 
                    WHEN id_nivel = 'F0' THEN 'SECUNDARIA' 
                END AS nivel_nombre
            "),
                DB::raw('COUNT(*) AS conteo')
            )
            ->where('modalidad', 'ebr');

        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            $query->where('id_gestion', $gestion);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        $resultados = $query->groupBy('anio', 'nivel_nombre')
            ->orderBy('anio')
            ->orderBy('nivel_nombre')
            ->get();

        return $resultados;
    }

    public static function total_anio_ugel($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_ugel as idugel', 'ugel', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio);

        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            $query->where('id_gestion', $gestion);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->groupBy('id_ugel', 'ugel')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function total_anio_ugel_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select(
            'id_ugel as idugel',
            'ugel',
            DB::raw('COUNT(*) AS tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(modalidad="EBR" and id_sexo=1,1,0)) as EBRth'),
            DB::raw('sum(IF(modalidad="EBR" and id_sexo=2,1,0)) as EBRtm'),
            DB::raw('sum(IF(modalidad="EBE" and id_sexo=1,1,0)) as EBEth'),
            DB::raw('sum(IF(modalidad="EBE" and id_sexo=2,1,0)) as EBEtm'),
            DB::raw('sum(IF(modalidad="EBA" and id_sexo=1,1,0)) as EBAth'),
            DB::raw('sum(IF(modalidad="EBA" and id_sexo=2,1,0)) as EBAtm'),
        )->where('anio', $anio);

        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            $query->where('id_gestion', $gestion);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->groupBy('id_ugel', 'ugel')
            ->orderByDesc('tt')
            ->get();
    }
}
