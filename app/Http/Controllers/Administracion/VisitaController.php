<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Visita;
use App\Models\Administracion\Sistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte()
    {
        return view('administracion.Visita.Reporte');
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = Visita::orderBy('created_at', 'desc');
        
        $totalRecords = $query->count();
        
        $data = $query->skip($start)->take($length)->get();
        
        $resultData = [];
        foreach ($data as $key => $value) {
            $sistema = $value->sistema_id ? Sistema::find($value->sistema_id) : null;
            $sistemaNombre = $sistema ? $sistema->nombre : 'GENERAL';
            
            $resultData[] = array(
                $start + $key + 1,
                $value->ip,
                $sistemaNombre,
                '<div style="word-break: break-all;">' . $value->url . '</div>',
                $value->user_agent,
                $value->created_at->format('Y-m-d H:i:s'),
            );
        }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $resultData,
        );
        return response()->json($result);
    }
}
