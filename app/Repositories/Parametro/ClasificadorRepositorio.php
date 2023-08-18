<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\Clasificador;

class ClasificadorRepositorio
{
    public static function Listar_menu_porClasificador($clase_codigo,$sistema_id)
    {
        $data = Clasificador::select('par_clasificador.nombre as nombre_niv1','claNiv2.id as id_niv2','claNiv2.nombre as nombre_niv2',
        'claNiv3.id as id_niv3','claNiv3.codigo','claNiv3.nombre as nombre_niv3','ind.clasificador_id','ind.nombre as indicador', 'ind.url', 'ind.posicion','ind.id','claNiv3.codigoAdicional')
                ->join('par_clasificador as claNiv2', 'par_clasificador.id', '=', 'claNiv2.dependencia')
                ->join('par_clasificador as claNiv3', 'claNiv2.id', '=', 'claNiv3.dependencia')
                ->join('par_indicador as ind', 'claNiv3.id', '=', 'ind.clasificador_id')
                ->where("par_clasificador.codigo", "=", $clase_codigo)
                ->where("claNiv2.estado", "=", 1)
                ->where("claNiv3.estado", "=", 1)
                ->where("ind.sistema_id", "=", $sistema_id)
                ->where("ind.estado", "=", 1)
                ->orderBy('claNiv3.codigo','asc')
                ->orderBy('ind.posicion','asc')
                ->get();

        return $data;
    }    

    public static function Listar_nivel3_porClasificador($clase_codigo,$sistema_id)
    {
        $data = Clasificador::select('claNiv3.id as id_niv3','claNiv3.codigo','claNiv3.nombre as nombre_niv3','claNiv3.codigoAdicional')
                ->join('par_clasificador as claNiv2', 'par_clasificador.id', '=', 'claNiv2.dependencia')
                ->join('par_clasificador as claNiv3', 'claNiv2.id', '=', 'claNiv3.dependencia')   
                ->join('par_indicador as ind', 'claNiv3.id', '=', 'ind.clasificador_id')           
                ->where("par_clasificador.codigo", "=", $clase_codigo)
                ->where("claNiv2.estado", "=", 1)
                ->where("claNiv3.estado", "=", 1)
                ->where("ind.sistema_id", "=", $sistema_id)
                ->where("ind.estado", "=", 1)
                ->orderBy('claNiv3.codigo','asc')  
                ->distinct()          
                ->get();

        return $data;
    }  

}