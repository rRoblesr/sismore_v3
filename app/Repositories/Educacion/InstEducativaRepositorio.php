<?php

namespace App\Repositories\Educacion;

use Illuminate\Support\Facades\DB;

class InstEducativaRepositorio
{
        /* public static function cantidad_activas_inactivas()
        {
                $data = DB::table('edu_institucioneducativa as inst')
                        ->join('edu_estadoinsedu as est', 'inst.EstadoInsEdu_id', '=', 'est.id')
                        ->where('inst.estado', '=', 'ac')
                        ->get([
                                DB::raw('sum( case when est.codigo = 1 then 1 else 0 end ) as activas'),
                                DB::raw('sum( case when est.codigo = 2 then 1 else 0 end ) as inactivas'),
                                DB::raw('sum( 1 ) as total')
                        ]);

                return $data;
        } */



        public static function listar()
        {
                $data = DB::table(
                        DB::raw(
                                "(
                                select  count(distinct codLocal ) as cantidad from
                                edu_institucioneducativa as inst
                                inner join edu_estadoinsedu as est on inst.EstadoInsEdu_id = est.id
                                where(inst.estado='ac') and est.codigo = 1 and anexo = 0
                                ) as datos"
                        )
                )

                        ->get([
                                DB::raw('cantidad')
                        ]);

                return $data;
        }
        public static function cantidad_locales()
        {
                $data = DB::table(
                        DB::raw(
                                "(
                                select  count(distinct codLocal ) as cantidad from
                                edu_institucioneducativa as inst
                                inner join edu_estadoinsedu as est on inst.EstadoInsEdu_id = est.id
                                where(inst.estado='ac') and est.codigo = 1 and anexo = 0
                                ) as datos"
                        )
                )

                        ->get([
                                DB::raw('cantidad')
                        ]);

                return $data;
        }

        public static function total_tipoGestion()
        {
                $data = DB::table(
                        /* DB::raw(
                                "(
                                select
                                sum(case when tipGest.id = 20 then 1 else 0 end) as privada,
                                sum(case when tipGest.id in (16,18,21,22,23) then 1 else 0 end) as publica
                                from edu_institucioneducativa as inst
                                inner join edu_estadoinsedu as est on inst.EstadoInsEdu_id = est.id
                                inner join edu_tipogestion tipGest on inst.TipoGestion_id = tipGest.id
                                where inst.estado = 'AC' and anexo = 0
                                and est.codigo = 1
                                ) as datos"
                        ) */
                        DB::raw(
                                "(
                                        select
                                                sum(case when v2.dependencia in (3) then 1 else 0 end) as privada,
                                                sum(case when v2.dependencia in (1,2) then 1 else 0 end) as publica
                                    from edu_institucioneducativa as v1
                                    inner join edu_tipogestion as v2 on v1.TipoGestion_id = v2.id
                                                                                                ) as datos"
                        )
                )

                        ->get([
                                DB::raw('privada'),
                                DB::raw('publica')
                        ]);

                return $data;
        }


        public static function resumen_porDistrito()
        {
                $data = DB::table('edu_institucioneducativa as inst')
                        ->join('edu_estadoinsedu as est', 'inst.EstadoInsEdu_id', '=', 'est.id')
                        ->join('edu_centropoblado as cenPo', 'inst.CentroPoblado_id', '=', 'cenPo.id')
                        ->join('par_ubigeo as distrito', 'cenPo.Ubigeo_id', '=', 'distrito.id')
                        ->join('par_ubigeo as provincia', 'distrito.dependencia', '=', 'provincia.id')
                        ->where('inst.estado', '=', 'ac')
                        ->orderBy('provincia.codigo', 'asc')
                        ->orderBy('distrito.codigo', 'asc')
                        ->groupBy('provincia.nombre')
                        ->groupBy('distrito.nombre')
                        ->get([
                                DB::raw('provincia.nombre as provincia'),
                                DB::raw('distrito.nombre as distrito'),
                                DB::raw('sum( case when est.codigo = 1 then 1 else 0 end ) as activas'),
                                DB::raw('sum( case when est.codigo = 2 then 1 else 0 end ) as inactivas'),
                                DB::raw('sum( 1 ) as total')
                        ]);

                return $data;
        }

        public static function resumen_porDistrito_tipoGestion()
        {
                $data = DB::table(
                        DB::raw(
                                "(
                                        select provincia.codigo,provincia.nombre as provincia,distrito.nombre as distrito,
                                        sum(case when tipGest.id = 20 then 1 else 0 end) as privada,
                                        sum(case when tipGest.id in (16,18,21,22,23) then 1 else 0 end) as publica
                                        from edu_institucioneducativa as inst
                                        inner join edu_estadoinsedu as est on inst.EstadoInsEdu_id = est.id
                                        inner join edu_tipogestion tipGest on inst.TipoGestion_id = tipGest.id
                                        inner join edu_centropoblado as cenPo on inst.CentroPoblado_id = cenPo.id
                                        inner join par_ubigeo as distrito on cenPo.Ubigeo_id = distrito.id
                                        inner join par_ubigeo as provincia on distrito.dependencia = provincia.id
                                        where inst.estado = 'AC' and anexo = 0
                                        and est.codigo = 1
                                        group by provincia.codigo,provincia.nombre,distrito.nombre
                                ) as datos"
                        )
                )

                        ->get([
                                DB::raw('privada'),
                                DB::raw('publica'),
                                DB::raw('distrito'),
                                DB::raw('provincia'),
                                DB::raw('codigo')
                        ]);

                return $data;
        }

        public static function resumen_porProvincia_tipoGestion()
        {
                $data = DB::table(
                        DB::raw(
                                "(
                                        select region.nombre as region,provincia.codigo,provincia.nombre as provincia,
                                        sum(case when tipGest.id = 20 then 1 else 0 end) as privada,
                                        sum(case when tipGest.id in (16,18,21,22,23) then 1 else 0 end) as publica
                                        from edu_institucioneducativa as inst
                                        inner join edu_estadoinsedu as est on inst.EstadoInsEdu_id = est.id
                                        inner join edu_tipogestion tipGest on inst.TipoGestion_id = tipGest.id
                                        inner join edu_centropoblado as cenPo on inst.CentroPoblado_id = cenPo.id
                                        inner join par_ubigeo as distrito on cenPo.Ubigeo_id = distrito.id
                                        inner join par_ubigeo as provincia on distrito.dependencia = provincia.id
                                        inner join par_ubigeo as region on provincia.dependencia = region.id

                                        where inst.estado = 'AC' and anexo = 0
                                        and est.codigo = 1
                                        group by region.nombre,provincia.codigo,provincia.nombre
                                ) as datos"
                        )
                )

                        ->get([
                                DB::raw('privada'),
                                DB::raw('publica'),
                                DB::raw('provincia'),
                                DB::raw('region'),
                                DB::raw('codigo')
                        ]);

                return $data;
        }


        public static function resumen_porProvincia()
        {
                $data = DB::table('edu_institucioneducativa as inst')
                        ->join('edu_estadoinsedu as est', 'inst.EstadoInsEdu_id', '=', 'est.id')
                        ->join('edu_centropoblado as cenPo', 'inst.CentroPoblado_id', '=', 'cenPo.id')
                        ->join('par_ubigeo as distrito', 'cenPo.Ubigeo_id', '=', 'distrito.id')
                        ->join('par_ubigeo as provincia', 'distrito.dependencia', '=', 'provincia.id')
                        ->join('par_ubigeo as region', 'provincia.dependencia', '=', 'region.id')
                        ->where('inst.estado', '=', 'ac')
                        ->orderBy('provincia.codigo', 'asc')
                        ->groupBy('region.nombre')
                        ->groupBy('provincia.nombre')
                        ->get([
                                DB::raw('region.nombre as region'),
                                DB::raw('provincia.nombre as provincia'),
                                DB::raw('sum( case when est.codigo = 1 then 1 else 0 end ) as activas'),
                                DB::raw('sum( case when est.codigo = 2 then 1 else 0 end ) as inactivas'),
                                DB::raw('sum( 1 ) as total')
                        ]);

                return $data;
        }

        public static function resumen_porRegion()
        {
                $data = DB::table('edu_institucioneducativa as inst')
                        ->join('edu_estadoinsedu as est', 'inst.EstadoInsEdu_id', '=', 'est.id')
                        ->join('edu_centropoblado as cenPo', 'inst.CentroPoblado_id', '=', 'cenPo.id')
                        ->join('par_ubigeo as distrito', 'cenPo.Ubigeo_id', '=', 'distrito.id')
                        ->join('par_ubigeo as provincia', 'distrito.dependencia', '=', 'provincia.id')
                        ->join('par_ubigeo as region', 'provincia.dependencia', '=', 'region.id')
                        ->where('inst.estado', '=', 'ac')
                        ->groupBy('region.nombre')
                        ->get([
                                DB::raw('region.nombre as region'),
                                DB::raw('sum( case when est.codigo = 1 then 1 else 0 end ) as activas'),
                                DB::raw('sum( case when est.codigo = 2 then 1 else 0 end ) as inactivas'),
                                DB::raw('sum( 1 ) as total')
                        ]);

                return $data;
        }

        public static function importaciones_padronweb()
        {
                $data = DB::table(
                        DB::raw(
                                "(
                                        select  distinct imp.fechaActualizacion from edu_padronweb pw
                                        inner join par_importacion imp on pw.importacion_id = imp.id
                                        where imp.estado = 'PR'
                                        order by imp.fechaActualizacion desc
                                ) as datos"
                        )
                )

                        ->get([
                                DB::raw('fechaActualizacion')
                        ]);

                return $data;
        }
}
