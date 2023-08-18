<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\SectoresGobiernoTabla1Export;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseIngresos;
use App\Models\Presupuesto\FuenteFinanciamiento;
use App\Models\Presupuesto\TipoGobierno;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseIngresosRepositorio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BaseIngresosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* IngresoPresupuestal */
    public function ingresopresupuestal()
    {
        $impG = Importacion::where('fuenteimportacion_id', '13')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $bg_id = BaseGastos::where('importacion_id', $impG->id)->first();
        $impI = Importacion::where('fuenteimportacion_id', '15')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $bi_id = BaseIngresos::where('importacion_id', $impI->id)->first();

        $opt1 = BaseIngresosRepositorio::total_pim($bi_id->id);
        $card1['pim'] = $opt1->pim;
        $card1['eje'] = $opt1->eje;

        $opt1 = BaseIngresosRepositorio::pim_tipogobierno($bi_id->id);
        $card2['pim'] = $opt1[0]->y;
        $card2['eje'] = $opt1[0]->eje;
        $card3['pim'] = $opt1[1]->y;
        $card3['eje'] = $opt1[1]->eje;
        $card4['pim'] = $opt1[2]->y;
        $card4['eje'] = $opt1[2]->eje;

        return view('Presupuesto.BaseIngresos.IngresoPresupuesto', compact('card1', 'card2', 'card3', 'card4', 'impG', 'impI'));
    }

    public function ingresopresupuestalgrafica1(Request $rq)
    {
        $bi = BaseIngresos::where('importacion_id', $rq->get('importacion_id'))->first();
        $info = BaseIngresosRepositorio::pim_tipogobierno($bi->id);
        return response()->json(compact('info'));
    }

    public function ingresopresupuestalgrafica2(Request $rq)
    {
        $bi = BaseIngresos::where('importacion_id', $rq->get('importacion_id'))->first();
        $info = BaseIngresosRepositorio::pim_pia_devengado_tipogobierno($bi->id);
        $data['categoria'] = ['GOBIERNO NACIONAL', 'GOBIERNOS REGIONALES', 'GOBIERNOS LOCALES'];
        $data['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($info as $key => $value) {
            //$dx1[] = $value->y1; //pia
            $dx2[] = $value->y2; //pim
            $dx3[] = round($value->y3, 2); //devengado
        }
        //$data['series'][] = ['name' => 'PIA', 'color' => '#7C7D7D', 'data' => $dx1];
        $data['series'][] = ['name' => 'PIM', 'color' => '#317eeb', 'data' => $dx2];
        $data['series'][] = ['name' => 'RECAUDADO', 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('data'));
    }

    public function ingresopresupuestalgrafica3()
    {
        $info = BaseIngresosRepositorio::pim_anios_tipogobierno();
        $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($info as $key => $ba) {
            $data['categoria'][] = $ba->ano;
            $dx1[] = $ba->pim1;
            $dx2[] = $ba->pim2;
            $dx3[] = $ba->pim3;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
        $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
        $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('data'));
    }

    public function ingresopresupuestalgrafica4()
    {
        $info = BaseIngresosRepositorio::recaudado_anios_tipogobierno();
        $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        $dx2 = [];
        $dx3 = [];
        foreach ($info as $key => $ba) {
            $data['categoria'][] = $ba->ano;
            $dx1[] = $ba->pim1;
            $dx2[] = $ba->pim2;
            $dx3[] = $ba->pim3;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#7e57c2',  'data' => $dx1];
        $data['series'][] = ['name' => 'GOBIERNOS REGIONALES', 'color' => '#317eeb',  'data' => $dx2];
        $data['series'][] = ['name' => 'GOBIERNOS LOCALES', 'color' => '#ef5350', 'data' => $dx3];
        return response()->json(compact('data'));
    }

    /* fin IngresoPresupuestal */


    /* Sectores de gobierno */
    public function sectoresgobiernos()
    {
        $anos = BaseIngresosRepositorio::anios();
        $gobiernos = TipoGobierno::whereIn('id', [1, 2, 3])->get();
        $financiamientos = FuenteFinanciamiento::all();
        return view('Presupuesto.BaseIngresos.SectoresGobierno', compact('anos', 'gobiernos', 'financiamientos'));
    }

    public function sectoresgobiernostabla01(Request $rq)
    {
        $info = BaseIngresosRepositorio::listar_sectorpliego_anio_tipogobierno_fuentefinanciamiento($rq->get('anio'), $rq->get('gobierno'), $rq->get('financiamiento'));
        return view("Presupuesto.BaseIngresos.SectoresGobiernoTabla1", ['head' => $info['head'], 'body' => $info['body'], 'foot' => $info['foot']]);
    }

    public function sectoresgobiernosdownload($ano, $gobierno, $financiamiento)
    {
        if ($ano > 0) {
            $name = 'xxx' . date('Y-m-d') . '.xlsx';
            return Excel::download(new SectoresGobiernoTabla1Export($ano, $gobierno, $financiamiento), $name);
        }
    }
}
