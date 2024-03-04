<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Repositories\Parametro\UbigeoRepositorio;

class IndicadoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PactoRegional()
    {
        return view('salud.Indicadores.PactoRegional');
    }

    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
