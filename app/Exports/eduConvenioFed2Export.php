<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Salud\IndicadoresController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class eduConvenioFed2Export implements FromView, ShouldAutoSize
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
        if ($this->div == 'tabla2') {
            $mgs = (new IndicadoresController())->ConvenioFEDEduMC0502Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('educacion.Indicadores.ConvenioFEDMC0502tabla2Export', $mgs);
        } else if ($this->div == 'tabla1') {
            $mgs = (new IndicadoresController())->ConvenioFEDEduMC0502Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('educacion.Indicadores.ConvenioFEDMC0502tabla2Export', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = (new IndicadoresController())->ConvenioFEDEduMC0502Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('educacion.Indicadores.ConvenioFEDMC0502tabla2Export', $mgs);
        } else if ($this->div == 'tabla4') {
            $mgs = (new IndicadoresController())->ConvenioFEDEduMC0502Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('educacion.Indicadores.ConvenioFEDMC0502tabla2Export', $mgs);
        } else {
            $mgs = (new IndicadoresController())->ConvenioFEDEduMC0502Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('educacion.Indicadores.ConvenioFEDMC0502tabla2Export', $mgs);
        }
    }
}
