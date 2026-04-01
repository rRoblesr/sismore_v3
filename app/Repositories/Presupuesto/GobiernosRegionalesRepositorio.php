<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\BaseActividadesProyectos;
use App\Models\Presupuesto\BaseActividadesProyectosDetalle;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseGastosDetalle;
use App\Models\Presupuesto\BaseProyectosDetalle;
use App\Models\Presupuesto\ImporConsultaAmigable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GobiernosRegionalesRepositorio
{
    public static function anios()
    {
        $anios = ImporConsultaAmigable::select(DB::raw("distinct anio"))->orderBy('anio')->get();
        return $anios;
    }

    public static function meses($ano)
    {
        $nommes = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $mes = ImporConsultaAmigable::select(DB::raw("distinct mes"))->where('anio', $ano)->orderBy('mes')->get();
        foreach ($mes as $key => $value) {
            $value->nombre = $nommes[$value->mes - 1];
        }
        return $mes;
    }

    public static function tipos_gobiernosregionales($ano, $mes, $tipo)
    {
        $imp = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->where('imp.estado', 'PR')
            ->where('ca.anio', $ano)
            ->where('ca.mes', $mes)
            ->where('ca.tipo', $tipo)
            ->orderBy('imp.fechaActualizacion', 'desc')
            ->select('ca.importacion_id')
            ->first();

        if (!$imp) {
            return collect();
        }

        $diaMax = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->where('imp.estado', 'PR')
            ->where('ca.importacion_id', $imp->importacion_id)
            ->where('ca.anio', $ano)
            ->where('ca.mes', $mes)
            ->where('ca.tipo', $tipo)
            ->max('ca.dia');

        $query = DB::table('pres_impor_consulta_amigable as ca')
            ->join('par_importacion as imp', 'imp.id', '=', 'ca.importacion_id')
            ->join('pres_gobiernos_regionales as gr', 'gr.codigo', '=', 'ca.cod_gob_reg')
            ->select(
                DB::raw('gr.gobiernoregional as corto'),
                DB::raw('sum(ca.pia) as pia'),
                DB::raw('sum(ca.pim) as pim'),
                DB::raw('sum(ca.certificacion) as certificacion'),
                DB::raw('sum(ca.compromiso_anual) as compromiso_anual'),
                DB::raw('sum(ca.devengado) as devengado'),
                DB::raw('round(100*sum(ca.devengado)/nullif(sum(ca.pim),0),5) as eje'),
                DB::raw('sum(ca.pim)-sum(ca.certificacion) as saldo1'),
                DB::raw('sum(ca.pim)-sum(ca.devengado) as saldo2')
            )
            ->where('imp.estado', 'PR')
            ->where('ca.importacion_id', $imp->importacion_id)
            ->where('ca.anio', $ano)
            ->where('ca.mes', $mes)
            ->where('ca.tipo', $tipo)
            ->where('ca.dia', $diaMax)
            ->groupBy('gr.gobiernoregional')
            ->orderBy('eje', 'desc')
            ->get();

        return $query;
    }
}
