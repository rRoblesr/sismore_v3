<?php

namespace App\Repositories\Salud;

use Illuminate\Support\Facades\DB;

class PadronNominalRepositorioSalud
{
    public static function Listar_PadronSabana($nombre_columna, $codigo_institucion, $id_grupo = 0)
    {
        $sector = session('usuario_sector');
        $query = DB::table('sal_sabana_nino as v1');
        if ( $sector == '14')//$sector == 'GL' or
            $query->where($nombre_columna, $codigo_institucion);
        if ($sector == 'MI') {
            $query->join('sal_padron_juntos as v2', function ($join) {
                $join->on('v1.dni', '=', 'v2.dni_mo');
            });
        }
        if ($id_grupo == 1) {
            $query->where('edad_anio', 0)->where('edad_mes', 0);
        } else {
            $query->where('edad_anio', $id_grupo - 2);
            if ($id_grupo == 2) {
                $query->where('edad_mes', '>', 0);
            }
        }

        $result = $query->select('v1.*')->get();
        return $result;
    }

    public static function Listar_UnDatoSabana($id)
    {
        $query = DB::table('sal_sabana_nino as v1')
            ->where('id', $id)->first();
        return $query;
    }
}
