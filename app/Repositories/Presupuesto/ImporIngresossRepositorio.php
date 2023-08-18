<?php

namespace App\Repositories\Educacion;

use App\Models\Presupuesto\ImporIngresos;
use Illuminate\Support\Facades\DB;

class ImporIngresossRepositorio
{
    public static function listaImportada($id)
    {
        $query = ImporIngresos::where('importacion_id', $id)->get();
        return $query;
    }
}
