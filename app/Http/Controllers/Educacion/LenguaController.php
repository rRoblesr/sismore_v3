<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Lengua;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class LenguaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $mensaje = "";
        return view('parametro.Lengua.principal', compact('mensaje'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = Lengua::orderBy('nombre', 'asc')->get();
        $data = [];
        foreach ($query as $key => $value) {

            $btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            if ($value->estado == 0) {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-power-off"></i></a> ';
            } else {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-check"></i></a> ';
            }
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';

            $data[] = array(
                $key + 1,
                $value->nombre,
                $value->estado,
                "<center>" . $btn1 .  $btn2  . $btn3 . "</center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
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
        } else {
            $rer = Lengua::where('nombre', $request->nombre)->get();
            if ($rer->count() > 0 && $request->id == '') {
                $data['inputerror'][] = 'nombre';
                $data['error_string'][] = 'Este Código Ya Existe.';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
    public function ajax_add(Request $request)
    {
        $this->_validate($request);
        $rer = Lengua::Create([
            'nombre' => $request->nombre,
            'estado' => 0,
        ]);
        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $rer = Lengua::find($request->id);
        $rer->nombre = $request->nombre;
        $rer->save();

        return response()->json(array('status' => true));
    }
    public function ajax_edit($id)
    {
        $info = Lengua::find($id);
        return response()->json(compact('info'));
    }
    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = Lengua::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }
    public function ajax_estado($id)
    {
        $rer = Lengua::find($id);
        $rer->estado = $rer->estado == 1 ? 0 : 1;
        $rer->save();
        return response()->json(array('status' => true, 'estado' => $rer->estado));
    }
}
