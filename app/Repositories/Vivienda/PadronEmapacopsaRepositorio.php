<?php

namespace App\Repositories\Vivienda;

use App\Models\Vivienda\PadronEmapacopsa;

class PadronEmapacopsaRepositorio
{
    public static function ListarImportados($importacion_id)
    {         
        $Lista = PadronEmapacopsa::where("importacion_id", "=", $importacion_id)
        ->get();

        return $Lista;
    }   
}