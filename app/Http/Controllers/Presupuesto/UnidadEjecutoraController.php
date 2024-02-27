<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UnidadEjecutora;
use Illuminate\Http\Request;

class UnidadEjecutoraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $gobs = TipoGobierno::whereIn('id', [1, 2, 3])->orderBy('id', 'desc')->get();
        $mensaje = "";
        return view('Presupuesto.UnidadEjecutora.Principal', compact('mensaje', 'gobs'));
    }

    public function listar(Request $rq)
    {
        $data = UnidadEjecutora::select('pres_unidadejecutora.*')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id');
        if ($rq->get('gobierno') > 0) $data = $data->where('v4.id', $rq->get('gobierno'));
        if ($rq->get('sector') > 0) $data = $data->where('v3.id', $rq->get('sector'));
        if ($rq->get('pliego') > 0) $data = $data->where('v2.id', $rq->get('pliego'));
        $data = $data->get();/* ->orderBy('id', 'desc') */

        return  datatables()::of($data)
            /* ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            }) */
            ->addColumn('action', function ($data) {
                $acciones = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                /* if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                } */
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function ajax_edit($ue_id)
    {
        $ue = UnidadEjecutora::find($ue_id);

        return response()->json(compact('ue'));
    }
    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        /*  if ($request->secuencia_ejecutora == '') {
            $data['inputerror'][] = 'secuencia_ejecutora';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } */

        /* if ($request->codigo_ue == '') {
            $data['inputerror'][] = 'codigo_ue';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } */

        /* if ($request->unidad_ejecutora == '') {
            $data['inputerror'][] = 'unidad_ejecutora';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } */
        return $data;
    }
    public function ajax_add(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $ue = UnidadEjecutora::Create([
            //'secuencia_ejecutora' => $request->secuencia_ejecutora,
            //'codigo_ue' => $request->codigo_ue,
            //'unidad_ejecutora' => $request->unidad_ejecutora,
            'nombre_ejecutora' => $request->nombre_ejecutora,
            'abreviatura' => $request->abreviatura,
        ]);

        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $ue = UnidadEjecutora::find($request->id);
        //$ue->secuencia_ejecutora = $request->secuencia_ejecutora;
        //$ue->codigo_ue = $request->codigo_ue;
        //$ue->unidad_ejecutora = $request->unidad_ejecutora;
        $ue->nombre_ejecutora = $request->nombre_ejecutora;
        $ue->abreviatura = $request->abreviatura;
        $ue->save();

        return response()->json(array('status' => true));
    }
    public function ajax_delete($ue_id)
    {
        $ue = UnidadEjecutora::find($ue_id);
        $ue->delete();
        return response()->json(array('status' => true, 'ue' => $ue));
    }

    public function cargarue(Request $rq)
    {
        $ue = UnidadEjecutora::select('pres_unidadejecutora.*')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id');
        if ($rq->get('pliego') > 0) $ue = $ue->where('v2.id', $rq->get('pliego'));
        if ($rq->get('sector') > 0) $ue = $ue->where('v3.id', $rq->get('sector'));
        if ($rq->get('gobierno') > 0) $ue = $ue->where('v4.id', $rq->get('gobierno'));
        $ues = $ue->get();
        return response()->json(compact('ues'));
    }
}
