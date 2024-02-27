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
    public $ugel;
    public $gestion;
    public $area;
    public $servicio;

    public function __construct($div, $anio, $ugel, $gestion, $area, $servicio)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->ugel = $ugel;
        $this->gestion = $gestion;
        $this->area = $area;
        $this->servicio = $servicio;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new ServiciosBasicosController())->principalTablaExport($this->div, $this->anio, $this->ugel, $this->gestion, $this->area, $this->servicio);
            return view('educacion.ServiciosBasicos.PrincipalTabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new ServiciosBasicosController())->principalTablaExport($this->div, $this->anio, $this->ugel, $this->gestion, $this->area, $this->servicio);
            return view('educacion.ServiciosBasicos.PrincipalTabla2Export', $mgs);
        } else {
            $mgs = (new ServiciosBasicosController())->principalTablaExport($this->div, $this->anio, $this->ugel, $this->gestion, $this->area, $this->servicio);
            return view('educacion.ServiciosBasicos.PrincipalTabla3Export', $mgs);
        }
    }
}
