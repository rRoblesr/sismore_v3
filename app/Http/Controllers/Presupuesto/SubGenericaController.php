<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\SubGenericaGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubGenericaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargarsg(Request $rq)
    {
        $query = SubGenericaGasto::where('generica_id', $rq->get('generica'))->get();
        return response()->json(compact('query'));
    }
}
