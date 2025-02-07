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
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        return $v1;
    }

    public static function Tabla02($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $query = "SELECT 
                    lpad(e.cod_unico,8,'0') codigo_unico,
                    e.nombre_establecimiento eess,
                    c.num numerador,
                    c.den denominador,
                    d.nombre AS distrito,
                    p.nombre AS provincia,
                    u.nombre AS departamento,
                    r.nombre AS red,
                    m.nombre AS microrred,
                    ROUND(100 * c.num / c.den, 1) AS indicador
                FROM 
                    (
                        SELECT cod_unico, SUM(numerador) AS num, SUM(denominador) AS den
                        FROM sal_cubo_pacto4_padron_12meses
                        WHERE anio = :anio1 AND mes = :mes1
                        GROUP BY cod_unico
                    ) AS c
                JOIN 
                    (
                        SELECT tmpe.cod_unico, tmpe.nombre_establecimiento, tmpe.ubigeo_id, tmpe.microrred_id
                        FROM sal_establecimiento tmpe
                        JOIN (
                            SELECT cod_unico 
                            FROM sal_cubo_pacto4_padron_12meses 
                            WHERE anio = :anio2 AND mes = :mes2
                            GROUP BY cod_unico
                        ) AS tmpc ON tmpc.cod_unico = tmpe.cod_unico
                    ) AS e ON e.cod_unico = c.cod_unico
                JOIN par_ubigeo d ON d.id = e.ubigeo_id
                JOIN par_ubigeo p ON p.id = d.dependencia
                JOIN par_ubigeo u ON u.id = p.dependencia
                JOIN sal_microred m ON m.id = e.microrred_id
                JOIN sal_red r ON r.id = m.red_id
                ORDER BY indicador DESC;";

        // Ejecutar la consulta
        $resultados = DB::select(DB::raw($query), ['anio1' => $anio, 'anio2' => $anio, 'mes1' => $mes, 'mes2' => $mes]);

        return $resultados;
    }

    public static function Tabla0201($importacion, $indicador, $anio, $mes, $provincia, $distrito, $cod_unico)
    {
        $filtro = function ($query) use ($provincia, $distrito) {
            if ($provincia > 0) $query->where('c4.provincia_id', $provincia);
            if ($distrito > 0) $query->where('c4.distrito_id', $distrito);
        };

        $query = CuboPacto4Padron12Meses::from('sal_cubo_pacto4_padron_12meses as c4')->select(
            'num_doc',
            'fecha_nac',
            'd.nombre as distrito',
            'seguro',
            'num_cred',
            'num_vac',
            'num_esq',
            'num_hb',
            'num_dniemision',
            'numerador',
        )
            ->join('par_ubigeo as d', 'd.id', '=', 'c4.distrito_id')
            ->where('c4.anio', $anio)->where('c4.mes', '=', $mes)->where('cod_unico', $cod_unico)//->tap($filtro) //->where('departamento', 'UCAYALI')
            ->orderBy('numerador', 'desc')->get();
        return $query;
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

        $query = $query->groupBy('distrito_id');

        $query = Ubigeo::from('par_ubigeo as u')
            ->leftJoinSub($query, 'anemia', function ($join) {
                $join->on('anemia.distrito_id', '=', 'u.id');
            })
            ->select(
                'u.nombre as distrito',
                DB::raw('COALESCE(anemia.nnn, 0) as nnn'),
                DB::raw('COALESCE(anemia.ddd, 0) as ddd'),
                DB::raw('COALESCE(anemia.indicador, 0) as indicador')
            )
            ->whereRaw('LENGTH(u.codigo) = 6')->where('u.codigo', 'like', '25%')->orderBy('indicador', 'desc')->get();

        // foreach ($query as $key => $value) {
        //     $value->distrito = $distritos[$value->distrito_id] ?? '';
        // }
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
