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
            ->whereIn('tipo_doc', ['DNI', 'CNV', 'CUI'])
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
            ->whereIn('tipo_doc', ['DNI', 'CNV', 'CUI'])
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
            ->whereIn('tipo_doc', ['DNI', 'CNV', 'CUI'])
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

        $distritos = Ubigeo::whereIn('id', $v1->pluck('distrito_id'))->pluck('nombre', 'id');

        foreach ($v1 as $key => $value) {
            $value->distrito = $distritos[$value->distrito_id] ?? 'Desconocido';
            $value->denominador = $v2[$value->distrito_id]->denominador ?? 0;
            $value->indicador = $value->denominador > 0 ? round(100 * $value->numerador / $value->denominador, 2) : 0;
        }
        $v1 = collect($v1)->sortByDesc('indicador')->values();
        return $v1;
    }
}
