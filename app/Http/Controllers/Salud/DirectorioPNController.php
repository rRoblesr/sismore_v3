<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Salud\DirectorioPN;
use App\Models\Salud\Establecimiento;
use App\Repositories\Salud\EstablecimientoRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class DirectorioPNController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        // $red = DB::table('sal_red')->where('cod_disa', '34')->get();
        $red = DB::select("SELECT * from sal_red where id in( SELECT DISTINCT red_id from sal_microred where id in( SELECT DISTINCT microrred_id FROM `sal_establecimiento` where cod_disa=34 and categoria in ('I-1','I-2','I-3','I-4') and institucion in ('GOBIERNO REGIONAL','MINSA') and estado='ACTIVO'))");
        $mensaje = "";
        return view('salud.DirectorioPN.Principal', compact('mensaje', 'red'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = DirectorioPN::orderBy('id', 'desc');
        if ($rq->red > 0) {
            $query = $query->where('red_id', $rq->red);
        }
        if ($rq->micro > 0) {
            $query = $query->where('microred_id', $rq->micro);
        }
        $query = $query->get();

        $red = EstablecimientoRepositorio::arrayIdRed();
        $micro = EstablecimientoRepositorio::arrayIdmicrored();
        $unico = EstablecimientoRepositorio::arrayIdEESS()->pluck('codigo_unico', 'id');
        $eess = EstablecimientoRepositorio::arrayIdEESS()->pluck('nombre_establecimiento', 'id');

        $data = [];
        foreach ($query as $key => $value) {
            $btn = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            if ($value->estado == 0) {
                $btn .= '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-power-off"></i></a> ';
            } else {
                $btn .= '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-check"></i></a> ';
            }
            $btn .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            //$btn .= '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';

            $data[] = array(
                $key + 1,
                $red[$value->red_id] ?? '-',
                $micro[$value->microred_id] ?? '-',
                $unico[$value->establecimiento_id] ?? '-',
                $eess[$value->establecimiento_id] ?? '-',
                $value->nombres . ' ' . $value->apellido_paterno . ' ' . $value->apellido_materno,
                $value->cargo,
                $value->condicion_laboral,
                "<center>" . $btn  . "</center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "rq" => $rq->all(),
            'red' => $unico
        );
        return response()->json($result);
    }



    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $usuarioxx = DirectorioPN::where('dni', $request->dni)->first();

        if ($request->dni == '') {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->dni) < 8) {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'Este campo necesita 8 digitos.';
            $data['status'] = FALSE;
        } else if ($usuarioxx && $request->id == '') {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'DNI ingresado ya existe.';
            $data['status'] = FALSE;
        }

        if ($request->nombres == '') {
            $data['inputerror'][] = 'nombres';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->apellido_paterno == '') {
            $data['inputerror'][] = 'apellido_paterno';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->apellido_materno == '') {
            $data['inputerror'][] = 'apellido_materno';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->profesion == '') {
            $data['inputerror'][] = 'profesion';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->cargo == '') {
            $data['inputerror'][] = 'cargo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->condicion_laboral == '') {
            $data['inputerror'][] = 'condicion_laboral';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->feess == '') {
            $data['inputerror'][] = 'feess';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->celular == '') {
            $data['inputerror'][] = 'celular';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->email == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
    public function ajax_add(Request $request)
    {
        $this->_validate($request);
        $rer = DirectorioPN::Create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'sexo' => $request->sexo,
            'profesion' => $request->profesion,
            'cargo' => $request->cargo,
            'condicion_laboral' => $request->condicion_laboral,
            'red_id' => $request->fred,
            'microred_id' => $request->fmicrored,
            'establecimiento_id' => $request->feess,
            'celular' => $request->celular,
            'email' => $request->email,
        ]);
        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $rer = DirectorioPN::find($request->id);
        $rer->dni = $request->dni;
        $rer->nombres = $request->nombres;
        $rer->apellido_paterno = $request->apellido_paterno;
        $rer->apellido_materno = $request->apellido_materno;
        $rer->sexo = $request->sexo;
        $rer->profesion = $request->profesion;
        $rer->cargo = $request->cargo;
        $rer->condicion_laboral = $request->condicion_laboral;
        $rer->red_id = $request->fred;
        $rer->microred_id = $request->fmicrored;
        $rer->establecimiento_id = $request->feess;
        $rer->celular = $request->celular;
        $rer->email = $request->email;
        $rer->save();

        return response()->json(['status' => true, 'data' => $request->all(), 'obj' => $rer]);
    }
    public function ajax_edit($id)
    {
        $dpn = DirectorioPN::find($id);
        // $dpn->entidadn = $this->getentidad($dpn->nivel, $dpn->codigo);
        return response()->json(compact('dpn'));
    }

    public function getentidad($nivel, $codigo)
    {
        switch ($nivel) {
            case '2':
                $query = DB::table('sal_red')->where('id', $codigo)->first();
                if ($query) {
                    return $query->codigo . ' ' . $query->nombre;
                }
                break;

            case '3':
                $query = DB::table('sal_microred')->where('id', $codigo)->first();
                if ($query) {
                    return $query->codigo . ' ' . $query->nombre;
                }
                break;

            case '4':
                $query = Establecimiento::find($codigo);
                if ($query) {
                    return str_pad($query->cod_unico, 8, '0', STR_PAD_LEFT) . ' | ' . $query->nombre_establecimiento;
                }
                break;

            default:
                return '';
                break;
        }
    }

    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = DirectorioPN::find($id);
        $rer->delete();
        return response()->json(array('status' => true, 'rer' => $rer));
    }
    public function ajax_estado($id)
    {
        $rer = DirectorioPN::find($id);
        $rer->estado = $rer->estado == '0' ? '1' : '0';
        $rer->save();
        return response()->json(array('status' => true));
    }

    public function autocompletarProfesion(Request $rq)
    {
        $term = $rq->term;
        $query = DirectorioPN::distinct()->select('profesion')
            ->where(function ($q) use ($term) {
                $q->where('profesion', 'like', '%' . $term . '%'); //->orWhere('codigo', 'like', '%' . $term . '%');
            })->get();

        $data = $query->count() > 0
            ? $query->map(fn($value) => ['label' => $value->profesion, 'id' => 0])->toArray()
            : [['label' => 'SIN REGISTROS', 'id' => 0]];

        return response()->json($data);
    }

    public function autocompletarCondicion(Request $rq)
    {
        $term = $rq->term;
        $query = DirectorioPN::distinct()->select('condicion_laboral')
            ->where(function ($q) use ($term) {
                $q->where('condicion_laboral', 'like', '%' . $term . '%'); //->orWhere('codigo', 'like', '%' . $term . '%');
            })->get();

        $data = $query->count() > 0
            ? $query->map(fn($value) => ['label' => $value->condicion_laboral, 'id' => 0])->toArray()
            : [['label' => 'SIN REGISTROS', 'id' => 0]];

        return response()->json($data);
    }

    public function autocompletarCargo(Request $rq)
    {
        $term = $rq->term;
        $query = DirectorioPN::distinct()->select('cargo')
            ->where(function ($q) use ($term) {
                $q->where('cargo', 'like', '%' . $term . '%'); //->orWhere('codigo', 'like', '%' . $term . '%');
            })->get();

        $data = $query->count() > 0
            ? $query->map(fn($value) => ['label' => $value->cargo, 'id' => 0])->toArray()
            : [['label' => 'SIN REGISTROS', 'id' => 0]];

        return response()->json($data);
    }

    // public function cargarEntidad($nivel)
    // {
    //     switch ($nivel) {
    //         case '2':
    //             return DB::table('sal_red')->select('id', DB::raw('concat(codigo," ",nombre) as nombrex'))->where('cod_disa', '34')->get();
    //         case '3':
    //             return DB::table('sal_microred as m')->join('sal_red as r', 'r.id', '=', 'm.red_id')
    //                 ->select('m.id', DB::raw('concat(m.codigo," ",m.nombre," | ",r.nombre) as nombrex'))->where('m.cod_disa', '34')->get();
    //         case '4':
    //             return EstablecimientoRepositorio::queAtiendenCargar();
    //         default:
    //             return [];
    //     }

    //     return response()->json($data);
    // }
}
