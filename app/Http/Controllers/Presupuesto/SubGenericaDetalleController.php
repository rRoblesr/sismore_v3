<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\SubGenericaDetalleGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubGenericaDetalleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargar(Request $rq)
    {
        $query = SubGenericaDetalleGasto::where('subgenerica_id', $rq->get('subgenerica'))->get();
        return response()->json(compact('query'));
    }
}
