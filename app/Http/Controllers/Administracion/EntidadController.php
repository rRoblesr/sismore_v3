<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Entidad;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Models\Presupuesto\TipoGobierno;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class EntidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $formato = 0;
        return view('administracion.Entidad.Principal', compact('formato'));
    }

    public function gerencia()
    {
        $formato = 1;
        $entidades = Entidad::whereNull('dependencia')->orderBy('nombre', 'asc')->get();
        return view('administracion.Entidad.Gerencia', compact('formato', 'entidades'));
    }
    public function oficina()
    {
        $formato = 2;
        $entidades = Entidad::whereNull('dependencia')->orderBy('nombre', 'asc')->get();
        return view('administracion.Entidad.Oficina', compact('formato', 'entidades'));
    }

    public function oficina2()
    {
        $formato = 2;
        $entidades = Entidad::whereNull('dependencia')->orderBy('nombre', 'asc')->get();
        return view('administracion.Entidad.Oficina2', compact('formato', 'entidades'));
    }

    public function ListarJSON(Request $rq)
    {
        //$draw = intval($rq->draw);
        //$start = intval($rq->start);
        //$length = intval($rq->length);

        if ($rq->get('dependencia') > 0 && $rq->get('formato') != 0)
            $query = Entidad::where('dependencia', $rq->get('dependencia'))->get();
        else
            $query = Entidad::whereNull('dependencia')->get();

        $data = [];
        foreach ($query as $key => $value) {
            $btn = '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $value->id . ')" title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            if ($rq->get('formato') == 0) {
                $data[] = array(
                    $key + 1,
                    $value->nombre,
                    $value->apodo,
                    '<div style="text-align:center">' . $btn . '</div>'
                );
            } else if ($rq->get('formato') == 1) {
                $ent = Entidad::find($value->dependencia);
                $data[] = array(
                    $key + 1,
                    $ent ? $ent->nombre : '',
                    $value->nombre,
                    $value->apodo,
                    '<div style="text-align:center">' . $btn . '</div>'
                );
            } else {
                $ger = Entidad::find($value->dependencia);
                $ent = Entidad::find($ger->dependencia);
                $data[] = array(
                    $key + 1,
                    $ent->nombre,
                    $ger->nombre,
                    $value->nombre,
                    $value->apodo,
                    '<div style="text-align:center">' . $btn . '</div>'
                );
            }
        }
        $result = array(
            //"draw" => $draw,
            //"recordsTotal" => $start,
            //"recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }

    public function listarDTentidad($dependencia)
    {
        if ($dependencia > 0)
            $data = Entidad::where('dependencia', $dependencia)->get();
        else
            $data = Entidad::whereNull('dependencia')->get();

        return  datatables()::of($data)
            /* ->editColumn('grupo', function ($data) {
                if ($data->dependencia) return $data->grupo;
                else return '';
            }) */
            ->addColumn('action', function ($data) {
                $acciones = '';
                $acciones .= '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')" title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';

                return $acciones;
            })
            ->rawColumns(['action'/* , 'grupo' */])
            ->make(true);
    }

    public function listarDTgerencia($entidad_id)
    {
        $data = Entidad::where('unidadejecutadora_id', $entidad_id)->where('dependencia')->orderBy('id', 'desc')->get();
        return  datatables()::of($data)
            ->addColumn('action', function ($data) {
                $acciones = '';
                $acciones .= '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')" title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function listarDToficina($gerencia_id)
    {
        $data = Entidad::where('dependencia', $gerencia_id)->orderBy('id', 'desc')->get();
        return  datatables()::of($data)
            ->addColumn('action', function ($data) {
                $acciones = '';
                $acciones .= '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                $acciones .= '&nbsp;<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')" title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    private function _validateentidad($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        /* if ($request->entidad_codigo == '') {
            $data['inputerror'][] = 'entidad_codigo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } */

        if ($request->descripcion == '') {
            $data['inputerror'][] = 'descripcion';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->apodo == '') {
            $data['inputerror'][] = 'apodo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        return $data;
    }

    public function ajax_add_entidad(Request $request)
    {
        $val = $this->_validateentidad($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $entidad = Entidad::Create([
            'codigo' => '',
            'nombre' => $request->descripcion,
            'apodo' => $request->apodo,
            'dependencia' => $request->dependencia > 0 ? $request->dependencia : NULL,
            'estado' => '0',
        ]);
        $entidad->save();

        return response()->json(array('status' => true, 'id' => $entidad->id, 'nombre' => $entidad->nombre));
    }

    public function ajax_edit_entidad($entidad_id)
    {
        $entidad = Entidad::find($entidad_id);
        return response()->json(compact('entidad'));
    }

    public function ajax_update_entidad(Request $request)
    {
        $val = $this->_validateentidad($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $entidad = Entidad::find($request->id);
        $entidad->nombre = $request->descripcion;
        $entidad->apodo = $request->apodo;
        $entidad->dependencia = $request->dependencia > 0 ? $request->dependencia : NULL;
        $entidad->save();

        return response()->json(array('status' => true));
    }

    public function ajax_delete_entidad($entidad_id)
    {
        $entidad = Entidad::find($entidad_id);
        $entidad->delete();
        return response()->json(array('status' => true));
    }

    public function cargarEntidad(Request $rq)
    {
        if ($rq->get('dependencia') > 0)
            $entidades = Entidad::where('dependencia', $rq->get('dependencia'))->where('estado', '0')->orderBy('nombre', 'asc')->get();
        else
            $entidades = Entidad::whereNull('dependencia')->where('estado', '0')->orderBy('nombre', 'asc')->get();
        return response()->json(compact('entidades'));
    }

    public function autocompletarEntidad(Request $rq)
    {
        $term = $rq->term;
        if ($rq->dependencia > 0)
            $entidades = Entidad::where('dependencia', $rq->dependencia)->where('estado', '0')
                ->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', '%' . $term . '%')
                        ->orWhere('apodo', 'like', '%' . $term . '%');
                })->get();
        else
            $entidades = Entidad::whereNull('dependencia')->where('estado', '0')
                ->where(function ($q) use ($term) {
                    $q->where('nombre', 'like', '%' . $term . '%')
                        ->orWhere('apodo', 'like', '%' . $term . '%');
                })->get();

        if ($entidades->count() > 0) {
            foreach ($entidades as $key => $value) {
                $data[] = [
                    'label' => $value->nombre,
                    'id' => $value->id
                ];
            }
        } else {
            $data[] = [
                'label' => 'SIN REGISTROS',
                'id' => 0
            ];
        }
        return $data;
    }

    public function cargarGerencia($entidad_id)
    {
        $gerencias = Entidad::where('unidadejecutadora_id', $entidad_id)->where('dependencia')->get();
        return response()->json(compact('gerencias'));
    }

    private function _validategerencia($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if ($request->vista == '2') {
            if ($request->gerencia_entidad == '') {
                $data['inputerror'][] = 'gerencia_entidad';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
            if ($request->gerencia_nombre == '') {
                $data['inputerror'][] = 'gerencia_nombre';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
        } else {
            if ($request->gerencia == '') {
                $data['inputerror'][] = 'gerencia';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
        }

        return $data;
    }

    /* public function ajax_add_gerencia(Request $request)
    {
        $val = $this->_validategerencia($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        if ($request->vista == '2') {
            $entidad = Entidad::Create([
                'entidad' => $request->gerencia_nombre,
                'codigo' => $request->gerencia_codigo,
                'abreviado' => $request->gerencia_abreviado,
                'unidadejecutadora_id' => $request->gerencia_entidad,
                'estado' => 1,
            ]);
        } else {
            $entidad = Entidad::Create([
                'entidad' => $request->gerencia,
                'abreviado' => $request->gerencia_abreviado,
                'unidadejecutadora_id' => $request->entidad_id,
                'estado' => 1,
            ]);
        }
        return response()->json(array('status' => true, 'codigo' => $entidad->id));
    } */

    public function ajax_update_gerencia(Request $request)
    {
        $val = $this->_validategerencia($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $entidad = Entidad::find($request->gerencia_id);
        $entidad->codigo = $request->gerencia_codigo;
        $entidad->entidad = $request->gerencia_nombre;
        $entidad->unidadejecutadora_id = $request->gerencia_entidad;
        $entidad->abreviado = $request->gerencia_abreviado;
        $entidad->save();

        return response()->json(array('status' => true, 'tipo' => $entidad));
    }

    public function ajax_edit_gerencia($gerencia_id)
    {
        $gerencia = Entidad::find($gerencia_id);
        $entidad = Entidad::find($gerencia->dependencia);
        $gerencia->entidad = $entidad->id;
        return response()->json(compact('gerencia'));
    }

    public function cargarOficina($gerencia_id)
    {
        $oficinas = Entidad::where('dependencia', $gerencia_id)->get();
        return response()->json(compact('oficinas'));
    }
    public function ajax_delete_gerencia($gerencia_id)
    {
        $entidad = Entidad::find($gerencia_id);
        $entidad->delete();
        return response()->json(array('status' => true));
    }
    private function _validateoficina($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if ($request->vista == 2) {
            if ($request->oficina_gerencia == '') {
                $data['inputerror'][] = 'oficina_gerencia';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
            if ($request->oficina_nombre == '') {
                $data['inputerror'][] = 'oficina_nombre';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
        } else {
            if ($request->oficina == '') {
                $data['inputerror'][] = 'oficina';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
        }
        return $data;
    }
    public function ajax_add_oficina(Request $request)
    {
        $val = $this->_validateoficina($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }

        if ($request->vista == 2) {
            $entidad = Entidad::Create([
                'codigo' => $request->oficina_codigo,
                'entidad' => $request->oficina_nombre,
                'abreviado' => $request->oficina_abreviado,
                'unidadejecutadora_id' => $request->oficina_entidad,
                'dependencia' => $request->oficina_gerencia,
                'estado' => 1,
            ]);
        } else {
            $gerencia = Entidad::where('id', $request->gerencia_id)->first();
            $entidad = Entidad::Create([
                'entidad' => $request->oficina,
                'abreviado' => $request->oficina_abreviado,
                'unidadejecutadora_id' => $gerencia->unidadejecutadora_id,
                'dependencia' => $request->gerencia_id,
                'estado' => 1,
            ]);
        }

        return response()->json(array('status' => true, 'codigo' => $entidad->id));
    }
    public function ajax_update_oficina(Request $request)
    {
        $val = $this->_validateoficina($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $entidad = Entidad::find($request->oficina_id);
        $entidad->codigo = $request->oficina_codigo;
        $entidad->entidad = $request->oficina_nombre;
        $entidad->unidadejecutadora_id = $request->oficina_entidad;
        $entidad->abreviado = $request->oficina_abreviado;
        $entidad->save();

        return response()->json(array('status' => true, 'tipo' => $entidad));
    }
    /*  */
    public function ajax_edit_oficina($oficina_id)
    {
        $oficina = Entidad::find($oficina_id);
        $gerencia = Entidad::find($oficina->dependencia);
        $oficina->entidad = $gerencia->dependencia;
        return response()->json(compact('oficina'));
    }
    public function ajax_delete_oficina($oficina_id)
    {
        $entidad = Entidad::find($oficina_id);
        $entidad->delete();
        return response()->json(array('status' => true));
    }
}
