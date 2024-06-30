<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Educacion\LogrosAprendizajeController;
use App\Http\Controllers\Salud\IndicadoresController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EvaluacionMensualExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $nivel;
    public $grado;
    public $curso;
    public $provincia;

    public function __construct($div, $anio, $nivel, $grado, $curso, $provincia)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->nivel = $nivel;
        $this->grado = $grado;
        $this->curso = $curso;
        $this->provincia = $provincia;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new LogrosAprendizajeController())->EvaluacionMuestralReportesExport($this->div, $this->anio, $this->nivel, $this->grado, $this->curso, $this->provincia);
            return view('educacion.EvaluacionMuestral.principalTable1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = []; // (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla2Export', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = []; // (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla3Export', $mgs);
        } else if ($this->div == 'tabla4') {
            $mgs = []; // (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla4Export', $mgs);
        } else {
            $mgs = []; // (new IndicadoresController())->PactoRegionalSalPacto2Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto2tabla5Export', $mgs);
        }
    }
}
