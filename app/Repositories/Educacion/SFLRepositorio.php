<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\SFL;
use Illuminate\Support\Facades\DB;

class SFLRepositorio
{
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
