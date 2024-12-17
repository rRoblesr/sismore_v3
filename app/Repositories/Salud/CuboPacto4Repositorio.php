<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto3PadronMaterno;
use App\Models\Salud\CuboPacto4Padron12Meses;
use Illuminate\Support\Facades\DB;

class CuboPacto4Repositorio
{

    public static function head($anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto4Padron12Meses::select(
            DB::raw('sum(num) si'),
            DB::raw('sum(den)-sum(num) no'),
            DB::raw('sum(den) conteo'),
            DB::raw('round(100*sum(num)/sum(den),1) indicador')
        )->where('anio', $anio)->where('mes', $mes);
        $query = $query->where('codigo_disa', 34)->whereIn('codigo_red', [0, 1, 2, 3, 4]);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->get()->first();
        return $query;
    }

    public static function Tabla01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto4Padron12Meses::select(
            // 'distrito_id',
            'distrito',
            DB::raw('sum(num) as numerador'),
            DB::raw('sum(den) as denominador'),
            DB::raw('100*sum(num)/sum(den) as indicador')
        )->where('anio', $anio)->where('mes', '=', $mes);

        $v1 = $v1->where('codigo_disa', 34)->whereIn('codigo_red', [0, 1, 2, 3, 4]);

        $v1 = $v1->groupBy('distrito')->orderBy('indicador', 'desc')->get();
        $v2 = Ubigeo::whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->get()->pluck('id', 'nombre');
        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            if ($value->distrito == 'ALEXANDER VON HUMBO') $distrito_id = $v2['ALEXANDER VON HUMBOLDT'] ?? 0;
            else $distrito_id = $v2[$value->distrito] ?? 0;
            $value->meta = $v3[$distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }

    public static function Tabla02($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto4Padron12Meses::select(
            'red',
            'microred',
            'eess as eess_parto',
            DB::raw('sum(den) as denominador'),
            DB::raw('sum(num) as numerador'),
            DB::raw('sum(cumple_cred) as condicion1'),
            DB::raw('sum(cumple_vacuna) as condicion2'),
            DB::raw('sum(cumple_suplemento) as condicion3'),
            DB::raw('100*sum(num)/sum(den) as indicador')
        )->where('anio', $anio)->where('mes', '=', $mes);

        $v1 = $v1->where('codigo_disa', 34)->whereIn('codigo_red', [0, 1, 2, 3, 4]);

        $v1 = $v1->groupBy('red', 'microred', 'eess_parto',)->orderBy('indicador', 'desc')->get();
        return $v1;
    }

    public static function Anal01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto4Padron12Meses::select(
            'distrito',
            DB::raw('100*sum(num)/sum(den) as indicador'),
            DB::raw('sum(num) as nnn'),
            DB::raw('sum(den) as ddd')
        )->where('anio', $anio)->where('mes', '=', $mes);

        $query = $query->where('codigo_disa', 34)->whereIn('codigo_red', [0, 1, 2, 3, 4]);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito')->orderBy('indicador', 'desc')->get();
        return $query;
    }


    public static function Anal02($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto4Padron12Meses::select(
            'mes',
            DB::raw('sum(num) as si'),
            DB::raw('round(100*sum(num)/sum(den),1) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        $query = $query->where('codigo_disa', 34)->whereIn('codigo_red', [0, 1, 2, 3, 4]);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }
 
    public static function Anal03($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto4Padron12Meses::select(
            'mes',
            DB::raw('sum(num) as si'),
            DB::raw('sum(den) as no'),
        )->where('anio', $anio)->where('mes', '<=', $mes);

        $query = $query->where('codigo_disa', 34)->whereIn('codigo_red', [0, 1, 2, 3, 4]);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }
}
