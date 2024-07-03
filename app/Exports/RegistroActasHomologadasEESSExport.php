<?php

namespace App\Exports;

use App\Repositories\Salud\EstablecimientoRepositorio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistroActasHomologadasEESSExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $municipio;
    public $red;
    public $microred;
    public $fechai;
    public $fechaf;
    public $registrador;

    public function __construct($div, $anio, $municipio, $red, $microred, $fechai, $fechaf, $registrador)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->municipio = $municipio;
        $this->red = $red;
        $this->microred = $microred;
        $this->fechai = $fechai;
        $this->fechaf = $fechaf;
        $this->registrador = $registrador;
    }

    public function view(): View
    {
        if ($this->div == 'otros') {
            $base = EstablecimientoRepositorio::otros($this->anio, $this->municipio, $this->red, $this->microred, $this->fechai, $this->fechaf, $this->registrador);
            return view('salud.ImporPadronActas.RegistroTablaOexcelExport', compact('base'));
        } else if ($this->div == 'municipalidad') {
            $base = EstablecimientoRepositorio::registroList($this->municipio, $this->red, $this->microred, $this->fechai, $this->fechaf, $this->registrador);
            return view('salud.ImporPadronActas.RegistroTabla1excelExport', compact('base'));
        } else {
            $base = EstablecimientoRepositorio::registroList($this->municipio, $this->red, $this->microred, $this->fechai, $this->fechaf, $this->registrador);
            return view('salud.ImporPadronActas.RegistroTabla1excelExport', compact('base'));
        }
    }
}
