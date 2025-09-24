<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaAnual;
use App\Models\Educacion\SFL;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class SFLRepositorio
{
    public static function get_iiee($anio, $ugel, $provincia, $distrito, $estado)
    {
        $est = ['', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $tip = ['', 'AFECTACION EN USO', 'TITULARIDAD', 'APORTE REGLAMENTARIO', 'OTROS'];

        $query = DB::table(DB::raw("(
            select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.codModular, iiee.nombreInstEduc, iiee.NivelModalidad_id, iiee.Area_id, iiee.Ugel_id
	        from edu_institucionEducativa as iiee
	        where iiee.EstadoInsEdu_id = 3 and iiee.TipoGestion_id in (4, 5, 7, 8) and iiee.estado = 'AC' and iiee.NivelModalidad_id not in (14, 15)
        ) as iiee"))
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
            ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_nivelmodalidad as ne', 'ne.id', '=', 'iiee.NivelModalidad_id')
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'iiee.id');
        $query = $query->select(
            'iiee.codLocal as local',
            'iiee.codModular as modular',
            'iiee.nombreInstEduc as iiee',
            'ne.tipo as modalidad',
            'ne.nombre as nivel',
            'uu.nombre as ugel',
            'dt.nombre as distrito',
            'cp.nombre as centropoblado',
            'aa.nombre as area',
            'sfl.estado as estadox',
            DB::raw('case when sfl.estado=1 then "SANEADO" when sfl.estado=2 then "NO SANEADO" when sfl.estado=3 then "NO REGISTRADO" when sfl.estado=4 then "EN PROCESO" end as estado')
        );

        if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($estado > 0) $query = $query->where('sfl.estado', $estado);

        $query = $query->get();

        return $query;
    }

    public static function inscripcion_max()
    {
        $query = SFL::select(
            DB::raw('MAX(fecha_inscripcion) as fecha, YEAR(MAX(fecha_inscripcion)) as anio, MONTH(MAX(fecha_inscripcion)) as mes, DAY(MAX(fecha_inscripcion)) as dia')
        )->first();
        return $query;
    }
}
