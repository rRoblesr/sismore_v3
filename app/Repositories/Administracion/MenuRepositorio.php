<?php

namespace App\Repositories\Administracion;

use App\Models\Administracion\Menu;
use Illuminate\Support\Facades\DB;

class MenuRepositorio
{
  public static function Listar_Nivel01_porUsuario_Sistema($usuario_id, $sistema_id)
  {
    $data = Menu::select(
      'adm_menu.id',
      'adm_menu.dependencia',
      'adm_menu.nombre',
      'adm_menu.url',
      'adm_menu.posicion',
      'adm_menu.icono',
      'adm_menu.parametro'
    )
      ->join('adm_menu_perfil as menPer', 'adm_menu.id', '=', 'menPer.menu_id')
      ->join('adm_perfil as per', 'menPer.perfil_id', '=', 'per.id')
      ->join('adm_usuario_perfil as usuPer', 'per.id', '=', 'usuPer.perfil_id')
      ->where("usuPer.usuario_id", "=", $usuario_id)
      ->where("adm_menu.sistema_id", "=", $sistema_id)
      ->where("adm_menu.estado", "=", 1)
      ->where("adm_menu.dependencia", "=", null)
      ->orderBy('adm_menu.dependencia', 'asc')
      ->orderBy('adm_menu.posicion', 'asc')
      ->get();

    return $data;
  }

  public static function Listar_Nivel02_porUsuario_Sistema($usuario_id, $sistema_id)
  {
    $data = Menu::select(
      'adm_menu.id',
      'adm_menu.dependencia',
      'adm_menu.nombre',
      'adm_menu.url',
      'adm_menu.posicion',
      'adm_menu.icono',
      'adm_menu.parametro'
    )
      ->join('adm_menu_perfil as menPer', 'adm_menu.id', '=', 'menPer.menu_id')
      ->join('adm_perfil as per', 'menPer.perfil_id', '=', 'per.id')
      ->join('adm_usuario_perfil as usuPer', 'per.id', '=', 'usuPer.perfil_id')
      ->where("usuPer.usuario_id", "=", $usuario_id)
      ->where("adm_menu.sistema_id", "=", $sistema_id)
      ->where("adm_menu.estado", "=", 1)
      ->where("adm_menu.dependencia", "!=", null)
      ->orderBy('adm_menu.dependencia', 'asc')
      ->orderBy('adm_menu.posicion', 'asc')
      ->get();

    return $data;
  }

  public static function listarMenu($sistema_id)
  {
    $query = DB::table('adm_menu as v1')
      ->select('v1.*', 'v2.nombre as grupo')
      ->join('adm_menu as v2', 'v2.id', '=', 'v1.dependencia', 'left')
      ->where('v1.sistema_id', $sistema_id)
      ->orderBy('v1.id', 'desc')
      ->get();
    return $query;
  }

  public static function listarGrupo($sistema_id)
  { //tiene un problema con el url cuando es NULO
    $query = DB::table('adm_menu as v1')
      ->select('v1.*')
      ->where('v1.sistema_id', $sistema_id)
      ->where('v1.url', '')
      ->where('v1.dependencia')
      ->where('v1.estado',1)
      ->orderBy('v1.id', 'desc')
      ->get();
    return $query;
  }

  public static function getMenu($sistema_id)
  {
    $query = Menu::where('sistema_id', $sistema_id)->whereNull('dependencia')->orderBy('posicion')->get();
    return $query;
  }
}
