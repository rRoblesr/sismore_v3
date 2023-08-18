<?php

namespace App\Exports;

use App\Models\Educacion\CuadroAsigPersonal;
use App\Models\Educacion\ImporPadronWeb;
use App\Models\Educacion\Importacion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ImporPadronNexusExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 2, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        return view('educacion.CuadroAsigPersonal.excel', ['padrons' => CuadroAsigPersonal::where('importacion_id', $imp->id)->get()]);
    }
}
