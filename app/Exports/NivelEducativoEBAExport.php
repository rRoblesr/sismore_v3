<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\MatriculaGeneralController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NivelEducativoEBAExport implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $provincia;
    public $distrito;
    public $nivel;


    public function __construct($div, $anio, $provincia, $distrito, $nivel)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->nivel = $nivel;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new MatriculaGeneralController())->niveleducativoEBAtablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->nivel);
            return view('educacion.MatriculaGeneral.NivelEducativoEBATable1Export', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new MatriculaGeneralController())->niveleducativoEBAtablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->nivel);
            return view('educacion.MatriculaGeneral.NivelEducativoEBATable2Export', $mgs);
        } else if ($this->div == 'tabla3') {
            $mgs = (new MatriculaGeneralController())->niveleducativoEBAtablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->nivel);
            return view('educacion.MatriculaGeneral.NivelEducativoEBATable3Export', $mgs);
        } else if ($this->div == 'tabla4') {
            $mgs = (new MatriculaGeneralController())->niveleducativoEBAtablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->nivel);
            return view('educacion.MatriculaGeneral.NivelEducativoEBATable4Export', $mgs);
        } else {
            $mgs = (new MatriculaGeneralController())->niveleducativoEBAtablaExport($this->div, $this->anio, $this->provincia, $this->distrito, $this->nivel);
            return view('educacion.MatriculaGeneral.NivelEducativoEBATable1Export', $mgs);
        }
    }
}
