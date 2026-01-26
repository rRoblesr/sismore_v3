<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Repositories\Educacion\CuboPacto2Repositorio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CuboPacto2Controller extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function mes($anio)
    {
        $mes = CuboPacto2Repositorio::mes_inscripcion($anio);
        return response()->json($mes);
    }

    public function provincia($anio, $mes)
    {
        $provincia = CuboPacto2Repositorio::provincia_inscripcion($anio, $mes);
        return response()->json($provincia);
    }

    public function distrito($anio, $mes, $provincia)
    {
        $distrito = CuboPacto2Repositorio::distrito_inscripcion($anio, $mes, $provincia);
        return response()->json($distrito);
    }

    public function sflugel()
    {
        $ugel = CuboPacto2Repositorio::sfl_ugel();
        return response()->json($ugel);
    }

    public function sflmodalidad($ugel)
    {
        $modalidad = CuboPacto2Repositorio::sfl_modalidad($ugel);
        return $modalidad;
    }

    public function sflnivel($ugel, $modalidad)
    {
        $nivel = CuboPacto2Repositorio::sfl_nivel($ugel, $modalidad);
        return $nivel;
    }

    public function sflprovincia($ugel)
    {
        $provincia = CuboPacto2Repositorio::sfl_provincia($ugel);
        return response()->json($provincia);
    }
    public function sfldistrito($ugel, $provincia)
    {
        $distrito = CuboPacto2Repositorio::sfl_distrito($ugel, $provincia);
        return response()->json($distrito);
    }
    public function sflestado($ugel, $provincia, $distrito)
    {
        $estado = CuboPacto2Repositorio::sfl_estado($ugel, $provincia, $distrito);
        return response()->json($estado);
    }
}
