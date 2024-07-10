<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Salud\IndicadoresController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class pactoregionalSal2Export implements FromView, ShouldAutoSize
{
    public $div;
    public $indicador;
    public $anio;
    public $mes;
    public $provincia;
    public $distrito;

    public function __construct($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $this->div = $div;
        $this->indicador = $indicador;
        $this->anio = $anio;
        $this->mes = $mes;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla2Export', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla3Export', $mgs);
        } else if ($this->div == 'tabla4') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla4Export', $mgs);
        } else {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla5Export', $mgs);
        }
    }
}
