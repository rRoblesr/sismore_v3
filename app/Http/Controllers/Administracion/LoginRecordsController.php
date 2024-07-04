<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\LoginRecords;
use App\Models\Parametro\Icono;
use App\Models\Administracion\Sistema;
use App\Models\Administracion\Usuario;
use App\Repositories\Administracion\EntidadRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginRecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reporte()
    {
        $data = LoginRecords::all();
        return view('administracion.LoginRecords.Reporte', compact('data'));
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = LoginRecords::orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $usu = Usuario::find($value->usuario);
            $ofi = EntidadRepositorio::migas($usu->entidad);
            $data[] = array(
                $key + 1,
                $usu->nombre . ', ' . $usu->apellido1 . ' ' . $usu->apellido2,
                $ofi ? $ofi->entidadn : '',
                $ofi ? $ofi->oficinan : '',
                $value->login,
                $value->logout,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            // "ofi" => $ofi,
            // "usu" => $usu,
        );
        return response()->json($result);
    }

    public function listarjjjDT()
    {
        $data = Sistema::orderBy('pos', 'desc')->get();
        return  datatables()::of($data)
            ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            })
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
            ->rawColumns(['action', 'estado', 'icono'])
            ->make(true);
    }

    public function ajax_edit($sistema_id)
    {
        $sistema = Sistema::find($sistema_id);

        return response()->json(compact('sistema'));
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

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
        $perfil = Sistema::Create([
            'nombre' => $request->nombre,
            'icono' => $request->icono,
            'pos' => $request->pos,
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
        $sistema = Sistema::find($request->id);
        $sistema->nombre = $request->nombre;
        $sistema->icono = $request->icono;
        $sistema->pos = $request->pos;
        $sistema->save();

        return response()->json(array('status' => true, 'update' => $request));
    }
    public function ajax_delete($sistema_id)
    {
        $sistema = Sistema::find($sistema_id);
        $sistema->delete();
        return response()->json(array('status' => true, 'sistema' => $sistema));
    }
    public function ajax_estado($sistema_id)
    {
        $sistema = Sistema::find($sistema_id);
        $sistema->estado = $sistema->estado == 1 ? 0 : 1;
        $sistema->save();
        return response()->json(array('status' => true, 'estado' => $sistema->estado));
    }
}
