<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaAnual;
use App\Models\Educacion\PadronRER;
use Illuminate\Support\Facades\DB;

class MatriculaRepositorio
{
    public static function datos_matricula_importada($matricula_id)
    {
        $data = DB::table("edu_matricula_detalle")
            ->where("matricula_id", "=", $matricula_id)
            ->orderBy('nivel', 'asc')
            ->groupBy("nivel")
            ->get([
                DB::raw('(case when nivel="I" then "1. INICIAL"
                                   when nivel="P" then "2. PRIMARIA"
                                   when nivel="S" then "3. SECUNDARIA"  else "4. EBE" end) as nivel'),
                DB::raw('count(*) as numeroFilas')
            ]);

        return $data;
    }

    public static function datos_matricula($matricula_id)
    {
        $data = Matricula::select('imp.fechaactualizacion')
            ->join('par_importacion as imp', 'edu_matricula.importacion_id', '=', 'imp.id')
            ->where("edu_matricula.id", "=", $matricula_id)
            ->get();

        return $data;
    }

    public static function matricula_porImportacion($importacion_id)
    {
        $data = Matricula::select('id', 'estado')
            ->where("importacion_id", "=", $importacion_id)
            ->get();

        return $data;
    }

    public static function matricula_mas_actual()
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_matricula as mat', 'imp.id', '=', 'mat.importacion_id')
            ->join('par_anio as vanio', 'mat.anio_id', '=', 'vanio.id')
            ->where('imp.estado', '=', 'PR')
            ->where('mat.estado', '=', 'PR')
            ->orderBy('vanio.anio', 'desc')
            ->orderBy('imp.fechaActualizacion', 'desc')
            ->select('mat.id', 'imp.fechaActualizacion')
            ->limit(1)
            ->get();

        return $data;
    }

    public static function matriculas_anio()
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_matricula as mat', 'imp.id', '=', 'mat.importacion_id')
            ->join('par_anio as vanio', 'mat.anio_id', '=', 'vanio.id')
            ->where('imp.estado', '=', 'PR')
            ->where('mat.estado', '=', 'PR')
            ->orderBy('vanio.anio', 'asc')
            ->select('vanio.id', 'vanio.anio')
            ->distinct()
            ->get();

        return $data;
    }

    public static function fechas_matriculas_anio($anio_id)
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_matricula as mat', 'imp.id', '=', 'mat.importacion_id')
            ->join('par_anio as vanio', 'mat.anio_id', '=', 'vanio.id')
            ->where('vanio.id', '=', $anio_id)
            ->where('imp.estado', '=', 'PR')
            ->where('mat.estado', '=', 'PR')
            ->orderBy('imp.fechaActualizacion', 'desc')
            ->select('mat.id as matricula_id', 'imp.fechaActualizacion', 'vanio.id', 'vanio.anio')
            ->get();

        return $data;
    }

    public static function total_matricula_EBR($matricula_id, $condicion, $filtro)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            ->where('mat.id', '=', $matricula_id)
            ->where('matDet.nivel', '!=', 'E')
            ->$condicion("inst.tipogestion_id", [$filtro])
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy('ugel.nombre')
            ->groupBy('ugel.id')
            ->get([
                DB::raw('ugel.nombre'),
                DB::raw('ugel.id'),
                DB::raw('count(*) as cantInstituciones'),
                DB::raw('case when ugel.id = 10 then "bg-primary"
                                when ugel.id = 11 then "bg-info"
                                when ugel.id = 12 then "bg-primary" else "bg-info" end as color'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

            ]);

        return $data;
    }

    /** DESARROLLO EBE */

    // public static function total_matricula_por_Nivel_Institucion($matricula_id,$nivel,$condicion, $filtro)
    // {
    //     $data = DB::table(
    //                     DB::raw("(
    //                             select ugel.nombre as ugel,case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
    //                             dist.nombre as distrito,cenPo.nombre as cenPo,inst.codModular,inst.anexo,inst.nombreInstEduc,tipoGestion.nombre as tipoGestion,
    //                             tipoGestionCab.nombre as tipoGestionCab,forma.nombre as forma,caracteristica.nombre as caracteristica, areas.nombre as areas,
    //                             prov.codigo,nivel,total_estudiantes_matriculados,
    //                             cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
    //                             tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
    //                             sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
    //                             cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
    //                             cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
    //                             cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
    //                             from edu_matricula mat
    //                             inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
    //                             inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
    //                             inner join edu_ugel ugel on inst.Ugel_id = ugel.id
    //                             inner join edu_tipogestion tipoGestion on inst.TipoGestion_id = tipoGestion.id
    //                             inner join edu_tipogestion tipoGestionCab on tipoGestion.dependencia = tipoGestionCab.id
    //                             inner join edu_forma forma on inst.Forma_id = forma.id
    //                             inner join edu_caracteristica caracteristica on inst.Caracteristica_id = caracteristica .id
    //                             inner join edu_area areas on inst.Area_id = areas.id
    //                             inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
    //                             inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
    //                             inner join par_ubigeo prov on dist.dependencia = prov.id
    //                             where mat.id = '$matricula_id' and nivel = '$nivel'
    //                             and inst.tipogestion_id $condicion ($filtro)
    //                             order by ugel.codigo
    //                         ) as datos"
    //                     )
    //                 )

    //             ->orderBy('nombreInstEduc', 'asc')
    //             ->get(
    //                 //[
    //                 // DB::raw('ugel'),
    //                 // ]
    //             );


    //     return $data;

    // }



    public static function total_matricula_EBE($matricula_id, $condicion, $filtro)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            ->where('mat.id', '=', $matricula_id)
            ->where('matDet.nivel', '=', 'E')
            ->$condicion("inst.tipogestion_id", [$filtro])
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy('ugel.nombre')
            ->groupBy('ugel.id')
            ->get([
                DB::raw('ugel.nombre'),
                DB::raw('ugel.id'),
                DB::raw('count(*) as cantInstituciones'),
                DB::raw('case when ugel.id = 10 then "bg-primary"
                                when ugel.id = 11 then "bg-info"
                                when ugel.id = 12 then "bg-primary" else "bg-info" end as color'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

            ]);

        return $data;
    }

    public static function total_matricula_EBE_porUgeles($matricula_id, $condicion, $filtro)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select id,codigo,nombre,
                                sum(case when nivel = 'I' then cantidad else 0 end ) as inicial,
                                sum(case when nivel = 'P' then cantidad else 0 end ) as primaria,
                                sum(case when nivel = 'S' then cantidad else 0 end ) as secundaria,
                                sum(case when nivel = 'E' then cantidad else 0 end ) as EBE

                                from (
                                        select ugel.id,ugel.codigo,ugel.nombre,matDet.nivel ,
                                        sum(
                                            ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                            ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                            ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                            ifnull(cinco_anios_hombre_ebe,0)+
                                            ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                            ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                            ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                            ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                            ) as cantidad
                                        from edu_matricula mat
                                        inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                        inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                        inner join edu_ugel ugel on inst.Ugel_id = ugel.id
                                        where mat.id = $matricula_id
                                        and inst.tipogestion_id $condicion ($filtro)
                                        and matDet.nivel = 'E'
                                        group by ugel.id,ugel.codigo,ugel.nombre,matDet.nivel
                                ) as datos
                                group by id,codigo,nombre
                                order by codigo
                        ) as datos"
            )
        )

            ->orderBy('codigo', 'asc')
            ->get();
        return $data;
    }

    public static function total_matricula_EBE_Provincia($matricula_id, $condicion, $filtro)
    {
        //->where('matDet.nivel','!=','E')

        $data = DB::table(
            DB::raw("(
                            select dist.nombre as distrito,prov.codigo,nivel,
                            case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                            cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                            tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                            sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                            cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                            cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                            cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                            from edu_matricula mat
                            inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                            inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                            inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                            inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                            inner join par_ubigeo prov on dist.dependencia = prov.id
                            where matDet.nivel = 'E' and  mat.id = $matricula_id
                            and inst.tipogestion_id $condicion ($filtro)
                        ) as datos")
        )

            ->orderBy('codigo', 'asc')
            ->groupBy('provincia')
            ->get([
                DB::raw('provincia'),

                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),
            ]);

        return $data;
    }

    public static function total_matricula_anual_EBE($anio_id, $condicion, $filtro)
    {
        //->where('matDet.nivel','!=','E')

        $data = DB::table(
            DB::raw("(
                            select fechaactualizacion,sum(cantidad) as cantTotal ,
                            sum(case when id = 10 then cantidad else 0 end ) as ugel10,
                            sum(case when id = 11 then cantidad else 0 end ) as ugel11,
                            sum(case when id = 12 then cantidad else 0 end ) as ugel12,
                            sum(case when id = 13 then cantidad else 0 end ) as ugel13
                            from (

                                    select
                                    fechaactualizacion,
                                    ugel.id,
                                    sum(
                                        ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                        ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                        ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                        ifnull(cinco_anios_hombre_ebe,0) + ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                        ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                        ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                        ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                        ) as cantidad

                                        from par_importacion as imp
                                    inner join edu_matricula as mat on imp.id = mat.importacion_id
                                    inner join edu_matricula_detalle as matDet on mat.id = matDet.matricula_id
                                    inner join edu_institucioneducativa as inst on matDet.institucioneducativa_id = inst.id
                                    inner join edu_ugel as ugel on inst.Ugel_id = ugel.id
                                    where mat.anio_id = '$anio_id' and matDet.nivel = 'E' and imp.estado = 'PR'
                                    and inst.tipogestion_id $condicion ($filtro)
                                    group By fechaactualizacion,ugel.id

                            ) as dd
                            group by fechaactualizacion

                        ) as datos")
        )

            // ->orderBy('codigo', 'asc')
            // ->groupBy('provincia')
            ->get([
                DB::raw('fechaactualizacion'),
                DB::raw('ugel10'),
                DB::raw('ugel11'),
                DB::raw('ugel12'),
                DB::raw('ugel13')

            ]);

        return $data;
    }

    /***************************************************************/

    public static function total_matricula_EIB($matricula_id, $condicion, $filtro)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            // ->join('edu_padron_eib as eib', 'inst.codModular', '=', 'eib.codModular')
            ->where('mat.id', '=', $matricula_id)
            ->where('matDet.nivel', '!=', 'E')
            ->where('inst.es_eib', '=', 'SI')
            ->$condicion("inst.tipogestion_id", [$filtro])
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy('ugel.nombre')
            ->groupBy('ugel.id')
            ->get([
                DB::raw('ugel.nombre'),
                DB::raw('ugel.id'),
                DB::raw('count(*) as cantInstituciones'),
                DB::raw('case when ugel.id = 10 then "bg-primary"
                                when ugel.id = 11 then "bg-info"
                                when ugel.id = 12 then "bg-primary" else "bg-info" end as color'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

            ]);

        return $data;
    }

    public static function total_matricula_EIB_porUgeles($matricula_id, $condicion, $filtro)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select id,codigo,nombre,
                                sum(case when nivel = 'I' then cantidad else 0 end ) as inicial,
                                sum(case when nivel = 'P' then cantidad else 0 end ) as primaria,
                                sum(case when nivel = 'S' then cantidad else 0 end ) as secundaria

                                from (
                                        select ugel.id,ugel.codigo,ugel.nombre,matDet.nivel ,
                                        sum(
                                            ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                            ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                            ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                            ifnull(cinco_anios_hombre_ebe,0)+
                                            ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                            ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                            ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                            ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                            ) as cantidad
                                        from edu_matricula mat
                                        inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                        inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                        inner join edu_ugel ugel on inst.Ugel_id = ugel.id

                                        /* inner join edu_padron_eib as eib on inst.codModular = eib.codModular  */

                                        where mat.id = $matricula_id
                                        and inst.tipogestion_id $condicion ($filtro)
                                        and inst.es_eib = 'SI'
                                        /*and matDet.nivel in ('I','P','S')*/
                                        group by ugel.id,ugel.codigo,ugel.nombre,matDet.nivel
                                ) as datos
                                group by id,codigo,nombre
                                order by codigo
                        ) as datos"
            )
        )

            ->orderBy('codigo', 'asc')
            ->get();
        return $data;
    }

    public static function total_matricula_EIB_Provincia($matricula_id, $condicion, $filtro)
    {
        //->where('matDet.nivel','!=','E')

        $data = DB::table(
            DB::raw("(
                            select dist.nombre as distrito,prov.codigo,nivel,
                            case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                            cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                            tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                            sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                            cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                            cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                            cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                            from edu_matricula mat
                            inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                            inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                            inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                            inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                            inner join par_ubigeo prov on dist.dependencia = prov.id

                            /*inner join edu_padron_eib as eib on inst.codModular = eib.codModular */

                            where matDet.nivel != 'E' and  mat.id = $matricula_id
                            and inst.tipogestion_id $condicion ($filtro)
                            and inst.es_eib = 'SI'
                        ) as datos")
        )

            ->orderBy('codigo', 'asc')
            ->groupBy('provincia')
            ->get([
                DB::raw('provincia'),

                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),
            ]);

        return $data;
    }

    public static function total_matricula_por_Nivel_Institucion_EIB($matricula_id, $nivel, $condicion, $filtro)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select ugel.nombre as ugel,case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                                dist.nombre as distrito,cenPo.nombre as cenPo,inst.codModular,inst.anexo,inst.nombreInstEduc,tipoGestion.nombre as tipoGestion,
                                tipoGestionCab.nombre as tipoGestionCab,forma.nombre as forma,caracteristica.nombre as caracteristica, areas.nombre as areas,
                                prov.codigo,nivel,total_estudiantes_matriculados,
                                cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                                tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                                sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                                cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                                cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                                cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                                from edu_matricula mat
                                inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                inner join edu_ugel ugel on inst.Ugel_id = ugel.id
                                inner join edu_tipogestion tipoGestion on inst.TipoGestion_id = tipoGestion.id
                                inner join edu_tipogestion tipoGestionCab on tipoGestion.dependencia = tipoGestionCab.id
                                inner join edu_forma forma on inst.Forma_id = forma.id
                                inner join edu_caracteristica caracteristica on inst.Caracteristica_id = caracteristica .id
                                inner join edu_area areas on inst.Area_id = areas.id
                                inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                                inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                                inner join par_ubigeo prov on dist.dependencia = prov.id

                                /*inner join edu_padron_eib as eib on inst.codModular = eib.codModular */

                                where mat.id = '$matricula_id' and nivel = '$nivel'
                                and inst.tipogestion_id $condicion ($filtro)
                                and inst.es_eib = 'SI'
                                order by ugel.codigo
                            ) as datos"
            )
        )

            ->orderBy('nombreInstEduc', 'asc')
            ->get(
                //[
                // DB::raw('ugel'),
                // ]
            );


        return $data;
    }

    public static function total_matricula_por_Nivel_Provincia_EIB($matricula_id, $condicion, $filtro)
    {
        $data = DB::table(

            DB::raw(
                "(
                            select dist.nombre as distrito,prov.codigo,nivel,
                            case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                            cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                            tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                            sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                            cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                            cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                            cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                            from edu_matricula mat
                            inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                            inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                            inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                            inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                            inner join par_ubigeo prov on dist.dependencia = prov.id

                            /*inner join edu_padron_eib as eib on inst.codModular = eib.codModular*/

                            where mat.id = '$matricula_id'
                            and inst.tipogestion_id $condicion ($filtro)
                            and inst.es_eib = 'SI'
                        ) as datos"
            )

        )

            ->orderBy('codigo', 'asc')
            ->groupBy('provincia')
            ->groupBy('nivel')

            ->get([

                DB::raw('provincia'),
                DB::raw('nivel'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

                DB::raw('sum( ifnull(cero_nivel_hombre,0) ) as cero_nivel_hombre'),
                DB::raw('sum( ifnull(primer_nivel_hombre,0) ) as primer_nivel_hombre'),
                DB::raw('sum( ifnull(segundo_nivel_hombre,0) ) as segundo_nivel_hombre'),
                DB::raw('sum( ifnull(tercero_nivel_hombre,0) ) as tercero_nivel_hombre'),
                DB::raw('sum( ifnull(cuarto_nivel_hombre,0) ) as cuarto_nivel_hombre'),
                DB::raw('sum( ifnull(quinto_nivel_hombre,0) ) as quinto_nivel_hombre'),
                DB::raw('sum( ifnull(sexto_nivel_hombre,0) ) as sexto_nivel_hombre'),

                DB::raw('sum( ifnull(tres_anios_hombre_ebe,0) ) as tres_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_hombre_ebe,0) ) as cuatro_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cinco_anios_hombre_ebe,0) ) as cinco_anios_hombre_ebe'),

                DB::raw('sum( ifnull(cero_nivel_mujer,0) ) as cero_nivel_mujer'),
                DB::raw('sum( ifnull(primer_nivel_mujer,0) ) as primer_nivel_mujer'),
                DB::raw('sum( ifnull(segundo_nivel_mujer,0) ) as segundo_nivel_mujer'),
                DB::raw('sum( ifnull(tercero_nivel_mujer,0) ) as tercero_nivel_mujer'),
                DB::raw('sum( ifnull(cuarto_nivel_mujer,0) ) as cuarto_nivel_mujer'),
                DB::raw('sum( ifnull(quinto_nivel_mujer,0) ) as quinto_nivel_mujer'),
                DB::raw('sum( ifnull(sexto_nivel_mujer,0) ) as sexto_nivel_mujer'),

                DB::raw('sum( ifnull(tres_anios_mujer_ebe,0) ) as tres_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_mujer_ebe,0) ) as cuatro_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cinco_anios_mujer_ebe,0) ) as cinco_anios_mujer_ebe'),

            ]);


        return $data;
    }

    public static function total_matricula_por_Nivel_Distrito_EIB($matricula_id, $condicion, $filtro)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_centropoblado as cenPo', 'inst.CentroPoblado_id', '=', 'cenPo.id')
            // ->join('edu_padron_eib as eib', 'inst.codModular', '=', 'eib.codModular')
            ->join('par_ubigeo as dist', 'cenPo.ubigeo_id', '=', 'dist.id')
            ->join('par_ubigeo as prov', 'dist.dependencia', '=', 'prov.id')

            ->where('mat.id', '=', $matricula_id)
            ->where('inst.es_eib', '=', 'SI')
            ->$condicion("inst.tipogestion_id", [$filtro])
            //->where('matDet.nivel','=',$nivel)
            ->orderBy('dist.codigo', 'asc')
            ->groupBy('prov.nombre')
            ->groupBy('dist.nombre')
            ->groupBy('nivel')
            ->get([
                DB::raw('case when dist.nombre = "YURUA" then "CORONEL PORTILLO" else prov.nombre end as provincia'),
                DB::raw('dist.nombre as distrito'),
                DB::raw('nivel'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

                DB::raw('sum( ifnull(cero_nivel_hombre,0) ) as cero_nivel_hombre'),
                DB::raw('sum( ifnull(primer_nivel_hombre,0) ) as primer_nivel_hombre'),
                DB::raw('sum( ifnull(segundo_nivel_hombre,0) ) as segundo_nivel_hombre'),
                DB::raw('sum( ifnull(tercero_nivel_hombre,0) ) as tercero_nivel_hombre'),
                DB::raw('sum( ifnull(cuarto_nivel_hombre,0) ) as cuarto_nivel_hombre'),
                DB::raw('sum( ifnull(quinto_nivel_hombre,0) ) as quinto_nivel_hombre'),
                DB::raw('sum( ifnull(sexto_nivel_hombre,0) ) as sexto_nivel_hombre'),

                DB::raw('sum( ifnull(tres_anios_hombre_ebe,0) ) as tres_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_hombre_ebe,0) ) as cuatro_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cinco_anios_hombre_ebe,0) ) as cinco_anios_hombre_ebe'),

                DB::raw('sum( ifnull(cero_nivel_mujer,0) ) as cero_nivel_mujer'),
                DB::raw('sum( ifnull(primer_nivel_mujer,0) ) as primer_nivel_mujer'),
                DB::raw('sum( ifnull(segundo_nivel_mujer,0) ) as segundo_nivel_mujer'),
                DB::raw('sum( ifnull(tercero_nivel_mujer,0) ) as tercero_nivel_mujer'),
                DB::raw('sum( ifnull(cuarto_nivel_mujer,0) ) as cuarto_nivel_mujer'),
                DB::raw('sum( ifnull(quinto_nivel_mujer,0) ) as quinto_nivel_mujer'),
                DB::raw('sum( ifnull(sexto_nivel_mujer,0) ) as sexto_nivel_mujer'),

                DB::raw('sum( ifnull(tres_anios_mujer_ebe,0) ) as tres_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_mujer_ebe,0) ) as cuatro_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cinco_anios_mujer_ebe,0) ) as cinco_anios_mujer_ebe'),

            ]);


        return $data;
    }

    public static function total_matricula_por_Nivel_EIB($matricula_id)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            // ->join('edu_padron_eib as eib', 'inst.codModular', '=', 'eib.codModular')
            ->where('mat.id', '=', $matricula_id)
            ->where('inst.es_eib', '=', 'SI')
            //->where('matDet.nivel','=',$nivel)
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy('ugel.nombre')
            ->groupBy('nivel')
            ->get([
                DB::raw('ugel.nombre'),
                DB::raw('nivel'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

                DB::raw('sum( ifnull(cero_nivel_hombre,0) ) as cero_nivel_hombre'),
                DB::raw('sum( ifnull(primer_nivel_hombre,0) ) as primer_nivel_hombre'),
                DB::raw('sum( ifnull(segundo_nivel_hombre,0) ) as segundo_nivel_hombre'),
                DB::raw('sum( ifnull(tercero_nivel_hombre,0) ) as tercero_nivel_hombre'),
                DB::raw('sum( ifnull(cuarto_nivel_hombre,0) ) as cuarto_nivel_hombre'),
                DB::raw('sum( ifnull(quinto_nivel_hombre,0) ) as quinto_nivel_hombre'),
                DB::raw('sum( ifnull(sexto_nivel_hombre,0) ) as sexto_nivel_hombre'),

                DB::raw('sum( ifnull(tres_anios_hombre_ebe,0) ) as tres_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_hombre_ebe,0) ) as cuatro_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cinco_anios_hombre_ebe,0) ) as cinco_anios_hombre_ebe'),

                DB::raw('sum( ifnull(cero_nivel_mujer,0) ) as cero_nivel_mujer'),
                DB::raw('sum( ifnull(primer_nivel_mujer,0) ) as primer_nivel_mujer'),
                DB::raw('sum( ifnull(segundo_nivel_mujer,0) ) as segundo_nivel_mujer'),
                DB::raw('sum( ifnull(tercero_nivel_mujer,0) ) as tercero_nivel_mujer'),
                DB::raw('sum( ifnull(cuarto_nivel_mujer,0) ) as cuarto_nivel_mujer'),
                DB::raw('sum( ifnull(quinto_nivel_mujer,0) ) as quinto_nivel_mujer'),
                DB::raw('sum( ifnull(sexto_nivel_mujer,0) ) as sexto_nivel_mujer'),

                DB::raw('sum( ifnull(tres_anios_mujer_ebe,0) ) as tres_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_mujer_ebe,0) ) as cuatro_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cinco_anios_mujer_ebe,0) ) as cinco_anios_mujer_ebe'),

            ]);

        return $data;
    }



    public static function total_matricula_EBR_porUgeles($matricula_id, $condicion, $filtro)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select id,codigo,nombre,
                                sum(case when nivel = 'I' then cantidad else 0 end ) as inicial,
                                sum(case when nivel = 'P' then cantidad else 0 end ) as primaria,
                                sum(case when nivel = 'S' then cantidad else 0 end ) as secundaria

                                from (
                                        select ugel.id,ugel.codigo,ugel.nombre,matDet.nivel ,
                                        sum(
                                            ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                            ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                            ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                            ifnull(cinco_anios_hombre_ebe,0)+
                                            ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                            ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                            ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                            ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                            ) as cantidad
                                        from edu_matricula mat
                                        inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                        inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                        inner join edu_ugel ugel on inst.Ugel_id = ugel.id
                                        where mat.id = $matricula_id
                                        and inst.tipogestion_id $condicion ($filtro)
                                        /*and matDet.nivel in ('I','P','S')*/
                                        group by ugel.id,ugel.codigo,ugel.nombre,matDet.nivel
                                ) as datos
                                group by id,codigo,nombre
                                order by codigo
                        ) as datos"
            )
        )

            ->orderBy('codigo', 'asc')
            ->get();
        return $data;
    }

    public static function total_matricula_EBR_porTipoGestion($matricula_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select  tipGes,sum(hombres + mujeres) as cantidad
                                from (
                                        select case when tipGesCab.id in (15,17) then 'PUBLICA' else 'PRIVADA' end as tipGes,sum(
                                                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                                                ifnull(cinco_anios_hombre_ebe,0)
                                                                ) as hombres,
                                                sum(
                                                    ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                                    ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                                    ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                                    ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                                    ) as mujeres
                                        from edu_matricula mat
                                        inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                        inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                        inner join edu_tipogestion tipGes on inst.TipoGestion_id = tipGes.id
                                        inner join edu_tipogestion tipGesCab on tipGes.dependencia = tipGesCab.id
                                        where mat.id = $matricula_id and matDet.nivel !='E'
                                        group by tipGesCab.id,tipGesCab.nombre
                                ) as datos
                                group by tipGes
                        ) as datos"
            )
        )
            ->get();
        return $data;
    }

    public static function total_matricula_EBR_porNivelEducativo($matricula_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select
                                    id,
                                    sum(case when nivel = 'I' then cantidad else 0 end) as inicial,
                                    sum(case when nivel = 'P' then cantidad else 0 end) as primaria,
                                    sum(case when nivel = 'S' then cantidad else 0 end) as secundaria
                                    from (

                                                                select mat.id,matDet.nivel ,
                                                                        sum(
                                                                            ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                                                            ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                                                            ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                                                            ifnull(cinco_anios_hombre_ebe,0)+
                                                                            ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                                                            ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                                                            ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                                                            ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                                                        ) as cantidad
                                                                        from edu_matricula mat
                                                                        inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                                                        inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                                                        inner join edu_ugel ugel on inst.Ugel_id = ugel.id
                                                                        where mat.id = $matricula_id
                                                                        group by mat.id,matDet.nivel
                                        ) as data
                                        group by id
                        ) as datos"
            )
        )


            ->get();
        return $data;
    }

    public static function total_matricula_por_Nivel($matricula_id)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            ->where('mat.id', '=', $matricula_id)
            //->where('matDet.nivel','=',$nivel)
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy('ugel.nombre')
            ->groupBy('nivel')
            ->get([
                DB::raw('ugel.nombre'),
                DB::raw('nivel'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

                DB::raw('sum( ifnull(cero_nivel_hombre,0) ) as cero_nivel_hombre'),
                DB::raw('sum( ifnull(primer_nivel_hombre,0) ) as primer_nivel_hombre'),
                DB::raw('sum( ifnull(segundo_nivel_hombre,0) ) as segundo_nivel_hombre'),
                DB::raw('sum( ifnull(tercero_nivel_hombre,0) ) as tercero_nivel_hombre'),
                DB::raw('sum( ifnull(cuarto_nivel_hombre,0) ) as cuarto_nivel_hombre'),
                DB::raw('sum( ifnull(quinto_nivel_hombre,0) ) as quinto_nivel_hombre'),
                DB::raw('sum( ifnull(sexto_nivel_hombre,0) ) as sexto_nivel_hombre'),

                DB::raw('sum( ifnull(tres_anios_hombre_ebe,0) ) as tres_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_hombre_ebe,0) ) as cuatro_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cinco_anios_hombre_ebe,0) ) as cinco_anios_hombre_ebe'),

                DB::raw('sum( ifnull(cero_nivel_mujer,0) ) as cero_nivel_mujer'),
                DB::raw('sum( ifnull(primer_nivel_mujer,0) ) as primer_nivel_mujer'),
                DB::raw('sum( ifnull(segundo_nivel_mujer,0) ) as segundo_nivel_mujer'),
                DB::raw('sum( ifnull(tercero_nivel_mujer,0) ) as tercero_nivel_mujer'),
                DB::raw('sum( ifnull(cuarto_nivel_mujer,0) ) as cuarto_nivel_mujer'),
                DB::raw('sum( ifnull(quinto_nivel_mujer,0) ) as quinto_nivel_mujer'),
                DB::raw('sum( ifnull(sexto_nivel_mujer,0) ) as sexto_nivel_mujer'),

                DB::raw('sum( ifnull(tres_anios_mujer_ebe,0) ) as tres_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_mujer_ebe,0) ) as cuatro_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cinco_anios_mujer_ebe,0) ) as cinco_anios_mujer_ebe'),

            ]);

        return $data;
    }

    public static function total_matricula_por_Nivel_Distrito($matricula_id, $condicion, $filtro)
    {
        $data = DB::table('edu_matricula as mat')
            ->join('edu_matricula_detalle as matDet', 'mat.id', '=', 'matDet.matricula_id')
            ->join('edu_institucioneducativa as inst', 'matDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_centropoblado as cenPo', 'inst.CentroPoblado_id', '=', 'cenPo.id')

            ->join('par_ubigeo as dist', 'cenPo.ubigeo_id', '=', 'dist.id')
            ->join('par_ubigeo as prov', 'dist.dependencia', '=', 'prov.id')

            ->where('mat.id', '=', $matricula_id)
            ->$condicion("inst.tipogestion_id", [$filtro])
            //->where('matDet.nivel','=',$nivel)
            ->orderBy('dist.codigo', 'asc')
            ->groupBy('prov.nombre')
            ->groupBy('dist.nombre')
            ->groupBy('nivel')
            ->get([
                DB::raw('case when dist.nombre = "YURUA" then "CORONEL PORTILLO" else prov.nombre end as provincia'),
                DB::raw('dist.nombre as distrito'),
                DB::raw('nivel'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

                DB::raw('sum( ifnull(cero_nivel_hombre,0) ) as cero_nivel_hombre'),
                DB::raw('sum( ifnull(primer_nivel_hombre,0) ) as primer_nivel_hombre'),
                DB::raw('sum( ifnull(segundo_nivel_hombre,0) ) as segundo_nivel_hombre'),
                DB::raw('sum( ifnull(tercero_nivel_hombre,0) ) as tercero_nivel_hombre'),
                DB::raw('sum( ifnull(cuarto_nivel_hombre,0) ) as cuarto_nivel_hombre'),
                DB::raw('sum( ifnull(quinto_nivel_hombre,0) ) as quinto_nivel_hombre'),
                DB::raw('sum( ifnull(sexto_nivel_hombre,0) ) as sexto_nivel_hombre'),

                DB::raw('sum( ifnull(tres_anios_hombre_ebe,0) ) as tres_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_hombre_ebe,0) ) as cuatro_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cinco_anios_hombre_ebe,0) ) as cinco_anios_hombre_ebe'),

                DB::raw('sum( ifnull(cero_nivel_mujer,0) ) as cero_nivel_mujer'),
                DB::raw('sum( ifnull(primer_nivel_mujer,0) ) as primer_nivel_mujer'),
                DB::raw('sum( ifnull(segundo_nivel_mujer,0) ) as segundo_nivel_mujer'),
                DB::raw('sum( ifnull(tercero_nivel_mujer,0) ) as tercero_nivel_mujer'),
                DB::raw('sum( ifnull(cuarto_nivel_mujer,0) ) as cuarto_nivel_mujer'),
                DB::raw('sum( ifnull(quinto_nivel_mujer,0) ) as quinto_nivel_mujer'),
                DB::raw('sum( ifnull(sexto_nivel_mujer,0) ) as sexto_nivel_mujer'),

                DB::raw('sum( ifnull(tres_anios_mujer_ebe,0) ) as tres_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_mujer_ebe,0) ) as cuatro_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cinco_anios_mujer_ebe,0) ) as cinco_anios_mujer_ebe'),

            ]);


        return $data;
    }

    public static function total_matricula_por_Nivel_Institucion($matricula_id, $nivel, $condicion, $filtro)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select ugel.nombre as ugel,case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                                dist.nombre as distrito,cenPo.nombre as cenPo,inst.codModular,inst.anexo,inst.nombreInstEduc,tipoGestion.nombre as tipoGestion,
                                tipoGestionCab.nombre as tipoGestionCab,forma.nombre as forma,caracteristica.nombre as caracteristica, areas.nombre as areas,
                                prov.codigo,nivel,total_estudiantes_matriculados,
                                cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                                tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                                sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                                cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                                cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                                cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                                from edu_matricula mat
                                inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                inner join edu_ugel ugel on inst.Ugel_id = ugel.id
                                inner join edu_tipogestion tipoGestion on inst.TipoGestion_id = tipoGestion.id
                                inner join edu_tipogestion tipoGestionCab on tipoGestion.dependencia = tipoGestionCab.id
                                inner join edu_forma forma on inst.Forma_id = forma.id
                                inner join edu_caracteristica caracteristica on inst.Caracteristica_id = caracteristica .id
                                inner join edu_area areas on inst.Area_id = areas.id
                                inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                                inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                                inner join par_ubigeo prov on dist.dependencia = prov.id
                                where mat.id = '$matricula_id' and nivel = '$nivel'
                                and inst.tipogestion_id $condicion ($filtro)
                                order by ugel.codigo
                            ) as datos"
            )
        )

            ->orderBy('nombreInstEduc', 'asc')
            ->get(
                //[
                // DB::raw('ugel'),
                // ]
            );


        return $data;
    }


    public static function total_matricula_por_Nivel_Provincia($matricula_id, $condicion, $filtro)
    {
        $data = DB::table(

            DB::raw(
                "(
                            select dist.nombre as distrito,prov.codigo,nivel,
                            case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                            cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                            tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                            sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                            cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                            cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                            cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                            from edu_matricula mat
                            inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                            inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                            inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                            inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                            inner join par_ubigeo prov on dist.dependencia = prov.id
                            where mat.id = '$matricula_id'
                            and inst.tipogestion_id $condicion ($filtro)
                        ) as datos"
            )

        )

            ->orderBy('codigo', 'asc')
            ->groupBy('provincia')
            ->groupBy('nivel')

            ->get([

                DB::raw('provincia'),
                DB::raw('nivel'),
                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),

                DB::raw('sum( ifnull(cero_nivel_hombre,0) ) as cero_nivel_hombre'),
                DB::raw('sum( ifnull(primer_nivel_hombre,0) ) as primer_nivel_hombre'),
                DB::raw('sum( ifnull(segundo_nivel_hombre,0) ) as segundo_nivel_hombre'),
                DB::raw('sum( ifnull(tercero_nivel_hombre,0) ) as tercero_nivel_hombre'),
                DB::raw('sum( ifnull(cuarto_nivel_hombre,0) ) as cuarto_nivel_hombre'),
                DB::raw('sum( ifnull(quinto_nivel_hombre,0) ) as quinto_nivel_hombre'),
                DB::raw('sum( ifnull(sexto_nivel_hombre,0) ) as sexto_nivel_hombre'),

                DB::raw('sum( ifnull(tres_anios_hombre_ebe,0) ) as tres_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_hombre_ebe,0) ) as cuatro_anios_hombre_ebe'),
                DB::raw('sum( ifnull(cinco_anios_hombre_ebe,0) ) as cinco_anios_hombre_ebe'),

                DB::raw('sum( ifnull(cero_nivel_mujer,0) ) as cero_nivel_mujer'),
                DB::raw('sum( ifnull(primer_nivel_mujer,0) ) as primer_nivel_mujer'),
                DB::raw('sum( ifnull(segundo_nivel_mujer,0) ) as segundo_nivel_mujer'),
                DB::raw('sum( ifnull(tercero_nivel_mujer,0) ) as tercero_nivel_mujer'),
                DB::raw('sum( ifnull(cuarto_nivel_mujer,0) ) as cuarto_nivel_mujer'),
                DB::raw('sum( ifnull(quinto_nivel_mujer,0) ) as quinto_nivel_mujer'),
                DB::raw('sum( ifnull(sexto_nivel_mujer,0) ) as sexto_nivel_mujer'),

                DB::raw('sum( ifnull(tres_anios_mujer_ebe,0) ) as tres_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cuatro_anios_mujer_ebe,0) ) as cuatro_anios_mujer_ebe'),
                DB::raw('sum( ifnull(cinco_anios_mujer_ebe,0) ) as cinco_anios_mujer_ebe'),

            ]);


        return $data;
    }

    public static function total_matricula_EBR_Provincia($matricula_id, $condicion, $filtro)
    {
        //->where('matDet.nivel','!=','E')

        $data = DB::table(
            DB::raw("(
                            select dist.nombre as distrito,prov.codigo,nivel,
                            case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                            cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                            tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                            sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                            cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                            cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                            cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                            from edu_matricula mat
                            inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                            inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                            inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                            inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                            inner join par_ubigeo prov on dist.dependencia = prov.id
                            where matDet.nivel != 'E' and  mat.id = $matricula_id
                            and inst.tipogestion_id $condicion ($filtro)
                        ) as datos")
        )

            ->orderBy('codigo', 'asc')
            ->groupBy('provincia')
            ->get([
                DB::raw('provincia'),

                DB::raw('sum(
                                ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                ifnull(cinco_anios_hombre_ebe,0)
                                ) as hombres'),
                DB::raw('sum(
                                ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                ) as mujeres'),
            ]);

        return $data;
    }


    public static function total_matricula_anual($anio_id, $condicion, $filtro)
    {
        //->where('matDet.nivel','!=','E')

        $data = DB::table(
            DB::raw("(
                            select fechaactualizacion,sum(cantidad) as cantTotal ,
                            sum(case when id = 10 then cantidad else 0 end ) as ugel10,
                            sum(case when id = 11 then cantidad else 0 end ) as ugel11,
                            sum(case when id = 12 then cantidad else 0 end ) as ugel12,
                            sum(case when id = 13 then cantidad else 0 end ) as ugel13
                            from (

                                    select
                                    fechaactualizacion,
                                    ugel.id,
                                    sum(
                                        ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                        ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                        ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                        ifnull(cinco_anios_hombre_ebe,0) + ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                        ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                        ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                        ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                        ) as cantidad

                                        from par_importacion as imp
                                    inner join edu_matricula as mat on imp.id = mat.importacion_id
                                    inner join edu_matricula_detalle as matDet on mat.id = matDet.matricula_id
                                    inner join edu_institucioneducativa as inst on matDet.institucioneducativa_id = inst.id
                                    inner join edu_ugel as ugel on inst.Ugel_id = ugel.id
                                    where mat.anio_id = '$anio_id' and matDet.nivel != 'E' and imp.estado = 'PR'
                                    and inst.tipogestion_id $condicion ($filtro)
                                    group By fechaactualizacion,ugel.id

                            ) as dd
                            group by fechaactualizacion

                        ) as datos")
        )

            // ->orderBy('codigo', 'asc')
            // ->groupBy('provincia')
            ->get([
                DB::raw('fechaactualizacion'),
                DB::raw('ugel10'),
                DB::raw('ugel11'),
                DB::raw('ugel12'),
                DB::raw('ugel13')

            ]);

        return $data;
    }

    public static function total_matricula_anual_EIB($anio_id, $condicion, $filtro)
    {
        //->where('matDet.nivel','!=','E')

        $data = DB::table(
            DB::raw("(
                            select fechaactualizacion,sum(cantidad) as cantTotal ,
                            sum(case when id = 10 then cantidad else 0 end ) as ugel10,
                            sum(case when id = 11 then cantidad else 0 end ) as ugel11,
                            sum(case when id = 12 then cantidad else 0 end ) as ugel12,
                            sum(case when id = 13 then cantidad else 0 end ) as ugel13
                            from (

                                    select
                                    fechaactualizacion,
                                    ugel.id,
                                    sum(
                                        ifnull(cero_nivel_hombre,0) + ifnull(primer_nivel_hombre,0) + ifnull(segundo_nivel_hombre,0) +
                                        ifnull(tercero_nivel_hombre,0) + ifnull(cuarto_nivel_hombre,0) + ifnull(quinto_nivel_hombre,0) +
                                        ifnull(sexto_nivel_hombre,0) + ifnull(tres_anios_hombre_ebe,0) + ifnull(cuatro_anios_hombre_ebe,0) +
                                        ifnull(cinco_anios_hombre_ebe,0) + ifnull(cero_nivel_mujer,0) + ifnull(primer_nivel_mujer,0) + ifnull(segundo_nivel_mujer,0) +
                                        ifnull(tercero_nivel_mujer,0) + ifnull(cuarto_nivel_mujer,0) + ifnull(quinto_nivel_mujer,0) +
                                        ifnull(sexto_nivel_mujer,0) + ifnull(tres_anios_mujer_ebe,0) +
                                        ifnull(cuatro_anios_mujer_ebe,0) + ifnull(cinco_anios_mujer_ebe,0)
                                        ) as cantidad

                                        from par_importacion as imp
                                    inner join edu_matricula as mat on imp.id = mat.importacion_id
                                    inner join edu_matricula_detalle as matDet on mat.id = matDet.matricula_id
                                    inner join edu_institucioneducativa as inst on matDet.institucioneducativa_id = inst.id
                                    inner join edu_ugel as ugel on inst.Ugel_id = ugel.id

                                    /*inner join edu_padron_eib as eib on inst.codModular = eib.codModular  */


                                    where mat.anio_id = '$anio_id' and matDet.nivel != 'E' and imp.estado = 'PR'
                                    and inst.tipogestion_id $condicion ($filtro)
                                    and inst.es_eib = 'SI'
                                    group By fechaactualizacion,ugel.id

                            ) as dd
                            group by fechaactualizacion

                        ) as datos")
        )

            // ->orderBy('codigo', 'asc')
            // ->groupBy('provincia')
            ->get([
                DB::raw('fechaactualizacion'),
                DB::raw('ugel10'),
                DB::raw('ugel11'),
                DB::raw('ugel12'),
                DB::raw('ugel13')

            ]);

        return $data;
    }


    /**********************************  ConsolidadoAnual *********************************/
    public static function matricula_porImportacion_ConsolidadoAnual($importacion_id)
    {
        $data = MatriculaAnual::select('id', 'estado')
            ->where("importacion_id", "=", $importacion_id)
            ->get();

        return $data;
    }

    public static function datos_matricula_importada_ConsolidadoAnual($importacion_id)
    {
        $data = DB::table("edu_matricula_anual_detalle as det")
            ->join('edu_matricula_anual as mat', 'det.matricula_anual_id', '=', 'mat.id')
            ->where("importacion_id", "=", $importacion_id)
            ->orderBy('nivel', 'asc')
            ->groupBy("nivel")
            ->get([
                DB::raw('(case when nivel="I" then "1. INICIAL"
                                   when nivel="P" then "2. PRIMARIA" else "3. SECUNDARIA" end) as nivel'),
                DB::raw('count(*) as numeroFilas')
            ]);

        return $data;
    }

    public static function anio_matricula_importada_ConsolidadoAnual($matricula_id)
    {
        $data = DB::table("edu_matricula_anual as mat")
            ->join('par_anio as vanio', 'mat.anio_id', '=', 'vanio.id')
            ->where("mat.id", "=", $matricula_id)
            ->select('vanio.id', 'vanio.anio')
            ->get();;

        return $data;
    }

    public static function busca_ConsolidadoAnual_segunAnio($anio_id)
    {
        $data = DB::table("par_importacion as imp")
            ->join('edu_matricula_anual as mat', 'imp.id', '=', 'mat.importacion_id')
            ->join('par_anio as vanio', 'mat.anio_id', '=', 'vanio.id')
            ->where("vanio.id", "=", $anio_id)
            ->where("imp.estado", "!=", "EL")
            ->get([
                DB::raw('(case when imp.estado="PR" then "PROCESADO" else "PENDIENTE" end) as estado')
            ]);

        return $data;
    }

    public static function matriculas_anio_ConsolidadoAnual()
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_matricula_anual as mat', 'imp.id', '=', 'mat.importacion_id')
            ->join('par_anio as vanio', 'mat.anio_id', '=', 'vanio.id')
            ->where('imp.estado', '=', 'PR')
            ->where('mat.estado', '=', 'PR')
            ->orderBy('vanio.anio', 'desc')
            ->select('vanio.id', 'vanio.anio')
            ->distinct()
            ->get();

        return $data;
    }

    public static function total_matricula_ComsolidadoAnual_porNivel($anio_id, $condicion, $filtro)
    {
        $data = DB::table(
            DB::raw(
                "(
                                select ugel,anio.anio,nivel,
                                sum(
                                ifnull(cero_nivel_concluyeron,0) + ifnull(cero_nivel_retirados,0)
                                + ifnull(primer_nivel_aprobados,0) + ifnull(primer_nivel_retirados,0) + ifnull(primer_nivel_requieren_recup,0) + ifnull(primer_nivel_desaprobados,0)
                                + ifnull(segundo_nivel_aprobados,0) + ifnull(segundo_nivel_retirados,0) + ifnull(segundo_nivel_requieren_recup,0) + ifnull(segundo_nivel_desaprobados,0)
                                + ifnull(tercer_nivel_aprobados,0) + ifnull(tercer_nivel_retirados,0) + ifnull(tercer_nivel_requieren_recup,0) + ifnull(tercer_nivel_desaprobados,0)
                                + ifnull(cuarto_nivel_aprobados,0) + ifnull(cuarto_nivel_retirados,0) + ifnull(cuarto_nivel_requieren_recup,0) + ifnull(cuarto_nivel_desaprobados,0)
                                + ifnull(quinto_nivel_aprobados,0) + ifnull(quinto_nivel_retirados,0) + ifnull(quinto_nivel_requieren_recup,0) + ifnull(quinto_nivel_desaprobados,0)
                                + ifnull(sexto_nivel_aprobados,0) + ifnull(sexto_nivel_retirados,0) + ifnull(sexto_nivel_requieren_recup,0) + ifnull(sexto_nivel_desaprobados,0)
                                )as cantidadAlumnos
                                from par_importacion imp
                                inner join edu_matricula_anual as mat on imp.id = mat.importacion_id
                                inner join edu_matricula_anual_detalle as matDet on mat.id = matDet.matricula_anual_id
                                inner join par_anio as anio on mat.anio_id = anio.id
                                where imp.estado = 'PR'
                                and nivel $condicion ('$filtro')
                                group by ugel,anio.anio,nivel
                                order by ugel,anio.anio,nivel
                        ) as datos "
            )
        )
            ->get([
                DB::raw('ugel'),
                DB::raw('anio'),
                DB::raw('case when nivel = "I" then "INICIAL"
                                when nivel = "P" then "PRIMARIA"
                                when nivel = "S" then "SECUNDARIA" else "OTROS" end as nivel'),
                DB::raw('cantidadAlumnos')
            ]);

        return $data;
    }

    public static function total_matricula_ComsolidadoAnual($anio_id, $condicion, $filtro, $filtroTipo_IE)
    {
        // la forma de ordenamiento va de la mano con los metodos
        //total_matricula_ComsolidadoAnual_porNivel_soloAnios
        //total_matricula_ComsolidadoAnual_porNivel_soloUgel
        $data = DB::table(
            DB::raw(
                "(
                                select row_number() OVER (partition BY anio.id  ORDER BY UGEL ) AS posUgel,
                                row_number() OVER (partition BY ugel  ORDER BY anio.anio asc) AS posAnio,
                                ugel,anio.anio,
                                sum(
                                    ifnull(cero_nivel_concluyeron,0) + ifnull(cero_nivel_retirados,0)
                                    + ifnull(primer_nivel_aprobados,0) + ifnull(primer_nivel_retirados,0) + ifnull(primer_nivel_requieren_recup,0) + ifnull(primer_nivel_desaprobados,0)
                                    + ifnull(segundo_nivel_aprobados,0) + ifnull(segundo_nivel_retirados,0) + ifnull(segundo_nivel_requieren_recup,0) + ifnull(segundo_nivel_desaprobados,0)
                                    + ifnull(tercer_nivel_aprobados,0) + ifnull(tercer_nivel_retirados,0) + ifnull(tercer_nivel_requieren_recup,0) + ifnull(tercer_nivel_desaprobados,0)
                                    + ifnull(cuarto_nivel_aprobados,0) + ifnull(cuarto_nivel_retirados,0) + ifnull(cuarto_nivel_requieren_recup,0) + ifnull(cuarto_nivel_desaprobados,0)
                                    + ifnull(quinto_nivel_aprobados,0) + ifnull(quinto_nivel_retirados,0) + ifnull(quinto_nivel_requieren_recup,0) + ifnull(quinto_nivel_desaprobados,0)
                                    + ifnull(sexto_nivel_aprobados,0) + ifnull(sexto_nivel_retirados,0) + ifnull(sexto_nivel_requieren_recup,0) + ifnull(sexto_nivel_desaprobados,0)
                                    )as cantidadAlumnos,

                                sum( ifnull(cero_nivel_concluyeron,0) + ifnull(primer_nivel_aprobados,0) + ifnull(segundo_nivel_aprobados,0) + ifnull(tercer_nivel_aprobados,0)
                                    + ifnull(cuarto_nivel_aprobados,0) + ifnull(quinto_nivel_aprobados,0) + ifnull(sexto_nivel_aprobados,0) )as cantidadAprobados,

                                sum( ifnull(cero_nivel_retirados,0) + ifnull(primer_nivel_retirados,0) + ifnull(segundo_nivel_retirados,0) + ifnull(tercer_nivel_retirados,0)
                                      + ifnull(cuarto_nivel_retirados,0) + ifnull(quinto_nivel_retirados,0) + ifnull(sexto_nivel_retirados,0) )as cantidadRetirados,

                                sum( ifnull(primer_nivel_requieren_recup,0) + ifnull(segundo_nivel_requieren_recup,0) + ifnull(tercer_nivel_requieren_recup,0)
                                      + ifnull(cuarto_nivel_requieren_recup,0) + ifnull(quinto_nivel_requieren_recup,0) + ifnull(sexto_nivel_requieren_recup,0) )as cantidadRequieren_Recup,

                                sum( ifnull(primer_nivel_desaprobados,0) + ifnull(segundo_nivel_desaprobados,0) + ifnull(tercer_nivel_desaprobados,0)
                                      + ifnull(cuarto_nivel_desaprobados,0) + ifnull(quinto_nivel_desaprobados,0) + ifnull(sexto_nivel_desaprobados,0) )as cantidadDesaprobados,

                                sum( ifnull(cero_nivel_trasladado,0) + ifnull(primer_nivel_trasladados,0) + ifnull(segundo_nivel_trasladados,0) + ifnull(tercer_nivel_trasladados,0)
                                      + ifnull(cuarto_nivel_trasladados,0) + ifnull(quinto_nivel_trasladados,0) + ifnull(sexto_nivel_trasladados,0) )as cantidadTrasladados

                                from par_importacion imp
                                inner join edu_matricula_anual as mat on imp.id = mat.importacion_id
                                inner join edu_matricula_anual_detalle as matDet on mat.id = matDet.matricula_anual_id
                                inner join par_anio as anio on mat.anio_id = anio.id
                                where imp.estado = 'PR'
                                and nivel $condicion ('$filtro')
                                and $filtroTipo_IE
                                group by ugel,anio.anio
                                order by ugel,anio.anio
                        ) as datos "
            )
        )
            ->get([
                DB::raw('posUgel'),
                DB::raw('posAnio'),
                DB::raw('ugel'),
                DB::raw('anio'),
                DB::raw('cantidadAlumnos'),
                DB::raw('cantidadAprobados'),
                DB::raw('cantidadRetirados'),
                DB::raw('cantidadRequieren_Recup'),
                DB::raw('cantidadDesaprobados'),
                DB::raw('cantidadTrasladados'),
                DB::raw('cantidadAlumnos + cantidadTrasladados as cantidadTotal')
            ]);

        return $data;
    }

    public static function total_matricula_ComsolidadoAnual_totalAnios($anio_id, $condicion, $filtro, $filtroTipo_IE)
    {
        // la forma de ordenamiento va de la mano con los metodos
        //total_matricula_ComsolidadoAnual_porNivel_soloAnios
        //total_matricula_ComsolidadoAnual_porNivel_soloUgel
        $data = DB::table(
            DB::raw(
                "(
                                select
                                row_number() OVER (partition BY ugel  ORDER BY anio.anio asc) AS posAnio,
                                anio.anio,
                                sum(
                                    ifnull(cero_nivel_concluyeron,0) + ifnull(cero_nivel_retirados,0)
                                    + ifnull(primer_nivel_aprobados,0) + ifnull(primer_nivel_retirados,0) + ifnull(primer_nivel_requieren_recup,0) + ifnull(primer_nivel_desaprobados,0)
                                    + ifnull(segundo_nivel_aprobados,0) + ifnull(segundo_nivel_retirados,0) + ifnull(segundo_nivel_requieren_recup,0) + ifnull(segundo_nivel_desaprobados,0)
                                    + ifnull(tercer_nivel_aprobados,0) + ifnull(tercer_nivel_retirados,0) + ifnull(tercer_nivel_requieren_recup,0) + ifnull(tercer_nivel_desaprobados,0)
                                    + ifnull(cuarto_nivel_aprobados,0) + ifnull(cuarto_nivel_retirados,0) + ifnull(cuarto_nivel_requieren_recup,0) + ifnull(cuarto_nivel_desaprobados,0)
                                    + ifnull(quinto_nivel_aprobados,0) + ifnull(quinto_nivel_retirados,0) + ifnull(quinto_nivel_requieren_recup,0) + ifnull(quinto_nivel_desaprobados,0)
                                    + ifnull(sexto_nivel_aprobados,0) + ifnull(sexto_nivel_retirados,0) + ifnull(sexto_nivel_requieren_recup,0) + ifnull(sexto_nivel_desaprobados,0)
                                    )as cantidadAlumnos,

                                sum( ifnull(cero_nivel_concluyeron,0) + ifnull(primer_nivel_aprobados,0) + ifnull(segundo_nivel_aprobados,0) + ifnull(tercer_nivel_aprobados,0)
                                    + ifnull(cuarto_nivel_aprobados,0) + ifnull(quinto_nivel_aprobados,0) + ifnull(sexto_nivel_aprobados,0) )as cantidadAprobados,

                                sum( ifnull(cero_nivel_retirados,0) + ifnull(primer_nivel_retirados,0) + ifnull(segundo_nivel_retirados,0) + ifnull(tercer_nivel_retirados,0)
                                      + ifnull(cuarto_nivel_retirados,0) + ifnull(quinto_nivel_retirados,0) + ifnull(sexto_nivel_retirados,0) )as cantidadRetirados,

                                sum( ifnull(primer_nivel_requieren_recup,0) + ifnull(segundo_nivel_requieren_recup,0) + ifnull(tercer_nivel_requieren_recup,0)
                                      + ifnull(cuarto_nivel_requieren_recup,0) + ifnull(quinto_nivel_requieren_recup,0) + ifnull(sexto_nivel_requieren_recup,0) )as cantidadRequieren_Recup,

                                sum( ifnull(primer_nivel_desaprobados,0) + ifnull(segundo_nivel_desaprobados,0) + ifnull(tercer_nivel_desaprobados,0)
                                      + ifnull(cuarto_nivel_desaprobados,0) + ifnull(quinto_nivel_desaprobados,0) + ifnull(sexto_nivel_desaprobados,0) )as cantidadDesaprobados

                                from par_importacion imp
                                inner join edu_matricula_anual as mat on imp.id = mat.importacion_id
                                inner join edu_matricula_anual_detalle as matDet on mat.id = matDet.matricula_anual_id
                                inner join par_anio as anio on mat.anio_id = anio.id
                                where imp.estado = 'PR'
                                and nivel $condicion ('$filtro')
                                and $filtroTipo_IE
                                group by anio.anio
                                order by anio.anio
                        ) as datos "
            )
        )
            ->get([

                DB::raw('posAnio'),
                DB::raw('anio'),
                DB::raw('cantidadAlumnos'),
                DB::raw('cantidadAprobados'),
                DB::raw('cantidadRetirados'),
                DB::raw('cantidadRequieren_Recup'),
                DB::raw('cantidadDesaprobados')
            ]);

        return $data;
    }


    public static function total_matricula_ComsolidadoAnual_porNivel_soloAnios($anio_id, $condicion, $filtro, $filtroTipo_IE)
    {
        // la forma de ordenamiento va de la mano con el metodo total_matricula_ComsolidadoAnual
        $data = DB::table(
            DB::raw(
                "(
                                select row_number() OVER (partition BY ugel  ORDER BY anio.anio asc) AS posAnio,anio.id,anio.anio,
                                sum(
                                    ifnull(cero_nivel_concluyeron,0) + ifnull(cero_nivel_retirados,0)
                                    + ifnull(primer_nivel_aprobados,0) + ifnull(primer_nivel_retirados,0) + ifnull(primer_nivel_requieren_recup,0) + ifnull(primer_nivel_desaprobados,0)
                                    + ifnull(segundo_nivel_aprobados,0) + ifnull(segundo_nivel_retirados,0) + ifnull(segundo_nivel_requieren_recup,0) + ifnull(segundo_nivel_desaprobados,0)
                                    + ifnull(tercer_nivel_aprobados,0) + ifnull(tercer_nivel_retirados,0) + ifnull(tercer_nivel_requieren_recup,0) + ifnull(tercer_nivel_desaprobados,0)
                                    + ifnull(cuarto_nivel_aprobados,0) + ifnull(cuarto_nivel_retirados,0) + ifnull(cuarto_nivel_requieren_recup,0) + ifnull(cuarto_nivel_desaprobados,0)
                                    + ifnull(quinto_nivel_aprobados,0) + ifnull(quinto_nivel_retirados,0) + ifnull(quinto_nivel_requieren_recup,0) + ifnull(quinto_nivel_desaprobados,0)
                                    + ifnull(sexto_nivel_aprobados,0) + ifnull(sexto_nivel_retirados,0) + ifnull(sexto_nivel_requieren_recup,0) + ifnull(sexto_nivel_desaprobados,0)
                                    )as cantidadAlumnos   ,
                                sum( ifnull(cero_nivel_trasladado,0) + ifnull(primer_nivel_trasladados,0) + ifnull(segundo_nivel_trasladados,0) + ifnull(tercer_nivel_trasladados,0)
                                    + ifnull(cuarto_nivel_trasladados,0) + ifnull(quinto_nivel_trasladados,0) + ifnull(sexto_nivel_trasladados,0) )as cantidadTrasladados

                                from par_importacion imp
                                inner join edu_matricula_anual as mat on imp.id = mat.importacion_id
                                inner join edu_matricula_anual_detalle as matDet on mat.id = matDet.matricula_anual_id
                                inner join par_anio as anio on mat.anio_id = anio.id
                                where imp.estado = 'PR'
                                and nivel $condicion ('$filtro')
                                and $filtroTipo_IE
                                group by anio.id,anio.anio
                                order by anio.anio
                        ) as datos "
            )
        )
            ->get([
                DB::raw('posAnio'),
                DB::raw('id'),
                DB::raw('anio'),
                DB::raw('cantidadAlumnos'),
                DB::raw('cantidadTrasladados'),
                DB::raw('cantidadAlumnos + cantidadTrasladados as cantidadTotal')
            ]);

        return $data;
    }

    public static function total_matricula_ComsolidadoAnual_porNivel_soloUgel($anio_id, $condicion, $filtro, $filtroTipo_IE)
    {
        // la forma de ordenamiento va de la mano con el metodo total_matricula_ComsolidadoAnual
        $data = DB::table(
            DB::raw(
                "(
                                select row_number() OVER (partition BY imp.ID  ORDER BY UGEL ) AS posUgel,ugel
                                from par_importacion imp
                                inner join edu_matricula_anual as mat on imp.id = mat.importacion_id
                                inner join edu_matricula_anual_detalle as matDet on mat.id = matDet.matricula_anual_id
                                inner join par_anio as anio on mat.anio_id = anio.id
                                where imp.estado = 'PR'
                                and nivel $condicion ('$filtro')
                                and $filtroTipo_IE
                                group by ugel
                                order by ugel
                        ) as datos "
            )
        )
            ->get([
                DB::raw('posUgel'),
                DB::raw('ugel'),
            ]);

        return $data;
    }



    /**pruebassssss */
    public static function total_matricula_por_Nivel_Provincia2($matricula_id)
    {


        $data = DB::table(DB::raw("(select nivel,sum(ifnull(dni_sin_validar,0)) as dni_sin_validar from edu_matricula_detalle group by nivel) as datos"))
            ->select("nivel", "dni_sin_validar")
            // ->groupBy('nivel')
            // ->get([
            //     DB::raw('nivel'),
            //     DB::raw('sum(dni_sin_validar) as dd'),

            // ])
            ->get();

        // $data =  DB::table()
        //     ->select("nivel", "sum (dni_sin_validar)")
        //     ->groupBy("nivel")
        //     ->get();
        $id = 45;

        $data = DB::table(
            DB::raw(
                "(
                                    select dist.nombre as distrito,prov.codigo,
                                    case when dist.nombre = 'yurua' then 'CORONEL PORTILLO' else prov.nombre end as provincia ,
                                    cero_nivel_hombre , primer_nivel_hombre ,segundo_nivel_hombre ,
                                    tercero_nivel_hombre , cuarto_nivel_hombre , quinto_nivel_hombre ,
                                    sexto_nivel_hombre , tres_anios_hombre_ebe,cuatro_anios_hombre_ebe, cinco_anios_hombre_ebe,
                                    cero_nivel_mujer , primer_nivel_mujer , segundo_nivel_mujer , tercero_nivel_mujer ,
                                    cuarto_nivel_mujer , quinto_nivel_mujer , sexto_nivel_mujer , tres_anios_mujer_ebe ,
                                    cuatro_anios_mujer_ebe , cinco_anios_mujer_ebe
                                    from edu_matricula mat
                                    inner join edu_matricula_detalle matDet on mat.id = matDet.matricula_id
                                    inner join edu_institucioneducativa inst on matDet.institucioneducativa_id = inst.id
                                    inner join  edu_centropoblado cenPo on inst.CentroPoblado_id = cenPo.id
                                    inner join par_ubigeo dist on cenPo.ubigeo_id = dist.id
                                    inner join par_ubigeo prov on dist.dependencia = prov.id
                                    where mat.id = '$id'
                                ) as datos"
            )
        )
            ->select("provincia", "distrito")
            // ->groupBy('nivel')
            // ->get([
            //     DB::raw('nivel'),
            //     DB::raw('sum(dni_sin_validar) as dd'),

            // ])
            ->get();

        return $data;
    }

    public static function conteo_alumnos_rer()
    {
        $matricula_id = MatriculaRepositorio::matricula_mas_actual()->first()->id;
        $query = PadronRER::where('v2.matricula_id', $matricula_id)
            ->join('edu_matricula_detalle as v2', 'v2.institucioneducativa_id', '=', 'edu_padron_rer.institucioneducativa_id')
            ->select(DB::raw('sum(v2.total_hombres+v2.total_mujeres) as conteo'))
            ->first();
        return $query->conteo;
    }
}
