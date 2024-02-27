<?php

namespace App\Http\Controllers\Presupuesto;

use App\Exports\BaseGastosExport;
use App\Exports\GobiernosRegionalesExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\TipoGobierno;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseProyectosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GobiernosRegionalesController extends Controller
{
    public $mesc = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $mesa = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* nivel gobiernos */
    public function principal()
    {
        $anos = GobiernosRegionalesRepositorio::anios();
        $anio = $anos->max('anio');
        $impG = Importacion::where('fuenteimportacion_id', '25')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $imp = ImportacionRepositorio::ImportacionMaxFuente_porFuenteAnio('25', $anio);
        $actualizado = $imp->fuente . ', Actualizado al ' . date('d', strtotime($imp->fecha)) . ' de ' . $this->mesc[date('m', strtotime($imp->fecha)) - 1] . ' del ' . date('Y', strtotime($imp->fecha));
        $mensaje = "";
        return view('Presupuesto.GobiernosRegionales.Principal', compact('mensaje', 'anos', 'anio', 'impG', 'actualizado'));
    }

    public function cargarmes(Request $rq)
    {
        $info = GobiernosRegionalesRepositorio::meses($rq->ano);
        return response()->json(compact('info'));
    }

    public function principaltabla01(Request $rq)
    {
        $body = GobiernosRegionalesRepositorio::tipos_gobiernosregionales($rq->ano, $rq->mes, $rq->tipo);
        $foot = ['pia' => 0, 'pim' => 0, 'certificacion' => 0, 'eje1' => 0, 'devengado' => 0, 'eje2' => 0, 'saldo1' => 0, 'saldo2' => 0];
        foreach ($body as $key => $value) {
            $value->eje1 = $value->pim > 0 ? round(100 * $value->certificacion / $value->pim, 1) : 0;
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['certificacion'] += $value->certificacion;
            $foot['eje1'] += $value->eje1;
            $foot['devengado'] += $value->devengado;
            $foot['eje2'] += $value->eje;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje1'] = $foot['pim'] > 0 ? round(100 * $foot['certificacion'] / $foot['pim'], 1) : 0;
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
