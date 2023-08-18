<?php

namespace App\Repositories\Trabajo;

use App\Utilities\Utilitario;
use Illuminate\Support\Facades\DB;

class ActividadRepositorio
{
     
    public static function direcciones_conActividad($anio,$sistema_id)
    {
       $data = DB::table(
                   DB::raw( 
                                "(
                                    
                                    select dir.id as Direccion_id,dir.nombre as direccion,dir.dependencia,dir.posicion
                                    from par_direccion dir
                                    inner join par_actividad act on dir.id = act.direccion_id
                                    where dir.sistema_id = $sistema_id and act.estado = 1
                                    order by dir.posicion
                                ) as datos"
                            )
                        )
                        ->distinct()
           ->get([
               DB::raw('direccion'),
               DB::raw('dependencia'),
               DB::raw('posicion'),
               DB::raw('Direccion_id' )                    
           ]);

       return $data;
    }

    public static function Actividad_conMeta($anio)
    {
       $data = DB::table(
                   DB::raw( 
                                "(
                                    
                                    select act.id as actividad_id,act.direccion_id,act.nombre as actividad,act.dependencia,act.posicion,act.esSubTitulo,
                                    meta.valor,uMed.nombre as uniMed
                                    from par_actividad act
                                    inner join par_actividad_meta meta on act.id = meta.actividad_id
                                    inner join par_unidad_medida uMed on meta.unidad_medida_id = uMed.id
                                    where act.estado = 1 and meta.anio_id = $anio
                                   
                                ) as datos"
                            )
                        )
                        ->distinct()
                        ->orderBy('posicion')
           ->get([
               DB::raw('actividad_id'),
               DB::raw('direccion_id'),
               DB::raw('actividad'),
               DB::raw('dependencia' ),
               DB::raw('posicion'),
               DB::raw('esSubTitulo'),
               DB::raw('valor'),
               DB::raw('uniMed')                 
           ]);

       return $data;
    }

    public static function Actividad_Resultado($anio)
    {
    //    $anio = now()->year; 
        $aniox = Utilitario::anio_deFecha(now());

       $data = DB::table(
                   DB::raw( 
                                "(
                                    select actividad_id,
                                    sum(case when mes = 1 then valor else 0  end) as 'M1',
                                    sum(case when mes = 2 then valor else 0  end) as 'M2',
                                    sum(case when mes = 3 then valor else 0  end) as 'M3',
                                    sum(case when mes = 4 then valor else 0  end) as 'M4',
                                    sum(case when mes = 5 then valor else 0  end) as 'M5',
                                    sum(case when mes = 6 then valor else 0  end) as 'M6',
                                    sum(case when mes = 7 then valor else 0  end) as 'M7',
                                    sum(case when mes = 8 then valor else 0  end) as 'M8',
                                    sum(case when mes = 9 then valor else 0  end) as 'M9',
                                    sum(case when mes = 10 then valor else 0  end) as 'M10',
                                    sum(case when mes = 11 then valor else 0  end) as 'M11',
                                    sum(case when mes = 12 then valor else 0  end) as 'M12'
                                    from 
                                        (
                                            select anio_id,actRes.valor,mes.codigo as mes ,actRes.actividad_id
                                            from par_actividad_meta actMeta
                                            inner join par_actividad_resultado actRes on actMeta.actividad_id = actRes.actividad_id 
                                            inner join par_mes mes on actRes.mes_id = mes.id
                                            where actMeta.estado = 1 and actRes.estado = 1 and actMeta.anio_id = $anio
                                        ) as datos
                                    group by actividad_id
                                   
                                ) as datos"
                            )
                            
                        )
                        // ->distinct()
                        // ->orderBy('posicion')
           ->get([
               DB::raw('actividad_id'),             
               DB::raw('M1'),
               DB::raw('M2'),
               DB::raw('M3'),
               DB::raw('M4'),
               DB::raw('M5'),
               DB::raw('M6'),
               DB::raw('M7'),
               DB::raw('M8'),
               DB::raw('M9'),
               DB::raw('M10'),
               DB::raw('M11'),
               DB::raw('M12'),
               DB::raw('M1 + M2 + M3 + M4 + M5 + M6 + M7 + M8 + M9 + M10 + M11 + M12 as Acumulado'),                           
           ]);

       return $data;
    }
}
