<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\EduCuboMatricula;
use Illuminate\Support\Facades\DB;

class EduCuboMatriculaRepositorio
{
    public static function anio_max()
    {
        return EduCuboMatricula::select(DB::raw('max(anio) as anio'))->first();
    }

    public static function listar_anios()
    {
        return EduCuboMatricula::distinct()->select('anio')->get();
    }

    public static function importacion($anio)
    {
        return EduCuboMatricula::distinct()->select('importacion_id')->where('anio', $anio)->first()->importacion_id;
    }

    public static function listar_ugel($anio)
    {
        return EduCuboMatricula::select('id_ugel as id', 'ugel as nombre')
            ->where('anio', $anio)
            ->groupBy('id_ugel', 'ugel')
            ->orderBy('id_ugel')
            ->get();
    }

    public static function listar_area($anio)
    {
        return EduCuboMatricula::select('id_area as id', 'area as nombre')
            ->where('anio', $anio)
            ->groupBy('id_area', 'area')
            ->orderBy('id_area')
            ->get();
    }

    public static function listar_distrito($anio)
    {
        return EduCuboMatricula::select('id_distrito as id', 'distrito as nombre')
            ->where('anio', $anio)
            ->groupBy('id_distrito', 'distrito')
            ->orderBy('id_distrito')
            ->get();
    }

    public static function listar_gestion($anio)
    {
        return EduCuboMatricula::select('id_gestion as id', 'gestion as nombre')
            ->where('anio', $anio)->whereIn('id_gestion', [2, 3])
            ->groupBy('id_gestion', 'gestion')
            ->orderBy('id_gestion')
            ->get();
    }

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

        if ($anio > 0) {
            $query->where('anio', $anio);
        }

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

    public static function modalidad_total_anios($modalidad, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('anio', DB::raw('COUNT(*) AS suma'));

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

        $resultados = $query->groupBy('anio')->orderBy('anio')->get();

        return $resultados;
    }

    public static function modalidad_total_anio_meses($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('mes', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->groupBy('mes')
            ->orderBy('mes');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }
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

        $resultados = $query->get();

        $mesesConDatos = [];
        foreach ($resultados as $fila) {
            $mesesConDatos[(int)$fila->mes] = $fila->conteo;
        }

        $acumulado = 0;
        $datos = [];

        for ($mes = 1; $mes <= 12; $mes++) {
            if (isset($mesesConDatos[$mes])) {
                $conteo = $mesesConDatos[$mes];
                $acumulado += $conteo;
            } else {
                $conteo = null;
            }

            $datos[] = [
                'mes' => $mes,
                'conteo' => $conteo,
                'acumulado' => $conteo !== null ? $acumulado : null
            ];
        }

        return collect($datos);
    }

    public static function modalidad_total_anio_sexo($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_sexo', 'sexo as name', DB::raw('COUNT(*) AS y'))
            ->where('anio', $anio)
            ->groupBy('id_sexo', 'sexo')
            ->orderBy('id_sexo');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

        return $query->get();
    }

    public static function modalidad_total_anio_area($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_area', 'area as name', DB::raw('COUNT(*) AS y'))
            ->where('anio', $anio)
            ->groupBy('id_area', 'area')
            ->orderBy('id_area');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

        return $query->get();
    }

    public static function modalidad_total_anio_ugel($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_ugel as idugel', 'ugel', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio);

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

    public static function modalidad_total_anio_ugel_mes($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_ugel',
            'ugel',
            DB::raw("SUM(IF(mes = 1, 1, 0)) AS ene"),
            DB::raw("SUM(IF(mes = 2, 1, 0)) AS feb"),
            DB::raw("SUM(IF(mes = 3, 1, 0)) AS mar"),
            DB::raw("SUM(IF(mes = 4, 1, 0)) AS abr"),
            DB::raw("SUM(IF(mes = 5, 1, 0)) AS may"),
            DB::raw("SUM(IF(mes = 6, 1, 0)) AS jun"),
            DB::raw("SUM(IF(mes = 7, 1, 0)) AS jul"),
            DB::raw("SUM(IF(mes = 8, 1, 0)) AS ago"),
            DB::raw("SUM(IF(mes = 9, 1, 0)) AS sep"),
            DB::raw("SUM(IF(mes = 10, 1, 0)) AS oct"),
            DB::raw("SUM(IF(mes = 11, 1, 0)) AS nov"),
            DB::raw("SUM(IF(mes = 12, 1, 0)) AS dic")
        ])
            ->where('anio', $anio)
            ->groupBy('id_ugel', 'ugel')
            ->orderBy('ugel');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

        return $query->get();
    }

    public static function matricula_con_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $baseQuery = DB::table('edu_cubo_matricula')
            ->select([
                'id_ugel',
                'ugel',
                DB::raw("SUM(IF(mes = 1, 1, 0)) AS m01"),
                DB::raw("SUM(IF(mes = 2, 1, 0)) AS m02"),
                DB::raw("SUM(IF(mes = 3, 1, 0)) AS m03"),
                DB::raw("SUM(IF(mes = 4, 1, 0)) AS m04"),
                DB::raw("SUM(IF(mes = 5, 1, 0)) AS m05"),
                DB::raw("SUM(IF(mes = 6, 1, 0)) AS m06"),
                DB::raw("SUM(IF(mes = 7, 1, 0)) AS m07"),
                DB::raw("SUM(IF(mes = 8, 1, 0)) AS m08"),
                DB::raw("SUM(IF(mes = 9, 1, 0)) AS m09"),
                DB::raw("SUM(IF(mes = 10, 1, 0)) AS m10"),
                DB::raw("SUM(IF(mes = 11, 1, 0)) AS m11"),
                DB::raw("SUM(IF(mes = 12, 1, 0)) AS m12")
            ])
            ->where('anio', $anio);

        // Aplicar filtros si existen
        $baseQuery->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia));
        $baseQuery->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito));
        $baseQuery->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion));
        $baseQuery->when($area > 0, fn($q) => $q->where('id_area', $area));

        $baseQuery->groupBy('id_ugel', 'ugel');

        // Subconsulta para el total general
        $totalQuery = DB::table('edu_cubo_matricula')
            ->select([
                DB::raw("'TOTAL' AS id_ugel"),
                DB::raw("'TOTAL GENERAL' AS ugel"),
                DB::raw("SUM(IF(mes = 1, 1, 0)) AS m01"),
                DB::raw("SUM(IF(mes = 2, 1, 0)) AS m02"),
                DB::raw("SUM(IF(mes = 3, 1, 0)) AS m03"),
                DB::raw("SUM(IF(mes = 4, 1, 0)) AS m04"),
                DB::raw("SUM(IF(mes = 5, 1, 0)) AS m05"),
                DB::raw("SUM(IF(mes = 6, 1, 0)) AS m06"),
                DB::raw("SUM(IF(mes = 7, 1, 0)) AS m07"),
                DB::raw("SUM(IF(mes = 8, 1, 0)) AS m08"),
                DB::raw("SUM(IF(mes = 9, 1, 0)) AS m09"),
                DB::raw("SUM(IF(mes = 10, 1, 0)) AS m10"),
                DB::raw("SUM(IF(mes = 11, 1, 0)) AS m11"),
                DB::raw("SUM(IF(mes = 12, 1, 0)) AS m12")
            ])
            ->where('anio', $anio);

        $totalQuery->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia));
        $totalQuery->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito));
        $totalQuery->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion));
        $totalQuery->when($area > 0, fn($q) => $q->where('id_area', $area));

        // Combinar ambas consultas
        $finalQuery = $baseQuery->union($totalQuery);

        return $finalQuery->get();
    }

    public static function modalidad_nivel_total_anio($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('modalidad as tipo', 'nivel', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->groupBy('modalidad', 'nivel')
            ->orderBy('modalidad')
            ->orderBy('id_nivel');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

        return $query->get();
    }

    public static function modalidad_nivel_total_anio_mes($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'modalidad as tipo',
            'nivel',
            DB::raw("SUM(IF(mes = 1, 1, 0)) AS ene"),
            DB::raw("SUM(IF(mes = 2, 1, 0)) AS feb"),
            DB::raw("SUM(IF(mes = 3, 1, 0)) AS mar"),
            DB::raw("SUM(IF(mes = 4, 1, 0)) AS abr"),
            DB::raw("SUM(IF(mes = 5, 1, 0)) AS may"),
            DB::raw("SUM(IF(mes = 6, 1, 0)) AS jun"),
            DB::raw("SUM(IF(mes = 7, 1, 0)) AS jul"),
            DB::raw("SUM(IF(mes = 8, 1, 0)) AS ago"),
            DB::raw("SUM(IF(mes = 9, 1, 0)) AS sep"),
            DB::raw("SUM(IF(mes = 10, 1, 0)) AS oct"),
            DB::raw("SUM(IF(mes = 11, 1, 0)) AS nov"),
            DB::raw("SUM(IF(mes = 12, 1, 0)) AS dic")
        ])
            ->where('anio', $anio)
            ->groupBy('modalidad', 'nivel')
            ->orderBy('modalidad')
            ->orderBy('id_nivel');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

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

        return $query->get();
    }

    public static function ebr_nivel_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select(
            DB::raw("
                CASE 
                    WHEN id_nivel IN ('A2', 'A3', 'A5') THEN 'INICIAL' 
                    WHEN id_nivel = 'B0' THEN 'PRIMARIA' 
                    WHEN id_nivel = 'F0' THEN 'SECUNDARIA' 
                END AS name
            "),
            DB::raw('COUNT(*) AS y')
        )
            ->where('anio', $anio)
            ->where('id_mod', '1')
            ->groupBy('anio', 'name')
            ->orderBy('anio')
            ->orderByRaw("FIELD(name, 'INICIAL', 'PRIMARIA', 'SECUNDARIA')");

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

        return $query->get();
    }

    public static function ebr_tabla1_provincia_conteo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('provincia', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '1');

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

        return $query->groupBy('provincia')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function ebr_tabla1_provincia_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_provincia',
            'provincia',
            DB::raw('count(anio) as tt'),
            DB::raw('sum(IF(id_sexo = 1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo = 2,1,0)) as tm'),
            DB::raw('sum(IF(id_nivel in ("A3","A5") and id_grado=1,1,0)) as ci'),
            DB::raw('sum(IF(id_nivel in ("A2","A3","A5") and id_grado in (2,3,4,5),1,0)) as cii'),
            DB::raw('sum(IF(id_nivel = "B0" and id_grado in (4,5),1,0)) as ciii'),
            DB::raw('sum(IF(id_nivel = "B0" and id_grado in (6,7),1,0)) as civ'),
            DB::raw('sum(IF(id_nivel = "B0" and id_grado in (8,9),1,0)) as cv'),
            DB::raw('sum(IF(id_nivel = "F0" and id_grado in (10,11),1,0)) as cvi'),
            DB::raw('sum(IF(id_nivel = "F0" and id_grado in (12,13,14),1,0)) as cvii'),
        ])
            ->where('anio', $anio)->where('id_mod', '1')
            ->groupBy('id_provincia', 'provincia')
            ->orderBy('ugel');

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

        return $query->get();
    }

    public static function ebr_tabla2_distrito_conteo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('distrito', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '1');

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

        return $query->groupBy('distrito')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function ebr_tabla2_distrito_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_distrito',
            'distrito',
            DB::raw('count(anio) as tt'),
            DB::raw('sum(IF(id_sexo = 1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo = 2,1,0)) as tm'),
            DB::raw('sum(IF(id_nivel in ("A3","A5") and id_grado=1,1,0)) as ci'),
            DB::raw('sum(IF(id_nivel in ("A2","A3","A5") and id_grado in (2,3,4,5),1,0)) as cii'),
            DB::raw('sum(IF(id_nivel = "B0" and id_grado in (4,5),1,0)) as ciii'),
            DB::raw('sum(IF(id_nivel = "B0" and id_grado in (6,7),1,0)) as civ'),
            DB::raw('sum(IF(id_nivel = "B0" and id_grado in (8,9),1,0)) as cv'),
            DB::raw('sum(IF(id_nivel = "F0" and id_grado in (10,11),1,0)) as cvi'),
            DB::raw('sum(IF(id_nivel = "F0" and id_grado in (12,13,14),1,0)) as cvii'),
        ])
            ->where('anio', $anio)->where('id_mod', '1')
            ->groupBy('id_distrito', 'distrito')
            ->orderBy('ugel');

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

        return $query->get();
    }

    public static function ebe_nivel_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select(
            DB::raw("
                CASE 
                    WHEN id_nivel = 'E0' THEN 'PRITE' 
                    WHEN id_nivel = 'E1' THEN 'INICIAL' 
                    WHEN id_nivel = 'E2' THEN 'PRIMARIA' 
                END AS name
            "),
            // 'nivel as name',
            DB::raw('COUNT(*) AS y')
        )
            ->where('anio', $anio)
            ->where('id_mod', '2')
            ->groupBy('name')
            // ->orderByRaw("FIELD(name, 'INICIAL', 'PRIMARIA', 'SECUNDARIA')")
        ;

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

        return $query->get();
    }

    public static function ebe_nivel_rango_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select(
            'anio',
            DB::raw("
                CASE 
                    WHEN id_nivel = 'E0' THEN 'PRITE' 
                    WHEN id_nivel = 'E1' THEN 'INICIAL' 
                    WHEN id_nivel = 'E2' THEN 'PRIMARIA' 
                END AS nivel_nombre
            "),
            DB::raw('COUNT(*) AS conteo')
        )
            ->where('id_mod', '2');

        if ($anio > 0) {
            $query->where('anio', $anio);
        }

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

    public static function ebe_tabla1_provincia_conteo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('provincia', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '2');

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

        return $query->groupBy('provincia')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function ebe_tabla1_provincia_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_provincia',
            'provincia',
            DB::raw('count(anio) as tt'),
            DB::raw('sum(IF(id_sexo = 1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo = 2,1,0)) as tm'),
            DB::raw('sum(IF(id_sexo = 1 and id_nivel = "E0", 1, 0)) as thi'),
            DB::raw('sum(IF(id_sexo = 2 and id_nivel = "E0", 1, 0)) as tmi'),
            DB::raw('sum(IF(id_sexo = 1 and id_nivel = "E1", 1, 0)) as thp'),
            DB::raw('sum(IF(id_sexo = 2 and id_nivel = "E1", 1, 0)) as tmp'),
            DB::raw('sum(IF(id_sexo = 1 and id_nivel = "E2", 1, 0)) as ths'),
            DB::raw('sum(IF(id_sexo = 2 and id_nivel = "E2", 1, 0)) as tms'),
        ])
            ->where('anio', $anio)->where('id_mod', '2')
            ->groupBy('id_provincia', 'provincia')
            ->orderBy('provincia');

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

        return $query->get();
    }

    public static function ebe_tabla2_distrito_conteo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('distrito', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '2');

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

        return $query->groupBy('distrito')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function ebe_tabla2_distrito_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_distrito',
            'distrito',
            DB::raw('count(anio) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="E0",1,0)) as thi'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="E0",1,0)) as tmi'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="E1",1,0)) as thp'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="E1",1,0)) as tmp'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="E2",1,0)) as ths'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="E2",1,0)) as tms'),
        ])
            ->where('anio', $anio)->where('id_mod', '2')
            ->groupBy('id_distrito', 'distrito')
            ->orderBy('ugel');

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

        return $query->get();
    }

    public static function eba_nivel_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select(
            DB::raw("
                CASE 
                    WHEN grado like'%inicial%' THEN 'INICIAL' 
                    WHEN grado like'%intermedio%' THEN 'INTERMEDIO' 
                    WHEN grado like'%avanzado%' THEN 'AVANZADO' 
                END AS name
            "),
            DB::raw('COUNT(*) AS y')
        )
            ->where('anio', $anio)
            ->where('id_mod', '3')
            ->groupBy('name')
            ->orderByRaw("FIELD(name, 'INICIAL', 'INTERMEDIO', 'AVANZADO')");

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

        return $query->get();
    }

    public static function eba_nivel_rango_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select(
            'anio',
            DB::raw("
                CASE 
                    WHEN grado like'%inicial%' THEN 'INICIAL' 
                    WHEN grado like'%intermedio%' THEN 'INTERMEDIO' 
                    WHEN grado like'%avanzado%' THEN 'AVANZADO' 
                END AS nivel_nombre
            "),
            DB::raw('COUNT(*) AS conteo')
        )
            ->where('id_mod', '3');

        if ($anio > 0) {
            $query->where('anio', $anio);
        }

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

    public static function eba_tabla1_provincia_conteo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('provincia', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '3');

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

        return $query->groupBy('provincia')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function eba_tabla1_provincia_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_provincia',
            'provincia',
            DB::raw('count(anio) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="D1" and id_grado in (1,2),1,0)) as thi'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="D1" and id_grado in (1,2),1,0)) as tmi'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="D1" and id_grado in (3,4,5),1,0)) as thp'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="D1" and id_grado in (3,4,5),1,0)) as tmp'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="D2",1,0)) as ths'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="D2",1,0)) as tms'),
        ])
            ->where('anio', $anio)->where('id_mod', '3')
            ->groupBy('id_provincia', 'provincia')
            ->orderBy('provincia');

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

        return $query->get();
    }

    public static function eba_tabla2_distrito_conteo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('distrito', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '3');

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

        return $query->groupBy('distrito')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function eba_tabla2_distrito_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_distrito',
            'distrito',
            DB::raw('count(anio) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="D1" and id_grado in   (1,2),1,0)) as thi'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="D1" and id_grado in   (1,2),1,0)) as tmi'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="D1" and id_grado in (3,4,5),1,0)) as thp'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="D1" and id_grado in (3,4,5),1,0)) as tmp'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel="D2",1,0)) as ths'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel="D2",1,0)) as tms'),
        ])
            ->where('anio', $anio)->where('id_mod', '3')
            ->groupBy('id_distrito', 'distrito')
            ->orderBy('ugel');

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

        return $query->get();
    }
}
