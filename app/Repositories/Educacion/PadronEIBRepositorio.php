<?php

namespace App\Repositories\Educacion;

use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Http\Controllers\Educacion\ImporPadronEibController;
use App\Http\Controllers\Educacion\ImporPadronWebController;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\PadronEIB;
use Illuminate\Support\Facades\DB;

class PadronEIBRepositorio
{
    public static function listaImportada($id)
    {
        $query = PadronEIB::select(
            'edu_padron_eib.id',
            'edu_padron_eib.periodo',
            'v3.codModular as cod_mod',
            'edu_padron_eib.forma_atencion',
            'va.nombre as lengua_1',
            'vb.nombre as lengua_2',
            'vc.nombre as lengua_3',
        )
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_eib.institucioneducativa_id')
            ->leftJoin('par_lengua as va', 'va.id', '=', 'edu_padron_eib.lengua1_id')
            ->leftJoin('par_lengua as vb', 'vb.id', '=', 'edu_padron_eib.lengua2_id')
            ->leftJoin('par_lengua as vc', 'vc.id', '=', 'edu_padron_eib.lengua3_id')
            ->where('edu_padron_eib.importacion_id', $id)
            ->orderBy('edu_padron_eib.id', 'desc')
            ->get();
        return $query;
    }

    public static function listaImportada2($anio, $ugel, $nivel)
    {
        $query = PadronEIB::select(
            'edu_padron_eib.id',
            'edu_padron_eib.periodo as anio',
            //'v5.nombre as dre',
            'v4.nombre as ugel',
            //DB::raw("UCAYALI as departamento"),
            'v8.nombre as provincia',
            'v7.nombre as distrito',
            'v6.nombre as centro_poblado',
            'v3.codModular as cod_mod',
            'v3.codLocal as cod_local',
            'v3.nombreInstEduc as institucion_educativa',
            'v9.codigo as cod_nivelmod',
            'v9.nombre as nivel_modalidad',
            'edu_padron_eib.forma_atencion',
            'va.nombre as lengua1',
            'vb.nombre as lengua2',
            'vc.nombre as lengua3',
        )
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_eib.institucioneducativa_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v3.Ugel_id')
            //->join('edu_ugel as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_centropoblado as v6', 'v6.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v7', 'v7.id', '=', 'v6.Ubigeo_id')
            ->join('par_ubigeo as v8', 'v8.id', '=', 'v7.dependencia')
            ->join('edu_nivelmodalidad as v9', 'v9.id', '=', 'v3.NivelModalidad_id')
            ->join('par_lengua as va', 'va.id', '=', 'edu_padron_eib.lengua1_id', 'left')
            ->join('par_lengua as vb', 'vb.id', '=', 'edu_padron_eib.lengua2_id', 'left')
            ->join('par_lengua as vc', 'vc.id', '=', 'edu_padron_eib.lengua3_id', 'left')
            //->where('edu_padron_eib.importacion_id', $id)
            ->orderBy('edu_padron_eib.id', 'desc');
        if ($anio != 0) $query = $query->where('edu_padron_eib.periodo', $anio);
        if ($nivel != 0) $query = $query->where('v9.id', $nivel);
        if ($ugel != 0) $query = $query->where('v4.id', $ugel);
        $query = $query->get();
        return $query;
    }

    public static function maxid()
    {
        return PadronEIB::select('v1.id')
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_padron_eib.importacion_id')
            ->orderBy('v1.fechaActualizacion', 'desc')->first()->id;
    }

    public static function rango_anios_segun_eib()
    {
        $minYear = Importacion::where('fuenteImportacion_id', ImporPadronEibController::$FUENTE)->where('estado', 'PR')->min(DB::raw('YEAR(fechaActualizacion)'));
        $maxYear = Importacion::where('fuenteImportacion_id', ImporPadronWebController::$FUENTE)->where('estado', 'PR')->max(DB::raw('YEAR(fechaActualizacion)'));
        return range($minYear, $maxYear);
    }

    public static function getYearMapping(int $inputYear)
    {
        $maxYear = Importacion::where('fuenteImportacion_id', 12)->where('estado', 'PR')->selectRaw('MAX(YEAR(fechaActualizacion)) as year')->whereRaw('YEAR(fechaActualizacion) <= ?', [$inputYear])->first();
        if ($maxYear && $maxYear->year !== null) {
            return (int)$maxYear->year;
        }
        $minYear = Importacion::where('fuenteImportacion_id', 12)->where('estado', 'PR')->selectRaw('MIN(YEAR(fechaActualizacion)) as year')->first();
        return $minYear ? (int)$minYear->year : null;
    }

    public static function gestion_select($anioImportacion, $periodoEIB)
    {
        return DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')
                    ->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                        $sub->select('id')
                            ->from('par_importacion')
                            ->where('fuenteImportacion_id', 1)
                            ->where('estado', 'PR')
                            ->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])
                            ->orderBy('fechaActualizacion', 'desc')
                            ->orderBy('id', 'desc')
                            ->limit(1);
                    });
            })
            ->join('edu_padron_eib as petb', function ($join) use ($periodoEIB) {
                $join->on('petb.institucioneducativa_id', '=', 'pw.institucioneducativa_id')
                    ->where('petb.periodo', '=', $periodoEIB);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->where('pw.estadoinsedu_id', 3)
            ->select('tg.id', DB::raw("CONCAT(COALESCE(tg.codigo, ''), ' | ', COALESCE(tg.nombre, '')) as nombre"))
            ->distinct()
            ->get()->pluck('nombre', 'id');
    }

    public static function provincia_select($anioImportacion, $periodoEIB)
    {
        return DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')
                    ->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                        $sub->select('id')
                            ->from('par_importacion')
                            ->where('fuenteImportacion_id', 1)
                            ->where('estado', 'PR')
                            ->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])
                            ->orderBy('fechaActualizacion', 'desc')
                            ->orderBy('id', 'desc')
                            ->limit(1);
                    });
            })
            ->join('edu_padron_eib as petb', function ($join) use ($periodoEIB) {
                $join->on('petb.institucioneducativa_id', '=', 'pw.institucioneducativa_id')
                    ->where('petb.periodo', '=', $periodoEIB);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->where('pw.estadoinsedu_id', 3)
            ->select('p.id', 'p.nombre')
            ->distinct()
            ->get()->pluck('nombre', 'id');
    }

    public static function distrito_select($anioImportacion, $periodoEIB, $provincia)
    {
        return DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')
                    ->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                        $sub->select('id')
                            ->from('par_importacion')
                            ->where('fuenteImportacion_id', 1)
                            ->where('estado', 'PR')
                            ->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])
                            ->orderBy('fechaActualizacion', 'desc')
                            ->orderBy('id', 'desc')
                            ->limit(1);
                    });
            })
            ->join('edu_padron_eib as petb', function ($join) use ($periodoEIB) {
                $join->on('petb.institucioneducativa_id', '=', 'pw.institucioneducativa_id')
                    ->where('petb.periodo', '=', $periodoEIB);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->where('pw.estadoinsedu_id', 3)
            ->when($provincia > 0, fn($query) => $query->where('p.id', $provincia))
            ->select('d.id', 'd.nombre')
            ->distinct()
            ->orderBy('d.codigo', 'asc')->get()->pluck('nombre', 'id');
    }

    public static function reportesreporte_head($anioImportacion, $periodo, $gestion, $provincia, $distrito)
    {
        $result = DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')
                    ->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                        $sub->select('id')
                            ->from('par_importacion')
                            ->where('fuenteImportacion_id', 1)
                            ->where('estado', 'PR')
                            ->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])
                            ->orderBy('fechaActualizacion', 'desc')
                            ->orderBy('id', 'desc')
                            ->limit(1);
                    });
            })
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'pw.institucioneducativa_id')
                    ->where('peib.periodo', '=', $periodo);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->where('pw.estadoinsedu_id', 3)
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->select(DB::raw('COUNT(*) as conteo'), DB::raw('COUNT(DISTINCT peib.lengua1_id) as lengua'))
            ->first();

        // Si no hay resultados, devolvemos 0s (evita null)
        return $result ?: (object) ['conteo' => 0, 'lenguas' => 0];
    }

    public static function reportesreporte_head2xx($anio, $periodo, $gestion, $provincia, $distrito)
    {
        $importIdMatricula = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 34)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        if (! $importIdMatricula || ! $importIdPadron) {
            return 0;
        }
        $count = DB::table('edu_matricula_general_detalle as mgd')
            ->join('edu_matricula_general as mg', 'mg.id', '=', 'mgd.matriculageneral_id')
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'mgd.institucioneducativa_id')->where('peib.periodo', $periodo);
            })
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')->where('pw.importacion_id', $importIdPadron)->where('pw.estadoinsedu_id', 3);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'mgd.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->where('mg.importacion_id', $importIdMatricula)
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->count(DB::raw('DISTINCT mgd.id'));

        return $count;
    }

    public static function reportesreporte_head2($anio, $periodo, $gestion, $provincia, $distrito)
    {
        // 1. Obtener ID de la última importación válida para matrícula (fuente 34)
        $importIdMatricula = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 34)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', ($anio) . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        // 2. Obtener ID de la última importación válida para padrón web (fuente 1)
        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', ($anio) . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        // Si alguna importación no existe, retornar 0
        if (! $importIdMatricula || ! $importIdPadron) {
            return 0;
        }

        // 3. Realizar el conteo
        $count = DB::table('edu_matricula_general_detalle as mgd')
            ->join('edu_matricula_general as mg', 'mg.id', '=', 'mgd.matriculageneral_id')
            ->where('mg.importacion_id', $importIdMatricula)
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'mgd.institucioneducativa_id')
                    ->where('peib.periodo', $periodo);
            })
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')
                    ->where('pw.importacion_id', $importIdPadron)
                    ->where('pw.estadoinsedu_id', 3);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'mgd.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->count(DB::raw('DISTINCT mgd.id')); // ✅ evita duplicados

        return (int) $count;
    }

    public static function reportesreporte_head3($anio, $periodo,  $gestion, $provincia, $distrito)
    {
        $importIdNexus = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 2)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', ($anio) . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', ($anio) . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        if (! $importIdNexus || ! $importIdPadron) {
            return 0;
        }
        $subquery = DB::table('edu_padron_eib as peib')
            ->join('edu_institucioneducativa as iex', 'iex.id', '=', 'peib.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iex.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')->where('pw.importacion_id', $importIdPadron)->where('pw.estadoinsedu_id', 3);
            })
            ->where('peib.periodo', $periodo)
            ->select('iex.codModular', 'iex.TipoGestion_id', 'd.id as distrito', 'd.dependencia as provincia');
        $conteo = DB::table('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'n.regimenlaboral_id')
            ->join(DB::raw('(' . $subquery->toSql() . ') as pie'), function ($join) {
                $join->on('pie.codModular', '=', 'ie.cod_mod');
            })
            ->mergeBindings($subquery)
            ->where('n.importacion_id', $importIdNexus)
            ->when($gestion > 0, fn($query) => $query->where('pie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('pie.provincia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('pie.distrito', $distrito))
            ->count(DB::raw('DISTINCT n.cod_plaza'));

        return $conteo;
    }

    public static function reportesreporte_anal1($anioImportacion, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')
                    ->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                        $sub->select('id')
                            ->from('par_importacion')
                            ->where('fuenteImportacion_id', 1)
                            ->where('estado', 'PR')
                            ->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])
                            ->orderBy('fechaActualizacion', 'desc')
                            ->orderBy('id', 'desc')
                            ->limit(1);
                    });
            })
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'pw.institucioneducativa_id')
                    ->where('peib.periodo', '=', $periodo);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->where('pw.estadoinsedu_id', 3)
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
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
                DB::raw('COUNT(*) as conteo')
            )
            ->groupBy('p.codigo', 'p.nombre')
            ->orderBy('p.nombre')
            ->get();
    }

    public static function reportesreporte_anal2_xanio($anioImportacion, $periodo, $gestion, $provincia, $distrito)
    {
        return (int) DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                    $sub->select('id')->from('par_importacion')->where('fuenteImportacion_id', 1)->where('estado', 'PR')->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])->orderBy('fechaActualizacion', 'desc')->orderBy('id', 'desc')->limit(1);
                });
            })
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'pw.institucioneducativa_id')->where('peib.periodo', '=', $periodo);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->where('pw.estadoinsedu_id', 3)
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->count();
    }

    public static function reportesreporte_anal3($anioImportacion, $periodo, $gestion, $provincia, $distrito)
    {
        return DB::table('edu_padronweb as pw')
            ->join('par_importacion as pi', function ($join) use ($anioImportacion) {
                $join->on('pi.id', '=', 'pw.importacion_id')
                    ->where('pi.id', '=', function ($sub) use ($anioImportacion) {
                        $sub->select('id')->from('par_importacion')->where('fuenteImportacion_id', 1)->where('estado', 'PR')->whereRaw('YEAR(fechaActualizacion) <= ?', [$anioImportacion])->orderBy('fechaActualizacion', 'desc')->orderBy('id', 'desc')->limit(1);
                    });
            })
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'pw.institucioneducativa_id')->where('peib.periodo', '=', $periodo);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'pw.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('pw.estadoinsedu_id', 3)
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->select(DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL' ELSE nm.nombre END as name"), DB::raw('COUNT(*) as y'))
            ->groupBy('name')
            ->orderBy('name')
            ->get();
    }

    public static function contarMatriculasEIB($anio, $periodo, $gestion, $provincia, $distrito)
    {
        $importIdMatricula = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 34)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        if (! $importIdMatricula || ! $importIdPadron) {
            return 0;
        }
        $count = DB::table('edu_matricula_general_detalle as mgd')
            ->join('edu_matricula_general as mg', 'mg.id', '=', 'mgd.matriculageneral_id')
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'mgd.institucioneducativa_id')->where('peib.periodo', $periodo);
            })
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')->where('pw.importacion_id', $importIdPadron)->where('pw.estadoinsedu_id', 3);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'mgd.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->where('mg.importacion_id', $importIdMatricula)
            ->when($gestion > 0, fn($query) => $query->where('ie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('d.dependencia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('d.id', $distrito))
            ->count(DB::raw('DISTINCT mgd.id'));

        return $count;
    }

    public function contarPlazasNexusEIB($periodo, $anio, $gestion, $provincia, $distrito)
    {
        $importIdNexus = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 2)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        if (! $importIdNexus || ! $importIdPadron) {
            return 0;
        }
        $subquery = DB::table('edu_padron_eib as peib')
            ->join('edu_institucioneducativa as iex', 'iex.id', '=', 'peib.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iex.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')->where('pw.importacion_id', $importIdPadron)->where('pw.estadoinsedu_id', 3);
            })
            ->where('peib.periodo', $periodo)
            ->select('iex.codModular', 'iex.TipoGestion_id', 'd.id as distrito', 'd.dependencia as provincia');
        $conteo = DB::table('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'n.regimenlaboral_id')
            ->join(DB::raw('(' . $subquery->toSql() . ') as pie'), function ($join) {
                $join->on('pie.codModular', '=', 'ie.cod_mod');
            })
            ->mergeBindings($subquery)
            ->where('n.importacion_id', $importIdNexus)
            ->when($gestion > 0, fn($query) => $query->where('pie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('pie.provincia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('pie.distrito', $distrito))
            ->count(DB::raw('DISTINCT n.cod_plaza'));

        return $conteo;
    }

    public static function contarMatriculasPorNivelEIB($anio, $periodo, $gestion, $provincia, $distrito)
    {
        $importIdMatricula = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 34)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        if (! $importIdMatricula || ! $importIdPadron) {
            return [];
        }
        $results = DB::table('edu_matricula_general_detalle as mgd')
            ->join('edu_matricula_general as mg', 'mg.id', '=', 'mgd.matriculageneral_id')
            ->where('mg.importacion_id', $importIdMatricula)
            ->join('edu_padron_eib as peib', function ($join) use ($periodo) {
                $join->on('peib.institucioneducativa_id', '=', 'mgd.institucioneducativa_id')->where('peib.periodo', $periodo);
            })
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')->where('pw.importacion_id', $importIdPadron)->where('pw.estadoinsedu_id', 3);
            })
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'mgd.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->select([DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL' ELSE nm.nombre END AS name"), DB::raw('COUNT(*) as y')])
            ->groupBy(DB::raw("CASE WHEN LOWER(nm.nombre) LIKE '%inicial%' THEN 'INICIAL'  ELSE nm.nombre  END"));
        return $results->get();
    }


    public static function contarPlazasPorSituacionEIB($anio, $periodo, $gestion, $provincia, $distrito)
    {
        $importIdNexus = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 2)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');
        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        if (! $importIdNexus || ! $importIdPadron) {
            return collect();
        }
        $subquery = DB::table('edu_padron_eib as peib')
            ->join('edu_institucioneducativa as iex', 'iex.id', '=', 'peib.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iex.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')->where('pw.importacion_id', $importIdPadron)->where('pw.estadoinsedu_id', 3);
            })
            ->where('peib.periodo', $periodo)
            ->select('iex.codModular', 'iex.TipoGestion_id', 'd.dependencia as provincia', 'd.id as distrito');
        $results = DB::table('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'n.regimenlaboral_id')
            ->join('edu_nexus_situacion_laboral as sl', function ($join) {
                $join->on('sl.id', '=', 'n.situacionlaboral_id')->whereIn('sl.id', [1, 6]);
            })
            ->join(DB::raw('(' . $subquery->toSql() . ') as pie'), function ($join) {
                $join->on('pie.codModular', '=', 'ie.cod_mod');
            })
            ->mergeBindings($subquery)
            ->where('n.importacion_id', $importIdNexus)
            ->when($gestion > 0, fn($query) => $query->where('pie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('pie.provincia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('pie.distrito', $distrito))
            ->select('sl.nombre as name', DB::raw('COUNT(DISTINCT n.cod_plaza) as y'))
            ->groupBy('sl.nombre');

        return $results->get();
    }


    public static function getPlazasPorCondicionYNivel($anio, $periodo, $gestion, $provincia, $distrito)
    {
        // 1. IDs de importaciones
        $importIdNexus = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 2)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        $importIdPadron = DB::table('par_importacion')
            ->where('fuenteImportacion_id', 1)
            ->where('estado', 'PR')
            ->where('fechaActualizacion', '<=', $anio . '-12-31')
            ->orderBy('fechaActualizacion', 'desc')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->value('id');

        if (! $importIdNexus || ! $importIdPadron) {
            return collect();
        }

        // 2. Subconsulta: instituciones EIB + padrón web + ubigeo
        $subquery = DB::table('edu_padron_eib as peib')
            ->join('edu_institucioneducativa as iex', 'iex.id', '=', 'peib.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iex.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('edu_padronweb as pw', function ($join) use ($importIdPadron) {
                $join->on('pw.institucioneducativa_id', '=', 'peib.institucioneducativa_id')
                    ->where('pw.importacion_id', $importIdPadron)
                    ->where('pw.estadoinsedu_id', 3);
            })
            ->where('peib.periodo', $periodo)
            ->select('iex.codModular', 'iex.TipoGestion_id', 'd.dependencia as provincia', 'd.id as distrito');

        // 3. Consulta principal
        $results = DB::table('edu_nexus as n')
            ->join('edu_nexus_institucion_educativa as ie', 'ie.id', '=', 'n.institucioneducativa_id')
            ->join('edu_nexus_regimen_laboral as stt', 'stt.id', '=', 'n.regimenlaboral_id')
            ->join('edu_nexus_nivel_educativo as nm', 'nm.id', '=', 'ie.niveleducativo_id')
            ->join('edu_nexus_situacion_laboral as sl', function ($join) {
                $join->on('sl.id', '=', 'n.situacionlaboral_id')
                    ->whereIn('sl.id', [1, 6]); // NOMBRADO=1, CONTRATADO=6
            })
            ->join(DB::raw('(' . $subquery->toSql() . ') as pie'), function ($join) {
                $join->on('pie.codModular', '=', 'ie.cod_mod');
            })
            ->mergeBindings($subquery)
            ->where('n.importacion_id', $importIdNexus)
            ->when($gestion > 0, fn($query) => $query->where('pie.TipoGestion_id', $gestion))
            ->when($provincia > 0, fn($query) => $query->where('pie.provincia', $provincia))
            ->when($distrito > 0, fn($query) => $query->where('pie.distrito', $distrito))
            ->select([
                'sl.nombre as condicion',
                DB::raw("CASE 
                WHEN nm.nombre LIKE '%INICIAL%' THEN 'INICIAL' 
                ELSE nm.nombre 
            END AS nivel"),
                DB::raw('COUNT(DISTINCT n.cod_plaza) AS conteo')
            ])
            ->groupBy('sl.nombre', 'nivel')
            ->get();

        return $results;
    }

    public static function consolidadoPorFormaAtencionxx($anio, $periodo, $gestion, $provincia, $distrito)
    {
        $sql = "WITH
                servicios AS (
                    SELECT
                        pie.forma_atencion,
                        COUNT(DISTINCT pie.codModular) AS ts,
                        COUNT(DISTINCT CASE WHEN pie.area = 1 THEN pie.codModular END) AS tsu,
                        COUNT(DISTINCT CASE WHEN pie.area = 2 THEN pie.codModular END) AS tsr
                    FROM edu_nexus n
                    INNER JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                    INNER JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                    INNER JOIN edu_nexus_nivel_educativo nm ON nm.id = ie.niveleducativo_id 
                    INNER JOIN edu_nexus_situacion_laboral sl ON sl.id = n.situacionlaboral_id
                    INNER JOIN (
                        SELECT iex.codModular, iex.TipoGestion_id, d.dependencia AS provincia, d.id AS distrito, peib.forma_atencion, iex.Area_id AS area
                        FROM edu_padron_eib peib 
                        INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                        INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                        INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                        INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                            AND pw.importacion_id = (
                                SELECT id FROM par_importacion 
                                WHERE fuenteImportacion_id = 1 
                                AND estado = 'PR' 
                                AND YEAR(fechaActualizacion) <= ?
                                ORDER BY fechaActualizacion DESC, id DESC 
                                LIMIT 1
                            )
                            AND pw.estadoinsedu_id = 3
                        WHERE peib.periodo = ?
                    ) pie ON pie.codModular = ie.cod_mod 
                    WHERE n.importacion_id = (
                        SELECT id FROM par_importacion 
                        WHERE fuenteImportacion_id = 2 
                        AND estado = 'PR' 
                        AND YEAR(fechaActualizacion) <= ?
                        ORDER BY fechaActualizacion DESC, id DESC 
                        LIMIT 1
                    )
                    GROUP BY pie.forma_atencion
                ),
                estudiantes AS (
                    SELECT 
                        peib.forma_atencion, 
                        COUNT(*) AS em,
                        COUNT(CASE WHEN ie.Area_id = 1 THEN 1 END) AS emu,
                        COUNT(CASE WHEN ie.Area_id = 2 THEN 1 END) AS emr
                    FROM edu_matricula_general_detalle mgd 
                    JOIN edu_matricula_general mg ON mg.id = mgd.matriculageneral_id
                    JOIN par_importacion i ON i.id = mg.importacion_id 
                        AND i.id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 34 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                    JOIN edu_padron_eib peib ON peib.institucioneducativa_id = mgd.institucioneducativa_id 
                        AND peib.periodo = ?
                    JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    JOIN edu_institucioneducativa ie ON ie.id = mgd.institucioneducativa_id
                    GROUP BY peib.forma_atencion
                ),
                docentes AS (
                    SELECT 
                        peib.forma_atencion,
                        SUM(dct.td) AS td, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.td END) AS tdu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.td END) AS tdr,
                        SUM(dct.ta) AS ta, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.ta END) AS tau,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.ta END) AS tar,
                        SUM(dct.tp) AS tp, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.tp END) AS tpu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.tp END) AS tpr
                    FROM edu_padron_eib peib 
                    INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                    INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                    INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                    INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN (
                        SELECT 
                            ie.cod_mod, 
                            COUNT(DISTINCT CASE WHEN stt.id IN (8,9,15) THEN n.cod_plaza END) AS td,
                            COUNT(DISTINCT CASE WHEN stt.id = 16 THEN n.cod_plaza END) AS ta,
                            COUNT(DISTINCT CASE WHEN stt.dependencia = 4 THEN n.cod_plaza END) AS tp 
                        FROM edu_nexus n
                        JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                        JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                        WHERE n.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 2 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        GROUP BY ie.cod_mod
                    ) AS dct ON dct.cod_mod = iex.codModular
                    WHERE peib.periodo = ?
                    GROUP BY peib.forma_atencion
                )
                SELECT 
                    COALESCE(s.forma_atencion, e.forma_atencion, d.forma_atencion) AS forma_atencion,
                    COALESCE(s.ts, 0)  AS ts,
                    COALESCE(s.tsu, 0) AS tsu,
                    COALESCE(s.tsr, 0) AS tsr,
                    COALESCE(e.em, 0)  AS em,
                    COALESCE(e.emu, 0) AS emu,
                    COALESCE(e.emr, 0) AS emr,
                    COALESCE(d.td, 0)  AS td,
                    COALESCE(d.tdu, 0) AS tdu,
                    COALESCE(d.tdr, 0) AS tdr,
                    COALESCE(d.ta, 0)  AS ta,
                    COALESCE(d.tau, 0) AS tau,
                    COALESCE(d.tar, 0) AS tar,
                    COALESCE(d.tp, 0)  AS tp,
                    COALESCE(d.tpu, 0) AS tpu,
                    COALESCE(d.tpr, 0) AS tpr
                FROM (
                    SELECT forma_atencion FROM servicios
                    UNION
                    SELECT forma_atencion FROM estudiantes
                    UNION
                    SELECT forma_atencion FROM docentes
                ) AS todas_formas
                LEFT JOIN servicios s ON s.forma_atencion = todas_formas.forma_atencion
                LEFT JOIN estudiantes e ON e.forma_atencion = todas_formas.forma_atencion
                LEFT JOIN docentes d ON d.forma_atencion = todas_formas.forma_atencion
                ORDER BY forma_atencion";

        // Parámetros (ordenados según aparición en ?)
        $params = [
            $anio,  // pw.importacion_id subquery (fuente 1)
            $periodo,            // peib.periodo en servicios
            $anio,  // n.importacion_id (fuente 2)
            $anio,  // estudiantes → i.id subquery (fuente 34)
            $periodo,            // peib.periodo en estudiantes
            $anio,  // pw.importacion_id en estudiantes (fuente 1)
            $anio,  // pw.importacion_id en docentes (fuente 1)
            $anio,  // dct subquery (fuente 2)
            $periodo             // peib.periodo en docentes
        ];

        return DB::select(DB::raw($sql), $params);
    }

    public static function consolidadoPorFormaAtencionxxx($anio, $periodo, $gestion = null, $provincia = null, $distrito = null)
    {
        $sql = "WITH
                servicios AS (
                    SELECT
                        pie.forma_atencion,
                        COUNT(DISTINCT pie.codModular) AS ts,
                        COUNT(DISTINCT CASE WHEN pie.area = 1 THEN pie.codModular END) AS tsu,
                        COUNT(DISTINCT CASE WHEN pie.area = 2 THEN pie.codModular END) AS tsr
                    FROM edu_nexus n
                    INNER JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                    INNER JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                    INNER JOIN edu_nexus_nivel_educativo nm ON nm.id = ie.niveleducativo_id 
                    INNER JOIN edu_nexus_situacion_laboral sl ON sl.id = n.situacionlaboral_id
                    INNER JOIN (
                        SELECT iex.codModular, iex.TipoGestion_id, d.dependencia AS provincia, d.id AS distrito, peib.forma_atencion, iex.Area_id AS area
                        FROM edu_padron_eib peib 
                        INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                        INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                        INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                        INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                            AND pw.importacion_id = (
                                SELECT id FROM par_importacion 
                                WHERE fuenteImportacion_id = 1 
                                AND estado = 'PR' 
                                AND YEAR(fechaActualizacion) <= ?
                                ORDER BY fechaActualizacion DESC, id DESC 
                                LIMIT 1
                            )
                            AND pw.estadoinsedu_id = 3
                        WHERE peib.periodo = ?
                    ) pie ON pie.codModular = ie.cod_mod 
                    WHERE n.importacion_id = (
                        SELECT id FROM par_importacion 
                        WHERE fuenteImportacion_id = 2 
                        AND estado = 'PR' 
                        AND YEAR(fechaActualizacion) <= ?
                        ORDER BY fechaActualizacion DESC, id DESC 
                        LIMIT 1
                    )
                    GROUP BY pie.forma_atencion
                ),
                estudiantes AS (
                    SELECT 
                        peib.forma_atencion, 
                        COUNT(*) AS em,
                        COUNT(CASE WHEN ie.Area_id = 1 THEN 1 END) AS emu,
                        COUNT(CASE WHEN ie.Area_id = 2 THEN 1 END) AS emr
                    FROM edu_matricula_general_detalle mgd 
                    JOIN edu_matricula_general mg ON mg.id = mgd.matriculageneral_id
                    JOIN par_importacion i ON i.id = mg.importacion_id 
                        AND i.id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 34 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                    JOIN edu_padron_eib peib ON peib.institucioneducativa_id = mgd.institucioneducativa_id 
                        AND peib.periodo = ?
                    JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    JOIN edu_institucioneducativa ie ON ie.id = mgd.institucioneducativa_id
                    GROUP BY peib.forma_atencion
                ),
                docentes AS (
                    SELECT 
                        peib.forma_atencion,
                        SUM(dct.td) AS td, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.td END) AS tdu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.td END) AS tdr,
                        SUM(dct.ta) AS ta, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.ta END) AS tau,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.ta END) AS tar,
                        SUM(dct.tp) AS tp, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.tp END) AS tpu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.tp END) AS tpr
                    FROM edu_padron_eib peib 
                    INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                    INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                    INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                    INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN (
                        SELECT 
                            ie.cod_mod, 
                            COUNT(DISTINCT CASE WHEN stt.id IN (8,9,15) THEN n.cod_plaza END) AS td,
                            COUNT(DISTINCT CASE WHEN stt.id = 16 THEN n.cod_plaza END) AS ta,
                            COUNT(DISTINCT CASE WHEN stt.dependencia = 4 THEN n.cod_plaza END) AS tp 
                        FROM edu_nexus n
                        JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                        JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                        WHERE n.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 2 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        GROUP BY ie.cod_mod
                    ) AS dct ON dct.cod_mod = iex.codModular
                    WHERE peib.periodo = ?
                    GROUP BY peib.forma_atencion
                )
                SELECT 
                    COALESCE(s.forma_atencion, e.forma_atencion, d.forma_atencion) AS forma_atencion,
                    COALESCE(s.ts, 0)  AS ts,
                    COALESCE(s.tsu, 0) AS tsu,
                    COALESCE(s.tsr, 0) AS tsr,
                    COALESCE(e.em, 0)  AS em,
                    COALESCE(e.emu, 0) AS emu,
                    COALESCE(e.emr, 0) AS emr,
                    COALESCE(d.td, 0)  AS td,
                    COALESCE(d.tdu, 0) AS tdu,
                    COALESCE(d.tdr, 0) AS tdr,
                    COALESCE(d.ta, 0)  AS ta,
                    COALESCE(d.tau, 0) AS tau,
                    COALESCE(d.tar, 0) AS tar,
                    COALESCE(d.tp, 0)  AS tp,
                    COALESCE(d.tpu, 0) AS tpu,
                    COALESCE(d.tpr, 0) AS tpr
                FROM (
                    SELECT forma_atencion FROM servicios
                    UNION
                    SELECT forma_atencion FROM estudiantes
                    UNION
                    SELECT forma_atencion FROM docentes
                ) AS todas_formas
                LEFT JOIN servicios s ON s.forma_atencion = todas_formas.forma_atencion
                LEFT JOIN estudiantes e ON e.forma_atencion = todas_formas.forma_atencion
                LEFT JOIN docentes d ON d.forma_atencion = todas_formas.forma_atencion
                ORDER BY forma_atencion";

        $params = [
            $anio,
            $periodo,
            $anio,
            $anio,
            $periodo,
            $anio,
            $anio,
            $anio,
            $periodo,
        ];
        return DB::table(DB::raw("({$sql}) as consolidado"))
            ->select(
                'forma_atencion',
                DB::raw('ts'),
                DB::raw('tsu'),
                DB::raw('tsr'),
                DB::raw('em'),
                DB::raw('emu'),
                DB::raw('emr'),
                DB::raw('td'),
                DB::raw('tdu'),
                DB::raw('tdr'),
                DB::raw('ta'),
                DB::raw('tau'),
                DB::raw('tar'),
                DB::raw('tp'),
                DB::raw('tpu'),
                DB::raw('tpr')
            )
            ->setBindings($params)
            ->get();
    }

    public static function reportesreporte_tabla1($anio, $periodo, $gestion = null, $provincia = null, $distrito = null)
    {
        $sql = "WITH
                servicios AS (
                    SELECT
                        pie.forma_atencion,
                        COUNT(DISTINCT pie.codModular) AS ts,
                        COUNT(DISTINCT CASE WHEN pie.area = 1 THEN pie.codModular END) AS tsu,
                        COUNT(DISTINCT CASE WHEN pie.area = 2 THEN pie.codModular END) AS tsr
                    FROM edu_nexus n
                    INNER JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                    INNER JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                    INNER JOIN edu_nexus_nivel_educativo nm ON nm.id = ie.niveleducativo_id 
                    INNER JOIN edu_nexus_situacion_laboral sl ON sl.id = n.situacionlaboral_id
                    INNER JOIN (
                        SELECT iex.codModular, iex.TipoGestion_id, d.dependencia AS provincia, d.id AS distrito, peib.forma_atencion, iex.Area_id AS area
                        FROM edu_padron_eib peib 
                        INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                        INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                        INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                        INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                            AND pw.importacion_id = (
                                SELECT id FROM par_importacion 
                                WHERE fuenteImportacion_id = 1 
                                AND estado = 'PR' 
                                AND YEAR(fechaActualizacion) <= ?
                                ORDER BY fechaActualizacion DESC, id DESC 
                                LIMIT 1
                            )
                            AND pw.estadoinsedu_id = 3
                        WHERE peib.periodo = ?
                    ) pie ON pie.codModular = ie.cod_mod 
                    WHERE n.importacion_id = (
                        SELECT id FROM par_importacion 
                        WHERE fuenteImportacion_id = 2 
                        AND estado = 'PR' 
                        AND YEAR(fechaActualizacion) <= ?
                        ORDER BY fechaActualizacion DESC, id DESC 
                        LIMIT 1
                    )
                    GROUP BY pie.forma_atencion
                ),
                estudiantes AS (
                    SELECT 
                        peib.forma_atencion, 
                        COUNT(*) AS em,
                        COUNT(CASE WHEN ie.Area_id = 1 THEN 1 END) AS emu,
                        COUNT(CASE WHEN ie.Area_id = 2 THEN 1 END) AS emr
                    FROM edu_matricula_general_detalle mgd 
                    JOIN edu_matricula_general mg ON mg.id = mgd.matriculageneral_id
                    JOIN par_importacion i ON i.id = mg.importacion_id 
                        AND i.id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 34 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                    JOIN edu_padron_eib peib ON peib.institucioneducativa_id = mgd.institucioneducativa_id 
                        AND peib.periodo = ?
                    JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    JOIN edu_institucioneducativa ie ON ie.id = mgd.institucioneducativa_id
                    GROUP BY peib.forma_atencion
                ),
                docentes AS (
                    SELECT 
                        peib.forma_atencion,
                        SUM(dct.td) AS td, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.td END) AS tdu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.td END) AS tdr,
                        SUM(dct.ta) AS ta, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.ta END) AS tau,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.ta END) AS tar,
                        SUM(dct.tp) AS tp, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.tp END) AS tpu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.tp END) AS tpr
                    FROM edu_padron_eib peib 
                    INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                    INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                    INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                    INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN (
                        SELECT 
                            ie.cod_mod, 
                            COUNT(DISTINCT CASE WHEN stt.id IN (8,9,15) THEN n.cod_plaza END) AS td,
                            COUNT(DISTINCT CASE WHEN stt.id = 16 THEN n.cod_plaza END) AS ta,
                            COUNT(DISTINCT CASE WHEN stt.dependencia = 4 THEN n.cod_plaza END) AS tp 
                        FROM edu_nexus n
                        JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                        JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                        WHERE n.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 2 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        GROUP BY ie.cod_mod
                    ) AS dct ON dct.cod_mod = iex.codModular
                    WHERE peib.periodo = ?
                    GROUP BY peib.forma_atencion
                )
                SELECT 
                    COALESCE(s.forma_atencion, e.forma_atencion, d.forma_atencion) AS forma_atencion,
                    COALESCE(s.ts, 0)  AS servicios_total,
                    COALESCE(s.tsu, 0) AS servicios_urbano,
                    COALESCE(s.tsr, 0) AS servicios_rural,
                    COALESCE(e.em, 0)  AS estudiantes_total,
                    COALESCE(e.emu, 0) AS estudiantes_urbano,
                    COALESCE(e.emr, 0) AS estudiantes_rural,
                    COALESCE(d.td, 0)  AS docentes_total,
                    COALESCE(d.tdu, 0) AS docentes_urbano,
                    COALESCE(d.tdr, 0) AS docentes_rural,
                    COALESCE(d.ta, 0)  AS auxiliares_total,
                    COALESCE(d.tau, 0) AS auxiliares_urbano,
                    COALESCE(d.tar, 0) AS auxiliares_rural,
                    COALESCE(d.tp, 0)  AS pec_total,
                    COALESCE(d.tpu, 0) AS pec_urbano,
                    COALESCE(d.tpr, 0) AS pec_rural
                FROM (
                    SELECT forma_atencion FROM servicios
                    UNION
                    SELECT forma_atencion FROM estudiantes
                    UNION
                    SELECT forma_atencion FROM docentes
                ) AS todas_formas
                LEFT JOIN servicios s ON s.forma_atencion = todas_formas.forma_atencion
                LEFT JOIN estudiantes e ON e.forma_atencion = todas_formas.forma_atencion
                LEFT JOIN docentes d ON d.forma_atencion = todas_formas.forma_atencion
                ORDER BY forma_atencion";

        $params = [
            $anio,
            $periodo,
            $anio,
            $anio,
            $periodo,
            $anio,
            $anio,
            $anio,
            $periodo,
        ];

        return DB::table(DB::raw("({$sql}) as consolidado"))
            ->select(
                'forma_atencion',
                'servicios_total',
                'servicios_urbano',
                'servicios_rural',
                'estudiantes_total',
                'estudiantes_urbano',
                'estudiantes_rural',
                'docentes_total',
                'docentes_urbano',
                'docentes_rural',
                'auxiliares_total',
                'auxiliares_urbano',
                'auxiliares_rural',
                'pec_total',
                'pec_urbano',
                'pec_rural'
            )
            ->setBindings($params)
            ->get();
    }

    public static function consolidadoPorNivelEducativo($anio, $periodo, $gestion = null, $provincia = null, $distrito = null)
    {
        $sql = "WITH
                servicios AS (
                    SELECT
                        CASE 
                            WHEN nm.nombre LIKE '%programa%' THEN 'PRONOEI' 
                            ELSE nm.nombre 
                        END AS nivel_educativo,
                        COUNT(DISTINCT pie.codModular) AS ts,
                        COUNT(DISTINCT CASE WHEN pie.area = 1 THEN pie.codModular END) AS tsu,
                        COUNT(DISTINCT CASE WHEN pie.area = 2 THEN pie.codModular END) AS tsr
                    FROM edu_nexus n
                    INNER JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                    INNER JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                    INNER JOIN edu_nexus_nivel_educativo nm ON nm.id = ie.niveleducativo_id 
                    INNER JOIN edu_nexus_situacion_laboral sl ON sl.id = n.situacionlaboral_id
                    INNER JOIN (
                        SELECT iex.codModular, iex.TipoGestion_id, d.dependencia AS provincia, d.id AS distrito, peib.forma_atencion, iex.Area_id AS area
                        FROM edu_padron_eib peib 
                        INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                        INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                        INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                        INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                            AND pw.importacion_id = (
                                SELECT id FROM par_importacion 
                                WHERE fuenteImportacion_id = 1 
                                AND estado = 'PR' 
                                AND YEAR(fechaActualizacion) <= ?
                                ORDER BY fechaActualizacion DESC, id DESC 
                                LIMIT 1
                            )
                            AND pw.estadoinsedu_id = 3
                        WHERE peib.periodo = ?
                    ) pie ON pie.codModular = ie.cod_mod 
                    WHERE n.importacion_id = (
                        SELECT id FROM par_importacion 
                        WHERE fuenteImportacion_id = 2 
                        AND estado = 'PR' 
                        AND YEAR(fechaActualizacion) <= ?
                        ORDER BY fechaActualizacion DESC, id DESC 
                        LIMIT 1
                    )
                    GROUP BY nm.nombre
                ),
                estudiantes AS (
                    SELECT 
                        CASE 
                            WHEN nm.nombre LIKE '%programa%' THEN 'PRONOEI' 
                            ELSE nm.nombre 
                        END AS nivel_educativo, 
                        COUNT(*) AS em,
                        COUNT(CASE WHEN ie.Area_id = 1 THEN 1 END) AS emu,
                        COUNT(CASE WHEN ie.Area_id = 2 THEN 1 END) AS emr
                    FROM edu_matricula_general_detalle mgd 
                    JOIN edu_matricula_general mg ON mg.id = mgd.matriculageneral_id
                    JOIN par_importacion i ON i.id = mg.importacion_id 
                        AND i.id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 34 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                    JOIN edu_padron_eib peib ON peib.institucioneducativa_id = mgd.institucioneducativa_id 
                        AND peib.periodo = ?
                    JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN edu_institucioneducativa ie ON ie.id = mgd.institucioneducativa_id
                    INNER JOIN edu_nivelmodalidad nm ON nm.id = ie.NivelModalidad_id 
                    GROUP BY nm.nombre
                ),
                docentes AS (
                    SELECT 
                        CASE 
                            WHEN nm.nombre LIKE '%programa%' THEN 'PRONOEI' 
                            ELSE nm.nombre 
                        END AS nivel_educativo, 
                        SUM(dct.td) AS td, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.td END) AS tdu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.td END) AS tdr,
                        SUM(dct.ta) AS ta, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.ta END) AS tau,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.ta END) AS tar,
                        SUM(dct.tp) AS tp, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.tp END) AS tpu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.tp END) AS tpr
                    FROM edu_padron_eib peib 
                    INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                    INNER JOIN edu_nivelmodalidad nm ON nm.id = iex.NivelModalidad_id 
                    INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                    INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                    INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN (
                        SELECT 
                            ie.cod_mod, 
                            COUNT(DISTINCT CASE WHEN stt.id IN (8,9,15) THEN n.cod_plaza END) AS td,
                            COUNT(DISTINCT CASE WHEN stt.id = 16 THEN n.cod_plaza END) AS ta,
                            COUNT(DISTINCT CASE WHEN stt.dependencia = 4 THEN n.cod_plaza END) AS tp 
                        FROM edu_nexus n
                        JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                        JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                        WHERE n.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 2 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        GROUP BY ie.cod_mod
                    ) AS dct ON dct.cod_mod = iex.codModular
                    WHERE peib.periodo = ?
                    GROUP BY nm.nombre
                )
                SELECT 
                    COALESCE(s.nivel_educativo, e.nivel_educativo, d.nivel_educativo) AS nivel_educativox,
                    COALESCE(s.ts, 0)  AS servicios_total,
                    COALESCE(s.tsu, 0) AS servicios_urbano,
                    COALESCE(s.tsr, 0) AS servicios_rural,
                    COALESCE(e.em, 0)  AS estudiantes_total,
                    COALESCE(e.emu, 0) AS estudiantes_urbano,
                    COALESCE(e.emr, 0) AS estudiantes_rural,
                    COALESCE(d.td, 0)  AS docentes_total,
                    COALESCE(d.tdu, 0) AS docentes_urbano,
                    COALESCE(d.tdr, 0) AS docentes_rural,
                    COALESCE(d.ta, 0)  AS auxiliares_total,
                    COALESCE(d.tau, 0) AS auxiliares_urbano,
                    COALESCE(d.tar, 0) AS auxiliares_rural,
                    COALESCE(d.tp, 0)  AS pec_total,
                    COALESCE(d.tpu, 0) AS pec_urbano,
                    COALESCE(d.tpr, 0) AS pec_rural
                FROM (
                    SELECT nivel_educativo FROM servicios
                    UNION
                    SELECT nivel_educativo FROM estudiantes
                    UNION
                    SELECT nivel_educativo FROM docentes
                ) AS todas_formas
                LEFT JOIN servicios s ON s.nivel_educativo = todas_formas.nivel_educativo
                LEFT JOIN estudiantes e ON e.nivel_educativo = todas_formas.nivel_educativo
                LEFT JOIN docentes d ON d.nivel_educativo = todas_formas.nivel_educativo
                ORDER BY 
                    CASE 
                        WHEN nivel_educativox = 'INICIAL - CUNA-JARDIN' THEN 1
                        WHEN nivel_educativox = 'INICIAL-JARDIN' THEN 2
                        WHEN nivel_educativox = 'PRONOEI' THEN 3
                        WHEN nivel_educativox = 'PRIMARIA' THEN 4
                        WHEN nivel_educativox = 'SECUNDARIA' THEN 5
                        ELSE 6
                    END";

        $params = [
            $anio,
            $periodo,
            $anio,
            $anio,
            $periodo,
            $anio,
            $anio,
            $anio,
            $periodo,
        ];

        return DB::table(DB::raw("({$sql}) as consolidado"))
            ->select(
                'nivel_educativox',
                'servicios_total',
                'servicios_urbano',
                'servicios_rural',
                'estudiantes_total',
                'estudiantes_urbano',
                'estudiantes_rural',
                'docentes_total',
                'docentes_urbano',
                'docentes_rural',
                'auxiliares_total',
                'auxiliares_urbano',
                'auxiliares_rural',
                'pec_total',
                'pec_urbano',
                'pec_rural'
            )
            ->setBindings($params)
            ->get();
    }

    public static function consolidadoPorLengua($anio, $periodo, $gestion = null, $provincia = null, $distrito = null)
    {
        $sql = "WITH
                servicios AS (
                    SELECT
                        pie.lengua,
                        COUNT(DISTINCT pie.codModular) AS ts,
                        COUNT(DISTINCT CASE WHEN pie.area = 1 THEN pie.codModular END) AS tsu,
                        COUNT(DISTINCT CASE WHEN pie.area = 2 THEN pie.codModular END) AS tsr
                    FROM edu_nexus n
                    INNER JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                    INNER JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                    INNER JOIN edu_nexus_nivel_educativo nm ON nm.id = ie.niveleducativo_id 
                    INNER JOIN edu_nexus_situacion_laboral sl ON sl.id = n.situacionlaboral_id
                    INNER JOIN (
                        SELECT 
                            iex.codModular, 
                            iex.TipoGestion_id, 
                            d.dependencia AS provincia, 
                            d.id AS distrito, 
                            peib.forma_atencion, 
                            iex.Area_id AS area, 
                            l.nombre AS lengua
                        FROM edu_padron_eib peib 
                        INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                        INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                        INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                        INNER JOIN par_lengua l ON l.id = peib.lengua1_id
                        INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                            AND pw.importacion_id = (
                                SELECT id FROM par_importacion 
                                WHERE fuenteImportacion_id = 1 
                                AND estado = 'PR' 
                                AND YEAR(fechaActualizacion) <= ?
                                ORDER BY fechaActualizacion DESC, id DESC 
                                LIMIT 1
                            )
                            AND pw.estadoinsedu_id = 3
                        WHERE peib.periodo = ?
                    ) pie ON pie.codModular = ie.cod_mod 
                    WHERE n.importacion_id = (
                        SELECT id FROM par_importacion 
                        WHERE fuenteImportacion_id = 2 
                        AND estado = 'PR' 
                        AND YEAR(fechaActualizacion) <= ?
                        ORDER BY fechaActualizacion DESC, id DESC 
                        LIMIT 1
                    )
                    GROUP BY pie.lengua
                ),
                estudiantes AS (
                    SELECT 
                        l.nombre AS lengua,
                        COUNT(*) AS em,
                        COUNT(CASE WHEN ie.Area_id = 1 THEN 1 END) AS emu,
                        COUNT(CASE WHEN ie.Area_id = 2 THEN 1 END) AS emr
                    FROM edu_matricula_general_detalle mgd 
                    JOIN edu_matricula_general mg ON mg.id = mgd.matriculageneral_id
                    JOIN par_importacion i ON i.id = mg.importacion_id 
                        AND i.id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 34 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                    JOIN edu_padron_eib peib ON peib.institucioneducativa_id = mgd.institucioneducativa_id 
                        AND peib.periodo = ?
                    JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN edu_institucioneducativa ie ON ie.id = mgd.institucioneducativa_id
                    INNER JOIN edu_nivelmodalidad nm ON nm.id = ie.NivelModalidad_id     
                    INNER JOIN par_lengua l ON l.id = peib.lengua1_id
                    GROUP BY l.nombre
                ),
                docentes AS (
                    SELECT 
                        l.nombre AS lengua, 
                        SUM(dct.td) AS td, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.td END) AS tdu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.td END) AS tdr,
                        SUM(dct.ta) AS ta, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.ta END) AS tau,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.ta END) AS tar,
                        SUM(dct.tp) AS tp, 
                        SUM(CASE WHEN iex.Area_id = 1 THEN dct.tp END) AS tpu,
                        SUM(CASE WHEN iex.Area_id = 2 THEN dct.tp END) AS tpr
                    FROM edu_padron_eib peib   
                    INNER JOIN par_lengua l ON l.id = peib.lengua1_id
                    INNER JOIN edu_institucioneducativa iex ON iex.id = peib.institucioneducativa_id 
                    INNER JOIN edu_nivelmodalidad nm ON nm.id = iex.NivelModalidad_id 
                    INNER JOIN edu_centropoblado cp ON cp.id = iex.CentroPoblado_id 
                    INNER JOIN par_ubigeo d ON d.id = cp.Ubigeo_id
                    INNER JOIN edu_padronweb pw ON pw.institucioneducativa_id = peib.institucioneducativa_id 
                        AND pw.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 1 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        AND pw.estadoinsedu_id = 3
                    INNER JOIN (
                        SELECT 
                            ie.cod_mod, 
                            COUNT(DISTINCT CASE WHEN stt.id IN (8,9,15) THEN n.cod_plaza END) AS td,
                            COUNT(DISTINCT CASE WHEN stt.id = 16 THEN n.cod_plaza END) AS ta,
                            COUNT(DISTINCT CASE WHEN stt.dependencia = 4 THEN n.cod_plaza END) AS tp 
                        FROM edu_nexus n
                        JOIN edu_nexus_institucion_educativa ie ON ie.id = n.institucioneducativa_id 
                        JOIN edu_nexus_regimen_laboral stt ON stt.id = n.regimenlaboral_id
                        WHERE n.importacion_id = (
                            SELECT id FROM par_importacion 
                            WHERE fuenteImportacion_id = 2 
                            AND estado = 'PR' 
                            AND YEAR(fechaActualizacion) <= ?
                            ORDER BY fechaActualizacion DESC, id DESC 
                            LIMIT 1
                        )
                        GROUP BY ie.cod_mod
                    ) AS dct ON dct.cod_mod = iex.codModular
                    WHERE peib.periodo = ?
                    GROUP BY l.nombre
                )
                SELECT 
                    COALESCE(s.lengua, e.lengua, d.lengua) AS lengua,
                    COALESCE(s.ts, 0)  AS servicios_total,
                    COALESCE(s.tsu, 0) AS servicios_urbano,
                    COALESCE(s.tsr, 0) AS servicios_rural,
                    COALESCE(e.em, 0)  AS estudiantes_total,
                    COALESCE(e.emu, 0) AS estudiantes_urbano,
                    COALESCE(e.emr, 0) AS estudiantes_rural,
                    COALESCE(d.td, 0)  AS docentes_total,
                    COALESCE(d.tdu, 0) AS docentes_urbano,
                    COALESCE(d.tdr, 0) AS docentes_rural,
                    COALESCE(d.ta, 0)  AS auxiliares_total,
                    COALESCE(d.tau, 0) AS auxiliares_urbano,
                    COALESCE(d.tar, 0) AS auxiliares_rural,
                    COALESCE(d.tp, 0)  AS pec_total,
                    COALESCE(d.tpu, 0) AS pec_urbano,
                    COALESCE(d.tpr, 0) AS pec_rural
                FROM (
                    SELECT lengua FROM servicios
                    UNION
                    SELECT lengua FROM estudiantes
                    UNION
                    SELECT lengua FROM docentes
                ) AS todas_formas
                LEFT JOIN servicios s ON s.lengua = todas_formas.lengua
                LEFT JOIN estudiantes e ON e.lengua = todas_formas.lengua
                LEFT JOIN docentes d ON d.lengua = todas_formas.lengua
                ORDER BY lengua";

        $params = [
            $anio,
            $periodo,
            $anio,
            $anio,
            $periodo,
            $anio,
            $anio,
            $anio,
            $periodo,
        ];

        return DB::table(DB::raw("({$sql}) as consolidado"))
            ->select(
                'lengua',
                'servicios_total',
                'servicios_urbano',
                'servicios_rural',
                'estudiantes_total',
                'estudiantes_urbano',
                'estudiantes_rural',
                'docentes_total',
                'docentes_urbano',
                'docentes_rural',
                'auxiliares_total',
                'auxiliares_urbano',
                'auxiliares_rural',
                'pec_total',
                'pec_urbano',
                'pec_rural'
            )
            ->setBindings($params)
            ->get();
    }
}
