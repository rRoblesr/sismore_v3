<?php

namespace App\Utilities;
use Carbon\Carbon;

class Utilitario
{
    public static function Fecha_ConFormato_DMY($fecha)
    {         
        return Carbon::createFromFormat('d/m/Y',substr($fecha,0,2) . "/" .substr($fecha,3,2). "/" . substr($fecha,6,4));       
    } 
    
    public static function nombre_mes($Mes)
    {         
        $nombre = '';
        
        switch($Mes)
        {
            case '01' :  $nombre = 'Enero';break;
            case '02' :  $nombre = 'Febrero';break;
            case '03' :  $nombre = 'Marzo';break;
            case '04' :  $nombre = 'Abril';break;
            case '05' :  $nombre = 'Mayo';break;
            case '06' :  $nombre = 'Junio';break;
            case '07' :  $nombre = 'Julio';break;
            case '08' :  $nombre = 'Agosto';break;
            case '09' :  $nombre = 'Setiembre';break;
            case '10' :  $nombre = 'Octubre';break;
            case '11' :  $nombre = 'Noviembre';break;
            case '12' :  $nombre = 'Diciembre';break;
        }

        return  $nombre;       
    } 

    public static function fecha_formato_texto_completo($fecha)
    {         
        $nombre = '';

        $fecha = Carbon::parse($fecha );
        $mes =  utilitario::nombre_mes( Carbon::parse($fecha )->format("m"));
        
        $nombre = Carbon::parse($fecha)->format("d").' de '.$mes.' del '.Carbon::parse($fecha )->format("Y");     
        return  $nombre;       
    } 

    public static function fecha_formato_texto_diayMes($fecha)
    {         
        $nombre = '';

        $fecha = Carbon::parse($fecha );
        $mes =  utilitario::nombre_mes( Carbon::parse($fecha )->format("m"));
        
        $nombre = Carbon::parse($fecha)->format("d").' de '.$mes;     
        return  $nombre;       
    } 

    public static function anio_deFecha($fecha)
    {         
        $anio = '';

        $fecha = Carbon::parse($fecha );
       
        
        $nombre = '20'.Carbon::parse($fecha)->format("y");     
        return  $nombre;       
    } 

}

