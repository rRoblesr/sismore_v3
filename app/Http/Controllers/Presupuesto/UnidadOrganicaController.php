<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\CategoriaGasto;
use App\Models\Presupuesto\Meta;
use App\Models\Presupuesto\ProductoProyecto;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UEMeta;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Models\Presupuesto\UnidadOrganica;
use App\Repositories\Presupuesto\UnidadOrganicaRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadOrganicaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $anios = Meta::distinct()->select('anio')->get();
        $gobs = TipoGobierno::whereIn('id', [1, 2, 3])->orderBy('id', 'desc')->get();
        $ue = UnidadEjecutora::orderBy('id', 'asc')->get();
        $mensaje = "";
        return view('Presupuesto.UnidadOrganica.Principal', compact('mensaje', 'anios', 'gobs', 'ue'));
    }

    public function listar(Request $rq)
    {
        $data = UnidadOrganica::select(
            'pres_unidadorganica.*',
            'v2.nombre_ejecutora',
            'v2.codigo_ue as codigo2',
            DB::raw($rq->get('anio') . ' as anio'),
        )
            ->join('pres_unidadejecutora as v2', 'v2.id', '=', 'pres_unidadorganica.unidadejecutora_id')
            ->join('pres_pliego as v3', 'v3.id', '=', 'v2.pliego_id')
            ->join('pres_sector as v4', 'v4.id', '=', 'v3.sector_id')
            ->join('pres_tipo_gobierno as v5', 'v5.id', '=', 'v4.tipogobierno_id');
        //$data = $data->where('anio', $rq->get('anio'));
        if ($rq->get('gobierno') > 0) $data = $data->where('v5.id', $rq->get('gobierno'));
        if ($rq->get('ue') > 0) $data = $data->where('v2.id', $rq->get('ue'));
        $data = $data->get();
        return  datatables()::of($data)
            /* ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            }) */
            ->editColumn('codigo2', '<div style="text-align:center">{{$codigo2}}</div>')
            ->editColumn('codigo', '<div style="text-align:center">{{$codigo}}</div>')
            ->addColumn('nmetas', function ($data) {
                $nmetas = UEMeta::select(DB::raw('count(pres_ue_meta.id) as conteo'))
                    ->join('pres_meta as v2', 'v2.id', '=', 'pres_ue_meta.meta_id')
                    ->where('v2.anio', $data->anio)
                    ->where('pres_ue_meta.unidadejecutora_id', $data->unidadejecutora_id)
                    ->where('pres_ue_meta.unidadorganica_id', $data->id);
                $nmetas = $nmetas->first();
                return '<div style="text-align:center">' . $nmetas->conteo . '</div>';
            })
            ->addColumn('action', function ($data) {
                $acciones = '<a href="#" class="btn btn-warning btn-xs" onclick="metas(' . $data->id . ',' . $data->unidadejecutora_id . ',`' . $data->nombre . '`)"  title="ASIGNAR METAS"> <i class="fa fa-cube"></i> </a>';
                $acciones .= '&nbsp;<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                /* if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                } */
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return '<div style="text-align:center">' . $acciones . '</div>';
            })
            ->rawColumns(['action', 'codigo2', 'codigo', 'nmetas'])
            ->make(true);
    }

    public function ajax_edit($uo_id)
    {
        $uo = UnidadOrganica::find($uo_id);
        return response()->json(compact('uo'));
    }
    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->unidadejecutora == '') {
            $data['inputerror'][] = 'unidadejecutora';
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
        return $data;
    }
    public function ajax_add(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $uo = UnidadOrganica::Create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'unidadejecutora_id' => $request->unidadejecutora,
        ]);

        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $uo = UnidadOrganica::find($request->id);
        $uo->codigo = $request->codigo;
        $uo->nombre = $request->nombre;
        $uo->unidadejecutora_id = $request->unidadejecutora;
        $uo->save();

        return response()->json(array('status' => true, 'update' => $request));
    }
    public function ajax_delete($uo_id)
    {
        $uo = UnidadOrganica::find($uo_id);
        $uo->delete();
        return response()->json(array('status' => true, 'uo' => $uo));
    }

    public function cargaruo(Request $rq)
    {
        $uo = UnidadOrganica::where('unidadejecutora_id', $rq->get('ue'));
        $uos = $uo->get();
        return response()->json(compact('uos'));
    }

    public function listarmetas(Request $rq)
    {
        $data = UEMeta::select(
            'v2.*',
            'pres_ue_meta.unidadejecutora_id as ue',
            'pres_ue_meta.unidadorganica_id as uo',
            'pres_ue_meta.id as uem',
        )
            ->join('pres_meta as v2', 'v2.id', '=', 'pres_ue_meta.meta_id')
            ->where('v2.anio', $rq->get('anio'))
            ->where('pres_ue_meta.unidadejecutora_id', $rq->get('ue'))
            ->where(DB::raw('(pres_ue_meta.unidadorganica_id is null or pres_ue_meta.unidadorganica_id = ' . $rq->get('uo') . ')'), true)
            /* ->whereNull('pres_ue_meta.unidadorganica_id')->orWhere('pres_ue_meta.unidadorganica_id', $rq->get('uo')) */;
        $data = $data->get();

        return  datatables()::of($data)
            ->addColumn('action', function ($data) {
                /* if ($data->uo)
                    $acciones = '<a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="quitar(' . $data->uem . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> Quitar</a>';
                else
                    $acciones = '<a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="asignar(' . $data->ue . ',' . $data->id . ')"  title="ASIGNAR"> <i class="fa fa-plus"></i> Asignar</a>'; */
                if ($data->uo) {
                    $acciones = '<div class="pretty p-switch">
                                        <input type="checkbox" onclick="quitar(' . $data->uem . ')" title="QUITAR" checked>
                                        <div class="state p-success"><label></label></div>
                                     </div>';
                } else {
                    $acciones = '<div class="pretty p-switch">
                                        <input type="checkbox" onclick="asignar(' . $data->ue . ',' . $data->id . ')" title="ASIGNAR">
                                        <div class="state p-success"><label></label></div>
                                     </div>';
                }

                return '<div style="text-align:center">' . $acciones . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function asignarmeta(Request $rq)
    {
        /* $pr = UEMeta::Create([
            'unidadejecutora_id' => $rq->get('ue'),
            'meta_id' => $rq->get('meta'),
            'unidadorganica_id' => $rq->get('uo'),
        ]); */
        $uemeta = UEMeta::where('unidadejecutora_id', $rq->get('ue'))->where('meta_id', $rq->get('meta'))->first();
        $uemeta->unidadorganica_id = $rq->get('uo');
        $uemeta->save();
        return response()->json(['status' => true, 'uemeta' => $uemeta]);
    }

    public function quitarmeta(Request $rq)
    {
        $uemeta = UEMeta::find($rq->get('id'));
        $uemeta->unidadorganica_id = null;
        $uemeta->save();
        return response()->json(['status' => true, 'uemeta' => $uemeta]);
    }

    public function ejecuciongasto()
    {
        $anios = BaseGastos::distinct()->select('anio')->get();
        $ue = UnidadEjecutora::orderBy('id', 'asc')->get();
        $catgas = CategoriaGasto::all();
        $articulo = ProductoProyecto::all();
        $mensaje = "";
        return view('Presupuesto.UnidadOrganica.EjecucionGasto', compact('mensaje', 'anios', 'catgas', 'ue', 'articulo'));
    }

    public function ejecuciongastotabla01(Request $rq)
    {
        $data = UnidadOrganicaRepositorio::listar_ejecuciongasto_anio_acticulo_ue_categoria($rq->get('anio'), $rq->get('articulo'), $rq->get('ue'), $rq->get('cg'));

        $foot = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0,];
        foreach ($data['head'] as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['cert'] += $value->cert;
            $foot['dev'] += $value->dev;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? number_format(100 * $foot['dev'] / $foot['pim'], 1) : 0;
        //return response()->json(['head' => $data['head'], 'subhead' => $data['subhead'], 'body' => $data['body'], 'foot' => $foot]);
        return view("Presupuesto.UnidadOrganica.EjecucionGastoTabla1",  ['head' => $data['head']/* , 'subhead' => $data['subhead'], 'body' => $data['body'] */, 'foot' => $foot]); //compact($data['body'], 'foot')
    }
}
