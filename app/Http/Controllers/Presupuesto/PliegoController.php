<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\Pliego;
use Illuminate\Http\Request;

class PliegoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargarpliego(Request $rq)
    {
        $pliegos = Pliego::where('sector_id', $rq->get('sector'))->get();
        return response()->json(compact('pliegos'));
    }
}
