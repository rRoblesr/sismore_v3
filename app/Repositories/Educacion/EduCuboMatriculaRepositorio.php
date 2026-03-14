<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\EduCuboMatricula;
use App\Models\Educacion\InstitucionEducativa;
use Illuminate\Support\Facades\DB;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;

class EduCuboMatriculaRepositorio
{
    public static function anio_max()
    {
        return EduCuboMatricula::select(DB::raw('max(anio) as anio'))->first();
    }

    public static function listar_anios()
    {
        return EduCuboMatricula::distinct()->select('anio')->orderBy('anio', 'asc')->get();
    }

    public static function matricula_resumen($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 1);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->selectRaw("
            COUNT(*) AS matriculados,
            SUM(eib) AS eib,
            SUM(IF(pais IS NOT NULL AND pais != '' AND pais != 'PERÚ', 1, 0)) AS extranjeros,
            SUM(IF(discapacidad IS NOT NULL AND discapacidad != '', 1, 0)) AS discapacidad
        ")->first();
    }

    public static function matricula_resumen_ebe($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 2); // EBE

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0') $query->where('id_nivel', $nivel);

        return $query->selectRaw("
            COUNT(DISTINCT cmodular) AS instituciones,
            COUNT(*) AS matriculados,
            SUM(IF(pais IS NOT NULL AND pais != '' AND pais != 'PERÚ', 1, 0)) AS extranjeros,
            SUM(IF(discapacidad IS NOT NULL AND discapacidad != '', 1, 0)) AS discapacidad
        ")->first();
    }

    public static function matricula_resumen_eba($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3); // EBA

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0' && $nivel != 0) $query->where('id_nivel', $nivel);

        return $query->selectRaw("
            COUNT(DISTINCT cmodular) AS instituciones,
            COUNT(*) AS estudiantes,
            SUM(IF(pais IS NOT NULL AND pais != '' AND pais != 'PERÚ', 1, 0)) AS extranjeros,
            SUM(IF(discapacidad IS NOT NULL AND discapacidad != '', 1, 0)) AS discapacitados
        ")->first();
    }

    public static function eba_ugel_sexo($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3); // EBA

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0' && $nivel != 0) $query->where('id_nivel', $nivel);

        return $query
            ->select('ugel', 'sexo', DB::raw('count(*) as conteo'))
            ->groupBy('ugel', 'sexo')
            ->get();
    }

    public static function eba_sexo($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3); // EBA

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0' && $nivel != 0) $query->where('id_nivel', $nivel);

        return $query
            ->select('sexo as name', DB::raw('count(*) as y'))
            ->groupBy('name')
            ->orderBy('y', 'desc')
            ->get();
    }

    public static function eba_edad_grupo_sexo($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3); // EBA

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0' && $nivel != 0) $query->where('id_nivel', $nivel);

        return $query
            ->select(
                'sexo',
                DB::raw('case when edad in (0,1,2,3,4) then "00-04"
                                  when edad in (5,6,7,8,9) then "05-09"
                                  when edad in (10,11,12,13,14) then "10-14"
                                  when edad in (15,16,17,18,19) then "15-19"
                                  when edad in (20,21,22,23,24) then "20-24"
                                  when edad in (25,26,27,28,29) then "25-29"
                                  when edad in (30,31,32,33,34) then "30-34"
                                  when edad in (35,36,37,38,39) then "35-39"
                                  when edad in (40,41,42,43,44) then "40-44"
                                  else "45 a mas" end as grupos'),
                DB::raw('count(*) as conteo')
            )
            ->groupBy('grupos', 'sexo')
            ->orderByRaw('case grupos
                            when "00-04" then 1
                            when "05-09" then 2
                            when "10-14" then 3
                            when "15-19" then 4
                            when "20-24" then 5
                            when "25-29" then 6
                            when "30-34" then 7
                            when "35-39" then 8
                            when "40-44" then 9
                            else 10
                        end')
            ->get();
    }

    public static function eba_discapacidad_sexo($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3); // EBA

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0' && $nivel != 0) $query->where('id_nivel', $nivel);

        return $query
            ->whereNotNull('discapacidad')
            ->whereRaw("TRIM(discapacidad) != ''")
            ->select(DB::raw('TRIM(discapacidad) as discapacidad'), 'sexo', DB::raw('count(*) as conteo'))
            ->groupBy(DB::raw('TRIM(discapacidad)'), 'sexo')
            ->orderBy('conteo', 'desc')
            ->get();
    }

    public static function eba_tabla1_ugel($anio, $provincia = 0, $distrito = 0, $nivel = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3); // EBA

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0' && $nivel != 0) $query->where('id_nivel', $nivel);

        return $query
            ->select(
                'id_ugel as idugel',
                'ugel',
                DB::raw('count(*) as tt'),
                DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
                DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),

                DB::raw('sum(IF(id_sexo=1 and id_gestion!=3,1,0)) as tpubh'),
                DB::raw('sum(IF(id_sexo=2 and id_gestion!=3,1,0)) as tpubm'),
                DB::raw('sum(IF(id_gestion!=3,1,0)) as tpub'),

                DB::raw('sum(IF(id_sexo=1 and id_gestion=3,1,0)) as tprih'),
                DB::raw('sum(IF(id_sexo=2 and id_gestion=3,1,0)) as tprim'),
                DB::raw('sum(IF(id_gestion=3,1,0))  as tpri'),

                DB::raw('sum(IF(id_sexo=1 and id_area=1,1,0)) as turh'),
                DB::raw('sum(IF(id_sexo=2 and id_area=1,1,0)) as turm'),
                DB::raw('sum(IF(id_area=1,1,0)) as tur'),

                DB::raw('sum(IF(id_sexo=1 and id_area=2,1,0)) as truh'),
                DB::raw('sum(IF(id_sexo=2 and id_area=2,1,0)) as trum'),
                DB::raw('sum(IF(id_area=2,1,0)) as tru'),
            )
            ->groupBy('idugel', 'ugel')
            ->orderBy('ugel')
            ->get();
    }

    public static function eba_tabla2_meta($anio, $provincia = 0, $distrito = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3)->where('id_nivel', 'D1');

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);

        return $query
            ->select(
                'cmodular as codmod',
                DB::raw('count(*) as tt'),
            )
            ->groupBy('cmodular')
            ->get();
    }

    public static function eba_tabla2_institucion($anio, $provincia = 0, $distrito = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3)->where('id_nivel', 'D1');

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);

        return $query
            ->select(
                'cmodular as codmod',
                'institucion_educativa as iiee',
                'id_gestion',
                'area',

                DB::raw('count(*) as tt'),
                DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
                DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),

                DB::raw('sum(IF(id_grado=1,1,0)) as e1'),
                DB::raw('sum(IF(id_grado=2,1,0)) as e2'),
                DB::raw('sum(IF(id_grado=3,1,0)) as e3'),
                DB::raw('sum(IF(id_grado=4,1,0)) as e4'),
                DB::raw('sum(IF(id_grado=5,1,0)) as e5'),
            )
            ->groupBy('cmodular', 'institucion_educativa', 'id_gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function eba_tabla3_meta($anio, $provincia = 0, $distrito = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3)->where('id_nivel', 'D2');

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);

        return $query
            ->select(
                'cmodular as codmod',
                DB::raw('count(*) as tt'),
            )
            ->groupBy('cmodular')
            ->get();
    }

    public static function eba_tabla3_institucion($anio, $provincia = 0, $distrito = 0)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 3)->where('id_nivel', 'D2');

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);

        return $query
            ->select(
                'cmodular as codmod',
                'institucion_educativa as iiee',
                'id_gestion',
                'area',

                DB::raw('count(*) as tt'),
                DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
                DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),

                DB::raw('sum(IF(id_grado=6,1,0)) as e1'),
                DB::raw('sum(IF(id_grado=7,1,0)) as e2'),
                DB::raw('sum(IF(id_grado=8,1,0)) as e3'),
                DB::raw('sum(IF(id_grado=9,1,0)) as e4'),
            )
            ->groupBy('cmodular', 'institucion_educativa', 'id_gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function listar_niveles_modalidad($anio, $modalidad)
    {
        return EduCuboMatricula::select('id_nivel as id', 'nivel as nombre')
            ->where('anio', $anio)
            ->where('id_mod', $modalidad)
            ->distinct()
            ->orderBy('nombre')
            ->get();
    }



    public static function niveleducativoEBEtabla($div, $anio, $provincia, $distrito,  $nivel)
    {
        $queryBase = EduCuboMatricula::where('anio', $anio)->where('id_mod', 2); // EBE

        if ($provincia > 0) $queryBase->where('id_provincia', $provincia);
        if ($distrito > 0) $queryBase->where('id_distrito', $distrito);
        if ($nivel != '0') $queryBase->where('id_nivel', $nivel);

        switch ($div) {
            case 'head1': // Instituciones Educativas (EBE Original: distinct codModular)
                $q1 = clone $queryBase;
                return (object) ['conteo' => $q1->distinct('cmodular')->count('cmodular')];

            case 'head2': // Matriculados (EBE Original: count id)
                $q2 = clone $queryBase;
                return (object) ['conteo' => $q2->count()];

            case 'head3': // Extranjeros
                $q3 = clone $queryBase;
                return (object) ['conteo' => $q3
                    ->whereNotNull('pais')
                    ->where('pais', '!=', '')
                    ->where('pais', '!=', 'PERÚ')
                    ->count()];

            case 'head4': // Discapacidad
                $q4 = clone $queryBase;
                return (object) ['conteo' => $q4
                    ->whereNotNull('discapacidad')
                    ->where('discapacidad', '!=', '')
                    ->count()];

            case 'anal1': // Grafica por UGEL y Sexo
                $qA1 = clone $queryBase;
                return $qA1
                    ->select('ugel', 'sexo', DB::raw('count(*) as conteo'))
                    ->groupBy('ugel', 'sexo')
                    ->get();

            case 'anal2': // Grafica por Area (o Gestion? Original EBE anal2 era Area?)
                // Asumimos Area por consistencia con EBR y lo usual.
                $qA2 = clone $queryBase;
                return $qA2
                    ->select('area as name', DB::raw('count(*) as y'))
                    ->groupBy('name')
                    ->get();

            case 'anal3': // Piramide de edades
                $qA3 = clone $queryBase;
                return $qA3
                    ->select(
                        DB::raw('case when edad in (0,1,2,3,4) then "00-04"
                                  when edad in (5,6,7,8,9) then "05-09"
                                  when edad in (10,11,12,13,14) then "10-14"
                                  when edad in (15,16,17,18,19) then "15-19"
                                  when edad in (20,21,22,23,24) then "20-24"
                                  when edad in (25,26,27,28,29) then "25-29"
                                  when edad in (30,31,32,33,34) then "30-34"
                                  when edad in (35,36,37,38,39) then "35-39"
                                  when edad in (40,41,42,43,44) then "40-44"
                                  else "45 a mas" end as grupos'),
                        'sexo',
                        DB::raw('count(*) as conteo')
                    )
                    ->groupBy('grupos', 'sexo')
                    ->get();

            case 'anal4': // Grafica por Discapacidad y Sexo
                $qA4 = clone $queryBase;
                return $qA4
                    ->whereNotNull('discapacidad')
                    ->where('discapacidad', '!=', '')
                    ->select('discapacidad', 'sexo', DB::raw('count(*) as conteo'))
                    ->groupBy('discapacidad', 'sexo')
                    ->orderBy('conteo', 'desc')
                    ->get();
        }
    }

    public static function metaEBE($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::select('cmodular as codmod', DB::raw('count(*) as tt'))
            ->where('anio', $anio)
            ->where('id_mod', 2)
            ->whereIn('id_nivel', ['E0', 'E1']); // EBE

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        // if ($nivel != '0') $query->where('id_nivel', $nivel);

        return $query->groupBy('cmodular')->get();
    }

    public static function tabla_matricula_ebe_inicial($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::where('anio', $anio)
            ->where('id_mod', 2)
            ->whereIn('id_nivel', ['E0', 'E1']);  // EBE

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0') $query->where('id_nivel', $nivel);

        // Si no se selecciona nivel, filtrar solo Inicial y Prite para tabla 1?
        // Generalmente tabla 1 es Inicial y tabla 2 Primaria.
        // Si el usuario filtra por nivel Primaria, esta tabla deberia salir vacia o no mostrarse.
        // Pero el filtro se aplica. Si selecciona Primaria (E2), esta consulta devolvera vacio si filtramos por edades tipicas de inicial?
        // Mejor no restringir mas alla del filtro de usuario.

        return $query->select(
            'cmodular as codmod',
            'institucion_educativa as nombre_ie',
            'nivel',
            'gestion',
            'area',
            // 'id_grado as idgrado',
            // 'grado',
            DB::raw('count(*) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(edad=0,1,0)) as e0'),
            DB::raw('sum(IF(edad=1,1,0)) as e1'),
            DB::raw('sum(IF(edad=2,1,0)) as e2'),
            DB::raw('sum(IF(edad=3,1,0)) as e3'),
            DB::raw('sum(IF(edad=4,1,0)) as e4'),
            DB::raw('sum(IF(edad=5,1,0)) as e5'),
            DB::raw('sum(IF(edad>5,1,0)) as e6') // Mas de 5 anios
        )
            ->groupBy('cmodular', 'institucion_educativa', 'nivel', 'gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function metaEBEPrimaria($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::select('cmodular as codmod', DB::raw('count(*) as tt'))
            ->where('anio', $anio)
            ->where('id_mod', 2)
            ->whereIn('id_nivel', ['E2']); // EBE

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        // if ($nivel != '0') $query->where('id_nivel', $nivel);

        return $query->groupBy('cmodular')->get();
    }

    public static function tabla_matricula_ebe_primaria($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 2)->whereIn('id_nivel', ['E2']); // EBE

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel != '0') $query->where('id_nivel', $nivel);

        return $query->select(
            'cmodular as codmod',
            'institucion_educativa as nombre_ie',
            'nivel',
            'gestion',
            'area',
            DB::raw('count(*) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(id_grado=6,1,0)) as e1'), // 1° Grado (segun tinker id_grado 6)
            DB::raw('sum(IF(id_grado=7,1,0)) as e2'), // 2° Grado (segun tinker id_grado 7)
            DB::raw('sum(IF(id_grado=8,1,0)) as e3'), // 3° Grado (segun tinker id_grado 8)
            DB::raw('sum(IF(id_grado=9,1,0)) as e4'), // 4° Grado (segun tinker id_grado 9)
            DB::raw('sum(IF(id_grado=10,1,0)) as e5'), // 5° Grado (segun tinker id_grado 10)
            DB::raw('sum(IF(id_grado=11,1,0)) as e6')  // 6° Grado (segun tinker id_grado 11)
        )
            ->groupBy('cmodular', 'institucion_educativa', 'nivel', 'gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function importacion($anio)
    {
        // Buscar primero en edu_cubo_matricula (tabla principal de este repositorio)
        $importacionCubo = DB::table('edu_cubo_matricula')
            ->join('par_importacion', 'par_importacion.id', '=', 'edu_cubo_matricula.importacion_id')
            ->where('edu_cubo_matricula.anio', $anio)
            ->where('par_importacion.estado', 'PR')
            ->orderBy('par_importacion.fechaActualizacion', 'desc')
            ->select('par_importacion.id')
            ->first();

        if ($importacionCubo) {
            return $importacionCubo->id;
        }

        // Fallback: Buscar en edu_matricula (lógica anterior)
        $anio_id = DB::table('par_anio')->where('anio', $anio)->value('id');

        if ($anio_id) {
            $importacion = DB::table('edu_matricula')
                ->join('par_importacion', 'par_importacion.id', '=', 'edu_matricula.importacion_id')
                ->where('edu_matricula.anio_id', $anio_id)
                ->where('par_importacion.estado', 'PR')
                ->orderBy('par_importacion.fechaActualizacion', 'desc')
                ->select('par_importacion.id')
                ->first();

            if ($importacion) {
                return $importacion->id;
            }
        }

        return 0;
    }

    public static function listar_ugel($modalidad, $anio)
    {
        return EduCuboMatricula::select('id_ugel as id', 'ugel as nombre')
            ->where('id_mod', $modalidad)
            ->where('anio', $anio)
            ->groupBy('id_ugel', 'ugel')
            ->orderBy('id_ugel')
            ->get();
    }

    public static function listar_gestion_por_ugel($modalidad, $anio, $ugel)
    {
        $query = EduCuboMatricula::select('id_gestion', 'gestion')
            ->where('id_mod', $modalidad)
            ->where('anio', $anio)
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel));

        $gestiones = $query->distinct()->get();

        $result = [];
        $hasPublica = false;
        $hasPrivada = false;

        foreach ($gestiones as $g) {
            if ($g->id_gestion == 3) {
                $hasPrivada = true;
            } else {
                $hasPublica = true;
            }
        }

        if ($hasPublica) {
            $result[] = ['id' => 12, 'nombre' => 'PUBLICA'];
        }
        if ($hasPrivada) {
            $result[] = ['id' => 3, 'nombre' => 'PRIVADA'];
        }

        return $result;
    }

    public static function listar_area($anio)
    {
        return EduCuboMatricula::select('id_area as id', 'area as nombre')
            ->where('anio', $anio)
            ->groupBy('id_area', 'area')
            ->orderBy('id_area')
            ->get();
    }

    public static function listar_areas_por_gestion($anio, $gestion, $ugel = 0)
    {
        $query = EduCuboMatricula::select('id_area as id', 'area as nombre')
            ->where('anio', $anio)
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));
        return $query->groupBy('id_area', 'area')
            ->orderBy('id_area')
            ->get();
    }

    public static function listar_distrito($modalidad, $anio, $ugel = 0)
    {
        $query = EduCuboMatricula::select('id_distrito as id', 'distrito as nombre')
            ->where('id_mod', $modalidad)
            ->where('anio', $anio)
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel));

        return $query->groupBy('id_distrito', 'distrito')
            ->orderBy('id_distrito')
            ->get();
    }

    public static function listar_gestion($anio, $ugel = 0, $distrito = 0)
    {
        $query = EduCuboMatricula::select('id_gestion as id', 'gestion as nombre')
            ->where('id_mod', '2')
            ->where('anio', $anio)->whereIn('id_gestion', [1, 3])
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito));

        $gestiones = $query->groupBy('id_gestion', 'gestion')
            ->orderBy('id_gestion')
            ->get();

        $result = [];
        $hasPublica = false;
        $hasPrivada = false;

        foreach ($gestiones as $g) {
            if ($g->id_gestion == 3) {
                $hasPrivada = true;
            } else {
                $hasPublica = true;
            }
        }

        if ($hasPublica) {
            $result[] = ['id' => 12, 'nombre' => 'PUBLICA'];
        }
        if ($hasPrivada) {
            $result[] = ['id' => 3, 'nombre' => 'PRIVADA'];
        }

        return $result;
    }

    public static function listar_provincias($anio, $modalidad = 0)
    {
        return EduCuboMatricula::select('id_provincia as id', 'provincia as nombre')
            ->where('anio', $anio)
            ->when($modalidad > 0, fn($q) => $q->where('id_mod', $modalidad))
            ->groupBy('id_provincia', 'provincia')
            ->orderBy('provincia')
            ->get();
    }

    public static function listar_distritos($anio, $provincia, $modalidad = 0)
    {
        return EduCuboMatricula::select('id_distrito as id', 'distrito as nombre')
            ->where('anio', $anio)
            // ->where('id_provincia', $provincia)
            ->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia))
            ->when($modalidad > 0, fn($q) => $q->where('id_mod', $modalidad))
            ->groupBy('id_distrito', 'distrito')
            ->orderBy('distrito')
            ->get();
    }

    public static function listar_niveles($anio, $modalidad = 0, $provincia = 0, $distrito = 0)
    {
        return EduCuboMatricula::select('id_nivel as id', 'nivel as nombre')
            ->where('anio', $anio)
            ->when($modalidad > 0, fn($q) => $q->where('id_mod', $modalidad))
            ->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->groupBy('id_nivel', 'nivel')
            ->orderBy('nivel')
            ->get();
    }

    public static function total_anio($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::query();
        $query->where('id_mod', '1'); // Assuming only EBR matters here based on other functions

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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->count();
    }

    public static function modalidad_total($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('modalidad', DB::raw('count(id) as conteo'));

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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        $resultados = $query->groupBy('modalidad')
            ->orderBy('id_mod')
            ->get();

        return $resultados;
    }

    public static function ebr_nivel_incial_primaria_secundaria($anio, $ugel = 0, $gestion = 0, $area = 0, $max_anio = 0)
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
            ->where('modalidad', 'ebr')
            ->when($anio > 0, fn($q) => $q->where('anio', $anio))
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($max_anio > 0, fn($q) => $q->where('anio', '<=', $max_anio));

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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->groupBy('id_ugel', 'ugel')
            ->orderByDesc('tt')
            ->get();
    }

    public static function niveleducativoEBRtabla($div, $anio, $provincia, $distrito, $nivel)
    {
        // Método base solo para casos simples que no requieren joins complejos
        $queryBase = EduCuboMatricula::where('anio', $anio)->where('id_mod', 1);
        if ($provincia > 0) $queryBase->where('id_provincia', $provincia);
        if ($distrito > 0) $queryBase->where('id_distrito', $distrito);
        if ($nivel > 0) $queryBase->where('id_nivel', $nivel);

        switch ($div) {
            case 'head1':
                $q1 = clone $queryBase;
                return (object) ['conteo' => $q1->count()];

            case 'head2':
                $q2 = clone $queryBase;
                if ($provincia > 0) $q2->where('id_provincia', $provincia);
                if ($distrito > 0) $q2->where('id_distrito', $distrito);
                if ($nivel > 0) $q2->where('id_nivel', $nivel);

                $codigosEIB = InstitucionEducativa::where('es_eib', 'SI')->pluck('codModular');

                return (object) ['conteo' => $q2->whereIn('cmodular', $codigosEIB)->count()];

            case 'head3':
                // return MatriculaGeneralRepositorio::niveleducativoEBRtabla('head3', $anio, $provincia, $distrito, $nivel);
                $q3 = clone $queryBase;
                return (object) ['conteo' => $q3
                    ->whereNotNull('pais')
                    ->where('pais', '!=', '')
                    ->where('pais', '!=', 'PERÚ')
                    ->count()];

            case 'head4':
                $q4 = clone $queryBase;
                return (object) ['conteo' => $q4
                    ->whereNotNull('discapacidad')
                    ->where('discapacidad', '!=', '')
                    ->count()];

            case 'anal1':
                $qA1 = EduCuboMatricula::where('anio', $anio)->where('id_mod', 1);
                if ($provincia > 0) $qA1->where('id_provincia', $provincia);
                if ($distrito > 0) $qA1->where('id_distrito', $distrito);
                if ($nivel > 0) $qA1->where('id_nivel', $nivel);

                return $qA1
                    ->select(
                        'ugel',
                        DB::raw('count(*) as conteo'),
                        DB::raw("CASE WHEN eib = 1 THEN 'Intercultural Bilingue' ELSE 'Intercultural' END as eib")
                    )
                    ->groupBy('id_ugel', 'ugel', 'eib')
                    ->orderBy('id_ugel', 'asc')
                    ->get();

            case 'anal2':
                $qA2 = clone $queryBase;
                return $qA2
                    ->select('area as name', DB::raw('count(*) as y'))
                    ->groupBy('name')
                    ->get();

            case 'anal3':
                $qA3 = clone $queryBase;
                return $qA3
                    ->select(
                        DB::raw('case when edad in (0,1,2,3,4) then "00-04"
                                  when edad in (5,6,7,8,9) then "05-09"
                                  when edad in (10,11,12,13,14) then "10-14"
                                  when edad in (15,16,17,18,19) then "15-19"
                                  when edad in (20,21,22,23,24) then "20-24"
                                  when edad in (25,26,27,28,29) then "25-29"
                                  when edad in (30,31,32,33,34) then "30-34"
                                  when edad in (35,36,37,38,39) then "35-39"
                                  when edad in (40,41,42,43,44) then "40-44"
                                  else "45 a mas" end as grupos'),
                        'sexo',
                        DB::raw('count(*) as conteo')
                    )
                    ->groupBy('grupos', 'sexo')
                    ->get();

            case 'anal4':
                $qA4 = clone $queryBase;
                return $qA4
                    ->select('sexo as name', DB::raw('count(*) as y'))
                    ->groupBy('name')
                    ->get();

            case 'anal5':
                $qA5 = clone $queryBase;
                return $qA5
                    ->whereNotNull('pais')
                    ->where('pais', '!=', '')
                    ->where('pais', '!=', 'PERÚ')
                    ->select('pais', 'sexo', DB::raw('count(*) as conteo'))
                    ->groupBy('pais', 'sexo')
                    ->orderBy('conteo', 'desc')
                    ->get();

            case 'anal6':
                $qA6 = clone $queryBase;
                return $qA6
                    ->whereNotNull('discapacidad')
                    ->where('discapacidad', '!=', '')
                    ->select('discapacidad', 'sexo', DB::raw('count(*) as conteo'))
                    ->groupBy('discapacidad', 'sexo')
                    ->orderBy('conteo', 'desc')
                    ->get();

            case 'tabla1':
                $qT1 = clone $queryBase;
                return $qT1
                    ->select(
                        'id_ugel as idugel',
                        'ugel',
                        DB::raw('count(*) as tt'),
                        DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
                        DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),

                        // Publico (Gestion != 3)
                        DB::raw('sum(IF(id_sexo=1 and id_gestion!=3,1,0)) as tpubh'),
                        DB::raw('sum(IF(id_sexo=2 and id_gestion!=3,1,0)) as tpubm'),
                        DB::raw('sum(IF(id_gestion!=3,1,0)) as tpub'),

                        // Privado (Gestion == 3)
                        DB::raw('sum(IF(id_sexo=1 and id_gestion=3,1,0)) as tprih'),
                        DB::raw('sum(IF(id_sexo=2 and id_gestion=3,1,0)) as tprim'),
                        DB::raw('sum(IF(id_gestion=3,1,0)) as tpri'),

                        // Area
                        DB::raw('sum(IF(id_sexo=1 and id_area=1,1,0)) as turh'),
                        DB::raw('sum(IF(id_sexo=2 and id_area=1,1,0)) as turm'),
                        DB::raw('sum(IF(id_area=1,1,0)) as tur'),

                        DB::raw('sum(IF(id_sexo=1 and id_area=2,1,0)) as truh'),
                        DB::raw('sum(IF(id_sexo=2 and id_area=2,1,0)) as trum'),
                        DB::raw('sum(IF(id_area=2,1,0)) as tru')
                    )
                    ->groupBy('id_ugel', 'ugel')
                    ->get();

            case 'tabla2':
                $qT2 = clone $queryBase;
                return $qT2
                    ->select(
                        'id_grado as idgrado',
                        'grado',
                        DB::raw('count(*) as tt'),
                        DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
                        DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),

                        // Assuming grado logic matches...
                        DB::raw('sum(IF(id_sexo=1 and id_gestion!=3,1,0)) as thi'), // Publico H?
                        DB::raw('sum(IF(id_sexo=2 and id_gestion!=3,1,0)) as tmi'), // Publico M?

                        DB::raw('sum(IF(id_sexo=1 and id_gestion=3,1,0)) as thp'), // Privado H?
                        DB::raw('sum(IF(id_sexo=2 and id_gestion=3,1,0)) as tmp'), // Privado M?

                        // Area?
                        DB::raw('sum(IF(id_sexo=1 and id_area=1,1,0)) as ths'), // Urbana H?
                        DB::raw('sum(IF(id_sexo=2 and id_area=1,1,0)) as tms')  // Urbana M?
                    )
                    ->groupBy('id_grado', 'grado')
                    ->get();
        }
    }

    public static function metaEBRInicial($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::select('cmodular as codmod', DB::raw('count(*) as tt'))
            ->where('anio', $anio)
            ->where('id_mod', 1)
            ->whereIn('id_nivel', ['A2', 'A3', 'A5']);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->groupBy('cmodular')->get();
    }

    public static function matriculaEBRInicial($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::where('anio', $anio)
            ->where('id_mod', 1)
            ->whereIn('id_nivel', ['A2', 'A3', 'A5']);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->select(
            'cmodular as codmod',
            'institucion_educativa as nombre_ie',
            'nivel',
            'gestion',
            'area',
            DB::raw('count(*) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(edad=0,1,0)) as e0'),
            DB::raw('sum(IF(edad=1,1,0)) as e1'),
            DB::raw('sum(IF(edad=2,1,0)) as e2'),
            DB::raw('sum(IF(edad=3,1,0)) as e3'),
            DB::raw('sum(IF(edad=4,1,0)) as e4'),
            DB::raw('sum(IF(edad=5,1,0)) as e5'),
            DB::raw('sum(IF(edad=6,1,0)) as e6')
        )
            ->groupBy('cmodular', 'institucion_educativa', 'nivel', 'gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function metaEBRPrimaria($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::select('cmodular as codmod', DB::raw('count(*) as tt'))
            ->where('anio', $anio)
            ->where('id_mod', 1)
            ->whereIn('id_nivel', ['B0']);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->groupBy('cmodular')->get();
    }

    public static function matriculaEBRPrimaria($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::where('anio', $anio)
            ->where('id_mod', 1)
            ->whereIn('id_nivel', ['B0']);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->select(
            'cmodular as codmod',
            'institucion_educativa as nombre_ie',
            'nivel',
            'gestion',
            'area',
            DB::raw('count(*) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(id_grado=4,1,0)) as e1'), // 1° Grado
            DB::raw('sum(IF(id_grado=5,1,0)) as e2'), // 2° Grado
            DB::raw('sum(IF(id_grado=6,1,0)) as e3'), // 3° Grado
            DB::raw('sum(IF(id_grado=7,1,0)) as e4'), // 4° Grado
            DB::raw('sum(IF(id_grado=8,1,0)) as e5'), // 5° Grado
            DB::raw('sum(IF(id_grado=9,1,0)) as e6')  // 6° Grado
        )
            ->groupBy('cmodular', 'institucion_educativa', 'nivel', 'gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function metaEBRSecundaria($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::select('cmodular as codmod', DB::raw('count(*) as tt'))
            ->where('anio', $anio)
            ->where('id_mod', 1)
            ->whereIn('id_nivel', ['F0']);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->groupBy('cmodular')->get();
    }

    public static function matriculaEBRSecundaria($anio, $provincia, $distrito, $nivel)
    {
        $query = EduCuboMatricula::where('anio', $anio)->where('id_mod', 1)->whereIn('id_nivel', ['F0']);

        if ($provincia > 0) $query->where('id_provincia', $provincia);
        if ($distrito > 0) $query->where('id_distrito', $distrito);
        if ($nivel > 0) $query->where('id_nivel', $nivel);

        return $query->select(
            'cmodular as codmod',
            'institucion_educativa as nombre_ie',
            'nivel',
            'gestion',
            'area',
            DB::raw('count(*) as tt'),
            DB::raw('sum(IF(id_sexo=1,1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2,1,0)) as tm'),
            DB::raw('sum(IF(id_grado=10,1,0)) as e1'), // 1° Grado
            DB::raw('sum(IF(id_grado=11,1,0)) as e2'), // 2° Grado
            DB::raw('sum(IF(id_grado=12,1,0)) as e3'), // 3° Grado
            DB::raw('sum(IF(id_grado=13,1,0)) as e4'), // 4° Grado
            DB::raw('sum(IF(id_grado=14,1,0)) as e5')  // 5° Grado
        )
            ->groupBy('cmodular', 'institucion_educativa', 'nivel', 'gestion', 'area')
            ->orderBy('cmodular')
            ->get();
    }

    public static function avance_matricula_total_anios($provincia = 0, $distrito = 0, $gestion = 0, $area = 0, $max_anio = 0)
    {
        $query = EduCuboMatricula::select('anio', DB::raw('COUNT(*) AS suma'))
            ->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($max_anio > 0, fn($q) => $q->where('anio', '<=', $max_anio))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return $query->groupBy('anio')->orderBy('anio')->get();
    }

    public static function avance_matricula_total_anio_meses($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('mes', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return self::format_meses_data($query->get());
    }

    public static function avance_matricula_total_anio_sexo($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_sexo', 'sexo as name', DB::raw('COUNT(*) AS y'))
            ->where('anio', $anio)
            ->groupBy('id_sexo', 'sexo')
            ->orderBy('id_sexo')
            ->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return $query->get();
    }

    public static function avance_matricula_total_anio_area($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_area', 'area as name', DB::raw('COUNT(*) AS y'))
            ->where('anio', $anio)
            ->groupBy('id_area', 'area')
            ->orderBy('id_area')
            ->when($provincia > 0, fn($q) => $q->where('id_provincia', $provincia))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return $query->get();
    }

    public static function ebr_total_anios($ugel = 0, $gestion = 0, $area = 0, $max_anio = 0)
    {
        $query = EduCuboMatricula::select('anio', DB::raw('COUNT(*) AS suma'))
            ->where('id_mod', 1)
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($max_anio > 0, fn($q) => $q->where('anio', '<=', $max_anio))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return $query->groupBy('anio')->orderBy('anio')->get();
    }

    public static function ebr_total_anio_meses($anio, $ugel = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('mes', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->where('id_mod', 1)
            ->groupBy('mes')
            ->orderBy('mes')
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return self::format_meses_data($query->get());
    }

    public static function ebe_total_anios($ugel = 0, $distrito = 0, $gestion = 0, $max_anio = 0)
    {
        $query = EduCuboMatricula::select('anio', DB::raw('COUNT(*) AS suma'))
            ->where('id_mod', 2)
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($max_anio > 0, fn($q) => $q->where('anio', '<=', $max_anio))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return $query->groupBy('anio')->orderBy('anio')->get();
    }

    public static function ebe_total_anio_meses($anio, $ugel = 0, $distrito = 0, $gestion = 0)
    {
        $query = EduCuboMatricula::select('mes', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->where('id_mod', 2)
            ->groupBy('mes')
            ->orderBy('mes')
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return self::format_meses_data($query->get());
    }

    public static function eba_total_anios($ugel = 0, $distrito = 0, $gestion = 0, $area = 0, $max_anio = 0)
    {
        $query = EduCuboMatricula::select('anio', DB::raw('COUNT(*) AS suma'))
            ->where('id_mod', 3)
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($max_anio > 0, fn($q) => $q->where('anio', '<=', $max_anio))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return $query->groupBy('anio')->orderBy('anio')->get();
    }

    public static function eba_total_anio_meses($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('mes', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->where('id_mod', 3)
            ->groupBy('mes')
            ->orderBy('mes')
            ->when($ugel > 0, fn($q) => $q->where('id_ugel', $ugel))
            ->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        return self::format_meses_data($query->get());
    }

    private static function format_meses_data($resultados)
    {
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

    public static function modalidad_total_anios($modalidad, $ugel_o_provincia = 0, $distrito = 0, $gestion = 0, $area = 0, $max_anio = 0)
    {
        $query = EduCuboMatricula::select('anio', DB::raw('COUNT(*) AS suma'))
            ->when($modalidad > 0, fn($q) => $q->where('id_mod', $modalidad));

        if ($modalidad == 0) { // Avance Matricula (usa Provincia)
            $query->when($ugel_o_provincia > 0, fn($q) => $q->where('id_provincia', $ugel_o_provincia));
        } else { // EBR, EBE, EBA (usan UGEL)
            $query->when($ugel_o_provincia > 0, fn($q) => $q->where('id_ugel', $ugel_o_provincia));
        }

        $query->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($max_anio > 0, fn($q) => $q->where('anio', '<=', $max_anio))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

        $resultados = $query->groupBy('anio')->orderBy('anio')->get();

        return $resultados;
    }

    public static function modalidad_total_anio_meses($modalidad, $anio, $ugel_o_provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('mes', DB::raw('COUNT(*) AS conteo'))
            ->where('anio', $anio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->when($modalidad > 0, fn($q) => $q->where('id_mod', $modalidad));

        if ($modalidad == 0) { // Avance Matricula (usa Provincia)
            $query->when($ugel_o_provincia > 0, fn($q) => $q->where('id_provincia', $ugel_o_provincia));
        } else { // EBR, EBE, EBA (usan UGEL)
            $query->when($ugel_o_provincia > 0, fn($q) => $q->where('id_ugel', $ugel_o_provincia));
        }

        $query->when($distrito > 0, fn($q) => $q->where('id_distrito', $distrito))
            ->when($area > 0, fn($q) => $q->where('id_area', $area))
            ->when($gestion > 0, fn($q) => $q->where('id_gestion', $gestion == 3 ? '=' : '!=', 3));

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

    public static function modalidad_total_anio_sexo($modalidad, $anio, $ugel_o_provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_sexo', 'sexo as name', DB::raw('COUNT(*) AS y'))
            ->where('anio', $anio)
            ->groupBy('id_sexo', 'sexo')
            ->orderBy('id_sexo');

        if ($modalidad > 0) {
            $query->where('id_mod', $modalidad);
        }

        if ($modalidad == 0) { // Avance Matricula (usa Provincia)
            if ($ugel_o_provincia > 0) {
                $query->where('id_provincia', $ugel_o_provincia);
            }
        } else { // EBR, EBE, EBA (usan UGEL)
            if ($ugel_o_provincia > 0) {
                $query->where('id_ugel', $ugel_o_provincia);
            }
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function avance_matricula_total_anio_ugel($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('id_ugel as idugel', 'ugel', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio);

        // modality filter can be removed if only for avance (mod 0 is unused in filter usually, but let's see)
        // Original code: if ($modalidad > 0) ...
        // Avance passes 0, so no filter.
        // So I can remove $modalidad param or keep it but ignore.
        // I'll keep signature clean: remove modality.

        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }
        // ...

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->groupBy('id_ugel', 'ugel')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function avance_matricula_total_anio_ugel_mes($modalidad, $anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
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

        // if ($modalidad > 0) ...


        if ($provincia > 0) {
            $query->where('id_provincia', $provincia);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebr_nivel_total($anio, $ugel = 0, $gestion = 0, $area = 0)
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
            ->where('id_mod', '1')
            ->groupBy('anio', 'name')
            ->orderBy('anio')
            ->orderByRaw("FIELD(name, 'INICIAL', 'PRIMARIA', 'SECUNDARIA')");

        if ($anio > 0) {
            $query->where('anio', $anio);
        }

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebr_tabla1_provincia_conteo($anio, $ugel = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('provincia', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '1');

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebr_tabla2_distrito_conteo($anio, $ugel = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('distrito', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '1');

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebe_nivel_total($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
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

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebe_nivel_rango_total($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
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

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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

    public static function ebe_tabla1_provincia_conteo($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('provincia', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '2');

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->groupBy('provincia')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function ebe_tabla1_provincia_conteo_detalles($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
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

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebe_tabla2_distrito_conteo($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select('distrito', DB::raw('COUNT(*) AS conteo'))->where('anio', $anio)->where('id_mod', '2');

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->groupBy('distrito')
            ->orderByDesc('conteo')
            ->get();
    }

    public static function ebe_tabla2_distrito_conteo_detalles($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
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
            ->orderBy('distrito');

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function eba_nivel_total($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0)
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

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function eba_nivel_rango_total($anio, $ugel = 0, $distrito = 0, $gestion = 0, $area = 0, $max_anio = 0)
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
            ->where('id_mod', '3')
            ->where('anio', $anio);

        if ($max_anio > 0) {
            $query->where('anio', '<=', $max_anio);
        }

        if ($ugel > 0) {
            $query->where('id_ugel', $ugel);
        }

        if ($distrito > 0) {
            $query->where('id_distrito', $distrito);
        }

        if ($gestion > 0) {
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
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
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        }

        if ($area > 0) {
            $query->where('id_area', $area);
        }

        return $query->get();
    }

    public static function ebr_tabla1_provincia_conteo_detalles_ciclos($anio, $ugel = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_provincia',
            'provincia',
            DB::raw('0 as meta'),
            DB::raw('0 as avance'),
            DB::raw('sum(IF(id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as tt'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as tm'),

            DB::raw('sum(IF(id_nivel="A1" OR (id_nivel in ("A3","A5") AND id_grado=1),1,0)) as ci'),
            DB::raw('sum(IF((id_nivel="A1" OR (id_nivel in ("A3","A5") AND id_grado=1)) AND id_sexo=1,1,0)) as cih'),
            DB::raw('sum(IF((id_nivel="A1" OR (id_nivel in ("A3","A5") AND id_grado=1)) AND id_sexo=2,1,0)) as cim'),

            DB::raw('sum(IF((id_nivel="A2" AND id_grado in (3,4,5)) OR (id_nivel in ("A3","A5") AND id_grado in (2,3,4)),1,0)) as cii'),
            DB::raw('sum(IF(((id_nivel="A2" AND id_grado in (3,4,5)) OR (id_nivel in ("A3","A5") AND id_grado in (2,3,4))) AND id_sexo=1,1,0)) as ciih'),
            DB::raw('sum(IF(((id_nivel="A2" AND id_grado in (3,4,5)) OR (id_nivel in ("A3","A5") AND id_grado in (2,3,4))) AND id_sexo=2,1,0)) as ciim'),

            DB::raw('sum(IF(id_nivel="B0" and id_grado in (4,5),1,0)) as ciii'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (4,5) and id_sexo=1,1,0)) as ciiih'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (4,5) and id_sexo=2,1,0)) as ciiim'),

            DB::raw('sum(IF(id_nivel="B0" and id_grado in (6,7),1,0)) as civ'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (6,7) and id_sexo=1,1,0)) as civh'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (6,7) and id_sexo=2,1,0)) as civm'),

            DB::raw('sum(IF(id_nivel="B0" and id_grado in (8,9),1,0)) as cv'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (8,9) and id_sexo=1,1,0)) as cvh'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (8,9) and id_sexo=2,1,0)) as cvm'),

            DB::raw('sum(IF(id_nivel="F0" and id_grado in (10,11),1,0)) as cvi'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (10,11) and id_sexo=1,1,0)) as cvih'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (10,11) and id_sexo=2,1,0)) as cvim'),

            DB::raw('sum(IF(id_nivel="F0" and id_grado in (12,13,14),1,0)) as cvii'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (12,13,14) and id_sexo=1,1,0)) as cviih'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (12,13,14) and id_sexo=2,1,0)) as cviim'),
        ])
            ->where('id_mod', '1')
            ->groupBy('id_provincia', 'provincia')
            ->orderBy('id_provincia');

        if ($anio > 0) {
            $query->where('anio', $anio);
        }

        if ($ugel > 0) $query->where('id_ugel', $ugel);
        if ($gestion > 0)
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        if ($area > 0) $query->where('id_area', $area);

        return $query->get();
    }

    public static function ebr_tabla2_distrito_conteo_detalles_ciclos($anio, $ugel = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::select([
            'id_distrito',
            'distrito',
            DB::raw('0 as meta'),
            DB::raw('0 as avance'),
            DB::raw('sum(IF(id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as tt'),
            DB::raw('sum(IF(id_sexo=1 and id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as th'),
            DB::raw('sum(IF(id_sexo=2 and id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as tm'),

            DB::raw('sum(IF(id_nivel="A1" OR (id_nivel in ("A3","A5") AND id_grado=1),1,0)) as ci'),
            DB::raw('sum(IF((id_nivel="A1" OR (id_nivel in ("A3","A5") AND id_grado=1)) AND id_sexo=1,1,0)) as cih'),
            DB::raw('sum(IF((id_nivel="A1" OR (id_nivel in ("A3","A5") AND id_grado=1)) AND id_sexo=2,1,0)) as cim'),

            DB::raw('sum(IF((id_nivel="A2" AND id_grado in (3,4,5)) OR (id_nivel in ("A3","A5") AND id_grado in (2,3,4)),1,0)) as cii'),
            DB::raw('sum(IF(((id_nivel="A2" AND id_grado in (3,4,5)) OR (id_nivel in ("A3","A5") AND id_grado in (2,3,4))) AND id_sexo=1,1,0)) as ciih'),
            DB::raw('sum(IF(((id_nivel="A2" AND id_grado in (3,4,5)) OR (id_nivel in ("A3","A5") AND id_grado in (2,3,4))) AND id_sexo=2,1,0)) as ciim'),

            DB::raw('sum(IF(id_nivel="B0" and id_grado in (4,5),1,0)) as ciii'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (4,5) and id_sexo=1,1,0)) as ciiih'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (4,5) and id_sexo=2,1,0)) as ciiim'),

            DB::raw('sum(IF(id_nivel="B0" and id_grado in (6,7),1,0)) as civ'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (6,7) and id_sexo=1,1,0)) as civh'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (6,7) and id_sexo=2,1,0)) as civm'),

            DB::raw('sum(IF(id_nivel="B0" and id_grado in (8,9),1,0)) as cv'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (8,9) and id_sexo=1,1,0)) as cvh'),
            DB::raw('sum(IF(id_nivel="B0" and id_grado in (8,9) and id_sexo=2,1,0)) as cvm'),

            DB::raw('sum(IF(id_nivel="F0" and id_grado in (10,11),1,0)) as cvi'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (10,11) and id_sexo=1,1,0)) as cvih'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (10,11) and id_sexo=2,1,0)) as cvim'),

            DB::raw('sum(IF(id_nivel="F0" and id_grado in (12,13,14),1,0)) as cvii'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (12,13,14) and id_sexo=1,1,0)) as cviih'),
            DB::raw('sum(IF(id_nivel="F0" and id_grado in (12,13,14) and id_sexo=2,1,0)) as cviim'),
        ])
            ->where('id_mod', '1')
            ->groupBy('id_distrito', 'distrito')
            ->orderBy('ugel')->orderBy('distrito');

        if ($anio > 0) {
            $query->where('anio', $anio);
        }

        if ($ugel > 0) $query->where('id_ugel', $ugel);

        if ($gestion > 0)
            if ($gestion == 3)
                $query->where('id_gestion', 3);
            else
                $query->where('id_gestion', '!=', 3);
        if ($area > 0) $query->where('id_area', $area);

        return $query->get();
    }

    public static function ebr_tabla3_institucion_conteo_detalles($anio, $provincia = 0, $distrito = 0, $gestion = 0, $area = 0)
    {
        $query = EduCuboMatricula::from('edu_cubo_matricula as cm')
            ->join('edu_institucioneducativa as ie', 'ie.codModular', '=', 'cm.cmodular')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->select([
                'cp.nombre as centropoblado',
                'cm.institucion_educativa',
                'cm.cmodular',
                DB::raw('0 as meta'),
                DB::raw('0 as avance'),
                DB::raw('sum(IF(cm.id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as tt'),
                DB::raw('sum(IF(cm.id_sexo=1 and cm.id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as th'),
                DB::raw('sum(IF(cm.id_sexo=2 and cm.id_nivel in ("A1","A2","A3","B0","F0","A5"),1,0)) as tm'),

                DB::raw('sum(IF(cm.id_nivel="A1" OR (cm.id_nivel in ("A3","A5") AND cm.id_grado=1),1,0)) as ci'),
                DB::raw('sum(IF((cm.id_nivel="A1" OR (cm.id_nivel in ("A3","A5") AND cm.id_grado=1)) AND cm.id_sexo=1,1,0)) as cih'),
                DB::raw('sum(IF((cm.id_nivel="A1" OR (cm.id_nivel in ("A3","A5") AND cm.id_grado=1)) AND cm.id_sexo=2,1,0)) as cim'),

                DB::raw('sum(IF((cm.id_nivel="A2" AND cm.id_grado in (3,4,5)) OR (cm.id_nivel in ("A3","A5") AND cm.id_grado in (2,3,4)),1,0)) as cii'),
                DB::raw('sum(IF(((cm.id_nivel="A2" AND cm.id_grado in (3,4,5)) OR (cm.id_nivel in ("A3","A5") AND cm.id_grado in (2,3,4))) AND cm.id_sexo=1,1,0)) as ciih'),
                DB::raw('sum(IF(((cm.id_nivel="A2" AND cm.id_grado in (3,4,5)) OR (cm.id_nivel in ("A3","A5") AND cm.id_grado in (2,3,4))) AND cm.id_sexo=2,1,0)) as ciim'),

                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (4,5),1,0)) as ciii'),
                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (4,5) and cm.id_sexo=1,1,0)) as ciiih'),
                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (4,5) and cm.id_sexo=2,1,0)) as ciiim'),

                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (6,7),1,0)) as civ'),
                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (6,7) and cm.id_sexo=1,1,0)) as civh'),
                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (6,7) and cm.id_sexo=2,1,0)) as civm'),

                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (8,9),1,0)) as cv'),
                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (8,9) and cm.id_sexo=1,1,0)) as cvh'),
                DB::raw('sum(IF(cm.id_nivel="B0" and cm.id_grado in (8,9) and cm.id_sexo=2,1,0)) as cvm'),

                DB::raw('sum(IF(cm.id_nivel="F0" and cm.id_grado in (10,11),1,0)) as cvi'),
                DB::raw('sum(IF(cm.id_nivel="F0" and cm.id_grado in (10,11) and cm.id_sexo=1,1,0)) as cvih'),
                DB::raw('sum(IF(cm.id_nivel="F0" and cm.id_grado in (10,11) and cm.id_sexo=2,1,0)) as cvim'),

                DB::raw('sum(IF(cm.id_nivel="F0" and cm.id_grado in (12,13,14),1,0)) as cvii'),
                DB::raw('sum(IF(cm.id_nivel="F0" and cm.id_grado in (12,13,14) and cm.id_sexo=1,1,0)) as cviih'),
                DB::raw('sum(IF(cm.id_nivel="F0" and cm.id_grado in (12,13,14) and cm.id_sexo=2,1,0)) as cviim'),
            ])
            ->where('cm.id_mod', '1')
            ->groupBy('cm.cmodular', 'cm.institucion_educativa', 'cp.nombre')
            ->orderBy('cm.institucion_educativa');

        if ($anio > 0) {
            $query->where('cm.anio', $anio);
        }

        if ($provincia > 0) $query->where('cm.id_provincia', $provincia);
        if ($distrito > 0) $query->where('cm.id_distrito', $distrito);
        if ($gestion > 0) $query->where('cm.id_gestion', $gestion);
        if ($area > 0) $query->where('cm.id_area', $area);

        return $query->get();
    }
}
