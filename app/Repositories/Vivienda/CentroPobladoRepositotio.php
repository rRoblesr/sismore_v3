<?php

namespace App\Repositories\Vivienda;

use App\Models\Vivienda\Datass;
use Illuminate\Support\Facades\DB;

class CentroPobladoRepositotio
{
    public static $servicios = [
        20 => 'sistema_agua',
        21 => 'sistema_cloracion',
        22 => 'servicio_agua_continuo',
        23 => 'sistema_disposicion_excretas',
        26 => 'realiza_cloracion_agua'
    ];

    public static function anios()
    {
        $data = DB::table(
            DB::raw(
                "(
                                select distinct anio.id,anio.anio from viv_centropoblado_datass cenPo
                                inner join par_importacion imp on cenPo.importacion_id = imp.id
                                inner join par_anio anio on year(imp.fechaActualizacion) = anio.anio
                                where imp.estado = 'PR'   
                                order by anio.anio desc
                        ) as datos"
            )
        )
            ->get();

        return $data;
    }

    public static function fechasPor_anio($anio_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select distinct imp.id,fechaActualizacion from viv_centropoblado_datass cenPo
                                inner join par_importacion imp on cenPo.importacion_id = imp.id
                                inner join par_anio anio on year(imp.fechaActualizacion) = anio.anio
                                where imp.estado = 'PR' and anio.id = $anio_id
                                order by fechaActualizacion desc
                        ) as datos"
            )
        )
            ->get();

        return $data;
    }

    public static function listaPor_Provincia_Distrito($importacion_id)
    {
        $data = DB::table('viv_centropoblado_datass as cenPob')
            ->join('par_ubigeo as dist', 'cenPob.ubigeo_id', '=', 'dist.id')
            ->join('par_ubigeo as prov', 'dist.dependencia', '=', 'prov.id')
            ->where('cenPob.importacion_id', '=', $importacion_id)
            ->orderBy('prov.codigo', 'asc')
            ->orderBy('dist.codigo', 'asc')
            ->groupBy('prov.nombre')
            ->groupBy('dist.nombre')
            ->get([
                DB::raw('prov.nombre as provincia'),
                DB::raw('dist.nombre as distrito'),
                DB::raw('count(*) as total_cenPob'),
                DB::raw('sum(total_viviendas) as total_viviendas'),
                DB::raw('sum(case when tiene_energia_electrica="SI" then 1 else 0 end) as  tiene_energia_electrica'),
                DB::raw('sum(case when tiene_establecimiento_salud="SI" then 1 else 0 end) as  tiene_establecimiento_salud'),
                DB::raw('sum(case when sistema_agua="SI" then 1 else 0 end) as  sistema_agua'),
                DB::raw('sum(case when sistema_disposicion_excretas="SI" then 1 else 0 end) as  sistema_disposicion_excretas'),
                DB::raw('sum(case when sistema_cloracion = "SI" then 1 else 0 end) as  sistema_cloracion')
            ]);

        return $data;
    }
    public static function ListarSINO_porIndicador($provincia, $distrito, $indicador_id, $importacion_id)
    {
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v2.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v2.dependencia=' . $provincia;
        switch ($indicador_id) {
            case 20: //1
                $query['indicador'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPIndicador($importacion_id, 'sistema_agua', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['filtro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPProvincia($importacion_id, 'sistema_agua')))
                    ->select(
                        DB::raw('provincia'),
                        DB::raw('cast(centro_poblado as SIGNED)centro_poblado'),
                        DB::raw('cast(servicio_si as SIGNED)servicio_si'),
                        DB::raw('cast(porcentaje_si as double)porcentaje_si'),
                        DB::raw('cast(servicio_no as SIGNED) servicio_no'),
                        DB::raw('cast(porcentaje_no as double)porcentaje_no')
                    )
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPProvinciaPuntos($importacion_id, 'sistema_agua')))
                    ->select(DB::raw('name'), DB::raw('cast(y as double)y'))
                    ->get();
                break;
            case 21: //2
                $query['indicador'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPIndicador($importacion_id, 'sistema_cloracion', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['indicador2'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPIndicador($importacion_id, 'sistema_cloracion', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['filtro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPProvincia($importacion_id, 'sistema_cloracion')))
                    ->select(
                        DB::raw('provincia'),
                        DB::raw('cast(centro_poblado as SIGNED)centro_poblado'),
                        DB::raw('cast(servicio_si as SIGNED)servicio_si'),
                        DB::raw('cast(porcentaje_si as double)porcentaje_si'),
                        DB::raw('cast(servicio_no as SIGNED) servicio_no'),
                        DB::raw('cast(porcentaje_no as double)porcentaje_no')
                    )
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPProvinciaPuntos($importacion_id, 'sistema_cloracion')))
                    ->select(DB::raw('name'), DB::raw('cast(y as double)y'))
                    ->get();
                break;
            case 22: //3
                $query['indicador'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHIndicador($importacion_id, 'servicio_agua_continuo', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['filtro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHProvincia($importacion_id, 'servicio_agua_continuo')))
                    ->select(
                        DB::raw('provincia'),
                        DB::raw('cast(centro_poblado as SIGNED)centro_poblado'),
                        DB::raw('cast(servicio_si as SIGNED)servicio_si'),
                        DB::raw('cast(porcentaje_si as double)porcentaje_si'),
                        DB::raw('cast(servicio_no as SIGNED) servicio_no'),
                        DB::raw('cast(porcentaje_no as double)porcentaje_no')
                    )
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHProvinciaPuntos($importacion_id, 'servicio_agua_continuo')))
                    ->select(DB::raw('name'), DB::raw('cast(y as double)y'))
                    ->get();
                break;
            case 23: //4
                $query['indicador'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHIndicador($importacion_id, 'sistema_disposicion_excretas', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['indicador2'] = DB::table(DB::raw(CentroPobladoRepositotio::querysCPIndicador($importacion_id, 'sistema_disposicion_excretas', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['filtro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHProvincia($importacion_id, 'sistema_disposicion_excretas')))
                    ->select(
                        DB::raw('provincia'),
                        DB::raw('cast(centro_poblado as SIGNED)centro_poblado'),
                        DB::raw('cast(servicio_si as SIGNED)servicio_si'),
                        DB::raw('cast(porcentaje_si as double)porcentaje_si'),
                        DB::raw('cast(servicio_no as SIGNED) servicio_no'),
                        DB::raw('cast(porcentaje_no as double)porcentaje_no')
                    )
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHProvinciaPuntos($importacion_id, 'sistema_disposicion_excretas')))
                    ->select(DB::raw('name'), DB::raw('cast(y as double)y'))
                    ->get();
                break;
            case 24:
                break;
            case 25:
                break;
            case 26: //5
                $query['indicador'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHIndicador($importacion_id, 'realiza_cloracion_agua', $ubicacion)))
                    ->select(DB::raw('name'), DB::raw('cast(y as SIGNED) y'))
                    ->get();
                $query['filtro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHProvincia($importacion_id, 'realiza_cloracion_agua')))
                    ->select(
                        DB::raw('provincia'),
                        DB::raw('cast(centro_poblado as SIGNED)centro_poblado'),
                        DB::raw('cast(servicio_si as SIGNED)servicio_si'),
                        DB::raw('cast(porcentaje_si as double)porcentaje_si'),
                        DB::raw('cast(servicio_no as SIGNED) servicio_no'),
                        DB::raw('cast(porcentaje_no as double)porcentaje_no')
                    )
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw(CentroPobladoRepositotio::querysHProvinciaPuntos($importacion_id, 'realiza_cloracion_agua')))
                    ->select(DB::raw('name'), DB::raw('cast(y as double)y'))
                    ->get();
                break;

            default:
                break;
        }
        return $query;
    }
    public static function querysCPIndicador($importacion_id, $campo, $ubicacion)
    {
        $query = '(select servicio as name,SUM(conteo) as y from (
            select IF(v1.' . $campo . '="SI","SI","NO") as servicio, count(v1.id) as conteo 
            from `viv_centropoblado_datass` as `v1` 
            inner join `par_ubigeo` as `v2` on `v2`.`id` = `v1`.`ubigeo_id` 
            where `v1`.`importacion_id` = ' . $importacion_id . $ubicacion . ' 
            group by `v1`.`' . $campo . '`
            ) nueva 
            group by servicio 
            order by servicio desc) xxx';
        return $query;
    }
    public static function querysHIndicador($importacion_id, $campo, $ubicacion)
    {
        $query = '(select servicio as name,SUM(conteo) as y from (
            select IF(v1.' . $campo . '="SI","SI","NO") as servicio, SUM(v1.total_viviendas) as conteo 
            from `viv_centropoblado_datass` as `v1` 
            inner join `par_ubigeo` as `v2` on `v2`.`id` = `v1`.`ubigeo_id` 
            where `v1`.`importacion_id` = ' . $importacion_id . $ubicacion . ' 
            group by `v1`.`' . $campo . '`
            ) nueva 
            group by servicio 
            order by servicio desc) xxx';
        return $query;
    }
    public static function querysCPProvincia($importacion_id, $campo)
    {
        $query = '(select 
        provincia,sum(centro_poblado) as centro_poblado ,
        sum(servicio_si) as servicio_si,
        ROUND(sum(servicio_si)*100/(
            select count(v1.id)
            from viv_centropoblado_datass as v1 
            join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
            join par_ubigeo as v3 on v3.id = v2.dependencia 
            where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" ),2) as porcentaje_si,
        SUM(servicio_no) as servicio_no,
        ROUND(sum(servicio_no)*100/(
            select count(v1.id)
            from viv_centropoblado_datass as v1 
            join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
            join par_ubigeo as v3 on v3.id = v2.dependencia 
            where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '!="SI" ),2) as porcentaje_no
    from (
        select v3.nombre as provincia,count(v1.id) as centro_poblado, 0 as servicio_si ,0 as servicio_no
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' 
        group by v3.nombre
        union all
        select v3.nombre as provincia,0 as centro_poblado, count(v1.id) as servicio_si ,0 as servicio_no
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" 
        group by v3.nombre,v1.' . $campo . '
        union all
        select v3.nombre as provincia,0 as centro_poblado,0 as servicio_si, count(v1.id) as servicio_no 
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '!="SI" 
        group by v3.nombre,v1.' . $campo . '
        ) as filtro 
    group by provincia) nueva';
        return $query;
    }
    public static function querysCPProvinciaPuntos($importacion_id, $campo)
    {
        $query = '(select 
        provincia as name,
        ROUND(sum(servicio_si)*100/(
            select count(v1.id)
            from viv_centropoblado_datass as v1 
            join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
            join par_ubigeo as v3 on v3.id = v2.dependencia 
            where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" ),2) as y 
    from (
        select v3.nombre as provincia,count(v1.id) as servicio_si 
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" 
        group by v3.nombre,v1.' . $campo . ' 
        ) as filtro 
    group by provincia) nueva';
        return $query;
    }

    public static function querysHProvincia($importacion_id, $campo)
    {
        $query = '(select 
        provincia,sum(centro_poblado) as centro_poblado ,
        sum(servicio_si) as servicio_si,
        ROUND(sum(servicio_si)*100/(
            select SUM(v1.total_viviendas)
            from viv_centropoblado_datass as v1 
            join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
            join par_ubigeo as v3 on v3.id = v2.dependencia 
            where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" ),2) as porcentaje_si,
        SUM(servicio_no) as servicio_no,
        ROUND(sum(servicio_no)*100/(
            select SUM(v1.total_viviendas)
            from viv_centropoblado_datass as v1 
            join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
            join par_ubigeo as v3 on v3.id = v2.dependencia 
            where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '!="SI" ),2) as porcentaje_no
    from (
        select v3.nombre as provincia,SUM(v1.total_viviendas) as centro_poblado, 0 as servicio_si ,0 as servicio_no
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' 
        group by v3.nombre
        union all
        select v3.nombre as provincia,0 as centro_poblado, SUM(v1.total_viviendas) as servicio_si ,0 as servicio_no
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" 
        group by v3.nombre,v1.' . $campo . '
        union all
        select v3.nombre as provincia,0 as centro_poblado,0 as servicio_si, SUM(v1.total_viviendas) as servicio_no 
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '!="SI" 
        group by v3.nombre,v1.' . $campo . '
        ) as filtro 
    group by provincia) nueva';
        return $query;
    }
    public static function querysHProvinciaPuntos($importacion_id, $campo)
    {
        $query = '(select 
        provincia as name,
        ROUND(sum(servicio_si)*100/(
            select SUM(v1.total_viviendas)
            from viv_centropoblado_datass as v1 
            join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
            join par_ubigeo as v3 on v3.id = v2.dependencia 
            where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" ),2) as y 
    from (
        select v3.nombre as provincia,SUM(v1.total_viviendas) as servicio_si 
        from viv_centropoblado_datass as v1 
        join par_ubigeo as v2 on v2.id = v1.ubigeo_id 
        join par_ubigeo as v3 on v3.id = v2.dependencia 
        where v1.importacion_id = ' . $importacion_id . ' and v1.' . $campo . '="SI" 
        group by v3.nombre,v1.' . $campo . '
        ) as filtro 
    group by provincia) nueva';
        return $query;
    }

    public static function listar_porProvinciaDistrito($provincia, $distrito, $importacion_id, $indicador_id)
    {
        $buscar = CentroPobladoRepositotio::$servicios[$indicador_id];
        $query = DB::table('viv_centropoblado_datass as v1')
            ->select('v1.id', 'v1.nombre as cp', 'v1.total_poblacion', 'v3.nombre as provincia', 'v2.nombre as distrito', DB::raw('if(' . $buscar . '="SI",' . $buscar . ',"NO") as servicio'))
            ->join('par_ubigeo as v2', 'v2.id', '=', 'v1.ubigeo_id')
            ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.dependencia')
            ->where('v1.importacion_id', $importacion_id);
        if ($provincia > 0 && $distrito > 0) {
            $query = $query->where('v2.id', $distrito);
        } else if ($provincia > 0 && $distrito == 0) {
            $query = $query->where('v3.id', $provincia);
        } else {
        }
        $query = $query->orderBy('v3.nombre')->orderBy('v2.nombre')->orderBy('v1.nombre');
        $query = $query->get();
        return $query;
    }
    public static function listarporprovincias($importacion_id)
    {
        $query = DB::table(DB::raw('(SELECT v3.nombre as name, count(v3.id) as conteo, round(count(v3.id)*100/(select count(id) from viv_centropoblado_datass where importacion_id=' . $importacion_id . '),2) as y 
        FROM viv_centropoblado_datass as v1 
        INNER JOIN par_ubigeo as v2 ON v2.id=v1.ubigeo_id 
        INNER JOIN par_ubigeo as v3 ON v3.id=v2.dependencia  
        where v1.importacion_id=' . $importacion_id . ' 
        group by v3.nombre 
        order by v3.nombre) AS xxx'))
            //->select(DB::raw('name'), DB::raw('conteo'), DB::raw('cast(y as DOUBLE) y'))
            ->select(DB::raw('name'), DB::raw('conteo as y'), DB::raw('cast(y as DOUBLE) as conteo'))
            ->get();
        return $query;
    }
    public static function listarporprovinciasconsistemaagua($importacion_id)
    {
        $query = DB::table(DB::raw('(
            SELECT name, sum(SI) as SI,sum(NO) as NO,ROUND(sum(SI)*100/(sum(SI)+sum(NO)),2) as PorSI,ROUND(sum(NO)*100/(sum(SI)+sum(NO)),2) as PorNO FROM (
            SELECT v3.nombre AS name, COUNT(v1.id) AS SI, 0 as NO  FROM viv_centropoblado_datass as v1
            INNER JOIN par_ubigeo as v2 ON v2.id = v1.ubigeo_id 
            INNER JOIN par_ubigeo as v3 ON v3.id = v2.dependencia 
            WHERE v1.importacion_id = ' . $importacion_id . ' AND v1.sistema_agua="SI" 
            GROUP BY v3.nombre,v1.sistema_agua 
            union all 
            SELECT v3.nombre AS name, 0 as SI, COUNT(v1.id) AS NO FROM viv_centropoblado_datass as v1 
            INNER JOIN par_ubigeo as v2 ON v2.id = v1.ubigeo_id 
            INNER JOIN par_ubigeo as v3 ON v3.id = v2.dependencia 
            WHERE v1.importacion_id = ' . $importacion_id . ' AND v1.sistema_agua!="SI" 
            GROUP BY v3.nombre,v1.sistema_agua) AS XXX 
        GROUP BY name
        ) AS xxx'))
            ->select(DB::raw('name'), DB::raw('SI as conteo'), DB::raw('cast(PorSi as DOUBLE) y'))
            //->select(DB::raw('name'),DB::raw('conteo as y'), DB::raw('cast(y as DOUBLE) as conteo'))
            ->get();
        return $query;
    }
   
}
