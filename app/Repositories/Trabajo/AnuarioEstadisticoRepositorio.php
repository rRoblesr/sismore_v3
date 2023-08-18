<?php

namespace App\Repositories\Trabajo;

use App\Models\Trabajo\Anuario_Estadistico;
use Illuminate\Support\Facades\DB;

class AnuarioEstadisticoRepositorio
{
    
     public static function Listar_Por_Importacion_id($importacion_id)
     {         
          $lista = Anuario_Estadistico::select( 'nombre','enero','febrero','marzo','abril','mayo',
          'junio','julio','agosto','setiembre','octubre','noviembre','diciembre')
          ->join('par_ubigeo', 'par_ubigeo.id', '=', 'tra_anuario_estadistico.ubigeo_id')
          ->where("importacion_id", "=", $importacion_id)
          ->get();

          return $lista;
     } 

     public static function datos_AnuarioEstadistico($importacion_id)
     {         
          $lista = Anuario_Estadistico::select( 'anio')
          ->join('par_anio', 'tra_anuario_estadistico.anio_id', '=', 'par_anio.id')
          ->where("importacion_id", "=", $importacion_id)
          ->get();

          return $lista;
     } 
    
     public static function Promedio_Remuneracion_trab_sector_privado($fuenteImportacion_id,$ubigeo_id)
     {
          $data = DB::table('par_importacion as imp')
               ->join('tra_anuario_estadistico as anuEst', 'imp.id', '=', 'anuEst.importacion_id')
               ->join('par_anio as anio', 'anuEst.anio_id', '=', 'anio.id')
               ->where('imp.estado', '=', 'PR')
               ->where('fuenteImportacion_id', '=',$fuenteImportacion_id)
               ->where('ubigeo_id', '=',$ubigeo_id)
               ->groupBy('anio.anio')
               ->groupBy('ubigeo_id')            
               ->get([
                    DB::raw('ubigeo_id'),
                    DB::raw('anio.anio'),
                    DB::raw('sum(enero + febrero + marzo + abril + mayo + junio + julio + agosto + setiembre + octubre + noviembre + diciembre)/12 as promedioAnual')                 
               ]);

          return $data;
     }

     public static function datosAnuario_Estadistico($fuenteImportacion_id,$ubigeo_id)
     {
          $data = DB::table('par_importacion as imp')
               ->join('tra_anuario_estadistico as anuEst', 'imp.id', '=', 'anuEst.importacion_id')
               ->join('par_anio as anio', 'anuEst.anio_id', '=', 'anio.id')
               ->where('imp.estado', '=', 'PR')
               ->where('fuenteImportacion_id', '=',$fuenteImportacion_id)
               ->where('ubigeo_id', '=',$ubigeo_id)
              
               ->groupBy('anio.anio')
               ->groupBy('ubigeo_id')  
               ->groupBy('enero','febrero','marzo','abril','mayo','junio','julio','agosto','setiembre','octubre','noviembre','diciembre')  
               ->orderBy('anio.anio','desc')          
               ->get([
                    DB::raw('ubigeo_id'),
                    DB::raw('anio.anio'),
                    DB::raw('enero'),
                    DB::raw('febrero'),
                    DB::raw('marzo'),
                    DB::raw('abril'),
                    DB::raw('mayo'),
                    DB::raw('junio'),
                    DB::raw('julio'),
                    DB::raw('agosto'),
                    DB::raw('setiembre'),
                    DB::raw('octubre'),
                    DB::raw('noviembre'),
                    DB::raw('diciembre'),
                    DB::raw('sum(enero + febrero + marzo + abril + mayo + junio + julio + agosto + setiembre + octubre + noviembre + diciembre)/12 as promedioAnual')                 
               ]);

          return $data;
     }

     public static function ranking_promedio_remuneracion_regiones($anio_id,$fuenteImportacion_id)
     {
        $data = DB::table(
                    DB::raw( 
                         "(
                              select row_number() OVER (ORDER BY promedio desc ) AS posicion,ubigeo.nombre as region,
                              sum(enero + febrero + marzo + abril + mayo + junio + julio + agosto + setiembre + octubre + noviembre + diciembre)/12 as promedio 
                              from par_importacion imp
                              inner join tra_anuario_estadistico anuEst on imp.id = anuEst.importacion_id
                              inner join par_anio anio on anuEst.anio_id = anio.id
                              inner join par_ubigeo ubigeo on anuEst.ubigeo_id = ubigeo.id
                              where fuenteImportacion_id = $fuenteImportacion_id and imp.estado = 'PR'
                              and anio.id = $anio_id
                              group by ubigeo.nombre
                              order by promedio desc                    
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('posicion'),
                DB::raw('region'),
                DB::raw('promedio')               
            ]);

        return $data;
     }

     public static function anios_anuarioEstadistico()
     {
        $data = DB::table(
                    DB::raw(
                         "(
                              select DISTINCT anio.id , anio.anio from par_importacion imp
                              inner join tra_anuario_estadistico anuEst on imp.id = anuEst.importacion_id
                              inner join par_anio anio on anuEst.anio_id = anio.id
                              where imp.estado = 'PR'
                              ORDER BY anio desc                   
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('id'),
                DB::raw('anio')           
            ]);

        return $data;
     }


     public static function ranking_promedio_prestadores_servicio4ta()
     {
        $data = DB::table(
                    DB::raw( 
                         "(
                              select anio,
                              sum(case when fuenteImportacion_id = 21 then promedio else 0 end) as publico,
                              sum(case when fuenteImportacion_id = 22 then promedio else 0 end) as privado
                              from 
                              (
                                   select fuenteImportacion_id,anio, 
                                   (sum(enero + febrero + marzo + abril + mayo + junio + julio + agosto + setiembre + octubre + noviembre + diciembre))/12 as promedio
                                   from tra_anuario_estadistico as anuE
                                   inner join par_importacion as imp on anuE.importacion_id = imp.id
                                   inner join par_anio as anio on anuE.anio_id = anio.id
                                   where fuenteImportacion_id in (21,22)
                                   and imp.estado = 'PR' and ubigeo_id = 34
                                   group by fuenteImportacion_id,anio
                              )
                              as datos
                              group by anio                    
                         ) as datos"
                    )
               )

            ->get([
               
                DB::raw('anio'),
                DB::raw('publico') ,
                DB::raw('privado')               
            ]);

        return $data;
     }
}
