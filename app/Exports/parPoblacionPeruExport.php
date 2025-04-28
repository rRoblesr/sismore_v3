<?php

namespace App\Exports;

use App\Http\Controllers\Parametro\PoblacionController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class parPoblacionPeruExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $departamento;
    public $etapavida;

    public function __construct($div,  $anio, $departamento, $etapavida)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->departamento = $departamento;
        $this->etapavida = $etapavida;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new PoblacionController())->poblacionprincipalperuExport($this->div, $this->anio, $this->departamento, $this->etapavida);
            return view('parametro.Poblacion.PeruTabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new PoblacionController())->poblacionprincipalperuExport($this->div, $this->anio, $this->departamento, $this->etapavida);
            return view('parametro.Poblacion.PeruTabla1Export', $mgs);
        } else {
            $mgs = (new PoblacionController())->poblacionprincipalperuExport($this->div, $this->anio, $this->departamento, $this->etapavida);
            return view('parametro.Poblacion.PeruTabla1Export', $mgs);
        }
    }
}
