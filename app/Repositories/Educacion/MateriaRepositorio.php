<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Grado;
use App\Models\Educacion\NivelModalidad;
use Illuminate\Support\Facades\DB;

class MateriaRepositorio
{

    public static function buscar_materia1($anio, $grado, $tipo)
    {
        $query = DB::table('edu_materia as v1')
            ->select('v1.*')
            ->join('edu_eceresultado as v2', 'v2.materia_id', '=', 'v1.id')
            ->join('edu_ece as v3', 'v3.id', '=', 'v2.ece_id')
            ->where('v3.grado_id', $grado)
            ->where('v3.tipo', $tipo)
            ->where('v3.anio', $anio)
            ->orderBy('v1.id', 'asc')
            ->distinct()->get();
        return $query;
    }
    public static function buscar_materia2($grado, $tipo, $materia = null)
    {
        $query1 = DB::table('edu_ece as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->where('v1.grado_id', $grado)
            ->where('v1.tipo', $tipo)
            ->where('v2.estado', 'PR')
            ->get([DB::raw('max(v1.anio) as anio')]);
        if ($materia) {
            $query = DB::table('edu_materia as v1')
                ->select('v1.*')
                ->join('edu_eceresultado as v2', 'v2.materia_id', '=', 'v1.id')
                ->join('edu_ece as v3', 'v3.id', '=', 'v2.ece_id')
                ->where('v1.id', $materia)
                ->where('v3.grado_id', $grado)
                ->where('v3.tipo', $tipo)
                ->where('v3.anio', '=', $query1[0]->anio)
                ->orderBy('v1.id', 'asc')
                ->distinct()->get();
        } else {
            $query = DB::table('edu_materia as v1')
                ->select('v1.*')
                ->join('edu_eceresultado as v2', 'v2.materia_id', '=', 'v1.id')
                ->join('edu_ece as v3', 'v3.id', '=', 'v2.ece_id')
                ->where('v3.grado_id', $grado)
                ->where('v3.tipo', $tipo)
                ->where('v3.anio', '=', $query1[0]->anio)
                ->orderBy('v1.id', 'asc')
                ->distinct()->get();
        }
        return $query;
    }
    public static function buscar_materia3($grado, $tipo, $materia = null)
    {
        if ($materia) {
            $query = DB::table('edu_materia as v1')
                ->select('v1.*')
                ->join('edu_eceresultado as v2', 'v2.materia_id', '=', 'v1.id')
                ->join('edu_ece as v3', 'v3.id', '=', 'v2.ece_id')
                ->join('par_importacion as v4', 'v4.id', '=', 'v3.importacion_id')
                ->where('v1.id', $materia)
                ->where('v3.grado_id', $grado)
                ->where('v3.tipo', $tipo)
                ->where('v4.estado', 'PR')
                ->orderBy('v1.id', 'asc')
                ->distinct()->get();
        } else {
            $query = DB::table('edu_materia as v1')
                ->select('v1.*')
                ->join('edu_eceresultado as v2', 'v2.materia_id', '=', 'v1.id')
                ->join('edu_ece as v3', 'v3.id', '=', 'v2.ece_id')
                ->join('par_importacion as v4', 'v4.id', '=', 'v3.importacion_id')
                ->where('v3.grado_id', $grado)
                ->where('v3.tipo', $tipo)
                ->where('v4.estado', 'PR')
                ->orderBy('v1.id', 'asc')
                ->distinct()->get();
        }
        return $query;
    }
}
