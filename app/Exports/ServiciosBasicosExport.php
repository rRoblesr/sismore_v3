<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\MatriculaGeneralController;
use App\Http\Controllers\Educacion\ServiciosBasicosController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ServiciosBasicosExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $provincia;
    public $distrito;
    public $area;
    public $servicio;

    public function __construct($div, $anio, $provincia, $distrito, $area, $servicio)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->area = $area;
        $this->servicio = $servicio;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new ServiciosBasicosController())->principalTablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->area, $this->servicio);
            return view('educacion.ServiciosBasicos.PrincipalTabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new ServiciosBasicosController())->principalTablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->area, $this->servicio);
            return view('educacion.ServiciosBasicos.PrincipalTabla2Export', $mgs);
        } else {
            $mgs = (new ServiciosBasicosController())->principalTablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->area, $this->servicio);
            return view('educacion.ServiciosBasicos.PrincipalTabla3Export', $mgs);
        }
    }
}
