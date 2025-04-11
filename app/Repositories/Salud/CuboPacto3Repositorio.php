<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto3PadronMaterno;
use Illuminate\Support\Facades\DB;

class CuboPacto3Repositorio
{

    public static function actualizado($anio)
    {
        $maxMes = CuboPacto3PadronMaterno::where('anio', $anio)->max('mes');

        if (!$maxMes) {
            return null;
        }
        $query = CuboPacto3PadronMaterno::from('sal_cubo_pacto3_padron_materno as m')
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
        $query = CuboPacto3PadronMaterno::select(
            DB::raw('sum(numerador) si'),
            DB::raw('sum(denominador)-sum(numerador) no'),
            DB::raw('sum(denominador) conteo'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);

        if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        if ($distrito > 0) $query = $query->where('distrito_id', $distrito);

        $query = $query->get()->first();
        return $query;
    }

    public static function Tabla01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $basal = [
            53 => 0,
            57 => 0,
            37 => 27,
            42 => 44,
            50 => 67,
            56 => 42,
            41 => 43,
            51 => 21,
            38 => 50,
            40 => 25,
            52 => 33,
            39 => 100,
            49 => 58,
            55 => 33,
            45 => 65,
            46 => 43,
            47 => 71,
            36 => 28,
            44 => 67,
        ];
        $v1 = CuboPacto3PadronMaterno::select(
            'distrito_id',
            DB::raw('sum(numerador) as numerador'),
            DB::raw('sum(denominador) as denominador'),
            DB::raw('100*sum(numerador)/sum(denominador) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        $v1 = $v1->groupBy('distrito_id');

        $v1 = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($v1, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.id as distrito_id',
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.numerador, 0) as numerador'),
                DB::raw('COALESCE(anemia.denominador, 0) as denominador'),
                DB::raw('COALESCE(anemia.indicador, 0) as indicador')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')->orderBy('indicador', 'desc')->get();


        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        foreach ($v1 as $key => $value) {
            $value->basal = $basal[$value->distrito_id] ?? 0;
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }

    public static function Tabla02($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        // $query = CuboPacto3PadronMaterno::select(
        //     'codigo_unico',
        //     'eess_parto',
        //     'p.nombre as provincia',
        //     'd.nombre as distrito',
        //     'red',
        //     'microred',
        //     DB::raw('sum(denominador) as denominador'),
        //     DB::raw('sum(numerador) as numerador'),
        //     // DB::raw('sum(num_exam_aux) as condicion1'),
        //     // DB::raw('sum(num_apn) as condicion2'),
        //     // DB::raw('sum(num_entrega_sfaf) as condicion3'),
        //     DB::raw('100*sum(numerador)/sum(denominador) as indicador')
        // )
        //     ->join('par_ubigeo as d', 'd.id', '=', 'distrito_id')
        //     ->join('par_ubigeo as p', 'p.id', '=', 'provincia_id')
        //     ->where('anio', $anio)->where('mes', '<=', $mes);
        // $query = $query->groupBy('codigo_unico', 'eess_parto', 'p.nombre', 'd.nombre', 'red', 'microred')->orderBy('indicador', 'desc')->get();
        // return $query;

        // $query = "SELECT 
        //             e.cod_unico as codigo_unico,
        //             e.nombre_establecimiento as eess_parto,
        //             c.num numerador,
        //             c.den denominador,
        //             d.nombre distrito, 
        //             p.nombre provincia, 
        //             r.nombre red, 
        //             m.nombre microred, 
        //             round(100*c.num/c.den,1) indicador
        //             from (
        //             select codigo_unico ,sum(numerador) num,sum(denominador) den from sal_cubo_pacto3_padron_materno where anio=:anio1 and mes=:mes1 group by codigo_unico
        //             ) c
        //             join (
        //             select tmpe.cod_unico, tmpe.nombre_establecimiento, tmpe.ubigeo_id, tmpe.microrred_id from sal_establecimiento tmpe join (
        //                 select cui_atencion from sal_cubo_pacto1_padron_nominal  where anio=:anio2 and mes=:mes2 group by cui_atencion
        //                 ) as tmpc on tmpc.cui_atencion=tmpe.cod_unico
        //             ) as e on e.cod_unico = c.codigo_unico 
        //             join par_ubigeo d on d.id = e.ubigeo_id
        //             join par_ubigeo p on p.id = d.dependencia
        //             join sal_microred m on m.id = e.microrred_id
        //             join sal_red r on r.id = m.red_id
        //             order by indicador desc ;";



        $query = "SELECT 
                        e.cod_unico as codigo_unico,
                        e.nombre_establecimiento as eess_parto,
                        c.num numerador,
                        c.den denominador,
                        d.nombre AS distrito,
                        p.nombre AS provincia,
                        r.nombre AS red,
                        m.nombre AS microred,
                        ROUND(100 * c.num / c.den, 1) AS indicador
                    FROM 
                        (
                            SELECT codigo_unico, SUM(numerador) AS num, SUM(denominador) AS den
                            FROM sal_cubo_pacto3_padron_materno
                            WHERE anio = :anio1 AND mes = :mes1
                            GROUP BY codigo_unico
                        ) AS c
                    JOIN 
                        (
                            SELECT tmpe.cod_unico, tmpe.nombre_establecimiento, tmpe.ubigeo_id, tmpe.microrred_id
                            FROM sal_establecimiento tmpe
                            JOIN (
                                SELECT cui_atencion 
                                FROM sal_cubo_pacto1_padron_nominal 
                                WHERE anio = :anio2 AND mes = :mes2
                                GROUP BY cui_atencion
                            ) AS tmpc ON tmpc.cui_atencion = tmpe.cod_unico
                        ) AS e ON e.cod_unico = c.codigo_unico
                    JOIN par_ubigeo d ON d.id = e.ubigeo_id
                    JOIN par_ubigeo p ON p.id = d.dependencia
                    JOIN sal_microred m ON m.id = e.microrred_id
                    JOIN sal_red r ON r.id = m.red_id
                    ORDER BY indicador DESC;";

        $resultados = DB::select(DB::raw($query), ['anio1' => $anio, 'anio2' => $anio, 'mes1' => $mes, 'mes2' => $mes]);
        // $resultados = DB::select(DB::raw($query));
        return $resultados;
    }

    public static function Tabla0201($importacion, $indicador, $anio, $mes, $provincia, $distrito, $cod_unico)
    {
        $query = CuboPacto3PadronMaterno::select(
            'num_doc',
            'fecha_parto',
            'distrito',
            'num_exam_aux',
            'num_apn',
            'num_entrega_sfaf',
            'numerador'
        )
            ->join('par_ubigeo as d', 'd.id', '=', 'distrito_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'provincia_id')
            ->where('anio', $anio)->where('mes', '=', $mes)->where('codigo_unico', $cod_unico);
        $query = $query->orderBy('numerador', 'desc')->get();
        return $query;
    }

    public static function Anal01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select('distrito_id', DB::raw('100*sum(numerador)/sum(denominador) as indicador'))->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('distrito_id');

        $query = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($query, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.indicador, 0) as indicador')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')->orderBy('indicador', 'desc')->get();
        return $query;
    }


    public static function Anal02($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            'mes',
            DB::raw('sum(numerador) as si'),
            DB::raw('round(100*sum(numerador)/sum(denominador),1) as indicador')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }

    public static function Anal03_($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            // 'provincia_id as xid',
            'red as edades',
            DB::raw('sum(numerador) as si'),
            DB::raw('sum(denominador)-sum(numerador) as no')
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('edades')->get();
        return $query;
    }

    public static function Anal03($importacion, $anio, $mes, $provincia, $distrito)
    {
        $query = CuboPacto3PadronMaterno::select(
            'mes',
            DB::raw('sum(numerador) as si'),
            DB::raw('sum(denominador) as no'),
        )->where('anio', $anio)->where('mes', '<=', $mes);
        // if ($provincia > 0) $query = $query->where('provincia_id', $provincia);
        // if ($distrito > 0) $query = $query->where('distrito_id', $distrito);
        $query = $query->groupBy('mes')->orderBy('mes')->get();
        return $query;
    }
}
