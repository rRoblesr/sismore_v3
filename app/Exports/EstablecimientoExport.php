<?php

namespace App\Exports;

use App\Http\Controllers\Salud\EstablecimientoController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EstablecimientoExport implements FromView, ShouldAutoSize
{
    public $div;
    public $provincia;
    public $distrito;
    public $red;
    public $microrred;

    public function __construct($div, $provincia, $distrito, $red, $microrred)
    {
        $this->div = $div;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
        $this->red = $red;
        $this->microrred = $microrred;
    }

    public function view(): View
    {
        ini_set('memory_limit', '1024M');
        if ($this->div == 'tabla1') {
            $mgs = (new EstablecimientoController())->dashboardContenidoDownload($this->div, $this->provincia, $this->distrito, $this->red, $this->microrred);
            return view('salud.Establecimiento.dashboardTabla1Excel', $mgs);
        } else if ($this->div == 'tabla2') {
            $mgs = (new EstablecimientoController())->dashboardContenidoDownload($this->div, $this->provincia, $this->distrito, $this->red, $this->microrred);
            return view('salud.Establecimiento.dashboardTabla2Excel', $mgs);
        } else {
            $mgs = (new EstablecimientoController())->dashboardContenidoDownload($this->div, $this->provincia, $this->distrito, $this->red, $this->microrred);
            return view('salud.Establecimiento.dashboardTabla3Excel', $mgs);
        }
    }
}
