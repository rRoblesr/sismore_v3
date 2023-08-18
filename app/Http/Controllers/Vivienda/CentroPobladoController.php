<?php

namespace App\Http\Controllers\Vivienda;
use App\Http\Controllers\Controller;
use App\Repositories\Vivienda\CentroPobladoRepositotio;

class CentroPobladoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $lista = CentroPobladoRepositotio::listaPor_Provincia_Distrito(240);
        $anios = CentroPobladoRepositotio ::anios( );

        //        $fechas_matriculas = MatriculaRepositorio ::fechas_matriculas_anio($anios->first()->id);
      
        return view('vivienda.CentroPoblado.Principal',compact('anios'));
    }

    
}
