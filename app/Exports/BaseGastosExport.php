<?php

namespace App\Exports;

use App\Http\Controllers\Presupuesto\BaseGastosController;
use App\Repositories\Presupuesto\BaseGastosRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class BaseGastosExport implements FromView, ShouldAutoSize
{
    public $div;
    public $basegasto_id;

    public function __construct($div, $basegasto_id)
    {
        $this->div = $div;
        $this->basegasto_id = $basegasto_id;
    }

    public function view(): View
    {
        $mgs = (new BaseGastosController())->nivelesgobiernosExportExcel($this->div);
        if ($this->div == 'table1')
            return view('presupuesto.BaseGastos.NivelesGobiernosTabla1Excel', $mgs);
        else if ($this->div == 'tabla2')
            return view('presupuesto.BaseGastos.NivelesGobiernosTabla2Excel', $mgs);
    }
}
