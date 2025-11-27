<?php

namespace App\Http\Controllers\Educacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Educacion\CuboPadronEIBRepositorio;

class CuboPadronEIBController extends Controller
{
    public function cargargestion($anio)
    {
        $gestiones = CuboPadronEIBRepositorio::select_gestion($anio);
        return response()->json($gestiones);
    }

    public function cargarprovincia($anio, $ugel)
    {
        $provincias = CuboPadronEIBRepositorio::select_provincia($anio, $ugel);
        return response()->json($provincias);
    }

    public function cargardistrito($anio, $ugel, $provincia)
    {
        $distritos = CuboPadronEIBRepositorio::select_distrito($anio, $ugel, $provincia);
        return response()->json($distritos);
    }
}
