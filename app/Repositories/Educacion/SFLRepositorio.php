<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\SFL;
use Illuminate\Support\Facades\DB;

class SFLRepositorio
{

    public static function locales_activos($ugel, $provincia, $distrito, $estado)
    {
        return DB::table('edu_sfl as s')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 's.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('edu_area as a', 'a.id', '=', 'ie.Area_id')
            ->join('edu_ugel as u', 'u.id', '=', 'ie.Ugel_id')
            ->where('s.estado_servicio', 1)
            ->when($ugel > 0, fn($query) => $query->where('u.id', $ugel))
            ->when($provincia > 0, fn($query) => $query->where('p.id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->when($estado > 0, fn($query) => $query->having('estados', $estado))
            ->select(
                'ie.codLocal as local',
                DB::raw('MAX(u.nombre) as ugel'),
                DB::raw('MAX(a.nombre) as area'),
                DB::raw('MAX(p.nombre) as provincia'),
                DB::raw('MAX(d.nombre) as distrito'),
                DB::raw('MAX(s.fecha_inscripcion) as inscripcion'),
                DB::raw('MAX(s.tipo) as tipo'),
                DB::raw('COUNT(s.id) as servicios'),
                DB::raw("
                            CASE 
                                WHEN COUNT(DISTINCT s.estado) = 1 THEN MAX(s.estado)  
                                ELSE 
                                    CASE 
                                        WHEN SUM(s.estado = 2) > 0 THEN 2  
                                        WHEN SUM(s.estado = 1) > 0 AND (SUM(s.estado = 3) > 0 OR SUM(s.estado = 4) > 0) THEN 2  
                                        WHEN SUM(s.estado = 3) > 0 OR SUM(s.estado = 4) > 0 THEN 3  
                                        ELSE MAX(s.estado)  
                                    END
                            END AS estados
                        ")
            )
            ->groupBy('ie.codLocal')
            ->get();
    }

    public static function servicios_activos($ugel, $provincia, $distrito,  $estado){
        return DB::table('edu_sfl as s')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 's.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('edu_area as a', 'a.id', '=', 'ie.Area_id')
            ->join('edu_ugel as u', 'u.id', '=', 'ie.Ugel_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->join('edu_sfl_estado as e', 'e.id', '=', 's.estado')
            ->where('s.estado_servicio', 1)
            ->when($ugel > 0, fn($query) => $query->where('u.id', $ugel))
            ->when($provincia > 0, fn($query) => $query->where('p.id', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->when($estado > 0, fn($query) => $query->where('s.estado', $estado))
            ->select(
                'ie.codLocal as local',
                'ie.codModular as modular',
                'ie.nombreInstEduc as iiee',
                'nm.tipo as nivel',
                'nm.nombre as modalidad',
                'u.nombre as ugel',
                'p.nombre as provincia',
                'd.nombre as distrito',
                'a.nombre as area',
                's.fecha_registro',
                's.fecha_inscripcion',
                's.tipo as tipo',
                'e.nombre as estado'
            )
            ->get();
    }

    public static function PactoRegionalEduPacto2Reports_tabla4($anio, $ugel, $provincia, $distrito, $estado)
    {
        $result = DB::table('edu_sfl as sfl')
            ->join('edu_sfl_estado as se', 'se.id', '=', 'sfl.estado')
            ->join('edu_institucioneducativa as ie', function ($join) {
                $join->on('ie.id', '=', 'sfl.institucioneducativa_id')->where('ie.EstadoInsEdu_id', '=', 3);
            })
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('edu_area as a', 'a.id', '=', 'ie.Area_id')
            ->join('edu_ugel as u', 'u.id', '=', 'ie.Ugel_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('sfl.estado_servicio', 1)
            ->when($ugel > 0, fn($q) => $q->where('u.id', $ugel))
            ->when($provincia > 0, fn($q) => $q->where('p.id', $provincia))
            ->when($distrito > 0, fn($q) => $q->where('d.id', $distrito))
            ->when($estado > 0, fn($q) => $q->where('sfl.estado', $estado))
            ->select(
                'ie.codLocal as clocal',
                'ie.codModular as cmodular',
                'ie.nombreInstEduc as iiee',
                'cp.nombre as centropoblado',
                'd.nombre as distrito',
                'nm.tipo as nivel',
                'nm.nombre as modalidad',
                'u.nombre as ugel',
                'a.nombre as area',
                'se.id as estadox',
                'se.nombre as estado'
            )
            ->get();

        return $result;
    }

    public static function inscripcion_max()
    {
        $query = SFL::select(
            DB::raw('MAX(fecha_inscripcion) as fecha, YEAR(MAX(fecha_inscripcion)) as anio, MONTH(MAX(fecha_inscripcion)) as mes, DAY(MAX(fecha_inscripcion)) as dia')
        )->first();
        return $query;
    }

    public static function reportesreporte_head($ugel, $modalidad, $nivel)
    {
        return DB::table('edu_sfl as sfl')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'sfl.institucioneducativa_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('sfl.estado_servicio', 1)
            ->when($ugel > 0, fn($query) => $query->where('ie.Ugel_id', $ugel))
            ->when($nivel > 0, fn($query) => $query->where('nm.id', $nivel))
            ->when(!empty($modalidad), fn($query) => $query->where('nm.tipo', $modalidad))
            ->count();
    }
}
