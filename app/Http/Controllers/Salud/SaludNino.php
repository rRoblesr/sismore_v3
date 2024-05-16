<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;

class SaludNino extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PadronNominal()
    {
        return view('salud.nino.PadronNominal');
    }

    public function ControlCalidad()
    {
        return view('salud.nino.ControlCalidad');
    }
}
