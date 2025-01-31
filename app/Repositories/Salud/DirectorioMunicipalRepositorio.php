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
    public static function listarMunicipalidades($distrito = 0)
    {
        $ubigeos = Ubigeo::select('codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('codigo');
        $ubigeo = Ubigeo::select('id', 'codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('id', 'codigo');
        $query = UnidadEjecutora::select('codigo_ue as codigo', DB::raw("TRIM(REPLACE(unidad_ejecutora, '\n', '')) as nombre"))->whereIn('codigo_ue', $ubigeos)->groupBy('codigo', 'nombre')->get();
        foreach ($query as $key => $value) {
            $value->distrito_id = $ubigeo[$value->codigo] ?? null;
            if ($value->distrito_id == 45) {
                $value->nombre = 'UNICIPALIDAD PROVINCIAL DE ATALAYA';
            }
            if ($value->distrito_id == 49) {
                $value->nombre = 'MUNICIPALIDAD PROVINCIAL DE PADRE ABAD';
            }
        }
        //if ($distrito > 0) return $query->where('distrito_id', $distrito);
        return $query;
    }

    public static function listarMunicipalidadesxx($distrito = 0)
    {
        $ubigeos = Ubigeo::select('codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('codigo');
        $ubigeo = Ubigeo::select('id', 'codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('id', 'codigo');

        $query = UnidadEjecutora::select('codigo_ue as codigo', DB::raw("TRIM(REPLACE(unidad_ejecutora, '\n', '')) as nombre"))
            ->whereIn('codigo_ue', $ubigeos)
            ->groupBy('codigo', 'nombre');

        // Si se pasa un valor para el distrito, aplica el filtro
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }

        $query = $query->get(); // Ejecuta la consulta

        // Modificar los resultados después de obtenerlos
        foreach ($query as $key => $value) {
            $value->distrito_id = $ubigeo[$value->codigo] ?? null;
            if ($value->distrito_id == 45) {
                $value->nombre = 'UNICIPALIDAD PROVINCIAL DE ATALAYA';
            }
            if ($value->distrito_id == 49) {
                $value->nombre = 'MUNICIPALIDAD PROVINCIAL DE PADRE ABAD';
            }
        }

        return $query;
    }

    //miasena 2c bicargonato 1c aceite de coco 1 c

    public static function listarMunicipalidadesIdMunicipalidad()
    {
        $ubigeos = Ubigeo::select('codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('codigo');
        $ubigeo = Ubigeo::select('id', 'codigo')->whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('id', 'codigo');
        $query = UnidadEjecutora::select('codigo_ue as codigo', DB::raw("TRIM(REPLACE(unidad_ejecutora, '\n', '')) as nombre"))->whereIn('codigo_ue', $ubigeos)->groupBy('codigo', 'nombre')->get();
        foreach ($query as $key => $value) {
            $value->distrito_id = $ubigeo[$value->codigo] ?? null;
            if ($value->distrito_id == 45) {
                $value->nombre = 'UNICIPALIDAD PROVINCIAL DE ATALAYA';
            }
            if ($value->distrito_id == 49) {
                $value->nombre = 'MUNICIPALIDAD PROVINCIAL DE PADRE ABAD';
            }
        }
        return $query->pluck('nombre', 'distrito_id');
    }
}
