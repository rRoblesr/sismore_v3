<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\MatriculaGeneralController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BasicaRegularExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $ugel;
    public $gestion;
    public $area;
    public $provincia;

    public function __construct($div, $anio, $ugel, $gestion, $area, $provincia)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->area = $area;
        $this->gestion = $gestion;
        $this->ugel = $ugel;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new MatriculaGeneralController())->basicaregulartablaExport($this->div, $this->anio, $this->ugel, $this->gestion, $this->area, $this->provincia);
            return view('educacion.MatriculaGeneral.BasicaRegularTabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new MatriculaGeneralController())->basicaregulartablaExport($this->div, $this->anio, $this->ugel, $this->gestion, $this->area, $this->provincia);
            return view('educacion.MatriculaGeneral.BasicaRegularTabla2Export', $mgs);
        } else {
            $mgs = (new MatriculaGeneralController())->basicaregulartablaExport($this->div, $this->anio, $this->ugel, $this->gestion, $this->area, $this->provincia);
            return view('educacion.MatriculaGeneral.BasicaRegularTabla3Export', $mgs);
        }
    }
}
