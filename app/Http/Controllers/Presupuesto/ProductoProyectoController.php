<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\ProductoProyecto;
use Illuminate\Http\Request;

class ProductoProyectoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargar()
    {
        $pp = ProductoProyecto::all();
        return response()->jsopn(compact('pp'));
    }
}
