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
        $sistemas = Sistema::orderBy('nombre')->get();
        return view('administracion.Visita.Reporte', compact('sistemas'));
    }

    public function resumen(Request $rq)
    {
        $baseQuery = Visita::query()
            ->when(!empty($rq->sistema), function ($query) use ($rq) {
                if ($rq->sistema == 3) {
                    $query->where(function($q) {
                        $q->where('sistema_id', 3)
                          ->orWhereNull('sistema_id');
                    });
                } else {
                    $query->where('sistema_id', $rq->sistema);
                }
            })
            ->when(!empty($rq->fecha_inicio), function ($query) use ($rq) {
                $query->whereDate('created_at', '>=', $rq->fecha_inicio);
            })
            ->when(!empty($rq->fecha_fin), function ($query) use ($rq) {
                $query->whereDate('created_at', '<=', $rq->fecha_fin);
            });

        $card1 = (clone $baseQuery)->count();
        $card2 = (clone $baseQuery)->distinct('ip')->count('ip');
        $card3 = (clone $baseQuery)->whereDate('created_at', date('Y-m-d'))->count();
        
        $topSystem = (clone $baseQuery)->select(DB::raw('IFNULL(sistema_id, 3) as sistema_id'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('IFNULL(sistema_id, 3)'))
            ->orderByDesc('total')
            ->first();
            
        $card4 = 'N/A';
        if ($topSystem) {
            $sys = $topSystem->sistema_id ? Sistema::find($topSystem->sistema_id) : null;
            $card4 = $sys ? $sys->nombre : ($topSystem->sistema_id ? 'Desconocido' : 'SALUD');
        }

        // Gráfico 1: Visitas por día
        $grafico1 = (clone $baseQuery)->select(DB::raw('DATE(created_at) as fecha'), DB::raw('count(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
            
        // Gráfico 2: Visitas por Sistema
        $grafico2 = (clone $baseQuery)->select(DB::raw('IFNULL(sistema_id, 3) as sistema_id'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('IFNULL(sistema_id, 3)'))
            ->orderByDesc('total')
            ->limit(10)
            ->get();
            
        $grafico2_data = [];
        foreach ($grafico2 as $item) {
            $sys = $item->sistema_id ? Sistema::find($item->sistema_id) : null;
            $name = $sys ? $sys->nombre : 'SALUD';
            $grafico2_data[] = ['name' => $name, 'y' => $item->total];
        }

        // Gráfico 3: Tendencia por Sistema
        $grafico3_raw = (clone $baseQuery)->select(DB::raw('DATE(created_at) as fecha'), DB::raw('IFNULL(sistema_id, 3) as sistema_id'), DB::raw('count(*) as total'))
            ->groupBy('fecha', DB::raw('IFNULL(sistema_id, 3)'))
            ->orderBy('fecha')
            ->get();

        $fechas = $grafico1->pluck('fecha')->toArray();
        $grafico3_series = [];
        
        $dataBySystem = [];
        foreach ($grafico3_raw as $item) {
            $sysId = $item->sistema_id ? $item->sistema_id : 0;
            $dataBySystem[$sysId][$item->fecha] = $item->total;
        }

        $involvedSystemIds = array_keys($dataBySystem);
        $systemsDict = [];
        if (!empty($involvedSystemIds)) {
             $systemsDict = Sistema::whereIn('id', $involvedSystemIds)->pluck('nombre', 'id')->toArray();
        }

        foreach ($dataBySystem as $sysId => $dates) {
            $seriesData = [];
            foreach ($fechas as $fecha) {
                $seriesData[] = isset($dates[$fecha]) ? (int)$dates[$fecha] : 0;
            }
            
            $sysName = $sysId == 0 ? 'SALUD' : ($systemsDict[$sysId] ?? 'Desconocido');
            
            $grafico3_series[] = [
                'name' => $sysName,
                'data' => $seriesData
            ];
        }

        return response()->json([
            'card1' => number_format($card1),
            'card2' => number_format($card2),
            'card3' => number_format($card3),
            'card4' => $card4,
            'grafico1' => $grafico1,
            'grafico2' => $grafico2_data,
            'grafico3' => $grafico3_series
        ]);
    }

    private function getBrowser($user_agent)
    {
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
        elseif (strpos($user_agent, 'Edge')) return 'Edge';
        elseif (strpos($user_agent, 'Chrome')) return 'Google Chrome';
        elseif (strpos($user_agent, 'Safari')) return 'Safari';
        elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
        elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
        
        return 'Otro';
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = Visita::orderBy('created_at', 'desc')
            ->when(!empty($rq->sistema), function ($q) use ($rq) {
                if ($rq->sistema == 3) {
                    $q->where(function($sub) {
                        $sub->where('sistema_id', 3)
                            ->orWhereNull('sistema_id');
                    });
                } else {
                    $q->where('sistema_id', $rq->sistema);
                }
            })
            ->when(!empty($rq->fecha_inicio), function ($q) use ($rq) {
                $q->whereDate('created_at', '>=', $rq->fecha_inicio);
            })
            ->when(!empty($rq->fecha_fin), function ($q) use ($rq) {
                $q->whereDate('created_at', '<=', $rq->fecha_fin);
            });
        
        $totalRecords = $query->count();
        
        $data = $query->skip($start)->take($length)->get();
        
        $resultData = [];
        foreach ($data as $key => $value) {
            $sistema = $value->sistema_id ? Sistema::find($value->sistema_id) : null;
            $sistemaNombre = $sistema ? $sistema->nombre : 'SALUD';
            
            $resultData[] = array(
                $start + $key + 1,
                $value->ip,
                $sistemaNombre,
                '<div style="word-break: break-all;">' . $value->url . '</div>',
                $this->getBrowser($value->user_agent),
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
