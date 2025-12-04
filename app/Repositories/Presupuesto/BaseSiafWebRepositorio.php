<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\BaseSiafWebDetalle;
use App\Models\Presupuesto\PartidasRestringidas;
use App\Models\Presupuesto\UnidadEjecutora;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BaseSiafWebRepositorio
{
    public static function anios()
    {
        $query = BaseSiafWeb::select(DB::raw('distinct anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->orderBy('anio', 'asc')->get();
        return $query;
    }

    public static function obtenerUltimoIdPorAnio(int $anio): ?int
    {
        return DB::table('pres_base_siafweb as sw')
            ->join('par_importacion as i', function ($join) {
                $join->on('i.id', '=', 'sw.importacion_id')
                    ->where('i.estado', '=', 'PR');
            })
            ->where('sw.anio', $anio)
            ->orderBy('i.fechaActualizacion', 'desc')
            ->limit(1)
            ->value('sw.id');
    }

    public static function UE_poranios($anio)
    {
        if ($anio == 0) {
            $queryJoinPresBaseSiafwebDetalle = '(select DISTINCT unidadejecutora_id from pres_base_siafweb_detalle) as siaf';
        } else {
            $queryJoinPresBaseSiafwebDetalle = "(select DISTINCT unidadejecutora_id from pres_base_siafweb_detalle as bsd inner join pres_base_siafweb as bs on bs.id=bsd.basesiafweb_id where bs.anio=$anio) as siaf";
        }

        $query = UnidadEjecutora::select(
            'pres_unidadejecutora.id',
            'pres_unidadejecutora.nombre_ejecutora as nombre',
            'pres_unidadejecutora.codigo_ue as codigo'
        )
            ->join(DB::raw($queryJoinPresBaseSiafwebDetalle), 'siaf.unidadejecutora_id', '=', 'pres_unidadejecutora.id')
            ->orderBy('codigo')->get();
        return $query;
    }

    public static function pia_pim_certificado_devengado($base, $tipo)
    {
        $query = BaseSiafWebDetalle::where('basesiafweb_id', $base)
            ->select(
                DB::raw('sum(pia) as pia'),
                DB::raw('sum(pim) as pim'),
                DB::raw('round(sum(certificado),1) as cer'),
                DB::raw('round(sum(devengado),1) as dev'),
                DB::raw('round(100*sum(devengado)/sum(pia),1) as eje_pia'),
                DB::raw('round(100*sum(devengado)/sum(pim),1) as eje_pim'),
                DB::raw('round(100*sum(certificado)/sum(pim),1) as eje_cer'),
                DB::raw('round(100*sum(devengado)/sum(certificado),1) as eje_dev')
            );
        if ($tipo != 0)
            $query = $query->where('productoproyecto_id', $tipo);
        $query = $query->first();
        return $query;
    }

    public static function baseids_fecha_max($anio)
    { //year(curdate())
        $query = DB::table(DB::raw("(select v1.id from pres_base_siafweb v1
            join par_importacion v3 on v3.id=v1.importacion_id
            join (select anio,mes,max(dia) as dia from pres_base_siafweb where anio=$anio group by anio,mes) as v2 on v2.anio=v1.anio and v2.mes=v1.mes and v2.dia=v1.dia
            where v1.anio=$anio and v3.estado='PR') as tb"))->get();
        $array = [];
        foreach ($query as $key => $value) {
            $array[] = $value->id;
        }
        return $array;
    }

    public static function baseids_max($anio)
    { //year(curdate())
        $query = DB::table(DB::raw("(select v1.* from pres_base_siafweb v1
        join par_importacion v3 on v3.id=v1.importacion_id
        join (select anio,mes,max(dia) as dia from pres_base_siafweb where anio=$anio group by anio,mes) as v2 on v2.anio=v1.anio and v2.mes=v1.mes and v2.dia=v1.dia
        where v1.anio=$anio and v3.estado='PR' order by mes desc,dia desc limit 1) as tb"))->first();
        return $query->id;
    }

    public static function suma_pim($reg, $tipo) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as y'),
            )
            ->groupBy('name');
        if ($tipo != 0)
            $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $tipo);
        $query = $query->get();
        return $query;
    }

    public static function suma_certificado($reg, $tipo) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->select(
                'v2.mes as name',
                //DB::raw('round(sum(pres_base_siafweb_detalle.certificado),2) as y'),
                DB::raw('round(sum(pres_base_siafweb_detalle.certificado)) as y'),
            )
            ->groupBy('name');
        if ($tipo != 0)
            $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $tipo);
        $query = $query->get();
        return $query;
    }

    public static function suma_devengado($reg, $tipo) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->select(
                'v2.mes as name',
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado)) as y'),
            )
            ->groupBy('name');
        if ($tipo != 0)
            $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $tipo);
        $query = $query->get();
        return $query;
    }

    public static function suma_xxxx($reg, $tipo) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as y1'),
                DB::raw('round(sum(pres_base_siafweb_detalle.certificado),2) as y2'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as y3'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.certificado)/sum(pres_base_siafweb_detalle.pim),1) as y4'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as y5'),
            )
            ->groupBy('name');
        if ($tipo != 0)
            $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $tipo);
        $query = $query

            ->get();
        return $query;
    }

    public static function rpt1_pim_devengado_acumulado_ejecucion_mensual($reg, $articulo, $categoria, $ue) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($categoria > 0) $query = $query->where('pres_base_siafweb_detalle.categoriagasto_id', $categoria);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        $query = $query->get();
        return $query;
    }

    public static function rpt2_pim_devengado_acumulado_ejecucion_mensual($reg, $articulo, $ue, $tipocategoria, $categoriapresupuestal) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('pres_categoriapresupuestal as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriapresupuestal_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        if ($tipocategoria > 0) $query = $query->where('v3.tipo_categoria_presupuestal', $tipocategoria);
        $query = $query->where('pres_base_siafweb_detalle.categoriapresupuestal_id', $categoriapresupuestal);
        $query = $query->get();
        return $query;
    }

    public static function rpt3_pim_devengado_acumulado_ejecucion_mensual($reg, $articulos, $ue, $codigo, $articulo) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('pres_categoriapresupuestal as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriapresupuestal_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulos > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulos);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        if ($articulo > 0) {
            if ($codigo[0] == '3') $query = $query->where('pres_base_siafweb_detalle.productos_id', $articulo);
            else $query = $query->where('pres_base_siafweb_detalle.proyectos_id', $articulo);
        }
        $query = $query->get();
        return $query;
    }

    public static function rpt4_pim_devengado_acumulado_ejecucion_mensual($reg, $articulo, $ue, $funcion) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            //->join('pres_categoriapresupuestal as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriapresupuestal_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        if ($funcion > 0) $query = $query->where('v5b.id', $funcion);
        $query = $query->get();
        return $query;
    }
    public static function rpt5_pim_devengado_acumulado_ejecucion_mensual($reg, $articulo, $ue, $rubro) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            //->join('pres_categoriapresupuestal as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriapresupuestal_id')
            ->join('pres_rubro as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v6a', 'v6a.id', '=', 'v6.fuentefinanciamiento_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        if ($rubro > 0) $query = $query->where('v6.id', $rubro);
        $query = $query->get();
        return $query;
    }

    public static function rpt5_pim_devengado_acumulado_ejecucion_mensual2($basesiafweb_id, $articulo, $ue) //base detallee
    {
        $query = BaseSiafWebDetalle::where('v2.id', $basesiafweb_id)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('pres_rubro as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v6a', 'v6a.id', '=', 'v6.fuentefinanciamiento_id')
            ->select(
                DB::raw('v6a.nombre as name'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name')->orderBy('pim', 'desc');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        $query = $query->get();
        return $query;
    }

    public static function rpt6_pim_devengado_acumulado_ejecucion_mensual($reg, $articulo, $ue, $generica) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        if ($generica > 0) $query = $query->where('v6d.id', $generica);
        $query = $query->get();
        return $query;
    }

    public static function rpt6_pim_devengado_acumulado_ejecucion_mensual2($basesiafweb_id, $articulo, $ue) //base detallee
    {
        $query = BaseSiafWebDetalle::where('v2.id', $basesiafweb_id)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')
            ->select(
                DB::raw('concat("2.",v6d.codigo," ",v6d.nombre) as name'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name')->orderBy('pim', 'desc');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        $query = $query->get();
        return $query;
    }

    public static function rpt7_pim_devengado_acumulado_ejecucion_mensual($reg, $articulo, $ue, $ff, $generica, $sg, $ed) //base detallee
    {
        $query = BaseSiafWebDetalle::whereIn('pres_base_siafweb_detalle.basesiafweb_id', $reg)
            ->join('pres_base_siafweb as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            //->join('pres_categoriapresupuestal as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriapresupuestal_id')
            ->join('pres_rubro as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v5a', 'v5a.id', '=', 'v5.fuentefinanciamiento_id')

            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')
            ->select(
                'v2.mes as name',
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('round(sum(pres_base_siafweb_detalle.devengado),2) as devengado'),
                DB::raw('round(100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim),1) as ejecucion'),
            )
            ->groupBy('name');
        if ($articulo > 0) $query = $query->where('pres_base_siafweb_detalle.productoproyecto_id', $articulo);
        if ($ue > 0) $query = $query->where('pres_base_siafweb_detalle.unidadejecutora_id', $ue);
        if ($ff > 0) $query = $query->where('v5a.id', $ff);
        if ($generica > 0) $query = $query->where('v6d.id', $generica);
        if ($sg > 0) $query = $query->where('v6c.id', $sg);
        if ($ed > 0) $query = $query->where('v6.id', $ed);
        $query = $query->get();
        return $query;
    }

    public static function listar_unidadejecutora_anio_acticulo_funcion_categoria($anio, $articulo,  $categoria) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $query = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->select(
                'v2.id as id',
                'v2.codigo_ue as codigo',
                'v2.nombre_ejecutora as ue',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0)
            $query = $query->where('v4.id', $articulo);
        if ($categoria != 0)
            $query = $query->where('v3.id', $categoria);
        $query = $query->groupBy('id', 'codigo', 'ue')->get();
        return $query;
    }

    public static function listar_fuentefinanciamiento_anio_acticulo_ue_categoria($anio, $articulo, $ue) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $body = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->join('pres_rubro as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v6a', 'v6a.id', '=', 'v6.fuentefinanciamiento_id')
            ->select(
                'v6a.id',
                'v6a.codigo as cfuente',
                'v6a.nombre as fuente',
                'v6.id as idrubro',
                'v6.codigo as crubro',
                'v6.nombre as rubro',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('100*sum(pres_base_siafweb_detalle.certificado)/sum(pres_base_siafweb_detalle.pim) as eje1'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0) $body = $body->where('v4.id', $articulo);
        if ($ue != 0) $body = $body->where('V2.id', $ue);
        $body = $body->groupBy('id', 'cfuente', 'fuente', 'idrubro', 'crubro', 'rubro')->get();

        $head = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->join('pres_rubro as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v6a', 'v6a.id', '=', 'v6.fuentefinanciamiento_id')
            ->select(
                'v6a.id',
                'v6a.codigo as cfuente',
                'v6a.nombre as fuente',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('100*sum(pres_base_siafweb_detalle.certificado)/sum(pres_base_siafweb_detalle.pim) as eje1'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0) $head = $head->where('v4.id', $articulo);
        if ($ue != 0) $head = $head->where('V2.id', $ue);
        $head = $head->groupBy('id', 'cfuente', 'fuente')->get();

        $foot = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->join('pres_rubro as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v6a', 'v6a.id', '=', 'v6.fuentefinanciamiento_id')
            ->select(
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('100*sum(pres_base_siafweb_detalle.certificado)/sum(pres_base_siafweb_detalle.pim) as eje1'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0) $foot = $foot->where('v4.id', $articulo);
        if ($ue != 0) $foot = $foot->where('V2.id', $ue);
        $foot = $foot->first();
        return ['head' => $head, 'body' => $body, 'foot' => $foot];
    }

    public static function listar_generica_anio_acticulo_ue_categoria($anio, $articulo, $ue) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $body = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')
            ->select(
                'v6d.id',
                'v3.nombre as categoria',
                'v6d.codigo as codigo',
                'v6d.nombre as generica',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0)
            $body = $body->where('v4.id', $articulo);
        if ($ue != 0)
            $body = $body->where('V2.id', $ue);
        $body = $body->groupBy('id', 'categoria', 'codigo', 'generica')->get();

        $head = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')
            ->select(
                'v3.nombre as categoria',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('100*sum(pres_base_siafweb_detalle.certificado)/sum(pres_base_siafweb_detalle.pim) as eje1'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0)
            $head = $head->where('v4.id', $articulo);
        if ($ue != 0)
            $head = $head->where('V2.id', $ue);
        $head = $head->groupBy('categoria')->get();
        return ['body' => $body, 'head' => $head];
    }

    public static function listar_funcion_anio_acticulo_ue_categoria($anio, $articulo, $ue) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $query = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id')
            ->select(
                'v5b.id',
                'v5b.codigo as codigo',
                'v5b.nombre as funcion',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0)
            $query = $query->where('v4.id', $articulo);
        if ($ue != 0)
            $query = $query->where('V2.id', $ue);
        $query = $query->groupBy('id', 'codigo', 'funcion')->get();
        return $query;
    }

    public static function listar_categoria_anio_acticulo_ue_categoria($anio, $articulo, $ue, $tc) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $query = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_categoriapresupuestal as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.categoriapresupuestal_id')
            ->select(
                'v6.id as id',
                'v6.codigo as codigo',
                'v6.categoria_presupuestal as categoria',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0) $query = $query->where('v4.id', $articulo);
        if ($ue != 0) $query = $query->where('v2.id', $ue);
        if ($tc != 0) $query = $query->where('v6.tipo_categoria_presupuestal', $tc);
        //$query = $query->where('v6.tipo_categoria_presupuestal', 'APNOP');
        $query = $query->groupBy('id', 'codigo', 'categoria')->orderBy('codigo', 'asc')->get();
        return $query;
    }

    public static function listar_subgenerica_anio_acticulo_ue_categoria($anio, $articulo, $ue, $ff, $generica, $partidas) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')
            ->first()->id;

        $pr = [];
        if ($partidas != 0) {
            $prx = PartidasRestringidas::where('anio', $anio)->select('especificadetalle_id as ed')->get();
            foreach ($prx as $value) {
                $pr[] = $value->ed;
            }
        }

        $body = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')

            ->join('pres_rubro as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v5a', 'v5a.id', '=', 'v5.fuentefinanciamiento_id')

            /* ->join('pres_grupofuncional as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.grupofuncional_id')
            ->join('pres_divisionfuncional as v5a', 'v5a.id', '=', 'v5.divisionfuncional_id')
            ->join('pres_funcion as v5b', 'v5b.id', '=', 'v5a.funcion_id') */

            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')




            ->select(
                'v6.id',
                DB::raw('concat("2.",v6d.codigo,".",v6c.codigo,".",v6b.codigo,".",v6a.codigo,".",v6.codigo) as codigo'),
                'v6.nombre as especificadetalle',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0)
            $body = $body->where('v4.id', $articulo);
        if ($ue != 0)
            $body = $body->where('v2.id', $ue);
        if ($ff != 0)
            $body = $body->where('v5a.id', $ff);
        if ($generica != 0)
            $body = $body->where('v6d.id', $generica);
        //if ($sg != 0)$body = $body->where('v6c.id', $sg);
        if ($partidas == 1)
            $body = $body->whereIn('v6.id', $pr);
        else if ($partidas == 2)
            $body = $body->whereNotIn('v6.id', $pr);
        $body = $body->groupBy('id', 'codigo', 'especificadetalle')->orderBy('codigo', 'asc')->get();

        return $body;
    }

    public static function listar_productoproyecto_anio_acticulo_ue_categoria($anio, $articulo, $ue, $ff) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $query = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')
            ->join('pres_productos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.productos_id', 'left')
            ->join('pres_proyectos as v7', 'v7.id', '=', 'pres_base_siafweb_detalle.proyectos_id', 'left')
            ->join('pres_rubro as v8', 'v8.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v8a', 'v8a.id', '=', 'v8.fuentefinanciamiento_id')
            ->select(
                DB::raw('if(v4.id=1,v7.id,v6.id) as id'),
                DB::raw('if(v4.id=1,v7.codigo,v6.codigo) as codigo'),
                DB::raw('if(v4.id=1,v7.nombre,v6.nombre) as producto_proyecto'),
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0) $query = $query->where('v4.id', $articulo);
        if ($ue != 0) $query = $query->where('v2.id', $ue);
        if ($ff != 0) $query = $query->where('v8a.id', $ff);
        $query = $query->groupBy('id', 'codigo', 'producto_proyecto')->orderBy('codigo', 'asc')->get();
        return $query;
    }

    public static function listar_subgenerica_anio_ue($anio, $articulo, $ue, $ff, $generica, $sg) //base detallee
    {
        $basesiafweb_id = BaseSiafWeb::select('pres_base_siafweb.*')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_siafweb.importacion_id')
            ->where('pres_base_siafweb.anio', $anio)->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;

        $body = BaseSiafWebDetalle::where('w1.anio', $anio)->where('w2.estado', 'PR')->where('w1.id', $basesiafweb_id)
            ->join('pres_base_siafweb as w1', 'w1.id', '=', 'pres_base_siafweb_detalle.basesiafweb_id')
            ->join('par_importacion as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_base_siafweb_detalle.unidadejecutora_id')
            ->join('pres_categoriagasto as v3', 'v3.id', '=', 'pres_base_siafweb_detalle.categoriagasto_id')
            ->join('pres_producto_proyecto as v4', 'v4.id', '=', 'pres_base_siafweb_detalle.productoproyecto_id')

            ->join('pres_rubro as v5', 'v5.id', '=', 'pres_base_siafweb_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as v5a', 'v5a.id', '=', 'v5.fuentefinanciamiento_id')

            ->join('pres_especificadetalle_gastos as v6', 'v6.id', '=', 'pres_base_siafweb_detalle.especificadetalle_id')
            ->join('pres_especifica_gastos as v6a', 'v6a.id', '=', 'v6.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v6b', 'v6b.id', '=', 'v6a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v6c', 'v6c.id', '=', 'v6b.subgenerica_id')
            ->join('pres_generica_gastos as v6d', 'v6d.id', '=', 'v6c.generica_id')


            ->select(
                'v6.id',
                DB::raw('concat("2.",v6d.codigo,".",v6c.codigo,".",v6b.codigo,".",v6a.codigo,".",v6.codigo) as codigo'),
                'v6.nombre as especificadetalle',
                DB::raw('sum(pres_base_siafweb_detalle.pia) as pia'),
                DB::raw('sum(pres_base_siafweb_detalle.pim) as pim'),
                DB::raw('sum(pres_base_siafweb_detalle.certificado) as cert'),
                DB::raw('sum(pres_base_siafweb_detalle.devengado) as dev'),
                DB::raw('100*sum(pres_base_siafweb_detalle.devengado)/sum(pres_base_siafweb_detalle.pim) as eje'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.certificado) as saldo1'),
                DB::raw('sum(pres_base_siafweb_detalle.pim-pres_base_siafweb_detalle.devengado) as saldo2')
            );
        if ($articulo != 0)
            $body = $body->where('v4.id', $articulo);
        if ($ue != 0)
            $body = $body->where('v2.id', $ue);
        if ($ff != 0)
            $body = $body->where('v5a.id', $ff);
        if ($generica != 0)
            $body = $body->where('v6d.id', $generica);
        if ($sg != 0)
            $body = $body->where('v6c.id', $sg);
        $body = $body->groupBy('id', 'codigo', 'especificadetalle')->get();

        return $body;
    }
}
