<?php

namespace App\Exports;

use App\Http\Controllers\Parametro\PoblacionController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class parPoblacionPeruUcayaliExport implements FromView, ShouldAutoSize
{ //$div, $anio, $provincia, $distrito, $sexo)
    public $div;
    public $anio;
    public $provincia;
    public $distrito;
    public $sexo;

    public function __construct($div,  $anio, $provincia, $distrito, $sexo)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->sexo = $sexo;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new PoblacionController())->poblacionprincipalucayaliExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->sexo);
            return view('parametro.Poblacion.PeruUcayaliTabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new PoblacionController())->poblacionprincipalperuExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->sexo);
            return view('parametro.Poblacion.PeruUcayaliTabla1Export', $mgs);
        } else {
            $mgs = (new PoblacionController())->poblacionprincipalperuExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->sexo);
            return view('parametro.Poblacion.PeruUcayaliTabla1Export', $mgs);
        }
    }
}
