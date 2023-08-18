<?php

namespace App\Repositories\Administracion;

use App\Models\Administracion\Menu;
use App\Models\Administracion\Sistema;
use Illuminate\Support\Facades\DB;

class PerfilAdminSistemaRepositorio
{
    public static function listarSistemas_perfil($perfil_id)
    {
      $data = Sistema::select('adm_sistema.id', 'adm_sistema.nombre', 'adm_sistema.icono')
        ->join('adm_perfil_admin_sistema as v2', 'v2.sistema_id', '=', 'adm_sistema.id')
        ->where("adm_sistema.estado",'1')
        ->where("v2.perfil_id",$perfil_id)
        ->orderBy('adm_sistema.nombre')
        ->get();
  
      return $data;
    }
}
