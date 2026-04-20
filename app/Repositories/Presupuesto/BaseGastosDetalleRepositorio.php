<?php

namespace App\Repositories\Presupuesto;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BaseGastosDetalleRepositorio
{
    public static function obtenerUltimoIdPorAnio(int $anio): int
    {
        $row = DB::table('pres_base_gastos as bg')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bg.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bg.anio', $anio)
            ->orderBy('i.fechaActualizacion', 'desc')
            ->select('bg.id')
            ->first();

        return $row ? (int) $row->id : 0;
    }

    public static function ue_segun_sistema($sistema): array
    {
        switch ((string) $sistema) {
            case '3':
                return [9, 10, 11, 14, 15, 20, 231];
            case '1':
                return [8, 16, 17, 18, 19];
            default:
                return [];
        }
    }

    public static function ue_permitidas(array $basegastosIds): array
    {
        $ue = self::ue_segun_sistema(session('sistema_id'));
        if (!empty($ue)) {
            return $ue;
        }

        if (empty($basegastosIds)) {
            return [];
        }

        return DB::table('pres_base_gastos_detalle')
            ->whereIn('basegastos_id', $basegastosIds)
            ->distinct()
            ->pluck('unidadejecutora_id')
            ->map(fn($v) => (int) $v)
            ->toArray();
    }

    public static function obtenerUnidadesEjecutorasParaSelect(int $anio)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bgd.unidadejecutora_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
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
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'bgd.categoriagasto_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $unidadejecutoraId))
            ->select('cg.id', 'cg.nombre')
            ->distinct()
            ->orderBy('cg.nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerCategoriasPresupuestalesParaSelect(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('bgd.categoriagasto_id', $categoriagastoId))
            ->select('cp.tipo_categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.tipo_categoria_presupuestal', 'asc')
            ->pluck('nombre', 'nombre');
    }

    public static function obtenerCategoriasPresupuestalesParaSelect2(int $anio, int $unidadejecutoraId = 0)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $unidadejecutoraId))
            ->select('cp.tipo_categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.tipo_categoria_presupuestal', 'asc')
            ->pluck('nombre', 'nombre');
    }

    public static function obtenerFuenteFinanciamientoParaSelect2(int $anio, int $unidadejecutoraId = 0, $categoriapresupuestal = 'todos')
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriapresupuestal != '' && $categoriapresupuestal != '0' && $categoriapresupuestal != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $categoriapresupuestal))
            ->select('ff.id', DB::raw("CONCAT(ff.codigo,' ',ff.nombre) as nombre"))
            ->distinct()
            ->orderBy('r.nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function catpresreportesreporte_anal1(int $anio, int $ue, int $cg, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return (object) ['pim' => 0, 'devengado' => 0];
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->select([
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado')
            ])
            ->first();
    }

    public static function catpresreportesreporte_anal2(int $anio, int $ue, int $cg, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->groupBy('cp.tipo_categoria_presupuestal')
            ->select([
                'cp.tipo_categoria_presupuestal as nombre',
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado')
            ])
            ->get();
    }

    public static function catpresreportesreporte_tabla1(int $anio, int $ue, int $cg, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->groupBy('cp.categoria_presupuestal', 'cp.codigo', 'cp.id')
            ->select([
                'cp.id as id',
                DB::raw("CONCAT(cp.codigo, ' ', cp.categoria_presupuestal) as nombre"),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function catpresreportesreporte_tabla1_export(int $anio, int $ue, int $cg, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'cp.categoria_presupuestal', 'cp.codigo', 'cp.id')
            ->select([
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as unidadejecutora"),
                'cp.id as id',
                DB::raw("CONCAT(cp.codigo, ' ', cp.categoria_presupuestal) as nombre"),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function catpresreportesreporte_tabla0101(int $anio, int $ue, int $cg, $ff, $cp)
    {
        $aniosDisponibles = DB::table('pres_base_gastos as bg')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bg.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bg.anio', '<=', $anio)
            ->select('bg.anio')
            ->distinct()
            ->orderBy('bg.anio', 'desc')
            ->limit(8)
            ->pluck('bg.anio');

        $basegastosIds = $aniosDisponibles
            ->map(fn($a) => self::obtenerUltimoIdPorAnio((int) $a))
            ->filter(fn($id) => (int) $id > 0)
            ->values();

        if ($basegastosIds->isEmpty()) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas($basegastosIds->toArray());

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->whereIn('bgd.basegastos_id', $basegastosIds)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($cp > 0, fn($q) => $q->where('bgd.categoriapresupuestal_id', $cp))
            ->groupBy('bgd.anio')
            ->selectRaw('
                bgd.anio as anio,
                SUM(CASE WHEN bgd.mes=1 THEN bgd.devengado ELSE 0 END) as ene,
                SUM(CASE WHEN bgd.mes=2 THEN bgd.devengado ELSE 0 END) as feb,
                SUM(CASE WHEN bgd.mes=3 THEN bgd.devengado ELSE 0 END) as mar,
                SUM(CASE WHEN bgd.mes=4 THEN bgd.devengado ELSE 0 END) as abr,
                SUM(CASE WHEN bgd.mes=5 THEN bgd.devengado ELSE 0 END) as may,
                SUM(CASE WHEN bgd.mes=6 THEN bgd.devengado ELSE 0 END) as jun,
                SUM(CASE WHEN bgd.mes=7 THEN bgd.devengado ELSE 0 END) as jul,
                SUM(CASE WHEN bgd.mes=8 THEN bgd.devengado ELSE 0 END) as ago,
                SUM(CASE WHEN bgd.mes=9 THEN bgd.devengado ELSE 0 END) as sep,
                SUM(CASE WHEN bgd.mes=10 THEN bgd.devengado ELSE 0 END) as oct,
                SUM(CASE WHEN bgd.mes=11 THEN bgd.devengado ELSE 0 END) as nov,
                SUM(CASE WHEN bgd.mes=12 THEN bgd.devengado ELSE 0 END) as dic
            ')
            ->orderBy('bgd.anio', 'desc')
            ->get();
    }

    public static function obtenerFuenteFinanciamientoParaSelect(int $anio, int $ue, int $cg)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->select('ff.id', 'ff.nombre')
            ->distinct()
            ->orderBy('ff.nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerRubrosParaSelect(int $anio, int $ue, int $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->select('r.id', DB::raw("CONCAT(r.codigo, ' ', r.nombre) as nombre"))
            ->distinct()
            ->orderBy('r.codigo', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerResumenEjecucionPorFuenteRubro(int $anio, int $ue, int $ff, int $rb): array
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
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

        $uePermitidas = self::ue_permitidas([$basegastosId]);

        $row = DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($rb > 0, fn($q) => $q->where('r.id', $rb))
            ->selectRaw('
                ROUND(SUM(bgd.pim), 0) AS pim,
                ROUND(SUM(bgd.certificado), 0) AS certificado,
                ROUND(SUM(bgd.compromiso_anual), 0) AS compromiso,
                ROUND(SUM(bgd.devengado), 0) AS devengado
            ')
            ->first();

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

        $pim = (float) $row->pim;
        $certificado = (float) $row->certificado;
        $compromiso = (float) $row->compromiso;
        $devengado = (float) $row->devengado;

        return [
            'pim' => (int) $pim,
            'certificado' => (int) $certificado,
            'compromiso' => (int) $compromiso,
            'devengado' => (int) $devengado,
            'ejecucion1' => $pim > 0 ? round(100 * $devengado / $pim, 1) : 0.0,
            'ejecucion2' => $pim > 0 ? round(100 * $certificado / $pim, 1) : 0.0,
            'ejecucion3' => $pim > 0 ? round(100 * $compromiso / $pim, 1) : 0.0,
            'ejecucion4' => $certificado > 0 ? round(100 * $devengado / $certificado, 1) : 0.0,
        ];
    }

    public static function obtenerResumenPorUnidadEjecutoraPorFuenteRubro(int $anio, int $ue, int $ff, int $rb)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($rb > 0, fn($q) => $q->where('r.id', $rb))
            ->groupBy('ue.id', 'ue.codigo_ue', 'ue.abreviatura')
            ->select([
                'ue.id',
                DB::raw('concat(ue.codigo_ue," ",ue.abreviatura) as ue'),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw("
                    CASE
                        WHEN SUM(bgd.pim) > 0
                        THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1)
                        ELSE 0.0
                    END as avance
                "),
                DB::raw('sum(bgd.pim)-sum(bgd.certificado) as saldocer'),
                DB::raw('sum(bgd.pim)-sum(bgd.devengado) as saldodev'),
            ])
            ->orderBy('ue.codigo_ue', 'asc')
            ->get();
    }

    public static function obtenerCertificadoMensualPorFuenteRubro(int $anio, int $ue, int $ff, int $rb)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);

        $pimBase = (float) DB::table('pres_base_gastos_detalle as bd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bd.basegastos_id', $basegastosId)
            ->where('bd.anio', $anio)
            ->where('bd.mes', 0)
            ->whereIn('bd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bd.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($rb > 0, fn($q) => $q->where('r.id', $rb))
            ->sum('bd.pim');

        $sub = DB::table('pres_base_gastos_detalle as bd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bd.basegastos_id', $basegastosId)
            ->where('bd.anio', $anio)
            ->whereIn('bd.unidadejecutora_id', $uePermitidas)
            ->whereBetween('bd.mes', [1, 12])
            ->when($ue > 0, fn($q) => $q->where('bd.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($rb > 0, fn($q) => $q->where('r.id', $rb))
            ->groupBy('bd.mes')
            ->selectRaw('
                bd.mes as mes_id,
                ROUND(SUM(bd.pim), 1) as pim,
                ROUND(SUM(bd.certificado), 0) as certificado,
                ROUND(SUM(bd.devengado), 1) as devengado
            ');

        $rows = DB::table('par_mes as m')
            ->leftJoinSub($sub, 'd', function ($join) {
                $join->on('d.mes_id', '=', 'm.id');
            })
            ->select([
                'm.id as pos',
                'm.abreviado as mes',
                'd.pim as pim',
                'd.certificado as certificado',
                'd.devengado as devengado',
            ])
            ->orderBy('m.id', 'asc')
            ->get();

        $mesesConData = $rows->filter(fn($r) => $r->pim !== null || $r->certificado !== null || $r->devengado !== null);
        if ($mesesConData->isEmpty()) {
            foreach ($rows as $row) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
            }
            return $rows;
        }

        $minMesConData = (int) $mesesConData->min('pos');
        $maxMesConData = (int) $mesesConData->max('pos');

        foreach ($rows as $row) {
            if ($maxMesConData <= 0) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
                continue;
            }

            if ((int) $row->pos > $maxMesConData) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
                continue;
            }

            if ((int) $row->pos < $minMesConData) {
                $row->pim = $pimBase > 0 ? $pimBase : 0.0;
                $row->certificado = 0.0;
                $row->devengado = 0.0;
                continue;
            }

            $row->pim = $pimBase > 0 ? $pimBase : ($row->pim === null ? 0.0 : (float) $row->pim);
            $row->certificado = $row->certificado === null ? 0.0 : (float) $row->certificado;
            $row->devengado = $row->devengado === null ? 0.0 : (float) $row->devengado;
        }

        return $rows;
    }

    public static function obtenerResumenEjecucion(int $anio, int $ue, int $cg, ?string $cp = null): array
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
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
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        $row = DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->selectRaw('
                ROUND(SUM(bgd.pim), 0) AS pim,
                ROUND(SUM(bgd.certificado), 0) AS certificado,
                ROUND(SUM(bgd.compromiso_anual), 0) AS compromiso,
                ROUND(SUM(bgd.devengado), 0) AS devengado
            ')
            ->first();

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

        $pim = (float) $row->pim;
        $certificado = (float) $row->certificado;
        $compromiso = (float) $row->compromiso;
        $devengado = (float) $row->devengado;

        return [
            'pim' => (int) $pim,
            'certificado' => (int) $certificado,
            'compromiso' => (int) $compromiso,
            'devengado' => (int) $devengado,
            'ejecucion1' => $pim > 0 ? round(100 * $devengado / $pim, 1) : 0.0,
            'ejecucion2' => $pim > 0 ? round(100 * $certificado / $pim, 1) : 0.0,
            'ejecucion3' => $pim > 0 ? round(100 * $compromiso / $pim, 1) : 0.0,
            'ejecucion4' => $certificado > 0 ? round(100 * $devengado / $certificado, 1) : 0.0,
        ];
    }

    public static function obtenerResumenPorUnidadEjecutora(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);

        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);

        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->groupBy('ue.id', 'ue.codigo_ue', 'ue.abreviatura')
            ->select([
                'ue.id',
                DB::raw('concat(ue.codigo_ue," ",ue.abreviatura) as ue'),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw("
                    CASE
                        WHEN SUM(bgd.pim) > 0
                        THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1)
                        ELSE 0.0
                    END as avance
                "),
                DB::raw('sum(bgd.pim)-sum(bgd.certificado) as saldocer'),
                DB::raw('sum(bgd.pim)-sum(bgd.devengado) as saldodev'),
            ])
            ->orderBy('ue.codigo_ue', 'asc')
            ->get();
    }

    public static function obtenerCertificadoMensual(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }
        $uePermitidas = self::ue_permitidas([$basegastosId]);
        $pimBaseQuery = DB::table('par_importacion as i')
            ->join('pres_base_gastos as b', 'b.importacion_id', '=', 'i.id')
            ->join('pres_base_gastos_detalle as bd', 'bd.basegastos_id', '=', 'b.id')
            ->when(filled($cp), function ($q) {
                $q->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bd.categoriapresupuestal_id');
            })
            ->where('i.estado', 'PR')
            ->where('bd.anio', $anio)
            ->where('bd.mes', 0)
            ->whereIn('bd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bd.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp));

        $pimBase = (float) $pimBaseQuery->sum('bd.pim');
        $sub = DB::table('par_importacion as i')
            ->join('pres_base_gastos as b', 'b.importacion_id', '=', 'i.id')
            ->join('pres_base_gastos_detalle as bd', 'bd.basegastos_id', '=', 'b.id')
            ->when(filled($cp), function ($q) {
                $q->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bd.categoriapresupuestal_id');
            })
            ->where('i.estado', 'PR')
            ->where('bd.anio', $anio)
            ->whereIn('bd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bd.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->groupBy('bd.mes')
            ->selectRaw('
                bd.mes as mes_id,
                ROUND(SUM(bd.pim), 1) as pim,
                ROUND(SUM(bd.certificado), 0) as certificado,
                ROUND(SUM(bd.devengado), 1) as devengado
            ');

        $rows = DB::table('par_mes as m')
            ->leftJoinSub($sub, 'd', function ($join) {
                $join->on('d.mes_id', '=', 'm.id');
            })
            ->select([
                'm.id as pos',
                'm.abreviado as mes',
                'd.pim as pim',
                'd.certificado as certificado',
                'd.devengado as devengado',
            ])
            ->orderBy('m.id', 'asc')
            ->get();

        $mesesConData = $rows->filter(fn($r) => $r->pim !== null || $r->certificado !== null || $r->devengado !== null);
        if ($mesesConData->isEmpty()) {
            foreach ($rows as $row) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
            }
            return $rows;
        }
        $minMesConData = (int) $mesesConData->min('pos');
        $maxMesConData = (int) $mesesConData->max('pos');

        foreach ($rows as $row) {
            if ($maxMesConData <= 0) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
                continue;
            }

            if ((int) $row->pos > $maxMesConData) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
                continue;
            }

            if ((int) $row->pos < $minMesConData) {
                $row->pim = $pimBase > 0 ? $pimBase : 0.0;
                $row->certificado = 0.0;
                $row->devengado = 0.0;
                continue;
            }
            $row->pim = $pimBase > 0 ? $pimBase : ($row->pim === null ? 0.0 : (float) $row->pim);
            $row->certificado = $row->certificado === null ? 0.0 : (float) $row->certificado;
            $row->devengado = $row->devengado === null ? 0.0 : (float) $row->devengado;
        }

        return $rows;
    }

    public static function obtenerCertificadoMensualDesdeCubo(int $anio, int $ue)
    {
        $cuboQuery = DB::table('pres_cubo_gasto')
            ->where('anio', $anio)
            ->whereBetween('mes', [0, 12])
            ->when($ue > 0, fn($q) => $q->where('unidadejecutora_id', $ue));

        if (!$cuboQuery->exists()) {
            return collect();
        }

        $pimBase = (float) (clone $cuboQuery)
            ->where('mes', 0)
            ->sum('pim');

        if ($pimBase <= 0) {
            $pimBase = (float) (clone $cuboQuery)
                ->whereBetween('mes', [1, 12])
                ->selectRaw('mes, SUM(pim) as pim')
                ->groupBy('mes')
                ->orderByDesc('pim')
                ->value('pim');
        }

        $sub = (clone $cuboQuery)
            ->whereBetween('mes', [1, 12])
            ->groupBy('mes')
            ->selectRaw('
                mes as mes_id,
                ROUND(SUM(certificado), 1) as certificado_mes,
                ROUND(SUM(devengado), 1) as devengado_mes
            ');

        $rows = DB::table('par_mes as m')
            ->leftJoinSub($sub, 'd', function ($join) {
                $join->on('d.mes_id', '=', 'm.id');
            })
            ->select([
                'm.id as pos',
                'm.abreviado as mes',
                'd.certificado_mes as certificado_mes',
                'd.devengado_mes as devengado_mes',
            ])
            ->orderBy('m.id', 'asc')
            ->get();

        $maxMesConData = (int) $rows
            ->filter(fn($r) => $r->certificado_mes !== null || $r->devengado_mes !== null)
            ->max('pos');

        $cumCert = 0.0;
        $cumDev = 0.0;
        foreach ($rows as $row) {
            if ($maxMesConData <= 0) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
                unset($row->certificado_mes, $row->devengado_mes);
                continue;
            }

            if ((int) $row->pos > $maxMesConData) {
                $row->pim = null;
                $row->certificado = null;
                $row->devengado = null;
                unset($row->certificado_mes, $row->devengado_mes);
                continue;
            }

            $row->pim = $pimBase > 0 ? $pimBase : null;
            $cumCert += (float) ($row->certificado_mes ?? 0);
            $cumDev += (float) ($row->devengado_mes ?? 0);
            $row->certificado = round($cumCert, 1);
            $row->devengado = round($cumDev, 1);
            unset($row->certificado_mes, $row->devengado_mes);
        }

        return $rows;
    }

    public static function obtenerGenericaParaSelect(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $sistemaId = (string) session('sistema_id');
        $cacheKey = "pres:basegastos:$basegastosId:genericaSelect:sis:$sistemaId:ue:$unidadejecutoraId:cg:$categoriagastoId";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($basegastosId, $unidadejecutoraId, $categoriagastoId) {
            $uePermitidas = self::ue_permitidas([$basegastosId]);

            $sub = DB::table('pres_base_gastos_detalle as bgd')
                ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
                ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
                ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
                ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
                ->where('bgd.basegastos_id', $basegastosId)
                ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
                ->when($unidadejecutoraId > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $unidadejecutoraId))
                ->when($categoriagastoId > 0, fn($q) => $q->where('bgd.categoriagasto_id', $categoriagastoId))
                ->select('sg.generica_id')
                ->distinct();

            return DB::table('pres_generica_gastos as g')
                ->joinSub($sub, 'x', function ($join) {
                    $join->on('x.generica_id', '=', 'g.id');
                })
                ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
                ->select('g.id', DB::raw("CONCAT(tt.codigo, '.', g.codigo, ' ', g.nombre) as nombre"))
                ->orderBy('nombre', 'asc')
                ->pluck('nombre', 'id');
        });
    }

    public static function fuenfinreportesreporte_anal1(int $anio, int $ue, int $cg, int $g)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return (object) ['pim' => 0, 'devengado' => 0];
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        $query = DB::table('pres_base_gastos_detalle as bgd')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg));

        if ($g > 0) {
            $query
                ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
                ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
                ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
                ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
                ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
                ->where('ge.id', $g);
        }

        return $query
            ->select([
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado')
            ])
            ->first();
    }

    public static function fuenfinreportesreporte_anal2(int $anio, int $ue, int $cg, int $g)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('ge.id', $g))
            ->groupBy('ff.nombre', 'ff.codigo')
            ->orderBy('ff.codigo')
            ->select([
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado')
            ])
            ->get();
    }

    public static function fuenfinreportesreporte_tabla1(int $anio, int $ue, int $cg, int $g)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('ge.id', $g))
            ->groupBy('ff.nombre', 'ff.codigo', 'ff.id')
            ->select([
                'ff.id as id',
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla1_export(int $anio, int $ue, int $cg, int $g)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('ge.id', $g))
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'ff.nombre', 'ff.codigo', 'ff.id')
            ->select([
                'ff.id as id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as unidadejecutora"),
                DB::raw("CONCAT(ff.codigo, ' ', ff.nombre) as nombre"),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla0101(int $anio, int $ue, int $cg, int $g, int $ff)
    {
        $aniosDisponibles = DB::table('pres_base_gastos as bg')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bg.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bg.anio', '<=', $anio)
            ->select('bg.anio')
            ->distinct()
            ->orderBy('bg.anio', 'desc')
            ->limit(8)
            ->pluck('bg.anio');

        $basegastosIds = $aniosDisponibles
            ->map(fn($a) => self::obtenerUltimoIdPorAnio((int) $a))
            ->filter(fn($id) => (int) $id > 0)
            ->values();

        if ($basegastosIds->isEmpty()) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas($basegastosIds->toArray());

        $query = DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ffin', 'ffin.id', '=', 'r.fuentefinanciamiento_id')
            ->whereIn('bgd.basegastos_id', $basegastosIds)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($ff > 0, fn($q) => $q->where('ffin.id', $ff));

        if ($g > 0) {
            $query
                ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
                ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
                ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
                ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
                ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
                ->where('ge.id', $g);
        }

        return $query
            ->groupBy('bgd.anio')
            ->selectRaw('
                bgd.anio as anio,
                SUM(CASE WHEN bgd.mes=1 THEN bgd.devengado ELSE 0 END) as ene,
                SUM(CASE WHEN bgd.mes=2 THEN bgd.devengado ELSE 0 END) as feb,
                SUM(CASE WHEN bgd.mes=3 THEN bgd.devengado ELSE 0 END) as mar,
                SUM(CASE WHEN bgd.mes=4 THEN bgd.devengado ELSE 0 END) as abr,
                SUM(CASE WHEN bgd.mes=5 THEN bgd.devengado ELSE 0 END) as may,
                SUM(CASE WHEN bgd.mes=6 THEN bgd.devengado ELSE 0 END) as jun,
                SUM(CASE WHEN bgd.mes=7 THEN bgd.devengado ELSE 0 END) as jul,
                SUM(CASE WHEN bgd.mes=8 THEN bgd.devengado ELSE 0 END) as ago,
                SUM(CASE WHEN bgd.mes=9 THEN bgd.devengado ELSE 0 END) as sep,
                SUM(CASE WHEN bgd.mes=10 THEN bgd.devengado ELSE 0 END) as oct,
                SUM(CASE WHEN bgd.mes=11 THEN bgd.devengado ELSE 0 END) as nov,
                SUM(CASE WHEN bgd.mes=12 THEN bgd.devengado ELSE 0 END) as dic
            ')
            ->orderBy('bgd.anio', 'desc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla2(int $anio, int $ue, int $cg, int $g)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('ge.id', $g))
            ->groupBy('r.nombre', 'r.codigo', 'r.id')
            ->select([
                'r.id as id',
                DB::raw("CONCAT(r.codigo, ' ', r.nombre) as nombre"),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla2_export(int $anio, int $ue, int $cg, int $g)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($g > 0, fn($q) => $q->where('ge.id', $g))
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'r.nombre', 'r.codigo', 'r.id')
            ->select([
                'r.id as id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as unidadejecutora"),
                DB::raw("CONCAT(r.codigo, ' ', r.nombre) as nombre"),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function fuenfinreportesreporte_tabla0201(int $anio, int $ue, int $cg, int $g, int $rb)
    {
        $aniosDisponibles = DB::table('pres_base_gastos as bg')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bg.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bg.anio', '<=', $anio)
            ->select('bg.anio')
            ->distinct()
            ->orderBy('bg.anio', 'desc')
            ->limit(8)
            ->pluck('bg.anio');

        $basegastosIds = $aniosDisponibles
            ->map(fn($a) => self::obtenerUltimoIdPorAnio((int) $a))
            ->filter(fn($id) => (int) $id > 0)
            ->values();

        if ($basegastosIds->isEmpty()) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas($basegastosIds->toArray());

        $query = DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->whereIn('bgd.basegastos_id', $basegastosIds)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bgd.categoriagasto_id', $cg))
            ->when($rb > 0, fn($q) => $q->where('r.id', $rb));

        if ($g > 0) {
            $query
                ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
                ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
                ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
                ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
                ->join('pres_generica_gastos as ge', 'ge.id', '=', 'sg.generica_id')
                ->where('ge.id', $g);
        }

        return $query
            ->groupBy('bgd.anio')
            ->selectRaw('
                bgd.anio as anio,
                SUM(CASE WHEN bgd.mes=1 THEN bgd.devengado ELSE 0 END) as ene,
                SUM(CASE WHEN bgd.mes=2 THEN bgd.devengado ELSE 0 END) as feb,
                SUM(CASE WHEN bgd.mes=3 THEN bgd.devengado ELSE 0 END) as mar,
                SUM(CASE WHEN bgd.mes=4 THEN bgd.devengado ELSE 0 END) as abr,
                SUM(CASE WHEN bgd.mes=5 THEN bgd.devengado ELSE 0 END) as may,
                SUM(CASE WHEN bgd.mes=6 THEN bgd.devengado ELSE 0 END) as jun,
                SUM(CASE WHEN bgd.mes=7 THEN bgd.devengado ELSE 0 END) as jul,
                SUM(CASE WHEN bgd.mes=8 THEN bgd.devengado ELSE 0 END) as ago,
                SUM(CASE WHEN bgd.mes=9 THEN bgd.devengado ELSE 0 END) as sep,
                SUM(CASE WHEN bgd.mes=10 THEN bgd.devengado ELSE 0 END) as oct,
                SUM(CASE WHEN bgd.mes=11 THEN bgd.devengado ELSE 0 END) as nov,
                SUM(CASE WHEN bgd.mes=12 THEN bgd.devengado ELSE 0 END) as dic
            ')
            ->orderBy('bgd.anio', 'desc')
            ->get();
    }

    public static function genericareportesreporte_anal1(int $anio, int $ue, $cp, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return (object) ['pim' => 0, 'devengado' => 0];
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when((int) $ff > 0, fn($q) => $q->where('ff.id', (int) $ff))
            ->select([
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado')
            ])
            ->first();
    }

    public static function genericareportesreporte_anal2(int $anio, int $ue, $cp, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when((int) $ff > 0, fn($q) => $q->where('ff.id', (int) $ff))
            ->groupBy('g.nombre', 'tt.codigo', 'g.codigo')
            ->select([
                DB::raw('concat(tt.codigo, ".", g.codigo, " ", g.nombre) as nombre'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado')
            ])
            ->orderBy('nombre')
            ->get();
    }

    public static function genericareportesreporte_tabla1(int $anio, int $ue, $cp, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when((int) $ff > 0, fn($q) => $q->where('ff.id', (int) $ff))
            ->groupBy('g.nombre', 'tt.codigo', 'g.codigo', 'g.id')
            ->select([
                'g.id as id',
                DB::raw('concat(tt.codigo, ".", g.codigo, " ", g.nombre) as nombre'),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla1_export(int $anio, int $ue, $cp, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when((int) $ff > 0, fn($q) => $q->where('ff.id', (int) $ff))
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'g.nombre', 'tt.codigo', 'g.codigo', 'g.id')
            ->select([
                'g.id as id',
                DB::raw('concat(ue.codigo_ue, " - ", ue.abreviatura) as unidadejecutora'),
                DB::raw('concat(tt.codigo, ".", g.codigo, " ", g.nombre) as nombre'),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla2(int $anio, int $ue, $cp, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when((int) $ff > 0, fn($q) => $q->where('ff.id', (int) $ff))
            ->groupBy('sg.id', 'tt.codigo', 'g.codigo', 'sg.codigo', 'sg.nombre')
            ->select([
                'sg.id as id',
                DB::raw('concat(tt.codigo, ".", g.codigo, ".", sg.codigo, " ", sg.nombre) as nombre'),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla2_export(int $anio, int $ue, $cp, $ff)
    {
        $basegastosId = self::obtenerUltimoIdPorAnio($anio);
        if ($basegastosId <= 0) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas([$basegastosId]);
        return DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'bgd.unidadejecutora_id')
                    ->whereIn('bgd.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
            ->join('pres_tipotransaccion as tt', 'tt.id', '=', 'g.tipotransaccion_id')
            ->where('bgd.basegastos_id', $basegastosId)
            ->when($ue > 0, fn($q) => $q->where('ue.id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when((int) $ff > 0, fn($q) => $q->where('ff.id', (int) $ff))
            ->groupBy('ue.codigo_ue', 'ue.abreviatura', 'sg.id', 'tt.codigo', 'g.codigo', 'sg.codigo', 'sg.nombre')
            ->select([
                'sg.id as id',
                DB::raw('concat(ue.codigo_ue, " - ", ue.abreviatura) as unidadejecutora'),
                DB::raw('concat(tt.codigo, ".", g.codigo, ".", sg.codigo, " ", sg.nombre) as nombre'),
                DB::raw('SUM(bgd.pia) as pia'),
                DB::raw('SUM(bgd.pim) as pim'),
                DB::raw('ROUND(SUM(bgd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(bgd.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(bgd.devengado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bgd.pim) > 0 THEN ROUND(100 * SUM(bgd.devengado) / SUM(bgd.pim), 1) ELSE 0 END as avance'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.certificado), 1) as saldocert'),
                DB::raw('ROUND(SUM(bgd.pim) - SUM(bgd.devengado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function genericareportesreporte_tabla0101(int $anio, int $ue, $cp = '', int $ff = 0, int $g = 0)
    {
        $aniosDisponibles = DB::table('pres_base_gastos as bg')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bg.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bg.anio', '<=', $anio)
            ->select('bg.anio')
            ->distinct()
            ->orderBy('bg.anio', 'desc')
            ->limit(8)
            ->pluck('bg.anio');

        $basegastosIds = $aniosDisponibles
            ->map(fn($a) => self::obtenerUltimoIdPorAnio((int) $a))
            ->filter(fn($id) => (int) $id > 0)
            ->values();

        if ($basegastosIds->isEmpty()) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas($basegastosIds->toArray());

        $query = DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->whereIn('bgd.basegastos_id', $basegastosIds)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff));

        if ($g > 0) {
            $query
                ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
                ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
                ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
                ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
                ->join('pres_generica_gastos as g', 'g.id', '=', 'sg.generica_id')
                ->where('g.id', $g);
        }

        return $query
            ->groupBy('bgd.anio')
            ->selectRaw('
                bgd.anio as anio,
                SUM(CASE WHEN bgd.mes=1 THEN bgd.devengado ELSE 0 END) as ene,
                SUM(CASE WHEN bgd.mes=2 THEN bgd.devengado ELSE 0 END) as feb,
                SUM(CASE WHEN bgd.mes=3 THEN bgd.devengado ELSE 0 END) as mar,
                SUM(CASE WHEN bgd.mes=4 THEN bgd.devengado ELSE 0 END) as abr,
                SUM(CASE WHEN bgd.mes=5 THEN bgd.devengado ELSE 0 END) as may,
                SUM(CASE WHEN bgd.mes=6 THEN bgd.devengado ELSE 0 END) as jun,
                SUM(CASE WHEN bgd.mes=7 THEN bgd.devengado ELSE 0 END) as jul,
                SUM(CASE WHEN bgd.mes=8 THEN bgd.devengado ELSE 0 END) as ago,
                SUM(CASE WHEN bgd.mes=9 THEN bgd.devengado ELSE 0 END) as sep,
                SUM(CASE WHEN bgd.mes=10 THEN bgd.devengado ELSE 0 END) as oct,
                SUM(CASE WHEN bgd.mes=11 THEN bgd.devengado ELSE 0 END) as nov,
                SUM(CASE WHEN bgd.mes=12 THEN bgd.devengado ELSE 0 END) as dic
            ')
            ->orderBy('bgd.anio', 'desc')
            ->get();
    }

    public static function genericareportesreporte_tabla0201(int $anio, int $ue, $cp = '', int $ff = 0, int $sg = 0)
    {
        $aniosDisponibles = DB::table('pres_base_gastos as bg')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bg.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bg.anio', '<=', $anio)
            ->select('bg.anio')
            ->distinct()
            ->orderBy('bg.anio', 'desc')
            ->limit(8)
            ->pluck('bg.anio');

        $basegastosIds = $aniosDisponibles
            ->map(fn($a) => self::obtenerUltimoIdPorAnio((int) $a))
            ->filter(fn($id) => (int) $id > 0)
            ->values();

        if ($basegastosIds->isEmpty()) {
            return collect();
        }

        $uePermitidas = self::ue_permitidas($basegastosIds->toArray());

        $query = DB::table('pres_base_gastos_detalle as bgd')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'bgd.categoriapresupuestal_id')
            ->join('pres_recursos_gastos as rg', 'rg.id', '=', 'bgd.recursosgastos_id')
            ->join('pres_rubro as r', 'r.id', '=', 'rg.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->join('pres_especificadetalle_gastos as ed', 'ed.id', '=', 'bgd.especificadetalle_id')
            ->join('pres_especifica_gastos as e', 'e.id', '=', 'ed.especifica_id')
            ->join('pres_subgenericadetalle_gastos as sgd', 'sgd.id', '=', 'e.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as sg', 'sg.id', '=', 'sgd.subgenerica_id')
            ->whereIn('bgd.basegastos_id', $basegastosIds)
            ->whereIn('bgd.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('bgd.unidadejecutora_id', $ue))
            ->when($cp != '' && $cp != '0' && $cp != 'todos', fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->when($ff > 0, fn($q) => $q->where('ff.id', $ff))
            ->when($sg > 0, fn($q) => $q->where('sg.id', $sg));

        return $query
            ->groupBy('bgd.anio')
            ->selectRaw('
                bgd.anio as anio,
                SUM(CASE WHEN bgd.mes=1 THEN bgd.devengado ELSE 0 END) as ene,
                SUM(CASE WHEN bgd.mes=2 THEN bgd.devengado ELSE 0 END) as feb,
                SUM(CASE WHEN bgd.mes=3 THEN bgd.devengado ELSE 0 END) as mar,
                SUM(CASE WHEN bgd.mes=4 THEN bgd.devengado ELSE 0 END) as abr,
                SUM(CASE WHEN bgd.mes=5 THEN bgd.devengado ELSE 0 END) as may,
                SUM(CASE WHEN bgd.mes=6 THEN bgd.devengado ELSE 0 END) as jun,
                SUM(CASE WHEN bgd.mes=7 THEN bgd.devengado ELSE 0 END) as jul,
                SUM(CASE WHEN bgd.mes=8 THEN bgd.devengado ELSE 0 END) as ago,
                SUM(CASE WHEN bgd.mes=9 THEN bgd.devengado ELSE 0 END) as sep,
                SUM(CASE WHEN bgd.mes=10 THEN bgd.devengado ELSE 0 END) as oct,
                SUM(CASE WHEN bgd.mes=11 THEN bgd.devengado ELSE 0 END) as nov,
                SUM(CASE WHEN bgd.mes=12 THEN bgd.devengado ELSE 0 END) as dic
            ')
            ->orderBy('bgd.anio', 'desc')
            ->get();
    }
}
