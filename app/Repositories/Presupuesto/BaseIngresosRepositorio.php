<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseIngresos;
use App\Models\Presupuesto\BaseIngresosDetalle;
use Illuminate\Support\Facades\DB;

class BaseIngresosRepositorio
{
    public static function anios()
    {
        $anios = BaseIngresos::distinct()
            ->select('anio')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_ingresos.importacion_id')
            ->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->get();
        return $anios;
    }

    public static function fechasActualicacion_anos_max()
    {
        $fechasb = DB::table(DB::raw("(
            select
                w1.id
            from pres_base_ingresos as w1
            inner join par_importacion as w2 on w2.id = w1.importacion_id
            inner join (
                select max(x2.fechaActualizacion) as fecha from pres_base_ingresos as x1
                inner join par_importacion as x2 on x2.id = x1.importacion_id
                where x2.estado = 'PR'
                group by anio
                        ) as w3 on w3.fecha=w2.fechaActualizacion
            where w2.estado = 'PR'    ) as tb
            "))->get();
        $fechas = [];
        foreach ($fechasb as $key => $value) {
            $fechas[] = $value->id;
        }
        return $fechas;
    }

    public static function obtenerUltimoIdPorAnio(int $anio): int
    {
        $row = DB::table('pres_base_ingresos as bi')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'bi.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('bi.anio', $anio)
            ->orderBy('i.fechaActualizacion', 'desc')
            ->select('bi.id')
            ->first();

        return $row ? (int) $row->id : 0;
    }

    public static function catpresreportesreporte_anal1(int $anio, int $ue, $cg, $ff)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return (object) ['pim' => 0, 'devengado' => 0];
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_rubro as r', 'r.id', '=', 'bid.rubro_id')
            ->join('pres_fuentefinanciamiento as ff_table', 'ff_table.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->when($ue > 0, fn($q) => $q->where('bid.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff_table.id', $ff))
            ->select([
                DB::raw('SUM(bid.pim) as pim'),
                DB::raw('ROUND(SUM(bid.recaudado), 1) as devengado')
            ])
            ->first();
    }

    public static function catpresreportesreporte_anal2(int $anio, int $ue, $cg, $ff)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return collect();
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_rubro as r', 'r.id', '=', 'bid.rubro_id')
            ->join('pres_fuentefinanciamiento as ff_table', 'ff_table.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->when($ue > 0, fn($q) => $q->where('bid.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff_table.id', $ff))
            ->groupBy('r.rubro')
            ->select([
                'r.rubro as nombre',
                DB::raw('SUM(bid.pim) as pim'),
                DB::raw('ROUND(SUM(bid.recaudado), 1) as devengado')
            ])
            ->get();
    }

    public static function catpresreportesreporte_tabla1(int $anio, int $ue, $cg, $ff)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return collect();
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_rubro as r', 'r.id', '=', 'bid.rubro_id')
            ->join('pres_fuentefinanciamiento as ff_table', 'ff_table.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->when($ue > 0, fn($q) => $q->where('bid.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff_table.id', $ff))
            ->groupBy('r.rubro', 'r.codigo', 'r.id')
            ->select([
                'r.id as id',
                DB::raw("CONCAT(r.codigo, ' ', r.rubro) as nombre"),
                DB::raw('0 as pia'),
                DB::raw('SUM(bid.pim) as pim'),
                DB::raw('0 as certificado'),
                DB::raw('0 as compromiso'),
                DB::raw('ROUND(SUM(bid.recaudado), 1) as devengado'),
                DB::raw('CASE WHEN SUM(bid.pim) > 0 THEN ROUND(100 * SUM(bid.recaudado) / SUM(bid.pim), 1) ELSE 0 END as avance'),
                DB::raw('0 as saldocert'),
                DB::raw('ROUND(SUM(bid.pim) - SUM(bid.recaudado), 1) as saldodev')
            ])
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public static function catpresreportesreporte_tabla0101(int $anio, int $ue, $cg, $ff, $cp)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return collect();
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_rubro as r', 'r.id', '=', 'bid.rubro_id')
            ->join('pres_fuentefinanciamiento as ff_table', 'ff_table.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->when($ue > 0, fn($q) => $q->where('bid.unidadejecutora_id', $ue))
            ->when($ff > 0, fn($q) => $q->where('ff_table.id', $ff))
            ->when($cp > 0, fn($q) => $q->where('bid.rubro_id', $cp))
            ->select([
                DB::raw('SUM(bid.recaudado_ene) as ene'),
                DB::raw('SUM(bid.recaudado_feb) as feb'),
                DB::raw('SUM(bid.recaudado_mar) as mar'),
                DB::raw('SUM(bid.recaudado_abr) as abr'),
                DB::raw('SUM(bid.recaudado_may) as may'),
                DB::raw('SUM(bid.recaudado_jun) as jun'),
                DB::raw('SUM(bid.recaudado_jul) as jul'),
                DB::raw('SUM(bid.recaudado_ago) as ago'),
                DB::raw('SUM(bid.recaudado_sep) as sep'),
                DB::raw('SUM(bid.recaudado_oct) as oct'),
                DB::raw('SUM(bid.recaudado_nov) as nov'),
                DB::raw('SUM(bid.recaudado_dic) as dic')
            ])
            ->get();
    }

    public static function obtenerUnidadesEjecutorasParaSelect(int $anio)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return collect();
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'bid.unidadejecutora_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->select([
                'ue.id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as nombre")
            ])
            ->distinct()
            ->orderBy('ue.codigo_ue', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerRubrosParaSelect(int $anio, int $ue)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return collect();
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_rubro as r', 'r.id', '=', 'bid.rubro_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->when($ue > 0, fn($q) => $q->where('bid.unidadejecutora_id', $ue))
            ->select('r.id', 'r.rubro as nombre')
            ->distinct()
            ->orderBy('r.rubro', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerFuenteFinanciamientoParaSelect(int $anio, int $ue, int $cg)
    {
        $baseingresosId = self::obtenerUltimoIdPorAnio($anio);
        if ($baseingresosId <= 0) {
            return collect();
        }
        return DB::table('pres_base_ingresos_detalle as bid')
            ->join('pres_rubro as r', 'r.id', '=', 'bid.rubro_id')
            ->join('pres_fuentefinanciamiento as ff', 'ff.id', '=', 'r.fuentefinanciamiento_id')
            ->where('bid.baseingresos_id', $baseingresosId)
            ->when($ue > 0, fn($q) => $q->where('bid.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('bid.rubro_id', $cg))
            ->select('ff.id', 'ff.nombre')
            ->distinct()
            ->orderBy('ff.nombre', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function total_pim($imp)
    {
        $query = BaseIngresosDetalle::where('baseingresos_id', $imp)->select(DB::raw('sum(pim) as pim'), DB::raw('100*sum(recaudado)/sum(pim) as eje'))->first();
        return $query;
    }
    /*
    public static function pim_tipogobierno($imp)
    {
        $query = BaseGastosDetalle::select(
            'v5.id',
            'v5.tipogobierno as gobiernos',
            DB::raw('sum(pres_base_gastos_detalle.pim) as pim'),
            DB::raw('100*sum(pres_base_gastos_detalle.devengado)/sum(pres_base_gastos_detalle.pim) as eje')
        )
            ->join('pres_base_gastos as w1', 'w1.id', '=', 'pres_base_gastos_detalle.basegastos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_gastos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->where('w2.estado', 'PR')
            ->where('pres_base_gastos_detalle.basegastos_id', $imp);
        $query = $query->groupBy('id', 'gobiernos')->get();
        return $query;
    } */

    public static function pim_tipogobierno($baseingresos_id)
    {
        $query = BaseIngresosDetalle::where('pres_base_ingresos_detalle.baseingresos_id', $baseingresos_id)
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->select(
                'v5.id',
                'v5.tipogobierno as name',
                DB::raw('sum(pres_base_ingresos_detalle.pim) as y'),
                DB::raw('round(100*sum(pres_base_ingresos_detalle.recaudado)/sum(pres_base_ingresos_detalle.pim),1) as eje'),
            )
            ->groupBy('id', 'name')
            ->orderBy('v5.pos', 'asc')
            ->get();

        $pad = [
            (object)['id' => null, 'name' => 'GOBIERNO NACIONAL', 'y' => 0, 'eje' => 0, 'color' => '#7e57c2'],
            (object)['id' => null, 'name' => 'GOBIERNOS REGIONALES', 'y' => 0, 'eje' => 0, 'color' => '#317eeb'],
            (object)['id' => null, 'name' => 'GOBIERNOS LOCALES', 'y' => 0, 'eje' => 0, 'color' => '#ef5350'],
        ];

        foreach ($query as $row) {
            $nombre = strtoupper(trim((string)($row->name ?? '')));
            if ($nombre === '') continue;
            if (strpos($nombre, 'NACIONAL') !== false) {
                $pad[0] = (object)['id' => $row->id ?? null, 'name' => $row->name ?? 'GOBIERNO NACIONAL', 'y' => $row->y ?? 0, 'eje' => $row->eje ?? 0, 'color' => '#7e57c2'];
                continue;
            }
            if (strpos($nombre, 'REGIONAL') !== false) {
                $pad[1] = (object)['id' => $row->id ?? null, 'name' => $row->name ?? 'GOBIERNOS REGIONALES', 'y' => $row->y ?? 0, 'eje' => $row->eje ?? 0, 'color' => '#317eeb'];
                continue;
            }
            if (strpos($nombre, 'LOCAL') !== false) {
                $pad[2] = (object)['id' => $row->id ?? null, 'name' => $row->name ?? 'GOBIERNOS LOCALES', 'y' => $row->y ?? 0, 'eje' => $row->eje ?? 0, 'color' => '#ef5350'];
                continue;
            }
        }

        return collect($pad);
    }

    public static function pim_anios_tipogobierno()
    {
        $fechas = BaseIngresosRepositorio::fechasActualicacion_anos_max();
        $query = BaseIngresosDetalle::where('w2.estado', 'PR')
            ->join('pres_base_ingresos as w1', 'w1.id', '=', 'pres_base_ingresos_detalle.baseingresos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->select(
                'w1.id',
                'w1.anio as ano',
                DB::raw("sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_ingresos_detalle.pim,0)) as pim1"),
                DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_ingresos_detalle.pim,0)) as pim2"),
                DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_ingresos_detalle.pim,0)) as pim3"),
            )
            ->whereIn('w1.id', $fechas)
            ->groupBy('id', 'ano')
            ->orderBy('ano')
            ->get();
        return $query;
    }

    public static function recaudado_anios_tipogobierno()
    {
        $fechas = BaseIngresosRepositorio::fechasActualicacion_anos_max();
        $query = BaseIngresosDetalle::where('w2.estado', 'PR')
            ->join('pres_base_ingresos as w1', 'w1.id', '=', 'pres_base_ingresos_detalle.baseingresos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->select(
                'w1.id',
                'w1.anio as ano',
                DB::raw("sum(IF(v5.tipogobierno='GOBIERNO NACIONAL',pres_base_ingresos_detalle.recaudado,0)) as pim1"),
                DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS REGIONALES',pres_base_ingresos_detalle.recaudado,0)) as pim2"),
                DB::raw("sum(IF(v5.tipogobierno='GOBIERNOS LOCALES',pres_base_ingresos_detalle.recaudado,0)) as pim3"),
            )
            ->whereIn('w1.id', $fechas)
            ->groupBy('id', 'ano')
            ->orderBy('ano')
            ->get();
        return $query;
    }

    public static function pim_pia_devengado_tipogobierno($baseingresos_id)
    {
        $query = BaseIngresosDetalle::where('pres_base_ingresos_detalle.baseingresos_id', $baseingresos_id)
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id')
            ->select(
                'v5.id',
                'v5.tipogobierno as name',
                DB::raw('sum(pres_base_ingresos_detalle.pia) as y1'),
                DB::raw('sum(pres_base_ingresos_detalle.pim) as y2'),
                DB::raw('sum(pres_base_ingresos_detalle.recaudado) as y3'),
            )
            ->groupBy('id', 'name')
            ->orderBy('v5.pos', 'asc')
            ->get();
        return $query;
    }

    public static function listar_sectorpliego_anio_tipogobierno_fuentefinanciamiento($anio, $gobierno,  $financiamiento) //base detallee
    {
        $baseingreso_id = BaseIngresos::select('pres_base_ingresos.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_ingresos.importacion_id')
            ->where('pres_base_ingresos.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $head = BaseIngresosDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $baseingreso_id)
            ->join('pres_base_ingresos as w1', 'w1.id', '=', 'pres_base_ingresos_detalle.baseingresos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v2a', 'v2a.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v2b', 'v2b.id', '=', 'v2a.sector_id')
            ->join('pres_tipo_gobierno as v2c', 'v2c.id', '=', 'v2b.tipogobierno_id')
            ->join('pres_recursos_ingreso as v3', 'v3.id', '=', 'pres_base_ingresos_detalle.recursosingreso_id')
            ->join('pres_rubro as v3a', 'v3a.id', '=', 'v3.rubro_id')
            ->join('pres_fuentefinanciamiento as v3b', 'v3b.id', '=', 'v3a.fuentefinanciamiento_id')
            ->select(
                'v2b.nombre as sector',
                DB::raw('sum(pres_base_ingresos_detalle.pia) as pia'),
                DB::raw('sum(pres_base_ingresos_detalle.pim) as pim'),
                DB::raw('sum(pres_base_ingresos_detalle.recaudado) as recaudado'),
                DB::raw('100*sum(pres_base_ingresos_detalle.recaudado)/sum(pres_base_ingresos_detalle.pim) as eje'),
            );
        if ($gobierno != 0) $head = $head->where('v2c.id', $gobierno);
        if ($financiamiento != 0) $head = $head->where('v3b.id', $financiamiento);
        $head = $head->groupBy('sector')->orderBy('eje','desc')->get();

        $body = BaseIngresosDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $baseingreso_id)
            ->join('pres_base_ingresos as w1', 'w1.id', '=', 'pres_base_ingresos_detalle.baseingresos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v2a', 'v2a.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v2b', 'v2b.id', '=', 'v2a.sector_id')
            ->join('pres_tipo_gobierno as v2c', 'v2c.id', '=', 'v2b.tipogobierno_id')
            ->join('pres_recursos_ingreso as v3', 'v3.id', '=', 'pres_base_ingresos_detalle.recursosingreso_id')
            ->join('pres_rubro as v3a', 'v3a.id', '=', 'v3.rubro_id')
            ->join('pres_fuentefinanciamiento as v3b', 'v3b.id', '=', 'v3a.fuentefinanciamiento_id')
            ->select(
                'v2b.nombre as sector',
                'v2a.nombre as pliego',
                DB::raw('sum(pres_base_ingresos_detalle.pia) as pia'),
                DB::raw('sum(pres_base_ingresos_detalle.pim) as pim'),
                DB::raw('sum(pres_base_ingresos_detalle.recaudado) as recaudado'),
                DB::raw('100*sum(pres_base_ingresos_detalle.recaudado)/sum(pres_base_ingresos_detalle.pim) as eje'),
            );
        if ($gobierno != 0) $body = $body->where('v2c.id', $gobierno);
        if ($financiamiento != 0) $body = $body->where('v3b.id', $financiamiento);
        $body = $body->groupBy('sector', 'pliego')->orderBy('eje','desc')->get();

        $foot = BaseIngresosDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $baseingreso_id)
            ->join('pres_base_ingresos as w1', 'w1.id', '=', 'pres_base_ingresos_detalle.baseingresos_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_ingresos_detalle.unidadejecutora_id')
            ->join('pres_pliego as v2a', 'v2a.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v2b', 'v2b.id', '=', 'v2a.sector_id')
            ->join('pres_tipo_gobierno as v2c', 'v2c.id', '=', 'v2b.tipogobierno_id')
            ->join('pres_recursos_ingreso as v3', 'v3.id', '=', 'pres_base_ingresos_detalle.recursosingreso_id')
            ->join('pres_rubro as v3a', 'v3a.id', '=', 'v3.rubro_id')
            ->join('pres_fuentefinanciamiento as v3b', 'v3b.id', '=', 'v3a.fuentefinanciamiento_id')
            ->select(
                DB::raw('sum(pres_base_ingresos_detalle.pia) as pia'),
                DB::raw('sum(pres_base_ingresos_detalle.pim) as pim'),
                DB::raw('sum(pres_base_ingresos_detalle.recaudado) as recaudado'),
                DB::raw('100*sum(pres_base_ingresos_detalle.recaudado)/sum(pres_base_ingresos_detalle.pim) as eje'),
            );
        if ($gobierno != 0) $foot = $foot->where('v2c.id', $gobierno);
        if ($financiamiento != 0) $foot = $foot->where('v3b.id', $financiamiento);
        $foot = $foot->get()->first();
        return ['head' => $head, 'body' => $body, 'foot' => $foot,];
    }
}
