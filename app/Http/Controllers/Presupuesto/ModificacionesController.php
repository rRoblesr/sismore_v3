<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\BaseGastosExport;
use App\Exports\ModificacionesGastoExport;
use App\Exports\ModificacionesIngresoExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseModificacionDetalle;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\ProductoProyecto;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\TipoModificacion;
use App\Models\Presupuesto\TipoTransaccion;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseProyectosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use App\Repositories\Presupuesto\ModificacionesRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ModificacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* nivel gobiernos */
    public function principal_gasto()
    {
        $opt1 = ModificacionesRepositorio::anios();
        $opt3 = ProductoProyecto::all();
        $opt4 = TipoModificacion::orderBy('codigo', 'asc')->get();
        $opt5 = BaseModificacionDetalle::select(DB::raw('distinct dispositivo_legal'))
            ->where('dispositivo_legal', '!=', '')
            ->orderBy('dispositivo_legal', 'asc')->get();
        /* $opt6 = TipoTransaccion::select('v2.id', DB::raw('concat(pres_tipotransaccion.codigo,".",v2.codigo," ",v2.nombre) as nombre'))
            ->join('pres_generica_gastos as v2', 'v2.tipotransaccion_id', '=', 'pres_tipotransaccion.id')
            ->get(); */
        $opt6 = UnidadEjecutora::select('pres_unidadejecutora.*')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();
        $impG = Importacion::where('fuenteimportacion_id', '26')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $mensaje = "";
        return view('Presupuesto.Modificaciones.Principal', compact('mensaje', 'opt1', 'opt3', 'opt4', 'opt5', 'opt6','impG'));
    }

    public function cargarmes(Request $rq)
    {
        $info = ModificacionesRepositorio::meses($rq->ano);
        return response()->json(compact('info'));
        //return $mes;
    }

    public function principalgastotabla01(Request $rq)
    {
        $body = ModificacionesRepositorio::listar_modificaciones($rq->get('ano'), $rq->get('mes'), $rq->get('productoproyecto'), $rq->get('tipomodificacion'), $rq->get('dispositivototal'), $rq->get('generica'));
        $foot = ['anulacion' => 0, 'credito' => 0];
        foreach ($body as $key => $value) {
            $foot['anulacion'] += $value->anulacion;
            $foot['credito'] += $value->credito;
        }
        return view("Presupuesto.Modificaciones.PrincipalTabla1", compact('body', 'foot'));
    }
    public function principalgastotabla01_DT(Request $rq)
    {
        $body = ModificacionesRepositorio::listar_modificaciones($rq->get('ano'), $rq->get('mes'), $rq->get('productoproyecto'), $rq->get('tipomodificacion'), $rq->get('dispositivototal'), $rq->get('generica'));
        return DataTables::of($body)
            ->editColumn('especifica_detalle', '{{$clasificador}} {{$especifica_detalle}}')
            ->editColumn('catpres', '<div class="text-center" title="{{$ncatpres}}"><a href="javascript:void(0)">{{$catpres}}</a></div>')
            ->editColumn('prod_proy', '<div class="text-center" title="{{$nprod_proy}}"><a href="javascript:void(0)">{{$prod_proy}}</a></div>')
            ->editColumn('act_acc_obra', '<div class="text-center" title="{{$nact_acc_obra}}"><a href="javascript:void(0)">{{$act_acc_obra}}</a></div>')
            ->editColumn('rb', '<div class="text-center"  title="{{$nrb}}"><a href="javascript:void(0)">{{$rb}}</a></div>')
            ->editColumn('anulacion', '<div class="text-right">{{number_format($anulacion,0)}}</div>')
            ->editColumn('credito', '<div class="text-right">{{number_format($credito,0)}}</div>')
            ->rawColumns(['especifica_detalle', 'catpres', 'prod_proy', 'act_acc_obra', 'rb', 'anulacion', 'credito'])
            ->make(true);
    }

    public function principalgastotabla01_foot(Request $rq)
    {
        $foot = ModificacionesRepositorio::listar_modificaciones_foot($rq->get('ano'), $rq->get('mes'), $rq->get('productoproyecto'), $rq->get('tipomodificacion'), $rq->get('dispositivototal'), $rq->get('generica'));
        return response()->json(compact('foot'));
    }

    public function downloadgasto($ano, $mes, $articulo, $tipo, $dispositivo, $ue)
    {
        if ($ano) {
            $name = 'Modificaciones_Gasto_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new ModificacionesGastoExport($ano, $mes, $articulo, $tipo, $dispositivo, $ue), $name);
        }
    }

    public function principal_ingreso()
    {
        $opt1 = ModificacionesRepositorio::anios();
        $opt4 = TipoModificacion::orderBy('codigo', 'asc')->get();
        $opt6 = UnidadEjecutora::select('pres_unidadejecutora.*')
            ->join('pres_pliego as v2', 'v2.id', '=', 'pres_unidadejecutora.pliego_id')
            ->join('pres_sector as v3', 'v3.id', '=', 'v2.sector_id')
            ->join('pres_tipo_gobierno as v4', 'v4.id', '=', 'v3.tipogobierno_id')
            ->where('v4.id', 3)
            ->get();
        $mensaje = "";
        $impG = Importacion::where('fuenteimportacion_id', '26')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        return view('Presupuesto.Modificaciones.PrincipalIngresos', compact('mensaje', 'opt1', 'opt4', 'opt6','impG'));
    }

    public function principalingresotabla01(Request $rq)
    {
        $body = ModificacionesRepositorio::listar_modificaciones_ingresos($rq->get('ano'), $rq->get('mes'), $rq->get('tipomodificacion'), $rq->get('ue'));
        $foot = ['anulacion' => 0, 'credito' => 0];
        foreach ($body as $key => $value) {
            $foot['anulacion'] += $value->anulacion;
            $foot['credito'] += $value->credito;
        }
        return view("Presupuesto.Modificaciones.PrincipalIngresosTabla1", compact('body', 'foot'));
    }

    public function downloadingreso($ano, $mes,  $tipo, $ue)
    {
        if ($ano) {
            $name = 'Modificaciones_Gasto_' . date('Y-m-d') . '.xlsx';
            return Excel::download(new ModificacionesIngresoExport($ano, $mes, $tipo, $ue), $name);
        }
    }
}
