<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\CuadroAsigPersonal;
use Illuminate\Support\Facades\DB;

class CuadroAsigPersonalRepositorio
{
    public static function Listar_Por_Importacion_id($importacion_id)
    {         /* 'region', */
        /* 'referencia_preventiva', */
        $Lista = CuadroAsigPersonal::select(
            'id',
            'unidad_ejecutora',
            'organo_intermedio',
            'provincia',
            'distrito',
            'tipo_ie',
            'gestion',
            'zona',
            'codmod_ie',
            'codigo_local',
            'clave8',
            'nivel_educativo',
            'institucion_educativa',
            'codigo_plaza',
            'tipo_trabajador',
            'sub_tipo_trabajador',
            'cargo',
            'situacion_laboral',
            'motivo_vacante',
            'documento_identidad',
            'codigo_modular',
            'apellido_paterno',
            'apellido_materno',
            'nombres',
            'fecha_ingreso',
            'categoria_remunerativa',
            'jornada_laboral',
            'estado',
            'fecha_nacimiento',
            'fecha_inicio',
            'fecha_termino',
            'tipo_registro',
            'ley',
            'preventiva',
            'especialidad',
            'tipo_estudios',
            'estado_estudios',
            'grado',
            'mencion',
            'especialidad_profesional',
            'fecha_resolucion',
            'numero_resolucion',
            'centro_estudios',
            'celular',
            'email',
        )
            ->where("importacion_id", "=", $importacion_id)
            ->get();

        return $Lista;
    }

    public static function cuadro_ugel()
    {
        $data = DB::table("edu_plaza as pla")
            ->join('edu_ugel as ugel', 'pla.ugel_id', '=', 'ugel.id')
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy("ugel.nombre")
            ->get([
                DB::raw('ugel.nombre as ugel'),
                DB::raw('count(*) as cantidad')
            ]);

        return $data;
    }

    public static function ultima_importacion_dePlaza()
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select distinct imp.id as importacion_id,fechaActualizacion  from edu_plaza pla 
                                    inner join par_importacion imp on pla.importacion_id = imp.id
                                    where imp.estado = 'PR'
                                    order by  fechaActualizacion desc  
                                    limit 1                                                  
                                ) as datos"
            )
        )
            ->get([
                DB::raw('importacion_id'),
                DB::raw('fechaActualizacion')
            ]);

        return $data;
    }

    public static function cuadro_ugel_nivel()
    {
        $data = DB::table("edu_plaza as pla")
            ->join('edu_ugel as ugel', 'pla.ugel_id', '=', 'ugel.id')
            ->leftjoin('edu_nivelmodalidad as niv', 'pla.nivelModalidad_id', '=', 'niv.id')
            ->orderBy('ugel.codigo', 'asc')
            ->orderBy('niv.codigo', 'asc')
            ->groupBy("ugel.nombre")
            ->groupBy("nivel_educativo_dato_adic")
            ->groupBy("niv.codigo")
            ->get([
                DB::raw('ugel.nombre as ugel'),
                DB::raw('nivel_educativo_dato_adic as nivel'),
                // DB::raw('case when nivel_educativo_dato_adic is null then "ssss" else niv.codigo end  as nivel'),              
                DB::raw('count(*) as cantidad')
            ]);

        return $data;
    }

    public static function cuadro_ugel_tipoTrab()
    {
        $data = DB::table("edu_plaza as pla")
            ->join('edu_ugel as ugel', 'pla.ugel_id', '=', 'ugel.id')
            ->join('edu_tipotrabajador as subTipTra', 'pla.tipoTrabajador_id', '=', 'subTipTra.id')
            ->join('edu_tipotrabajador as tipTra', 'subTipTra.dependencia', '=', 'tipTra.id')
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy("ugel.nombre")
            ->groupBy("tipTra.nombre")
            ->get([
                DB::raw('ugel.nombre as ugel'),
                DB::raw('tipTra.nombre as tipoTrab'),
                DB::raw('count(*) as cantidad')
            ]);

        return $data;
    }



    public static function docentes_ugel()
    {
        $data = DB::table("edu_plaza as pla")
            ->join('edu_ugel as ugel', 'pla.ugel_id', '=', 'ugel.id')
            ->join('edu_nivelmodalidad as nivMod', 'pla.nivelModalidad_id', '=', 'nivMod.id')
            ->join('edu_tipotrabajador as subTipTra', 'pla.tipoTrabajador_id', '=', 'subTipTra.id')
            ->join('edu_tipotrabajador as tipTra', 'subTipTra.dependencia', '=', 'tipTra.id')
            ->where("tipTra.id", "=", 1) //solo docentes
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy("ugel.codigo")
            ->groupBy("ugel.nombre")
            ->get([
                DB::raw('ugel.codigo'),
                DB::raw('ugel.nombre as ugel'),
                DB::raw('count(*) as cantidad')
            ]);

        return $data;
    }

    public static function docentes_ugel_nivel()
    {
        $data = DB::table("edu_plaza as pla")
            ->join('edu_ugel as ugel', 'pla.ugel_id', '=', 'ugel.id')
            ->join('edu_nivelmodalidad as nivMod', 'pla.nivelModalidad_id', '=', 'nivMod.id')
            ->join('edu_tipotrabajador as subTipTra', 'pla.tipoTrabajador_id', '=', 'subTipTra.id')
            ->join('edu_tipotrabajador as tipTra', 'subTipTra.dependencia', '=', 'tipTra.id')
            ->where("tipTra.id", "=", 1) //solo docentes
            ->orderBy('ugel.codigo', 'asc')
            ->groupBy("ugel.codigo")
            ->groupBy("ugel.nombre")
            ->groupBy("nivMod.nombre")
            ->get([
                DB::raw('ugel.codigo'),
                DB::raw('ugel.nombre as ugel'),
                DB::raw('nivMod.nombre as nivel'),
                DB::raw('count(*) as cantidad')
            ]);

        return $data;
    }

    public static function docentes_pedagogico($nivel_educativo, $importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select 
                                    row_number() OVER (partition BY ugel.nombre   ORDER BY imp.fechaActualizacion DESC) AS item, ugel.nombre as ugel, 
                                    imp.fechaActualizacion,
                                    sum(case when estEst.id = 2  and esTitulado = 1 then 1 else 0 end) as pedagogico, 
                                    sum(1) as total
                                    from edu_plaza pla
                                    inner join edu_estadoestudio estEst on pla.estadoEstudio_id = estEst.id
                                    inner join edu_tipotrabajador subTipTra on pla.tipoTrabajador_id = subTipTra.id
                                    inner join edu_tipotrabajador tipTra on subTipTra.dependencia = tipTra.id
                                    inner join edu_ugel ugel on pla.ugel_id = ugel.id
                                    inner join par_importacion imp on pla.importacion_id = imp.id
                                    where  tipTra.id = 1 and nivel_educativo_dato_adic = '$nivel_educativo'
                                    and imp.id ='$importacion_id'
                                    group by imp.fechaActualizacion,ugel.nombre                            
                                ) as datos"
            )
        )
            // ->orderBy('codigo', 'asc')                 
            // ->groupBy('provincia')  
            // ->groupBy('nivel')       
            ->get([
                DB::raw('item'),
                DB::raw('ugel'),
                DB::raw('fechaActualizacion'),
                DB::raw('pedagogico'),
                DB::raw('total'),
                DB::raw('pedagogico*100/total as porcentaje'),
            ]);

        return $data;
    }

    public static function docentes_bilingues($importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select imp.fechaActualizacion,ugel.id as ugel_id,ugel.nombre as ugel,
                                    sum( case when eib.id is null then 0 else 1 end) as Bilingue,
                                    sum( case when eib.id is null then 1 else 0 end) as resto
                                    from  edu_plaza pla 
                                    inner join edu_tipotrabajador subTipTra on pla.tipoTrabajador_id = subTipTra.id
                                    inner join edu_tipotrabajador tipTra on subTipTra.dependencia = tipTra.id
                                    inner join edu_ugel ugel on pla.ugel_id = ugel.id
                                    inner join par_importacion imp on pla.importacion_id = imp.id
                                    left outer join edu_padron_eib eib on pla.codmodular = eib.codmodular
                                    where tipTra.id = 1 
                                    and subTipTra.id in (6,13) and imp.estado= 'PR'
                                    and imp.id = $importacion_id and situacion='AC'
                                    group by imp.fechaActualizacion,ugel.id ,ugel.nombre
                                    having sum( case when eib.id is null then 0 else 1 end)  >0
                                    order by ugel.codigo                    
                                ) as datos"
            )
        )
            ->get([
                DB::raw('ugel_id'),
                DB::raw('ugel'),
                DB::raw('fechaActualizacion'),
                DB::raw('Bilingue'),
                DB::raw('Bilingue + resto as total'),
                DB::raw('(Bilingue*100)/ (Bilingue + resto)     as porcentaje'),
            ]);
        return $data;
    }

    public static function docentes_bilingues_ugel($importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select                                   
                                    imp.fechaActualizacion,ugel.id as ugel_id,ugel.nombre as ugel,nivel_educativo_dato_adic as nivel_educativo,
                                    sum( case when eib.id is null then 0 else 1 end) as Bilingue,
                                    sum( case when eib.id is null then 1 else 0 end) as resto
                                    from  edu_plaza pla 
                                    inner join edu_tipotrabajador subTipTra on pla.tipoTrabajador_id = subTipTra.id
                                    inner join edu_tipotrabajador tipTra on subTipTra.dependencia = tipTra.id
                                    inner join edu_ugel ugel on pla.ugel_id = ugel.id
                                    inner join par_importacion imp on pla.importacion_id = imp.id
                                    left outer join edu_padron_eib eib on pla.codmodular = eib.codmodular
                                    where tipTra.id = 1 and subTipTra.id in (6,13) and imp.estado= 'PR' 
                                    and imp.id = $importacion_id and situacion='AC'
                                    group by imp.fechaActualizacion,ugel.id,ugel.nombre,nivel_educativo_dato_adic
                                    having sum( case when eib.id is null then 0 else 1 end)  >0
                                    order by ugel.codigo,nivel_educativo_dato_adic                        
                                ) as datos"
            )
        )

            ->get([

                DB::raw('ugel_id'),
                DB::raw('ugel'),
                DB::raw('fechaActualizacion'),
                DB::raw('nivel_educativo'),
                DB::raw('Bilingue'),
                DB::raw('resto + Bilingue as total'),
                DB::raw('Bilingue*100/(resto + Bilingue) as porcentaje'),
            ]);

        return $data;
    }

    public static function docentes_bilingues_nivel($importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select                                    
                                    nivel_educativo_dato_adic as nivel_educativo,
                                    sum( case when eib.id is null then 0 else 1 end) as Bilingue,
                                    sum( case when eib.id is null then 1 else 0 end) as noBilingue
                                    from edu_plaza pla 
                                    inner join edu_tipotrabajador subTipTra on pla.tipoTrabajador_id = subTipTra.id
                                    inner join edu_tipotrabajador tipTra on subTipTra.dependencia = tipTra.id
                                    inner join par_importacion imp on pla.importacion_id = imp.id
                                    left outer join edu_padron_eib eib on pla.codmodular = eib.codmodular
                                    where tipTra.id = 1 and subTipTra.id in (6,13) and imp.estado= 'PR' 
                                    and imp.id = $importacion_id and situacion='AC'
                                    group by nivel_educativo_dato_adic
                                    having sum( case when eib.id is null then 0 else 1 end)  >0
                                    order by nivel_educativo_dato_adic                        
                                ) as datos"
            )
        )
            ->get([
                DB::raw('nivel_educativo'),
                DB::raw('Bilingue'),
                DB::raw('noBilingue + Bilingue as total')
            ]);

        return $data;
    }

    public static function docentes_EBR()
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select row_number() OVER (partition BY imp.id ORDER BY imp.fechaActualizacion DESC) AS item,
                                    imp.id,fechaActualizacion,
                                        sum(case when nivMod.codigo in( 'A2','A3','A5')   then 1 else 0 end) as inicial ,
                                        sum(case when nivMod.codigo = 'B0'   then 1 else 0 end) as primaria ,
                                        sum(case when nivMod.codigo = 'F0'  then 1 else 0 end) as Secundaria
                                    from par_importacion imp
                                    inner join edu_plaza pla on imp.id = pla.importacion_id
                                    inner join edu_nivelmodalidad nivMod on pla.nivelModalidad_id = nivMod.id
                                    inner join edu_tipotrabajador subTipTra on pla.tipoTrabajador_id = subTipTra.id
                                    inner join edu_tipotrabajador tipTra on subTipTra.dependencia = tipTra.id
                                    where tipTra.id = 1 and imp.estado= 'PR' 
                                    and subTipTra.id in (6,13) and situacion='AC'                                   
                                    group by imp.id,fechaActualizacion                       
                                ) as datos"
            )
        )
            ->where("item", "=", 1)
            ->get([
                DB::raw('fechaActualizacion'),
                DB::raw('inicial'),
                DB::raw('primaria'),
                DB::raw('Secundaria')
            ]);

        return $data;
    }

    public static function docentes_total()
    {
        $data = DB::table(
            DB::raw(
                "(
                                    select row_number() OVER (partition BY imp.id ORDER BY imp.fechaActualizacion DESC) AS item,
                                    imp.id,fechaActualizacion,count(*) as total
                                    from par_importacion imp
                                    inner join edu_plaza pla on imp.id = pla.importacion_id
                                    inner join edu_tipotrabajador subTipTra on pla.tipoTrabajador_id = subTipTra.id
                                    inner join edu_tipotrabajador tipTra on subTipTra.dependencia = tipTra.id
                                    where tipTra.id = 1 and imp.estado= 'PR' 
                                    and subTipTra.id in (6,13) and situacion='AC'                                   
                                    group by imp.id,fechaActualizacion                      
                                ) as datos"
            )
        )
            ->where("item", "=", 1)
            ->get([
                DB::raw('fechaActualizacion'),
                DB::raw('total')
            ]);

        return $data;
    }



    /** docentes */

    public static function plazas_anio()
    {
        $data = DB::table(
            DB::raw(
                "(
                        select distinct YEAR(fechaActualizacion) as anio from par_importacion as imp
                        inner join edu_plaza as pla on imp.id = pla.importacion_id
                        where imp.estado='PR'
                        order by anio desc                        
                        ) as datos"
            )
        )
            ->get([
                DB::raw('anio')
            ]);

        return $data;
    }

    public static function plazas_fechas($anio)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select distinct imp.id as importacion_id,fechaActualizacion from par_importacion as imp
                            inner join edu_plaza as pla on imp.id = pla.importacion_id
                            where imp.estado='PR'
                            and YEAR(fechaActualizacion) = $anio
                            order by fechaActualizacion desc                   
                        ) as datos"
            )
        )
            ->get([
                DB::raw('fechaActualizacion'),
                DB::raw('importacion_id')
            ]);

        return $data;
    }

    public static function plazas_porTipoTrab($tipoTrab_id, $importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select ugel.codigo,ugel.nombre as ugel,count(*) as cantidad from edu_plaza as pla
                            inner join edu_tipotrabajador as tipoTrab on pla.tipoTrabajador_id = tipoTrab.id
                            inner join edu_tipotrabajador as tipoTrabCab on tipoTrab.dependencia = tipoTrabCab.id
                            inner join edu_ugel as ugel on pla.ugel_id = ugel.id
                            where tipoTrabCab.id = $tipoTrab_id and pla.importacion_id = $importacion_id
                            group by ugel.codigo,ugel.nombre
                            order by ugel.codigo             
                        ) as datos"
            )
        )
            ->get([
                DB::raw('ugel'),
                DB::raw('cantidad')
            ]);

        return $data;
    }

    public static function docentes_porUgel($importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select ugel.codigo,ugel.nombre as ugel,count(*) as cantidad from edu_plaza as pla
                            inner join edu_tipotrabajador as tipoTrab on pla.tipoTrabajador_id = tipoTrab.id
                            inner join edu_tipotrabajador as tipoTrabCab on tipoTrab.dependencia = tipoTrabCab.id
                            inner join edu_ugel as ugel on pla.ugel_id = ugel.id
                            where  pla.importacion_id = $importacion_id
                            and tipoTrabCab.id = 1 and tipoTrab.id in (6,13) and situacion='AC'
                            group by ugel.codigo,ugel.nombre
                            order by ugel.codigo             
                        ) as datos"
            )
        )
            ->get([
                DB::raw('ugel'),
                DB::raw('cantidad')
            ]);

        return $data;
    }

    public static function plazas_docentes_Titulados($importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select ugel.codigo,ugel.nombre as ugel,
                            sum(case when pla.esTitulado = 1 then 1 else 0 end) as Titulados,
                            sum(case when pla.esTitulado = 0 then 1 else 0 end) as noTitulados
                            from edu_plaza as pla
                            inner join edu_tipotrabajador as tipoTrab on pla.tipoTrabajador_id = tipoTrab.id
                            inner join edu_tipotrabajador as tipoTrabCab on tipoTrab.dependencia = tipoTrabCab.id
                            inner join edu_ugel as ugel on pla.ugel_id = ugel.id
                            where   pla.importacion_id = $importacion_id
                            and tipoTrabCab.id = 1 and tipoTrab.id in (6,13) and situacion='AC'
                            group by ugel.codigo,ugel.nombre
                            order by ugel.codigo        
                        ) as datos"
            )
        )
            ->get([
                DB::raw('ugel'),
                DB::raw('Titulados'),
                DB::raw('noTitulados')
            ]);

        return $data;
    }

    public static function plazas_docentes_nivelEducativo($importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select nivel_educativo_dato_adic as nivel_educativo,count(*) as cantidad from edu_plaza as pla
                            inner join edu_tipotrabajador as tipoTrab on pla.tipoTrabajador_id = tipoTrab.id
                            inner join edu_tipotrabajador as tipoTrabCab on tipoTrab.dependencia = tipoTrabCab.id
                            where pla.importacion_id = $importacion_id
                            and tipoTrabCab.id = 1 and tipoTrab.id in (6,13) and situacion='AC'
                            group by nivel_educativo_dato_adic       
                        ) as datos"
            )
        )
            ->get([
                DB::raw('nivel_educativo'),
                DB::raw('cantidad')
            ]);

        return $data;
    }

    public static function plazas_Titulados($tipoTrab_id, $importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select ugel.codigo,ugel.nombre as ugel,
                            sum(case when pla.esTitulado = 1 then 1 else 0 end) as Titulados,
                            sum(case when pla.esTitulado = 0 then 1 else 0 end) as noTitulados
                            from edu_plaza as pla
                            inner join edu_tipotrabajador as tipoTrab on pla.tipoTrabajador_id = tipoTrab.id
                            inner join edu_tipotrabajador as tipoTrabCab on tipoTrab.dependencia = tipoTrabCab.id
                            inner join edu_ugel as ugel on pla.ugel_id = ugel.id
                            where tipoTrabCab.id = $tipoTrab_id and pla.importacion_id = $importacion_id
                            group by ugel.codigo,ugel.nombre
                            order by ugel.codigo        
                        ) as datos"
            )
        )
            ->get([
                DB::raw('ugel'),
                DB::raw('Titulados'),
                DB::raw('noTitulados')
            ]);

        return $data;
    }

    public static function plazas_nivelEducativo($tipoTrab_id, $importacion_id)
    {
        $data = DB::table(
            DB::raw(
                "(
                            select nivel_educativo_dato_adic as nivel_educativo,count(*) as cantidad from edu_plaza as pla
                            inner join edu_tipotrabajador as tipoTrab on pla.tipoTrabajador_id = tipoTrab.id
                            inner join edu_tipotrabajador as tipoTrabCab on tipoTrab.dependencia = tipoTrabCab.id
                            where tipoTrabCab.id = $tipoTrab_id and pla.importacion_id = $importacion_id
                            group by nivel_educativo_dato_adic       
                        ) as datos"
            )
        )
            ->get([
                DB::raw('nivel_educativo'),
                DB::raw('cantidad')
            ]);

        return $data;
    }
}
