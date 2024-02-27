<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\MatriculaGeneralController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BasicaEspecialExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $ugel;
    public $distrito;
    public $dependencia;
    public $provincia;

    public function __construct($div, $anio, $ugel, $distrito, $dependencia, $provincia)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->dependencia = $dependencia;
        $this->distrito = $distrito;
        $this->ugel = $ugel;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new MatriculaGeneralController())->basicaespecialtablaExport($this->div, $this->anio, $this->ugel, $this->distrito, $this->dependencia, $this->provincia);
            return view('educacion.MatriculaGeneral.BasicaEspecialTabla1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new MatriculaGeneralController())->basicaespecialtablaExport($this->div, $this->anio, $this->ugel, $this->distrito, $this->dependencia, $this->provincia);
            return view('educacion.MatriculaGeneral.BasicaEspecialTabla2Export', $mgs);
        } else {
            $mgs = (new MatriculaGeneralController())->basicaespecialtablaExport($this->div, $this->anio, $this->ugel, $this->distrito, $this->dependencia, $this->provincia);
            return view('educacion.MatriculaGeneral.BasicaEspecialTabla3Export', $mgs);
        }
    }
}
