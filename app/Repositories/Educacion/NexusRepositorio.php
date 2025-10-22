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

    public static function reportesreporte_anal1($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                DB::raw("
                    CASE 
                        WHEN p.codigo = '2501' THEN 'pe-uc-cp'
                        WHEN p.codigo = '2502' THEN 'pe-uc-at'
                        WHEN p.codigo = '2503' THEN 'pe-uc-pa'
                        WHEN p.codigo = '2504' THEN 'pe-uc-pr'
                    END AS codigo
                "),
                'p.nombre as provincia',
                DB::raw('COUNT(DISTINCT nx.cod_plaza) AS conteo')
            )
            ->where('stt.dependencia', 1)
            ->whereIn('stt.id', [8, 9, 15])
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
            ->groupBy('p.codigo', 'p.nombre')
            ->get();
    }
    public static function reportesreporte_anal2($anio, $ugel, $modalidad, $nivel)
    {
        return DB::table('par_mes as m')
            ->selectRaw('m.codigo, m.abreviado as mes, COALESCE(dt.conteo, 0) AS conteo')
            ->leftJoinSub(
                function ($query) use ($anio, $ugel, $modalidad, $nivel) {
                    $query->from('edu_nexus as nx')
                        ->join('edu_nexus_regimen_laboral as stt', function ($join) {
                            $join->on('stt.id', '=', 'nx.regimenlaboral_id')
                                ->where('stt.dependencia', 1)
                                ->whereIn('stt.id', [8, 9, 15]);
                        })
                        ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
                        ->join('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
                        ->joinSub(
                            function ($subQuery) use ($anio) {
                                $subQuery->selectRaw('id, MONTH(fechaActualizacion) AS mes')
                                    ->from(DB::raw('(
                                    SELECT
                                        id,
                                        fechaActualizacion,
                                        ROW_NUMBER() OVER (
                                            PARTITION BY YEAR(fechaActualizacion), MONTH(fechaActualizacion)
                                            ORDER BY fechaActualizacion DESC
                                        ) AS rn
                                    FROM par_importacion
                                    WHERE fuenteImportacion_id = 2
                                      AND estado = "PR"
                                      AND YEAR(fechaActualizacion) = ?
                                ) AS ranked'))
                                    ->whereRaw('rn = 1', [$anio]);
                            },
                            'imp',
                            'imp.id',
                            '=',
                            'nx.importacion_id'
                        )
                        ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
                        ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
                        ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
                        ->selectRaw('imp.mes, COUNT(DISTINCT nx.cod_plaza) AS conteo')
                        ->groupBy('imp.mes');
                },
                'dt',
                'dt.mes',
                '=',
                'm.codigo'
            )
            ->whereBetween('m.codigo', [1, 12])
            ->orderBy('m.codigo')
            ->get();
    }

    public static function reportesreporte_anal3($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('edu_nexus_gestion as g', 'g.id', '=', 'ie.gestion_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                'g.nombre as name',
                DB::raw('COUNT(DISTINCT nx.cod_plaza) AS y')
            )
            ->where('stt.dependencia', 1)
            ->whereIn('stt.id', [8, 9, 15])
            ->where('nx.importacion_id', function ($query) use ($anio) {
                $query->select('id')
                    ->from('par_importacion')
                    ->where('fuenteImportacion_id', 2)
                    ->where('estado', 'PR')
                    ->whereRaw('fechaActualizacion = (
                    SELECT MAX(fechaActualizacion)
                    FROM par_importacion
                    WHERE fuenteImportacion_id = 2
                      AND YEAR(fechaActualizacion) = ?
                      AND estado = "PR"
                )', [$anio]);
            })
            ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->groupBy('g.nombre')
            ->orderBy('y', 'desc')
            ->get();
    }

    public static function reportesreporte_anal4($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                'stt.nombre as name',
                DB::raw('COUNT(DISTINCT nx.cod_plaza) AS y')
            )
            ->where('stt.dependencia', 1)
            ->whereIn('stt.id', [8, 9, 15])
            ->where('nx.importacion_id', function ($query) use ($anio) {
                $query->select('id')
                    ->from('par_importacion')
                    ->where('fuenteImportacion_id', 2)
                    ->where('estado', 'PR')
                    ->whereRaw('fechaActualizacion = (
                    SELECT MAX(fechaActualizacion)
                    FROM par_importacion
                    WHERE fuenteImportacion_id = 2
                      AND YEAR(fechaActualizacion) = ?
                      AND estado = "PR"
                )', [$anio]);
            })
            ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->groupBy('stt.nombre')
            ->orderBy('y', 'desc')
            ->get();
    }

    public static function reportesreporte_tabla01($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as subtipo', 'subtipo.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_regimen_laboral as tipo', 'tipo.id', '=', 'subtipo.dependencia')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('edu_nexus_ugel as u', 'u.id', '=', 'ie.ugel_id')
            ->leftJoin('edu_nexus_situacion_laboral as sl', 'sl.id', '=', 'nx.situacionlaboral_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                'u.nombre as ugel',
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) THEN 1 ELSE 0 END) AS td'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 6 THEN 1 ELSE 0 END) AS tdn'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 1 THEN 1 ELSE 0 END) AS tdc'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 5 THEN 1 ELSE 0 END) AS tde'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 3 THEN 1 ELSE 0 END) AS tdd'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 7 THEN 1 ELSE 0 END) AS tdv'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 THEN 1 ELSE 0 END) AS ta'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 6 THEN 1 ELSE 0 END) AS tan'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 1 THEN 1 ELSE 0 END) AS tac'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 7 THEN 1 ELSE 0 END) AS tav')
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
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->groupBy('u.nombre')
            ->orderBy('u.nombre')
            ->get();
    }

    public static function reportesreporte_tabla02($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as subtipo', 'subtipo.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_regimen_laboral as tipo', 'tipo.id', '=', 'subtipo.dependencia')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('edu_nexus_ley as l', 'l.id', '=', 'ie.ugel_id')
            ->leftJoin('edu_nexus_situacion_laboral as sl', 'sl.id', '=', 'nx.situacionlaboral_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                'l.nombre as ley',
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) THEN 1 ELSE 0 END) AS td'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 6 THEN 1 ELSE 0 END) AS tdn'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 1 THEN 1 ELSE 0 END) AS tdc'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 5 THEN 1 ELSE 0 END) AS tde'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 3 THEN 1 ELSE 0 END) AS tdd'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 7 THEN 1 ELSE 0 END) AS tdv'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 THEN 1 ELSE 0 END) AS ta'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 6 THEN 1 ELSE 0 END) AS tan'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 1 THEN 1 ELSE 0 END) AS tac'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 7 THEN 1 ELSE 0 END) AS tav')
            )
            ->where('nx.importacion_id', function ($query) use ($anio) {
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
            ->when($ugel > 0, fn($q) => $q->where('ie.ugel_id', $ugel))
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->groupBy('l.nombre')
            ->orderBy('l.nombre')
            ->get();
    }

    public static function reportesreporte_tabla03($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as subtipo', 'subtipo.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_regimen_laboral as tipo', 'tipo.id', '=', 'subtipo.dependencia')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('edu_nexus_ley as l', 'l.id', '=', 'ie.ugel_id')
            ->leftJoin('edu_nexus_situacion_laboral as sl', 'sl.id', '=', 'nx.situacionlaboral_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                'd.nombre as distrito',
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) THEN 1 ELSE 0 END) AS td'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 6 THEN 1 ELSE 0 END) AS tdn'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 1 THEN 1 ELSE 0 END) AS tdc'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 5 THEN 1 ELSE 0 END) AS tde'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 3 THEN 1 ELSE 0 END) AS tdd'),
                DB::raw('SUM(CASE WHEN subtipo.id IN (8,9,15) AND sl.id = 7 THEN 1 ELSE 0 END) AS tdv'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 THEN 1 ELSE 0 END) AS ta'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 6 THEN 1 ELSE 0 END) AS tan'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 1 THEN 1 ELSE 0 END) AS tac'),
                DB::raw('SUM(CASE WHEN subtipo.id = 16 AND sl.id = 7 THEN 1 ELSE 0 END) AS tav')
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
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->groupBy('d.nombre')
            ->orderBy('d.codigo')
            ->get();
    }

    public static function reportesreporte_tabla04($anio, $ugel, $modalidad, $nivel)
    {
        return Nexus::from('edu_nexus as nx')
            ->leftJoin('edu_nexus_regimen_laboral as subtipo', 'subtipo.id', '=', 'nx.regimenlaboral_id')
            ->leftJoin('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'nx.institucioneducativa_id')
            ->leftJoin('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->leftJoin('edu_nexus_modalidad as m', 'm.id', '=', 'nm.modalidad_id')
            ->leftJoin('edu_nexus_situacion_laboral as sl', 'sl.id', '=', 'nx.situacionlaboral_id')
            ->leftJoin('edu_nexus_tipo_ie as tie', 'tie.id', '=', 'ie.tipoie_id')
            ->leftJoin('edu_nexus_gestion as g', 'g.id', '=', 'ie.gestion_id')
            ->leftJoin('edu_nexus_zona as z', 'z.id', '=', 'ie.zona_id')
            ->leftJoin('par_ubigeo as d', 'd.id', '=', 'ie.ubigeo_id')
            ->leftJoin('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->select(
                'ie.cod_mod as modular',
                'ie.institucion_educativa as iiee',
                'tie.nombre as tipo',
                'nm.nombre as nivel',
                'g.nombre as gestion',
                'z.nombre as zona',
                'd.nombre as distrito',
                DB::raw('COUNT(DISTINCT nx.cod_plaza) as conteo'),
                DB::raw('COUNT(DISTINCT CASE WHEN subtipo.dependencia = 1 AND subtipo.id IN (8,9,15) THEN nx.cod_plaza END) AS docentes'),
                DB::raw('COUNT(DISTINCT CASE WHEN subtipo.dependencia = 1 AND subtipo.id = 16 THEN nx.cod_plaza END) AS auxiliar'),
                DB::raw('COUNT(DISTINCT CASE WHEN subtipo.dependencia = 4 THEN nx.cod_plaza END) AS promotor'),
                DB::raw('COUNT(DISTINCT CASE WHEN subtipo.dependencia IN (2,3) THEN nx.cod_plaza END) AS administrativo')
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
            ->when($modalidad > 0, fn($q) => $q->where('nm.modalidad_id', $modalidad))
            ->when($nivel > 0, fn($q) => $q->where('nm.id', $nivel))
            ->groupBy('ie.cod_mod', 'ie.institucion_educativa', 'tie.nombre', 'nm.nombre', 'g.nombre', 'z.nombre', 'd.nombre')
            ->get();
    }
}
