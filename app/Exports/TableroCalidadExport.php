<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Salud\IndicadoresController;
use App\Http\Controllers\Salud\PadronNominalController;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CalidadCriterio;
use App\Models\Salud\Establecimiento;
use App\Repositories\Salud\EstablecimientoRepositorio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableroCalidadExport implements FromView, ShouldAutoSize
{
    public $div;
    public $importacion;
    public $anio;
    public $mes;
    public $provincia;
    public $distrito;
    public $ubigeo;

    public function __construct($div, $importacion, $anio, $mes, $provincia, $distrito, $ubigeo)
    {
        $this->div = $div;
        $this->importacion = $importacion;
        $this->anio = $anio;
        $this->mes = $mes;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->ubigeo = $ubigeo;
    }

    public function view(): View
    {
        ini_set('memory_limit', '1024M');
        if ($this->div == 'tabla2') {
            $mgs = (new PadronNominalController())->tablerocalidadreporteexport($this->div, $this->importacion,  $this->anio, $this->mes, $this->provincia, $this->distrito, $this->ubigeo);
            return view('salud.PadronNominal.TableroCalidadTabla2excel', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = (new PadronNominalController())->tablerocalidadreporteexport($this->div, $this->importacion,  $this->anio, $this->mes, $this->provincia, $this->distrito, $this->ubigeo);
            return view('salud.PadronNominal.TableroCalidadTabla3excel', $mgs);
        } else {
            $mgs = (new PadronNominalController())->tablerocalidadreporteexport($this->div, $this->importacion,  $this->anio, $this->mes, $this->provincia, $this->distrito, $this->ubigeo);
            return view('salud.PadronNominal.TableroCalidadTabla3_1excel', $mgs);
        }
    }
}
