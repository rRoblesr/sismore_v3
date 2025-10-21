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

    public static function sfl_ugel()
    {
        $query = CuboPacto2::distinct()
            ->select('ugel_id as id', 'ugel as nombre')
            ->get();
        return $query;
    }

    public static function sfl_modalidad($ugel)
    {
        return CuboPacto2::selectRaw("
                DISTINCT modalidad as id,
                CASE
                    WHEN modalidad = 'EBA' THEN 'EBA | Educación Básica Alternativa'
                    WHEN modalidad = 'EBE' THEN 'EBE | Educación Básica Especial'
                    WHEN modalidad = 'EBR' THEN 'EBR | Educación Básica Regular'
                    WHEN modalidad = 'ETP' THEN 'ETP | Educación Técnico Productiva'
                    WHEN modalidad = 'SNU' THEN 'SNU | Superior No Universitaria'
                END as nombre
            ")
            ->whereNotNull('modalidad')
            ->when($ugel > 0, fn($query) => $query->where('ugel_id', $ugel))
            ->pluck('nombre', 'id');
    }

    public static function sfl_nivel($ugel, $modalidad = '')
    {
        return CuboPacto2::from('edu_cubo_pacto02_local as p')
            ->selectRaw("DISTINCT nm.id, CONCAT(nm.codigo, ' | ', nm.nombre) as nombre")
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'p.nivel_id')
            ->when($ugel > 0, fn($query) => $query->where('p.ugel_id', $ugel))
            ->when(!empty($modalidad), fn($query) => $query->where('p.modalidad', $modalidad))
            ->orderBy('nombre')
            ->pluck('nombre', 'id');
    }

    public static function sfl_provincia($ugel)
    {
        return CuboPacto2::distinct()
            ->select('provincia_id  as id', 'provincia as nombre')
            ->when($ugel > 0, function ($query) use ($ugel) {
                return $query->where('ugel_id', $ugel);
            })
            ->get();
    }

    public static function sfl_distrito($ugel, $provincia)
    {
        return CuboPacto2::distinct()
            ->select('distrito_id  as id', 'distrito as nombre')
            ->when($ugel > 0, function ($query) use ($ugel) {
                return $query->where('ugel_id', $ugel);
            })
            ->when($provincia > 0, function ($query) use ($provincia) {
                return $query->where('provincia_id', $provincia);
            })
            ->get();
    }

    public static function sfl_estado($ugel, $provincia, $distrito)
    {
        return CuboPacto2::distinct()
            ->select('estado as id', DB::raw('case when estado = 1 then "SANEADO" when estado = 2 then "NO SANEADO" when estado = 3 then "NO REGISTRADO" ELSE "EN PROCESO" end as nombre'))
            ->when($ugel > 0, function ($query) use ($ugel) {
                return $query->where('ugel_id', $ugel);
            })
            ->when($provincia > 0, function ($query) use ($provincia) {
                return $query->where('provincia_id', $provincia);
            })
            ->when($distrito > 0, function ($query) use ($distrito) {
                return $query->where('distrito_id', $distrito);
            })
            ->get();
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

    public static function PactoRegionalEduPacto2Reports_anal2($provincia = 0, $distrito = 0)
    {
        $query = CuboPacto2::select(
            DB::raw("CASE WHEN estado = 1 THEN 'SANEADO' ELSE 'NO SANEADO' END AS name"),
            DB::raw('COUNT(*) as y')
        );

        if ($provincia > 0) {
            $query->where('provincia_id', $provincia);
        }
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }
        return $query->groupBy(DB::raw("CASE WHEN estado = 1 THEN 'SANEADO' ELSE 'NO SANEADO' END "))->get();
    }

    public static function PactoRegionalEduPacto2Reports_head($anio = null, $mes = null, $provincia = 0, $distrito = 0, $estado = 0)
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

    public static function reportesreporte_head($ugel, $modalidad, $nivel, $estado)
    {
        return CuboPacto2::query()
            ->when($estado > 0, function ($query) use ($estado) {
                if ($estado == 1)
                    return $query->where('estado', 1);
                else
                    return $query->where('estado', '!=', 1);
            })
            ->when($ugel > 0, fn($query) => $query->where('ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('modalidad', $modalidad))
            ->count();
    }

    public static function reportesreporte_anal1($ugel, $modalidad, $nivel)
    {
        return CuboPacto2::from("edu_cubo_pacto02_local as pct")
            ->select(
                'p.nombre as provincia',
                DB::raw("sum(if(estado=1,1,0)) as saneado"),
                DB::raw("sum(if(estado!=1,1,0)) as nosaneado")
            )
            ->join('par_ubigeo as d', 'd.id', '=', 'pct.distrito_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->when($ugel > 0, fn($query) => $query->where('pct.ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('pct.nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('pct.modalidad', $modalidad))
            ->groupBy('p.nombre')->get();
    }

    public static function reportesreporte_anal2($ugel, $modalidad, $nivel)
    {
        return CuboPacto2::selectRaw("
                CASE 
                    WHEN estado = 1 THEN 'SANEADO' 
                    ELSE 'NO SANEADO' 
                END as name,
                COUNT(*) as y
            ")
            ->when($ugel > 0, fn($query) => $query->where('ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('modalidad', $modalidad))
            ->groupBy('name')
            ->pluck('y', 'name');
    }

    public static function reportesreporte_anal3($ugel, $modalidad, $nivel)
    {
        return CuboPacto2::from("edu_cubo_pacto02_local as pct")
            ->select(
                'p.codigo',
                'p.nombre as provincia',
                DB::raw("sum(if(pct.estado=1,1,0)) as saneado"),
                DB::raw("count(*) as nosaneado"),
                DB::raw("round(100*sum(if(pct.estado=1,1,0))/count(*),1) as indicador")
            )
            ->join('par_ubigeo as d', 'd.id', '=', 'pct.distrito_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->when($ugel > 0, fn($query) => $query->where('pct.ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('pct.nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('pct.modalidad', $modalidad))
            ->groupBy('p.codigo', 'p.nombre')->get();
    }

    public static function reportesreporte_anal4($ugel, $modalidad, $nivel)
    {
        return CuboPacto2::from("edu_cubo_pacto02_local as pct")
            ->select(
                'a.nombre as area',
                DB::raw("sum(if(pct.estado=1,1,0)) as saneado"),
                DB::raw("sum(if(pct.estado!=1,1,0)) as nosaneado")
            )
            ->join('edu_area as a', 'a.id', '=', 'pct.area_id')
            ->when($ugel > 0, fn($query) => $query->where('pct.ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('pct.nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('pct.modalidad', $modalidad))
            ->groupBy('a.nombre')->get();
    }

    public static function reportesreporte_tabla1($ugel, $modalidad, $nivel)
    {
        $subquery = DB::table('edu_sfl as sfl')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'sfl.institucioneducativa_id')
            ->selectRaw("ie.Ugel_id as ugel, COUNT(*) as se, SUM(IF(ie.Area_id = 1, 1, 0)) as seu, SUM(IF(ie.Area_id = 2, 1, 0)) as ser")
            ->where('sfl.estado_servicio', 1)
            ->groupBy('ie.Ugel_id');

        return DB::table('edu_cubo_pacto02_local as pct')
            ->join('edu_ugel as u', 'u.id', '=', 'pct.ugel_id')
            ->joinSub($subquery, 'tb', function ($join) {
                $join->on('tb.ugel', '=', 'pct.ugel_id');
            })
            ->selectRaw("u.nombre as ugel,
                tb.se, tb.seu, tb.ser,
                COUNT(*) as le, SUM(IF(pct.area_id = 1, 1, 0)) as leu, SUM(IF(pct.area_id = 2, 1, 0)) as ler,
                SUM(IF(pct.estado = 1, 1, 0)) as le1, ROUND(100 * SUM(IF(pct.estado = 1, 1, 0)) / COUNT(*), 1) as le1p,
                SUM(IF(pct.estado = 2, 1, 0)) as le2, ROUND(100 * SUM(IF(pct.estado = 2, 1, 0)) / COUNT(*), 1) as le2p,
                SUM(IF(pct.estado = 3, 1, 0)) as le3, ROUND(100 * SUM(IF(pct.estado = 3, 1, 0)) / COUNT(*), 1) as le3p,
                SUM(IF(pct.estado = 4, 1, 0)) as le4, ROUND(100 * SUM(IF(pct.estado = 4, 1, 0)) / COUNT(*), 1) as le4p")
            ->when($ugel > 0, fn($query) => $query->where('pct.ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('pct.nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('pct.modalidad', $modalidad))
            ->groupBy('u.nombre', 'tb.se', 'tb.seu', 'tb.ser')
            ->orderBy('u.nombre')
            ->get();
    }

    public static function reportesreporte_tabla2($ugel, $modalidad, $nivel)
    {
        $subquery = DB::table('edu_sfl as sfl')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'sfl.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->selectRaw('cp.Ubigeo_id as distrito, COUNT(*) as se, SUM(IF(ie.Area_id = 1,1,0)) as seu, SUM(IF(ie.Area_id = 2,1,0)) as ser')
            ->where('sfl.estado_servicio', 1)
            ->groupBy('cp.Ubigeo_id');

        return DB::table('edu_cubo_pacto02_local as pct')
            ->join('par_ubigeo as d', 'd.id', '=', 'pct.distrito_id')
            ->joinSub($subquery, 'tb', function ($join) {
                $join->on('tb.distrito', '=', 'pct.distrito_id');
            })
            ->selectRaw("d.nombre distrito,
                tb.se, tb.seu, tb.ser,
                count(*) le, sum(if(pct.area_id = 1,1,0))leu, sum(if(pct.area_id = 2,1,0)) ler, 
                sum(if(pct.estado = 1,1,0)) le1, round(100*sum(if(pct.estado = 1,1,0))/count(*),1) le1p,
                sum(if(pct.estado = 2,1,0)) le2, round(100*sum(if(pct.estado = 2,1,0))/count(*),1) le2p,
                sum(if(pct.estado = 3,1,0)) le3, round(100*sum(if(pct.estado = 3,1,0))/count(*),1) le3p,
                sum(if(pct.estado = 4,1,0)) le4, round(100*sum(if(pct.estado = 4,1,0))/count(*),1) le4p")
            ->when($ugel > 0, fn($query) => $query->where('pct.ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('pct.nivel_id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('pct.modalidad', $modalidad))
            ->groupBy('d.nombre', 'tb.se', 'tb.seu', 'tb.ser')
            ->orderBy('d.codigo')
            ->get();
    }

    public static function reportesreporte_tabla3($ugel, $modalidad, $nivel)
    {
        return  DB::table('edu_sfl as sfl')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'sfl.institucioneducativa_id')
            ->join('edu_ugel as u', 'u.id', '=', 'ie.Ugel_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('edu_area as a', 'a.id', '=', 'ie.Area_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->join('edu_sfl_estado as e', 'e.id', '=', 'sfl.estado')
            ->where('sfl.estado_servicio', 1)
            ->select(
                'u.nombre as ugel',
                'd.nombre as distrito',
                'cp.nombre as centropoblado',
                'a.nombre as ambito',
                'ie.codLocal as clocal',
                'ie.codModular as cmodular',
                'ie.nombreInstEduc as iiee',
                'nm.nombre as nivel',
                'sfl.estado'
            )
            ->when($ugel > 0, fn($query) => $query->where('u.id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('nm.id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('nm.tipo', $modalidad))
            ->orderBy('u.nombre')
            ->orderBy('d.nombre')
            ->orderBy('ie.nombreInstEduc')
            ->get();
    }
}
