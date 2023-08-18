<?php

namespace App\Repositories\Vivienda;

use App\Models\Vivienda\Emapacopsa;
use Illuminate\Support\Facades\DB;

class EmapacopsaRepositorio
{
    public static function listarProvincias()
    {
        $query = DB::table(DB::raw('(select v5.id,v5.nombre  from viv_emapacopsa as v1 
        join viv_manzana as v2 on v2.id=v1.manzana_id 
        join viv_sector as v3 on v3.id=v2.sector_id 
        join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
        join par_ubigeo as v5 on v5.id=v4.dependencia 
        group by v5.id,v5.nombre ) nuevo'))->get();
        return $query;
    }
    public static function listarDistrito($provincia)
    {
        $query = DB::table(DB::raw('(select v4.id,v4.nombre  from viv_emapacopsa as v1 
        join viv_manzana as v2 on v2.id=v1.manzana_id 
        join viv_sector as v3 on v3.id=v2.sector_id 
        join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
        where v4.dependencia=' . $provincia . '
        group by v4.id,v4.nombre ) nuevo'))->get();
        return $query;
    }
    public static function ListarSINO_porIndicador($provincia, $distrito, $indicador_id, $importacion_id)
    {
        switch ($indicador_id) {
            case 24:
                $estadoconexion = '';
                $ubicacion = '';
                //if ($estado_conexion_id != 0) $estadoconexion = ' and v1.estado_conexion_id=' . $estado_conexion_id;
                if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v6.id=' . $distrito;
                else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v6.dependencia=' . $provincia;

                $query['indicador'] = DB::table(DB::raw('(select IF(v2.nombre="DESAGUE","SIN AGUA","CON AGUA") AS servicio,count(v1.id) as conteo  from `viv_emapacopsa` as `v1` 
        inner join `viv_tipo_servicio` as `v2` on `v2`.`id` = `v1`.`tipo_servicio_id` 
        inner join `viv_estado_conexion` as `v3` on `v3`.`id` = `v1`.`estado_conexion_id` 
        inner join `viv_manzana` as `v4` on `v4`.`id` = `v1`.`manzana_id` 
        inner join `viv_sector` as `v5` on `v5`.`id` = `v4`.`sector_id` 
        inner join `par_ubigeo` as `v6` on `v6`.`id` = `v5`.`ubigeo_id`  
        where 1 and v1.importacion_id=' . $importacion_id  . $ubicacion . '
        group by `v2`.`nombre`) as xx'))
                    ->select(DB::raw('xx.servicio as name'), DB::raw('cast(SUM(xx.conteo) as SIGNED) as y'))
                    ->groupBy('xx.servicio')
                    ->get();
                $query['categoriaconagua'] = DB::table(DB::raw(
                    '(select 
                    v7.nombre as categoria, 
                    count(v1.id) as "con_agua",
                    cast(ROUND(count(v1.id)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1,2)),2) as double) as porcentaje_con,
                    cast(SUM(v1.unid_uso) as SIGNED) as "unid_uso" 
                from `viv_emapacopsa` as `v1` 
                inner join `viv_tipo_servicio` as `v2` on `v2`.`id` = `v1`.`tipo_servicio_id`         
                inner join `viv_estado_conexion` as `v3` on `v3`.`id` = `v1`.`estado_conexion_id`   
                inner join `viv_manzana` as `v4` on `v4`.`id` = `v1`.`manzana_id`  
                inner join `viv_sector` as `v5` on `v5`.`id` = `v4`.`sector_id` 
                inner join `par_ubigeo` as `v6` on `v6`.`id` = `v5`.`ubigeo_id` 
                inner join `viv_sub_categoria` as `v7` on `v7`.`id` = `v1`.`sub_categoria_id`    
                WHERE `v1`.`importacion_id`=' . $importacion_id . ' and `v1`.`tipo_servicio_id` in(1,2) 
                group by `v7`.`nombre`) as xx'))->get();

                $query['categoriasinagua'] = DB::table(DB::raw(
                    '(select 
                    v7.nombre as categoria, 
                    count(v1.id) as "sin_agua",
                    cast(ROUND(count(v1.id)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (3)),2) as double) as porcentaje_sin,
                    cast(SUM(v1.unid_uso) as SIGNED) as "unid_uso"
                from `viv_emapacopsa` as `v1` 
                inner join `viv_tipo_servicio` as `v2` on `v2`.`id` = `v1`.`tipo_servicio_id`         
                inner join `viv_estado_conexion` as `v3` on `v3`.`id` = `v1`.`estado_conexion_id`   
                inner join `viv_manzana` as `v4` on `v4`.`id` = `v1`.`manzana_id`  
                inner join `viv_sector` as `v5` on `v5`.`id` = `v4`.`sector_id` 
                inner join `par_ubigeo` as `v6` on `v6`.`id` = `v5`.`ubigeo_id` 
                inner join `viv_sub_categoria` as `v7` on `v7`.`id` = `v1`.`sub_categoria_id`    
                WHERE `v1`.`importacion_id`=' . $importacion_id . ' and `v1`.`tipo_servicio_id` in(3)
                group by `v7`.`nombre`) as xx'))->get();

                $query['filtro1'] = DB::table(DB::raw(
                    '(select v4.nombre as distrito,count(v1.id) as hogares,0 as con_servicio,0 as sin_servicio from viv_emapacopsa as v1 
                join viv_manzana as v2 on v2.id=v1.manzana_id 
                join viv_sector as v3 on v3.id=v2.sector_id 
                join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                WHERE v1.importacion_id=' . $importacion_id . '
                group by v4.nombre 
                union all 
                select v4.nombre as distrito,0 as hogares,count(v1.id) as con_servicio,0 as sin_servicio from viv_emapacopsa as v1 
                join viv_manzana as v2 on v2.id=v1.manzana_id 
                join viv_sector as v3 on v3.id=v2.sector_id 
                join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1,2) 
                group by v4.nombre 
                union all
                select v4.nombre as distrito,0 as hogares,0 as con_servicio,count(v1.id) as sin_servicio from viv_emapacopsa as v1 
                join viv_manzana as v2 on v2.id=v1.manzana_id 
                join viv_sector as v3 on v3.id=v2.sector_id 
                join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (3) 
                group by v4.nombre ) nuevo'
                ))
                    ->select(
                        db::raw('distrito'),
                        DB::raw('cast(SUM(hogares) as SIGNED) as hogares'),
                        DB::raw('cast(ROUND(SUM(con_servicio)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1,2)),2) as double) as porcentaje_con'),
                        DB::raw('cast(SUM(con_servicio) as SIGNED) as con_servicio'),
                        DB::raw('cast(ROUND(SUM(sin_servicio)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (3)),2) as double) as porcentaje_sin'),
                        DB::raw('cast(SUM(sin_servicio) as SIGNED) as sin_servicio')
                    )
                    ->groupBy('distrito')
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw('(select v4.nombre as name,ROUND(count(v1.id)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1,2)),2) as y from viv_emapacopsa as v1 
                    join viv_manzana as v2 on v2.id=v1.manzana_id 
                    join viv_sector as v3 on v3.id=v2.sector_id 
                    join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                    where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1,2) 
                    group by v4.nombre ) filtro'))
                    ->select(DB::raw('name'), DB::raw('cast(y as double) as y'))
                    ->get();
                return $query;
            case 25:
                $estadoconexion = '';
                $ubicacion = '';
                //if ($estado_conexion_id != 0) $estadoconexion = ' and v1.estado_conexion_id=' . $estado_conexion_id;
                if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v6.id=' . $distrito;
                else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v6.dependencia=' . $provincia;

                $query['indicador'] = DB::table(DB::raw('(select IF(v2.nombre="AGUA","SIN DESAGUE","CON DESAGUE") AS servicio,count(v1.id) as conteo  from `viv_emapacopsa` as `v1` 
        inner join `viv_tipo_servicio` as `v2` on `v2`.`id` = `v1`.`tipo_servicio_id` 
        inner join `viv_estado_conexion` as `v3` on `v3`.`id` = `v1`.`estado_conexion_id` 
        inner join `viv_manzana` as `v4` on `v4`.`id` = `v1`.`manzana_id` 
        inner join `viv_sector` as `v5` on `v5`.`id` = `v4`.`sector_id` 
        inner join `par_ubigeo` as `v6` on `v6`.`id` = `v5`.`ubigeo_id`  
        where 1 and v1.importacion_id=' . $importacion_id  . $ubicacion . '
        group by `v2`.`nombre`) as xx'))
                    ->select(DB::raw('xx.servicio as name'), DB::raw('cast(SUM(xx.conteo) as SIGNED) as y'))
                    ->groupBy('xx.servicio')
                    //->orderBy('xx.servicio','desc')
                    ->get();
                $query['categoriaconagua'] = DB::table(DB::raw(
                    '(select 
                    v7.nombre as categoria, 
                    count(v1.id) as "con_agua",
                    cast(ROUND(count(v1.id)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (2,3)),2) as double) as porcentaje_con,
                    cast(SUM(v1.unid_uso) as SIGNED) as "unid_uso"
                from `viv_emapacopsa` as `v1` 
                inner join `viv_tipo_servicio` as `v2` on `v2`.`id` = `v1`.`tipo_servicio_id`         
                inner join `viv_estado_conexion` as `v3` on `v3`.`id` = `v1`.`estado_conexion_id`   
                inner join `viv_manzana` as `v4` on `v4`.`id` = `v1`.`manzana_id`  
                inner join `viv_sector` as `v5` on `v5`.`id` = `v4`.`sector_id` 
                inner join `par_ubigeo` as `v6` on `v6`.`id` = `v5`.`ubigeo_id` 
                inner join `viv_sub_categoria` as `v7` on `v7`.`id` = `v1`.`sub_categoria_id`    
                WHERE `v1`.`importacion_id`=' . $importacion_id . ' and `v1`.`tipo_servicio_id` in(2,3) 
                group by `v7`.`nombre`) as xx'))->get();

                $query['categoriasinagua'] = DB::table(DB::raw(
                    '(select 
                    v7.nombre as categoria, 
                    count(v1.id) as "sin_agua",
                    cast(ROUND(count(v1.id)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1)),2) as double) as porcentaje_sin,
                    cast(SUM(v1.unid_uso) as SIGNED) as "unid_uso"
                from `viv_emapacopsa` as `v1` 
                inner join `viv_tipo_servicio` as `v2` on `v2`.`id` = `v1`.`tipo_servicio_id`         
                inner join `viv_estado_conexion` as `v3` on `v3`.`id` = `v1`.`estado_conexion_id`   
                inner join `viv_manzana` as `v4` on `v4`.`id` = `v1`.`manzana_id`  
                inner join `viv_sector` as `v5` on `v5`.`id` = `v4`.`sector_id` 
                inner join `par_ubigeo` as `v6` on `v6`.`id` = `v5`.`ubigeo_id` 
                inner join `viv_sub_categoria` as `v7` on `v7`.`id` = `v1`.`sub_categoria_id`    
                WHERE `v1`.`importacion_id`=' . $importacion_id . ' and `v1`.`tipo_servicio_id` in(1)
                group by `v7`.`nombre`) as xx'))->get();
                $query['filtro1'] = DB::table(db::raw(
                    '(select v4.nombre as distrito,count(v1.id) as hogares,0 as con_servicio,0 as sin_servicio from viv_emapacopsa as v1 
                join viv_manzana as v2 on v2.id=v1.manzana_id 
                join viv_sector as v3 on v3.id=v2.sector_id 
                join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                WHERE v1.importacion_id=' . $importacion_id . '
                group by v4.nombre 
                union all 
                select v4.nombre as distrito,0 as hogares,count(v1.id) as con_servicio,0 as sin_servicio from viv_emapacopsa as v1 
                join viv_manzana as v2 on v2.id=v1.manzana_id 
                join viv_sector as v3 on v3.id=v2.sector_id 
                join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (2,3) 
                group by v4.nombre 
                union all
                select v4.nombre as distrito,0 as hogares,0 as con_servicio,count(v1.id) as sin_servicio from viv_emapacopsa as v1 
                join viv_manzana as v2 on v2.id=v1.manzana_id 
                join viv_sector as v3 on v3.id=v2.sector_id 
                join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1) 
                group by v4.nombre ) nuevo'
                ))
                    ->select(
                        db::raw('distrito'),
                        DB::raw('cast(SUM(hogares) as SIGNED) as hogares'),
                        DB::raw('cast(ROUND(SUM(con_servicio)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (2,3)),2) as double) as porcentaje_con'),
                        DB::raw('cast(SUM(con_servicio) as SIGNED) as con_servicio'),
                        DB::raw('cast(ROUND(SUM(sin_servicio)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (1)),2) as double) as porcentaje_sin'),
                        DB::raw('cast(SUM(sin_servicio) as SIGNED) as sin_servicio')
                    )
                    ->groupBy('distrito')
                    ->get();
                $query['gfiltro1'] = DB::table(DB::raw('(select v4.nombre as name,ROUND(count(v1.id)*100/(select count(v1.id) as conteo from viv_emapacopsa as v1 where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (2,3)),2) as y from viv_emapacopsa as v1 
                    join viv_manzana as v2 on v2.id=v1.manzana_id 
                    join viv_sector as v3 on v3.id=v2.sector_id 
                    join par_ubigeo as v4 on v4.id=v3.ubigeo_id 
                    where v1.importacion_id=' . $importacion_id . ' and v1.tipo_servicio_id in (2,3) 
                    group by v4.nombre ) filtro'))
                    ->select(DB::raw('name'), DB::raw('cast(y as double) as y'))
                    ->get();
                return $query;

            default:
                return null;
        }
    }
}
