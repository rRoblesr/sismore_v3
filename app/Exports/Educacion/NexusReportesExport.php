<?php

namespace App\Exports\Educacion;

use App\Repositories\Educacion\CuboPacto2Repositorio;
use App\Repositories\Educacion\NexusRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NexusReportesExport implements FromView, ShouldAutoSize, WithEvents
{
    public $div;
    public $anio;
    public $ugel;
    public $modalidad;
    public $nivel;
    public $rows = 0;


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
                $this->rows = $base->count();
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
                return view('educacion.Nexus.ReportesTablasExcel', compact('div', 'base', 'foot'));
            case 'tabla2':
                $base = NexusRepositorio::reportesreporte_tabla02($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                $this->rows = $base->count();
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
                return view('educacion.Nexus.ReportesTablasExcel', compact('div', 'base', 'foot'));
            case 'tabla3':
                $base = NexusRepositorio::reportesreporte_tabla03($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                $this->rows = $base->count();
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
                return view('educacion.Nexus.ReportesTablasExcel', compact('div', 'base', 'foot'));

            case 'tabla4':
                $base = NexusRepositorio::reportesreporte_tabla04($this->anio, $this->ugel, $this->modalidad, $this->nivel);
                $this->rows = $base->count();
                return view('educacion.Nexus.ReportesTablasExcel', compact('div', 'base'));
            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if (in_array($this->div, ['tabla1', 'tabla2', 'tabla3'])) {
                    $firstDataRow = 3; // 2 filas de cabecera
                    $lastRow = max($firstDataRow, $firstDataRow + $this->rows); // incluye fila TOTAL
                    $event->sheet->getDelegate()->getStyle("B{$firstDataRow}:O{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }

                if ($this->div === 'tabla4') {
                    $firstDataRow = 2; // 1 fila de cabecera
                    $lastRow = max($firstDataRow, $firstDataRow + $this->rows); // incluye fila TOTAL
                    $event->sheet->getDelegate()->getStyle("G{$firstDataRow}:K{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }
            },
        ];
    }
}
