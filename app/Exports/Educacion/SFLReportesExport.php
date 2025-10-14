<?php

namespace App\Exports\Educacion;

use App\Repositories\Educacion\CuboPacto2Repositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SFLReportesExport implements FromView, ShouldAutoSize
{
    public $div;
    public $ugel;
    public $modalidad;
    public $nivel;


    public function __construct($div, $ugel, $modalidad, $nivel)
    {
        $this->div = $div;
        $this->ugel = $ugel;
        $this->modalidad = $modalidad;
        $this->nivel = $nivel;
    }

    public function view(): View
    {
        switch ($this->div) {
            case 'tabla1':
                $base = CuboPacto2Repositorio::reportesreporte_tabla1($this->ugel, $this->modalidad, $this->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->se = $base->sum('se');
                    $foot->ser = $base->sum('ser');
                    $foot->seu = $base->sum('seu');
                    $foot->le = $base->sum('le');
                    $foot->ler = $base->sum('ler');
                    $foot->leu = $base->sum('leu');
                    $foot->le1 = $base->sum('le1');
                    $foot->le2 = $base->sum('le2');
                    $foot->le3 = $base->sum('le3');
                    $foot->le4 = $base->sum('le4');
                    $foot->le1p = round(100 * $foot->le1 / $foot->le, 1);
                    $foot->le2p = round(100 * $foot->le2 / $foot->le, 1);
                    $foot->le3p = round(100 * $foot->le3 / $foot->le, 1);
                    $foot->le4p = round(100 * $foot->le4 / $foot->le, 1);
                }
                return view('educacion.SFL.ReportesTabla1', compact('base', 'foot'));
            case 'tabla2':
                $base = CuboPacto2Repositorio::reportesreporte_tabla2($this->ugel, $this->modalidad, $this->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->se = $base->sum('se');
                    $foot->ser = $base->sum('ser');
                    $foot->seu = $base->sum('seu');
                    $foot->le = $base->sum('le');
                    $foot->ler = $base->sum('ler');
                    $foot->leu = $base->sum('leu');
                    $foot->le1 = $base->sum('le1');
                    $foot->le2 = $base->sum('le2');
                    $foot->le3 = $base->sum('le3');
                    $foot->le4 = $base->sum('le4');
                    $foot->le1p = round(100 * $foot->le1 / $foot->le, 1);
                    $foot->le2p = round(100 * $foot->le2 / $foot->le, 1);
                    $foot->le3p = round(100 * $foot->le3 / $foot->le, 1);
                    $foot->le4p = round(100 * $foot->le4 / $foot->le, 1);
                }
                return view('educacion.SFL.ReportesTabla2', compact('base', 'foot'));
            case 'tabla3':
                $base = CuboPacto2Repositorio::reportesreporte_tabla3($this->ugel, $this->modalidad, $this->nivel);
                return view('educacion.SFL.ReportesTabla3', compact('base'));
            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }
}
