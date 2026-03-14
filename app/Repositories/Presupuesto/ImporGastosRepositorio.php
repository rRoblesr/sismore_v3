<?php

namespace App\Repositories\Presupuesto;

use App\Models\Presupuesto\ImporGastos;
use Illuminate\Support\Facades\DB;

class ImporGastosRepositorio
{
    public static function listaImportada($id)
    {
        $query = ImporGastos::where('importacion_id', $id);
        return $query;
    }
}
