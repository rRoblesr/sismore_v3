<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaAnual;
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
            DB::raw('case when sfl.estado=1 then "SANEADO" when sfl.estado=2 then "NO SANEADO" when sfl.estado=3 then "NO REGISTRADO" when sfl.estado=4 then "EN PROCESO" end as estado')
        );

        if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($estado > 0) $query = $query->where('sfl.estado', $estado);

        $query = $query->get();

        return $query;
    }

    public static function get_locals($anio, $ugel, $provincia, $distrito, $estado)
    {
        $est = ['', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $tip = ['', 'AFECTACION EN USO', 'TITULARIDAD', 'APORTE REGLAMENTARIO', 'OTROS'];

        $query = DB::table(DB::raw("(
            select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id
	        from edu_institucionEducativa as iiee
	        where iiee.EstadoInsEdu_id = 3 and iiee.TipoGestion_id in (4, 5, 7, 8) and iiee.estado = 'AC' and iiee.NivelModalidad_id not in (14, 15)
        ) as iiee"))
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
            ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'iiee.id');
        $query = $query->select(
            'iiee.codLocal as local',
            DB::raw('max(iiee.id) as id'),
            DB::raw('max(uu.nombre) as ugel'),
            DB::raw('max(pv.nombre) as provincia'),
            DB::raw('max(dt.nombre) as distrito'),
            DB::raw('max(aa.nombre) as area'),
        );

        if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($estado > 0) $query = $query->where('sfl.estado', $estado);

        $query = $query->groupBy('local')->get();

        $querySFL = DB::table(DB::raw('(select id, codLocal as local, codModular as modular from edu_institucioneducativa)as ie'))
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'ie.id', 'left')->where('ie.local', '!=', '')
            ->select('ie.*', 'sfl.estado', 'sfl.tipo', 'sfl.fecha_registro', 'sfl.fecha_inscripcion')
            ->orderBy('ie.id')->get();

        $data = [];
        foreach ($query as $key => $value) {
            $value->vista = 0;
            $value->estado = '';
            $local = $value->local;
            $sflLOCAL = $querySFL->where('local', $local);

            $saneado = 0;
            $nosaneado = 0;
            $noregistrado = 0;
            $enproceso = 0;
            $blanco = 0;
            $pos = 0;
            $var0 = FALSE;
            foreach ($sflLOCAL as $item) {
                if ($item->estado == 1) {
                    $saneado++;
                }
                if ($item->estado == 2) {
                    $nosaneado++;
                }
                if ($item->estado == 3) {
                    $noregistrado++;
                }
                if ($item->estado == 4) {
                    $enproceso++;
                }
                if ($item->estado == null) {
                    $blanco++;
                }
                if ($pos == 0) {
                    $var0 = clone $item;
                }
                $pos++;
            }
            //NIURCA 941696330
            $vestado = '';
            if ($sflLOCAL->count() == $saneado) {
                $vestado = 'SANEADO';
            } else {
                $vestado = 'NO SANEADO';
            }

            // $sfl = null;
            // if ($sflLOCAL->count() > 0)
            //     $sfl = $var0;

            if ($estado > 0) {
                if ($est[$estado] == $vestado) {
                    $value->vista = 1;
                    $value->estado = $vestado;
                }
            } else {
                $value->vista = 1;
                $value->estado = $vestado;
            }
        }

        return $query;
    }


}
