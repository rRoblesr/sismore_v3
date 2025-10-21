<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Importacion;
use App\Models\Educacion\Nexus;
use App\Models\Educacion\NexusUgel;
use Illuminate\Support\Facades\DB;

class NexusRepositorio
{
    public static function filtro_ugel_deanio($anio = 2025)
    {
        return Nexus::from('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_ugel as u', 'u.id', '=', 'ie.ugel_id')
            // ->select('u.id', 'u.nombre')
            ->distinct()
            ->where('n.importacion_id', function ($query) use ($anio) {
                $query->select('id')
                    ->from('par_importacion')
                    ->where('fuenteImportacion_id', 2)
                    ->where('estado', 'PR')
                    ->whereRaw('fechaActualizacion = (
                        SELECT MAX(fechaActualizacion)
                        FROM par_importacion
                        WHERE fuenteImportacion_id = 2 AND YEAR(fechaActualizacion) = ? AND estado = "PR"
                    )', [$anio]);
            })
            ->pluck('u.nombre', 'u.id');
    }

    public static function filtro_modalidad_deaniougel($anio = 2025, $ugel = 0)
    {
        return  Nexus::from('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->join('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            // ->select('m.id', 'm.nombre')
            ->distinct()
            ->where('n.importacion_id', function ($query) use ($anio) {
                $query->select('id')
                    ->from('par_importacion')
                    ->where('fuenteImportacion_id', 2)
                    ->where('estado', 'PR')
                    ->whereRaw('fechaActualizacion = (
                        SELECT MAX(fechaActualizacion) FROM par_importacion WHERE fuenteImportacion_id = 2 AND YEAR(fechaActualizacion) = ? AND estado = "PR"
                    )', [$anio]);
            })
            ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
            ->pluck('m.nombre', 'm.id');
    }

    public static function filtro_nivel_deaniougelmodalidad($anio = 2025, $ugel = 0, $modalidad = 0)
    {
        return  Nexus::from('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->join('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            // ->select('nm.id', 'nm.nombre')
            ->distinct()
            ->where('n.importacion_id', function ($query) use ($anio) {
                $query->select('id')
                    ->from('par_importacion')
                    ->where('fuenteImportacion_id', 2)
                    ->where('estado', 'PR')
                    ->whereRaw('fechaActualizacion = (
                        SELECT MAX(fechaActualizacion) FROM par_importacion WHERE fuenteImportacion_id = 2 AND YEAR(fechaActualizacion) = ? AND estado = "PR"
                    )', [$anio]);
            })
            ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
            ->when($modalidad > 0, fn($q) => $q->where('m.id', $modalidad))
            ->pluck('nm.nombre', 'nm.id');
    }

    public static function reportesreporte_head($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->select(
                DB::raw("COUNT(DISTINCT CASE WHEN stt.dependencia = 1 AND stt.id IN (8,9,15)  THEN nx.cod_plaza  END) AS docentes"),
                DB::raw("COUNT(DISTINCT CASE WHEN stt.dependencia = 1 AND stt.id = 16  THEN nx.cod_plaza  END) AS auxiliar"),
                DB::raw("COUNT(DISTINCT CASE WHEN stt.dependencia = 4  THEN nx.cod_plaza  END) AS promotor"),
                DB::raw("COUNT(DISTINCT CASE WHEN stt.dependencia IN (2,3) THEN nx.cod_plaza END) AS administrativo")
            )
            ->where('nx.importacion_id', function ($query) use ($anio) {
                $query->select('id')
                    ->from('par_importacion')
                    ->where('fuenteImportacion_id', 2)
                    ->where('estado', 'PR')
                    ->whereRaw('fechaActualizacion = (
                        SELECT MAX(fechaActualizacion) FROM par_importacion WHERE fuenteImportacion_id = 2 AND YEAR(fechaActualizacion) = ? AND estado = "PR"
                    )', [$anio]);
            })
            ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->first();
    }
}
