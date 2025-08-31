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
      'adm_menu.parametro',
      'adm_menu.link',
      'adm_menu.tipo_enlace',
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

  /* 
  * Listar Nivel 2
  *  Nivel 1: dependencia = null
  *  Nivel 2: dependencia = id de Nivel 1
  *  Nivel 3: dependencia = id de Nivel 2     
  */
  public static function Listar_Nivel02_porUsuario_Sistema($usuario_id, $sistema_id)
  {
    $data = Menu::from('adm_menu as sm')->select(
      'sm.id',
      'sm.dependencia',
      'sm.nombre',
      'sm.url',
      'sm.posicion',
      'sm.icono',
      'sm.parametro',
      'sm.link',
      'sm.tipo_enlace',
    )
      ->join('adm_menu as m', 'm.id', '=', 'sm.dependencia')
      ->join('adm_menu_perfil as mp', 'sm.id', '=', 'mp.menu_id')
      ->join('adm_perfil as p', 'mp.perfil_id', '=', 'p.id')
      ->join('adm_usuario_perfil as up', 'p.id', '=', 'up.perfil_id')
      ->where("up.usuario_id", "=", $usuario_id)
      ->where("m.sistema_id", "=", $sistema_id)
      ->where("m.estado", "=", 1)
      ->where("sm.estado", "=", 1)
      ->where("m.dependencia", "=", null)
      ->orderBy('m.posicion', 'asc')
      ->orderBy('sm.posicion', 'asc')
      ->get();

    return $data;
  }

  /* 
  * Listar Nivel 2 y Nivel 3 juntos
  *  Nivel 1: dependencia = null
  *  Nivel 2: dependencia = id de Nivel 1
  *  Nivel 3: dependencia = id de Nivel 2   
  */
  public static function Listar_Nivel02_porUsuario_Sistemax($usuario_id, $sistema_id)
  {
    $data = Menu::select(
      'adm_menu.id',
      'adm_menu.dependencia',
      'adm_menu.nombre',
      'adm_menu.url',
      'adm_menu.posicion',
      'adm_menu.icono',
      'adm_menu.parametro',
      'adm_menu.link',
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

  /* 
  * Listar Nivel 3
  *  Nivel 1: dependencia = null
  *  Nivel 2: dependencia = id de Nivel 1
  *  Nivel 3: dependencia = id de Nivel 2     
  */
  public static function Listar_Nivel03_porUsuario_Sistema($usuario_id, $sistema_id)
  {
    $data = Menu::from('adm_menu as ssm')->select(
      'ssm.id',
      'ssm.dependencia',
      'ssm.nombre',
      'ssm.url',
      'ssm.posicion',
      'ssm.icono',
      'ssm.parametro',
      'ssm.link',
      'ssm.tipo_enlace',
    )
      ->join('adm_menu as sm', 'sm.id', '=', 'ssm.dependencia')
      ->join('adm_menu as m', 'm.id', '=', 'sm.dependencia')
      ->join('adm_menu_perfil as mp', 'ssm.id', '=', 'mp.menu_id')
      ->join('adm_perfil as p', 'mp.perfil_id', '=', 'p.id')
      ->join('adm_usuario_perfil as up', 'p.id', '=', 'up.perfil_id')
      ->where("up.usuario_id", "=", $usuario_id)
      ->where("m.sistema_id", "=", $sistema_id)
      ->where("m.estado", "=", 1)
      ->where("m.dependencia", "=", null)
      ->orderBy('m.posicion', 'asc')
      ->orderBy('sm.posicion', 'asc')
      ->orderBy('ssm.posicion', 'asc')
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
      ->select('v1.id', 'v1.nombre')
      ->where('v1.sistema_id', $sistema_id)
      ->where('v1.url', '')
      ->where('v1.dependencia')
      ->where('v1.estado', 1)
      ->orderBy('v1.posicion', 'asc')
      ->get();
    return $query;
  }

  public static function listarNivel2($sistema_id, $nivel1)
  { //tiene un problema con el url cuando es NULO
    $query = menu::select('id', 'nombre')->where('sistema_id', $sistema_id)
      ->where('dependencia', $nivel1)
      ->where('estado', 1)
      ->orderBy('posicion', 'asc')
      ->get();
    return $query;
  }

  public static function getMenu($sistema_id)
  {
    $query = Menu::where('sistema_id', $sistema_id)->whereNull('dependencia')->orderBy('posicion')->get();
    return $query;
  }
}
