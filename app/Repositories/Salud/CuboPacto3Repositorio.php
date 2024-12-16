<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Salud\CuboPacto3PadronMaterno;
use Illuminate\Support\Facades\DB;

class CuboPacto3Repositorio
{

    public static function head($anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            DB::raw('sum(numerador) si'),
            DB::raw('sum(denominador)-sum(numerador) no'),
            DB::raw('sum(denominador) conteo'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->get()->first();
        return $query;
    }

    public static function Tabla01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto3PadronMaterno::select(
            'distrito_id',
            'distrito',
            DB::raw('sum(numerador) as numerador'),
            DB::raw('sum(denominador) as denominador'),
            DB::raw('100*sum(numerador)/sum(denominador) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        $v1 = $v1->groupBy('distrito_id', 'distrito')->orderBy('indicador', 'desc')->get();
        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }

    public static function Anal01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select('distrito', DB::raw('100*sum(numerador)/sum(denominador) as indicador'))->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito')->orderBy('indicador', 'desc')->get();
        return $query;
    }


    public static function Anal02($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            'mes',
            DB::raw('round(100*sum(numerador)/sum(denominador),1) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }

    public static function Anal03($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            'provincia_id as xid',
            'provincia as edades',
            DB::raw('sum(numerador) as si'),
            DB::raw('sum(denominador)-sum(numerador) as no')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('xid', 'edades')->orderBy('xid')->get();
        return $query;
    }
 
}
