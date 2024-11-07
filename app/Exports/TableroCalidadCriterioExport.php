<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Salud\IndicadoresController;
use App\Models\Salud\CalidadCriterio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableroCalidadCriterioExport implements FromView, ShouldAutoSize
{
    public $div;
    public $importacion;
    public $criterio;
    public $edades;
    public $provincia;
    public $distrito;

    public function __construct($div, $importacion, $criterio, $edades, $provincia, $distrito)
    {
        $this->div = $div;
        $this->importacion = $importacion;
        $this->criterio = $criterio;
        $this->edades = $edades;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
    }

    public function view(): View
    {
        $query = CalidadCriterio::where('importacion_id', $this->importacion)->where('criterio', $this->criterio);
        if ($this->edades > 0) {
            if ($this->edades == 1) {
                $query = $query->whereIn('tipo_edad', ['D', 'M']);
            } else {
                $query = $query->where('tipo_edad', 'A')->where('edad', $this->edades - 1);
            }
        }
        if ($this->provincia > 0) $query = $query->where('provincia_id', $this->provincia);
        if ($this->distrito > 0) $query = $query->where('distrito_id', $this->distrito);

        $query = $query->get();

        if ($this->div == 'tabla1') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla2Export', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla3Export', $mgs);
        } else if ($this->div == 'tabla4') {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla4Export', $mgs);
        } else {
            $mgs = (new IndicadoresController())->PactoRegionalSalPacto1Export($this->div, $this->indicador, $this->anio, $this->mes, $this->provincia, $this->distrito);
            return view('salud.Indicadores.PactoRegionalSalPacto1tabla5Export', $mgs);
        }
    }
}
