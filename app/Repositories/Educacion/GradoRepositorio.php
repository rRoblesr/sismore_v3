<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Grado;
use App\Models\Educacion\NivelModalidad;
use Illuminate\Support\Facades\DB;

class GradoRepositorio
{
    public static function buscar_nivel1()
    {
        $query = NivelModalidad::whereIn('id', ['7', '8'])->get();
        return $query;
    }
    public static function buscar_grado1($grado)
    {
        $query = DB::table('edu_grado as v1')
        ->join('edu_nivelmodalidad as v2', 'v2.id', '=', 'v1.nivelmodalidad_id')
        ->select('v1.id', 'v1.descripcion as grado', 'v2.nombre as nivel')
        ->where('v1.id', $grado)
        ->get();
        return $query;
    }
    public static function buscar_grados1($nivel)
    {
        $query = Grado::where('nivelmodalidad_id', $nivel)->get();
        return $query;
    }
}
