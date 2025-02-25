<?php

namespace App\Exports\Salud;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PlantillaExport implements FromView
{
    public function view(): View
    {
        // Devuelve una vista que contiene solo las cabeceras
        return view('salud.ImporPadronPrograma.plantilla');
    }
}
