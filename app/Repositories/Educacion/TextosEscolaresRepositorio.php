<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Tableta;
use Illuminate\Support\Facades\DB;

class TextosEscolaresRepositorio
{

    public static function mas_actual()
    { 
        $data = DB::table('par_importacion as imp')           
                    ->join('edu_textos_escolares as tab', 'imp.id', '=', 'tab.importacion_id')
                    ->join('par_anio as vanio', 'tab.anio_id', '=', 'vanio.id')         
                    ->where('imp.estado','=', 'PR')
                    ->orderBy('vanio.anio', 'desc')
                    ->orderBy('imp.fechaActualizacion', 'desc')
                    ->select('imp.id','imp.fechaActualizacion')
                    ->limit(1)
                    ->get();

        return $data;
    }

    public static function TextosEscolares_anio()
    { 
             $data = DB::table('par_importacion as imp')           
                    ->join('edu_textos_escolares as tab', 'imp.id', '=', 'tab.importacion_id')
                    ->join('par_anio as vanio', 'tab.anio_id', '=', 'vanio.id')        
                    ->where('imp.estado','=', 'PR')              
                    ->orderBy('vanio.anio', 'desc')
                    ->select('vanio.id','vanio.anio')   
                    ->distinct()  
                    ->get();

        return $data;
    }

    public static function fechas_TextosEscolares_anio($anio_id)
    { 
              $data = DB::table('par_importacion as imp')           
                     ->join('edu_textos_escolares as tab', 'imp.id', '=', 'tab.importacion_id')
                     ->join('par_anio as vanio', 'tab.anio_id', '=', 'vanio.id')   
                     ->where('vanio.id','=', $anio_id)      
                     ->where('imp.estado','=', 'PR')              
                     ->orderBy('imp.fechaActualizacion', 'desc')
                     ->select('imp.id as tableta_id','imp.fechaActualizacion','vanio.id','vanio.anio')     
                     ->distinct() 
                     ->get();

         return $data;
    }

    public static function total_porBeneficiario($importacion_id)
    { 
        $data = DB::table(
                        DB::raw("(
                                    select beneficiario, 
                                    SUM(case when ugel = 'DRE UCAYALI'  then cantidad_ugel else 0 end) as cantDRE,
                                    SUM(case when ugel = 'UGEL ATALAYA'  then cantidad_ugel else 0 end) as cantAtalaya,
                                    SUM(case when ugel = 'UGEL CORONEL PORTILLO'  then cantidad_ugel else 0 end) as cantCoronelPortillo,
                                    SUM(case when ugel = 'UGEL PADRE ABAD'  then cantidad_ugel else 0 end) as cantPadreAbad,
                                    SUM(case when ugel = 'UGEL PURUS'  then cantidad_ugel else 0 end) as cantPurus
                                    from  edu_textos_escolares
                                    where importacion_id = $importacion_id
                                    group by beneficiario
                        ) as datos" )
                    ) 

                ->get([                      
                    DB::raw('beneficiario'), 
                    DB::raw('cantDRE'),
                    DB::raw('cantAtalaya'),
                    DB::raw('cantCoronelPortillo'),
                    DB::raw('cantPadreAbad') ,
                    DB::raw('cantPurus') 
                  
                ]);

        return $data;

    }
}