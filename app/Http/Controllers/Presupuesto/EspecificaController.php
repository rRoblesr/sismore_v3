<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\EspecificaGasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecificaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargar(Request $rq)
    {
        $query = EspecificaGasto::where('subgenericadetalle_id', $rq->get('subgenericadetalle'))->get();
        return response()->json(compact('query'));
    }
}
