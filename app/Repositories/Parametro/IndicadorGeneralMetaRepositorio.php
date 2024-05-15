<?php

namespace App\Repositories\Parametro;

use App\Http\Controllers\Salud\IndicadoresController;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\DataPacto1;
use App\Models\Salud\ImporPadronActas;
use App\Models\Salud\ImporPadronAnemia;
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

    public static function getPacto1tabla1($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $queryx = DataPacto1::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(estado) as conteo'));
            if (IndicadoresController::$pacto1_anio == $anio)
                $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $queryx = $queryx->get()->first();
            $value->avance = $queryx->conteo ? $queryx->conteo : 0;
            $value->porcentaje = number_format(100 * ($value->valor > 0 ? $value->avance / $value->valor : 0), 1);
            if ($anio == date('Y'))
                $value->cumple = $value->valor == $value->avance ? 1 : (intval(date('m')) == $value->avance ? 1 : (intval(date('m')) - 1 == $value->avance  ? 1 : 0));
            // $value->cumple = $value->valor == $value->avance ? 1 : (intval(date('m')) == $value->avance ? 1 : (intval(date('m')) - 1 == $value->avance && date('Y-m-d') < date('Y-m-d', strtotime($anio . '-' . intval(date('m')) . '-07'))  ? 1 : 0));
            else $value->cumple = $value->valor == $value->avance ? 1 : 0;
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

    public static function getPacto2GL($indicador_id, $anio)
    {
        $query2 =  ImporPadronAnemia::where('anio', $anio)->select(DB::raw('sum(den) as conteo'));
        if (IndicadoresController::$pacto1_anio == $anio) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        return $query2 = $query2->get()->first()->conteo;
    }

    public static function getPacto2GLS($indicador_id, $anio)
    {
        $query2 =  ImporPadronAnemia::where('anio', $anio)->select(DB::raw('sum(num) as conteo'));
        if (IndicadoresController::$pacto1_anio == $anio) $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        return $query2 = $query2->get()->first()->conteo;
    }

    public static function getPacto2Mensual($anio, $distrito)
    { //DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), 
        $query = ImporPadronAnemia::select(DB::raw('mes as name'), DB::raw('round(100*sum(num)/sum(den),1) as y'))->where('anio', $anio);
        // ->join('par_importacion as imp', 'imp.id', '=', 'sal_impor_padron_anemia.importacion_id');

        if ($distrito > 0) {
            // $dd = Ubigeo::find($distrito);
            // $query = $query->where('distrito',  $dd->nombre);
            $query = $query->where('ubigeo',  $distrito);
        }

        if (IndicadoresController::$pacto1_anio == $anio)
            $query = $query->where(DB::raw('month(sal_impor_padron_anemia.mes)'), '>=', IndicadoresController::$pacto1_mes);

        $query = $query->groupBy('name')->orderBy('name')->get();
        return $query;
    }

    public static function getPacto2tabla1($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $queryx = ImporPadronAnemia::select(DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
                ->where('anio', $value->anio)->where('ubigeo', $value->distrito_id);
            if (IndicadoresController::$pacto1_anio == $anio)
                $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
            $queryx = $queryx->get()->first();

            $value->num = $queryx->num;
            $value->den = $queryx->den;
            $value->ind = $queryx->ind;
            $value->cumple = floatval($value->ind) >= floatval($value->valor) ? 1 : 0;
        }
        return $query;
    }

    public static function getPacto2tabla2($indicador_id, $anio)
    {
        // $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
        //     ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        // foreach ($query as $key => $value) {
        //     $queryx = ImporPadronAnemia::select(DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
        //         ->where('anio', $value->anio)->where('ubigeo', $value->distrito_id);
        //     if (IndicadoresController::$pacto1_anio == $anio)
        //         $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $queryx = $queryx->get()->first();

        //     $value->num = $queryx->num;
        //     $value->den = $queryx->den;
        //     $value->ind = $queryx->ind;
        //     $value->cumple = floatval($value->ind) >= floatval($value->valor) ? 1 : 0;
        // } sal_impor_padron_anemia
        $queryx = ImporPadronAnemia::select('pp.nombre as name', DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
            ->where('anio', $anio);
        // $queryx = $queryx->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        // $queryx = $queryx->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        $queryx = $queryx->join('par_establecimiento as es', 'es.cod_unico', '=', 'cod_unico');
        $queryx = $queryx->join('par_microrred as mr', 'mr.id', '=', 'es.microrred_id');
        $queryx = $queryx->join('par_red as rr', 'rr.id', '=', 'mr.red_id');
        if (IndicadoresController::$pacto1_anio == $anio)
            $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
        $queryx = $queryx->groupBy('name')->get();

        return $queryx;
    }

    public static function getPacto2tabla3($indicador_id, $anio)
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

    public static function getEduPacto2anal1($anio, $ugel, $provincia, $distrito, $estado)
    {
        $query = DB::select('call edu_pa_sfl_porlocal_provincia(?,?,?,?)', [$ugel, $provincia, $distrito, $estado]);
        return $query;
    }

    public static function getEduPacto2tabla1($indicador_id, $anio)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            // $queryx = DataPacto1::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(estado) as conteo'));
            // if (IndicadoresController::$pacto1_anio == $anio)
            //     $queryx = $queryx->where('mes', '>=', IndicadoresController::$pacto1_mes);
            // $queryx = $queryx->get()->first();
            $value->avance = 0; //$queryx->conteo ? $queryx->conteo : 0;
            $value->porcentaje = 0; //number_format(100 * ($value->valor > 0 ? $value->avance / $value->valor : 0), 1);
            $value->cumple = $value->valor == $value->avance ? 1 : 0;
        }
        return $query;
    }

    public static function getEduPacto2tabla2($anio, $ugel, $provincia, $distrito, $estado)
    {
        $query = DB::select('call edu_pa_sfl_porlocal_distrito(?,?,?,?)', [$ugel, $provincia, $distrito, $estado]);
        return $query;
    }

    public static function getEduPacto2tabla3($indicador_id, $anio)
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

        // foreach ($query as $key => $value) {
        //     $anioxx = 2023;
        //     $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
        //     if (IndicadoresController::$pacto1_anio == $anioxx)
        //         $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $query2 = $query2->groupBy('distrito')->get();
        //     $value->r2023 = $query2->count() > 0 ? $query2->first()->conteo : 0;
        //     if ($anioxx == $anio) {
        //         $value->avance = number_format(100 * ($value->v2023 > 0 ? $value->r2023 / $value->v2023 : 0), 0);
        //         $value->cumple = $value->r2023 == $value->v2023 ? 1 : 0;
        //     }
        // }

        // foreach ($query as $key => $value) {
        //     $anioxx = 2024;
        //     $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
        //     if (IndicadoresController::$pacto1_anio == $anioxx)
        //         $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $query2 = $query2->groupBy('distrito')->get();
        //     $value->r2024 = $query2->count() > 0 ? $query2->first()->conteo : 0;
        //     if ($anioxx == $anio) {
        //         $value->avance = number_format(100 * ($value->v2024 > 0 ? $value->r2024 / $value->v2024 : 0), 0);
        //         $value->cumple = $value->r2024 == $value->v2024 ? 1 : 0;
        //     }
        // }
        // foreach ($query as $key => $value) {
        //     $anioxx = 2025;
        //     $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
        //     if (IndicadoresController::$pacto1_anio == $anioxx)
        //         $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $query2 = $query2->groupBy('distrito')->get();
        //     $value->r2025 = $query2->count() > 0 ? $query2->first()->conteo : 0;
        //     if ($anioxx == $anio) {
        //         $value->avance = number_format(100 * ($value->v2025 > 0 ? $value->r2025 / $value->v2025 : 0), 0);
        //         $value->cumple = $value->r2025 == $value->v2025 ? 1 : 0;
        //     }
        // }
        // foreach ($query as $key => $value) {
        //     $anioxx = 2026;
        //     $query2 =  DataPacto1::where('anio', $anioxx)->select(DB::raw("sum(estado) as conteo"))->where('distrito', $value->dis);
        //     if (IndicadoresController::$pacto1_anio == $anioxx)
        //         $query2 = $query2->where('mes', '>=', IndicadoresController::$pacto1_mes);
        //     $query2 = $query2->groupBy('distrito')->get();
        //     $value->r2026 = $query2->count() > 0 ? $query2->first()->conteo : 0;
        //     if ($anioxx == $anio) {
        //         $value->avance = number_format(100 * ($value->v2026 > 0 ? $value->r2026 / $value->v2026 : 0), 0);
        //         $value->cumple = $value->r2026 == $value->v2026 ? 1 : 0;
        //     }
        // }
        return $query;
    }
}
