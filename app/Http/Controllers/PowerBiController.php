<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

use App\Http\Controllers\Controller;

class PowerBiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    }

    public function trabajoPlanillaElectronica()
    {
        return view('PowerBi.Trabajo.PlantillaElectronica');
    }

    public function trabajoEmpleoFormal()
    {
        return view('PowerBi.Trabajo.EmpleoFormal');
    }

    public function trabajoEmpleoInformal()
    {
        return view('PowerBi.Trabajo.EmpleoInformal');
    }

    public function saludCovid19()
    {
        return view('PowerBi.Salud.Covid19');
    }
}
