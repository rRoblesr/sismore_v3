<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\BaseGastosExport;
use App\Exports\GobiernosRegionalesExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\TipoGobierno;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseProyectosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GobiernosRegionalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* nivel gobiernos */
    public function principal()
    {
        $anos = GobiernosRegionalesRepositorio::anios();
        $impG = Importacion::where('fuenteimportacion_id', '25')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $mensaje = "";
        return view('Presupuesto.GobiernosRegionales.Principal', compact('mensaje', 'anos','impG'));
    }

    public function cargarmes(Request $rq)
    {
        $info = GobiernosRegionalesRepositorio::meses($rq->ano);
        return response()->json(compact('info'));
    }

    public function principaltabla01(Request $rq)
    {
        $body = GobiernosRegionalesRepositorio::tipos_gobiernosregionales($rq->get('ano'), $rq->get('mes'), $rq->get('tipo'));
        $foot = ['pia' => 0, 'pim' => 0, 'certificacion' => 0, 'compromiso' => 0, 'devengado' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['certificacion'] += $value->certificacion;
            $foot['compromiso'] += $value->compromiso_anual;
            $foot['devengado'] += $value->devengado;
            $foot['eje'] += $value->eje;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? round(100 * $foot['devengado'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.GobiernosRegionales.PrincipalTabla1", compact('body', 'foot'));
    }

    public function download($ano, $mes, $tipo)
    {
        if ($ano) {
            $name = 'Gobiernos_Regionales_' . date('Y-m-d') . '.xlsx';
            //return Excel::download(new GobiernosRegionalesExport(2022, 12, 1), $name);
            return Excel::download(new GobiernosRegionalesExport($ano, $mes, $tipo), $name);
        }
    }

}
