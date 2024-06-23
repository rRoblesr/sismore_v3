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
}
