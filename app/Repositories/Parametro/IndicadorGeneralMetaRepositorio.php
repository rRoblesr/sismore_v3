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
use Carbon\Carbon;
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
        // $dis_ = Ubigeo::whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->get()->pluck('nombre', 'id');
        $query = ImporPadronAnemia::select(
            'ubigeo as distrito_id',
            DB::raw('round(100*sum(num)/sum(den),1) as indicador'),
        )->where('anio', $anio)->where('mes', '<=', $mes);

        // if ($provincia > 0) $query = $query->where('provincia', $pro_[$provincia] ?? "");
        // if ($distrito > 0) $query = $query->where('distrito', $dis_[$distrito] ?? "");

        $ipa = $query->groupBy('distrito_id');

        $query = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($ipa, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.indicador, 0) as indicador')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')
            ->orderBy('indicador', 'desc')->get();


        // foreach ($query as $key => $value) {
        //     $value->distrito = $dis_[$value->ubigeo] ?? "";
        // }
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
        $basal = [
            53 => 0,
            57 => 33,
            37 => 31,
            42 => 67,
            50 => 33,
            56 => 0,
            41 => 40,
            51 => 100,
            38 => 47,
            40 => 25,
            52 => 75,
            39 => 0,
            49 => 0,
            55 => 0,
            45 => 40,
            46 => 100,
            47 => 0,
            36 => 27,
            44 => 0,
        ];
        // $v1 = ImporPadronAnemia::select(
        //     'ubigeo as distrito_id',
        //     DB::raw('sum(num) as num'),
        //     DB::raw('sum(den) as den'),
        //     DB::raw('100*sum(num)/sum(den) as ind')
        // )
        //     ->where('anio', $anio)->where('mes', '<=', $mes);
        // $v1 = $v1->groupBy('distrito_id')->orderBy('ind', 'desc')->get();
        // $vx = Ubigeo::from('par_ubigeo as u')->join($v1,'sal_impor_padron_anemia.ubugeo','=','u.codigo')->select('u.nombre','num','dem','ind')->whereRaw('length(u.codigo) = 6')->where('u.codigo', 'like', '25%')->get();

        $vx = ImporPadronAnemia::select(
            'ubigeo as distrito_id',
            DB::raw('SUM(num) as num'),
            DB::raw('SUM(den) as den'),
            DB::raw('CASE WHEN SUM(den) > 0 THEN 100 * SUM(num) / SUM(den) ELSE 0 END as ind')
        )
            ->where('anio', $anio)->where('mes', '<=', $mes)->groupBy('ubigeo');

        $v1 = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($vx, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.id as distrito_id',
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.num, 0) as num'),
                DB::raw('COALESCE(anemia.den, 0) as den'),
                DB::raw('COALESCE(anemia.ind, 0) as ind')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')
            ->orderBy('ind', 'desc')->get();

        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador_id)->where('anio', $anio)->pluck('valor', 'distrito');
        foreach ($v1 as $key => $value) {
            $value->basal = $basal[$value->distrito_id] ?? 0;
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
        $query = $query->join('sal_microrred as mr', 'mr.id', '=', 'es.microrred_id');
        $query = $query->join('sal_red as rr', 'rr.id', '=', 'mr.red_id');
        $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query = $query->where('mes', '<=',  $mes);
        if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query = $query->where('pp.id',  $provincia);
        $query = $query->groupBy('idred', 'rr.nombre', 'red')->get();

        return $query;
    }

    public static function getSalPacto2tabla2tabla1($indicador_id, $anio, $mes, $provincia, $distrito, $red = 0)
    {
        $query = ImporPadronAnemia::select('mr.id as idmicro', 'mr.nombre as micro', DB::raw('sum(den) as den'), DB::raw('sum(num) as num'), DB::raw('round(100*sum(num)/sum(den),1) as ind'))
            ->where('anio', $anio);
        $query = $query->join('sal_establecimiento as es', 'es.cod_unico', '=', 'sal_impor_padron_anemia.cod_unico');
        $query = $query->join('sal_microrred as mr', 'mr.id', '=', 'es.microrred_id');
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
        // $query = ImporPadronAnemia::select(
        //     'es.cod_unico as unico',
        //     'es.nombre_establecimiento as eess',
        //     'rr.nombre as red',
        //     'mr.nombre as micro',
        //     'pp.nombre as pro',
        //     'dd.nombre as dis',
        //     DB::raw('sum(den) as den'),
        //     DB::raw('sum(num) as num'),
        //     DB::raw('round(100*sum(num)/sum(den),1) as ind')
        // );
        // $query = $query->where('anio', $anio);
        // $query = $query->join('sal_establecimiento as es', 'es.cod_unico', '=', 'sal_impor_padron_anemia.cod_unico');
        // $query = $query->join('sal_microrred as mr', 'mr.id', '=', 'es.microrred_id');
        // $query = $query->join('sal_red as rr', 'rr.id', '=', 'mr.red_id');
        // $query = $query->join('par_ubigeo as dd', 'dd.id', '=', 'es.ubigeo_id');
        // $query = $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        // if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        // if ($mes > 0) $query = $query->where('mes',  $mes);
        // if ($distrito > 0) $query = $query->where('ubigeo',  $distrito);
        // if ($provincia > 0) $query = $query->where('pp.id',  $provincia);

        // $query = $query->groupBy('unico', 'eess', 'rr.nombre', 'red', 'micro', 'pro', 'dis')->get();

        // return $query;

        // $anio = 2025;
        // $mes = 1;

        // Consulta SQL
        $query = "SELECT 
            LPAD(e.cod_unico, 8, '0') AS unico,
            e.nombre_establecimiento AS eess,
            a.num,
            a.den,
            d.nombre AS dis,
            p.nombre AS pro,
            r.nombre AS red,
            m.nombre AS micro,
            ROUND(100 * a.num / a.den, 1) AS ind
        FROM 
            (
                SELECT cod_unico, SUM(num) AS num, SUM(den) AS den
                FROM sal_impor_padron_anemia a
                WHERE a.anio = :anio1 AND a.mes = :mes1
                GROUP BY cod_unico
            ) AS a
        JOIN 
            (
                SELECT tmpe.cod_unico, tmpe.nombre_establecimiento, tmpe.ubigeo_id, tmpe.microrred_id
                FROM sal_establecimiento tmpe
                JOIN (
                    SELECT DISTINCT cod_unico 
                    FROM sal_impor_padron_anemia 
                    WHERE anio = :anio2 AND mes = :mes2
                ) AS tmpc ON tmpc.cod_unico = tmpe.cod_unico
            ) AS e ON e.cod_unico = a.cod_unico
        JOIN par_ubigeo d ON d.id = e.ubigeo_id
        JOIN par_ubigeo p ON p.id = d.dependencia
        JOIN sal_microrred m ON m.id = e.microrred_id
        JOIN sal_red r ON r.id = m.red_id
        ORDER BY ind desc;";

        $resultados = DB::select(DB::raw($query), ['anio1' => $anio, 'anio2' => $anio, 'mes1' => $mes, 'mes2' => $mes]);


        // Retornar los resultados como JSON
        // return response()->json($resultados);
        return $resultados;
    }

    public static function getSalPacto2tabla0301($indicador_id, $anio, $mes, $provincia, $distrito, $cod_unico)
    {
        $query = ImporPadronAnemia::select(
            'num_doc',
            'dd.nombre as distrito',
            'fecha_nac',
            'seguro',
            'num_supt1',
            'num_supt3',
            'num_recup',
            'num_dosaje',
            'num',
        );
        $query->where('anio', $anio)->where('cod_unico', $cod_unico);
        $query->join('par_ubigeo as dd', 'dd.id', '=', 'ubigeo');
        $query->join('par_ubigeo as pp', 'pp.id', '=', 'dd.dependencia');
        // if (IndicadoresController::$pacto1_anio == $anio) $query = $query->where('mes', '>=', IndicadoresController::$pacto1_mes);
        if ($mes > 0) $query->where('mes',  $mes);
        if ($distrito > 0) $query->where('ubigeo',  $distrito);
        if ($provincia > 0) $query->where('pp.id',  $provincia);

        $query = $query->get();

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
    public static function getEduPacto2anal1($anio = null, $mes = null, $provincia = 0, $distrito = 0, $estado = 0)
    {
        $query = DB::table('edu_cubo_pacto02_local')
            ->select(
                'provincia_id as provincia', // 👈 más claro y consistente
                DB::raw('COUNT(*) as conteo'),
                DB::raw('SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as si'),
                DB::raw('SUM(CASE WHEN estado != 1 THEN 1 ELSE 0 END) as no')
            );
        if ($anio) {
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaInicio = Carbon::create($anio, $mes, 1);
                $fechaFin = $fechaInicio->copy()->endOfMonth();
                $query->whereBetween('fecha_inscripcion', [$fechaInicio, $fechaFin]);
            } else {
                $fechaInicio = Carbon::create($anio, 1, 1);
                $fechaFin = $fechaInicio->copy()->endOfYear();
                $query->whereBetween('fecha_inscripcion', [$fechaInicio, $fechaFin]);
            }
        }
        if ($provincia > 0) {
            $query->where('provincia_id', $provincia);
        }
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }
        if ($estado > 0) {
            $query->where('estado', $estado);
        }
        return $query->groupBy('provincia_id')->get();
    }

    public static function getEduPacto2tabla1_para_eliminar($indicador_id, $anio, $mes, $provincia, $distrito, $estado)
    {
        $query = IndicadorGeneralMeta::select(
            'par_Indicador_general_meta.*',
            'd.codigo',
            'd.id as distrito_id',
            'd.nombre as distrito'
        )
            ->where('indicadorgeneral', $indicador_id)
            ->where('anio', $anio)
            ->join('par_ubigeo as d', 'd.id', '=', 'par_Indicador_general_meta.distrito')
            ->get();
        foreach ($query as $key => $value) {
            $data = CuboPacto2Repositorio::getEduPacto2tabla1($anio, $mes, 0, $value->distrito_id, 0);
            $value->avance = $data->count() > 0 ? ($data->first()->conteo ? $data->first()->conteo : 0) : 0; //$query->conteo ? $query->conteo : 0;
            $value->porcentaje = number_format(100 * ($value->valor > 0 ? $value->avance / $value->valor : 0), 1);
            $value->cumple = $value->avance >= $value->valor ? 1 : 0;
        }
        return $query;
    }

    public static function getEduPacto2tabla1($indicador_id, $anio, $mes = null, $provincia = 0, $distrito = 0, $estado = 0)
    {
        // === 1. Subconsulta: Metas por distrito ===
        $metas = DB::table('par_ubigeo as d')
            ->leftJoin('par_indicador_general_meta as m', 'm.distrito', '=', 'd.id')
            ->select('d.id', 'd.nombre', 'm.valor as meta')
            ->where('m.indicadorgeneral', $indicador_id)
            ->where('m.anio', $anio)
            ->groupBy('d.id', 'd.nombre', 'm.valor');

        // === 2. Subconsulta: Avance por distrito ===
        $avances = DB::table('edu_cubo_pacto02_local as c')
            ->select('c.distrito_id', DB::raw('COUNT(*) as avance'));

        if ($anio) {
            $fechaInicio = Carbon::create($anio, 1, 1);
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
            } else {
                $fechaFin = $fechaInicio->copy()->endOfYear();
            }
            $avances->whereBetween('c.fecha_inscripcion', [$fechaInicio, $fechaFin]);
        }

        if ($provincia > 0) $avances->where('c.provincia_id', $provincia);
        if ($distrito > 0) $avances->where('c.distrito_id', $distrito);
        if ($estado > 0) $avances->where('c.estado', $estado);

        $avances->groupBy('c.distrito_id');

        // === 3. Consulta principal con subconsultas ===
        $sqlMetas = $metas->toSql();
        $sqlAvances = $avances->toSql();

        $bindingsMetas = $metas->getBindings();
        $bindingsAvances = $avances->getBindings();

        $resultado = DB::table(DB::raw("({$sqlMetas}) as ubigeo"))
            ->select(
                'ubigeo.id',
                'ubigeo.nombre as distrito',
                DB::raw('COALESCE(ubigeo.meta, 0) as meta'),
                DB::raw('COALESCE(tb.avance, 0) as avance'),
                DB::raw("
                        CASE 
                            WHEN COALESCE(tb.avance, 0) = 0 THEN NULL 
                            ELSE ROUND(ubigeo.meta / tb.avance, 1) 
                        END as indicador
                    "),
                DB::raw('if((CASE 
                    WHEN COALESCE(tb.avance, 0) = 0 THEN NULL 
                    ELSE ROUND(ubigeo.meta / tb.avance, 1) 
                END)>ubigeo.meta,1,0) as cumple')
            )
            ->leftJoin(DB::raw("({$sqlAvances}) as tb"), 'tb.distrito_id', '=', 'ubigeo.id')
            ->mergeBindings($metas)      // Inyecta bindings de metas
            ->mergeBindings($avances)    // Inyecta bindings de avances
            ->orderBy('indicador', 'desc')
            ->get();

        return $resultado;
    }

    public static function getEduPacto2tabla2_para_eliminar($anio, $mes, $provincia, $distrito, $estado)
    {
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

    public static function getEduPacto2tabla2($anio = null, $mes = null, $provincia = 0, $distrito = 0, $estado = 0)
    {
        $query = DB::table('edu_cubo_pacto02_local')
            ->select(
                'distrito',
                DB::raw('COUNT(*) as conteo'),
                DB::raw('
                        CASE 
                            WHEN COUNT(*) = 0 THEN 0 
                            ELSE ROUND(100 * SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) / COUNT(*), 1)
                        END AS indicador
                    '),
                DB::raw('SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as si'),
                DB::raw('SUM(CASE WHEN estado = 2 THEN 1 ELSE 0 END) as no'),
                DB::raw('SUM(CASE WHEN estado = 3 THEN 1 ELSE 0 END) as pro'),
                DB::raw('SUM(CASE WHEN estado = 4 THEN 1 ELSE 0 END) as sin')
            );
        if ($anio) {
            $fechaInicio = Carbon::create($anio, 1, 1);
            if ($mes && $mes >= 1 && $mes <= 12) {
                $fechaFin = Carbon::create($anio, $mes, 1)->endOfMonth();
            } else {
                $fechaFin = $fechaInicio->copy()->endOfYear();
            }
            $query->whereBetween('fecha_inscripcion', [$fechaInicio, $fechaFin]);
        }
        if ($provincia > 0) {
            $query->where('provincia_id', $provincia);
        }
        if ($distrito > 0) {
            $query->where('distrito_id', $distrito);
        }
        if ($estado > 0) {
            $query->where('estado', $estado);
        }
        return $query->groupBy('distrito')->orderBy('indicador', 'desc')->get();
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
