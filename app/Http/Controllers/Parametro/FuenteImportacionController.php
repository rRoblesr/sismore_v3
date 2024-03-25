<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Icono;
use App\Models\Administracion\Sistema;
use App\Models\Parametro\FuenteImportacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FuenteImportacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function principal()
    {
        $sistema = Sistema::all();
        return view('parametro.FuenteImportacion.Principal', compact('sistema'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = FuenteImportacion::join('adm_sistema as s', 's.id', '=', 'par_fuenteimportacion.sistema_id')->select(
            'par_fuenteimportacion.id',
            'par_fuenteimportacion.sistema_id',
            'par_fuenteimportacion.codigo',
            'par_fuenteimportacion.nombre',
            'par_fuenteimportacion.formato',
            's.nombre as sistema'
        )->orderBy('par_fuenteimportacion.id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $btn = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            // $btn .= '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';

            $data[] = array(
                $key + 1,
                "<center>$value->id</center>",
                $value->sistema,
                $value->codigo,
                $value->nombre,
                $value->formato,
                "<center>$btn</center>",
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

        if ($request->sistema == '') {
            $data['inputerror'][] = 'sistema';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->codigo == '') {
            $data['inputerror'][] = 'codigo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->nombre == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->formato == '') {
            $data['inputerror'][] = 'formato';
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

        $objfuente = FuenteImportacion::where('sistema_id', $request->sistema)->orderBy('codigo', 'desc')->first();
        $codigo = '';
        if ($objfuente) {
            $valor = (int)substr($objfuente->codigo, 3, 5);
            $valor++;
            $ceros = '';
            for ($i = 0; $i < 2 - strlen('' . $valor); $i++) {
                $ceros .= '0';
            }
            $codigo = 'COD' . $codigo . $valor;
        } else {
            $codigo = 'COD01';
        }
        $obj = FuenteImportacion::Create([
            'codigo' => $codigo,
            'sistema_id' => $request->sistema,
            'nombre' => $request->nombre,
            'formato' => $request->formato
        ]);
        return response()->json(array('status' => true));
    }

    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $obj = FuenteImportacion::find($request->id);
        // $obj->id = $request->identificador;
        $obj->sistema_id = $request->sistema;
        $obj->codigo = $request->codigo;
        $obj->nombre = $request->nombre;
        $obj->formato = $request->formato;
        $obj->save();

        return response()->json(array('status' => true));
    }

    public function ajax_edit($id)
    {
        $obj = FuenteImportacion::find($id);
        return response()->json(compact('obj'));
    }

    public function ajax_delete($id) //elimina deverdad *o*
    {
        $obj = FuenteImportacion::find($id);
        $obj->delete();
        return response()->json(array('status' => true));
    }

    public function cargar($sistema_id)
    {
        $query = FuenteImportacion::where('sistema_id', $sistema_id)->get();
        return response()->json(array('fuenteimportacions' => $query));
    }
}
