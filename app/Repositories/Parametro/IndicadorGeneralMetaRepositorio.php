<?php

namespace App\Repositories\Parametro;

use App\Http\Controllers\Salud\ImporReportePN05Controller;
use App\Http\Controllers\Salud\IndicadoresController;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\DataPacto1;
use App\Models\Salud\DataPacto3;
use App\Models\Salud\DataPacto3Denominador;
use App\Models\Salud\ImporPadronActas;
use App\Models\Salud\ImporPadronAnemia;
use App\Models\Salud\ImporReportePN05;
use App\Repositories\Educacion\CuboPacto1Repositorio;
use App\Repositories\Educacion\CuboPacto2Repositorio;
use Illuminate\Support\Facades\DB;

class IndicadorGeneralMetaRepositorio
{
    public static function getPacto1Anios($indicador_id)
    {
        return IndicadorGeneralMeta::distinct()->select('anio')->where('indicadorgeneral', $indicador_id)->orderBy('anio')->get();
    }

    //###################### salud pacto 1  #######################
    public static function getPacto1GL($indicador_id, $anio)
    {
        return IndicadorGeneralMeta::where('indicadorgeneral', $indicador_id)->where('anio', $anio)->get()->count();
    }

    public static function getPacto1GLS($indicador_id, $anio)
    {
        $query1 = IndicadorGeneralMeta::distinct()->select('valor')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)->get();
        $base = $query1->count() > 0 ? ($query1->first()->valor ? $query1->first()->valor : 0) : 0;
        // $value->cumple = $value->r2024 == $value->v2024  ? 1 : (intval(date('m')) == $value->r2024 ? 1 : (intval(date('m')) - 1 == $value->r2024  ? 1 : 0));
        // if ($anio == date('Y'))
        //     $mm = date('Y-m-d') < date('Y-m-d', strtotime($anio . '-' . (intval(date('m')) + 1) . '-08')) ? date('m') - 1 : date('m');
        // else
        $mm = $base;

        $query2 =  DataPacto1::where('anio', $anio)->select(DB::raw("IF(sum(estado)=$mm,1,0) as conteo"), DB::raw("sum(estado) as conteo2"));
        if (IndicadoresController::$pacto1_anio == $anio) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        $query2 = $query2->groupBy('distrito')->orderBy('distrito')->get();

        return $query2->count() > 0 ? $query2->sum('conteo') : 0;
    }

    public static function getPacto1tabla1($indicador_id, $anio, $mes)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $queryx = DataPacto1::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(estado) as conteo'));
            if (IndicadoresController::$pacto1_anio == $anio) {
                $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
            }
            if ($mes > 0) {
                $queryx = $queryx->where('mes', '<=', $mes);
            }
            $queryx = $queryx->get()->first();

            $value->avance = $queryx->conteo ? $queryx->conteo : 0;
            $value->porcentaje = number_format(100 * ($value->valor > 0 ? $value->avance / $value->valor : 0), 1);
            if ($anio == date('Y')) {
                if ($mes == 0) {
                    $smes = intval(date('m'));
                    $value->cumple = $value->valor == $value->avance ? 1 : ($smes == $value->avance ? 1 : ($smes - 1 == $value->avance  ? 1 : 0));
                } else {
                    $smes = $mes;
                    $value->cumple = $value->valor == $value->avance ? 1 : ($smes == $value->avance ? 1 : 0);
                }
                // $smes = $mes == 0 ? intval(date('m')) : $mes;
                // $value->cumple = $value->valor == $value->avance ? 1 : ($smes == $value->avance ? 1 : ($smes - 1 == $value->avance  ? 1 : 0));

                // $value->cumple = $value->valor == $value->avance ? 1 : (intval(date('m')) == $value->avance ? 1 : (intval(date('m')) - 1 == $value->avance  ? 1 : 0));
                // $value->cumple = $value->valor == $value->avance ? 1 : (intval(date('m')) == $value->avance ? 1 : (intval(date('m')) - 1 == $value->avance && date('Y-m-d') < date('Y-m-d', strtotime($anio . '-' . intval(date('m')) . '-07'))  ? 1 : 0));
            } else {
                $value->cumple = $value->valor == $value->avance ? 1 : 0;
            }
        }
        return $query;
    }

    public static function getPacto1tabla2($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select(
            'd.nombre as dis',
            'anio_base',
            'valor_base',
            DB::raw('max(if(anio=2023,valor,0)) as v2023'),
            DB::raw('max(if(anio=2024,valor,0)) as v2024'),
            DB::raw('max(if(anio=2025,valor,0)) as v2025'),
            DB::raw('max(if(anio=2026,valor,0)) as v2026'),
        )->where('indicadorgeneral', $indicador_id)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_indicador_general_meta.distrito')->groupBy('dis', 'anio_base', 'valor_base')->get();

        foreach ($query as $key => $value) {
            $anioxx = 2023;
            $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2023 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2023 > 0 ? $value->r2023 / $value->v2023 : 0), 0);
                $value->cumple = $value->r2023 == $value->v2023 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2024;
            $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2024 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2024 > 0 ? $value->r2024 / $value->v2024 : 0), 0);
                // $value->cumple = $value->r2024 == $value->v2024 ? 1 : 0;
                $value->cumple = $value->r2024 == $value->v2024  ? 1 : (intval(date('m')) == $value->r2024 ? 1 : (intval(date('m')) - 1 == $value->r2024  ? 1 : 0));
            }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2025;
            $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2025 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2025 > 0 ? $value->r2025 / $value->v2025 : 0), 0);
                $value->cumple = $value->r2025 == $value->v2025 ? 1 : 0;
            }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2026;
            $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2026 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2026 > 0 ? $value->r2026 / $value->v2026 : 0), 0);
                $value->cumple = $value->r2026 == $value->v2026 ? 1 : 0;
            }
        }



        return $query;
    }

    public static function getPacto1Mensual($anio, $distrito)
    {
        $query = ImporPadronActas::select(DB::raw('month(sal_impor_padron_actas.fecha_envio) as name'), DB::raw('sum(numero_archivos) as y'))
            ->join('par_importacion as imp', 'imp.id', '=', 'sal_impor_padron_actas.importacion_id')
            // ->where(DB::raw('year(fechaActualizacion)'), $anio)
            ->where('sal_impor_padron_actas.fecha_envio', '<', ($anio + 1) . '-01-08')->where(DB::raw('year(fecha_envio)'), $anio);

        if ($distrito > 0) {
            $dd = Ubigeo::find($distrito);
            $query = $query->where('distrito',  $dd->nombre);
        }

        if (IndicadoresController::$pacto1_anio == $anio)
            $query = $query->where(DB::raw('month(sal_impor_padron_actas.fecha_envio)'), '>=', IndicadoresController::$pacto1_mes);

        $query = $query->groupBy('name')->orderBy('name')->get();
        // ->where('distrito', $distrito)where('anio', $anio)->
        return $query;
    }

    public static function getPacto1Mensual2($anio, $distrito)
    {
        $query =  DataPacto1::where('anio', $anio)->select('mes', DB::raw('sum(estado) as y'));
        if ($distrito > 0) {
            $dd = Ubigeo::find($distrito);
            $query = $query->where('distrito',  $dd->nombre);
        }
        if (IndicadoresController::$pacto1_anio == $anio)
            $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }

    public static function getPacto2Anios($indicador_id)
    {
        return IndicadorGeneralMeta::distinct()->select('anio')->where('indicadorgeneral', $indicador_id)->orderBy('anio')->get();
    }
    //###################### salud pacto 2  #######################
    public static function getSalPacto2GL($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query =  ImporPadronAnemia::where('anio', $anio)->select(DB::raw('sum(den) as conteo'));
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first()->conteo;
    }

    // public static function getSalPacto2GLS($indicador_id, $anio, $mes, $provincia, $distrito)
    // {
    //     $query =  ImporPadronAnemia::where('anio', $anio)->select(DB::raw('sum(num) as conteo'));
    //     $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
    //     $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
    //     if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
    //     if ($mes > 0) $query = $query->where('mes', '<=',  $mes);
    //     if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
    //     if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
    //     return $query = $query->get()->first()->conteo;
    // }


    public static function getSalPacto2GLS2($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query =  ImporPadronAnemia::where('anio', $anio)->select(DB::raw('sum(num) as si,sum(den) as conteo,sum(den)-sum(num) as no,round(100*sum(num)/sum(den),1) as indicador'));
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes', '<=',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first();
    }

    public static function getSalPacto2anal1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        // $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
        //     ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        // foreach ($query as $key => $value) {
        //     $queryx = ImporPadronAnemia::select(DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
        //         ->where('anio', $value->anio)->where('ubigeo', $value->distrito_id);
        //     if ($mes > 0)
        //         $queryx = $queryx->where('mes', '<=', $mes);
        //     if (IndicadoresController::$pacto1_anio == $anio)
        //         $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $queryx = $queryx->get()->first();

        //     $value->num = $queryx->num;
        //     $value->den = $queryx->den;
        //     $value->ind = $queryx->ind;
        //     $value->cumple = floatval($value->ind) >= floatval($value->valor) ? 1 : 0;
        // }
        $dis_ = Ubigeo::whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->get()->pluck('nombre', 'id');
        $query = ImporPadronAnemia::select(
            'ubigeo',
            DB::raw('round(100*sum(num)/sum(den),1) as indicador'),
        )->where('anio', $anio)->where('mes', '<=', $mes);

        // if ($provincia > 0) $query = $query->where('provincia', $pro_[$provincia] ?? "");
        // if ($distrito > 0) $query = $query->where('distrito', $dis_[$distrito] ?? "");

        $query = $query->groupBy('ubigeo')->orderBy('indicador', 'desc')->get();

        foreach ($query as $key => $value) {
            $value->distrito = $dis_[$value->ubigeo] ?? "";
        }
        return $query;
    }

    public static function getSalPacto2Mensual($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = ImporPadronAnemia::select(DB::raw('mes as name'), DB::raw('round(100*sum(num)/sum(den),1) as y'))->where('anio', $anio);
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes', '<=', $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        $query = $query->groupBy('name')->orderBy('name')->get();
        return $query;
    }

    public static function getSalPacto2tabla1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        // $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
        //     ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        // foreach ($query as $key => $value) {
        //     $queryx = ImporPadronAnemia::select(DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
        //         ->where('anio', $value->anio)->where('ubigeo', $value->distrito_id);
        //     if ($mes > 0)
        //         $queryx = $queryx->where('mes', '<=', $mes);
        //     if (IndicadoresController::$pacto1_anio == $anio)
        //         $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $queryx = $queryx->get()->first();

        //     $value->num = $queryx->num;
        //     $value->den = $queryx->den;
        //     $value->ind = $queryx->ind;
        //     $value->cumple = floatval($value->ind) >= floatval($value->valor) ? 1 : 0;
        // }
        // return $query;
        // $dis_ = Ubigeo::whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->get()->pluck('nombre', 'id');
        $v1 = ImporPadronAnemia::select(
            'ubigeo as distrito_id',
            DB::raw('sum(num) as num'),
            DB::raw('sum(den) as den'),
            DB::raw('100*sum(num)/sum(den) as ind')
        )->where('anio', $anio)->where('mes', '<=', $mes);


        $v1 = $v1->groupBy('distrito_id')->orderBy('ind', 'desc')->get();
        $v2 = Ubigeo::whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->get()->pluck('nombre', 'id');
        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador_id)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            $value->distrito = $v2[$value->distrito_id] ?? "";
            $value->valor = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->ind >= $value->valor ? 1 : 0;
        }
        return $v1;
    }

    public static function getSalPacto2tabla2($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = ImporPadronAnemia::select('rr.id as idred', 'rr.nombre as red', DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
            ->where('anio', $anio);
        $query = $query->join('sal_establecimiento as es', 'es.cod_unico', '=', 'sal_impor_padron_anemia.cod_unico');
        $query = $query->join('sal_microred as mr', 'mr.id', '=', 'es.microrred_id');
        $query = $query->join('sal_red as rr', 'rr.id', '=', 'mr.red_id');
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes', '<=',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        $query = $query->groupBy('idred', 'red')->get();

        return $query;
    }

    public static function getSalPacto2tabla2tabla1($indicador_id, $anio, $mes, $provincia, $distrito, $red = 0)
    {
        $query = ImporPadronAnemia::select('mr.id as idmicro', 'mr.nombre as micro', DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
            ->where('anio', $anio);
        $query = $query->join('sal_establecimiento as es', 'es.cod_unico', '=', 'sal_impor_padron_anemia.cod_unico');
        $query = $query->join('sal_microred as mr', 'mr.id', '=', 'es.microrred_id');
        $query = $query->join('sal_red as rr', 'rr.id', '=', 'mr.red_id');
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if ($red > 0) $query = $query->where('rr.id',  $red);
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        $query = $query->groupBy('idmicro', 'micro')->get();

        return $query;
    }

    public static function getSalPacto2tabla3($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = ImporPadronAnemia::select(
            'es.cod_unico as unico',
            'es.nombre_establecimiento as eess',
            'rr.nombre as red',
            'mr.nombre as micro',
            'pp.nombre as pro',
            'dd.nombre as dis',
            DB::raw('sum(den) as den'),
            DB::raw('sum(num) as num'),
            DB::raw('round(100*sum(num)/sum(den),1) as ind')
        );
        $query = $query->where('anio', $anio);
        $query = $query->join('sal_establecimiento as es', 'es.cod_unico', '=', 'sal_impor_padron_anemia.cod_unico');
        $query = $query->join('sal_microred as mr', 'mr.id', '=', 'es.microrred_id');
        $query = $query->join('sal_red as rr', 'rr.id', '=', 'mr.red_id');
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'es.ubigeo_id');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);

        $query = $query->groupBy('unico', 'eess', 'red', 'micro', 'pro', 'dis')->get();

        return $query;
    }

    public static function getSalPacto2tabla4($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = IndicadorGeneralMeta::select(
            'd.id as dis_id',
            'd.nombre as dis',
            'anio_base',
            'valor_base',
            DB::raw('max(if(anio=2023,valor,0)) as v2023'),
            DB::raw('max(if(anio=2024,valor,0)) as v2024'),
            DB::raw('max(if(anio=2025,valor,0)) as v2025'),
            DB::raw('max(if(anio=2026,valor,0)) as v2026'),
        )->where('indicadorgeneral', $indicador_id)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_indicador_general_meta.distrito')->groupBy('dis_id', 'dis', 'anio_base', 'valor_base')->get();

        foreach ($query as $key => $value) {
            $anioxx = 2023;
            $query2 =  ImporPadronAnemia::where('anio', $anioxx)->select(DB::raw("round(100*sum(num)/sum(den),1) as conteo"))->where('ubigeo', $value->dis_id);
            if (IndicadoresController::$pacto1_anio == $anioxx) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('ubigeo')->get();
            $value->r2023 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = $value->r2023;
                $value->cumple = $value->r2023 >= $value->v2023  ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2024;
            $query2 =  ImporPadronAnemia::where('anio', $anioxx)->select(DB::raw("round(100*sum(num)/sum(den),1) as conteo"))->where('ubigeo', $value->dis_id);
            if (IndicadoresController::$pacto1_anio == $anioxx) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            if ($mes > 0) $query2 = $query2->where('mes', '<=',  $mes);
            $query2 = $query2->groupBy('ubigeo')->get();
            $value->r2024 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = $value->r2024;
                $value->cumple = $value->r2024 >= $value->v2024  ? 1 : 0;
            }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2025;
            $query2 =  ImporPadronAnemia::where('anio', $anioxx)->select(DB::raw("round(100*sum(num)/sum(den),1) as conteo"))->where('ubigeo', $value->dis_id);
            if (IndicadoresController::$pacto1_anio == $anioxx) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('ubigeo')->get();
            $value->r2025 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = $value->r2025;
                $value->cumple = $value->r2025 >= $value->v2025 ? 1 : 0;
            }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2026;
            $query2 =  ImporPadronAnemia::where('anio', $anioxx)->select(DB::raw("round(100*sum(num)/sum(den),1) as conteo"))->where('ubigeo', $value->dis_id);
            if (IndicadoresController::$pacto1_anio == $anioxx) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('ubigeo')->get();
            $value->r2026 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = $value->r2026;
                $value->cumple = $value->r2026 >= $value->v2026  ? 1 : 0;
            }
        }

        return $query;
    }

    //###################### salud pacto 3  #######################
    public static function getSalPacto3GL($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $query =  DataPacto3Denominador::where('anio', $anio)->select(DB::raw('sum(meta) as conteo'));
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        // if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        // if ($mes > 0) $query = $query->where('mes',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo_id',  $distrito);
        // if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first()->conteo;
    }

    public static function getSalPacto3GLS($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $query =  DataPacto3::where('anio', $anio)->select(DB::raw('sum(cantidad) as conteo'));
        // $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
        // $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes', '<=',  $mes);
        if ($distrito > 0) $query = $query->where('distrito',  $dst->nombre);
        // if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first()->conteo;
    }

    public static function getPacto3Mensual($anio, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $query = DataPacto3::select(DB::raw('mes as name'), DB::raw('sum(cantidad) as y'))->where('anio', $anio);
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($distrito > 0) $query = $query->where('distrito',  $dst->nombre);
        $query = $query->groupBy('name')->orderBy('name')->get();
        return $query;
    }

    public static function getPacto3Mensual2($anio, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $nquery = DataPacto3::select(DB::raw('mes as name'), DB::raw('sum(cantidad) as avance'))->where('anio', $anio);
        if (IndicadoresController::$pacto1_anio == $anio) $nquery = $nquery->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($distrito > 0) $nquery = $nquery->where('distrito',  $dst->nombre);
        $nquery = $nquery->groupBy('name')->orderBy('name')->get();

        $dquery = DataPacto3Denominador::where('anio', $anio)->select(DB::raw('sum(meta) as meta'));
        if ($distrito > 0) $dquery = $dquery->where('distrito',  $dst->nombre);
        $dquery = $dquery->get()->first();

        foreach ($nquery as $key => $value) {
            $value->y = round(100 * $value->avance / $dquery->meta, 1);
        }
        return $nquery;
    }

    public static function getSalPacto3tabla1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            // #################### numerador
            $nquery = DataPacto3::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(cantidad) as conteo'));
            if (IndicadoresController::$pacto1_anio == $anio) {
                $nquery = $nquery->where('mes', '>=', IndicadoresController::$pacto1_mes);
            }
            if ($mes > 0) {
                $nquery = $nquery->where('mes', '<=', $mes);
            }
            $nquery = $nquery->get()->first();
            // #################### denomidador
            $dquery = DataPacto3Denominador::where('anio', $value->anio)->where('distrito', $value->distrito)->select('meta');
            $dquery = $dquery->get()->first();

            $value->avance = $nquery->conteo > 0 ? $nquery->conteo : 0;
            $value->meta = $dquery->meta;
            $value->ind = $value->meta > 0 ? 100 * $value->avance / $value->meta : 0;
            $value->cumple = floatval($value->ind) >= floatval($value->valor) ? 1 : 0;
        }
        return $query;
    }

    public static function getSalPacto3tabla2($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select(
            'd.nombre as dis',
            'anio_base',
            'valor_base',
            DB::raw('max(if(anio=2023,valor,0)) as v2023'),
            DB::raw('max(if(anio=2024,valor,0)) as v2024'),
            DB::raw('max(if(anio=2025,valor,0)) as v2025'),
            DB::raw('max(if(anio=2026,valor,0)) as v2026'),
        )->where('indicadorgeneral', $indicador_id)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_indicador_general_meta.distrito')->groupBy('dis', 'anio_base', 'valor_base')->get();

        foreach ($query as $key => $value) {
            $anioxx = 2023;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();

            $num = $query2->count() > 0 ? $query2->first()->conteo : 0;

            $yquery =  DataPacto3Denominador::where('anio', $anioxx)->select(DB::raw('sum(meta) as conteo'));
            $yquery = $yquery->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
            $yquery = $yquery->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
            // if ($value->dis > 0)
            $yquery = $yquery->where('dd.nombre',  $value->dis);
            $yquery = $yquery->get()->first()->conteo;

            $den = $yquery ? $yquery : 0;

            $value->r2023 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = $den > 0 ? 100 * $num / $den : 0;
                $value->cumple = $value->avance >= $value->v2023 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2024;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();

            $num = $query2->count() > 0 ? $query2->first()->conteo : 0;

            $yquery =  DataPacto3Denominador::where('anio', $anioxx)->select(DB::raw('sum(meta) as conteo'));
            $yquery = $yquery->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
            $yquery = $yquery->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
            // if ($value->dis > 0)
            $yquery = $yquery->where('dd.nombre',  $value->dis);
            $yquery = $yquery->get()->first()->conteo;

            $den = $yquery ? $yquery : 0;

            $value->r2024 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = $den > 0 ? 100 * $num / $den : 0;
                $value->cumple = $value->avance >= $value->v2023 ? 1 : 0;
            }
            // $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            // if (IndicadoresController::$pacto1_anio == $anioxx)
            //     $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            // $query2 = $query2->groupBy('distrito')->get();
            // $value->r2024 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            // if ($anioxx == $anio) {
            //     $value->avance = number_format(100 * ($value->v2024 > 0 ? $value->r2024 / $value->v2024 : 0), 0);
            //     // $value->cumple = $value->r2024 == $value->v2024 ? 1 : 0;
            //     $value->cumple = $value->r2024 == $value->v2024  ? 1 : (intval(date('m')) == $value->r2024 ? 1 : (intval(date('m')) - 1 == $value->r2024  ? 1 : 0));
            // }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2025;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2025 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2025 > 0 ? $value->r2025 / $value->v2025 : 0), 0);
                $value->cumple = $value->r2025 == $value->v2025 ? 1 : 0;
            }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2026;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2026 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2026 > 0 ? $value->r2026 / $value->v2026 : 0), 0);
                $value->cumple = $value->r2026 == $value->v2026 ? 1 : 0;
            }
        }



        return $query;
    }

    //###################### salud pacto 4  #######################
    public static function getSalPacto4GL($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        return $imp = Importacion::where(DB::raw('month(fechaActualizacion)'), 6)->where('estado', 'PR')->where('fuenteimportacion_id', ImporReportePN05Controller::$FUENTE)
            ->get()->first();
        $ninos = ImporReportePN05::where('importacion_id', 6546)->get();
        $dst = Ubigeo::find($distrito);
        $query =  DataPacto3Denominador::where('anio', $anio)->select(DB::raw('sum(meta) as conteo'));
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        // if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        // if ($mes > 0) $query = $query->where('mes',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo_id',  $distrito);
        // if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first()->conteo;
    }

    public static function getSalPacto4GLS($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $query =  DataPacto3::where('anio', $anio)->select(DB::raw('sum(cantidad) as conteo'));
        // $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
        // $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes', '<=',  $mes);
        if ($distrito > 0) $query = $query->where('distrito',  $dst->nombre);
        // if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first()->conteo;
    }

    public static function getPacto4Mensual($anio, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $query = DataPacto3::select(DB::raw('mes as name'), DB::raw('sum(cantidad) as y'))->where('anio', $anio);
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($distrito > 0) $query = $query->where('distrito',  $dst->nombre);
        $query = $query->groupBy('name')->orderBy('name')->get();
        return $query;
    }

    public static function getPacto4Mensual2($anio, $distrito)
    {
        $dst = Ubigeo::find($distrito);
        $nquery = DataPacto3::select(DB::raw('mes as name'), DB::raw('sum(cantidad) as avance'))->where('anio', $anio);
        if (IndicadoresController::$pacto1_anio == $anio) $nquery = $nquery->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($distrito > 0) $nquery = $nquery->where('distrito',  $dst->nombre);
        $nquery = $nquery->groupBy('name')->orderBy('name')->get();

        $dquery = DataPacto3Denominador::where('anio', $anio)->select(DB::raw('sum(meta) as meta'));
        if ($distrito > 0) $dquery = $dquery->where('distrito',  $dst->nombre);
        $dquery = $dquery->get()->first();

        foreach ($nquery as $key => $value) {
            $value->y = round(100 * $value->avance / $dquery->meta, 1);
        }
        return $nquery;
    }

    public static function getSalPacto4tabla1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            // #################### numerador
            $nquery = DataPacto3::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(cantidad) as conteo'));
            if (IndicadoresController::$pacto1_anio == $anio) {
                $nquery = $nquery->where('mes', '>=', IndicadoresController::$pacto1_mes);
            }
            if ($mes > 0) {
                $nquery = $nquery->where('mes', '<=', $mes);
            }
            $nquery = $nquery->get()->first();
            // #################### denomidador
            $dquery = DataPacto3Denominador::where('anio', $value->anio)->where('distrito', $value->distrito)->select('meta');
            $dquery = $dquery->get()->first();

            $value->avance = $nquery->conteo > 0 ? $nquery->conteo : 0;
            $value->meta = $dquery->meta;
            $value->ind = $value->meta > 0 ? 100 * $value->avance / $value->meta : 0;
            $value->cumple = floatval($value->ind) >= floatval($value->valor) ? 1 : 0;
        }
        return $query;
    }

    public static function getSalPacto4tabla2($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select(
            'd.nombre as dis',
            'anio_base',
            'valor_base',
            DB::raw('max(if(anio=2023,valor,0)) as v2023'),
            DB::raw('max(if(anio=2024,valor,0)) as v2024'),
            DB::raw('max(if(anio=2025,valor,0)) as v2025'),
            DB::raw('max(if(anio=2026,valor,0)) as v2026'),
        )->where('indicadorgeneral', $indicador_id)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_indicador_general_meta.distrito')->groupBy('dis', 'anio_base', 'valor_base')->get();

        foreach ($query as $key => $value) {
            $anioxx = 2023;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();

            $num = $query2->count() > 0 ? $query2->first()->conteo : 0;

            $yquery =  DataPacto3Denominador::where('anio', $anioxx)->select(DB::raw('sum(meta) as conteo'));
            $yquery = $yquery->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
            $yquery = $yquery->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
            // if ($value->dis > 0)
            $yquery = $yquery->where('dd.nombre',  $value->dis);
            $yquery = $yquery->get()->first()->conteo;

            $den = $yquery ? $yquery : 0;

            $value->r2023 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = $den > 0 ? 100 * $num / $den : 0;
                $value->cumple = $value->avance >= $value->v2023 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2024;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();

            $num = $query2->count() > 0 ? $query2->first()->conteo : 0;

            $yquery =  DataPacto3Denominador::where('anio', $anioxx)->select(DB::raw('sum(meta) as conteo'));
            $yquery = $yquery->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo_id');
            $yquery = $yquery->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
            // if ($value->dis > 0)
            $yquery = $yquery->where('dd.nombre',  $value->dis);
            $yquery = $yquery->get()->first()->conteo;

            $den = $yquery ? $yquery : 0;

            $value->r2024 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = $den > 0 ? 100 * $num / $den : 0;
                $value->cumple = $value->avance >= $value->v2023 ? 1 : 0;
            }
            // $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            // if (IndicadoresController::$pacto1_anio == $anioxx)
            //     $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            // $query2 = $query2->groupBy('distrito')->get();
            // $value->r2024 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            // if ($anioxx == $anio) {
            //     $value->avance = number_format(100 * ($value->v2024 > 0 ? $value->r2024 / $value->v2024 : 0), 0);
            //     // $value->cumple = $value->r2024 == $value->v2024 ? 1 : 0;
            //     $value->cumple = $value->r2024 == $value->v2024  ? 1 : (intval(date('m')) == $value->r2024 ? 1 : (intval(date('m')) - 1 == $value->r2024  ? 1 : 0));
            // }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2025;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2025 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2025 > 0 ? $value->r2025 / $value->v2025 : 0), 0);
                $value->cumple = $value->r2025 == $value->v2025 ? 1 : 0;
            }
        }
        foreach ($query as $key => $value) {
            $anioxx = 2026;
            $query2 =  DataPacto3::where('anio', $anioxx)->select(DB::raw("sum(cantidad) as conteo"))->where('distrito', $value->dis);
            if (IndicadoresController::$pacto1_anio == $anioxx)
                $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $query2 = $query2->groupBy('distrito')->get();
            $value->r2026 = $query2->count() > 0 ? $query2->first()->conteo : 0;
            if ($anioxx == $anio) {
                $value->avance = number_format(100 * ($value->v2026 > 0 ? $value->r2026 / $value->v2026 : 0), 0);
                $value->cumple = $value->r2026 == $value->v2026 ? 1 : 0;
            }
        }



        return $query;
    }
    //###################### educacion pacto 1  #######################

    public static function getEduPacto1anal1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $est = CuboPacto1Repositorio::pacto1_matriculados_mensual($anio, 0, 0, $distrito);
        $ppn = PoblacionPNRepositorio::conteo3a5_mensual($anio, 0, 0, $distrito, 0);
        $query = Mes::select('id', 'abreviado as mes')->get();
        foreach ($query as $key => $value) {
            $value->est = null;
            $value->pob = null;
            $value->ind = null;
            $acumulado = 0;
            foreach ($est as $ee) {
                $acumulado += $ee->conteo;
                if ($ee->mes_id == $value->id) $value->est = $acumulado;
            }
            foreach ($ppn as $pp) {
                if ($pp->mes_id == $value->id) $value->pob = $pp->conteo;
            }
            $value->ind = $value->est == null && $key > 0 ? null : round(100 * ($value->pob > 0 ? $value->est / $value->pob : 0), 1);
        }
        return $query;
    }

    public static function getEduPacto1tabla1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anio, $mes, 0, $value->distrito_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anio, $mes, 0, $value->distrito_id);
            $value->den = $poblacion ? $poblacion : 0;
            $value->num = $cubo ? $cubo->first()->conteo : 0;
            $value->porcentaje = round(100 * ($value->den > 0 ? $value->num / $value->den : 0), 1);
            $value->cumple = $value->porcentaje >= $value->valor ? 1 : 0;
        }
        return $query;
    }

    public static function getEduPacto1tabla2($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = DB::table('edu_cubo_pacto01_matriculados')->select(
            'nivelmodalidad',
            DB::raw('sum(total) as conteo'),
            DB::raw('sum(if(sexo_id=1,total,0)) as hconteo'),
            DB::raw('sum(if(sexo_id=2,total,0)) as mconteo'),
            DB::raw('sum(if(edad=3,total,0)) as conteo3'),
            DB::raw('sum(if(edad=3 and sexo_id=1,total,0)) as hconteo3'),
            DB::raw('sum(if(edad=3 and sexo_id=2,total,0)) as mconteo3'),
            DB::raw('sum(if(edad=4,total,0)) as conteo4'),
            DB::raw('sum(if(edad=4 and sexo_id=1,total,0)) as hconteo4'),
            DB::raw('sum(if(edad=4 and sexo_id=2,total,0)) as mconteo4'),
            DB::raw('sum(if(edad=5,total,0)) as conteo5'),
            DB::raw('sum(if(edad=5 and sexo_id=1,total,0)) as hconteo5'),
            DB::raw('sum(if(edad=5 and sexo_id=2,total,0)) as mconteo5')
        )->where('anio', $anio)->whereIn('nivelmodalidad_codigo', ['A2', 'A3', 'A5']);
        if ($mes > 0) $query = $query->where('mes_id', '<=',  $mes);
        if ($provincia > 0) $query = $query->where('provincia_id',  $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id',  $distrito);
        $query = $query->groupBy('nivelmodalidad')->orderBy('nivelmodalidad_codigo', 'asc')->get();

        return $query;
    }

    public static function getEduPacto1tabla3($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = IndicadorGeneralMeta::from('par_indicador_general_meta as igm')->select(
            'ds.id as dis_id',
            'ds.nombre as dis',
            'anio_base',
            'valor_base',
            DB::raw('max(if(anio=2023,valor,0)) as v2023'),
            DB::raw('max(if(anio=2024,valor,0)) as v2024'),
            DB::raw('max(if(anio=2025,valor,0)) as v2025'),
            DB::raw('max(if(anio=2026,valor,0)) as v2026'),
        )->where('indicadorgeneral', $indicador_id)
            ->join('par_ubigeo as ds', 'ds.id', '=', 'igm.distrito')->groupBy('dis_id', 'dis', 'anio_base', 'valor_base')->get();


        foreach ($query as $key => $value) {
            $anioxx = 2023;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2023 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2023, 1);
                $value->cumple = $value->r2023 >= $value->v2023 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2024;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2024 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2024, 1);
                $value->cumple = $value->r2024 >= $value->v2024 ? 1 : 0;
                // $value->cumple = $value->r2024 == $value->v2024  ? 1 : (intval(date('m')) == $value->r2024 ? 1 : (intval(date('m')) - 1 == $value->r2024  ? 1 : 0));
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2025;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2025 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2025, 1);
                $value->cumple = $value->r2025 >= $value->v2025 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2026;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2026 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2026, 1);
                $value->cumple = $value->r2026 >= $value->v2026 ? 1 : 0;
            }
        }

        return $query;
    }


    //###################### educacion pacto 2  #######################
    public static function getEduPacto2anal1($anio, $mes, $provincia, $distrito, $estado)
    {
        // $query = DB::select('call edu_pa_sfl_porlocal_provincia(?,?,?,?)', [$ugel, $provincia, $distrito, $estado]);
        // return $query;

        // $npro = Ubigeo::where(DB::raw('length(codigo)'), 4)->where('nombre', $provincia)->first();
        // $ndis = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('nombre', $distrito)->first();
        //
        return CuboPacto2Repositorio::getEduPacto2anal1($anio, $mes, $provincia, $distrito, $estado);
    }

    public static function getEduPacto2tabla1($indicador_id, $anio, $mes, $provincia, $distrito, $estado)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $data = CuboPacto2Repositorio::getEduPacto2tabla1($anio, $mes, 0, $value->distrito_id, 0);
            $value->avance = $data->count() > 0 ? ($data->first()->conteo ? $data->first()->conteo : 0) : 0; //$query->conteo ? $query->conteo : 0;
            $value->porcentaje = number_format(100 * ($value->valor > 0 ? $value->avance / $value->valor : 0), 1);
            $value->cumple = $value->avance >= $value->valor ? 1 : 0;
        }
        return $query;
    }

    public static function getEduPacto2tabla2($anio, $ugel, $provincia, $distrito, $estado)
    {
        // $npro = Ubigeo::where(DB::raw('length(codigo)'), 4)->where('nombre', $provincia)->first();
        // $ndis = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('nombre', $distrito)->first();
        // $query = DB::select('call edu_pa_sfl_porlocal_distrito(?,?,?,?)', [$ugel, $provincia, $distrito, $estado]);
        $query = DB::table('edu_cubo_pacto02_local')->select(
            'distrito',
            DB::raw('count(local) as conteo'),
            DB::raw('sum(IF(estado=1,1,0)) as si'),
            DB::raw('sum(IF(estado=2,1,0)) as no'),
            DB::raw('sum(IF(estado=3,1,0)) as pro'),
            DB::raw('sum(IF(estado=4,1,0)) as sin'),
        );
        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        if ($estado > 0) $query = $query->where('estado', $distrito);

        $query = $query->groupBy('distrito')->get();
        return $query;
    }

    public static function getEduPacto2tabla3($indicador_id, $anio, $mes, $provincia, $distrito, $estado)
    {
        $query = IndicadorGeneralMeta::select(
            'd.id as dis_id',
            'd.nombre as dis',
            'anio_base',
            'valor_base',
            DB::raw('max(if(anio=2023,valor,0)) as v2023'),
            DB::raw('max(if(anio=2024,valor,0)) as v2024'),
            DB::raw('max(if(anio=2025,valor,0)) as v2025'),
            DB::raw('max(if(anio=2026,valor,0)) as v2026'),
        )->where('indicadorgeneral', $indicador_id)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_indicador_general_meta.distrito')->groupBy('dis_id', 'dis', 'anio_base', 'valor_base')->get();


        foreach ($query as $key => $value) {
            $anioxx = 2023;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2023 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2023, 1);
                $value->cumple = $value->r2023 >= $value->v2023 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2024;
            // $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            // $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $cubo1 = CuboPacto2Repositorio::getEduPacto2tabla1x($anioxx, 0, 0, $value->dis_id, 0);
            $cubo = CuboPacto2Repositorio::getEduPacto2tabla1($anioxx, $mes, 0, $value->dis_id, 0);
            $den = 1; //$cubo1->first() ? $cubo1->first()->conteo : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2024 = round($den > 0 ? $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2024, 1);
                $value->cumple = $value->r2024 >= $value->v2024 ? 1 : 0;
                // $value->cumple = $value->r2024 == $value->v2024  ? 1 : (intval(date('m')) == $value->r2024 ? 1 : (intval(date('m')) - 1 == $value->r2024  ? 1 : 0));
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2025;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2025 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2025, 1);
                $value->cumple = $value->r2025 >= $value->v2025 ? 1 : 0;
            }
        }

        foreach ($query as $key => $value) {
            $anioxx = 2026;
            $poblacion = PoblacionPNRepositorio::conteo3a5_acumulado($anioxx, $mes, 0, $value->dis_id, 0);
            $cubo = CuboPacto1Repositorio::pacto1_matriculados_mes_a($anioxx, $mes, 0, $value->dis_id);
            $den = $poblacion ? $poblacion : 0;
            $num = $cubo->first() ? $cubo->first()->conteo : 0;
            $value->r2026 = round($den > 0 ? 100 * $num / $den : 0, 1);
            if ($anioxx == $anio) {
                $value->avance = number_format($value->r2026, 1);
                $value->cumple = $value->r2026 >= $value->v2026 ? 1 : 0;
            }
        }
        return $query;
    }

    //PDRC EDUCACION

    // public static function getpdrcAnios($indicador_id)
    // {
    //     return IndicadorGeneralMeta::distinct()->select('anio')->where('indicadorgeneral', $indicador_id)->orderBy('anio')->get();
    // }
}
