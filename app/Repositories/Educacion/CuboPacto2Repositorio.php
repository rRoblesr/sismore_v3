<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use Illuminate\Support\Facades\DB;

class CuboPacto2Repositorio
{
    public static function getEduPacto2anal1($anio, $mes, $provincia, $distrito, $estado)
    {
        $query = DB::table('edu_cubo_pacto02_local')->select(
            'provincia',
            DB::raw('count(local) as conteo'),
            DB::raw('sum(if(estado=1,1,0)) as si'),
            DB::raw('sum(if(estado!=1,1,0)) as no'),
        ); //->where(DB::raw('year(fecha_inscripcion)'), $anio);

        if ($mes > 0)
            $query = $query->where(DB::raw('month(fecha_inscripcion)'), $mes);
        if ($provincia > 0)
            $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0)
            $query = $query->where('distrito_id', $distrito);
        if ($estado > 0)
            $query = $query->where('estado', $estado);

        $query = $query->groupBy('provincia')->get();
        return $query;
    }

    public static function getEduPacto2tabla1($anio, $mes, $provincia, $distrito, $estado)
    {
        $query = DB::table('edu_cubo_pacto02_local')->select('distrito', DB::raw('count(local) as conteo'))->where(DB::raw('year(fecha_inscripcion)'), $anio);

        if ($mes > 0)
            $query = $query->where(DB::raw('month(fecha_inscripcion)'), '<=', $mes);
        if ($provincia > 0)
            $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0)
            $query = $query->where('distrito_id', $distrito);
        if ($estado > 0)
            $query = $query->where('estado', $estado);

        $query = $query->groupBy('distrito')->get();
        return $query;
    }

    public static function pacto1_matriculadxxos_mes_a($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto01_matriculados')->select('distrito', DB::raw('sum(total) as conteo'))
            ->where('anio', $anio)->where('mes_id', '<=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito')->get();
        return $query;
    }

    public static function pacto1_matriculazxczxcdos_mensual($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto01_matriculados')->select('mes_id', DB::raw('sum(total) as conteo'))->whereIn('nivelmodalidad_codigo', ['A2', 'A3', 'A5'])->where('anio', $anio);
        if ($mes > 0) $query = $query->where('mes_id', '=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes_id')->get();
        return $query;
    }

    public static function pacto1_matricuzxczxclados_edad($anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto01_matriculados')->select('edad', DB::raw('sum(total) as conteo'))->where('anio', $anio);
        if ($mes > 0) $query = $query->where('mes_id', '<=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('edad')->get();
        return $query;
    }

    public static function liszxczxctar_opt()
    {
        $query = Ugel::select('id', 'codigo', 'nombre')->orderBy('nombre', 'asc')->get();
        return $query;
    }
}
