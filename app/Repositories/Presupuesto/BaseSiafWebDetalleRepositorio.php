<?php

namespace App\Repositories\Presupuesto;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class BaseSiafWebDetalleRepositorio
{
    public static function obtenerUnidadesEjecutorasParaSelect(int $anio)
    {
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        return DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'sw.unidadejecutora_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->select([
                'ue.id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as nombre")
            ])
            ->distinct()
            ->orderBy('ue.codigo_ue', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerCategoriasGastoParaSelect(int $anio, int $unidadejecutoraId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'sw.categoriagasto_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->select('cg.id', 'cg.nombre')
            ->distinct()
            ->orderBy('cg.nombre', 'asc');

        return $query->pluck('nombre', 'id');
    }


    public static function obtenerCategoriasPresupuestalesParaSelect(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('sw.categoriagasto_id', $categoriagastoId))
            ->select('cp.tipo_categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.tipo_categoria_presupuestal', 'asc');
        return $query->pluck('nombre', 'nombre');
    }

    public static function obtenerCategoriasPresupuestalesParaSelect2(int $anio, int $unidadejecutoraId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->select('cp.tipo_categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.tipo_categoria_presupuestal', 'asc');
        return $query->pluck('nombre', 'nombre');
    }

    public static function obtenerCategoriasPresupuestalesParaSelect_categoria_presupuestal(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('sw.categoriagasto_id', $categoriagastoId))
            ->select('cp.id', 'cp.categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.categoria_presupuestal', 'asc');
        return $query->pluck('nombre', 'id');
    }


    public static function obtenerFuenteFinanciamientoParaSelect(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_rubro as r', 'r.id', '=', 'sw.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('sw.categoriagasto_id', $categoriagastoId))
            ->select('ff.id', DB::raw("CONCAT(ff.codigo,' ',ff.nombre) as nombre"))
            ->distinct()
            ->orderBy('r.nombre', 'asc');
        return $query->pluck('nombre', 'id');
    }

    public static function obtenerFuenteFinanciamientoParaSelect2(int $anio, int $unidadejecutoraId = 0, $categoriapresupuestal = 'todos')
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('swd.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriapresupuestal != '' && $categoriapresupuestal != '0' && $categoriapresupuestal != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $categoriapresupuestal))
            ->select('ff.id', DB::raw("CONCAT(ff.codigo,' ',ff.nombre) as nombre"))
            ->distinct()
            ->orderBy('r.nombre', 'asc');
        return $query->pluck('nombre', 'id');
    }

    public static function obtenerGenericaParaSelect(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('swd.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('swd.categoriagasto_id', $categoriagastoId)) // Comentado porque no aparece en la nueva lógica SQL
            ->select('g.id', DB::raw("CONCAT(tt.codigo, '.', g.codigo, ' ', g.nombre) as nombre"))
            ->distinct()
            ->orderBy('nombre', 'asc');
        return $query->pluck('nombre', 'id');
    }

    /*  */
    public static function ue_segun_sistema(int $sistema)
    {
        // $route = Request::route();
        // $routeName = $route && method_exists($route, 'getName') ? $route->getName() : '';
        // if ($routeName && Str::startsWith($routeName, 'presupuesto.')) {
        //     return DB::table('pres_base_siafweb_detalle')
        //         ->distinct()
        //         ->pluck('unidadejecutora_id');
        // }
        switch ($sistema) {
            case '3': // salud
                return [9, 10, 11, 14, 15, 20];
            case '1': // educacion
                return [8, 16, 17, 18, 19];
            default:
                return [];
        }
    }

    public static function obtenerResumenEjecucion(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $row = DB::table('pres_base_siafweb_detalle as sw')
            ->selectRaw('
            ROUND(SUM(pim), 0) AS pim,
            ROUND(SUM(certificado), 0) AS certificado,
            ROUND(SUM(compromiso_anual), 0) AS compromiso,
            ROUND(SUM(devengado), 0) AS devengado
        ')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('sw.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('sw.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->first();

        // Si no hay resultados, devolver ceros (o nulls, según tu política)
        if (!$row) {
            return [
                'pim' => 0,
                'certificado' => 0,
                'compromiso' => 0,
                'devengado' => 0,
                'ejecucion1' => 0.0,
                'ejecucion2' => 0.0,
                'ejecucion3' => 0.0,
                'ejecucion4' => 0.0,
            ];
        }

        // Extraer valores (cast a float/int para evitar string de DB)
        $pim = (float) $row->pim;
        $certificado = (float) $row->certificado;
        $compromiso = (float) $row->compromiso;
        $devengado = (float) $row->devengado;

        // ✅ Manejo seguro de división por cero
        $ejecucion1 = $pim > 0 ? round(100 * $devengado / $pim, 1) : 0.0;
        $ejecucion2 = $pim > 0 ? round(100 * $certificado / $pim, 1) : 0.0;
        $ejecucion3 = $pim > 0 ? round(100 * $compromiso / $pim, 1) : 0.0;
        $ejecucion4 = $certificado > 0 ? round(100 * $devengado / $certificado, 1) : 0.0;

        return [
            'pim' => (int) $pim,
            'certificado' => (int) $certificado,
            'compromiso' => (int) $compromiso,
            'devengado' => (int) $devengado,
            'ejecucion1' => $ejecucion1, // % devengado / pim
            'ejecucion2' => $ejecucion2, // % certificado / pim
            'ejecucion3' => $ejecucion3, // % compromiso / pim
            'ejecucion4' => $ejecucion4, // % devengado / certificado
        ];
    }

    public static function obtenerResumenPorUnidadEjecutora(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return  DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'sw.unidadejecutora_id')
                    ->whereIn('sw.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->when($ue > 0, fn($q) => $q->where('sw.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('sw.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->groupBy('ue.id', 'ue.codigo_ue', 'ue.abreviatura')
            ->select([
                'ue.id',
                DB::raw('concat(ue.codigo_ue," ",ue.abreviatura) as ue'),
                DB::raw('SUM(sw.pia) as pia'),
                DB::raw('SUM(sw.pim) as pim'),
                DB::raw('ROUND(SUM(sw.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(sw.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(sw.devengado), 1) as devengado'),
                DB::raw("
                            CASE 
                                WHEN SUM(sw.pim) > 0 
                                THEN ROUND(100 * SUM(sw.devengado) / SUM(sw.pim), 1)
                                ELSE 0.0 
                            END as avance
                        "),
                DB::raw("sum(sw.pim)-sum(sw.certificado) as saldocer"),
                DB::raw("sum(sw.pim)-sum(sw.devengado) as saldodev"),
            ])
            ->orderBy('avance', 'desc')
            ->get();
    }

    public static function obtenerCertificadoMensual(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            ->where('sw2.anio', $anio)
            ->whereExists(function ($query) use ($anio) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->where('sw.anio', $anio)
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }

        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            ->where('sw.anio', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('swd.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->select([
                'm.id as pos',
                'm.abreviado as mes',
                DB::raw('ROUND(SUM(swd.pim), 1) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado')
            ])
            ->groupBy('m.id', 'm.abreviado')
            ->orderBy('m.id', 'asc')
            ->get();

        // $result = [];
        // foreach ($rows as $row) {
        //     $result[] = [
        //         'pos' => (int) $row->pos,
        //         'mes' => $row->mes,
        //         'certificado' => (float) $row->certificado,
        //     ];
        // }

        // return $result;
    }

    /*  */

    public static function catpresreportesreporte_anal1(int $anio, int $ue, int $cg, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'sw.unidadejecutora_id')
                    ->whereIn('sw.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'sw.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'sw.categoriagasto_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->select([
                DB::raw('SUM(sw.pim) as pim'),
                DB::raw('ROUND(SUM(sw.devengado), 1) as devengado')
            ])
            ->first();
    }

    /*  */
    public static function catpresreportesreporte_anal2(int $anio, int $ue, int $cg, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'sw.unidadejecutora_id')
                    ->whereIn('sw.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'sw.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'sw.categoriagasto_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->groupBy('cp.tipo_categoria_presupuestal')
            ->select([
                'cp.tipo_categoria_presupuestal as nombre',
                DB::raw('SUM(sw.pim) as pim'),
                DB::raw('ROUND(SUM(sw.devengado), 1) as devengado')
            ])
            ->get();
    }
    /*  */

    /*  */
    public static function catpresreportesreporte_tabla1(int $anio, int $ue, int $cg, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'sw.unidadejecutora_id')
                    ->whereIn('sw.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'sw.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'sw.categoriagasto_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->groupBy('cp.categoria_presupuestal', 'cp.codigo', 'cp.id')
            ->select([
                'cp.id as id',
                DB::raw("CONCAT(cp.codigo, ' ', cp.categoria_presupuestal) as nombre"),
                DB::raw('SUM(sw.pia) as pia'),
                DB::raw('SUM(sw.pim) as pim'),
                DB::raw('ROUND(SUM(sw.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(sw.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(sw.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(sw.pim) > 0 THEN ROUND(100 * SUM(sw.devengado) / SUM(sw.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(sw.pim) - SUM(sw.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(sw.pim) - SUM(sw.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function catpresreportesreporte_tabla1_export(int $anio, int $ue, int $cg, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'sw.unidadejecutora_id')
                    ->whereIn('sw.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'sw.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'sw.categoriagasto_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'cp.categoria_presupuestal', 'cp.codigo', 'cp.id')
            ->select([
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as unidadejecutora"),
                'cp.id as id',
                DB::raw("CONCAT(cp.codigo, ' ', cp.categoria_presupuestal) as nombre"),
                DB::raw('SUM(sw.pia) as pia'),
                DB::raw('SUM(sw.pim) as pim'),
                DB::raw('ROUND(SUM(sw.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(sw.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(sw.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(sw.pim) > 0 THEN ROUND(100 * SUM(sw.devengado) / SUM(sw.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(sw.pim) - SUM(sw.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(sw.pim) - SUM(sw.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function catpresreportesreporte_tabla0101(int $anio, int $ue, int $cg, int $ff, int $cp = 0)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            // ->where('sw2.anio', $anio)
            ->where('sw2.anio', '<=', $anio)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->whereColumn('sw.anio', 'sw2.anio')
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        $max_meses = DB::table('pres_base_siafweb')
            ->whereIn('id', $idsSubquery)
            ->select('anio', DB::raw('MAX(mes) as max_mes'))
            ->groupBy('anio')
            ->pluck('max_mes', 'anio');

        $data = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            ->where('sw.anio', '<=', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('swd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($cp > 0, fn($q) => $q->where('cp.id', $cp))
            ->select([
                'sw.anio as anio',
                DB::raw("SUM(CASE WHEN m.id = 1 THEN swd.devengado ELSE 0 END) as ene"),
                DB::raw("SUM(CASE WHEN m.id = 2 THEN swd.devengado ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN m.id = 3 THEN swd.devengado ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN m.id = 4 THEN swd.devengado ELSE 0 END) as abr"),
                DB::raw("SUM(CASE WHEN m.id = 5 THEN swd.devengado ELSE 0 END) as may"),
                DB::raw("SUM(CASE WHEN m.id = 6 THEN swd.devengado ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN m.id = 7 THEN swd.devengado ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN m.id = 8 THEN swd.devengado ELSE 0 END) as ago"),
                DB::raw("SUM(CASE WHEN m.id = 9 THEN swd.devengado ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN m.id = 10 THEN swd.devengado ELSE 0 END) as oct"),
                DB::raw("SUM(CASE WHEN m.id = 11 THEN swd.devengado ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN m.id = 12 THEN swd.devengado ELSE 0 END) as dic"),
            ])
            ->groupBy('sw.anio')
            ->orderBy('sw.anio', 'desc')
            ->get();

        $meses_map = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        ];

        foreach ($data as $row) {
            $max_m = $max_meses[$row->anio] ?? 12;
            $col = $meses_map[$max_m];
            $row->total = $row->$col;
        }

        return $data;
    }

    public static function obtenerGastoPresupuestalMensual_anterior(int $anio, int $ue, int $cg, int $ff, int $cp = 0)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            ->where('sw2.anio', $anio)
            ->whereExists(function ($query) use ($anio) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->where('sw.anio', $anio)
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        $max_mes = DB::table('pres_base_siafweb')
            ->whereIn('id', $idsSubquery)
            ->max('mes');

        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            ->where('sw.anio', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('swd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($cp > 0, fn($q) => $q->where('cp.id', $cp))
            ->select([
                'sw.anio as anio',
                DB::raw("SUM(CASE WHEN m.id = 1 THEN swd.devengado ELSE 0 END) as ene"),
                DB::raw("SUM(CASE WHEN m.id = 2 THEN swd.devengado ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN m.id = 3 THEN swd.devengado ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN m.id = 4 THEN swd.devengado ELSE 0 END) as abr"),
                DB::raw("SUM(CASE WHEN m.id = 5 THEN swd.devengado ELSE 0 END) as may"),
                DB::raw("SUM(CASE WHEN m.id = 6 THEN swd.devengado ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN m.id = 7 THEN swd.devengado ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN m.id = 8 THEN swd.devengado ELSE 0 END) as ago"),
                DB::raw("SUM(CASE WHEN m.id = 9 THEN swd.devengado ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN m.id = 10 THEN swd.devengado ELSE 0 END) as oct"),
                DB::raw("SUM(CASE WHEN m.id = 11 THEN swd.devengado ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN m.id = 12 THEN swd.devengado ELSE 0 END) as dic"),
                DB::raw("SUM(CASE WHEN m.id = $max_mes THEN swd.devengado ELSE 0 END) as total")
            ])
            ->groupBy('sw.anio')
            ->get();
    }

    public static function fuenfinreportesreporte_anal1(int $anio, int $ue, int $cg, int $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->select([
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado')
            ])
            ->first();
    }

    public static function fuenfinreportesreporte_anal2(int $anio, int $ue, int $cg, $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ff.nombre', 'ff.codigo')
            ->orderBy('ff.codigo')
            ->select([
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado')
            ])
            ->get();
    }

    public static function fuenfinreportesreporte_tabla1(int $anio, int $ue, int $cg, $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ff.nombre', 'ff.codigo', 'ff.id')
            ->select([
                'ff.id as id',
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla1_export(int $anio, int $ue, int $cg, $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'ff.nombre', 'ff.codigo', 'ff.id')
            ->select([
                'ff.id as id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as unidadejecutora"),
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_xtabla1(int $anio, int $ue, int $cg, $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'ff.nombre', 'ff.codigo', 'ff.id')
            ->select([
                'ff.id as id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as ejecutora"),
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function obtenerResumenPorCategoriaPresupuestalgenerica2(int $anio, int $ue, int $cg, $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ff.nombre', 'ff.codigo', 'ff.id')
            ->select([
                'ff.id as id',
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla0101(int $anio, int $ue, int $cg, int $g, int $ff)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            // ->where('sw2.anio', $anio)
            ->where('sw2.anio', '<=', $anio)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->whereColumn('sw.anio', 'sw2.anio')
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        $max_meses = DB::table('pres_base_siafweb')
            ->whereIn('id', $idsSubquery)
            ->select('anio', DB::raw('MAX(mes) as max_mes'))
            ->groupBy('anio')
            ->pluck('max_mes', 'anio');

        $data = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            // ->where('sw.anio', $anio)
            ->where('sw.anio', '<=', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('swd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('ff.id', $g))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->select([
                'sw.anio as anio',
                DB::raw("SUM(CASE WHEN m.id = 1 THEN swd.devengado ELSE 0 END) as ene"),
                DB::raw("SUM(CASE WHEN m.id = 2 THEN swd.devengado ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN m.id = 3 THEN swd.devengado ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN m.id = 4 THEN swd.devengado ELSE 0 END) as abr"),
                DB::raw("SUM(CASE WHEN m.id = 5 THEN swd.devengado ELSE 0 END) as may"),
                DB::raw("SUM(CASE WHEN m.id = 6 THEN swd.devengado ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN m.id = 7 THEN swd.devengado ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN m.id = 8 THEN swd.devengado ELSE 0 END) as ago"),
                DB::raw("SUM(CASE WHEN m.id = 9 THEN swd.devengado ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN m.id = 10 THEN swd.devengado ELSE 0 END) as oct"),
                DB::raw("SUM(CASE WHEN m.id = 11 THEN swd.devengado ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN m.id = 12 THEN swd.devengado ELSE 0 END) as dic"),
            ])
            ->groupBy('sw.anio')
            ->orderBy('sw.anio', 'desc')
            ->get();

        $meses_map = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        ];

        foreach ($data as $row) {
            $max_m = $max_meses[$row->anio] ?? 12;
            $col = $meses_map[$max_m];
            $row->total = $row->$col;
        }

        return $data;
    }

    public static function genericareportesreporte_anal1(int $anio, int $ue, $cp, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->select([
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado')
            ])
            ->first();
    }

    public static function genericareportesreporte_anal2(int $anio, int $ue, $cp, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('g.nombre', 'tt.codigo', 'g.codigo')
            ->select([
                DB::raw('concat(tt.codigo, ".", g.codigo, " ", g.nombre) as nombre'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado')
            ])
            ->orderBy('nombre')
            ->get();
    }

    public static function genericareportesreporte_tabla1(int $anio, int $ue, $cp, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('g.nombre', 'tt.codigo', 'g.codigo', 'g.id')
            ->select([
                'g.id as id',
                DB::raw('concat(tt.codigo, ".", g.codigo, " ", g.nombre) as nombre'),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

        public static function genericareportesreporte_tabla1_export(int $anio, int $ue, $cp, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ue.codigo_ue','ue.abreviatura','g.nombre', 'tt.codigo', 'g.codigo', 'g.id')
            ->select([
                'g.id as id',
                DB::raw('concat(ue.codigo_ue, " - ", ue.abreviatura) as unidadejecutora'),
                DB::raw('concat(tt.codigo, ".", g.codigo, " ", g.nombre) as nombre'),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla2(int $anio, int $ue, $cp, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('sg.id', 'tt.codigo', 'g.codigo', 'sg.codigo', 'sg.nombre')
            ->select([
                'sg.id as id',
                DB::raw('concat(tt.codigo, ".", g.codigo, ".", sg.codigo, " ", sg.nombre) as nombre'),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla2_export(int $anio, int $ue, $cp, $ff)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'sg.id', 'tt.codigo', 'g.codigo', 'sg.codigo', 'sg.nombre')
            ->select([
                'sg.id as id',
                DB::raw('concat(ue.codigo_ue, " - ", ue.abreviatura) as unidadejecutora'),
                DB::raw('concat(tt.codigo, ".", g.codigo, ".", sg.codigo, " ", sg.nombre) as nombre'),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla0101(int $anio, int $ue, $cp = '', int $ff, int $g = 0)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            // ->where('sw2.anio', $anio)
            ->where('sw2.anio', '<=', $anio)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->whereColumn('sw.anio', 'sw2.anio')
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        $max_meses = DB::table('pres_base_siafweb')
            ->whereIn('id', $idsSubquery)
            ->select('anio', DB::raw('MAX(mes) as max_mes'))
            ->groupBy('anio')
            ->pluck('max_mes', 'anio');

        $data = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            // ->where('sw.anio', $anio)
            ->where('sw.anio', '<=', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->select([
                'sw.anio as anio',
                DB::raw("SUM(CASE WHEN m.id = 1 THEN swd.devengado ELSE 0 END) as ene"),
                DB::raw("SUM(CASE WHEN m.id = 2 THEN swd.devengado ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN m.id = 3 THEN swd.devengado ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN m.id = 4 THEN swd.devengado ELSE 0 END) as abr"),
                DB::raw("SUM(CASE WHEN m.id = 5 THEN swd.devengado ELSE 0 END) as may"),
                DB::raw("SUM(CASE WHEN m.id = 6 THEN swd.devengado ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN m.id = 7 THEN swd.devengado ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN m.id = 8 THEN swd.devengado ELSE 0 END) as ago"),
                DB::raw("SUM(CASE WHEN m.id = 9 THEN swd.devengado ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN m.id = 10 THEN swd.devengado ELSE 0 END) as oct"),
                DB::raw("SUM(CASE WHEN m.id = 11 THEN swd.devengado ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN m.id = 12 THEN swd.devengado ELSE 0 END) as dic"),
            ])
            ->groupBy('sw.anio')
            ->orderBy('sw.anio', 'desc')
            ->get();

        $meses_map = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        ];

        foreach ($data as $row) {
            $max_m = $max_meses[$row->anio] ?? 12;
            $col = $meses_map[$max_m];
            $row->total = $row->$col;
        }

        return $data;
    }

    public static function fuenfinreportesreporte_tabla2(int $anio, int $ue, int $cg, int $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('r.nombre', 'r.codigo', 'r.id')
            ->select([
                'r.id as id',
                DB::raw("CONCAT(r.codigo, ' ', r.nombre) as nombre"),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

        public static function fuenfinreportesreporte_tabla2_export(int $anio, int $ue, int $cg, int $g)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'swd.unidadejecutora_id')
                    ->whereIn('swd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'swd.categoriagasto_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('cg.id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->where('swd.basesiafweb_id', $basesiafweb)
            ->groupBy('ue.codigo_ue','ue.abreviatura', 'r.nombre', 'r.codigo', 'r.id')
            ->select([
                'r.id as id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as unidadejecutora"),
                DB::raw("CONCAT(r.codigo, ' ', r.nombre) as nombre"),
                DB::raw('SUM(swd.pia) as pia'),
                DB::raw('SUM(swd.pim) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(swd.pim) > 0 THEN ROUND(100 * SUM(swd.devengado) / SUM(swd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(swd.pim) - SUM(swd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla0201(int $anio, int $ue, int $cg, int $g, int $rb)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            // ->where('sw2.anio', $anio)
            ->where('sw2.anio', '<=', $anio)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->whereColumn('sw.anio', 'sw2.anio')
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        $max_meses = DB::table('pres_base_siafweb')
            ->whereIn('id', $idsSubquery)
            ->select('anio', DB::raw('MAX(mes) as max_mes'))
            ->groupBy('anio')
            ->pluck('max_mes', 'anio');

        $data = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            // ->where('sw.anio', $anio)
            ->where('sw.anio', '<=', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('swd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('g.id', $g))
            ->when($rb > 0, fn($q) => $q->where('r.id', $rb))
            ->select([
                'sw.anio as anio',
                DB::raw("SUM(CASE WHEN m.id = 1 THEN swd.devengado ELSE 0 END) as ene"),
                DB::raw("SUM(CASE WHEN m.id = 2 THEN swd.devengado ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN m.id = 3 THEN swd.devengado ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN m.id = 4 THEN swd.devengado ELSE 0 END) as abr"),
                DB::raw("SUM(CASE WHEN m.id = 5 THEN swd.devengado ELSE 0 END) as may"),
                DB::raw("SUM(CASE WHEN m.id = 6 THEN swd.devengado ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN m.id = 7 THEN swd.devengado ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN m.id = 8 THEN swd.devengado ELSE 0 END) as ago"),
                DB::raw("SUM(CASE WHEN m.id = 9 THEN swd.devengado ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN m.id = 10 THEN swd.devengado ELSE 0 END) as oct"),
                DB::raw("SUM(CASE WHEN m.id = 11 THEN swd.devengado ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN m.id = 12 THEN swd.devengado ELSE 0 END) as dic"),
            ])
            ->groupBy('sw.anio')
            ->orderBy('sw.anio', 'desc')
            ->get();

        $meses_map = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        ];

        foreach ($data as $row) {
            $max_m = $max_meses[$row->anio] ?? 12;
            $col = $meses_map[$max_m];
            $row->total = $row->$col;
        }

        return $data;
    }

    public static function genericareportesreporte_tabla0201(int $anio, int $ue, $cp = '', int $ff, int $sg = 0)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            // ->where('sw2.anio', $anio)
            ->where('sw2.anio', '<=', $anio)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->whereColumn('sw.anio', 'sw2.anio')
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        $max_meses = DB::table('pres_base_siafweb')
            ->whereIn('id', $idsSubquery)
            ->select('anio', DB::raw('MAX(mes) as max_mes'))
            ->groupBy('anio')
            ->pluck('max_mes', 'anio');

        $data = DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->join('pres_rubro as r', 'r.id', '=', 'swd.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'swd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            // ->where('sw.anio', $anio)
            ->where('sw.anio', '<=', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($sg > 0, fn($q) => $q->where('sg.id', $sg))
            ->select([
                'sw.anio as anio',
                DB::raw("SUM(CASE WHEN m.id = 1 THEN swd.devengado ELSE 0 END) as ene"),
                DB::raw("SUM(CASE WHEN m.id = 2 THEN swd.devengado ELSE 0 END) as feb"),
                DB::raw("SUM(CASE WHEN m.id = 3 THEN swd.devengado ELSE 0 END) as mar"),
                DB::raw("SUM(CASE WHEN m.id = 4 THEN swd.devengado ELSE 0 END) as abr"),
                DB::raw("SUM(CASE WHEN m.id = 5 THEN swd.devengado ELSE 0 END) as may"),
                DB::raw("SUM(CASE WHEN m.id = 6 THEN swd.devengado ELSE 0 END) as jun"),
                DB::raw("SUM(CASE WHEN m.id = 7 THEN swd.devengado ELSE 0 END) as jul"),
                DB::raw("SUM(CASE WHEN m.id = 8 THEN swd.devengado ELSE 0 END) as ago"),
                DB::raw("SUM(CASE WHEN m.id = 9 THEN swd.devengado ELSE 0 END) as sep"),
                DB::raw("SUM(CASE WHEN m.id = 10 THEN swd.devengado ELSE 0 END) as oct"),
                DB::raw("SUM(CASE WHEN m.id = 11 THEN swd.devengado ELSE 0 END) as nov"),
                DB::raw("SUM(CASE WHEN m.id = 12 THEN swd.devengado ELSE 0 END) as dic"),
            ])
            ->groupBy('sw.anio')
            ->orderBy('sw.anio', 'desc')
            ->get();

        $meses_map = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic'
        ];

        foreach ($data as $row) {
            $max_m = $max_meses[$row->anio] ?? 12;
            $col = $meses_map[$max_m];
            $row->total = $row->$col;
        }

        return $data;
    }
}
