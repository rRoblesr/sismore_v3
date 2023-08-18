<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\RER;
use App\Models\Educacion\PadronRER;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class RERController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $mensaje = "";
        return view('educacion.RER.principal', compact('mensaje'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = RER::orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $iiees = PadronRER::where('rer_id', $value->id)->get();

            $btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            if ($value->estado == 0) {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-power-off"></i></a> ';
            } else {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-check"></i></a> ';
            }
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';

            $data[] = array(
                $key + 1,
                "<center>$value->codigo_rer</center>",
                $value->nombre,
                !$value->anio_creacion || $value->anio_creacion == 0 ? '' : "<center>$value->anio_creacion</center>",
                //!$value->anio_implementacion || $value->anio_implementacion == 0 ? '' : $value->anio_implementacion,
                //!$value->fecha_resolucion || $value->fecha_resolucion == null ? '' : date("d/m/Y", strtotime($value->fecha_resolucion)),
                "<center>$value->numero_resolucion</center>",
                '<div style="text-align:right">' . $value->presupuesto . '</div>',
                "<center>" . $iiees->count() . "</center>",
                "<center>" . $btn1 . $btn4 . $btn2  . $btn3 . "</center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }



    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->codigo_rer == '') {
            $data['inputerror'][] = 'codigo_rer';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else {
            $rer = RER::where('codigo_rer', $request->codigo_rer)->get();
            if ($rer->count() > 0 && $request->id == '') {
                $data['inputerror'][] = 'codigo_rer';
                $data['error_string'][] = 'Este Código Ya Existe.';
                $data['status'] = FALSE;
            }
        }

        if ($request->nombre == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else {
            $rer = RER::where('nombre', $request->nombre)->get();
            if ($rer->count() > 0 && $request->id == '') {
                $data['inputerror'][] = 'nombre';
                $data['error_string'][] = 'Nombre Ya Existe.';
                $data['status'] = FALSE;
            }
        }

        if ($request->anio_creacion == '') {
            $data['inputerror'][] = 'anio_creacion';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->anio_implementacion == '') {
            $data['inputerror'][] = 'anio_implementacion';
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
        $rer = RER::Create([
            'codigo_rer' => $request->codigo_rer,
            'nombre' => $request->nombre,
            'anio_creacion' => $request->anio_creacion,
            'anio_implementacion' => $request->anio_implementacion,
            'fecha_resolucion' => $request->fecha_resolucion,
            'numero_resolucion' => $request->numero_resolucion,
            'presupuesto' => $request->presupuesto,
            'ambito' => $request->ambito,
            'estado' => 0,
        ]);
        return response()->json(array('status' => true));
    }
    public function ajax_update(Request $request)
    {
        $val = $this->_validate($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $rer = RER::find($request->id);
        $rer->codigo_rer = $request->codigo_rer;
        $rer->nombre = $request->nombre;
        $rer->anio_creacion = $request->anio_creacion;
        $rer->anio_implementacion = $request->anio_implementacion;
        $rer->fecha_resolucion = $request->fecha_resolucion;
        $rer->numero_resolucion = $request->numero_resolucion;
        //$rer->presupuesto = $request->presupuesto;
        $rer->save();

        return response()->json(array('status' => true));
    }
    public function ajax_edit($id)
    {
        $rer = RER::find($id);
        return response()->json(compact('rer'));
    }
    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = RER::find($id);
        $rer->delete();
        return response()->json(array('status' => true, 'rer' => $rer));
    }
    public function ajax_estado($id)
    {
        $rer = RER::find($id);
        $rer->estado = $rer->estado == 1 ? 0 : 1;
        $rer->save();
        return response()->json(array('status' => true, 'estado' => $rer->estado));
    }

    public function completarred(Request $rq)
    {
        $term = $rq->get('term');
        $query = RER::where(DB::raw("concat(' ',codigo_rer,nombre)"), 'like', "%$term%")->where('estado', 0)->orderBy('nombre', 'asc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                "label" => $value->codigo_rer . ' | ' . $value->nombre,
                "id" => $value->id
            ];
        }
        return $data; //response()->json('data');
    }

    public function ajax_cargar(Request $rq)
    {
        $nivel = $rq->get('nivel');
        $ugel = $rq->get('ugel');
        $rer = RER::select('edu_rer.id', 'edu_rer.nombre')
            ->join('edu_padron_rer as v2', 'v2.rer_id', '=', 'edu_rer.id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v2.institucioneducativa_id');
        if ($nivel != 0)
            $rer = $rer->where('v3.NivelModalidad_id', $nivel);
        if ($ugel != 0)
            $rer = $rer->where('v3.Ugel_id', $ugel);
        $rer = $rer->distinct();
        $rer = $rer->orderBy('nombre', 'asc');
        $rer = $rer->get();
        return response()->json(compact('rer'));
    }
}
