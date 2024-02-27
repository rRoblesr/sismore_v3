<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Educacion\MatriculaGeneralController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CensoDocenteSecundariaExport  implements FromView, ShouldAutoSize
{
    public $div;
    public $anio;
    public $provincia;
    public $distrito;
    public $gestion;
    public $ugel;

    public function __construct($div, $anio, $provincia, $distrito, $gestion)
    {
        $this->div = $div;
        $this->anio = $anio;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->gestion = $gestion;
    }

    public function view(): View
    {
        if ($this->div == 'ctabla1') {
            $mgs = (new IndicadorController())->panelControlEduacionNuevoindicador04Export($this->div, $this->anio, $this->provincia, $this->distrito, $this->gestion);
            return view('parametro.indicador.educacion.inicioEducacionIndicador04Table1Export', $mgs);
        } else if ($this->div == 'ctabla2') {
            $mgs = (new IndicadorController())->panelControlEduacionNuevoindicador04Export($this->div, $this->anio, $this->provincia, $this->distrito, $this->gestion);
            return view('parametro.indicador.educacion.inicioEducacionIndicador04Table2Export', $mgs);
        }
    }
}
