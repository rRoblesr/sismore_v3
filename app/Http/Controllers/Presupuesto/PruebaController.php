<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\Meta;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UnidadOrganica;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function prueba1()
    {
        return view('presupuesto.prueba.Prueba01');
    }
}
