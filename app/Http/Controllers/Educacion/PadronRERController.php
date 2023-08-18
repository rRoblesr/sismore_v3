<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\RER;
use App\Models\Educacion\PadronRER;
use App\Models\Educacion\Ugel;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Educacion\PlazaRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/* serapadronRER */

class PadronRERController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $ugels = Ugel::select('id', 'codigo', 'nombre')->where('dependencia', '>', '0')->get();
        $mensaje = "";
        return view('educacion.PadronRER.Principal', compact('mensaje', 'ugels'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $ugel = $rq->fugel;
        $rer = $rq->frer;
        $nivel = $rq->fnivel;

        $query = PadronRER::select(
            'edu_padron_rer.*',
            'v2.codigo_rer as modularrer',
            'v2.nombre as rer',
            'v3.codModular as modulariiee',
            'v3.nombreInstEduc as iiee',
            'v4.nombre as nivel',
            'v5.nombre as ugel',
        )
            ->join('edu_rer as v2', 'v2.id', '=', 'edu_padron_rer.rer_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_rer.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v4', 'v4.id', '=', 'v3.NivelModalidad_id')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v3.Ugel_id')
            ->orderBy('edu_padron_rer.id', 'desc');
        if ($ugel > 0)
            $query = $query->where('v5.id', $ugel);
        if ($rer > 0)
            $query = $query->where('v2.id', $rer);
        if ($nivel > 0)
            $query = $query->where('v3.NivelModalidad_id', $nivel);
        $query = $query->get();
        $data = [];
        foreach ($query as $key => $value) {

            $btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';

            $data[] = array(
                $key + 1,
                $value->ugel,
                $value->rer,
                $value->modulariiee,
                $value->iiee,
                $value->nivel,

                $value->tipo_transporte,
                $btn1 . $btn4 . $btn3,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "ugel" => $ugel,
            "rer" => $rer,
        );
        return response()->json($result);
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;



        if ($request->rer == '') {
            $data['inputerror'][] = 'rer';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->rer_id == '') {
            $data['inputerror'][] = 'rer';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->iiee == '') {
            $data['inputerror'][] = 'iiee';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if ($request->iiee_id == '') {
            $data['inputerror'][] = 'iiee';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else { //'rer_id' => $request->rer_id,
            $iiee = PadronRER::where(['institucioneducativa_id' => $request->iiee_id])->get();
            if ($iiee->count() > 0 && $request->id == '') {
                $data['inputerror'][] = 'iiee';
                $data['error_string'][] = 'IE ya está en uso.';
                $data['status'] = FALSE;
            }
        }

        if ($request->estudiantes == '') {
            $data['inputerror'][] = 'estudiantes';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->docentes == '') {
            $data['inputerror'][] = 'docentes';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->administrativos == '') {
            $data['inputerror'][] = 'administrativos';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->transporte == '') {
            $data['inputerror'][] = 'transporte';
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
        $rer = PadronRER::Create([
            'rer_id' => $request->rer_id,
            'institucioneducativa_id' => $request->iiee_id,
            'total_estudiantes' => $request->estudiantes,
            'total_docentes' => $request->docentes,
            'total_administrativo' => $request->administrativos,
            'tiempo_tras_rer' => $request->tiempo1,
            'tiempo_tras_rer_ugel' => $request->tiempo2,
            'tipo_transporte' => $request->transporte,
        ]);
        return response()->json(array('status' => true, 'data' => $rer, 'rer' => $request->rer_id, 'iiee' => $request->iiee_id));
    }
    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $rer = PadronRER::find($request->id);
        $rer->rer_id = $request->rer_id;
        $rer->institucioneducativa_id = $request->iiee_id;
        $rer->total_estudiantes = $request->estudiantes;
        $rer->total_docentes = $request->docentes;
        $rer->total_administrativo = $request->administrativos;
        $rer->tiempo_tras_rer = $request->tiempo1;
        $rer->tiempo_tras_rer_ugel = $request->tiempo2;
        $rer->tipo_transporte = $request->transporte;
        $rer->save();

        return response()->json(array('status' => true, 'data' => $rer));
    }
    public function ajax_edit($id)
    {
        $padronRER = PadronRER::find($id);
        $rer = RER::find($padronRER->rer_id);
        $padronRER->red = $rer->codigo_rer . ' | ' . $rer->nombre;
        $iiee = InstitucionEducativa::find($padronRER->institucioneducativa_id);
        $padronRER->iiee = $iiee->codModular . ' | ' . $iiee->nombreInstEduc;
        return response()->json(compact('padronRER', 'iiee'));
    }
    public function ajax_delete($id)
    {
        $rer = PadronRER::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }
    public function ajax_estado($id)
    {
        $rer = PadronRER::find($id);
        $rer->estado = $rer->estado == 1 ? 0 : 1;
        $rer->save();
        return response()->json(array('status' => true));
    }

    public function avance()
    {
        $mensaje = "";
        $data['rer'] = RER::where('estado', 0)->count();
        $data['pres'] = RER::select(DB::raw('sum(presupuesto) as vv'))->first()->vv;
        $data['iiee'] = PadronRER::all()->count();
        $data['alumnos'] = MatriculaRepositorio::conteo_alumnos_rer(); // PadronRER::select(DB::raw('sum(total_estudiantes) as vv'))->first()->vv;
        $data['docentes'] =PlazaRepositorio::conteo_docentes_rer();// PadronRER::select(DB::raw('sum(total_docentes) as vv'))->first()->vv;
        //return $data;
        return view('educacion.PadronRER.Avance', compact('mensaje', 'data'));
    }

    public function grafica1()
    {
        $info = DB::table(DB::raw('(select distinct
                                        v4.nombre as name,
                                        v2.codigo_rer as codigo
                                    from edu_padron_rer v1
                                    inner join edu_rer as v2 on v2.id=v1.rer_id
                                    inner join edu_institucioneducativa as v3 on v3.id=v1.institucioneducativa_id
                                    inner join edu_ugel as v4 on v4.id=v3.Ugel_id) as tx'))
            ->select('name', DB::raw('count(codigo) as y'))
            ->groupBy('name')->orderBy('y', 'desc')
            ->get();
        return response()->json(compact('info'));
    }

    public function grafica2()
    {
        $info = PadronRER::select('v4.nombre as name', DB::raw('count(v2.codigo_rer) as y'))
            ->join('edu_rer as v2', 'v2.id', '=', 'edu_padron_rer.rer_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_rer.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v4', 'v4.id', '=', 'v3.NivelModalidad_id')
            ->groupBy('name')->orderBy('y', 'desc')
            ->get();
        return response()->json(compact('info'));
    }

    public function ajax_cargarnivel(Request $rq)
    {
        $ugel = $rq->ugel; //->get('ugel');
        $rer = PadronRER::select(
            'v4.id',
            'v4.nombre',
        )
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'edu_padron_rer.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v4', 'v4.id', '=', 'v3.NivelModalidad_id')
            ->orderBy('v4.nombre', 'asc');
        $rer = $rer->where('v3.Ugel_id', $ugel);
        $rer = $rer->distinct()->get();
        return response()->json(compact('rer'));
    }
}
