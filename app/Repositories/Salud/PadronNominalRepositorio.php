<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto1PadronNominal;
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

}
