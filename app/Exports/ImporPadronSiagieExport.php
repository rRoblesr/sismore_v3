<?php

namespace App\Exports;

use App\Models\Educacion\ImporMatricula;
use App\Models\Educacion\ImporPadronWeb;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ImporPadronSiagieExport implements FromView,ShouldAutoSize
{
    public function view(): View
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 8, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        return view('educacion.ImporMatricula.excel', ['padrons' => ImporMatricula::where('matricula_id', $mat->id)->get()]);
    }
}
