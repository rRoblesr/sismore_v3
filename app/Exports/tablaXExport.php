<?php

namespace App\Exports;

use App\Models\Administracion\Usuario;
use App\Models\Educacion\ImporPadronWeb;
use App\Models\Educacion\Importacion;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class tablaXExport implements FromView,ShouldAutoSize // FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    /* public function collection()
    {
        return ImporPadronWeb::all();// Usuario::all();
    } */

    public function view(): View
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 1, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        return view('educacion.ImporPadronWeb.Usux', ['padrons' => ImporPadronWeb::where('importacion_id', $imp->id)->get()]);
    }
}
