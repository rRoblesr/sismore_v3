<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\BaseSiafWebDetalle;
use Illuminate\Support\Facades\DB;

class UnidadOrganicaRepositorio
{
    public static function listar_ejecuciongasto_anio_acticulo_ue_categoria($anio, $articulo, $ue, $cg) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        /* $body = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_categoriagasto as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_grupofuncional as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v3a', 'v3a.id', '=', 'v3.divisionfuncional_id')
            ->join('pres_funcion as v3b', 'v3b.id', '=', 'v3a.funcion_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_especificadetalle_gastos as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v5a', 'v5a.id', '=', 'v5.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v5b', 'v5b.id', '=', 'v5a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v5c', 'v5c.id', '=', 'v5b.subgenerica_id')
            ->join('pres_generica_gastos as v5d', 'v5d.id', '=', 'v5c.generica_id')
            ->join('pres_ue_meta as v6', 'v6.meta_id', '=', 'pres_base_siafweb_detalle.meta_id')
            ->join('pres_unidadorganica as v6a', 'v6a.id', '=', 'v6.unidadorganica_id')
            ->select(
                //DB::raw('v2a.id'),
                DB::raw('v6a.nombre as uo'),
                DB::raw('v3b.nombre as funcion'),
                DB::raw('concat("2.",v5d.codigo,".",v5c.codigo,".",v5b.codigo,".",v5a.codigo,".",v5.codigo) as codigo'),
                DB::raw('v5.nombre as especificadetalle'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pia)) as pia'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim)) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.certificado)) as cert'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado)) as dev'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),2) as eje'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado)) as saldo1'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado)) as saldo2')
            );
        if ($articulo != 0) $body = $body->where('v4.id', $articulo);
        if ($ue != 0) $body = $body->where('v6.unidadejecutora_id', $ue);
        if ($cg != 0) $body = $body->where('v2.id', $cg);
        $body = $body->groupBy('uo', 'funcion', 'codigo', 'especificadetalle')->get(); */

        $head = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_categoriagasto as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_grupofuncional as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v3a', 'v3a.id', '=', 'v3.divisionfuncional_id')
            ->join('pres_funcion as v3b', 'v3b.id', '=', 'v3a.funcion_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_especificadetalle_gastos as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v5a', 'v5a.id', '=', 'v5.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v5b', 'v5b.id', '=', 'v5a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v5c', 'v5c.id', '=', 'v5b.subgenerica_id')
            ->join('pres_generica_gastos as v5d', 'v5d.id', '=', 'v5c.generica_id')
            ->join('pres_ue_meta as v6', 'v6.meta_id', '=', 'pres_base_siafweb_detalle.meta_id')
            ->join('pres_unidadorganica as v6a', 'v6a.id', '=', 'v6.unidadorganica_id')
            ->join('pres_unidadejecutora as v7', 'v7.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->select(
                DB::raw('v6a.nombre as uo'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pia)) as pia'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim)) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.certificado)) as cert'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado)) as dev'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),2) as eje'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado)) as saldo1'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado)) as saldo2')
            );
        if ($articulo != 0) $head = $head->where('v4.id', $articulo);
        if ($ue != 0) $head = $head->where('v7.id', $ue);
        if ($cg != 0) $head = $head->where('v2.id', $cg);
        $head = $head->groupBy('uo')->get();

        /* $subhead = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_categoriagasto as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_grupofuncional as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v3a', 'v3a.id', '=', 'v3.divisionfuncional_id')
            ->join('pres_funcion as v3b', 'v3b.id', '=', 'v3a.funcion_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_especificadetalle_gastos as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v5a', 'v5a.id', '=', 'v5.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v5b', 'v5b.id', '=', 'v5a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v5c', 'v5c.id', '=', 'v5b.subgenerica_id')
            ->join('pres_generica_gastos as v5d', 'v5d.id', '=', 'v5c.generica_id')
            ->join('pres_ue_meta as v6', 'v6.meta_id', '=', 'pres_base_siafweb_detalle.meta_id')
            ->join('pres_unidadorganica as v6a', 'v6a.id', '=', 'v6.unidadorganica_id')
            ->select(
                //DB::raw('v2a.id'),
                DB::raw('v6a.nombre as uo'),
                DB::raw('v3b.nombre as funcion'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pia)) as pia'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim)) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.certificado)) as cert'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado)) as dev'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),2) as eje'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado)) as saldo1'),
                DB::raw('round(sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado)) as saldo2')
            );
        if ($articulo != 0) $subhead = $subhead->where('v4.id', $articulo);
        if ($ue != 0) $subhead = $subhead->where('v6.unidadejecutora_id', $ue);
        if ($cg != 0) $subhead = $subhead->where('v2.id', $cg);
        $subhead = $subhead->groupBy('uo', 'funcion')->get(); */
        return ['head' => $head/* , 'subhead' => $subhead, 'body' => $body */];
    }
}
