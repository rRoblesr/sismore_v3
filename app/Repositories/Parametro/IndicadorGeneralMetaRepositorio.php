<?php

namespace App\Repositories\Parametro;

use App\Http\Controllers\Salud\IndicadoresController;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Salud\DataPacto1;
use App\Models\Salud\ImporPadronActas;
use Illuminate\Support\Facades\DB;

class IndicadorGeneralMetaRepositorio
{
    public static function getPacto1Anios($indicador_id)
    {
        return IndicadorGeneralMeta::distinct()->select('anio')->where('indicadorgeneral', $indicador_id)->orderBy('anio')->get();
    }

    public static function getPacto1GL($indicador_id, $anio)
    {
        return IndicadorGeneralMeta::where('indicadorgeneral', $indicador_id)->where('anio', $anio)->get()->count();
    }

    public static function getPacto1GLS($indicador_id, $anio)
    {
        $query1 = IndicadorGeneralMeta::distinct()->select('valor')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)->get()->first();
        $base = $query1->valor ? $query1->valor : 0;
        $query2 =  DataPacto1::where('anio', $anio)->select(DB::raw("IF(sum(estado)=$base,1,0) as conteo"));
        if (IndicadoresController::$pacto1_anio == $anio)
            $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        $query2 = $query2->groupBy('mes')->orderBy('mes')->get();

        $conteo = 0;
        foreach ($query2 as $key => $value) {
            $conteo += $value->conteo;
        }

        return $conteo;
    }

    public static function getPacto1GLN($indicador_id, $anio)
    {
        $query1 = IndicadorGeneralMeta::distinct()->select('valor')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)->get()->first();
        $base = $query1->valor ? $query1->valor : 0;
        $query2 =  DataPacto1::where('anio', $anio)->select(DB::raw("IF(sum(estado)=$base,0,1) as conteo"));
        if (IndicadoresController::$pacto1_anio == $anio)
            $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        $query2 = $query2->groupBy('mes')->orderBy('mes')->get();

        $conteo = 0;
        foreach ($query2 as $key => $value) {
            $conteo += $value->conteo;
        }

        return 0;
    }

    public static function getPacto1tabla1($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $queryx = DataPacto1::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(estado) as conteo'))->get()->first();
            $value->avance = $queryx->conteo ? $queryx->conteo : 0;
            $value->porcentaje = number_format(100 * ($value->valor > 0 ? $value->avance / $value->valor : 0), 1);
            $value->cumple = $value->valor == $value->avance ? 1 : 0;
        }
        return $query;
    }

    public static function getPacto1Mensual($anio, $distrito)
    {
        $query = ImporPadronActas::select(DB::raw('month(sal_impor_padron_actas.fecha_envio) as name'), DB::raw('sum(numero_archivos) as y'))
            ->join('par_importacion as imp', 'imp.id', '=', 'sal_impor_padron_actas.importacion_id')
            ->where(DB::raw('year(fechaActualizacion)'), $anio);
        if (IndicadoresController::$pacto1_anio == $anio)
            $query = $query->where(DB::raw('month(sal_impor_padron_actas.fecha_envio)'), '>=', IndicadoresController::$pacto1_mes);
        $query = $query->groupBy('name')->orderBy('name')->get();
        // ->where('distrito', $distrito)where('anio', $anio)->
        return $query;
    }

    public static function getPacto1Mensual2($anio, $distrito)
    {
        $query =  DataPacto1::where('anio', $anio)->select('mes', DB::raw('sum(estado) as y'));
        if (IndicadoresController::$pacto1_anio == $anio)
            $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }
}
