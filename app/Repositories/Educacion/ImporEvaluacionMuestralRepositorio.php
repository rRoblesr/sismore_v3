<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\ImporEvaluacionMuestral;
use Illuminate\Support\Facades\DB;

class ImporEvaluacionMuestralRepositorio
{
    public static function anios()
    {
        $query = ImporEvaluacionMuestral::distinct()->select('anio')->orderBy('anio')->get();
        return $query;
    }

    public static function nivel($anio)
    {
        $query = ImporEvaluacionMuestral::distinct()->select('nivel')->where('anio', $anio)->orderBy('nivel')->get();
        return $query;
    }

    public static function grado($anio, $nivel)
    {
        $query = ImporEvaluacionMuestral::distinct()->select('grado')->where('anio', $anio)->where('nivel', $nivel)->orderBy('grado')->get();
        return $query;
    }

    public static function curso($anio, $nivel, $grado)
    {
        // $cursos = ['l' => 'LENGUAJE', 'm' => 'MATEMATICA', 'cn' => 'CIENCIAS NATURALES', 'cs' => 'CIENCIAS SOCIALES'];
        $curso = [];
        $query = ImporEvaluacionMuestral::select(DB::raw('count(grupo_l) as l'), DB::raw('count(grupo_m) as m'), DB::raw('count(grupo_cn) as cn'), DB::raw('count(grupo_cs) as cs'))->where('anio', $anio)->where('nivel', $nivel)->where('grado', $grado)->get();
        if ($query[0]->l > 0) {
            $curso[] = ['id' => 'l', 'curso' => 'LENGUAJE'];
        }
        if ($query[0]->m > 0) {
            $curso[] = ['id' => 'm', 'curso' => 'MATEMATICA'];
        }
        if ($query[0]->cn > 0) {
            $curso[] = ['id' => 'cn', 'curso' => 'CIENCIAS NATURALES'];
        }
        if ($query[0]->cs > 0) {
            $curso[] = ['id' => 'cs', 'curso' => 'CIENCIAS SOCIALES'];
        }

        return $curso;
    }

    public static function EvaluacionMuestralReportesHead($div, $anio, $nivel, $grado, $curso)
    {
        $query = ImporEvaluacionMuestral::select(
            DB::raw("round(sum(medida_$curso*peso_$curso)/sum(peso_$curso),1) as ponderado"),
            DB::raw("round(100*sum(IF(grupo_$curso='Satisfactorio',peso_$curso,0))/sum(peso_$curso),1) as satisfactorio"),
            // DB::raw("sum(medida_$curso*peso_$curso) as c1"),
            // DB::raw("sum(peso_$curso) as c2")
            // DB::raw("sum(IF(grupo_$curso='Satisfactorio',medida_$curso*peso_$curso,0)) as l1"),
            // DB::raw("sum(IF(grupo_$curso='Satisfactorio',peso_$curso,0)) as l1x"),
            // DB::raw("sum(peso_$curso) as c2"),
            DB::raw("count(grupo_$curso) as evaluados"),
            DB::raw("round(count(distinct cod_mod),1) as locales"),
        )
            ->where('anio', $anio)->where('nivel', $nivel)->where('grado', $grado)->whereNotNull("grupo_$curso")
            ->get();
        return $query->first();
    }

    public static function EvaluacionMuestralReportesanal1($div, $nivel, $grado, $curso)
    {
        $query = ImporEvaluacionMuestral::select(
            'anio',
            DB::raw("round(100*SUM(if(grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(peso_$curso),1) as s1"),
            DB::raw("round(100*SUM(if(grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(peso_$curso),1) as p1"),
            DB::raw("round(100*SUM(if(grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(peso_$curso),1) as i1"),
            DB::raw("round(100*SUM(if(grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(peso_$curso),1) as a1"),
        )
            ->where('nivel', $nivel)->where('grado', $grado)->whereNotNull("grupo_$curso")
            ->groupBy('anio')->get();
        return $query;
    }

    public static function EvaluacionMuestralReportesanal2($div, $anio, $nivel, $grado, $curso)
    {
        $query = ImporEvaluacionMuestral::select(
            DB::raw("round(100*SUM(if(gestion = 'PÚBLICO' and grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(if(gestion = 'PÚBLICO',peso_$curso,0)),1) as s1"),
            DB::raw("round(100*SUM(if(gestion = 'PÚBLICO' and grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(if(gestion = 'PÚBLICO',peso_$curso,0)),1) as p1"),
            DB::raw("round(100*SUM(if(gestion = 'PÚBLICO' and grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(if(gestion = 'PÚBLICO',peso_$curso,0)),1) as i1"),
            DB::raw("round(100*SUM(if(gestion = 'PÚBLICO' and grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(if(gestion = 'PÚBLICO',peso_$curso,0)),1) as a1"),
            DB::raw("round(100*SUM(if(gestion = 'PRIVADO' and grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(if(gestion = 'PRIVADO',peso_$curso,0)),1) as s2"),
            DB::raw("round(100*SUM(if(gestion = 'PRIVADO' and grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(if(gestion = 'PRIVADO',peso_$curso,0)),1) as p2"),
            DB::raw("round(100*SUM(if(gestion = 'PRIVADO' and grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(if(gestion = 'PRIVADO',peso_$curso,0)),1) as i2"),
            DB::raw("round(100*SUM(if(gestion = 'PRIVADO' and grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(if(gestion = 'PRIVADO',peso_$curso,0)),1) as a2"),
        )
            ->where('anio', $anio)->where('nivel', $nivel)->where('grado', $grado)->whereNotNull("grupo_$curso")
            ->get();
        return $query->first();
    }

    public static function EvaluacionMuestralReportesanal3($div, $anio, $nivel, $grado, $curso)
    {
        $query = ImporEvaluacionMuestral::select(
            DB::raw("round(100*SUM(if(area_geografica = 'RURAL' and grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(if(area_geografica = 'RURAL',peso_$curso,0)),1) as s1"),
            DB::raw("round(100*SUM(if(area_geografica = 'RURAL' and grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(if(area_geografica = 'RURAL',peso_$curso,0)),1) as p1"),
            DB::raw("round(100*SUM(if(area_geografica = 'RURAL' and grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(if(area_geografica = 'RURAL',peso_$curso,0)),1) as i1"),
            DB::raw("round(100*SUM(if(area_geografica = 'RURAL' and grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(if(area_geografica = 'RURAL',peso_$curso,0)),1) as a1"),
            DB::raw("round(100*SUM(if(area_geografica = 'URBANA' and grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(if(area_geografica = 'URBANA',peso_$curso,0)),1) as s2"),
            DB::raw("round(100*SUM(if(area_geografica = 'URBANA' and grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(if(area_geografica = 'URBANA',peso_$curso,0)),1) as p2"),
            DB::raw("round(100*SUM(if(area_geografica = 'URBANA' and grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(if(area_geografica = 'URBANA',peso_$curso,0)),1) as i2"),
            DB::raw("round(100*SUM(if(area_geografica = 'URBANA' and grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(if(area_geografica = 'URBANA',peso_$curso,0)),1) as a2"),
        )
            ->where('anio', $anio)->where('nivel', $nivel)->where('grado', $grado)->whereNotNull("grupo_$curso")
            ->get();
        return $query->first();
    }

    public static function EvaluacionMuestralReportesanal4($div, $anio, $nivel, $grado, $curso)
    {
        $query = ImporEvaluacionMuestral::select(
            DB::raw("round(100*SUM(if(sexo = 'HOMBRE' and grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(if(sexo = 'HOMBRE',peso_$curso,0)),1) as s1"),
            DB::raw("round(100*SUM(if(sexo = 'HOMBRE' and grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(if(sexo = 'HOMBRE',peso_$curso,0)),1) as p1"),
            DB::raw("round(100*SUM(if(sexo = 'HOMBRE' and grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(if(sexo = 'HOMBRE',peso_$curso,0)),1) as i1"),
            DB::raw("round(100*SUM(if(sexo = 'HOMBRE' and grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(if(sexo = 'HOMBRE',peso_$curso,0)),1) as a1"),
            DB::raw("round(100*SUM(if(sexo = 'MUJER' and grupo_$curso = 'Satisfactorio',   peso_$curso,0))/SUM(if(sexo = 'MUJER',peso_$curso,0)),1) as s2"),
            DB::raw("round(100*SUM(if(sexo = 'MUJER' and grupo_$curso = 'En inicio',       peso_$curso,0))/SUM(if(sexo = 'MUJER',peso_$curso,0)),1) as p2"),
            DB::raw("round(100*SUM(if(sexo = 'MUJER' and grupo_$curso = 'En proceso',      peso_$curso,0))/SUM(if(sexo = 'MUJER',peso_$curso,0)),1) as i2"),
            DB::raw("round(100*SUM(if(sexo = 'MUJER' and grupo_$curso = 'Previo al inicio',peso_$curso,0))/SUM(if(sexo = 'MUJER',peso_$curso,0)),1) as a2"),
        )
            ->where('anio', $anio)->where('nivel', $nivel)->where('grado', $grado)->whereNotNull("grupo_$curso")
            ->get();
        return $query->first();
    }
}
