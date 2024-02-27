<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\EspecificaDetalleGasto;
use App\Models\Presupuesto\Funcion;
use App\Models\Presupuesto\GenericaGasto;
use App\Models\Presupuesto\PartidasRestringidas;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Models\Presupuesto\UnidadOrganica;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecificaDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function partidasrestringidas()
    {
        $anios = BaseGastos::distinct()->select('anio')->orderBy('anio')->get();
        $anio = $anios->max('anio');
        $generica = GenericaGasto::orderBy('codigo', 'asc')->get();
        $mensaje = "";
        return view('Presupuesto.EspecificaDetalle.restringidas', compact('mensaje', 'generica', 'anios', 'anio'));
    }

    public function listarpartidasrestringidas(Request $rq)
    {

        $data = PartidasRestringidas::select(
            'pres_partidasrestringidas.id',
            'pres_partidasrestringidas.anio',
            'pres_partidasrestringidas.estado',
            DB::raw('concat("2.",v6.codigo,".",v5.codigo,".",v4.codigo,".",v3.codigo,".",v2.codigo) as pespecificadetalle'),
            'v2.nombre as especificadetalle',
            DB::raw('concat("2.",v6.codigo) as pgenerica'),
            'v6.nombre as generica',
        )
            ->join('pres_especificadetalle_gastos as v2', 'v2.id', '=', 'pres_partidasrestringidas.especificadetalle_id')
            ->join('pres_especifica_gastos as v3', 'v3.id', '=', 'v2.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v4', 'v4.id', '=', 'v3.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v5', 'v5.id', '=', 'v4.subgenerica_id')
            ->join('pres_generica_gastos as v6', 'v6.id', '=', 'v5.generica_id');

        if ($rq->get('anio') > 0) $data = $data->where('pres_partidasrestringidas.anio', $rq->get('anio'));
        if ($rq->get('generica') > 0) $data = $data->where('v6.id', $rq->get('generica'));
        if ($rq->get('sg') > 0) $data = $data->where('v5.id', $rq->get('sg'));
        $data = $data->orderBy('id', 'asc')->get();

        return  datatables()::of($data)
            /* ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            }) */
            ->addColumn('espdet', function ($data) {
                return $data->pespecificadetalle . ' ' . $data->especificadetalle;
            })
            ->addColumn('gen', function ($data) {
                return $data->pgenerica . ' ' . $data->generica;
            })
            ->addColumn('action', function ($data) {

                //$acciones = '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                /* if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                } */
                $acciones = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })
            ->rawColumns(['action', 'espdet', 'gen'])
            ->make(true);
    }

    public function listar(Request $rq)
    {
        if ($rq->get('generica') > 0) {
            $data = EspecificaDetalleGasto::select(
                'pres_especificadetalle_gastos.*',
                DB::raw('concat("2.",v6.codigo,".",v5.codigo,".",v4.codigo,".",v3.codigo,".",pres_especificadetalle_gastos.codigo) as partida'),
            )
                ->join('pres_especifica_gastos as v3', 'v3.id', '=', 'pres_especificadetalle_gastos.especifica_id')
                ->join('pres_subgenericadetalle_gastos as v4', 'v4.id', '=', 'v3.subgenericadetalle_id')
                ->join('pres_subgenerica_gastos as v5', 'v5.id', '=', 'v4.subgenerica_id')
                ->join('pres_generica_gastos as v6', 'v6.id', '=', 'v5.generica_id');
            if ($rq->get('generica') > 0) $data = $data->where('v6.id', $rq->get('generica'));
            if ($rq->get('sg') > 0) $data = $data->where('v5.id', $rq->get('sg'));
            if ($rq->get('subgenericadetalle') > 0) $data = $data->where('v4.id', $rq->get('subgenericadetalle'));
            $data = $data->orderBy('partida', 'asc')->get();
        } else $data = [];

        return  datatables()::of($data)
            ->addColumn('action', function ($data) {
                $quitar = PartidasRestringidas::where('anio', date('Y'))->where('especificadetalle_id', $data->id)->first();

                if ($quitar) { // onclick="asignar(' . $data->id . ',`' . $data->partida . '`)"
                    $acciones = '<div class="pretty p-switch">
                                    <input type="checkbox" id="checkbox' . $data->id . '" name="checkbox[]" value="' . $data->id . '" checked title="Liberar">
                                    <div class="state p-success"><label></label></div>
                                 </div>';
                    //$acciones = '<a href="javascript:void(0);" class="btn btn-success btn-xs" onclick="asignar(' . $data->id . ',`' . $data->partida . '`)"  title="ASIGNAR"> <i class="fa fa-plus"></i> Asignar</a>';
                } else { // onclick="quitar(' . $data->id . ')"
                    $acciones = '<div class="pretty p-switch">
                                    <input type="checkbox" id="checkbox' . $data->id . '" name="checkbox[]" value="' . $data->id . '" title="Restringir">
                                    <div class="state p-success"><label></label></div>
                                 </div>';
                    //$acciones = '<a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="quitar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> Quitar</a>';
                }
                return '<div style="text-align:center">' . $acciones . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function guardarpartidasrestringidas(Request $rq)
    {
        $data = EspecificaDetalleGasto::select(
            'pres_especificadetalle_gastos.id',
        )
            ->join('pres_especifica_gastos as v3', 'v3.id', '=', 'pres_especificadetalle_gastos.especifica_id')
            ->join('pres_subgenericadetalle_gastos as v4', 'v4.id', '=', 'v3.subgenericadetalle_id')
            ->join('pres_subgenerica_gastos as v5', 'v5.id', '=', 'v4.subgenerica_id')
            ->join('pres_generica_gastos as v6', 'v6.id', '=', 'v5.generica_id');
        if ($rq->get('fgenerica') > 0) $data = $data->where('v6.id', $rq->get('fgenerica'));
        if ($rq->get('fsg') > 0) $data = $data->where('v5.id', $rq->get('fsg'));
        if ($rq->get('fsubgenericadetalle') > 0) $data = $data->where('v4.id', $rq->get('fsubgenericadetalle'));
        $data = $data->get();
        foreach ($data as $bd) {
            $pr = PartidasRestringidas::where('anio', $rq->get('fanio'))->where('especificadetalle_id', $bd->id)->first();
            if ($pr) { //si esta
                $eliminado = TRUE;
                foreach ($rq->get('checkbox') as $select) {
                    if ($bd->id == $select)
                        $eliminado = FALSE;
                }
                if ($eliminado) {
                    $pr->delete();
                }
            } else {
                $esta = FALSE;
                foreach ($rq->get('checkbox') as $select) {
                    if ($bd->id == $select)
                        $esta = TRUE;
                }
                if ($esta) {
                    $ed = EspecificaDetalleGasto::find($bd->id);
                    PartidasRestringidas::Create([
                        'anio' => $rq->get('fanio'),
                        'especificadetalle_id' => $bd->id,
                        'codigo' => $ed->codigo,
                        'estado' => 0,
                    ]);
                }
            }
        }
        /* foreach ($rq->get('espdet') as $value) {
            $pr = PartidasRestringidas::where('anio', date('Y'))->where('especificadetalle_id', $value)->first();
            if (!$pr) {
                $ed = EspecificaDetalleGasto::find($value);
                PartidasRestringidas::Create([
                    'anio' => date('Y'),
                    'especificadetalle_id' => $value,
                    'codigo' => $ed->codigo,
                    'estado' => 0,
                ]);
            }
        } */
        /* foreach ($rq->get('checkbox') as $key => $value) {
        } */

        return response()->json(['status' => true]);
    }

    public function asignarpartidasrestringidas(Request $rq)
    {
        $pr = PartidasRestringidas::Create([
            'anio' => date('Y'),
            'especificadetalle_id' => $rq->get('especificadetalle_id'),
            'codigo' => $rq->get('partida'),
            'estado' => 0,
        ]);
        return response()->json(['status' => true, 'partidas' => $pr]);
    }

    public function borrarpartidasrestringidas(Request $rq)
    {
        $pr = PartidasRestringidas::find($rq->get('id'));
        $pr->delete();
        return response()->json(['status' => true, 'partidas' => $pr]);
    }

    public function quitarpartidasrestringidas(Request $rq)
    {
        $pr = PartidasRestringidas::where('especificadetalle_id', $rq->get('id'))->first();
        $pr->delete();
        return response()->json(['status' => true, 'partidas' => $pr]);
    }
}
