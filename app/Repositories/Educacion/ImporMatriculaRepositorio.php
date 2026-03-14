<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\ImporMatriculaGeneral;

class ImporMatriculaRepositorio
{
    public static function Listar_Por_Importacion_id($importacion_id)
    {
        return ImporMatriculaGeneral::where("importacion_id", $importacion_id);
    }
}
