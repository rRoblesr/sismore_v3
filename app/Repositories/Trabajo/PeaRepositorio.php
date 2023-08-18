<?php

namespace App\Repositories\Trabajo;

use Illuminate\Support\Facades\DB;

class PeaRepositorio
{
     
    public static function lista_PEA_segunTipo($tipo)
    {
       $data = DB::table(
                   DB::raw( 
                        "(
                             
                         select anio,
                         cast( SUM(case when sexo= 'M' then resultado else 0 end) as int) as Masculino,
                         cast( SUM(case when sexo= 'F' then resultado else 0 end) as int) as Femenino
                         from tra_pea as pea
                         inner join par_anio as anio on pea.anio_id = anio.id
                         where tipo = '$tipo'
                         group by anio
                         order by anio

                        ) as datos"
                   )
              )

           ->get([
               DB::raw('anio'),
               DB::raw('Masculino'),
               DB::raw('Femenino'),
               DB::raw('Masculino + Femenino as total' )                    
           ]);

       return $data;
    }
}
