<?php

namespace App\Exports;

use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SiafWebRPT5Export implements FromView, ShouldAutoSize
{
    public $ano;
    public $articulo;
    public $ue;

    public function __construct($ano, $articulo, $ue)
    {
        $this->ano = $ano;
        $this->articulo = $articulo;
        $this->ue = $ue;
    }

    public function view(): View
    {
        $data = BaseSiafWebRepositorio::listar_fuentefinanciamiento_anio_acticulo_ue_categoria($this->ano, $this->articulo, $this->ue);
        $body = $data['body'];
        $head = $data['head'];
        $foot = clone $data['body'][0];
        $foot->pia = 0;
        $foot->pim = 0;
        $foot->cert = 0;
        $foot->dev = 0;
        $foot->saldo1 = 0;
        $foot->saldo2 = 0;
        $foot->eje = 0;
        foreach ($body as $key => $value) {
            $foot->pia += $value->pia;
            $foot->pim += $value->pim;
            $foot->cert += $value->cert;
            $foot->dev += $value->dev;
            $foot->saldo1  += $value->saldo1;
            $foot->saldo2  += $value->saldo2;
        }
        $foot->eje = $foot->pim > 0 ? number_format(100 * $foot->dev / $foot->pim, 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte5Tabla1Export", compact('body', 'head', 'foot'));
    }
}
