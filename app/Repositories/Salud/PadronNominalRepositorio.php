<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\ImporPadronNominal;
use Illuminate\Support\Facades\DB;

class PadronNominalRepositorio
{
    public static function PNImportacion_idmax_($fuente, $anio, $mes)
    {
        $sql1 = "SELECT * FROM par_importacion
                WHERE fuenteimportacion_id = ? AND estado = 'PR'
                AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                    SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                    WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ? AND MONTH(fechaActualizacion) = ?
                )
                ORDER BY fechaActualizacion DESC limit 1";
        $query1 = DB::select($sql1, [$fuente, $fuente, $anio, $mes]);
        return $query1 ? $query1[0]->id : 0;
    }

    public static function PNImportacion_idmax($fuente, $anio, $mes = null)
    {
        if ($mes > 0) {
            $sql = "SELECT id, fechaActualizacion FROM par_importacion
                WHERE fuenteimportacion_id = ? 
                  AND estado = 'PR'
                  AND YEAR(fechaActualizacion) = ? 
                  AND MONTH(fechaActualizacion) = ?
                ORDER BY fechaActualizacion DESC 
                LIMIT 1";

            $query = DB::select($sql, [$fuente, $anio, $mes]);
        } else {
            $sql = "SELECT id, fechaActualizacion FROM par_importacion
                WHERE fuenteimportacion_id = ? 
                  AND estado = 'PR'
                  AND YEAR(fechaActualizacion) = ?
                  AND fechaActualizacion = (
                      SELECT MAX(fechaActualizacion) 
                      FROM par_importacion 
                      WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                  )
                LIMIT 1";

            $query = DB::select($sql, [$fuente, $anio, $fuente, $anio]);
        }

        return $query ? $query[0]->id : 0;
    }

    public static function PNImportacion_idmax_p1($fuente, $anio, $mes)
    {
        $sql = "SELECT id FROM par_importacion
            WHERE fuenteimportacion_id = ? 
              AND estado = 'PR'
              AND YEAR(fechaActualizacion) = ? 
              AND MONTH(fechaActualizacion) = ?
            ORDER BY fechaActualizacion DESC 
            LIMIT 1";

        $query = DB::select($sql, [$fuente, $anio, $mes]);
        return $query ? $query[0]->id : 0;
    }

    public static function PNImportacion_idmax_p2($fuente, $anio)
    {
        $sql = "SELECT id, fechaActualizacion FROM par_importacion
            WHERE fuenteimportacion_id = ? 
              AND estado = 'PR'
              AND YEAR(fechaActualizacion) = ?
              AND fechaActualizacion = (
                  SELECT MAX(fechaActualizacion) 
                  FROM par_importacion 
                  WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
              )
            LIMIT 1";

        $query = DB::select($sql, [$fuente, $anio, $fuente, $anio]);
        return $query ? $query[0]->id : 0;
    }

    public static function Listar_UnDatoSabana($id) {}

    public static function cumplen01($importacion, $anio, $mes, $provincia, $distrito)
    {
        $excluidos = ['R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R'];
        $v1 = DB::table('sal_impor_padron_nominal as ipm')
            ->leftJoinSub(
                DB::table('sal_establecimiento')
                    ->select('id')
                    ->where('cod_disa', '34'),
                'est',
                'est.id',
                '=',
                'ipm.establecimiento_id'
            )
            ->where('importacion_id', $importacion)
            //criterio 1
            ->whereIn('tipo_doc', ['DNI', 'CNV'])
            //criterio 2
            ->whereNotNull('apellido_paterno')->whereNotNull('apellido_materno')->whereNotNull('nombre')
            ->where('apellido_paterno', '!=', '')->where('apellido_materno', '!=', '')->where('nombre', '!=', '')
            ->whereNotIn('apellido_paterno', $excluidos)->whereNotIn('apellido_materno', $excluidos)->whereNotIn('nombre', $excluidos)
            //criterio 3
            ->where('seguro_id', '>', 0)
            //criterio 4
            ->whereNotNull('direccion')->where('direccion', '!=', '')
            //crirerio 5
            ->whereNotNull('centro_poblado')->where('centro_poblado', '!=', '')
            //crirerio 6
            ->whereNotNull('est.id')
            //crirerio 7
            ->whereNotNull('num_doc_madre')
            ->where('num_doc_madre', '!=', '')
            //crirerio 8
            ->whereNotNull('apellido_paterno_madre')->whereNotNull('apellido_materno_madre')->whereNotNull('nombres_madre')
            ->where('apellido_paterno_madre', '!=', '')->where('apellido_materno_madre', '!=', '')->where('nombres_madre', '!=', '')
            ->whereNotIn('apellido_paterno_madre', $excluidos)->whereNotIn('apellido_materno_madre', $excluidos)->whereNotIn('nombres_madre', $excluidos)
            //crirerio 9
            ->whereNotNull('grado_instruccion')->where('grado_instruccion', '!=', '')
            //crirerio 10
            ->whereNotNull('lengua_madre')->where('lengua_madre', '!=', '');
        if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);
        return $v1 = $v1->count();
    }

    public static function cumplenDistrito01($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $excluidos = ['R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R'];
        $v1 = DB::table('sal_impor_padron_nominal as ipm')
            ->select('distrito_id', DB::raw('count(*) as numerador'))
            ->leftJoinSub(
                DB::table('sal_establecimiento')
                    ->select('id')
                    ->where('cod_disa', '34'),
                'est',
                'est.id',
                '=',
                'ipm.establecimiento_id'
            )
            ->where('importacion_id', $importacion)
            //criterio 1
            ->whereIn('tipo_doc', ['DNI', 'CNV'])
            //criterio 2
            ->whereNotNull('apellido_paterno')->whereNotNull('apellido_materno')->whereNotNull('nombre')
            ->where('apellido_paterno', '!=', '')->where('apellido_materno', '!=', '')->where('nombre', '!=', '')
            ->whereNotIn('apellido_paterno', $excluidos)->whereNotIn('apellido_materno', $excluidos)->whereNotIn('nombre', $excluidos)
            //criterio 3
            ->where('seguro_id', '>', 0)
            //criterio 4
            ->whereNotNull('direccion')->where('direccion', '!=', '')
            //crirerio 5
            ->whereNotNull('centro_poblado')->where('centro_poblado', '!=', '')
            //crirerio 6
            ->whereNotNull('est.id')
            //crirerio 7
            ->whereNotNull('num_doc_madre')
            ->where('num_doc_madre', '!=', '')
            //crirerio 8
            ->whereNotNull('apellido_paterno_madre')->whereNotNull('apellido_materno_madre')->whereNotNull('nombres_madre')
            ->where('apellido_paterno_madre', '!=', '')->where('apellido_materno_madre', '!=', '')->where('nombres_madre', '!=', '')
            ->whereNotIn('apellido_paterno_madre', $excluidos)->whereNotIn('apellido_materno_madre', $excluidos)->whereNotIn('nombres_madre', $excluidos)
            //crirerio 9
            ->whereNotNull('grado_instruccion')->where('grado_instruccion', '!=', '')
            //crirerio 10
            ->whereNotNull('lengua_madre')->where('lengua_madre', '!=', '');
        if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);
        $v1 = $v1->groupBy('distrito_id')->get(); // es el numerador

        $v2 = ImporPadronNominal::select('distrito_id', DB::raw('count(*) as denominador'))->where('importacion_id', $importacion)->groupBy('distrito_id')->get()->keyBy('distrito_id'); // son los denominadores

        // $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->get();
        $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $indicador)->where('anio', $anio)->pluck('valor', 'distrito');

        // $distritos = Ubigeo::whereIn('id', $v1->pluck('distrito_id'))->get()->keyBy('id');
        $distritos = Ubigeo::whereIn('id', $v1->pluck('distrito_id'))->pluck('nombre', 'id');

        foreach ($v1 as $key => $value) {
            $value->distrito = $distritos[$value->distrito_id] ?? 'Desconocido';
            $value->denominador = $v2[$value->distrito_id]->denominador ?? 0;
            $value->indicador = $value->denominador > 0 ? round(100 * $value->numerador / $value->denominador, 2) : 0;
            $value->meta = $v3[$value->distrito_id] ?? 0;
            $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
        }
        $v1 = collect($v1)->sortByDesc('indicador')->values();
        return $v1;
    }

    public static function cumplenDistrito02($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $excluidos = ['R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R'];
        $v1 = DB::table('sal_impor_padron_nominal as ipm')
            ->select('distrito_id', DB::raw('count(*) as numerador'))
            ->leftJoinSub(
                DB::table('sal_establecimiento')
                    ->select('id')
                    ->where('cod_disa', '34'),
                'est',
                'est.id',
                '=',
                'ipm.establecimiento_id'
            )
            ->where('importacion_id', $importacion)
            //criterio 1
            ->whereIn('tipo_doc', ['DNI', 'CNV'])
            //criterio 2
            ->whereNotNull('apellido_paterno')->whereNotNull('apellido_materno')->whereNotNull('nombre')
            ->where('apellido_paterno', '!=', '')->where('apellido_materno', '!=', '')->where('nombre', '!=', '')
            ->whereNotIn('apellido_paterno', $excluidos)->whereNotIn('apellido_materno', $excluidos)->whereNotIn('nombre', $excluidos)
            //criterio 3
            ->where('seguro_id', '>', 0)
            //criterio 4
            ->whereNotNull('direccion')->where('direccion', '!=', '')
            //crirerio 5
            ->whereNotNull('centro_poblado')->where('centro_poblado', '!=', '')
            //crirerio 6
            ->whereNotNull('est.id')
            //crirerio 7
            ->whereNotNull('num_doc_madre')
            ->where('num_doc_madre', '!=', '')
            //crirerio 8
            ->whereNotNull('apellido_paterno_madre')->whereNotNull('apellido_materno_madre')->whereNotNull('nombres_madre')
            ->where('apellido_paterno_madre', '!=', '')->where('apellido_materno_madre', '!=', '')->where('nombres_madre', '!=', '')
            ->whereNotIn('apellido_paterno_madre', $excluidos)->whereNotIn('apellido_materno_madre', $excluidos)->whereNotIn('nombres_madre', $excluidos)
            //crirerio 9
            ->whereNotNull('grado_instruccion')->where('grado_instruccion', '!=', '')
            //crirerio 10
            ->whereNotNull('lengua_madre')->where('lengua_madre', '!=', '');
        if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);
        $v1 = $v1->groupBy('distrito_id')->get(); // es el numerador

        $v2 = ImporPadronNominal::select('distrito_id', DB::raw('count(*) as denominador'))->where('importacion_id', $importacion);
        if ($provincia > 0) $v2 = $v2->where('provincia_id', $provincia);
        if ($distrito > 0) $v2 = $v2->where('distrito_id', $distrito);
        $v2 = $v2->groupBy('distrito_id')->get()->keyBy('distrito_id');

        $distritos = Ubigeo::whereIn('id', $v1->pluck('distrito_id'))->pluck('nombre', 'id');

        foreach ($v1 as $key => $value) {
            $value->distrito = $distritos[$value->distrito_id] ?? 'Desconocido';
            $value->denominador = $v2[$value->distrito_id]->denominador ?? 0;
            $value->indicador = $value->denominador > 0 ? round(100 * $value->numerador / $value->denominador, 2) : 0;
        }
        $v1 = collect($v1)->sortByDesc('indicador')->values();
        return $v1;
    }

    public static function pacto1anal2($anio, $mes, $provincia, $distrito)
    {
        $xdistrito = '';
        $xprovincia = '';
        if ($provincia > 0) $xprovincia = ' AND sipn.provincia_id = :provincia';
        if ($distrito > 0) $xdistrito = ' AND sipn.distrito_id = :distrito';

        $query = "SELECT
                    mes,
                    COUNT(sipn.id) AS conteo
                FROM
                    (
                        SELECT
                            MONTH(pi.fechaActualizacion) AS mes,
                            pi.id AS maxImportacionId
                        FROM
                            par_importacion pi
                        WHERE
                            pi.estado = 'PR'
                            AND YEAR(pi.fechaActualizacion) = :anio1
                            AND pi.fuenteImportacion_id = 45
                            AND pi.fechaActualizacion = (
                                SELECT
                                    MAX(sub.fechaActualizacion)
                                FROM
                                    par_importacion sub
                                WHERE
                                    sub.estado = 'PR'
                                    AND YEAR(sub.fechaActualizacion) = :anio2
                                    AND sub.fuenteImportacion_id = 45
                                    AND DATE_FORMAT(sub.fechaActualizacion, '%Y-%m') = DATE_FORMAT(pi.fechaActualizacion, '%Y-%m')
                            )
                    ) sub
                LEFT JOIN sal_impor_padron_nominal sipn ON
                    sub.maxImportacionId = sipn.importacion_id
                    $xprovincia $xdistrito
                GROUP BY
                    sub.mes
                ORDER BY
                    sub.mes;";

        $params = ['anio1' => $anio, 'anio2' => $anio,];
        if ($provincia > 0) $params['provincia'] = $provincia;
        if ($distrito > 0) $params['distrito'] = $distrito;

        $query = DB::select($query, $params);

        $query2 = "SELECT
                    mes,
                    COUNT(sipn.id) AS conteo
                FROM
                    (
                        SELECT
                            MONTH(pi.fechaActualizacion) AS mes,
                            pi.id AS maxImportacionId
                        FROM
                            par_importacion pi
                        WHERE
                            pi.estado = 'PR'
                            AND YEAR(pi.fechaActualizacion) = :anio1
                            AND pi.fuenteImportacion_id = 45
                            AND pi.fechaActualizacion = (
                                SELECT
                                    MAX(sub.fechaActualizacion)
                                FROM
                                    par_importacion sub
                                WHERE
                                    sub.estado = 'PR'
                                    AND YEAR(sub.fechaActualizacion) = :anio2
                                    AND sub.fuenteImportacion_id = 45
                                    AND DATE_FORMAT(sub.fechaActualizacion, '%Y-%m') = DATE_FORMAT(pi.fechaActualizacion, '%Y-%m')
                            )
                    ) sub
                LEFT JOIN (
                    SELECT ipm.* FROM `sal_impor_padron_nominal` ipm
                    LEFT JOIN (SELECT id FROM sal_establecimiento WHERE cod_disa = '34') AS est ON est.id = ipm.establecimiento_id
                    WHERE 1
                        -- criterio 1
                        and tipo_doc IN ('DNI', 'CNV', 'CUI')
                        -- criterio 2
                        AND apellido_paterno IS NOT NULL AND apellido_materno IS NOT NULL AND nombre IS NOT NULL
                        AND apellido_paterno != '' AND apellido_materno != '' AND nombre != ''
                        AND apellido_paterno NOT IN ('R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R')
                        AND apellido_materno NOT IN ('R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R')
                        AND nombre NOT IN ('R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R')
                        -- criterio 3
                        AND seguro_id > 0
                        -- criterio 4
                        AND (direccion IS NOT NULL AND direccion != '')
                        -- criterio 5
                        AND (centro_poblado IS NOT NULL AND centro_poblado != '')
                        -- criterio 6
                        AND est.id IS NOT null
                        -- criterio 7
                        AND (num_doc_madre IS NOT NULL AND num_doc_madre != '')
                        -- criterio 8
                        AND apellido_paterno_madre IS NOT NULL AND apellido_materno_madre IS NOT NULL AND nombres_madre IS NOT NULL
                        AND apellido_paterno_madre != '' AND apellido_materno_madre != '' AND nombres_madre != ''
                        AND apellido_paterno_madre NOT IN ('R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R')
                        AND apellido_materno_madre NOT IN ('R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R')
                        AND nombres_madre NOT IN ('R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R')
                        -- criterio 9
                        AND (grado_instruccion IS NOT NULL AND grado_instruccion != '')
                        -- criterio 10
                        AND (lengua_madre IS NOT NULL AND lengua_madre != '')
                ) sipn ON
                    sub.maxImportacionId = sipn.importacion_id
                    $xprovincia $xdistrito
                GROUP BY
                    sub.mes
                ORDER BY
                    sub.mes;";

        $params2 = ['anio1' => $anio, 'anio2' => $anio,];
        if ($provincia > 0) $params2['provincia'] = $provincia;
        if ($distrito > 0) $params2['distrito'] = $distrito;

        $query2 = DB::select($query2, $params2);

        return compact('query', 'query2');
    }

    public static function pacto1anal3x($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $excluidos = ['R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R'];
        $v1 = DB::table('sal_impor_padron_nominal as ipm')
            ->select(
                DB::raw('case when tipo_edad in("D","M") then "MENOR DE 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
                DB::raw('count(*) as numerador')
            )
            ->leftJoinSub(
                DB::table('sal_establecimiento')
                    ->select('id')
                    ->where('cod_disa', '34'),
                'est',
                'est.id',
                '=',
                'ipm.establecimiento_id'
            )
            ->leftJoinSub(
                DB::table('sal_impor_padron_nominal')
                    ->select(
                        DB::raw('case when tipo_edad in("D","M") then "MENOR DE 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
                        DB::raw('count(*) as numerador')
                    )
                    ->where('importacion_id', $importacion),
                'sipnx',
                'sipnx.edades',
                '=',
                'ipm.edades'
            )
            ->where('importacion_id', $importacion)
            //criterio 1
            ->whereIn('tipo_doc', ['DNI', 'CNV'])
            //criterio 2
            ->whereNotNull('apellido_paterno')->whereNotNull('apellido_materno')->whereNotNull('nombre')
            ->where('apellido_paterno', '!=', '')->where('apellido_materno', '!=', '')->where('nombre', '!=', '')
            ->whereNotIn('apellido_paterno', $excluidos)->whereNotIn('apellido_materno', $excluidos)->whereNotIn('nombre', $excluidos)
            //criterio 3
            ->where('seguro_id', '>', 0)
            //criterio 4
            ->whereNotNull('direccion')->where('direccion', '!=', '')
            //crirerio 5
            ->whereNotNull('centro_poblado')->where('centro_poblado', '!=', '')
            //crirerio 6
            ->whereNotNull('est.id')
            //crirerio 7
            ->whereNotNull('num_doc_madre')
            ->where('num_doc_madre', '!=', '')
            //crirerio 8
            ->whereNotNull('apellido_paterno_madre')->whereNotNull('apellido_materno_madre')->whereNotNull('nombres_madre')
            ->where('apellido_paterno_madre', '!=', '')->where('apellido_materno_madre', '!=', '')->where('nombres_madre', '!=', '')
            ->whereNotIn('apellido_paterno_madre', $excluidos)->whereNotIn('apellido_materno_madre', $excluidos)->whereNotIn('nombres_madre', $excluidos)
            //crirerio 9
            ->whereNotNull('grado_instruccion')->where('grado_instruccion', '!=', '')
            //crirerio 10
            ->whereNotNull('lengua_madre')->where('lengua_madre', '!=', '');
        if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);
        $v1 = $v1->groupBy('edades')->get(); // es el numerador

        return $v1;
    }

    public static function pacto1anal3($importacion, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $excluidos = ['R N', 'N N', 'XXX', 'NN', 'SD', 'SN', 'SR', 'XX', 'RN', 'R'];

        $v1 = DB::table('sal_impor_padron_nominal as ipm')
            ->select(
                DB::raw('case when tipo_edad in("D","M") then 1 when tipo_edad="A" AND edad=1 then 2 else edad+1 end as xid'),
                DB::raw('case when tipo_edad in("D","M") then "MENOR DE 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
                DB::raw('count(*) as si')
            )
            ->leftJoinSub(
                DB::table('sal_establecimiento')
                    ->select('id')
                    ->where('cod_disa', '34'),
                'est',
                'est.id',
                '=',
                'ipm.establecimiento_id'
            )

            ->where('ipm.importacion_id', $importacion)
            ->whereIn('tipo_doc', ['DNI', 'CNV'])
            ->whereNotNull('apellido_paterno')->whereNotNull('apellido_materno')->whereNotNull('nombre')
            ->where('apellido_paterno', '!=', '')->where('apellido_materno', '!=', '')->where('nombre', '!=', '')
            ->whereNotIn('apellido_paterno', $excluidos)->whereNotIn('apellido_materno', $excluidos)->whereNotIn('nombre', $excluidos)
            ->where('seguro_id', '>', 0)
            ->whereNotNull('direccion')->where('direccion', '!=', '')
            ->whereNotNull('centro_poblado')->where('centro_poblado', '!=', '')
            ->whereNotNull('est.id')
            ->groupBy('xid', 'edades');
        if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);
        $v1 = $v1->orderBy('xid')->get();

        $v2 = ImporPadronNominal::select(
            DB::raw('case when tipo_edad in("D","M") then "MENOR DE 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
            DB::raw('count(*) as no')
        )->where('importacion_id', $importacion);
        if ($provincia > 0) $v1 = $v1->where('provincia_id', $provincia);
        if ($distrito > 0) $v1 = $v1->where('distrito_id', $distrito);
        $v2 = $v2->groupBy('edades')->get()->pluck('no', 'edades');

        foreach ($v1 as $key => $value) {
            $value->no = $v2[$value->edades];
        }

        return $v1;
    }
}
