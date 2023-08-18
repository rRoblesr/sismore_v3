<?php

namespace App\Repositories\Educacion;

use App\Models\Presupuesto\ImporGastos;
use Illuminate\Support\Facades\DB;

class ImporGastosRepositorio
{
    public static function listaImportada($id)
    {
        $query = ImporGastos::where('importacion_id', $id)->get();
        return $query;
    }
}
