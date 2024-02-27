<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Menu;
use App\Models\Administracion\Menuperfil;
use App\Models\Administracion\Perfil;
use App\Models\Administracion\PerfilAdminSistema;
use App\Models\Administracion\Sistema;
use App\Repositories\Administracion\MenuRepositorio;
use App\Repositories\Administracion\PerfilAdminSistemaRepositorio;
use App\Repositories\Administracion\SistemaRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $sistemas = SistemaRepositorio::listar_porperfil(session('perfil_administrador_id'));
        return view('administracion.Perfil.Principal', compact('sistemas'));
    }

    public function listarDT($sistema_id)
    {
        $data = Perfil::where('sistema_id', $sistema_id)->get();

        return  datatables()::of($data)
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            })
            ->addColumn('sistemas', function ($data) {
                $sistemas = PerfilAdminSistemaRepositorio::listarSistemas_perfil($data->id);
                $html = '';
                foreach ($sistemas as $key => $item) {
                    $html .= '<span class="badge badge-dark"><i class="' . $item->icono . '"></i> </span> <span class="badge badge-secondary"> SISTEMA ' . $item->nombre . '</span><br/>';
                }
                return $html;
            })
            ->addColumn('action', function ($data) {
                $acciones = '';
                $acciones .= '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                if ($data->sistema_id == 4)
                    $acciones .= '&nbsp;<a href="#" class="btn btn-purple btn-sm" onclick="sistema(' . $data->id . ')" title="AGREGAR SISTEMAS"> <i class="ion ion-md-cube"></i> </a>';
                $acciones .= '&nbsp;<a href="#" class="btn btn-warning btn-sm" onclick="menu(' . $data->id . ')" title="AGREGAR MENU"> <i class="fa fa-list-ul"></i> </a>';

                if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                }
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')" title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })

            ->rawColumns(['sistemas', 'action', 'estado'])
            ->make(true);
    }

    public function ajax_edit($perfil_id)
    {
        $menu = Perfil::find($perfil_id);

        return response()->json(compact('menu'));
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->sistema_id == '') {
            $data['inputerror'][] = 'sistema_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->nombre == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        return $data;
    }

    public function ajax_add(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $perfil = Perfil::Create([
            'sistema_id' => $request->sistema_id,
            'nombre' => $request->nombre,
            'estado' => '1',
        ]);

        $menu = Menu::where('sistema_id', $request->sistema_id)->where('dependencia')->where('posicion', '1')->where('estado', '1')->first();
        if ($menu)
            Menuperfil::Create(['perfil_id' => $perfil->id, 'menu_id' => $menu->id]);
        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $perfil = Perfil::find($request->id);
        $perfil->sistema_id = $request->sistema_id;
        $perfil->nombre = $request->nombre;
        $perfil->save();

        return response()->json(array('status' => true, 'update' => $request, 'perfil' => $perfil));
    }
    public function ajax_delete($perfil_id)
    {
        $perfil = Perfil::find($perfil_id);
        $perfil->delete();
        return response()->json(array('status' => true, 'perfil' => $perfil));
    }

    public function listarmenu($perfil_id, $sistema_id)
    {
        $datas = MenuRepositorio::getMenu($sistema_id);
        $ticket = '';
        $ticket .= '<input type="hidden" class="form-control" name="perfil" id="perfil" value="' . $perfil_id . '">';
        $ticket .= '<ul >'; //class="checktree"
        foreach ($datas as $value) {
            $perfilmenu = Menuperfil::where('perfil_id', $perfil_id)->where('menu_id', $value->id)->first();
            $ticket .= '<li><label>';
            $ticket .= '<input id="menu" name="menu[]" type="checkbox" value="' . $value->id . '" ' . (isset($perfilmenu->id) ? 'checked' : '') . '> ' . $value->nombre;
            $ticket .= '</label><ul>';
            $menus = Menu::where('dependencia', $value->id)->get();
            foreach ($menus as $menu) {
                $perfilmenus = Menuperfil::where('perfil_id', $perfil_id)->where('menu_id', $menu->id)->first();
                $ticket .= '<li><label>';
                $ticket .= '<input id="menu" name="menu[]" type="checkbox" value="' . $menu->id . '" ' . (isset($perfilmenus->id) ? 'checked' : '') . '> ' . $menu->nombre;
                $ticket .= '</label></li>';
            }
            $ticket .= '</ul></li>';
        }
        $ticket .= '</ul>';
        return  $ticket;
    }
    public function ajax_add_menu(Request $request)
    {
        $modulos = Menu::where('sistema_id', $request->msistema_id)->get();
        foreach ($modulos as $modulo) {
            if ($request->menu) {
                $encontrado = false;
                foreach ($request->menu as $menu) {
                    if ($menu == $modulo->id) {
                        $encontrado = true;
                        $menuperfil = Menuperfil::where('perfil_id', $request->perfil)->where('menu_id', $menu)->first();
                        if (!$menuperfil) {
                            Menuperfil::Create(['perfil_id' => $request->perfil, 'menu_id' => $menu]);
                        }
                        break;
                    }
                }
                if ($encontrado == false) {
                    Menuperfil::where('perfil_id', $request->perfil)->where('menu_id', $modulo->id)->delete();
                }
            } else {
                Menuperfil::where('perfil_id', $request->perfil)->where('menu_id', $modulo->id)->delete();
            }
        }
        return response()->json(array('status' => true));
    }
    public function ajax_estado($perfil_id)
    {
        $perfil = Perfil::find($perfil_id);
        $perfil->estado = $perfil->estado == 1 ? 0 : 1;
        $perfil->save();
        return response()->json(array('status' => true, 'estado' => $perfil->estado));
    }
    public function listarsistema($perfil_id, $sistema_id)
    {
        $data = SistemaRepositorio::listarSistemaPerfil($perfil_id, $sistema_id);
        return response()->json(array('status' => true, 'sistemas' => $data));
    }
    public function ajax_add_sistema(Request $request)
    {
        $sistemas = Sistema::where('estado', '1')->get();
        foreach ($sistemas as $sistema) {
            if ($request->csistemas) {
                $encontrado = false;
                foreach ($request->csistemas as $csistema) {
                    if ($csistema == $sistema->id) {
                        $encontrado = true;
                        $pas = PerfilAdminSistema::where('perfil_id', $request->cperfil_id)->where('sistema_id', $csistema)->first();
                        if (!$pas) {
                            PerfilAdminSistema::Create(['perfil_id' => $request->cperfil_id, 'sistema_id' => $csistema]);
                        }
                        break;
                    }
                }
                if ($encontrado == false) {
                    PerfilAdminSistema::where('perfil_id', $request->cperfil_id)->where('sistema_id', $sistema->id)->delete();
                }
            } else {
                PerfilAdminSistema::where('perfil_id', $request->cperfil_id)->where('sistema_id', $sistema->id)->delete();
            }
        }
        return response()->json(array(
            'status' => true, 'csistemas' => $request->csistemas, 'sistemas' => $sistemas, 'csistema_id' => $request->csistema_id, 'cperfil_id' => $request->cperfil_id
        ));
    }
}
