<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\ImporConsultaAmigable;
use Illuminate\Support\Facades\DB;

class ImporConsultaAmigableRepositorio
{
    public static function anios()
    {
        return ImporConsultaAmigable::from('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->select(DB::raw('distinct ca.anio as anio'))
            ->where('imp.estado', 'PR')
            ->orderBy('anio')
            ->get();
    }

    public static function listar_regiones($importacion, $articulobase)
    {
        $articulo = $articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3);
        $query = ImporConsultaAmigable::from('pres_impor_consulta_amigable as ca')
            ->join('pres_gobiernos_regionales as gr', 'gr.codigo', '=', 'ca.cod_gob_reg')
            ->select(
                DB::raw('gr.corto as name'),
                DB::raw('gr.codigo'),
                DB::raw('round(100*ca.devengado/nullif(ca.pim,0),5) as y')
            )
            ->where('ca.importacion_id', $importacion)
            ->where('ca.tipo', $articulo)
            ->orderBy('y', 'desc')
            ->get();
        return $query;
    }

    public static function baseids_fecha_max($anio, $articulobase = null)
    {
        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->select('ca.mes', DB::raw('max(ca.dia) as dia'))
            ->where('ca.anio', $anio)
            ->where('imp.estado', 'PR');

        if ($articulobase !== null) {
            $tipo = $articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3);
            $query->where('ca.tipo', $tipo);
        }

        return $query->groupBy('ca.mes')->orderBy('ca.mes', 'asc')->get();
    }

    public static function listado_ejecucion($fechas, $anio, $articulobase = null)
    {
        $tipo = $articulobase === null ? null : ($articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3));

        $sub = "select ca2.mes, max(ca2.dia) as dia
                from pres_impor_consulta_amigable ca2
                join par_importacion imp2 on imp2.id = ca2.importacion_id
                where ca2.anio = $anio and imp2.estado = 'PR'";
        if ($tipo !== null) {
            $sub .= " and ca2.tipo = $tipo";
        }
        $sub .= " group by ca2.mes";

        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->join('pres_gobiernos_regionales as gr', 'gr.codigo', '=', 'ca.cod_gob_reg')
            ->join(DB::raw("($sub) as v2"), function ($join) {
                $join->on('v2.mes', '=', 'ca.mes')->on('v2.dia', '=', 'ca.dia');
            })
            ->select(
                'ca.mes as mes',
                'gr.departamento as dep',
                DB::raw('sum(ca.pim) as pim'),
                DB::raw('sum(ca.devengado) as devengado'),
                DB::raw('round(100*sum(ca.devengado)/nullif(sum(ca.pim),0),5) as eje')
            )
            ->where('ca.anio', $anio)
            ->where('imp.estado', 'PR');

        if ($tipo !== null) {
            $query->where('ca.tipo', $tipo);
        }

        return $query
            ->groupBy('ca.mes', 'gr.departamento')
            ->orderBy('ca.mes', 'asc')
            ->orderBy('eje', 'desc')
            ->get();
    }

    public static function resumen_ultimo_registro($anio, $articulobase = null)
    {
        $tipo = $articulobase === null ? null : ($articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3));

        $imp = DB::table('par_importacion as imp')
            ->join('pres_impor_consulta_amigable as ca', 'ca.importacion_id', '=', 'imp.id')
            ->where('imp.estado', 'PR')
            ->where('ca.anio', $anio);
        if ($tipo !== null) $imp->where('ca.tipo', $tipo);
        $imp = $imp->orderBy('imp.fechaActualizacion', 'desc')->select('imp.id')->first();

        if (!$imp) {
            return (object)[
                'pia' => 0,
                'pim' => 0,
                'certificacion' => 0,
                'devengado' => 0,
                'eje_pia' => 0,
                'eje_pim' => 0,
                'eje_cer' => 0,
                'eje_dev' => 0,
            ];
        }

        $res = DB::table('pres_impor_consulta_amigable as ca')
            ->select(
                DB::raw('sum(ca.pia) as pia'),
                DB::raw('sum(ca.pim) as pim'),
                DB::raw('sum(ca.certificacion) as certificacion'),
                DB::raw('sum(ca.devengado) as devengado')
            )
            ->where('ca.importacion_id', $imp->id)
            ->where('ca.cod_gob_reg', 462)
            ->where('ca.anio', $anio);
        if ($tipo !== null) $res->where('ca.tipo', $tipo);
        $res = $res->first();

        $pia = (float)($res->pia ?? 0);
        $pim = (float)($res->pim ?? 0);
        $cer = (float)($res->certificacion ?? 0);
        $dev = (float)($res->devengado ?? 0);

        $eje_pia = $pia ? round(100 * $dev / $pia, 2) : 0;
        $eje_pim = $pim ? round(100 * $dev / $pim, 2) : 0;
        $eje_cer = $cer ? round(100 * $cer / $pim, 2) : 0;
        $eje_dev = $dev ? round(100 * $dev / $cer, 2) : 0;

        return (object)[
            'pia' => $pia,
            'pim' => $pim,
            'certificacion' => $cer,
            'devengado' => $dev,
            'eje_pia' => $eje_pia,
            'eje_pim' => $eje_pim,
            'eje_cer' => $eje_cer,
            'eje_dev' => $eje_dev,
        ];
    }

    public static function pim_ucayali_ultimo_registro_mes($anio, $articulobase = null)
    {
        $tipo = $articulobase === null ? null : ($articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3));

        $ultDiaPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->select('ca2.mes', DB::raw('max(ca2.dia) as dia'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultDiaPorMes->where('ca2.tipo', $tipo);
        $ultDiaPorMes = $ultDiaPorMes->groupBy('ca2.mes');

        $ultImpPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->joinSub($ultDiaPorMes, 'd', function ($join) {
                $join->on('d.mes', '=', 'ca2.mes')->on('d.dia', '=', 'ca2.dia');
            })
            ->select('ca2.mes', 'ca2.dia', DB::raw('max(ca2.importacion_id) as importacion_id'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultImpPorMes->where('ca2.tipo', $tipo);
        $ultImpPorMes = $ultImpPorMes->groupBy('ca2.mes', 'ca2.dia');

        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->joinSub($ultImpPorMes, 'u', function ($join) {
                $join->on('u.mes', '=', 'ca.mes')
                    ->on('u.dia', '=', 'ca.dia')
                    ->on('u.importacion_id', '=', 'ca.importacion_id');
            })
            ->select(
                DB::raw('ca.mes as name'),
                DB::raw('sum(ca.pim) as y')
            )
            ->where('imp.estado', 'PR')
            ->where('imp.fuenteImportacion_id', 51)
            ->where('ca.anio', $anio)
            ->where('ca.cod_gob_reg', 462);

        if ($tipo !== null) $query->where('ca.tipo', $tipo);

        return $query->groupBy('ca.mes')->orderBy('ca.mes', 'asc')->get();
    }

    public static function cert_ucayali_ultimo_registro_mes($anio, $articulobase = null)
    {
        $tipo = $articulobase === null ? null : ($articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3));

        $ultDiaPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->select('ca2.mes', DB::raw('max(ca2.dia) as dia'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultDiaPorMes->where('ca2.tipo', $tipo);
        $ultDiaPorMes = $ultDiaPorMes->groupBy('ca2.mes');

        $ultImpPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->joinSub($ultDiaPorMes, 'd', function ($join) {
                $join->on('d.mes', '=', 'ca2.mes')->on('d.dia', '=', 'ca2.dia');
            })
            ->select('ca2.mes', 'ca2.dia', DB::raw('max(ca2.importacion_id) as importacion_id'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultImpPorMes->where('ca2.tipo', $tipo);
        $ultImpPorMes = $ultImpPorMes->groupBy('ca2.mes', 'ca2.dia');

        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->joinSub($ultImpPorMes, 'u', function ($join) {
                $join->on('u.mes', '=', 'ca.mes')
                    ->on('u.dia', '=', 'ca.dia')
                    ->on('u.importacion_id', '=', 'ca.importacion_id');
            })
            ->select(
                DB::raw('ca.mes as name'),
                DB::raw('sum(ca.certificacion) as y')
            )
            ->where('imp.estado', 'PR')
            ->where('imp.fuenteImportacion_id', 51)
            ->where('ca.anio', $anio)
            ->where('ca.cod_gob_reg', 462);

        if ($tipo !== null) $query->where('ca.tipo', $tipo);

        return $query->groupBy('ca.mes')->orderBy('ca.mes', 'asc')->get();
    }

    public static function dev_ucayali_ultimo_registro_mes($anio, $articulobase = null)
    {
        $tipo = $articulobase === null ? null : ($articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3));

        $ultDiaPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->select('ca2.mes', DB::raw('max(ca2.dia) as dia'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultDiaPorMes->where('ca2.tipo', $tipo);
        $ultDiaPorMes = $ultDiaPorMes->groupBy('ca2.mes');

        $ultImpPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->joinSub($ultDiaPorMes, 'd', function ($join) {
                $join->on('d.mes', '=', 'ca2.mes')->on('d.dia', '=', 'ca2.dia');
            })
            ->select('ca2.mes', 'ca2.dia', DB::raw('max(ca2.importacion_id) as importacion_id'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultImpPorMes->where('ca2.tipo', $tipo);
        $ultImpPorMes = $ultImpPorMes->groupBy('ca2.mes', 'ca2.dia');

        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->joinSub($ultImpPorMes, 'u', function ($join) {
                $join->on('u.mes', '=', 'ca.mes')
                    ->on('u.dia', '=', 'ca.dia')
                    ->on('u.importacion_id', '=', 'ca.importacion_id');
            })
            ->select(
                DB::raw('ca.mes as name'),
                DB::raw('sum(ca.devengado) as y')
            )
            ->where('imp.estado', 'PR')
            ->where('imp.fuenteImportacion_id', 51)
            ->where('ca.anio', $anio)
            ->where('ca.cod_gob_reg', 462);

        if ($tipo !== null) $query->where('ca.tipo', $tipo);

        return $query->groupBy('ca.mes')->orderBy('ca.mes', 'asc')->get();
    }

    public static function suma_xxxx($anio, $articulobase = null)
    {
        $tipo = $articulobase === null ? null : ($articulobase == 0 ? 1 : ($articulobase == 1 ? 2 : 3));

        $ultDiaPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->select('ca2.mes', DB::raw('max(ca2.dia) as dia'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultDiaPorMes->where('ca2.tipo', $tipo);
        $ultDiaPorMes = $ultDiaPorMes->groupBy('ca2.mes');

        $ultImpPorMes = DB::table('pres_impor_consulta_amigable as ca2')
            ->join('par_importacion as imp2', 'imp2.id', '=', 'ca2.importacion_id')
            ->joinSub($ultDiaPorMes, 'd', function ($join) {
                $join->on('d.mes', '=', 'ca2.mes')->on('d.dia', '=', 'ca2.dia');
            })
            ->select('ca2.mes', 'ca2.dia', DB::raw('max(ca2.importacion_id) as importacion_id'))
            ->where('imp2.estado', 'PR')
            ->where('imp2.fuenteImportacion_id', 51)
            ->where('ca2.anio', $anio)
            ->where('ca2.cod_gob_reg', 462);
        if ($tipo !== null) $ultImpPorMes->where('ca2.tipo', $tipo);
        $ultImpPorMes = $ultImpPorMes->groupBy('ca2.mes', 'ca2.dia');

        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->joinSub($ultImpPorMes, 'u', function ($join) {
                $join->on('u.mes', '=', 'ca.mes')
                    ->on('u.dia', '=', 'ca.dia')
                    ->on('u.importacion_id', '=', 'ca.importacion_id');
            })
            ->select(
                DB::raw('ca.mes as name'),
                DB::raw('sum(ca.pim) as y1'),
                DB::raw('sum(ca.certificacion) as y2'),
                DB::raw('sum(ca.devengado) as y3'),
                DB::raw('round(100*sum(ca.certificacion)/sum(ca.pim),1) as y4'),
                DB::raw('round(100*sum(ca.devengado)/sum(ca.pim),1) as y5')
            )
            ->where('imp.estado', 'PR')
            ->where('imp.fuenteImportacion_id', 51)
            ->where('ca.anio', $anio)
            ->where('ca.cod_gob_reg', 462);

        if ($tipo !== null) $query->where('ca.tipo', $tipo);

        return $query->groupBy('ca.mes')->orderBy('ca.mes', 'asc')->get();
    }
}
