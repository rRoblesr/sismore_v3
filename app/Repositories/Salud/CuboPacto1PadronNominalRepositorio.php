<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Salud\CuboPacto1PadronNominal;
use Illuminate\Support\Facades\DB;

class CuboPacto1PadronNominalRepositorio
{
    public static function pacto01Head($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto1PadronNominal::selectRaw('SUM(den) as gl')
            ->selectRaw('SUM(num) as gls')
            ->selectRaw('SUM(den) - SUM(num) as gln')
            ->selectRaw('ROUND(100 * SUM(num) / SUM(den), 2) as indicador')
            ->where('importacion', $importacion);
        $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->first();
        return $query;
    }

    public static function pacto01Anal01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto1PadronNominal::select('distrito', DB::raw('100*sum(num)/sum(den) as indicador'))->where('importacion', $importacion);
        $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito')->orderBy('indicador', 'desc')->get();
        return $query;
    }

    public static function pacto01Anal02($importacion, $anio, $mes, $provincia, $distrito)
    {
        $xdistrito = '';
        $xprovincia = '';
        if ($provincia > 0) $xprovincia = ' AND sipn.provincia_id = :provincia';
        if ($distrito > 0) $xdistrito = ' AND sipn.distrito_id = :distrito';

        $query = "SELECT
                sub.mes,
                round(100*sum(sipn.num)/sum(sipn.den),1) indicador
            from
                (
                select
                    month(pi.fechaActualizacion) as mes,
                    pi.id as maxImportacionId
                from
                    par_importacion pi
                where
                    pi.estado = 'PR'
                    and year(pi.fechaActualizacion) = :anio1
                    and pi.fuenteImportacion_id = 45
                    and pi.fechaActualizacion = (
                    select
                        MAX(sub.fechaActualizacion)
                    from
                        par_importacion sub
                    where
                        sub.estado = 'PR'
                        and year(sub.fechaActualizacion) = :anio2
                            and sub.fuenteImportacion_id = 45
                            and DATE_FORMAT(sub.fechaActualizacion, '%Y-%m') = DATE_FORMAT(pi.fechaActualizacion, '%Y-%m')
                                        )
                                ) sub
            left join sal_cubo_pacto1_padron_nominal sipn on
                sub.maxImportacionId = sipn.importacion $xprovincia $xdistrito and sipn.tipo_doc in('DNI','CNV')
            group by
                sub.mes
            order by
                sub.mes";

        $params = ['anio1' => $anio, 'anio2' => $anio,];
        if ($provincia > 0) $params['provincia'] = $provincia;
        if ($distrito > 0) $params['distrito'] = $distrito;

        $query = DB::select($query, $params);
        return compact('query');
    }

    public static function pacto01Anal03($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto1PadronNominal::select(
            DB::raw('case when tipo_edad in("D","M") then 1 when tipo_edad="A" AND edad=1 then 2 else edad+1 end as xid'),
            DB::raw('case when tipo_edad in("D","M") then "< 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
            DB::raw('sum(num) as si'),
            DB::raw('sum(den)-sum(num) as no')
        )->where('importacion', $importacion);
        $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('xid', 'edades')->orderBy('xid')->get();
        return $query;
    }

    public static function pacto01Tabla01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto1PadronNominal::select(
            'distrito_id',
            'distrito',
            DB::raw('sum(num) as numerador'),
            DB::raw('sum(den) as denominador'),
            DB::raw('100*sum(num)/sum(den) as indicador')
        )->where('importacion', $importacion);
        $v1 = $v1->whereIn('tipo_doc', ['DNI', 'CNV']);
        $v1 = $v1->groupBy('distrito_id', 'distrito')->orderBy('indicador', 'desc')->get();
        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }
}