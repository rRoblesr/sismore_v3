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
use App\Repositories\Educacion\CuboPadronEIBRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\InstitucionEducativaRepositorio;
use App\Repositories\Educacion\NexusRepositorio;
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

    public function cargargestion($anio)
    {
        $anioeib = PadronEIBRepositorio::getYearMapping($anio);
        return PadronEIBRepositorio::gestion_select($anio, $anioeib);
    }

    public function cargarprovincia($anio)
    {
        $anioeib = PadronEIBRepositorio::getYearMapping($anio);
        return PadronEIBRepositorio::provincia_select($anio, $anioeib);
    }

    public function cargardistrito($anio, $provincia)
    {
        $anioeib = PadronEIBRepositorio::getYearMapping($anio);
        return PadronEIBRepositorio::distrito_select($anio, $anioeib, $provincia);
    }

    public function reportes()
    {
        // return PadronEIBRepositorio::gestion_select(2025,2024);
        //return CuboPadronEIBRepositorio::select_anios();
        // $fuenteId = ImporMatriculaGeneralController::$FUENTE;
        // $anios = PadronEIBRepositorio::rango_anios_segun_eib();
        $anios =  CuboPadronEIBRepositorio::select_anios();
        $aniomax = $anios->max();
        $anioeib = PadronEIBRepositorio::getYearMapping($aniomax);
        return view('educacion.EIB.Reportes', compact('anios', 'aniomax', 'anioeib'));
    }

    public function reportesreporte(Request $rq)
    {
        $div = $rq->div;
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE);
        switch ($rq->div) {
            case 'head':
                $data = CuboPadronEIBRepositorio::reportesreporte_head($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $card1 = number_format($data->servicios, 0);
                $card2 = number_format($data->lengua, 0);
                $card3 = number_format($data->matriculados, 0);
                $card4 = number_format($data->docentes, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $pe_pv = [
                    '2501' => 'pe-uc-cp',
                    '2502' => 'pe-uc-at',
                    '2503' => 'pe-uc-pa',
                    '2504' => 'pe-uc-pr',
                ];
                $info = [];
                $valores = [];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal1($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $total = $data->sum('conteo');
                foreach ($data as $key => $value) {
                    $info[] = [$value->codigo, round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                    $valores[$value->codigo] = ['num' => (int)$value->conteo, 'dem' => $total, 'ind' => round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                }
                return response()->json(compact('info', 'valores', 'data'));

            case 'anal2':
                $info = [
                    'categoria' => [],
                    'series' => [
                        ['type' => 'column', 'yAxis' => 0, 'data' => [], 'name' => 'Matriculados'],
                        ['type' => 'spline', 'yAxis' => 1, 'data' => [], 'name' => '%Avance'],
                    ],
                    'maxbar' => 0,
                ];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal2($rq->gestion, $rq->provincia, $rq->distrito);
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->anio;
                    $info['series'][0]['data'][] = (int)$value->matriculados;
                    $info['series'][1]['data'][] = $key == 0 ? 0 : round($data[$key - 1]->matriculados > 0 ? 100 * $value->matriculados / $data[$key - 1]->matriculados : 0, 1);
                    $info['maxbar'] = max($info['maxbar'], (int)$value->matriculados);
                }
                return response()->json(compact('info', 'data'));

            case 'anal3':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal3($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                foreach ($data as $key => $value) {
                    $value->y = (int)$value->y;
                    $value->color = $color[$key % count($color)];
                }
                return response()->json($data);

            case 'anal4':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $info = [
                    'cat' => [],
                    'dat' => [
                        ['name' => 'inicial',    'data' => [], 'color' => $color[0]],
                        ['name' => 'primaria',   'data' => [], 'color' => $color[1]],
                        ['name' => 'secundaria', 'data' => [], 'color' => $color[2]],
                    ]
                ];
                $data = CuboPadronEIBRepositorio::reportesreporte_anal4($rq->gestion, $rq->provincia, $rq->distrito);
                foreach ($data->unique('anio') as $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data as $value) {
                    switch (strtolower($value->name)) {
                        case 'inicial':
                            $info['dat'][0]['data'][] = (int)$value->y;
                            break;
                        case 'primaria':
                            $info['dat'][1]['data'][] = (int)$value->y;
                            break;
                        case 'secundaria':
                            $info['dat'][2]['data'][] = (int)$value->y;
                            break;
                    }
                }
                return response()->json($info);
            case 'anal5':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data2 = CuboPadronEIBRepositorio::reportesreporte_anal5($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $data = [
                    ["name" => "CONTRATADO", "y" => (int)$data2->contratado, "color" => "#43beac"],
                    ["name" => "NOMBRADO", "y" => (int)$data2->nombrado, "color" => "#ffc107"]
                ];
                return response()->json($data);
            case 'anal6':
                $data = CuboPadronEIBRepositorio::reportesreporte_anal6($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $niveles = ['INICIAL', 'PRIMARIA', 'SECUNDARIA'];
                $series = [
                    'NOMBRADOS'  => ['name' => 'NOMBRADOS',  'color' => '#43beac', 'data' => array_fill(0, count($niveles), 0)],
                    'CONTRATADOS' => ['name' => 'CONTRATADOS', 'color' => '#ffc107', 'data' => array_fill(0, count($niveles), 0)],
                ];
                foreach ($data as $key => $value) {
                    $nivel = $value->NIVEL;
                    $nombrados = (int) $value->NOMBRADOS;
                    $contratados = (int) $value->CONTRATADOS;

                    // Buscar índice del nivel en el array definido
                    $idx = array_search($nivel, $niveles);
                    if ($idx !== false) {
                        $series['NOMBRADOS']['data'][$idx] = $nombrados;
                        $series['CONTRATADOS']['data'][$idx] = $contratados;
                    }
                }
                $response = [
                    'categories' => $niveles,
                    'series' => array_values($series), // convierte a array indexado: [serie1, serie2]
                    'xx' => $data,
                ];

                return response()->json($response);

            case 'tabla1':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla1($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->forma_atencion = 'TOTAL';
                    // Servicios educativos
                    $foot->ts = $base->sum('ts');
                    $foot->tsr = $base->sum('tsr');
                    $foot->tsu = $base->sum('tsu');
                    // Estudiantes matriculados
                    $foot->tm = $base->sum('tm');
                    $foot->tmr = $base->sum('tmr');
                    $foot->tmu = $base->sum('tmu');
                    // Personal docente
                    $foot->td = $base->sum('td');
                    $foot->tdr = $base->sum('tdr');
                    $foot->tdu = $base->sum('tdu');
                    // Auxiliar de educación
                    $foot->ta = $base->sum('ta');
                    $foot->tar = $base->sum('tar');
                    $foot->tau = $base->sum('tau');
                    // PEC
                    $foot->tp = $base->sum('tp');
                    $foot->tpr = $base->sum('tpr');
                    $foot->tpu = $base->sum('tpu');
                }
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla2':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla2($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->nivel_modalidad = 'TOTAL';
                    // Servicios educativos
                    $foot->ts = $base->sum('ts');
                    $foot->tsr = $base->sum('tsr');
                    $foot->tsu = $base->sum('tsu');
                    // Estudiantes matriculados
                    $foot->tm = $base->sum('tm');
                    $foot->tmr = $base->sum('tmr');
                    $foot->tmu = $base->sum('tmu');
                    // Personal docente
                    $foot->td = $base->sum('td');
                    $foot->tdr = $base->sum('tdr');
                    $foot->tdu = $base->sum('tdu');
                    // Auxiliar de educación
                    $foot->ta = $base->sum('ta');
                    $foot->tar = $base->sum('tar');
                    $foot->tau = $base->sum('tau');
                    // PEC
                    $foot->tp = $base->sum('tp');
                    $foot->tpr = $base->sum('tpr');
                    $foot->tpu = $base->sum('tpu');
                }
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla3':
                $base = CuboPadronEIBRepositorio::reportesreporte_tabla3($rq->anio, 0, $rq->gestion, $rq->provincia, $rq->distrito);
                $foot = [];
                if ($base->isNotEmpty()) {
                    $foot = clone $base->first();
                    $foot->lengua = 'TOTAL';
                    // Servicios educativos
                    $foot->ts = $base->sum('ts');
                    $foot->tsr = $base->sum('tsr');
                    $foot->tsu = $base->sum('tsu');
                    // Estudiantes matriculados
                    $foot->tm = $base->sum('tm');
                    $foot->tmr = $base->sum('tmr');
                    $foot->tmu = $base->sum('tmu');
                    // Personal docente
                    $foot->td = $base->sum('td');
                    $foot->tdr = $base->sum('tdr');
                    $foot->tdu = $base->sum('tdu');
                    // Auxiliar de educación
                    $foot->ta = $base->sum('ta');
                    $foot->tar = $base->sum('tar');
                    $foot->tau = $base->sum('tau');
                    // PEC
                    $foot->tp = $base->sum('tp');
                    $foot->tpr = $base->sum('tpr');
                    $foot->tpu = $base->sum('tpu');
                }
                $excel = view('educacion.EIB.ReportesTablas', compact('div', 'base', 'foot'))->render();
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
