<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Educacion\MatriculaGeneralController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AvanceMatricula1Export implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $provincia;
    public $distrito;
    public $gestion;
    public $ugel;

    public function __construct($div, $anio, $provincia, $distrito, $gestion, $ugel)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->gestion = $gestion;
        $this->ugel = $ugel;
    }

    public function view(): View
    {
        if ($this->div == 'tabla1') {
            $mgs = (new IndicadorController())->panelControlEduacionNuevoindicador01Export($this->div, $this->anio, $this->provincia, $this->distrito, $this->gestion, $this->ugel);
            return view('parametro.indicador.educacion.inicioEducacionIndicador01Table1Export', $mgs);
        } else {
            $mgs = (new IndicadorController())->panelControlEduacionNuevoindicador01Export($this->div, $this->anio, $this->provincia, $this->distrito, $this->gestion, $this->ugel);
            return view('parametro.indicador.educacion.inicioEducacionIndicador01Table2Export', $mgs);
        }
    }
}
