<?php

namespace App\Repositories\Vivienda;

use App\Models\Vivienda\CentroPobladoDatass;
use App\Models\Vivienda\Datass;
use Illuminate\Support\Facades\DB;

class CentroPobladoDatassRepositorio
{
    public static function listar_provincia()
    {
        $query = CentroPobladoDatass::select('v3.id', 'v3.nombre')
            ->join('par_ubigeo as v2', 'v2.id', '=', 'viv_centropoblado_datass.ubigeo_id')
            ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.dependencia')
            ->groupBy('v3.id')->groupBy('v3.nombre')
            ->get();
        return $query;
    }
    public static function listar_distrito($provincia)
    {
        $query = CentroPobladoDatass::select('v2.id', 'v2.nombre')
            ->join('par_ubigeo as v2', 'v2.id', '=', 'viv_centropoblado_datass.ubigeo_id')
            ->where('v2.dependencia', $provincia)
            ->groupBy('v2.id')->groupBy('v2.nombre')
            ->get();
        return $query;
    }
    public static function listar_centroPoblado($importacion_id)
    {
        $query = DB::table(DB::raw('(select distinct ubigeo_cp from viv_centropoblado_datass as v1 
        inner join par_importacion as v2 on v2.id=v1.importacion_id 
        where v2.estado="PR" and v1.importacion_id=' . $importacion_id . ') as tabla'))
            ->select(DB::raw('count(ubigeo_cp) as conteo'))
            ->first();
        return $query;
    }
    public static function listar_ultimo() //'.$ultimo->importacion_id.' 
    {
        $ultimo = DB::table(DB::raw('(select max(v1.importacion_id) as importacion_id from viv_centropoblado_datass v1 
        inner join par_importacion v2 on v2.id=v1.importacion_id 
        where v2.estado="PR") as xxx'))->first();
        $query = DB::table(DB::raw('(select 
                        v3.nombre provincia,
                        v2.nombre distrito,
                        count(v1.id) centropoblado,
                        SUM(IF(v1.sistema_agua="SI",1,0)) sistema_agua  ,
                        SUM(IF(v1.sistema_cloracion="SI",1,0)) sistema_cloracion ,
                        SUM(IF(v1.sistema_disposicion_excretas="SI",1,0)) sistema_disposicion_excretas ,
                        SUM(v1.total_poblacion) total_poblacion 
                from viv_centropoblado_datass v1 
                inner join par_ubigeo v2 on v2.id=v1.ubigeo_id 
                inner join par_ubigeo v3 on v3.id=v2.dependencia 
                where v1.importacion_id=356 
                group by v3.nombre,v2.nombre 
                order by v3.nombre ) as xx'))->get();
        return $query;
    }
    public static function poblacion_servicio_agua($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(SELECT 
                                    SUM(total_poblacion) as total,
                                    "SI" AS servicio,
                                    SUM(poblacion_servicio_agua) as conteo,
                                    ROUND(SUM(poblacion_servicio_agua)*100/SUM(total_poblacion),2) as porcentaje 
                                    FROM viv_centropoblado_datass as v1 
                                    INNER JOIN par_ubigeo as v2 ON v2.id=v1.ubigeo_id 
                                    WHERE v1.importacion_id=' . $importacion_id . $ubicacion . ' 
                                    UNION ALL 
                                    SELECT 
                                    SUM(total_poblacion) as total,
                                    "NO" AS servicio,
                                    SUM(total_poblacion-poblacion_servicio_agua) as conteo ,
                                    ROUND((SUM(total_poblacion)-SUM(poblacion_servicio_agua))*100/SUM(total_poblacion),2) as porcentaje 
                                    FROM viv_centropoblado_datass as v1 
                                    INNER JOIN par_ubigeo as v2 ON v2.id=v1.ubigeo_id 
                                    WHERE v1.importacion_id=' . $importacion_id . $ubicacion . ') as datass'))
            ->select(DB::raw('FORMAT(cast(conteo as SIGNED),0) as conteo'), DB::raw('servicio as name'), DB::raw('cast(porcentaje as double) as y'))
            ->get();
        return $query;
    }
    public static function viviendas_servicio_agua($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(SELECT 
                                    SUM(viviendas_habitadas) as total,
                                    "SI" AS servicio,
                                    SUM(viviendas_conexion) as conteo,
                                    ROUND(SUM(viviendas_conexion)*100/SUM(viviendas_habitadas),2) as porcentaje 
                                    FROM viv_centropoblado_datass as v1 
                                    INNER JOIN par_ubigeo as v2 ON v2.id=v1.ubigeo_id 
                                    WHERE v1.importacion_id=' . $importacion_id . $ubicacion . ' 
                                    UNION ALL 
                                    SELECT 
                                    SUM(viviendas_habitadas) as total,
                                    "NO" AS servicio,
                                    SUM(viviendas_habitadas-viviendas_conexion) as conteo ,
                                    ROUND((SUM(viviendas_habitadas)-SUM(viviendas_conexion))*100/SUM(viviendas_habitadas),2) as porcentaje 
                                    FROM viv_centropoblado_datass as v1 
                                    INNER JOIN par_ubigeo as v2 ON v2.id=v1.ubigeo_id 
                                    WHERE v1.importacion_id=' . $importacion_id . $ubicacion . ') as datass'))
            ->select(DB::raw('FORMAT(cast(conteo as SIGNED),0) as conteo'), DB::raw('servicio as name'), DB::raw('cast(porcentaje as double) as y'))
            ->get();
        return $query;
    }
    public static function querysPIndicador($importacion_id, $campo, $ubicacion, $SI, $NO)
    {
        $query = '(select servicio as name,SUM(conteo) as y from (
            select IF(v1.sistema_disposicion_excretas="SI","' . $SI . '","' . $NO . '") as servicio, SUM(v1.' . $campo . ') as conteo 
            from `viv_centropoblado_datass` as `v1` 
            inner join `par_ubigeo` as `v2` on `v2`.`id` = `v1`.`ubigeo_id` 
            where `v1`.`importacion_id` = ' . $importacion_id . $ubicacion . ' 
            group by `v1`.`sistema_disposicion_excretas`
            ) nueva 
            group by servicio 
            order by servicio desc) xxx';
        return $query;
    }
    public static function poblacion_disposicion_excretas($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v2.dependencia=' . $provincia;
        $query = DB::table(DB::raw(CentroPobladoDatassRepositorio::querysPIndicador($importacion_id, 'total_poblacion', $ubicacion, 'SI', 'NO')))
            ->select(DB::raw('cast(y as SIGNED) as y'), DB::raw('name'), DB::raw('FORMAT(y,0) as conteo'))
            ->get();
        return $query;
    }
    public static function viviendas_disposicion_excretas($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v2.dependencia=' . $provincia;
        $query = DB::table(DB::raw(CentroPobladoDatassRepositorio::querysPIndicador($importacion_id, 'viviendas_habitadas', $ubicacion, 'SI', 'NO')))
            ->select(DB::raw('cast(y as SIGNED) as y'), DB::raw('name'), DB::raw('FORMAT(y,0) as conteo'))
            ->get();
        return $query;
    }
    public static function listar_porubigeo($provincia, $distrito, $importacion_id)
    {
        $query = CentroPobladoDatass::select('viv_centropoblado_datass.*')
            ->join('par_ubigeo as v2', 'v2.id', '=', 'viv_centropoblado_datass.ubigeo_id')
            ->where('viv_centropoblado_datass.importacion_id', $importacion_id);
        if ($provincia > 0 && $distrito > 0) $query = $query->where('v2.id', $distrito);
        else if ($provincia > 0 && $distrito == 0) $query = $query->where('v2.dependencia', $provincia);
        $query = $query->get();
        return $query;
    }

    public static function centropoplado_porServicioAgua($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and x2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and x2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select  count(sistema_agua) as conteo,"SI" as servicio from (
            select x1.importacion_id,x1.ubigeo_cp,x1.sistema_agua from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.sistema_agua="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . ' 
            group by x1.importacion_id,x1.ubigeo_cp,x1.sistema_agua) as datass        
            union all      
            select count(sistema_agua) as conteo,"NO" as servicio from (
             select x1.importacion_id,x1.ubigeo_cp,x1.sistema_agua from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.sistema_agua!="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . ' 
            group by x1.importacion_id,x1.ubigeo_cp,x1.sistema_agua) as datass) as v1'))
            ->select(DB::raw('cast(v1.conteo as SIGNED) as y'), DB::raw('v1.servicio as name'))
            ->get();
        return $query;
    }

    public static function centropoplado_porDisposicionExcretas($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and x2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and x2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select  count(sistema_disposicion_excretas) as conteo,"SI" as servicio from (
            select x1.importacion_id,x1.ubigeo_cp,x1.sistema_disposicion_excretas from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.sistema_disposicion_excretas="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . ' 
            group by x1.importacion_id,x1.ubigeo_cp,x1.sistema_disposicion_excretas) as datass        
            union all      
            select count(sistema_disposicion_excretas) as conteo,"NO" as servicio from (
             select x1.importacion_id,x1.ubigeo_cp,x1.sistema_disposicion_excretas from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.sistema_disposicion_excretas!="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '  
            group by x1.importacion_id,x1.ubigeo_cp,x1.sistema_disposicion_excretas) as datass) as ggg'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('servicio as name'))
            ->get();
        return $query;
    }

    public static function centropoplado_porTipoServicioAgua($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v3.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v3.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select  v1.nombre as servicio,count(v1.id) as conteo from viv_tipo_sistema_agua as v1 
        inner join viv_centropoblado_datass as v2 on v2.tipo_sistema_agua_id=v1.id 
        inner join par_ubigeo as v3 on v3.id=v2.ubigeo_id  
        where v1.id in (4,5,1,2)  and v2.importacion_id=' . $importacion_id . $ubicacion . ' 
        group by v1.id,v1.nombre 
        order by v1.id ) as xxx'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('servicio as name'))
            ->get();
        return $query;
    }
    public static function centropoplado_porServicioAguaSINO($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v3.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v3.dependencia=' . $provincia;

        $query = DB::table(DB::raw('(select  provincia,sum(servicio_si) as csi,sum(servicio_no) as cno ,sum(servicio_si)+sum(servicio_no) as total from (
            select provincia,  conteo as servicio_si,0 as servicio_no from (
                select x3.nombre as provincia,count(x1.sistema_agua) as conteo from viv_centropoblado_datass as x1
                inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
                inner join par_ubigeo as x3 on x3.id=x2.dependencia 
                where x1.sistema_agua="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . ' 
                group by x1.ubigeo_cp,x3.nombre 
                ) as datass        
            union all      
            select  provincia, 0 as servicio_si,conteo as servicio_no from (
                select x3.nombre as provincia,count(x1.sistema_agua) as conteo from viv_centropoblado_datass as x1
                inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
                inner join par_ubigeo as x3 on x3.id=x2.dependencia 
                where x1.sistema_agua!="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . ' 
                group by x1.ubigeo_cp,x3.nombre 
                ) as datass
            ) as aux group by provincia) as tb'))
            ->select(DB::raw('cast(csi as SIGNED) as si'), DB::raw('cast(cno as SIGNED) as no'), DB::raw('provincia as categoria'))
            ->get();
        return $query;
    }

    public static function centropoplado_porOrganizacionesComunales($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v3.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v3.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select v1.nombre,count(v1.nombre) as conteo from viv_tipo_organizacion_comunal as v1 
        inner join viv_centropoblado_datass as v2 on v2.tipo_organizacion_comunal_id=v1.id 
        inner join par_ubigeo as v3 on v3.id=v2.ubigeo_id 
        where v1.id in (2,3,4,5,6) and v2.importacion_id=' . $importacion_id . $ubicacion . '
        group by v1.nombre 
        order by v1.nombre) as ggg'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('nombre as name'))
            ->get();
        return $query;
    }
    public static function centropoplado_porTotalAsociados($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v3.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v3.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select v1.nombre,sum(v2.total_asociados) as conteo from viv_tipo_organizacion_comunal as v1 
        inner join viv_centropoblado_datass as v2 on v2.tipo_organizacion_comunal_id=v1.id 
        inner join par_ubigeo as v3 on v3.id=v2.ubigeo_id 
        where v1.id in (2,3,4,5,6) and v2.importacion_id=' . $importacion_id . $ubicacion . '
        group by v1.nombre 
        order by v1.nombre) as ggg'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('nombre as name'))
            ->get();
        return $query;
    }

    public static function centropoplado_porCuotaFamiliar($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and x2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and x2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select  count(cuota_familiar) as conteo,"SI" as servicio from (
            select x1.importacion_id,x1.ubigeo_cp,x1.cuota_familiar from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.cuota_familiar="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '
            group by x1.importacion_id,x1.ubigeo_cp,x1.cuota_familiar) as datass        
            union all      
            select count(cuota_familiar) as conteo,"NO" as servicio from (
             select x1.importacion_id,x1.ubigeo_cp,x1.cuota_familiar from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.cuota_familiar!="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '
            group by x1.importacion_id,x1.ubigeo_cp,x1.cuota_familiar) as datass) as ggg'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('servicio as name'))
            ->get();
        return $query;
    }

    public static function centropoplado_porServicioAguaContinuo($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and x2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and x2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select  count(servicio_agua_continuo) as conteo,"SI" as servicio from (
            select x1.importacion_id,x1.ubigeo_cp,x1.servicio_agua_continuo from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.servicio_agua_continuo="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '
            group by x1.importacion_id,x1.ubigeo_cp,x1.servicio_agua_continuo) as datass        
            union all      
            select count(servicio_agua_continuo) as conteo,"NO" as servicio from (
             select x1.importacion_id,x1.ubigeo_cp,x1.servicio_agua_continuo from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.servicio_agua_continuo!="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '
            group by x1.importacion_id,x1.ubigeo_cp,x1.servicio_agua_continuo) as datass) as ggg'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('servicio as name'))
            ->get();
        return $query;
    }

    public static function centropoplado_porRealizaCloracionAgua($importacion_id, $provincia, $distrito)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and x2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and x2.dependencia=' . $provincia;
        $query = DB::table(DB::raw('(select  count(realiza_cloracion_agua) as conteo,"SI" as servicio from (
            select x1.importacion_id,x1.ubigeo_cp,x1.realiza_cloracion_agua from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.realiza_cloracion_agua="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '
            group by x1.importacion_id,x1.ubigeo_cp,x1.realiza_cloracion_agua) as datass        
            union all      
            select count(realiza_cloracion_agua) as conteo,"NO" as servicio from (
             select x1.importacion_id,x1.ubigeo_cp,x1.realiza_cloracion_agua from viv_centropoblado_datass as x1
            inner join par_ubigeo as x2 on x2.id=x1.ubigeo_id  
            where x1.realiza_cloracion_agua!="SI" and x1.importacion_id=' . $importacion_id . $ubicacion . '
            group by x1.importacion_id,x1.ubigeo_cp,x1.realiza_cloracion_agua) as datass) as ggg'))
            ->select(DB::raw('cast(conteo as SIGNED) as y'), DB::raw('servicio as name'))
            ->get();
        return $query;
    }

    public static function sumas_dashboard($importacion_id)
    {
        $query = CentroPobladoDatass::where('importacion_id', $importacion_id)
            ->select(
                DB::raw('sum(total_poblacion) as poblacion'),
                DB::raw('sum(poblacion_servicio_agua) as con_agua'),
                DB::raw('sum(total_viviendas) as viviendas'),
                DB::raw('sum(viviendas_conexion) as con_conexion')
            )->first();
        return $query;
    }
}
