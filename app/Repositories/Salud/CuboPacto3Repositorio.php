<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto3PadronMaterno;
use Illuminate\Support\Facades\DB;

class CuboPacto3Repositorio
{

    public static function actualizado($anio)
    {
        $maxMes = CuboPacto3PadronMaterno::where('anio', $anio)->max('mes');

        if (!$maxMes) {
            return null;
        }
        $query = CuboPacto3PadronMaterno::from('sal_cubo_pacto3_padron_materno as m')
            ->join('par_mes as p', 'p.id', '=', 'm.mes')
            ->where('m.anio', $anio)
            ->where('m.mes', $maxMes)
            ->selectRaw('m.mes, CONCAT(p.mes, " ", m.anio) AS fecha')
            ->first();

        if (!$query) {
            return null; // O devolver un mensaje de error
        }

        return $query;
    }

    public static function head($anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            DB::raw('sum(numerador) si'),
            DB::raw('sum(denominador)-sum(numerador) no'),
            DB::raw('sum(denominador) conteo'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->get()->first();
        return $query;
    }

    public static function Tabla01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto3PadronMaterno::select(
            'distrito_id',
            DB::raw('sum(numerador) as numerador'),
            DB::raw('sum(denominador) as denominador'),
            DB::raw('100*sum(numerador)/sum(denominador) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        $v1 = $v1->groupBy('distrito_id');

        $v1 = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($v1, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.id as distrito_id',
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.numerador, 0) as numerador'),
                DB::raw('COALESCE(anemia.denominador, 0) as denominador'),
                DB::raw('COALESCE(anemia.indicador, 0) as indicador')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')->orderBy('indicador', 'desc')->get();


        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }

    public static function Tabla02($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto3PadronMaterno::select(
            'red',
            'microred',
            'codigo_unico',
            'eess_parto',
            DB::raw('sum(denominador) as denominador'),
            DB::raw('sum(numerador) as numerador'),
            DB::raw('sum(num_exam_aux) as condicion1'),
            DB::raw('sum(num_apn) as condicion2'),
            DB::raw('sum(num_entrega_sfaf) as condicion3'),
            DB::raw('100*sum(numerador)/sum(denominador) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        $v1 = $v1->groupBy('red', 'microred', 'codigo_unico', 'eess_parto',)->orderBy('indicador', 'desc')->get();
        return $v1;
    }

    public static function Anal01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select('distrito_id', DB::raw('100*sum(numerador)/sum(denominador) as indicador'))->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito_id');

        $query = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($query, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.indicador, 0) as indicador')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')->orderBy('indicador', 'desc')->get();
        return $query;
    }


    public static function Anal02($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            'mes',
            DB::raw('sum(numerador) as si'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }

    public static function Anal03_($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            // 'provincia_id as xid',
            'red as edades',
            DB::raw('sum(numerador) as si'),
            DB::raw('sum(denominador)-sum(numerador) as no')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('edades')->get();
        return $query;
    }

    public static function Anal03($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            'mes',
            DB::raw('sum(numerador) as si'),
            DB::raw('sum(denominador) as no'),
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }
}
