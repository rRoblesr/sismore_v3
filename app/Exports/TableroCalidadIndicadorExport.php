<?php

namespace App\Exports;

use App\Http\Controllers\Salud\PadronNominalController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableroCalidadIndicadorExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $mes;
    public $edades;
    public $indicador;
    public $ubigeo;

    public function __construct($div, $anio, $mes, $edades, $indicador, $ubigeo)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->mes = $mes;
        $this->edades = $edades;
        $this->indicador = $indicador;
        $this->ubigeo = $ubigeo;
    }

    public function view(): View
    {
        ini_set('memory_limit', '1024M');
        if ($this->div == 'tabla2') {
            $mgs = (new PadronNominalController())->tablerocalidadindicadorreporteExport($this->div,  $this->anio, $this->mes, $this->edades, $this->indicador, $this->ubigeo);
            return view('salud.PadronNominal.TableroCalidadIndicadorTabla2', $mgs);
        } else if ($this->div == 'tabla0201') {
            $mgs = (new PadronNominalController())->tablerocalidadindicadorreporteExport($this->div,  $this->anio, $this->mes, $this->edades, $this->indicador, $this->ubigeo);
            return view('salud.PadronNominal.TableroCalidadIndicadorTabla0201', $mgs);
        } else {
            $mgs = (new PadronNominalController())->tablerocalidadindicadorreporteExport($this->div,  $this->anio, $this->mes, $this->edades, $this->indicador, $this->ubigeo);
            return view('salud.PadronNominal.TableroCalidadTabla3_1excel', $mgs);
        }
    }
}
