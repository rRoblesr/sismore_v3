<?php

namespace App\Exports;

use App\Models\Educacion\ImporPadronWeb;
use App\Models\Educacion\Importacion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ImporPadronWebExport implements FromView,ShouldAutoSize
{
    public function view(): View
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 1, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        return view('educacion.ImporPadronWeb.excel', ['padrons' => ImporPadronWeb::where('importacion_id', $imp->id)->get()]);
    }
}
