<?php

namespace App\Repositories\Salud;

use App\Models\Salud\CuboPacto3PadronMaterno;
use Illuminate\Support\Facades\DB;

class CuboPacto3Repositorio
{

    public static function head($anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            DB::raw('sum(numerador) si'),
            DB::raw('sum(denominador)-sum(numerador) no'),
            DB::raw('sum(denominador) conteo'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->get()->first();
        return $query;
    }

    public static function getEduPacto2anal1($anio, $mes, $provincia, $distrito, $estado)
    {
        $query = DB::table('edu_cubo_pacto02_local')->select(
            'provincia',
            DB::raw('count(local) as conteo'),
            DB::raw('sum(if(estado=1,1,0)) as si'),
            DB::raw('sum(if(estado!=1,1,0)) as no'),
        ); //->where(DB::raw('year(fecha_inscripcion)'), $anio);

        if ($mes > 0) $query = $query->where(DB::raw('month(fecha_inscripcion)'), $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        if ($estado > 0) $query = $query->where('estado', $estado);

        $query = $query->groupBy('provincia')->get();
        return $query;
    }

    public static function getEduPacto2tabla1($anio, $mes, $provincia, $distrito, $estado)
    {
        $query = DB::table('edu_cubo_pacto02_local')->select('distrito', DB::raw('count(local) as conteo'))->where(DB::raw('year(fecha_inscripcion)'), $anio);

        if ($mes > 0) $query = $query->where(DB::raw('month(fecha_inscripcion)'), '<=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        if ($estado > 0) $query = $query->where('estado', $estado);

        $query = $query->groupBy('distrito')->get();
        return $query;
    }

    public static function getEduPacto2tabla1x($anio, $mes, $provincia, $distrito, $estado)
    {
        $query = DB::table('edu_cubo_pacto02_local')->select('distrito', DB::raw('count(local) as conteo'));

        // if ($mes > 0) $query = $query->where(DB::raw('month(fecha_inscripcion)'), '<=', $mes);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        if ($estado > 0) $query = $query->where('estado', $estado);

        $query = $query->groupBy('distrito')->get();
        return $query;
    }
}
