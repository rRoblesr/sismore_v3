<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\RER;
use App\Models\Educacion\PadronRER;
use App\Models\Salud\DirectorioPN;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class DirectorioPNController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $mensaje = "";
        return view('salud.DirectorioPN.Principal', compact('mensaje'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = DirectorioPN::orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            if ($value->estado == 0) {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-power-off"></i></a> ';
            } else {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-check"></i></a> ';
            }
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            // $btn4 = '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';

            $data[] = array(
                $key + 1,
                $value->dni,
                $value->nombres . ' ' . $value->apellido_paterno . ' ' . $value->apellido_materno,
                $value->profesion,
                $value->cargo,
                $value->condicion_laboral,
                $value->celular,
                "<center>" . $btn1 . $btn2  . $btn3 . "</center>",
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

        $usuarioxx = DirectorioPN::where('dni', $request->dni)->first();

        if ($request->dni == '') {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->dni) < 8) {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'Este campo necesita 8 digitos.';
            $data['status'] = FALSE;
        } else if ($usuarioxx && $request->id == '') {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'DNI ingresado ya existe.';
            $data['status'] = FALSE;
        }

        if ($request->nombres == '') {
            $data['inputerror'][] = 'nombres';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->apellido_paterno == '') {
            $data['inputerror'][] = 'apellido_paterno';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->apellido_materno == '') {
            $data['inputerror'][] = 'apellido_materno';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->profesion == '') {
            $data['inputerror'][] = 'profesion';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->cargo == '') {
            $data['inputerror'][] = 'cargo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->condicion_laboral == '') {
            $data['inputerror'][] = 'condicion_laboral';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->nivel == '0') {
            $data['inputerror'][] = 'nivel';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->entidadn == 0) {
            $data['inputerror'][] = 'entidadn';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->celular == '') {
            $data['inputerror'][] = 'celular';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->email == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
    public function ajax_add(Request $request)
    {
        $this->_validate($request);
        $rer = DirectorioPN::Create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'profesion' => $request->profesion,
            'cargo' => $request->cargo,
            'condicion_laboral' => $request->condicion_laboral,
            'nivel' => $request->nivel,
            'codigo' => $request->entidad,
            'celular' => $request->celular,
            'email' => $request->email,
        ]);
        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $rer = DirectorioPN::find($request->id);
        $rer->dni = $request->dni;
        $rer->nombres = $request->nombres;
        $rer->apellido_paterno = $request->apellido_paterno;
        $rer->apellido_materno = $request->apellido_materno;
        $rer->profesion = $request->profesion;
        $rer->cargo = $request->cargo;
        $rer->condicion_laboral = $request->condicion_laboral;
        $rer->nivel = $request->nivel;
        $rer->codigo = $request->codigo;
        $rer->celular = $request->celular;
        $rer->email = $request->email;
        $rer->save();

        return response()->json(array('status' => true));
    }
    public function ajax_edit($id)
    {
        $dpn = DirectorioPN::find($id);
        $ent = Entidad::find($dpn->codigo);
        if($ent){
            $dpn->entidadn=$ent->codigo . ' ' . $ent->nombre;
        }
        return response()->json(compact('dpn'));
    }
    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = RER::find($id);
        $rer->delete();
        return response()->json(array('status' => true, 'rer' => $rer));
    }
    public function ajax_estado($id)
    {
        $rer = DirectorioPN::find($id);
        $rer->estado = $rer->estado == 1 ? 0 : 1;
        $rer->save();
        return response()->json(array('status' => true, 'estado' => $rer->estado));
    }

    public function completarred(Request $rq)
    {
        $term = $rq->get('term');
        $query = RER::where(DB::raw("concat(' ',codigo_rer,nombre)"), 'like', "%$term%")->where('estado', 0)->orderBy('nombre', 'asc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                "label" => $value->codigo_rer . ' | ' . $value->nombre,
                "id" => $value->id
            ];
        }
        return $data; //response()->json('data');
    }

    public function ajax_cargar(Request $rq)
    {
        $nivel = $rq->get('nivel');
        $ugel = $rq->get('ugel');
        $rer = RER::select('edu_rer.id', 'edu_rer.nombre')
            ->join('edu_padron_rer as v2', 'v2.rer_id', '=', 'edu_rer.id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v2.institucioneducativa_id');
        if ($nivel != 0)
            $rer = $rer->where('v3.NivelModalidad_id', $nivel);
        if ($ugel != 0)
            $rer = $rer->where('v3.Ugel_id', $ugel);
        $rer = $rer->distinct();
        $rer = $rer->orderBy('nombre', 'asc');
        $rer = $rer->get();
        return response()->json(compact('rer'));
    }
}
