<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseActividadesProyectos;
use App\Models\Presupuesto\BaseActividadesProyectosDetalle;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseGastosDetalle;
use App\Models\Presupuesto\BaseModificacion;
use App\Models\Presupuesto\BaseModificacionDetalle;
use App\Models\Presupuesto\BaseProyectosDetalle;
use App\Models\Presupuesto\UnidadEjecutora;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ModificacionesRepositorio
{
    public static function anios()
    {
        $anios = BaseModificacion::distinct()->select(DB::raw('year(v2.fechaActualizacion) as anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('v2.estado', 'PR')->orderBy('anio')->get();
        return $anios;
    }

    public static function anioActual()
    {
        $anios = BaseModificacion::distinct()->select(DB::raw('year(v2.fechaActualizacion) as anio'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')->first()->anio;
        return $anios;
    }
    public static function meses($ano)
    {
        $nommes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $mes = BaseModificacion::distinct()->select(DB::raw('pres_base_modificacion.mes'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('pres_base_modificacion.anio', $ano)->where('v2.estado', 'PR')
            ->orderBy('pres_base_modificacion.mes', 'asc')->get();
        foreach ($mes as $key => $value) {
            $value->nombre = $nommes[$value->mes - 1];
        }
        return $mes;
    }

    public static function mesesActual($ano)
    {
        $mes = BaseModificacion::distinct()->select(DB::raw('pres_base_modificacion.mes'))
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('pres_base_modificacion.anio', $ano)->where('v2.estado', 'PR')
            ->orderBy('pres_base_modificacion.mes', 'desc')->first()->mes;
        return $mes;
    }

    public static function UE_poranios($anio)
    {
        if ($anio == 0) {
            $queryJoinPresBaseSiafwebDetalle = '(select DISTINCT unidadejecutora_id from pres_base_modificacion_detalle) as siaf';
        } else {
            $queryJoinPresBaseSiafwebDetalle = "(select DISTINCT unidadejecutora_id from pres_base_modificacion_detalle as bsd inner join pres_base_modificacion as bs on bs.id=bsd.basemodificacion_id where bs.anio=$anio) as siaf";
        }

        $query = UnidadEjecutora::select(
            'pres_unidadejecutora.id',
            'pres_unidadejecutora.unidad_ejecutora as nombre',
            'pres_unidadejecutora.codigo_ue as codigo'
        )
            ->join(DB::raw($queryJoinPresBaseSiafwebDetalle), 'siaf.unidadejecutora_id', '=', 'pres_unidadejecutora.id')
            ->orderBy('codigo')->get();
        return $query;
    }

    public static function productoproyecto($ano, $mes, $articulo, $tipo, $ue, $usb)
    {
        $base = BaseModificacion::select('pres_base_modificacion.id')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('pres_base_modificacion.anio', $ano)->where('pres_base_modificacion.mes', $mes)->where('v2.estado', 'PR')
            ->orderBy('v2.fechaActualizacion', 'desc')->first();
        $detalle = BaseModificacionDetalle::distinct()->select('v1.*')
            ->join('pres_producto_proyecto as v1', 'v1.id', '=', 'pres_base_modificacion_detalle.productoproyecto_id')
            ->where('pres_base_modificacion_detalle.basemodificacion_id', $base->id)
            ->where('pres_base_modificacion_detalle.tipo_presupuesto', 'GASTO');
        //if ($articulo > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.productoproyecto_id', $articulo);
        if ($tipo > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.tipomodificacion_id', $tipo);;
        if ($ue > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.unidadejecutora_id', $ue);
        if ($usb != 'todos') {
            if ($usb != '')
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', '');
            else
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', $usb);
        }
        $detalle = $detalle->get();
        return $detalle;
    }

    public static function unidadejecutora($ano, $mes, $articulo, $tipo, $ue, $usb, $presupuesto)
    {
        $base = BaseModificacion::select('pres_base_modificacion.id')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('pres_base_modificacion.anio', $ano)->where('pres_base_modificacion.mes', $mes)->where('v2.estado', 'PR')
            ->orderBy('v2.fechaActualizacion', 'desc')->first();
        $detalle = BaseModificacionDetalle::distinct()->select('v1.id', 'v1.nombre_ejecutora as nombre', 'v1.codigo_ue as codigo')
            ->join('pres_unidadejecutora as v1', 'v1.id', '=', 'pres_base_modificacion_detalle.unidadejecutora_id')
            ->where('pres_base_modificacion_detalle.basemodificacion_id', $base->id)
            ->where('pres_base_modificacion_detalle.tipo_presupuesto', $presupuesto);
        if ($articulo > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.productoproyecto_id', $articulo);
        if ($tipo > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.tipomodificacion_id', $tipo);;
        //if ($ue > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.unidadejecutora_id', $ue);
        if ($usb != 'todos') {
            if ($usb != '')
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', '');
            else
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', $usb);
        }
        $detalle = $detalle->orderBy('v1.codigo_ue', 'asc')->get();
        return $detalle;
    }

    public static function cargartipos($ano, $mes, $articulo, $tipo, $ue, $usb, $presupuesto)
    {
        $base = BaseModificacion::select('pres_base_modificacion.id')
            ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
            ->where('pres_base_modificacion.anio', $ano)->where('pres_base_modificacion.mes', $mes)->where('v2.estado', 'PR')
            ->orderBy('v2.fechaActualizacion', 'desc')->first();
        $detalle = BaseModificacionDetalle::distinct()->select('v1.*')
            ->join('pres_tipomodificacion as v1', 'v1.id', '=', 'pres_base_modificacion_detalle.tipomodificacion_id')
            ->where('pres_base_modificacion_detalle.basemodificacion_id', $base->id)
            ->where('pres_base_modificacion_detalle.tipo_presupuesto', $presupuesto);
        if ($articulo > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.productoproyecto_id', $articulo);
        //if ($tipo > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.tipomodificacion_id', $tipo);;
        if ($ue > 0) $detalle = $detalle->where('pres_base_modificacion_detalle.unidadejecutora_id', $ue);
        if ($usb != 'todos') {
            if ($usb != '')
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', '');
            else
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', $usb);
        }
        $detalle = $detalle->orderBy('v1.codigo', 'asc')->get();
        return $detalle;
    }

    public static function cargardispositivolegal($ano, $mes, $articulo, $tipo, $ue, $usb)
    {
        $detalle = DB::table(DB::raw("(
                 select bmd.dispositivo_legal from pres_base_modificacion_detalle bmd
                 inner join (
                    select bm.id from pres_base_modificacion bm
                    inner join par_importacion imp on imp.id=bm.importacion_id
                    where imp.estado='PR' and bm.anio=$ano and bm.mes=$mes
                 ) as bm on bm.id=bmd.basemodificacion_id
                 where bmd.tipo_presupuesto='GASTO'
            ) as bmd"))
            ->distinct()
            ->select('bmd.dispositivo_legal');
        if ($articulo > 0) $detalle = $detalle->where('bmd.productoproyecto_id', $articulo);
        if ($tipo > 0) $detalle = $detalle->where('bmd.tipomodificacion_id', $tipo);;
        if ($ue > 0) $detalle = $detalle->where('bmd.unidadejecutora_id', $ue);
        /* if ($usb != 'todos') {
            if ($usb != '')
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', '');
            else
                $detalle = $detalle->where('pres_base_modificacion_detalle.dispositivo_legal', $usb);
        } */
        $detalle = $detalle->orderBy('dispositivo_legal', 'asc')->get();
        return $detalle;
    }

    public static function listar_modificaciones($anio, $mes, $articulo, $tipo, $usb, $ue)
    {
        if ($mes > 0) {
            $basemodificacion_id = BaseModificacion::select('pres_base_modificacion.*')
                ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
                ->where('pres_base_modificacion.anio', $anio)->where('pres_base_modificacion.mes', $mes)->where('v2.estado', 'PR')
                ->orderBy('v2.fechaActualizacion', 'desc')->first()->id;
        }
        $query = BaseModificacionDetalle::select(
            'm4.nombre_ejecutora as unidad_ejecutora',
            'pres_base_modificacion_detalle.fecha_aprobacion',
            'pres_base_modificacion_detalle.documento',
            'pres_base_modificacion_detalle.justificacion',
            'v5.sec_fun as secfun',
            'm1.codigo as catpres',
            'm1.categoria_presupuestal as ncatpres',
            DB::raw('IF(pres_base_modificacion_detalle.productos_id is null,p2.codigo,p1.codigo) as prod_proy'),
            DB::raw('IF(pres_base_modificacion_detalle.productos_id is null,p2.nombre,p1.nombre) as nprod_proy'),
            DB::raw('IF(pres_base_modificacion_detalle.actividad_id is not null,v6.codigo,IF(pres_base_modificacion_detalle.accion_id is not null,v7.codigo,v8.codigo)) as act_acc_obra'),
            DB::raw('IF(pres_base_modificacion_detalle.actividad_id is not null,v6.nombre,IF(pres_base_modificacion_detalle.accion_id is not null,v7.nombre,v8.nombre)) as nact_acc_obra'),
            'm2.codigo as rb',
            'm2.nombre as nrb',
            DB::raw('concat("2.",v4d.codigo,".",v4c.codigo,".",v4b.codigo,".",v4a.codigo,".",v4.codigo) as clasificador'),
            'v4.nombre as especifica_detalle',
            'pres_base_modificacion_detalle.anulacion',
            'pres_base_modificacion_detalle.credito'
        )
            ->join('pres_base_modificacion as w1', 'w1.id', '=', 'pres_base_modificacion_detalle.basemodificacion_id')
            ->join('par_importacion        as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_tipomodificacion as v2', 'v2.id', '=', 'pres_base_modificacion_detalle.tipomodificacion_id')
            //->join('pres_producto_proyecto as v3', 'v3.id', '=', 'pres_base_modificacion_detalle.productoproyecto_id')
            ->join('pres_especificadetalle_gastos  as v4', 'v4.id', '=', 'pres_base_modificacion_detalle.especialidaddetalle_id')
            ->join('pres_especifica_gastos         as v4a', 'v4a.id', '=', 'v4.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v4b', 'v4b.id', '=', 'v4a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos        as v4c', 'v4c.id', '=', 'v4b.subgenerica_id')
            ->join('pres_generica_gastos           as v4d', 'v4d.id', '=', 'v4c.generica_id')
            ->join('pres_meta as v5', 'v5.id', '=', 'pres_base_modificacion_detalle.meta_id')
            ->join('pres_categoriapresupuestal as m1', 'm1.id', '=', 'pres_base_modificacion_detalle.categoriapresupuestal_id')
            ->join('pres_rubro as m2', 'm2.id', '=', 'pres_base_modificacion_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as m3', 'm3.id', '=', 'm2.fuentefinanciamiento_id')
            ->join('pres_unidadejecutora as m4', 'm4.id', '=', 'pres_base_modificacion_detalle.unidadejecutora_id')

            ->join('pres_actividades as v6', 'v6.id', '=', 'pres_base_modificacion_detalle.actividad_id', 'left')
            ->join('pres_accion as v7', 'v7.id', '=', 'pres_base_modificacion_detalle.accion_id', 'left')
            ->join('pres_obra as v8', 'v8.id', '=', 'pres_base_modificacion_detalle.obra_id', 'left')

            ->join('pres_productos as p1', 'p1.id', '=', 'pres_base_modificacion_detalle.productos_id', 'left')
            ->join('pres_proyectos as p2', 'p2.id', '=', 'pres_base_modificacion_detalle.proyectos_id', 'left')
            ->where('w2.estado', 'PR')
            ->where('pres_base_modificacion_detalle.tipo_presupuesto', 'GASTO')
            ->where('w1.anio', $anio);
        if ($mes > 0) $query = $query->where('w1.id', $basemodificacion_id);
        if ($articulo > 0) {
            if ($articulo == 1) $query = $query->where('pres_base_modificacion_detalle.productos_id');
            else $query = $query->where('pres_base_modificacion_detalle.proyectos_id');
        }
        if ($tipo > 0) $query = $query->where('v2.id', $tipo);
        if ($ue > 0) $query = $query->where('m4.id', $ue);
        if ($usb != 'todos') {
            if ($usb == '')
                $query = $query->where('pres_base_modificacion_detalle.dispositivo_legal', "");
            else
                $query = $query->where('pres_base_modificacion_detalle.dispositivo_legal', $usb);
        }
        $query = $query->get();
        return $query;
    }

    public static function listar_modificaciones_foot($opt1, $opt2, $opt3, $opt4, $opt5, $opt6)
    {
        if ($opt2 > 0) {
            $basemodificacion_id = BaseModificacion::select('pres_base_modificacion.*')
                ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
                ->where('pres_base_modificacion.anio', $opt1)->where('v2.estado', 'PR')->where('pres_base_modificacion.mes', $opt2)
                ->orderBy('v2.fechaActualizacion', 'desc')->first()->id;
        }
        $query = BaseModificacionDetalle::select(
            DB::raw('sum(pres_base_modificacion_detalle.anulacion) as anulacion'),
            DB::raw('sum(pres_base_modificacion_detalle.credito) as credito')
        )
            ->join('pres_base_modificacion as w1', 'w1.id', '=', 'pres_base_modificacion_detalle.basemodificacion_id')
            ->join('par_importacion        as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_tipomodificacion as v2', 'v2.id', '=', 'pres_base_modificacion_detalle.tipomodificacion_id')
            ->join('pres_especificadetalle_gastos  as v4', 'v4.id', '=', 'pres_base_modificacion_detalle.especialidaddetalle_id')
            ->join('pres_especifica_gastos         as v4a', 'v4a.id', '=', 'v4.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v4b', 'v4b.id', '=', 'v4a.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos        as v4c', 'v4c.id', '=', 'v4b.subgenerica_id')
            ->join('pres_generica_gastos           as v4d', 'v4d.id', '=', 'v4c.generica_id')
            ->join('pres_meta as v5', 'v5.id', '=', 'pres_base_modificacion_detalle.meta_id')
            ->join('pres_categoriapresupuestal as m1', 'm1.id', '=', 'pres_base_modificacion_detalle.categoriapresupuestal_id')
            ->join('pres_rubro as m2', 'm2.id', '=', 'pres_base_modificacion_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as m3', 'm3.id', '=', 'm2.fuentefinanciamiento_id')
            ->join('pres_unidadejecutora as m4', 'm4.id', '=', 'pres_base_modificacion_detalle.unidadejecutora_id')
            ->join('pres_productos as p1', 'p1.id', '=', 'pres_base_modificacion_detalle.productos_id', 'left')
            ->join('pres_proyectos as p2', 'p2.id', '=', 'pres_base_modificacion_detalle.proyectos_id', 'left')
            ->where('w2.estado', 'PR')
            ->where('pres_base_modificacion_detalle.tipo_presupuesto', 'GASTO')
            ->where('w1.anio', $opt1);
        if ($opt2 > 0) $query = $query->where('w1.id', $basemodificacion_id);
        if ($opt3 > 0) {
            if ($opt3 == 1) $query = $query->where('pres_base_modificacion_detalle.productos_id');
            else $query = $query->where('pres_base_modificacion_detalle.proyectos_id');
        }
        if ($opt4 > 0) $query = $query->where('v2.id', $opt4);
        if ($opt5 != 'todos') {
            if ($opt5 == '')
                $query = $query->where('pres_base_modificacion_detalle.dispositivo_legal', "");
            else
                $query = $query->where('pres_base_modificacion_detalle.dispositivo_legal', $opt5);
        }
        if ($opt6 > 0) $query = $query->where('m4.id', $opt6);
        $query = $query->get();
        if ($query)
            return [
                'anulacion' => number_format($query->first()->anulacion, 0),
                'credito' => number_format($query->first()->credito, 0)
            ];
        else return [
            'anulacion' => 0,
            'credito' => 0
        ];
    }

    public static function listar_modificaciones_ingresos($opt1, $opt2, $opt4, $opt5)
    {
        if ($opt2 != 0) {
            $basemodificacion_id = BaseModificacion::select('pres_base_modificacion.*')
                ->join('par_importacion as v2', 'v2.id', '=', 'pres_base_modificacion.importacion_id')
                ->where('pres_base_modificacion.anio', $opt1)
                ->where('v2.estado', 'PR')
                ->where('pres_base_modificacion.mes', $opt2)
                ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')->first()->id;
        }
        $query = BaseModificacionDetalle::select(
            'm4.nombre_ejecutora as unidad_ejecutora',
            'pres_base_modificacion_detalle.fecha_aprobacion',
            'pres_base_modificacion_detalle.documento',
            'pres_base_modificacion_detalle.justificacion',
            'pres_base_modificacion_detalle.dispositivo_legal',
            'm3.nombre as fuente_financiamiento',
            'pres_base_modificacion_detalle.anulacion',
            'pres_base_modificacion_detalle.credito'
        )
            ->join('pres_base_modificacion as w1', 'w1.id', '=', 'pres_base_modificacion_detalle.basemodificacion_id')
            ->join('par_importacion        as w2', 'w2.id', '=', 'w1.importacion_id')
            ->join('pres_tipomodificacion as v2', 'v2.id', '=', 'pres_base_modificacion_detalle.tipomodificacion_id')
            ->join('pres_rubro as m2', 'm2.id', '=', 'pres_base_modificacion_detalle.rubro_id')
            ->join('pres_fuentefinanciamiento as m3', 'm3.id', '=', 'm2.fuentefinanciamiento_id')
            ->join('pres_unidadejecutora as m4', 'm4.id', '=', 'pres_base_modificacion_detalle.unidadejecutora_id')
            ->where('w2.estado', 'PR')
            ->where('pres_base_modificacion_detalle.tipo_presupuesto', 'INGRESO')
            ->where('w1.anio', $opt1);
        if ($opt2 != 0) $query = $query->where('w1.id', $basemodificacion_id);
        if ($opt4 != 0) $query = $query->where('v2.id', $opt4);
        if ($opt5 != 0) $query = $query->where('m4.id', $opt5);
        $query = $query->get();
        return $query;
    }


    /* public static function tipos_gobiernosregionales($ano, $mes, $tipo)
    {
        if ($tipo == 1) {
            $fechamax = BaseActividadesProyectosDetalle::select(DB::raw("max(v3.fechaActualizacion) as fecha"))
                ->join('pres_base_actividadesproyectos as v2', 'v2.id', '=', 'pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id')
                ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
                ->where('v3.estado', 'PR')->where('v2.anio', $ano)->where('v2.mes', $mes)
                ->first()->fecha;

            $query = BaseActividadesProyectosDetalle::select(
                'pres_base_actividadesproyectos_detalle.id',
                'v4.gobiernoregional as corto',
                'pres_base_actividadesproyectos_detalle.pia',
                'pres_base_actividadesproyectos_detalle.pim',
                'pres_base_actividadesproyectos_detalle.certificacion',
                'pres_base_actividadesproyectos_detalle.compromiso_anual',
                'pres_base_actividadesproyectos_detalle.devengado',
                DB::raw("round(100*pres_base_actividadesproyectos_detalle.devengado/pres_base_actividadesproyectos_detalle.pim,5) as eje"),
                DB::raw('pres_base_actividadesproyectos_detalle.pim-pres_base_actividadesproyectos_detalle.certificacion as saldo1'),
                DB::raw('pres_base_actividadesproyectos_detalle.pim-pres_base_actividadesproyectos_detalle.devengado as saldo2')
            )
                ->join('pres_base_actividadesproyectos as v2', 'v2.id', '=', 'pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id')
                ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
                ->join('pres_gobiernos_regionales as v4', 'v4.id', '=', 'pres_base_actividadesproyectos_detalle.gobiernosregionales_id')
                ->where('v3.estado', 'PR')->where('v3.fechaActualizacion', $fechamax);
            $query = $query->orderBy('eje', 'desc')->get();
            return $query;
        } else if ($tipo == 2) {
            $fechamax = BaseProyectosDetalle::select(DB::raw("max(v3.fechaActualizacion) as fecha"))
                ->join('pres_base_proyectos as v2', 'v2.id', '=', 'pres_base_proyectos_detalle.baseproyectos_id')
                ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
                ->where('v3.estado', 'PR')->where('v2.anio', $ano)->where('v2.mes', $mes)
                ->first()->fecha;

            $query = BaseProyectosDetalle::select(
                'pres_base_proyectos_detalle.id',
                'v4.gobiernoregional as corto',
                'pres_base_proyectos_detalle.pia',
                'pres_base_proyectos_detalle.pim',
                'pres_base_proyectos_detalle.certificacion',
                'pres_base_proyectos_detalle.compromiso_anual',
                'pres_base_proyectos_detalle.devengado',
                DB::raw("round(100*pres_base_proyectos_detalle.devengado/pres_base_proyectos_detalle.pim,5) as eje"),
                DB::raw('pres_base_proyectos_detalle.pim-pres_base_proyectos_detalle.certificacion as saldo1'),
                DB::raw('pres_base_proyectos_detalle.pim-pres_base_proyectos_detalle.devengado as saldo2')
            )
                ->join('pres_base_proyectos as v2', 'v2.id', '=', 'pres_base_proyectos_detalle.baseproyectos_id')
                ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
                ->join('pres_gobiernos_regionales as v4', 'v4.id', '=', 'pres_base_proyectos_detalle.gobiernosregionales_id')
                ->where('v3.estado', 'PR')->where('v3.fechaActualizacion', $fechamax);
            $query = $query->orderBy('eje', 'desc')->get();
            return $query;
        } else {
            $fechamax = BaseActividadesProyectosDetalle::select(DB::raw("max(v3.fechaActualizacion) as fecha"))
                ->join('pres_base_actividadesproyectos as v2', 'v2.id', '=', 'pres_base_actividadesproyectos_detalle.baseactividadesproyectos_id')
                ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
                ->where('v3.estado', 'PR')->where('v2.anio', $ano)->where('v2.mes', $mes)
                ->first()->fecha;
            $query = DB::table(DB::raw("(
                    select
                        tb2.corto as corto,
                        tb2.pia1-tb2.pia2 as pia,
                        tb2.pim1-tb2.pim2 as pim,
                        tb2.certificacion1-tb2.certificacion2 as certificacion,
                        tb2.compromiso_anual1-tb2.compromiso_anual2 as compromiso_anual,
                        tb2.devengado1-tb2.devengado2 as devengado
                    from (
                        select
                            tb1.corto as corto,
                            sum(tb1.pia1) as pia1,sum(tb1.pim1) as pim1,sum(tb1.certificacion1) as certificacion1,sum(tb1.compromiso_anual1) as compromiso_anual1,sum(tb1.devengado1) as devengado1,
                            sum(tb1.pia2) as pia2,sum(tb1.pim2) as pim2,sum(tb1.certificacion2) as certificacion2,sum(tb1.compromiso_anual2) as compromiso_anual2,sum(tb1.devengado2) as devengado2
                        from (
                            select
                                v4.gobiernoregional as corto,
                                v1.pia as pia1,v1.pim as pim1,v1.certificacion as certificacion1,v1.compromiso_anual as compromiso_anual1,v1.devengado as devengado1,
                                     0 as pia2,  0 as pim2,           0 as certificacion2,          0 as compromiso_anual2,    0 as devengado2
                            from pres_base_actividadesproyectos_detalle as v1
                            inner join pres_base_actividadesproyectos as v2 on v2.id = v1.baseactividadesproyectos_id
                            inner join par_importacion as v3 on v3.id = v2.importacion_id
                            inner join pres_gobiernos_regionales as v4 on v4.id = v1.gobiernosregionales_id
                            where v3.estado = 'PR' and v3.fechaActualizacion = '$fechamax'
                            union
                            select
                                v4.gobiernoregional as corto,
                                0 as pia1,  0 as pim1,        0 as certificacion1,   0 as compromiso_anual1,    0 as devengado1,
                                v1.pia as pia2,v1.pim as pim2,v1.certificacion as certificacion2,v1.compromiso_anual as compromiso_anual2,v1.devengado as devengado2
                                from pres_base_proyectos_detalle as v1
                                inner join pres_base_proyectos as v2 on v2.id = v1.baseproyectos_id
                                inner join par_importacion as v3 on v3.id = v2.importacion_id
                                inner join pres_gobiernos_regionales as v4 on v4.id = v1.gobiernosregionales_id
                                where v3.estado = 'PR' and v3.fechaActualizacion = '$fechamax'
                        ) as tb1  group by tb1.corto
                    ) as tb2
            ) as bb"))
                ->select(
                    '*',
                    DB::raw('round(100*devengado/pim,5) as eje'),
                    DB::raw('pim-certificacion as saldo1'),
                    DB::raw('pim-devengado as saldo2')
                )
                ->orderBy('eje', 'desc')->get();
            return $query;
        }
        //return NULL;
    } */
}
