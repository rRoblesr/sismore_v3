<?php

namespace App\Repositories\Salud;

use Illuminate\Support\Facades\DB;

class PadronNominalRepositorio
{
    public static function PNImportacion_idmax($fuente, $anio, $mes)
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

    public static function Listar_UnDatoSabana($id) {}
}
