<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Indicador;
use Illuminate\Support\Facades\DB;

class IndicadorRepositorio
{

    public static function listar($sistema, $clasificador, $fuente)
    {
        $query = Indicador::select('par_indicador.*');
        $query = $query->get();
        return $query;
    }

    public static function listar_indicador1($id)
    {
        $query =  DB::table('par_indicador_resultado as v1')
            ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
            ->join('par_anio as v3', 'v3.id', '=', 'v1.anio_id')
            ->select(DB::raw('cast(v1.resultado as SIGNED) as y'), 'v1.nota', 'v2.nombre as departamento', 'v3.anio as name')
            //->select(DB::raw('cast(v1.resultado as SIGNED) as y'),  'v3.anio as name')
            ->where('v1.indicador_id', $id)->get();
        return $query;
    }




    /*public static function ListarSINO_porIndicador($provincia, $distrito, $indicador_id, $importacion_id)
    {
        switch ($indicador_id) {
            case 20:
                $queryx =  DB::table('viv_centropoblado_datass as v1')
                    ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
                    ->where('v1.importacion_id', $importacion_id)
                    ->groupBy('v1.sistema_agua');
                if ($provincia > 0 && $distrito > 0) {
                    $queryx = $queryx->where('v2.id', $distrito);
                } else if ($provincia > 0 && $distrito == 0) {
                    $queryx = $queryx->where('v2.dependencia', $provincia);
                } else {
                }
                $queryx = $queryx->get(['v1.sistema_agua as name', DB::raw('count(v1.id) as y')]);
                break;
            case 21:
                $queryx =  DB::table('viv_centropoblado_datass as v1')
                    ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
                    ->where('v1.importacion_id', $importacion_id)
                    ->groupBy('v1.sistema_cloracion');
                if ($provincia > 0 && $distrito > 0) {
                    $queryx = $queryx->where('v2.id', $distrito);
                } else if ($provincia > 0 && $distrito == 0) {
                    $queryx = $queryx->where('v2.dependencia', $provincia);
                } else {
                }
                $queryx = $queryx->get(['v1.sistema_cloracion as name', DB::raw('count(v1.id) as y')]);
                break;
            case 22:
                $queryx =   DB::table('viv_centropoblado_datass as v1')
                    ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
                    ->where('v1.importacion_id', $importacion_id)
                    ->groupBy('v1.servicio_agua_continuo');
                if ($provincia > 0 && $distrito > 0) {
                    $queryx = $queryx->where('v2.id', $distrito);
                } else if ($provincia > 0 && $distrito == 0) {
                    $queryx = $queryx->where('v2.dependencia', $provincia);
                } else {
                }
                $queryx = $queryx->get(['v1.servicio_agua_continuo as name', DB::raw('count(v1.id) as y')]);
                break;
            case 23:
                $queryx =  DB::table('viv_centropoblado_datass as v1')
                    ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
                    ->where('v1.importacion_id', $importacion_id)
                    ->groupBy('v1.sistema_disposicion_excretas');
                if ($provincia > 0 && $distrito > 0) {
                    $queryx = $queryx->where('v2.id', $distrito);
                } else if ($provincia > 0 && $distrito == 0) {
                    $queryx = $queryx->where('v2.dependencia', $provincia);
                } else {
                }
                $queryx = $queryx->get(['v1.sistema_disposicion_excretas as name', DB::raw('count(v1.id) as y')]);
                break;
            case 24:
                break;
            case 25:
                break;
            case 26:
                $queryx =  DB::table('viv_centropoblado_datass as v1')
                    ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
                    ->where('v1.importacion_id', $importacion_id)
                    ->groupBy('v1.realiza_cloracion_agua');
                if ($provincia > 0 && $distrito > 0) {
                    $queryx = $queryx->where('v2.id', $distrito);
                } else if ($provincia > 0 && $distrito == 0) {
                    $queryx = $queryx->where('v2.dependencia', $provincia);
                } else {
                }
                $queryx = $queryx->get(['v1.realiza_cloracion_agua as name', DB::raw('count(v1.id) as y')]);
                break;

            default:
                break;
        }
        $coor = [['name' => 'SI', 'y' => 0], ['name' => 'NO', 'y' => 0]];
        foreach ($queryx as $item) {
            if ($item->name == 'SI') $coor[0]['y'] += $item->y;
            else $coor[1]['y'] += $item->y;
        }
        $query['indicador'] = $coor;

        return $query;
    }//*/
}
