<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\SFLController;
use App\Services\Educacion\EduCuboPacto2ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SFLExport implements FromView, ShouldAutoSize
{
    public $tipo;
    public $ugel;
    public $provincia;
    public $distrito;
    public $estado;


    public function __construct($tipo, $ugel, $provincia, $distrito, $estado)
    {
        $this->tipo = $tipo;
        $this->ugel = $ugel;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->estado = $estado;
    }

    public function view(): View
    {
        switch ($this->tipo) {
            case 'servicios':
                $mgs = (new SFLController())->ListarDTExport($this->ugel, $this->provincia, $this->distrito, $this->estado);
                return view('educacion.SFL.PrincipalTabla1excelExport', $mgs);
            case 'locales':
                $base = EduCuboPacto2ReportService::exportar_excel_locales($this->ugel, $this->provincia, $this->distrito, $this->estado);
                return view('educacion.SFL.PrincipalTabla1excel01Export', compact('base'));
            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }
}
