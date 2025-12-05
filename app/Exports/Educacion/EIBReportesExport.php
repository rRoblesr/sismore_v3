<?php

namespace App\Exports\Educacion;

use App\Repositories\Educacion\CuboPacto2Repositorio;
use App\Repositories\Educacion\CuboPadronEIBRepositorio;
use App\Repositories\Educacion\NexusRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EIBReportesExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $ugel;
    public $provincia;
    public $distrito;


    public function __construct($div, $anio, $ugel, $provincia, $distrito)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->ugel = $ugel;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
    }

    public function view(): View
    {
        $div = $this->div;
        switch ($this->div) {
            case 'tabla1':
            case 'tabla2':
            case 'tabla3':
            case 'tabla4':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla4_excel($this->anio, 0, $this->ugel, $this->provincia, $this->distrito);
                return view('educacion.EIB.ReportesTablasExcel', compact('div', 'base'));
                
            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }
}
