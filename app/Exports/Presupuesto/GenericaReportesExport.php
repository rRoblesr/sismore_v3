<?php

namespace App\Exports\Presupuesto;

use App\Models\Presupuesto\GenericaGasto;
use App\Models\Presupuesto\SubGenericaGasto;
use App\Repositories\Presupuesto\BaseSiafWebDetalleRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GenericaReportesExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $ue;
    public $cp;
    public $ff;
    public $g;
    public $sg;


    public function __construct($div, $anio, $ue, $cp, $ff, $g, $sg)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->ue = $ue;
        $this->cp = $cp;
        $this->ff = $ff;
        $this->g = $g;
        $this->sg = $sg;
    }

    public function view(): View
    {
        $div = $this->div;
        switch ($this->div) {
            case 'tabla1':
                $base = BaseSiafWebDetalleRepositorio::genericareportesreporte_tabla1_export($this->anio, $this->ue, $this->cp, $this->ff);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->nombre = 'TOTAL';
                    $foot->pia = $base->sum('pia');
                    $foot->pim = $base->sum('pim');
                    $foot->certificado = $base->sum('certificado');
                    $foot->compromiso = $base->sum('compromiso');
                    $foot->devengado = $base->sum('devengado');
                    $foot->avance = $foot->pim > 0 ? round(100 * $foot->devengado / $foot->pim, 1) : 0;
                    $foot->saldocert = $foot->pim - $foot->certificado;
                    $foot->saldodev = $foot->pim - $foot->devengado;
                }
                return view('presupuesto.BaseSiafWeb.GenericaReportesTablasExport', compact('div', 'base', 'foot'));

            case 'tabla0101':
                $base = BaseSiafWebDetalleRepositorio::genericareportesreporte_tabla0101($this->anio, $this->ue, $this->cp, $this->ff, $this->g);
                foreach ($base as $key => $value) {
                    $value->dic = $value->dic - $value->nov;
                    $value->nov = $value->nov - $value->oct;
                    $value->oct = $value->oct - $value->sep;
                    $value->sep = $value->sep - $value->ago;
                    $value->ago = $value->ago - $value->jul;
                    $value->jul = $value->jul - $value->jun;
                    $value->jun = $value->jun - $value->may;
                    $value->may = $value->may - $value->abr;
                    $value->abr = $value->abr - $value->mar;
                    $value->mar = $value->mar - $value->feb;
                    $value->feb = $value->feb - $value->ene;
                }
                $nombre = GenericaGasto::find($this->g)->nombre;
                return view('presupuesto.BaseSiafWeb.GenericaReportesTablasExport', compact('div', 'base'));

            case 'tabla2':
                $base = BaseSiafWebDetalleRepositorio::genericareportesreporte_tabla2_export($this->anio, $this->ue, $this->cp, $this->ff);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->nombre = 'TOTAL';
                    $foot->pia = $base->sum('pia');
                    $foot->pim = $base->sum('pim');
                    $foot->certificado = $base->sum('certificado');
                    $foot->compromiso = $base->sum('compromiso');
                    $foot->devengado = $base->sum('devengado');
                    $foot->avance = $foot->pim > 0 ? round(100 * $foot->devengado / $foot->pim, 1) : 0;
                    $foot->saldocert = $foot->pim - $foot->certificado;
                    $foot->saldodev = $foot->pim - $foot->devengado;
                }
                return view('presupuesto.BaseSiafWeb.GenericaReportesTablasExport', compact('div', 'base', 'foot'));

            case 'tabla0201':
                 $base = BaseSiafWebDetalleRepositorio::genericareportesreporte_tabla0201($this->anio, $this->ue, $this->cp, $this->ff, $this->sg);
                foreach ($base as $key => $value) {
                    $value->dic = $value->dic - $value->nov;
                    $value->nov = $value->nov - $value->oct;
                    $value->oct = $value->oct - $value->sep;
                    $value->sep = $value->sep - $value->ago;
                    $value->ago = $value->ago - $value->jul;
                    $value->jul = $value->jul - $value->jun;
                    $value->jun = $value->jun - $value->may;
                    $value->may = $value->may - $value->abr;
                    $value->abr = $value->abr - $value->mar;
                    $value->mar = $value->mar - $value->feb;
                    $value->feb = $value->feb - $value->ene;
                }
                $nombre = SubGenericaGasto::find($this->sg)->nombre;
                return view('presupuesto.BaseSiafWeb.GenericaReportesTablasExport', compact('div', 'base'));

            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }
}
