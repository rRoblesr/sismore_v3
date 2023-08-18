<?php

namespace App\Repositories\Trabajo;

use App\Models\Trabajo\ProEmpleo;
use App\Models\Trabajo\ProEmpleo_Colocados;
use Illuminate\Support\Facades\DB;

class ProEmpleoRepositorio
{
     public static function ProEmpleo_porIdImportacion($importacion_id)
     {
          $data = DB::table('tra_proempleo as proEmpleo')           
          ->join('par_anio as anio', 'anio.id', '=', 'proEmpleo.anio_id')  
          ->join('par_mes as meses', 'proEmpleo.mes', '=', 'meses.codigo')        
          ->where('proEmpleo.importacion_id','=', $importacion_id)     
          ->get([  
              DB::raw('proEmpleo.oferta_hombres'),       
              DB::raw('proEmpleo.oferta_mujeres'),   
              DB::raw('proEmpleo.demanda'),
              DB::raw('anio.anio'),
              DB::raw('meses.mes')
          ])
          ->first();

         return $data;
     }

     public static function ProEmpleo_anios()
     {
          $data = DB::table('tra_proempleo as proEmpleo')  
          ->join('par_anio as anio', 'anio.id', '=', 'proEmpleo.anio_id')           
          ->join('par_importacion as imp', 'proEmpleo.importacion_id', '=', 'imp.id')
          ->where('imp.estado','=', 'PR') 
          ->orderBy('anio.anio','desc')   
          ->distinct()     
          ->get([  
              DB::raw('anio.id'),
              DB::raw('anio.anio')
          ])
      
          ;

         return $data;
     }

     public static function ProEmpleo_ultimo_anio()
     {
          $data = DB::table('tra_proempleo as proEmpleo')  
          ->join('par_anio as anio', 'anio.id', '=', 'proEmpleo.anio_id')           
          ->join('par_importacion as imp', 'proEmpleo.importacion_id', '=', 'imp.id')  

          ->where('imp.estado','=', 'PR') 
          ->orderBy('anio.anio','desc')        
          ->get([  
              DB::raw('proEmpleo.anio_id')
          ])
          ->first()
          ;

         return $data;
     }

     public static function Listar_Por_Importacion_id($importacion_id)
     {         
          $lista = ProEmpleo_Colocados::select( 'ruc','empresa','titulo','provincia','distrito','tipDoc',
          'documento','nombres','apellidos','sexo','per_Con_Discapacidad','email','telefono1','telefono2',
          'colocado','fuente','observaciones',)
          ->join('tra_proempleo', 'tra_proempleo.id', '=', 'tra_proempleo_colocados.proempleo_id')
          ->where("importacion_id", "=", $importacion_id)
          ->get();

          return $lista;
     }
     
     public static function eliminar_mismoPeriodo($importacion_id)
     {
          DB::update("update par_importacion set estado = 'EL', updated_at = now()
                         where id in (
                              select importacion_id from (
                                        select mes,anio_id from par_importacion imp
                                        inner join tra_proempleo proEmp on imp.id = proEmp.importacion_id
                                        where imp.id = $importacion_id
                              )as periodo
                              inner join tra_proempleo proEmp on periodo.mes = proEmp.mes and periodo.anio_id = proEmp.anio_id
                              inner join par_importacion imp on proEmp.importacion_id = imp.id
                              where importacion_id != $importacion_id and imp.estado != 'EL'
                         ) "
                    );       
     }

     public static function datos_PorMes_yAnio($anio_id)
        {
                $data = DB::table('par_importacion as imp')
                        ->join('tra_proempleo as proEmp', 'imp.id', '=', 'proEmp.importacion_id')
                        ->join('par_anio as anio', 'proEmp.anio_id', '=', 'anio.id')
                        ->join('tra_proempleo_colocados as colocados', 'proEmp.id', '=', 'colocados.proempleo_id')

                        

                        ->where('imp.estado', '=', 'PR')
                        ->where('anio.id', '=',$anio_id)
                        ->groupBy('proEmp.id')
                        ->groupBy('oferta_hombres')
                        ->groupBy('oferta_mujeres')
                        ->groupBy('demanda')
                        ->groupBy('mes')
                        ->groupBy('anio')

                        ->orderBy('mes')
                     
                        ->get([
                                DB::raw('oferta_hombres'),
                                DB::raw('oferta_mujeres'),
                                DB::raw('(oferta_hombres + oferta_mujeres) as oferta'),
                                DB::raw('demanda'),
                                DB::raw('mes'),
                                DB::raw('count( 1 ) as cantColocados')
                              ]);

                return $data;
        }

     public static function formato_reporte_MTPE($anio_id)
     {
        $data = DB::table(
                    DB::raw(
                         "(
                              select oferta_hombres,oferta_mujeres,demanda,meses.mes as nombreMes ,cantColocadosM,cantColocadosF,meses.codigo
                              from par_importacion imp
                              inner join tra_proempleo as proEmpleo on imp.id = proEmpleo.importacion_id
                              inner join par_mes as meses on proEmpleo.mes = meses.codigo
                              inner join (select proempleo_id,
                                                  sum(case when sexo = 'M' then 1 else 0 end) as cantColocadosM,
                                                  sum(case when sexo = 'F' then 1 else 0 end) as cantColocadosF 
                                                  from tra_proempleo_colocados
                              group by proempleo_id) as colocados on proEmpleo.id = colocados.proempleo_id

                              where imp.estado = 'PR' and anio_id = $anio_id 
                             
                         ) as datos
                         order by codigo
                         "
                    )
               )

            ->get([
                DB::raw('oferta_hombres'),
                DB::raw('oferta_mujeres'),
                DB::raw('demanda'),
                DB::raw('nombreMes'),
                DB::raw('cantColocadosM')  ,
                DB::raw('cantColocadosF')               
            ]);

        return $data;
     }

     public static function formato_reporte_MTPE_Discapacitados($anio_id)
     {
        $data = DB::table(
                    DB::raw(
                         "(
                              select codigo,meses.mes as nombreMes,abreviado,ifnull(oferta_hombres,0) as oferta_hombres,
                              ifnull(oferta_mujeres,0) as oferta_mujeres,ifnull(demanda,0) as demanda,
                              ifnull(cantColocadosM,0) as cantColocadosM,ifnull(cantColocadosF,0) as cantColocadosF
                              from par_mes as meses
                              left join
                              (
                                   select oferta_hombres,oferta_mujeres,demanda,mes,cantColocadosM,cantColocadosF
                                   from par_importacion imp
                                   inner join tra_proempleo as proEmpleo on imp.id = proEmpleo.importacion_id
                                   inner join (
                                                       select proempleo_id,
                                                            sum(case when sexo = 'M' then 1 else 0 end) as cantColocadosM,
                                                            sum(case when sexo = 'F' then 1 else 0 end) as cantColocadosF 
                                                            from tra_proempleo_colocados
                                                            where per_Con_Discapacidad = 'SI'
                                                       group by proempleo_id
                                                  ) as colocados on proEmpleo.id = colocados.proempleo_id
                                   where imp.estado = 'PR'
                              ) as datos on meses.codigo = datos.mes
                              where meses.codigo <= ( select max(mes) mesMaximo from par_importacion imp
                                                            inner join tra_proempleo as proEmpleo on imp.id = proEmpleo.importacion_id
                                                            where anio_id = $anio_id and  imp.estado = 'PR'
                                                  )
                              order by codigo
                         ) as datos"
                    )
               )

            ->get([
           
                DB::raw('demanda'),
                DB::raw('nombreMes'),
                DB::raw('cantColocadosM')  ,
                DB::raw('cantColocadosF') ,
                DB::raw('(cantColocadosF + cantColocadosM) as totalColocados ')              
            ]);

        return $data;
     }


}
