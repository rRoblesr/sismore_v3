<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\LoginRecords;
use App\Models\Parametro\Icono;
use App\Models\Administracion\Sistema;
use App\Models\Administracion\Usuario;
use App\Repositories\Administracion\EntidadRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginRecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function reporte()
    {
        $sistemas = Sistema::where('estado', 1)->get();
        return view('administracion.LoginRecords.Reporte', compact('sistemas'));
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $fecha_inicio = $rq->get('fecha_inicio');
        $fecha_fin = $rq->get('fecha_fin');
        $sistema = $rq->get('sistema');
        
        $order = $rq->get('order');
        $colIndex = $order[0]['column'] ?? 0;
        $colDir = $order[0]['dir'] ?? 'desc';

        $query = DB::table('adm_login_records as l')
            ->leftJoin('adm_usuario as u', 'u.id', '=', 'l.usuario')
            ->leftJoin('adm_entidad as o', 'o.id', '=', 'u.entidad') // oficina
            ->leftJoin('adm_entidad as e', 'e.id', '=', 'o.dependencia') // entidad
            ->select(
                'l.id',
                'l.ip',
                'l.login',
                'l.logout',
                'l.user_agent',
                'u.nombre as u_nombre',
                'u.apellido1 as u_apellido1',
                'u.apellido2 as u_apellido2',
                'o.nombre as oficina',
                'e.nombre as entidad'
            );

        if ($fecha_inicio) {
            $query->whereDate('l.login', '>=', $fecha_inicio);
        }
        if ($fecha_fin) {
            $query->whereDate('l.login', '<=', $fecha_fin);
        }
        if ($sistema) {
            $query->whereExists(function ($subquery) use ($sistema) {
                $subquery->select(DB::raw(1))
                    ->from('adm_usuario_perfil as up')
                    ->join('adm_perfil as p', 'p.id', '=', 'up.perfil_id')
                    ->whereColumn('up.usuario_id', 'l.usuario')
                    ->where('p.sistema_id', $sistema);
            });
        }

        $totalRecords = $query->count();

        // Ordenamiento
        switch ($colIndex) {
            case 1: // Usuario
                $query->orderBy('u.nombre', $colDir)->orderBy('u.apellido1', $colDir);
                break;
            case 2: // Entidad
                $query->orderBy('e.nombre', $colDir);
                break;
            case 3: // Oficina
                $query->orderBy('o.nombre', $colDir);
                break;
            case 4: // IP
                $query->orderBy('l.ip', $colDir);
                break;
            case 5: // Login
                $query->orderBy('l.login', $colDir);
                break;
            case 6: // Logout
                $query->orderBy('l.logout', $colDir);
                break;
            case 7: // Navegador
                $query->orderBy('l.user_agent', $colDir);
                break;
            default: // N° o default
                $query->orderBy('l.id', 'desc');
                break;
        }

        $data = $query->skip($start)
            ->take($length)
            ->get();

        $resultData = [];
        foreach ($data as $key => $value) {
            $nombreUsuario = $value->u_nombre ? ($value->u_nombre . ', ' . $value->u_apellido1 . ' ' . $value->u_apellido2) : 'Usuario Desconocido';
            
            $resultData[] = array(
                $start + $key + 1,
                $nombreUsuario,
                $value->entidad ?? '',
                $value->oficina ?? '',
                $value->ip,
                $value->login,
                $value->logout,
                $this->getBrowser($value->user_agent),
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

    public function resumen(Request $rq)
    {
        $fecha_inicio = $rq->get('fecha_inicio');
        $fecha_fin = $rq->get('fecha_fin');
        $sistema = $rq->get('sistema');

        $query = DB::table('adm_login_records as l')
            ->join('adm_usuario as u', 'u.id', '=', 'l.usuario')
            ->join('adm_entidad as o', 'o.id', '=', 'u.entidad') // oficina
            ->join('adm_entidad as e', 'e.id', '=', 'o.dependencia'); // entidad

        if ($fecha_inicio) {
            $query->whereDate('l.login', '>=', $fecha_inicio);
        }
        if ($fecha_fin) {
            $query->whereDate('l.login', '<=', $fecha_fin);
        }
        if ($sistema) {
            $query->whereExists(function ($subquery) use ($sistema) {
                $subquery->select(DB::raw(1))
                    ->from('adm_usuario_perfil as up')
                    ->join('adm_perfil as p', 'p.id', '=', 'up.perfil_id')
                    ->whereColumn('up.usuario_id', 'u.id')
                    ->where('p.sistema_id', $sistema);
            });
        }

        // Card 1: Total Accesos
        $card1 = (clone $query)->count();

        // Card 2: Usuarios Únicos
        $card2 = (clone $query)->distinct('l.usuario')->count('l.usuario');

        // Card 3: Accesos Hoy
        $queryHoy = DB::table('adm_login_records as l');
        if ($sistema) {
            $queryHoy->join('adm_usuario as u', 'u.id', '=', 'l.usuario')
                ->whereExists(function ($subquery) use ($sistema) {
                    $subquery->select(DB::raw(1))
                        ->from('adm_usuario_perfil as up')
                        ->join('adm_perfil as p', 'p.id', '=', 'up.perfil_id')
                        ->whereColumn('up.usuario_id', 'u.id')
                        ->where('p.sistema_id', $sistema);
                });
        }
        $card3 = $queryHoy->whereDate('l.login', date('Y-m-d'))->count();

        // Card 4: Top Entidad
        $topEntity = (clone $query)->select('e.nombre', DB::raw('count(*) as total'))
            ->groupBy('e.nombre')
            ->orderByDesc('total')
            ->first();
        $card4 = $topEntity ? $topEntity->nombre : 'N/A';

        // Grafico 1: Tendencia (Accesos por día)
        $grafico1 = (clone $query)->select(DB::raw('DATE(l.login) as fecha'), DB::raw('count(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Grafico 2: Accesos por Sistema (Top 10) - usando joins y conteo de logins
        $grafico2_raw = (clone $query)
            ->join('adm_usuario_perfil as up', 'up.usuario_id', '=', 'u.id')
            ->join('adm_perfil as p', 'p.id', '=', 'up.perfil_id')
            ->select('p.sistema_id', DB::raw('count(distinct l.id) as total'))
            ->groupBy('p.sistema_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $grafico2 = [];
        $ids = collect($grafico2_raw)->pluck('sistema_id')->filter()->unique()->values();
        $nombres = Sistema::whereIn('id', $ids)->pluck('nombre', 'id');
        foreach ($grafico2_raw as $row) {
            $name = $row->sistema_id ? ($nombres[$row->sistema_id] ?? 'Sistema ' . $row->sistema_id) : 'Sin Sistema';
            $grafico2[] = ['name' => $name, 'y' => $row->total];
        }

        return response()->json([
            'card1' => number_format($card1),
            'card2' => number_format($card2),
            'card3' => number_format($card3),
            'card4' => $card4,
            'grafico1' => $grafico1,
            'grafico2' => $grafico2,
        ]);
    }

    public function listarjjjDT()
    {
        $data = Sistema::orderBy('pos', 'desc')->get();
        return  datatables()::of($data)
            ->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            })
            ->addColumn('action', function ($data) {
                $acciones = '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';

                if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estado(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                }
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })
            ->rawColumns(['action', 'estado', 'icono'])
            ->make(true);
    }

    public function ajax_edit($sistema_id)
    {
        $sistema = Sistema::find($sistema_id);

        return response()->json(compact('sistema'));
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->nombre == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        return $data;
    }
    public function ajax_add(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $perfil = Sistema::Create([
            'nombre' => $request->nombre,
            'icono' => $request->icono,
            'pos' => $request->pos,
            'estado' => '1',
        ]);

        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $sistema = Sistema::find($request->id);
        $sistema->nombre = $request->nombre;
        $sistema->icono = $request->icono;
        $sistema->pos = $request->pos;
        $sistema->save();

        return response()->json(array('status' => true, 'update' => $request));
    }
    public function ajax_delete($sistema_id)
    {
        $sistema = Sistema::find($sistema_id);
        $sistema->delete();
        return response()->json(array('status' => true, 'sistema' => $sistema));
    }
    public function ajax_estado($sistema_id)
    {
        $sistema = Sistema::find($sistema_id);
        $sistema->estado = $sistema->estado == 1 ? 0 : 1;
        $sistema->save();
        return response()->json(array('status' => true, 'estado' => $sistema->estado));
    }

    private function getBrowser($user_agent)
    {
        if (empty($user_agent)) return '';
        if (strpos($user_agent, 'Edg') !== false) return 'Edge';
        if (strpos($user_agent, 'OPR') !== false || strpos($user_agent, 'Opera') !== false) return 'Opera';
        if (strpos($user_agent, 'Chrome') !== false) return 'Chrome';
        if (strpos($user_agent, 'Firefox') !== false) return 'Firefox';
        if (strpos($user_agent, 'Safari') !== false) return 'Safari';
        if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) return 'Internet Explorer';
        return 'Otros';
    }
}
