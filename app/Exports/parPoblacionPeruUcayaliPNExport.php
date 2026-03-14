<?php

namespace App\Exports;

use App\Http\Controllers\Parametro\PoblacionController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class parPoblacionPeruUcayaliPNExport implements FromView, ShouldAutoSize
{ //$div, $anio, $provincia, $distrito, $sexo)
    public $div;
    public $anio;
    public $mes;
    public $provincia;
    public $distrito;

    public function __construct($div,  $anio, $mes, $provincia, $distrito)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->mes = $mes;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new PoblacionController())->poblacionprincipalucayaliPNExport($this->div, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('parametro.Poblacion.PeruUcayaliPNTabla1', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new PoblacionController())->poblacionprincipalucayaliPNExport($this->div, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('parametro.Poblacion.PeruUcayaliPNTabla2', $mgs);
        } else {
            $mgs = (new PoblacionController())->poblacionprincipalucayaliPNExport($this->div, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('parametro.Poblacion.PeruUcayaliPNTabla1', $mgs);
        }
    }
}
