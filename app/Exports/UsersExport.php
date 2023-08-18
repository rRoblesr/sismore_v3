<?php

namespace App\Exports;

use App\Models\Administracion\Usuario;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Usuario::all();
    }
}
