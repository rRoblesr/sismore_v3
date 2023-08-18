<?php

namespace App\Repositories\Vivienda;
use Illuminate\Support\Facades\DB;

use App\Models\Vivienda\Datass;

class DatassRepositorio
{
    public static function Listar_Por_Importacion_id($importacion_id)
    {         
        $Lista = Datass::select('id','departamento','provincia','distrito','ubigeo_cp','centro_poblado',
                              'total_viviendas','viviendas_habitadas','total_poblacion','predomina_primera_lengua',
                              'tiene_energia_electrica','tiene_internet','tiene_establecimiento_salud','pronoei','primaria',
                              'secundaria','sistema_agua','sistema_disposicion_excretas','prestador_codigo','prestador_de_servicio_agua',
                              'tipo_organizacion_comunal','cuota_familiar','servicio_agua_continuo','sistema_cloracion',
                              'realiza_cloracion_agua','tipo_sistema_agua'
                      )
        ->where("importacion_id", "=", $importacion_id)
        ->get();

        return $Lista;
    } 
    
    public static function datos_PorDepartamento_periodos($nroIndicador)
     {
        $data = DB::table(
                    DB::raw( 
                         "(


                          
                            select concat( substring( MONTHNAME ( fechaActualizacion),1,3) , '-' , year ( fechaActualizacion)) as Periodo , fechaActualizacion,
                            Departamento, INDICADOR_SI, round( case when total>0 then (INDICADOR_SI*100)/total else 0 end ,2) as INDICADOR_SI_porcentaje ,
                            INDICADOR_NO, round( case when total>0 then (INDICADOR_NO*100)/total else 0 end ,2) as INDICADOR_NO_porcentaje ,total 
                            from
                            (
                                select fechaActualizacion,Departamento, 
                                (case when $nroIndicador = 1 then Sistema_agua_SI else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_SI else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_SI else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_SI else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_SI else 0 end ) as INDICADOR_SI,
                                (case when $nroIndicador = 1 then Sistema_agua_NO else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_NO else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_NO else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_NO else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_NO else 0 end ) as INDICADOR_NO,
                                case when  $nroIndicador in( 3,4,5) then total_viviendas else total_CP end as total
                                from
                                (
                                    SELECT fechaActualizacion,Departamento, 		
                                    sum(case when sistema_agua = 'SI' then 1 else 0 end) as Sistema_agua_SI,
                                    sum(case when sistema_agua = 'SI' then 0 else 1 end) as Sistema_agua_NO,		
                                    sum(case when sistema_cloracion = 'SI' then 1 else 0 end) as Sistema_cloracion_SI,
                                    sum(case when sistema_cloracion = 'SI' then 0 else 1 end) as Sistema_cloracion_NO,	
                                    sum(case when servicio_agua_continuo = 'SI' then total_viviendas else 0 end) as servicio_agua_continuo_SI,
                                    sum(case when servicio_agua_continuo = 'SI' then 0 else total_viviendas end) as servicio_agua_continuo_NO,		
                                    sum(case when sistema_disposicion_excretas = 'SI' then total_viviendas else 0 end) as sistema_disposicion_excretas_SI,
                                    sum(case when sistema_disposicion_excretas = 'SI' then 0 else total_viviendas end) as sistema_disposicion_excretas_NO,						
                                    sum(case when realiza_cloracion_agua = 'SI' then total_viviendas else 0 end) as realiza_cloracion_agua_SI,
                                    sum(case when realiza_cloracion_agua = 'SI' then 0 else total_viviendas end) as realiza_cloracion_agua_NO,		
                                    COUNT(*) AS total_CP, SUM(total_viviendas) AS total_viviendas
                                    FROM viv_datass datass
                                    inner join par_importacion imp on datass.importacion_id = imp.id
                                    where imp.id in 
                                    (
                                        select id from (
                                                    select row_number() OVER (partition BY Periodo  ORDER BY fechaActualizacion DESC) AS item , fechaActualizacion , Periodo ,id from 
                                                    (
                                                    select concat( substring( MONTHNAME ( fechaActualizacion),1,3) , '-' , year ( fechaActualizacion)) as Periodo, fechaActualizacion, id 
                                                    from par_importacion
                                                    where estado = 'PR' and fuenteImportacion_id = 7
                                                    ) as importacion
                                            ) as datos
                                            where item  = 1
                                            )
                                    -- and imp.id = 516
                                    group by imp.fechaActualizacion,Departamento
                                ) as datos
                            ) AS RESULTADO

            
                         ) as datos"
                    )
               )
               
            ->get([
                DB::raw('Periodo'),
                DB::raw('Departamento'),
                DB::raw('INDICADOR_SI'),
                DB::raw('INDICADOR_SI_porcentaje'),
                DB::raw('INDICADOR_NO'),
                DB::raw('INDICADOR_NO_porcentaje')              
            ]);

        return $data;
     }

    
     public static function datos_PorDepartamento($importacion_id, $nroIndicador)
     {
        $data = DB::table(
                    DB::raw( 
                         "(
                            select Departamento, INDICADOR_SI, round( case when total>0 then (INDICADOR_SI*100)/total else 0 end ,2) as INDICADOR_SI_porcentaje ,
                            INDICADOR_NO, round( case when total>0 then (INDICADOR_NO*100)/total else 0 end ,2) as INDICADOR_NO_porcentaje ,
                            total
                            from
                            (
                                select Departamento, 
                                (case when $nroIndicador = 1 then Sistema_agua_SI else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_SI else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_SI else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_SI else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_SI else 0 end ) as INDICADOR_SI,
                                (case when $nroIndicador = 1 then Sistema_agua_NO else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_NO else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_NO else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_NO else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_NO else 0 end ) as INDICADOR_NO,
                                case when  $nroIndicador in( 3,4,5) then total_viviendas else total_CP end as total
                                from
                                (
                                    SELECT Departamento, 
                                    
                                    sum(case when sistema_agua = 'SI' then 1 else 0 end) as Sistema_agua_SI,
                                    sum(case when sistema_agua = 'SI' then 0 else 1 end) as Sistema_agua_NO,
                                    
                                    sum(case when sistema_cloracion = 'SI' then 1 else 0 end) as Sistema_cloracion_SI,
                                    sum(case when sistema_cloracion = 'SI' then 0 else 1 end) as Sistema_cloracion_NO,
                                
                                    sum(case when servicio_agua_continuo = 'SI' then total_viviendas else 0 end) as servicio_agua_continuo_SI,
                                    sum(case when servicio_agua_continuo = 'SI' then 0 else total_viviendas end) as servicio_agua_continuo_NO,
                                    
                                    sum(case when sistema_disposicion_excretas = 'SI' then total_viviendas else 0 end) as sistema_disposicion_excretas_SI,
                                    sum(case when sistema_disposicion_excretas = 'SI' then 0 else total_viviendas end) as sistema_disposicion_excretas_NO,
                                                    
                                    sum(case when realiza_cloracion_agua = 'SI' then total_viviendas else 0 end) as realiza_cloracion_agua_SI,
                                    sum(case when realiza_cloracion_agua = 'SI' then 0 else total_viviendas end) as realiza_cloracion_agua_NO,
                                    
                                    COUNT(*) AS total_CP, SUM(total_viviendas) AS total_viviendas
                                    FROM viv_datass datass
                                    inner join par_importacion imp on datass.importacion_id = imp.id
                                    where imp.estado = 'PR'
                                    and imp.id = $importacion_id
                                    group by Departamento
                                ) as datos
                            ) AS RESULTADO

            
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('Departamento'),
                DB::raw('INDICADOR_SI'),
                DB::raw('INDICADOR_SI_porcentaje'),
                DB::raw('INDICADOR_NO'),
                DB::raw('INDICADOR_NO_porcentaje')              
            ]);

        return $data;
     }

     public static function datos_PorProvincia($importacion_id, $nroIndicador)
     {
        $data = DB::table(
                    DB::raw( 
                         "(
                            select Departamento,Provincia, INDICADOR_SI, round( case when total>0 then (INDICADOR_SI*100)/total else 0 end ,2) as INDICADOR_SI_porcentaje ,
                            INDICADOR_NO, round( case when total>0 then (INDICADOR_NO*100)/total else 0 end ,2) as INDICADOR_NO_porcentaje ,
                            total
                            from
                            (
                                select Departamento, Provincia,
                                (case when $nroIndicador = 1 then Sistema_agua_SI else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_SI else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_SI else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_SI else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_SI else 0 end ) as INDICADOR_SI,
                                (case when $nroIndicador = 1 then Sistema_agua_NO else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_NO else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_NO else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_NO else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_NO else 0 end ) as INDICADOR_NO,
                                case when  $nroIndicador in( 3,4,5) then total_viviendas else total_CP end as total
                                from
                                (
                                    SELECT Departamento, Provincia,
                                    
                                    sum(case when sistema_agua = 'SI' then 1 else 0 end) as Sistema_agua_SI,
                                    sum(case when sistema_agua = 'SI' then 0 else 1 end) as Sistema_agua_NO,
                                    
                                    sum(case when sistema_cloracion = 'SI' then 1 else 0 end) as Sistema_cloracion_SI,
                                    sum(case when sistema_cloracion = 'SI' then 0 else 1 end) as Sistema_cloracion_NO,
                                
                                    sum(case when servicio_agua_continuo = 'SI' then total_viviendas else 0 end) as servicio_agua_continuo_SI,
                                    sum(case when servicio_agua_continuo = 'SI' then 0 else total_viviendas end) as servicio_agua_continuo_NO,
                                    
                                    sum(case when sistema_disposicion_excretas = 'SI' then total_viviendas else 0 end) as sistema_disposicion_excretas_SI,
                                    sum(case when sistema_disposicion_excretas = 'SI' then 0 else total_viviendas end) as sistema_disposicion_excretas_NO,
                                                    
                                    sum(case when realiza_cloracion_agua = 'SI' then total_viviendas else 0 end) as realiza_cloracion_agua_SI,
                                    sum(case when realiza_cloracion_agua = 'SI' then 0 else total_viviendas end) as realiza_cloracion_agua_NO,
                                    
                                    COUNT(*) AS total_CP, SUM(total_viviendas) AS total_viviendas
                                    FROM viv_datass datass
                                    inner join par_importacion imp on datass.importacion_id = imp.id
                                    where imp.estado = 'PR'
                                    and imp.id = $importacion_id
                                    group by Departamento,Provincia
                                ) as datos
                            ) AS RESULTADO

            
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('Departamento'),
                DB::raw('Provincia'),
                DB::raw('INDICADOR_SI'),
                DB::raw('INDICADOR_SI_porcentaje'),
                DB::raw('INDICADOR_NO'),
                DB::raw('INDICADOR_NO_porcentaje')  ,
                DB::raw('total')              
            ]);

        return $data;
     }

     public static function datos_Distrito_PorProvincia($importacion_id, $nroIndicador, $vprovincia)
     {
        $data = DB::table(
                    DB::raw( 
                         "(
                            select Departamento,Provincia,Distrito, INDICADOR_SI, round( case when total>0 then (INDICADOR_SI*100)/total else 0 end ,2) as INDICADOR_SI_porcentaje ,
                            INDICADOR_NO, round( case when total>0 then (INDICADOR_NO*100)/total else 0 end ,2) as INDICADOR_NO_porcentaje ,
                            total
                            from
                            (
                                select Departamento, Provincia,Distrito,
                                (case when $nroIndicador = 1 then Sistema_agua_SI else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_SI else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_SI else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_SI else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_SI else 0 end ) as INDICADOR_SI,
                                (case when $nroIndicador = 1 then Sistema_agua_NO else 0 end ) + (case when $nroIndicador = 2 then Sistema_cloracion_NO else 0 end ) 
                                + (case when $nroIndicador = 3 then servicio_agua_continuo_NO else 0 end ) + (case when $nroIndicador = 4 then sistema_disposicion_excretas_NO else 0 end ) 
                                + (case when $nroIndicador = 5 then realiza_cloracion_agua_NO else 0 end ) as INDICADOR_NO,
                                case when  $nroIndicador in( 3,4,5) then total_viviendas else total_CP end as total
                                from
                                (
                                    SELECT Departamento, Provincia,Distrito,
                                    
                                    sum(case when sistema_agua = 'SI' then 1 else 0 end) as Sistema_agua_SI,
                                    sum(case when sistema_agua = 'SI' then 0 else 1 end) as Sistema_agua_NO,
                                    
                                    sum(case when sistema_cloracion = 'SI' then 1 else 0 end) as Sistema_cloracion_SI,
                                    sum(case when sistema_cloracion = 'SI' then 0 else 1 end) as Sistema_cloracion_NO,
                                
                                    sum(case when servicio_agua_continuo = 'SI' then total_viviendas else 0 end) as servicio_agua_continuo_SI,
                                    sum(case when servicio_agua_continuo = 'SI' then 0 else total_viviendas end) as servicio_agua_continuo_NO,
                                    
                                    sum(case when sistema_disposicion_excretas = 'SI' then total_viviendas else 0 end) as sistema_disposicion_excretas_SI,
                                    sum(case when sistema_disposicion_excretas = 'SI' then 0 else total_viviendas end) as sistema_disposicion_excretas_NO,
                                                    
                                    sum(case when realiza_cloracion_agua = 'SI' then total_viviendas else 0 end) as realiza_cloracion_agua_SI,
                                    sum(case when realiza_cloracion_agua = 'SI' then 0 else total_viviendas end) as realiza_cloracion_agua_NO,
                                    
                                    COUNT(*) AS total_CP, SUM(total_viviendas) AS total_viviendas
                                    FROM viv_datass datass
                                    inner join par_importacion imp on datass.importacion_id = imp.id
                                    where imp.estado = 'PR'
                                    and imp.id = $importacion_id
                                    and Provincia = '$vprovincia'
                                    group by Departamento,Provincia,Distrito
                                ) as datos
                            ) AS RESULTADO

            
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('Departamento'),
                DB::raw('Provincia'),
                DB::raw('Distrito'),
                DB::raw('INDICADOR_SI'),
                DB::raw('INDICADOR_SI_porcentaje'),
                DB::raw('INDICADOR_NO'),
                DB::raw('INDICADOR_NO_porcentaje')              
            ]);

        return $data;
     }



     public static function datos_PorRegion_segunColumna($columna)
     {
        $data = DB::table(
                    DB::raw( 
                         "(
                            select concat( substring( MONTHNAME ( fechaActualizacion),1,3) , '-' , year ( fechaActualizacion)) as Periodo , fechaActualizacion,
                            valor_Si,round( case when total>0 then (valor_Si*100)/total else 0 end ,2) as valor_Si_Porcentual,
                            valor_No,round( case when total>0 then (valor_No*100)/total else 0 end ,2) as valor_No_Porcentual,
                            total
                            from 
                            (
                                select  fechaActualizacion,
                                sum(case when $columna = 'SI' then 1 else 0 end) as valor_Si,
                                sum(case when $columna = 'SI' then 0 else 1 end) as valor_No,
                                sum(1) as total
                                FROM viv_datass datass
                                    inner join par_importacion imp on datass.importacion_id = imp.id        
                                    where imp.id in 
                                    (
                                        select id from (
                                                    select row_number() OVER (partition BY Periodo  ORDER BY fechaActualizacion DESC) AS item , fechaActualizacion , Periodo ,id from 
                                                    (
                                                    select concat( substring( MONTHNAME ( fechaActualizacion),1,3) , '-' , year ( fechaActualizacion)) as Periodo, fechaActualizacion, id 
                                                    from par_importacion
                                                    where estado = 'PR' and fuenteImportacion_id = 7
                                                    ) as importacion
                                            ) as datos
                                            where item  = 1
                                    )                
                                group by imp.fechaActualizacion
                            ) as resultado
            
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('Periodo'),
                DB::raw('fechaActualizacion'),   
                DB::raw('valor_Si'),
                DB::raw('valor_Si_Porcentual'),
                DB::raw('valor_No'),
                DB::raw('valor_No_Porcentual')              
            ]);

        return $data;
     }


     public static function datos_PorRegion_CP_Periodos()
     {
        $data = DB::table(
                    DB::raw( 
                         "(                            
                            select concat( substring( MONTHNAME ( fechaActualizacion),1,3) , '-' , year ( fechaActualizacion)) as Periodo , fechaActualizacion,
                            total
                            from 
                            (
                                select  fechaActualizacion,
                                sum(1) as total
                                FROM viv_datass datass
                                    inner join par_importacion imp on datass.importacion_id = imp.id        
                                    where imp.id in 
                                    (
                                        select id from (
                                                    select row_number() OVER (partition BY Periodo  ORDER BY fechaActualizacion DESC) AS item , fechaActualizacion , Periodo ,id from 
                                                    (
                                                    select concat( substring( MONTHNAME ( fechaActualizacion),1,3) , '-' , year ( fechaActualizacion)) as Periodo, fechaActualizacion, id 
                                                    from par_importacion
                                                    where estado = 'PR' and fuenteImportacion_id = 7
                                                    ) as importacion
                                            ) as datos
                                            where item  = 1
                                    )                
                                group by imp.fechaActualizacion
                            ) as resultado
            
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('Periodo'),
                DB::raw('fechaActualizacion'),   
                DB::raw('total')             
            ]);

        return $data;
     }


     public static function datos_PorRegion_tipo_organizacion_comunal($importacion_id)
     {
        $data = DB::table(
                    DB::raw( 
                         "(                            
                            select tipo_organizacion_comunal,count(*) as total_OrgComunal,sum(total_asociados) as  total_asociados from viv_datass
                            where importacion_id = $importacion_id
                            and ltrim(rtrim(tipo_organizacion_comunal)) != ''
                            group by tipo_organizacion_comunal
                            order by 2 desc
            
                         ) as datos"
                    )
               )

            ->get([
                DB::raw('tipo_organizacion_comunal'),
                DB::raw('total_OrgComunal'),   
                DB::raw('total_asociados')             
            ]);

        return $data;
     }





}