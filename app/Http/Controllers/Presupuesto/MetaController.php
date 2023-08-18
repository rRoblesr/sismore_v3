<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\Meta;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UEMeta;
use App\Models\Presupuesto\UnidadOrganica;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $anios = Meta::distinct()->select('anio')->get();
        $gobs = TipoGobierno::whereIn('id', [1, 2, 3])->orderBy('id', 'desc')->get();
        $uo = UnidadOrganica::orderBy('nombre', 'asc')->get();
        $mensaje = "";
        return view('Presupuesto.Meta.Principal', compact('mensaje', 'gobs', 'uo', 'anios'));
    }

    public function listar(Request $rq)
    {
        $data = UEMeta::select('pres_ue_meta.id', 'anio', 'sec_fun', 'unidad_ejecutora', 'nombre_ejecutora', 'unidadorganica_id')
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_ue_meta.unidadejecutora_id')
            ->join('pres_pliego as v2a', 'v2a.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v2b', 'v2b.id', '=', 'v2a.sector_id')
            ->join('pres_tipo_gobierno as v2c', 'v2c.id', '=', 'v2b.tipogobierno_id')
            ->join('pres_meta as v3', 'v3.id', '=', 'pres_ue_meta.meta_id');
        //->join('pres_unidadorganica as v4', 'v4.id', '=', 'pres_ue_meta.unidadorganica_id');
        if ($rq->get('anio') > 0) $data = $data->where('v3.anio', $rq->get('anio'));
        if ($rq->get('gobierno') > 0) $data = $data->where('v2c.id', $rq->get('gobierno'));
        if ($rq->get('ue') > 0) $data = $data->where('pres_ue_meta.unidadejecutora_id', $rq->get('ue'));
        $data = $data->get();
        //$data = Meta::where('anio', $rq->get('anio'))->orderBy('anio', 'desc')->orderBy('sec_fun', 'asc')->get();
        return  datatables()::of($data)
            ->editColumn('unidad_ejecutora', function ($data) {
                return $data->nombre_ejecutora == '' ? $data->unidad_ejecutora : $data->nombre_ejecutora;
            })
            /* ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            }) */
            ->addColumn('action', function ($data) {
                $acciones = '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                /* if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                } */
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })
            ->addColumn('uo', function ($data) {
                return $data->unidadorganica_id ? UnidadOrganica::find($data->unidadorganica_id)->nombre : '';
            })
            ->rawColumns(['uo', 'action', 'unidad_ejecutora'])
            ->make(true);
    }

    public function ajax_edit($meta_id)
    {
        $data = UEMeta::select(
            'anio',
            'sec_fun',
            'unidad_ejecutora',
            'nombre_ejecutora',
            'pres_ue_meta.id',
            'v2c.id as gob',
            'v2.id as ue',
            'pres_ue_meta.unidadorganica_id as uo',
        )
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_ue_meta.unidadejecutora_id')
            ->join('pres_pliego as v2a', 'v2a.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v2b', 'v2b.id', '=', 'v2a.sector_id')
            ->join('pres_tipo_gobierno as v2c', 'v2c.id', '=', 'v2b.tipogobierno_id')
            ->join('pres_meta as v3', 'v3.id', '=', 'pres_ue_meta.meta_id')
            ->where('pres_ue_meta.id', $meta_id);
        $uemeta = $data->get()->first();
        return response()->json(compact('uemeta'));
    }
    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->fuo == '') {
            $data['inputerror'][] = 'fuo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->fanio == '') {
            $data['inputerror'][] = 'fanio';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->fsec_fun == '') {
            $data['inputerror'][] = 'fsec_fun';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else {
            $meta = Meta::where('anio', $request->fanio)->where('sec_fun', $request->fsec_fun)->first();
            $uemeta = false;
            if ($meta && $request->fid == '') {
                $uemeta = UEMeta::where('unidadejecutora_id', $request->fue)->where('meta_id', $meta->id)->first();
            }
            if ($uemeta) {
                $data['inputerror'][] = 'fsec_fun';
                $data['error_string'][] = 'Este campo ya se esta usando.';
                $data['status'] = FALSE;
            }
        }
        return $data;
    }
    public function ajax_add(Request $rq)
    {
        $val = $this->_validate($rq);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $meta = Meta::where('anio', $rq->fanio)->where('sec_fun', $rq->fsec_fun)->first();
        if (!$meta) {
            $meta = Meta::Create(['anio' => $rq->fanio, 'sec_fun' => $rq->fsec_fun]);
        }
        $uemeta = UEMeta::where('unidadejecutora_id', $rq->fue)->where('meta_id', $meta->id)->first();
        if ($uemeta) {
            return response()->json(array('status' => false));
        } else {
            $uemeta = UEMeta::Create(['unidadejecutora_id' => $rq->fue, 'meta_id' => $meta->id, 'unidadorganica_id' => $rq->fuo]);
            return response()->json(array('status' => true));
        }
        //return response()->json(array('status' => true));
    }
    public function ajax_update(Request $rq)
    {
        $val = $this->_validate($rq);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $meta = Meta::where('anio', $rq->fanio)->where('sec_fun', $rq->fsec_fun)->first();
        if (!$meta) {
            $meta = Meta::Create(['anio' => $rq->fanio, 'sec_fun' => $rq->fsec_fun]);
        }
        $uemeta = UEMeta::find($rq->fid);
        $uemeta->unidadejecutora_id = $rq->fue;
        $uemeta->meta_id = $meta->id;
        $uemeta->unidadorganica_id = $rq->fuo;

        $uemeta_ok = UEMeta::where('unidadejecutora_id', $rq->fue)->where('meta_id', $meta->id)->first();
        if ($uemeta_ok->id == $rq->fid) {
            $uemeta->save();
            return response()->json(array('status' => true,'uemeta'=>$uemeta));
        } else {
            //$uemeta_ok = UEMeta::Create(['unidadejecutora_id' => $rq->fue, 'meta_id' => $meta->id, 'unidadorganica_id' => $rq->fuo]);
            return response()->json(array('status' => false));
        }

        /* $meta = Meta::find($request->id);
        $meta->anio = $request->anio;
        $meta->sec_fun = $request->sec_fun;
        $meta->unidadorganica_id = $request->unidadorganica;
        $meta->save(); */

        //return response()->json(array('status' => true, 'update' => $request));
    }
    public function ajax_delete($uemeta_id)
    {
        $meta = UEMeta::find($uemeta_id);
        $meta->delete();
        return response()->json(array('status' => true, 'meta' => $meta));
    }
}
