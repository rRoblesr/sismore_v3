<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Repositories\Parametro\UbigeoRepositorio;

class UbigeoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargarprovincia25()
    {
        $provincias = UbigeoRepositorio::provincia('25');
        return response()->json($provincias);
    }
    public function cargardistrito25($provincia)
    {
        $distritos = UbigeoRepositorio::distrito('25', $provincia);
        return response()->json($distritos);
    }
}
