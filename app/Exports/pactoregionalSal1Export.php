<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Salud\IndicadoresController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class pactoregionalSal1Export implements FromView, ShouldAutoSize
{
    public $div;
    public $fuente;
    public $indicador;
    public $anio;
    public $mes;
    public $provincia;
    public $distrito;

    public function __construct($div, $fuente, $indicador, $anio, $mes, $provincia, $distrito)
    {
        $this->div = $div;
        $this->fuente = $fuente;
        $this->indicador = $indicador;
        $this->anio = $anio;
        $this->mes = $mes;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->fuente, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->fuente, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla2Export', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->fuente, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla3Export', $mgs);
        } else {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->fuente, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla5Export', $mgs);
        }
    }
}
