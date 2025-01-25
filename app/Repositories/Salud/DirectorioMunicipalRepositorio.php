<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Models\Salud\CuboPacto1PadronNominal;
use App\Models\Salud\ImporPadronNominal;
use Illuminate\Support\Facades\DB;

class DirectorioMunicipalRepositorio
{
    public static function listarMunicipalidades()
    {
        $ubigeos = Ubigeo::select('codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('codigo');
        $ubigeo = Ubigeo::select('id', 'codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('id', 'codigo');
        $query = UnidadEjecutora::select('codigo_ue as codigo', DB::raw("TRIM(REPLACE(unidad_ejecutora, '\n', '')) as nombre"))->whereIn('codigo_ue', $ubigeos)->groupBy('codigo', 'nombre')->get();
        foreach ($query as $key => $value) {
            $value->distrito_id = $ubigeo[$value->codigo] ?? null;
        }
        return $query;
    }

    public static function listarMunicipalidadesIdMunicipalidad()
    {
        $ubigeos = Ubigeo::select('codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('codigo');
        $ubigeo = Ubigeo::select('id', 'codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('id', 'codigo');
        $query = UnidadEjecutora::select('codigo_ue as codigo', DB::raw("TRIM(REPLACE(unidad_ejecutora, '\n', '')) as nombre"))->whereIn('codigo_ue', $ubigeos)->groupBy('codigo', 'nombre')->get();
        foreach ($query as $key => $value) {
            $value->distrito_id = $ubigeo[$value->codigo] ?? null;
        }
        return $query->pluck('nombre', 'distrito_id');
    }
}
