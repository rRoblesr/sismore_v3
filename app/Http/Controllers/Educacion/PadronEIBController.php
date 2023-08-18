<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use Illuminate\Http\Request;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\PadronEIB;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Lengua;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\InstitucionEducativaRepositorio;
use App\Repositories\Educacion\PadronEIBRepositorio;
use Illuminate\Support\Facades\DB;

class PadronEIBController extends Controller
{
    public $fuente = 12;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {

        $ugels = Ugel::select('id', 'codigo', 'nombre')->where('dependencia', '>', '0')->get();
        $mensaje = "";
        $lenguas = Lengua::where('estado', 0)->get();
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(12);
        $anios = PadronEIB::distinct()->select('v2.*')->join('par_anio as v2', 'v2.id', '=', 'edu_padron_eib.anio_id')->get();
        //return Importacion::where('fuenteImportacion_id', $this->fuente)->where('estado', 'PR')->orderBy('fechaActualizacion','desc')->first();
        //return Importacion::all();

        return view('educacion.PadronEIB.Principal', compact('mensaje', 'lenguas', 'ugels', 'imp', 'anios'));
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = PadronEIBRepositorio::listaImportada2($rq->get('anio'), $rq->get('ugel'), $rq->get('nivel'));
        $data = [];
        foreach ($query as $key => $value) {
            //$btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            //$btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                '<div style="text-align:center">' . $value->anio . '</div>',
                $value->ugel,
                $value->cod_mod,
                $value->institucion_educativa,
                $value->nivel_modalidad,
                $value->forma_atencion,
                $value->lengua1,
                '<div style="text-align:center">' . $btn4 . '</div>'
                //$btn1  . $btn4 . $btn3,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            //"ugel" => $rq->get('ugel'),
            //"nivel" => $rq->get('nivel'),
        );
        return response()->json($result);
    }
    /*
    private function _validate_ajaxopt1($rq)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;


        if ($rq->idiiee_padronweb == '') {
            $data['inputerror'][] = 'codigomodular_padronweb';
            $data['error_string'][] = '<br>Este campo es obligatorio buscar.';
            $data['status'] = FALSE;
        }

        if ($rq->estado_padronweb == 'SI') {
            $data['inputerror'][] = 'codigomodular_padronweb';
            $data['error_string'][] = '<br> Servicio Educativo Registrado en el Padron EIB.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
    public function ajax_add_opt1(Request $rq)
    {
        $this->_validate_ajaxopt1($rq);
        $query = DB::table('edu_padron_eib as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->orderBy('v2.fechaActualizacion', 'desc')
            ->take(1)
            ->select('v2.id', 'v1.anio_id as ano')
            ->get();
        $data = [
            'importacion_id' => $query->first()->id,
            'anio_id' => $query->first()->ano,
            'institucioneducativa_id' => $rq->idiiee_padronweb,
            'forma_atencion' => $rq->formaatencion_padronweb,
            'cod_lengua' => $rq->codigolengua_padronweb,
            'lengua_uno' => $rq->lenguauno_padronweb,
            'lengua_dos' => $rq->lenguados_padronweb,
            'lengua_3' => $rq->lengua3_padronweb,
        ];
        //return response()->json(['status' => TRUE, 'info' => $data]);
        $eib = PadronEIB::Create($data);
        if ($eib) {
            $iiee = InstitucionEducativa::find($rq->idiiee_padronweb);
            $iiee->es_eib = 'SI';
            $iiee->save();
        }
        return response()->json(['status' => TRUE, 'info' => $eib]);
    }

    public function ajax_delete_opt1($idpadroneib)
    {
        $eib = PadronEIB::find($idpadroneib);
        if ($eib) {
            $iiee = InstitucionEducativa::find($eib->institucioneducativa_id);
            $iiee->es_eib = null;
            $iiee->save();
        }
        $eib->delete();
        return response()->json(['status' => TRUE, 'eib' => $eib]);
    } */

    /*  */
    /*  */
    /*  */

    private function _validate($rq)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;


        if ($rq->iiee_id == '') {
            $data['inputerror'][] = 'iiee';
            $data['error_string'][] = 'Este campo es obligatorio buscar.';
            $data['status'] = FALSE;
        }

        if ($rq->id == '' && $rq->estado == 'SI') {
            $data['inputerror'][] = 'iiee';
            $data['error_string'][] = 'Servicio Educativo Ya Registrado.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add(Request $rq)
    {
        $this->_validate($rq);
        $data = [
            'importacion_id' => 518,
            'anio_id' => 8,
            'institucioneducativa_id' => $rq->iiee_id,
            'forma_atencion' => $rq->formaatencion,
            //'cod_lengua' => $rq->codigolengua,
            'lengua1_id' => $rq->lengua1,
            'lengua2_id' => $rq->lengua2,
            'lengua3_id' => $rq->lengua3,
        ];
        $eib = PadronEIB::Create($data);
        if ($eib) {
            $iiee = InstitucionEducativa::find($rq->iiee_id);
            $iiee->es_eib = 'SI';
            $iiee->save();
        }
        return response()->json(array('status' => true));
    }

    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $eib = PadronEIB::find($request->id);
        $eib->institucioneducativa_id = $request->iiee_id;
        $eib->forma_atencion = $request->formaatencion;
        //$eib->cod_lengua = $request->codigolengua;
        $eib->lengua1_id = $request->lengua1;
        $eib->lengua2_id = $request->lengua2;
        $eib->lengua3_id = $request->lengua3;
        $eib->save();

        return response()->json(array('status' => true));
    }
    public function ajax_edit($id)
    {
        $eib = PadronEIB::find($id);
        $iiee = InstitucionEducativaRepositorio::buscariiee_id($eib->institucioneducativa_id);
        if ($iiee->count() > 0) {
            $eib->provincia = $iiee->first()->provincia;
            $eib->distrito = $iiee->first()->distrito;
            $eib->centro_poblado = $iiee->first()->centro_poblado;
            $eib->codigo_local = $iiee->first()->codigo_local;
            $eib->iiee = $iiee->first()->iiee;
            $eib->codigo_nivel = $iiee->first()->codigo_nivel;
            $eib->nivel_modalidad = $iiee->first()->nivel_modalidad;
            $eib->estado = $iiee->first()->estado;
            $eib->ugel = $iiee->first()->ugel;
            $eib->label = $iiee->first()->codigo_modular . ' | ' . $iiee->first()->iiee;
        }
        //ib->lengua1=Lengua::find($eib->lengua1_id)->id;
        return response()->json(compact('eib'));
    }
    public function ajax_delete($id)
    {
        $eib = PadronEIB::find($id);
        if ($eib) {
            $iiee = InstitucionEducativa::find($eib->institucioneducativa_id);
            $iiee->es_eib = null;
            $iiee->save();
        }
        $eib->delete();
        return response()->json(array('status' => true));
    }
}
