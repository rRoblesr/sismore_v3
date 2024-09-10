<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use Illuminate\Support\Facades\DB;

class CuboPacto1Repositorio
{
    public static function pacto1_matriculados($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto1_matriculados') //->select(DB::raw('sum(total) as conteo'))
            ->where('anio', $anio)->where('mes_id', '<=', $mes)->whereIn('nivelmodalidad_codigo', ['A2', 'A3', 'A5']);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->sum('total');
        return $query;
    }

    public static function pacto1_matriculados_mes_a($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto1_matriculados')->select('distrito', DB::raw('sum(total) as conteo'))
            ->where('anio', $anio)->where('mes_id', '<=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito')->get();
        return $query;
    }

    public static function pacto1_matriculados_mensual($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto1_matriculados')->select('mes_id', DB::raw('sum(total) as conteo'))->whereIn('nivelmodalidad_codigo', ['A2', 'A3', 'A5'])->where('anio', $anio);
        if ($mes > 0) $query = $query->where('mes_id', '=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes_id')->get();
        return $query;
    }

    public static function pacto1_matriculados_edad($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto1_matriculados')->select('edad', DB::raw('sum(total) as conteo'))->where('anio', $anio);
        if ($mes > 0) $query = $query->where('mes_id', '<=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('edad')->get();
        return $query;
    }

    public static function listar_opt()
    {
        $query = Ugel::select('id', 'codigo', 'nombre')->orderBy('nombre', 'asc')->get();
        return $query;
    }
}
