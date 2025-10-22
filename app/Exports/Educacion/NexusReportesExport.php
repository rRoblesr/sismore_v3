<?php

namespace App\Exports\Educacion;

use App\Repositories\Educacion\CuboPacto2Repositorio;
use App\Repositories\Educacion\NexusRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NexusReportesExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $ugel;
    public $modalidad;
    public $nivel;


    public function __construct($div, $anio, $ugel, $modalidad, $nivel)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->ugel = $ugel;
        $this->modalidad = $modalidad;
        $this->nivel = $nivel;
    }

    public function view(): View
    {
        $div = $this->div;
        switch ($this->div) {
            case 'tabla1':
                $base = NexusRepositorio::reportesreporte_tabla01($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ugel = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                }
                return view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'));
            case 'tabla2':
                $base = NexusRepositorio::reportesreporte_tabla02($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ley = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                }
                return view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'));
            case 'tabla3':
                $base = NexusRepositorio::reportesreporte_tabla03($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->distrito = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                }
                return view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'));

            case 'tabla4':
                $base = NexusRepositorio::reportesreporte_tabla04($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                return view('educacion.Nexus.ReportesTablas', compact('div', 'base'));
            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }
}
