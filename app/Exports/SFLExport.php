<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Educacion\MatriculaGeneralController;
use App\Http\Controllers\Educacion\SFLController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SFLExport implements FromView, ShouldAutoSize
{
    public $ugel;
    public $provincia;
    public $distrito;
    public $estado;


    public function __construct($ugel, $provincia, $distrito, $estado)
    {
        $this->ugel = $ugel;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->estado = $estado;
    }

    public function view(): View
    {
        $mgs = (new SFLController())->ListarDTExport($this->ugel, $this->provincia, $this->distrito, $this->estado);
        return view('educacion.SFL.PrincipalTabla1excelExport', $mgs);
    }
}
