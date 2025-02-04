<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Administracion\DirectoriosAuditoria;
use App\Models\Salud\DirectorioPN;
use App\Models\Salud\Establecimiento;
use App\Repositories\Salud\EstablecimientoRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class DirectorioPNController extends Controller
{
    public static $VISTA_DASHBOARD = 'D';
    public static $VISTA_MANTENIMIENTO = 'm';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // $red = DB::table('sal_red')->where('cod_disa', '34')->get();
        $red = DB::select("SELECT * from sal_red where id in( SELECT DISTINCT red_id from sal_microred where id in( SELECT DISTINCT microrred_id FROM `sal_establecimiento` where cod_disa=34 and categoria in ('I-1','I-2','I-3','I-4') and institucion in ('GOBIERNO REGIONAL','MINSA') and estado='ACTIVO'))");
        $mensaje = "";
        $vista = 'D';
        return view('salud.DirectorioPN.Principal', compact('vista', 'mensaje', 'red'));
    }

    public function principal()
    {
        // $red = DB::table('sal_red')->where('cod_disa', '34')->get();
        $red = DB::select("SELECT * from sal_red where id in( SELECT DISTINCT red_id from sal_microred where id in( SELECT DISTINCT microrred_id FROM `sal_establecimiento` where cod_disa=34 and categoria in ('I-1','I-2','I-3','I-4') and institucion in ('GOBIERNO REGIONAL','MINSA') and estado='ACTIVO'))");
        $mensaje = "";
        $vista = 'M';
        return view('salud.DirectorioPN.Principal', compact('vista', 'mensaje', 'red'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = DirectorioPN::orderBy('id', 'desc');
        if ($rq->vista == 'D') $query->where('estado', '0');
        if ($rq->red > 0) $query->where('red_id', $rq->red);
        if ($rq->micro > 0) $query->where('microred_id', $rq->micro);
        if ($rq->ipress > 0) $query->where('establecimiento_id', $rq->ipress);
        $query = $query->get();

        $red = EstablecimientoRepositorio::arrayIdRed();
        $micro = EstablecimientoRepositorio::arrayIdmicrored();
        $unico = EstablecimientoRepositorio::arrayIdEESS()->pluck('codigo_unico', 'id');
        $eess = EstablecimientoRepositorio::arrayIdEESS()->pluck('nombre_establecimiento', 'id');

        $data = [];
        foreach ($query as $key => $value) {
            $estado = [
                '<span class="badge badge-success" style="font-size: 0.7rem;padding: 0.25em .4em;">Activo</span>',
                '<span class="badge badge-danger"  style="font-size: 0.7rem;padding: 0.25em .4em;">Inactivo</span>'
            ];
            $btn = '';
            if ($rq->vista == 'M') {
                if ($value->estado == 0) {
                    $btn .= '<a href="javascript:void(0)" class="btn btn-xs waves-effect waves-light btn-info"    title="MODIFICAR"  onclick="edit(' . $value->id . ')"                            > <i class="fa fa-pen"></i> </a>&nbsp;';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-xs waves-effect waves-light btn-dark"    title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"   ><i class="fa fa-power-off"></i></a>&nbsp;';
                    $btn .= '<a href="javascript:void(0)" class="btn btn-xs waves-effect waves-light btn-danger"  title="ELIMINAR"   onclick="borrar(' . $value->id . ')"  > <i class="fa fa-trash"></i> </a>&nbsp;';
                } else {
                    $btn .= '<a href="javascript:void(0)" class="btn btn-xs waves-effect waves-light btn-warning" title="Activar"    onclick="estado(' . $value->id . ',' . $value->estado . ')"   ><i class="fa fa-check"></i></a> ';
                }
            } else {
                $btn .= '<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            }

            //

            if ($rq->vista == 'M') {
                $data[] = array(
                    $key + 1,
                    $red[$value->red_id] ?? '-',
                    $micro[$value->microred_id] ?? '-',
                    $unico[$value->establecimiento_id] ?? '-',
                    $eess[$value->establecimiento_id] ?? '-',
                    $value->nombres . ' ' . $value->apellido_paterno . ' ' . $value->apellido_materno,
                    $value->celular,
                    $estado[$value->estado] ?? '',
                    "<center>" . $btn  . "</center>",
                );
            } else {
                $data[] = array(
                    $key + 1,
                    $red[$value->red_id] ?? '-',
                    $micro[$value->microred_id] ?? '-',
                    $unico[$value->establecimiento_id] ?? '-',
                    $eess[$value->establecimiento_id] ?? '-',
                    $value->nombres . ' ' . $value->apellido_paterno . ' ' . $value->apellido_materno,
                    $value->cargo,
                    $value->celular,
                    "<center>" . $btn  . "</center>",
                );
            }
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

        // if ($request->profesion == '') {
        //     $data['inputerror'][] = 'profesion';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($request->cargo == '') {
            $data['inputerror'][] = 'cargo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->sexo == '0') {
            $data['inputerror'][] = 'sexo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->condicion_laboral == '') {
            $data['inputerror'][] = 'condicion_laboral';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->fred == '0') {
            $data['inputerror'][] = 'fred';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->fmicrored == '0') {
            $data['inputerror'][] = 'fmicrored';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->feess == '0') {
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
        $responsable = DirectorioPN::Create([
            'dni' => $request->dni,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'sexo' => $request->sexo,
            'profesion' => '', //$request->profesion,
            'cargo' => $request->cargo,
            'condicion_laboral' => $request->condicion_laboral,
            'red_id' => $request->fred,
            'microred_id' => $request->fmicrored,
            'establecimiento_id' => $request->feess,
            'celular' => $request->celular,
            'email' => $request->email,
        ]);

        $auditoria = DirectoriosAuditoria::Create([
            'responsable_id' => $responsable->id,
            'tipo' => 'PADRON_NOMINAL',
            'accion' => 'CREADO',
            'datos_anteriores' => null,
            'datos_nuevos' => $responsable,
            'usuario_responsable' => auth()->user()->id,
        ]);

        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $responsable = DirectorioPN::find($request->id);
        $responsable_anterior = $responsable->getOriginal();
        $responsable->dni = $request->dni;
        $responsable->nombres = $request->nombres;
        $responsable->apellido_paterno = $request->apellido_paterno;
        $responsable->apellido_materno = $request->apellido_materno;
        $responsable->sexo = $request->sexo;
        // $responsable->profesion = $request->profesion;
        $responsable->cargo = $request->cargo;
        $responsable->condicion_laboral = $request->condicion_laboral;
        $responsable->red_id = $request->fred;
        $responsable->microred_id = $request->fmicrored;
        $responsable->establecimiento_id = $request->feess;
        $responsable->celular = $request->celular;
        $responsable->email = $request->email;
        $responsable_modificado = $responsable->getDirty();
        $responsable->save();

        $auditoria = DirectoriosAuditoria::Create([
            'responsable_id' => $responsable->id,
            'tipo' => 'PADRON_NOMINAL',
            'accion' => 'MODIFICADO',
            'datos_anteriores' => $responsable_anterior,
            'datos_nuevos' => $responsable_modificado,
            'usuario_responsable' => auth()->user()->id,
        ]);
        // return response()->json(['status' => true, 'data' => $request->all(), 'obj' => $responsable]);
        return response()->json(['status' => true]);
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
        $responsable = DirectorioPN::find($id);
        $responsable_anterior = $responsable->getOriginal();
        $responsable->delete();

        $auditoria = DirectoriosAuditoria::Create([
            'responsable_id' => $responsable_anterior['id'],
            'tipo' => 'PADRON_NOMINAL',
            'accion' => 'ELIMINADO',
            'datos_anteriores' => $responsable_anterior,
            'datos_nuevos' => null,
            'usuario_responsable' => auth()->user()->id,
        ]);
        return response()->json(array('status' => true));
    }
    public function ajax_estado($id)
    {
        $responsable = DirectorioPN::find($id);
        $responsable_anterior = $responsable->getOriginal();
        $responsable->estado = $responsable->estado == '0' ? '1' : '0';
        $responsable_modificado = $responsable->getDirty();
        $responsable->save();

        $auditoria = DirectoriosAuditoria::Create([
            'responsable_id' => $responsable->id,
            'tipo' => 'PADRON_NOMINAL',
            'accion' => 'MODIFICADO',
            'datos_anteriores' => $responsable_anterior,
            'datos_nuevos' => $responsable_modificado,
            'usuario_responsable' => auth()->user()->id,
        ]);
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
