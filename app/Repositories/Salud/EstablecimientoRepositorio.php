<?php

namespace App\Repositories\Salud;

use App\Models\Salud\Establecimiento;
use Illuminate\Support\Facades\DB;

class EstablecimientoRepositorio
{

 public static function listar($sector)
 {
  $query = Establecimiento::from('sal_establecimiento as es')->select('re.nombre red', 'mi.nombre microred', 'es.cod_unico', 'es.nombre_establecimiento eess')
   ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
   ->join('sal_red as re', 're.id', '=', 'mi.red_id')
   ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
   ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
   ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
    $join->on('te.id', '=', 'en.tipoentidad_id')
     ->where('te.sector_id', '=', $sector);
   })
   ->where('es.estado', 'activo')
   // ->where('re.codigo', '03')
   ->orderBy('en.codigo')
   ->get();
  return $query;
 }
}
