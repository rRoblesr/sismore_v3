<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto3PadronMaterno;
use App\Models\Salud\CuboPacto4Padron12Meses;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Support\Facades\DB;

class CuboPacto4Repositorio
{

    public static function actualizado($anio)
    {
        $maxMes = CuboPacto4Padron12Meses::where('anio', $anio)->max('mes');

        if (!$maxMes) {
            return null;
        }
        $query = CuboPacto4Padron12Meses::from('sal_cubo_pacto4_padron_12meses as m')
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
        $query = CuboPacto4Padron12Meses::select(
            DB::raw('sum(numerador) si'),
            DB::raw('sum(denominador)-sum(numerador) no'),
            DB::raw('sum(denominador) conteo'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) indicador')
        )->where('anio', $anio)->where('mes', $mes);

        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->get()->first();
        return $query;
    }

    public static function Tabla01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $distritos = UbigeoRepositorio::arrayDistritoIdNombre();

        $v1 = CuboPacto4Padron12Meses::select(
            'distrito_id',
            DB::raw('sum(numerador) as numerador'),
            DB::raw('sum(denominador) as denominador'),
            DB::raw('100*sum(numerador)/sum(denominador) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        // if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        // if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);

        $v1 = $v1->groupBy('distrito_id')->orderBy('indicador', 'desc')->get();

        foreach ($v1 as $key => $value) {
            $value->distrito = $distritos[$value->distrito_id] ?? '';
        }
        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }

    public static function Tabla02($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $v1 = CuboPacto4Padron12Meses::from('sal_cubo_pacto4_padron_12meses as c4')->select(
            'departamento',
            'provincia',
            'distrito',
            'eess',
            DB::raw('sum(c4.denominador) as denominador'),
            DB::raw('sum(c4.numerador) as numerador'),
            DB::raw('sum(num_cred) as condicion1'),
            DB::raw('sum(num_vac) as condicion2'),
            DB::raw('sum(num_esq) as condicion3'),
            DB::raw('sum(num_hb) as condicion4'),
            DB::raw('sum(num_dniemision) as condicion5'),
            DB::raw('100*sum(c4.numerador)/sum(c4.denominador) as indicador')
        )
            // ->join('par_ubigeo as d', 'd.id', '=', 'c4.distrito_id')
            // ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->where('c4.anio', $anio)->where('c4.mes', '=', $mes);

        // if ($provincia > 0) $query = $v1->where('c4.provincia_id', $provincia);
        // if ($distrito > 0) $query = $v1->where('c4.distrito_id', $distrito);

        $v1 = $v1->groupBy('departamento', 'provincia', 'distrito', 'eess')->orderBy('indicador', 'desc')->get();
        return $v1;
    }

    public static function Anal01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $distritos = UbigeoRepositorio::arrayDistritoIdNombre();
        $query = CuboPacto4Padron12Meses::select(
            'distrito_id',
            DB::raw('100*sum(numerador)/sum(denominador) as indicador'),
            DB::raw('sum(numerador) as nnn'),
            DB::raw('sum(denominador) as ddd')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->groupBy('distrito_id')->orderBy('indicador', 'desc')->get();
        foreach ($query as $key => $value) {
            $value->distrito = $distritos[$value->distrito_id] ?? '';
        }
        return $query;
    }


    public static function Anal02($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto4Padron12Meses::select(
            'mes',
            DB::raw('sum(numerador) as si'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }

    public static function Anal03($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto4Padron12Meses::select(
            'mes',
            DB::raw('sum(numerador) as si'),
            DB::raw('sum(denominador) as no'),
        )->where('anio', $anio)->where('mes', '<=', $mes);

        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }
}
