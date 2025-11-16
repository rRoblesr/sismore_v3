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

    public function reportes()
    {
        $fuenteId = ImporMatriculaGeneralController::$FUENTE;
        $anios = PadronEIBRepositorio::rango_anios_segun_eib();
        $aniomax = max($anios);
        return view('educacion.EIB.Reportes', compact('anios', 'aniomax'));
    }

    public function reportesreporte(Request $rq)
    {
        $div = $rq->div;
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE);
        switch ($rq->div) {
            case 'head':
                $data2025 = NexusRepositorio::reportesreporte_head($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $data2024 = NexusRepositorio::reportesreporte_head($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $card1 = number_format($data2025->docentes, 0);
                $card2 = number_format($data2025->auxiliar, 0);
                $card3 = number_format($data2025->promotor, 0);
                $card4 = number_format($data2025->administrativo, 0);
                $pcard1 = round($data2024->docentes > 0 ? (100 * $data2025->docentes / $data2024->docentes) : 0, 1);
                $pcard2 = round($data2024->auxiliar > 0 ? (100 * $data2025->auxiliar / $data2024->auxiliar) : 0, 1);
                $pcard3 = round($data2024->promotor > 0 ? (100 * $data2025->promotor / $data2024->promotor) : 0, 1);
                $pcard4 = round($data2024->administrativo > 0 ? (100 * $data2025->administrativo / $data2024->administrativo) : 0, 1);
                return response()->json(compact('card1', 'card2', 'card3', 'card4', 'pcard1', 'pcard2', 'pcard3', 'pcard4'));
            case 'anal1':
                $pe_pv = [
                    '2501' => 'pe-uc-cp',
                    '2502' => 'pe-uc-at',
                    '2503' => 'pe-uc-pa',
                    '2504' => 'pe-uc-pr',
                ];
                $info = [];
                $valores = [];
                $data = NexusRepositorio::reportesreporte_anal1($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $total = $data->sum('conteo');
                foreach ($data as $key => $value) {
                    $info[] = [$value->codigo, round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                    $valores[$value->codigo] = ['num' => (int)$value->conteo, 'dem' => $total, 'ind' => round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                }
                return response()->json(compact('info', 'valores', 'data'));


            case 'anal2':
                $data = NexusRepositorio::reportesreporte_anal2($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $info = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->mes;
                    $info['data'][] = $value->conteo;
                }
                // $data = CuboPacto2Repositorio::reportesreporte_anal2($rq->ugel, $rq->modalidad, $rq->nivel);
                // $info = $data->map(function ($y, $name) {
                //     return ['name' => $name, 'y' => (int)$y];
                // })->values();
                return response()->json(compact('info', 'data'));
                break;
            case 'anal3':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data = NexusRepositorio::reportesreporte_anal3($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                foreach ($data as $key => $value) {
                    $value->color = $color[$key % count($color)];
                }
                return response()->json($data);

            case 'anal4':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data = NexusRepositorio::reportesreporte_anal4($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                foreach ($data as $key => $value) {
                    $value->color = $color[$key % count($color)];
                }
                return response()->json($data);

            case 'tabla1':
                $base = NexusRepositorio::reportesreporte_tabla01($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ugel = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                    $foot->tpc = $base->sum('tpc');
                }
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla2':
                $base = NexusRepositorio::reportesreporte_tabla02($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ley = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                    $foot->tpc = $base->sum('tpc');
                }
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla3':
                $base = NexusRepositorio::reportesreporte_tabla03($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->distrito = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                    $foot->tpc = $base->sum('tpc');
                }
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla4':
                $base = NexusRepositorio::reportesreporte_tabla04($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base'))->render();
                return response()->json(compact('excel', 'base'));
                // return response()->json(compact('div', 'base', 'foot'));
            default:
                # code...
                return [];
        }
    }
}
