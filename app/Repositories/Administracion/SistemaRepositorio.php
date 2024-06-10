<?php

namespace App\Repositories\Administracion;

use App\Models\Administracion\Sistema;
use Illuminate\Support\Facades\DB;

class SistemaRepositorio
{
  public static function Listar_porUsuario($usuario_id)
  {
    $data = Sistema::select('adm_sistema.id as sistema_id', 'adm_sistema.nombre', 'adm_sistema.icono')
      ->join('adm_perfil', 'adm_perfil.sistema_id', '=', 'adm_sistema.id')
      ->join('adm_usuario_perfil', 'adm_usuario_perfil.perfil_id', '=', 'adm_perfil.id')
      ->where("adm_usuario_perfil.usuario_id", "=", $usuario_id)
      ->where("adm_perfil.estado", "=", 1)
      ->orderBy('adm_sistema.pos')
      ->get();

    return $data;
  }
  public static function listar_porperfil($perfil_id)
  {
    $query = Sistema::select('adm_sistema.*')
      ->join('adm_perfil_admin_sistema as v2', 'v2.sistema_id', '=', 'adm_sistema.id')
      ->where('v2.perfil_id', $perfil_id)
      ->where('adm_sistema.estado', '1')
      ->orderBy('adm_sistema.nombre')
      ->get();
    return $query;
  }


  /* public static function listar_porusuariosistemachecked($usuario_id)
  {
    $query = Sistema::select(
      'adm_sistema.id',
      'adm_sistema.nombre',
      DB::raw('ifnull((select "checked" from adm_usuario_sistema where usuario_id=' . $usuario_id . ' and sistema_id=adm_sistema.id),"") as elegido')
    )
      ->join('adm_usuario_sistema as v2', 'v2.sistema_id', '=', 'adm_sistema.id')
      ->where('v2.usuario_id', session()->get('usuario_id'))
      ->orderBy('adm_sistema.nombre')->get();
    return $query;
  } */
  public static function listar_sistemasconusuarios($usuario_id)
  {
    $query = DB::table('adm_sistema as v1')
      ->select('v1.id', 'v1.nombre', 'v1.icono', DB::raw('(select count(v2.id) from `adm_perfil` as `v2`
      inner join `adm_usuario_perfil` as `v3` on `v3`.`perfil_id` = `v2`.`id`
      where v2.sistema_id=v1.id) as nrousuario'))
      ->where('v1.estado', '1')
      ->orderBy('v1.nombre')
      ->get();
    return $query;
  }

  public static function listarSistemaPerfil($perfil_id, $sistema_id)
  {
    $query = Sistema::where('estado', '1')
      ->select('id', 'nombre', 'icono', DB::raw('(ifnull((SELECT id FROM adm_perfil_admin_sistema WHERE perfil_id=' . $perfil_id . ' AND sistema_id=adm_sistema.id),0)) as status'))
      ->orderBy('nombre')
      ->get();
    return $query;
  }

  public static function accesopublico()
  {
    $query = Sistema::select('id as sistema_id', 'nombre', 'icono')->where('id', '!=', 4)->orderBy('pos')->get();
    return $query;
  }
}
