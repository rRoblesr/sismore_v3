<?php

namespace App\Repositories\Parametro;

use App\Http\Controllers\Salud\IndicadoresController;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\DataPacto1;
use App\Models\Salud\DataPacto3;
use App\Models\Salud\DataPacto3Denominador;
use App\Models\Salud\ImporPadronActas;
use App\Models\Salud\ImporPadronAnemia;
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

    public static function getSalPacto2GLS($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query =  ImporPadronAnemia::where('anio', $anio)->select(DB::raw('sum(num) as conteo'));
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        return $query = $query->get()->first()->conteo;
    }

    public static function getSalPacto2Mensual($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = ImporPadronAnemia::select(DB::raw('mes as name'), DB::raw('round(100*sum(num)/sum(den),1) as y'))->where('anio', $anio);
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        // if ($mes > 0) $query = $query->where('mes', $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        $query = $query->groupBy('name')->orderBy('name')->get();
        return $query;
    }

    public static function getSalPacto2tabla1($indicador_id, $anio, $mes, $provincia, $distrito)
    {
        $query = IndicadorGeneralMeta::select('par_Indicador_general_meta.*', 'd.codigo', 'd.id as distrito_id', 'd.nombre as distrito')->where('indicadorgeneral', $indicador_id)->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')->get();
        foreach ($query as $key => $value) {
            $queryx = ImporPadronAnemia::select(DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
                ->where('anio', $value->anio)->where('ubigeo', $value->distrito_id);
            if ($mes > 0)
                $queryx = $queryx->where('mes', '<=', $mes);
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

    //###################### educacion pacto 2  #######################
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
            // $query = DataPacto1::where('anio', $value->anio)->where('distrito', $value->distrito)->select(DB::raw('sum(estado) as conteo'));
            // if (IndicadoresController::$pacto1_anio == $anio)
            //     $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
            // $query = $query->get()->first();
            $value->avance = 0; //$query->conteo ? $query->conteo : 0;
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
