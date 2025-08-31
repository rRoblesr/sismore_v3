<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Menu;
use App\Repositories\Administracion\MenuRepositorio;
use App\Repositories\Administracion\SistemaRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        // $sistema_id = session('sistema_id');
        // $v1 = session('perfils');
        // $v2 =  session('perfils')->where('sistema_id', $sistema_id)->first()->perfil_id;
        // return compact('v1', 'v2', 'sistema_id');


        // return session('perfils');
        // return session('perfils')->where('sistema_id', 4)->first()->perfil_id;

        // return session('perfil_administrador_id');
        $sistemas = SistemaRepositorio::listar_porperfil(session('perfil_administrador_id'));
        return view('administracion.Menu.Principal', compact('sistemas'));
    }

    public function principalLink()
    {
        $sistemas = SistemaRepositorio::listar_porperfil(session('perfil_administrador_id'));
        return view('administracion.Menu.PrincipalLink', compact('sistemas'));
    }

    public function listarDT($sistema_id)
    {
        $data = MenuRepositorio::listarMenu($sistema_id);

        return  datatables()::of($data)
            ->addColumn('action', function ($data) {
                $acciones = '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';

                if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                }
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })
            ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                return $data->estado == 0 ? '<span class="badge badge-danger">DESABILITADO</span>' : '<span class="badge badge-success">ACTIVO</span>';
            })
            ->editColumn('grupo', function ($oo) {
                return $oo->grupo == '' ? '_menu' : '_' . $oo->grupo;
            })
            ->rawColumns(['action', 'icono', 'estado', 'grupo'])
            ->make(true);
    }

    public function cargarGrupo($sistema_id)
    {
        $grupo = MenuRepositorio::listarGrupo($sistema_id);
        return response()->json(compact('grupo'));
    }

    public function ajax_edit($menu_id)
    {
        $menu = Menu::find($menu_id);
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
        /*if ($request->dependencia == '') {
            $data['inputerror'][] = 'dependencia';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }*/
        if ($request->nombre == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->url == '' && $request->dependencia) {
            $data['inputerror'][] = 'url';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->posicion == '') {
            $data['inputerror'][] = 'posicion';
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
        $menu = Menu::Create([
            'sistema_id' => $request->sistema_id,
            'dependencia' => $request->dependencia,
            'nombre' => $request->nombre,
            'url' => ($request->dependencia ? $request->url : ""),
            'posicion' => $request->posicion,
            'icono' => $request->icono,
            'parametro' => $request->parametro,
            'link' => "",
            'estado' => '1',
        ]);

        return response()->json(array('status' => true));
    }

    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $menu = Menu::find($request->id);
        $menu->sistema_id = $request->sistema_id;
        $menu->dependencia = $request->dependencia;
        $menu->nombre = $request->nombre;
        $menu->url = $request->url == null ? '' : $request->url;
        $menu->posicion = $request->posicion;
        $menu->icono = $request->icono;
        $menu->parametro = $request->parametro;
        $menu->link = "";
        $menu->save();

        return response()->json(array('status' => true, 'menu' => $request->url));
    }

    public function ajax_delete($menu_id)
    {
        $menu = Menu::find($menu_id);
        $menu->delete();
        return response()->json(array('status' => true));
    }

    public function ajax_estado($menu_id)
    {
        $menu = Menu::find($menu_id);
        $menu->estado = $menu->estado == 1 ? 0 : 1;
        $menu->save();
        return response()->json(array('status' => true, 'estado' => $menu->estado));
    }

    public function listarDTLink($sistema_id)
    {
        $data = MenuRepositorio::listarMenu($sistema_id);

        return  datatables()::of($data)
            ->addColumn('action', function ($data) {
                if ($data->id == 100 || $data->id == 150 || $data->id == 149)
                    $acciones = '';
                else
                    $acciones = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';

                if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-xs btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-xs btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                }
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return '<center><div class="btn-group">' . $acciones . '</div></center>';
            })
            ->editColumn('icono', '<center><i class="{{$icono}}"></i></center>')
            ->editColumn('link', function ($data) {
                if ($data->link == '') return '';
                else
                    return '<textarea id="xvxv" name="xvxv" class="form-control" cols="30" rows="5">' . $data->link . '</textarea>';
            })
            ->editColumn('estado', function ($data) {
                return '<center>' . ($data->estado == 0 ? '<span class="badge badge-danger">DESABILITADO</span>' : '<span class="badge badge-success">ACTIVO</span>') . '</center>';
            })
            ->editColumn('grupo', function ($oo) {
                return $oo->grupo == '' ? '_menu' : '_' . $oo->grupo;
            })
            ->editColumn('posicion', '<center>{{$posicion}}</center>')
            ->rawColumns(['action', 'icono', 'estado', 'grupo', 'link', 'posicion'])
            ->make(true);
    }

    private function _validate_link($request)
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
        if ($request->link == '' && $request->dependencia > 0) {
            $data['inputerror'][] = 'link';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->posicion == '') {
            $data['inputerror'][] = 'posicion';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        // return $data;
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add_link(Request $request)
    {
        $this->_validate_link($request);

        $menu = Menu::Create([
            'sistema_id' => $request->sistema_id,
            'dependencia' => $request->dependencia == 0 ? NULL : $request->dependencia,
            'nombre' => $request->nombre,
            'url' => ($request->dependencia > 0 ? "powerbi.salud.menu" : ($request->link == "" ? "" : "powerbi.salud.menu")),
            'posicion' => $request->posicion,
            'icono' => $request->icono,
            'parametro' => NULL,
            'link' => $request->link == '' ? '' : $request->link,
            'estado' => '1',
        ]);

        return response()->json(array('status' => true, 'menu' => $menu));
    }

    public function ajax_update_link(Request $request)
    {
        $this->_validate_link($request);

        $menu = Menu::find($request->id);
        $menu->sistema_id = $request->sistema_id;
        $menu->dependencia = $request->dependencia == 0 ? NULL : $request->dependencia;
        $menu->nombre = $request->nombre;
        $menu->url = $request->link == '' ? '' : "powerbi.salud.menu";
        $menu->posicion = $request->posicion;
        $menu->icono = $request->icono;
        $menu->parametro = NULL;
        $menu->link = $request->link == '' ? '' : $request->link;
        $menu->save();

        return response()->json(array('status' => true, 'menu' => $menu));
    }
}
