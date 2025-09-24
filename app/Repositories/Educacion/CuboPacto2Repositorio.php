<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\CuboPacto2;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CuboPacto2Repositorio
{
    /* 
    * filtros
    */
    public static function actualizado()
    {
        $maxAno = CuboPacto2::selectRaw('MAX(YEAR(fecha_inscripcion)) as anio')->value('anio');
        $maxMes = CuboPacto2::whereRaw('YEAR(fecha_inscripcion) = ?', [$maxAno])
            ->selectRaw('MAX(MONTH(fecha_inscripcion)) as mes')
            ->value('mes');
        $query = CuboPacto2::from('edu_cubo_pacto02_local as m')
            ->join('par_mes as p', 'p.id', '=', DB::raw('MONTH(m.fecha_inscripcion)'))
            ->whereRaw('YEAR(m.fecha_inscripcion) = ?', [$maxAno])
            ->whereRaw('MONTH(m.fecha_inscripcion) = ?', [$maxMes])
            ->selectRaw('YEAR(m.fecha_inscripcion) as anio, MONTH(m.fecha_inscripcion) as mes, CONCAT(p.mes, " ", YEAR(m.fecha_inscripcion)) AS fecha')
            ->first();
        return $query ?: null;
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


    public static function getEduPacto2tabla1($anio = null, $mes = null, $provincia = 0, $distrito = 0, $estado = 0)
    {
        $query = CuboPacto2::select('distrito', DB::raw('COUNT(*) as conteo'));
        if ($anio) {
            $fechaInicio = Carbon::create($anio, 1, 1);
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
            } else {
                $fechaFin = $fechaInicio->copy()->endOfYear();
            }
            $query->whereBetween('fecha_inscripcion', [$fechaInicio, $fechaFin]);
        }
        if ($provincia > 0) {
            $query->where('provincia_id', $provincia);
        }
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }
        if ($estado > 0) {
            $query->where('estado', $estado);
        }

        return $query->groupBy('distrito')->get();
    }

    public static function inscripcion_max($provincia, $distrito, $estado)
    {
        $query = CuboPacto2::select(
            DB::raw('
            MAX(fecha_inscripcion) as fecha,
            YEAR(MAX(fecha_inscripcion)) as anio,
            MONTH(MAX(fecha_inscripcion)) as mes,
            DAY(MAX(fecha_inscripcion)) as dia
        ')
        );
        if ($provincia > 0) {
            $query->where('provincia_id', $provincia);
        }
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }
        if ($estado > 0) {
            $query->where('estado', $estado);
        }
        return $query->first();
    }

    public static function anios_inscripcion()
    {
        $query = CuboPacto2::distinct()->select(
            DB::raw('YEAR(fecha_inscripcion) as anio')
        )->whereNotNull('fecha_inscripcion')->orderBy('fecha_inscripcion')->get();
        return $query;
    }

    public static function mes_inscripcion($anio)
    {
        $query = CuboPacto2::from('edu_cubo_pacto02_local as c')
            ->join('par_mes as m', DB::raw('m.codigo'), '=', DB::raw('MONTH(c.fecha_inscripcion)'))
            ->select('m.codigo as id', 'm.mes as nombre');
        if ($anio > 0) $query->whereBetween('c.fecha_inscripcion', ["{$anio}-01-01", "{$anio}-12-31"]);
        return $query->distinct()->orderBy('m.codigo')->get();
    }

    public static function provincia_inscripcion($anio = null, $mes = null)
    {
        $query = CuboPacto2::from('edu_cubo_pacto02_local as c')
            ->join('par_ubigeo as d', 'd.id', '=', 'c.distrito_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select('p.id', 'p.nombre')
            ->distinct()
            ->orderBy('p.nombre');
        if ($anio) {
            $fechaInicio = Carbon::create($anio, 1, 1);
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
            } else {
                $fechaFin = Carbon::create($anio, 12, 31, 23, 59, 59);
            }
            $query->whereBetween('c.fecha_inscripcion', [$fechaInicio, $fechaFin]);
        }
        return $query->get();
    }

    public static function distrito_inscripcion($anio = null, $mes = null, $provincia = null)
    {
        $query = CuboPacto2::from('edu_cubo_pacto02_local as c')
            ->join('par_ubigeo as d', 'd.id', '=', 'c.distrito_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select('d.id', 'd.nombre')
            ->distinct()
            ->orderBy('p.nombre');
        if ($anio) {
            $fechaInicio = Carbon::create($anio, 1, 1);
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
            } else {
                $fechaFin = Carbon::create($anio, 12, 31, 23, 59, 59);
            }
            $query->whereBetween('c.fecha_inscripcion', [$fechaInicio, $fechaFin]);
        }
        if ($provincia) {
            $query->where('p.id', $provincia);
        }
        return $query->get();
    }

    public static function locales($provincia, $distrito, $estado)
    {
        $query = CuboPacto2::select(
            DB::raw('count(local) as conteo')
        );
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        if ($estado > 0) $query = $query->where('estado', $estado);

        $query = $query->first();
        return $query->conteo;
    }

    public static function PactoRegionalEduPacto2Reports_locales($anio = null, $mes = null, $provincia = 0, $distrito = 0, $estado = 0)
    {
        $query = CuboPacto2::query();
        if ($anio) {
            $fechaInicio = Carbon::create($anio, 1, 1);
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
            } else {
                $fechaFin = Carbon::create($anio, 12, 31, 23, 59, 59);
            }
            $query->whereBetween('fecha_inscripcion', [$fechaInicio, $fechaFin]);
        }
        if ($provincia > 0) {
            $query->where('provincia_id', $provincia);
        }
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }
        if ($estado > 0) {
            $query->where('estado', $estado);
        }
        return $query->count();
    }
}
