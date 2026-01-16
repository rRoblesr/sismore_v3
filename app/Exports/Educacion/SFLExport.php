<?php

namespace App\Exports\Educacion;

use App\Repositories\Educacion\SFLRepositorio;
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
                $base = SFLRepositorio::servicios_activos($this->ugel, $this->provincia, $this->distrito, $this->estado);
                return view('educacion.SFL.PrincipalExcelExport', compact('base') + ['tipo' => $this->tipo]);
            case 'locales':
                $base = SFLRepositorio::locales_activos($this->ugel, $this->provincia, $this->distrito, $this->estado);
                return view('educacion.SFL.PrincipalExcelExport', compact('base') + ['tipo' => $this->tipo]);
            default:
                return view('exports.vacio', ['mensaje' => 'Tipo de reporte no válido']);
        }
    }
}
