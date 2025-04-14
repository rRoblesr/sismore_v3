<?php

namespace App\Http\Controllers\Parametro;

use App\Exports\Salud\AgregarMetasExport;
use App\Http\Controllers\Controller;
use App\Imports\Salud\AgregarMetasImport;
use App\Models\Administracion\Entidad;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Ubigeo;
use App\Models\Presupuesto\Sector;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class IndicadorGeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        return view('parametro.IndicadorGeneral.Principal');
    }

    public function xxx()
    {
        return view('prueba.nav-tabs');
    }

    public function principalEducacion()
    {
        $control = session('perfil_administrador_id');
        $sistema = session('sistema_id');

        $instrumento = IndicadorGeneralRepositorio::instrumento(0);
        $tipo = IndicadorGeneralRepositorio::tipo(0);
        $dimension = IndicadorGeneralRepositorio::dimension();
        $unidad = IndicadorGeneralRepositorio::unidad();
        $frecuencia = IndicadorGeneralRepositorio::frecuencia();

        if ($control == 3) {
            $sector = Sector::all();
        } else {
            switch ($sistema) {
                case 1:
                    $sector = Sector::where('id', 4)->get();
                    break;
                case 3:
                    $sector = Sector::where('id', 14)->get();
                    break;
                default:
                    break;
            }
        }
        //$provincia=Ubigeo::where(DB::raw('length(codigo)'),4)->where('dependencia',34)->get();
        // $provincia = Ubigeo::join('par_ubigeo as p', 'p.dependencia', '=', 'par_ubigeo.id')->select('p.id', 'p.codigo', 'p.nombre')->where('par_ubigeo.codigo', '25')->get();

        return view('parametro.IndicadorGeneral.PrincipalEducacion', compact('instrumento', 'tipo', 'dimension', 'unidad', 'frecuencia', 'sector'));
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $control = session('perfil_administrador_id');
        $sistema = session('sistema_id');
        if ($control == 3 || $control == 8) {
            $query = IndicadorGeneral::select('id', 'codigo', 'nombre', 'descripcion', 'instrumento_id', 'tipo_id', 'dimension_id', 'unidad_id', 'frecuencia_id', 'fuente_dato', 'anio_base', 'valor_base', 'sector_id', 'oficina_id', 'estado')
                ->orderBy('id', 'desc')->get();
        } else {
            switch ($sistema) {
                case 1:
                    $query = IndicadorGeneral::select('id', 'codigo', 'nombre', 'descripcion', 'instrumento_id', 'tipo_id', 'dimension_id', 'unidad_id', 'frecuencia_id', 'fuente_dato', 'anio_base', 'valor_base', 'sector_id', 'oficina_id', 'estado')
                        ->where('sector_id', 4)
                        ->orderBy('id', 'desc')->get();
                    break;
                case 3:
                    $query = IndicadorGeneral::select('id', 'codigo', 'nombre', 'descripcion', 'instrumento_id', 'tipo_id', 'dimension_id', 'unidad_id', 'frecuencia_id', 'fuente_dato', 'anio_base', 'valor_base', 'sector_id', 'oficina_id', 'estado')
                        ->where('sector_id', 14)
                        ->orderBy('id', 'desc')->get();
                    break;
                default:
                    break;
            }
        }


        $data = [];
        foreach ($query as $key => $value) {
            $sector = Sector::find($value->sector_id);
            $tipo = DB::table('par_tipo')->where('id', $value->tipo_id)->first();
            $instrumento = DB::table('par_instrumento')->where('id', $value->instrumento_id)->first();

            $btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn2 = '';
            if (auth()->user()->id == 49) {
                if ($value->estado == 0) {
                    $btn2 = '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $btn2 = '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-check"></i></a> ';
                }
            }
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="verpdf(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fas fa-file"></i> </button>';
            switch ($instrumento->abreviado) {
                case 'DIT':
                    $btn5 = '&nbsp;<a href="#" class="btn btn-success btn-xs" onclick="metas_dit(' . $value->id . ')"  title="Agregar Metas"> <i class="fa fa-plus"></i> </a>';
                    break;

                case 'FED':
                    $btn5 = '&nbsp;<a href="#" class="btn btn-success btn-xs" onclick="metas_fed(' . $value->id . ')"  title="Agregar Metas"> <i class="fa fa-plus"></i> </a>';
                    break;

                default:
                    $btn5 = '&nbsp;<a href="#" class="btn btn-success btn-xs" onclick="metas(' . $value->id . ')"  title="Agregar Metas"> <i class="fa fa-plus"></i> </a>';
                    break;
            }


            $data[] = array(
                $key + 1,
                '<div style="text-align:center"><span class="badge" style="color:#797979;">' . $value->codigo . '</span></div>',
                $value->nombre,
                $sector->nombre,
                '<div style="text-align:center">' . $tipo->nombre . '</div>',
                '<div style="text-align:center" title="' . $instrumento->nombre . '">' . $instrumento->abreviado . '</div>',
                '<div style="text-align:center">' . ($value->anio_base != 0 ? $value->anio_base : '----') . '</div>',
                "<center><div class='btn-group'>" . $btn1 . $btn2 .  $btn4  . ($instrumento->meta == 'SI' ? $btn5 : '')  . $btn3 . "</div></center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "data2" => session()->all(),
        );
        return response()->json($result);
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
        } else {
            $otros = IndicadorGeneral::where('nombre', $request->nombre)->count();
            if ($otros > 0 && $request->id == '') {
                $data['inputerror'][] = 'nombre';
                $data['error_string'][] = 'Este Código Ya Existe.';
                $data['status'] = FALSE;
            }
        }

        if ($request->descripcion == '') {
            $data['inputerror'][] = 'descripcion';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->instrumento == '') {
            $data['inputerror'][] = 'instrumento';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->numerador == '') {
            $data['inputerror'][] = 'numerador';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->codigo == '') {
            $data['inputerror'][] = 'codigo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }/* else if ($request->codigoconteo == 0) {
            $data['inputerror'][] = 'codigo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } /*else if (strlen($request->codigo) != $request->codigoconteo) {
            $data['inputerror'][] = 'codigo';
            $data['error_string'][] = 'Este campo esta incompleto';
            $data['status'] = FALSE;
        }*/

        // if ($request->denominador == '') {
        //     $data['inputerror'][] = 'denominador';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($request->tipo == '') {
            $data['inputerror'][] = 'tipo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        // if ($request->dimension == '') {
        //     $data['inputerror'][] = 'dimension';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($request->unidad == '') {
            $data['inputerror'][] = 'unidad';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->frecuencia == '') {
            $data['inputerror'][] = 'frecuencia';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->fuentedato == '') {
            $data['inputerror'][] = 'fuentedato';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->aniobase < 1) {
            $data['inputerror'][] = 'aniobase';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->valorbase == '') {
            $data['inputerror'][] = 'valorbase';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->sector == '') {
            $data['inputerror'][] = 'sector';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->oficina == '') {
            $data['inputerror'][] = 'oficina';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        $file = $request->file('fichatecnica');
        if ($request->id == '') {
            if ($file) {
                $name = $file->getClientOriginalExtension();
                if ($name != 'pdf') {
                    $data['inputerror'][] = 'fichatecnica';
                    $data['error_string'][] = 'Solo archivos PDF.';
                    $data['status'] = FALSE;
                }
            } else {
                $data['inputerror'][] = 'fichatecnica';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
        } else if ($file && $request->id != '') {
            $name = $file->getClientOriginalExtension();
            if ($name != 'pdf') {
                $data['inputerror'][] = 'fichatecnica';
                $data['error_string'][] = 'Solo archivos PDF.';
                $data['status'] = FALSE;
            }
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add(Request $request)
    {
        //dd($request);
        $this->_validate($request);
        $file = $request->file('fichatecnica');
        if ($file) {
            $fichatecnica = NULL;
            $name = $file->getClientOriginalName();
            $ext = \File::extension($name);
            if ($ext == 'pdf') {
                $ruta = $file->getRealPath();
                $fichatecnica = file_get_contents($ruta);
                $fichatecnica = base64_encode($fichatecnica);
            } else {
                return response()->json(array('status' => false, 'msn' => 'Solo archivos PDF'));
            }
        } else {
            $fichatecnica = NULL;
        }
        // $codigoSector = IndicadorGeneral::where('sector_id', $request->sector)->select(DB::raw('max(codigo) codigo'))->first();
        // if ($codigoSector) {
        //     $codigo = $this->generarCodigo($codigoSector->codigo);
        // } else {
        //     $codigo = 'IND0001';
        // }
        IndicadorGeneral::Create([
            'codigo' => $request->codigo,
            'nombre' => strtoupper($request->nombre),
            'descripcion' => $request->descripcion,
            'numerador' => $request->numerador,
            'denominador' => $request->denominador,
            'instrumento_id' => $request->instrumento,
            'tipo_id' => $request->tipo,
            'dimension_id' => 0, //$request->dimension,
            'unidad_id' => $request->unidad,
            'frecuencia_id' => $request->frecuencia,
            'fuente_dato' => $request->fuentedato,
            'anio_base' => $request->aniobase,
            'valor_base' => $request->valorbase,
            'sector_id' => $request->sector,
            'oficina_id' => $request->oficina,
            'ficha_tecnica' => $fichatecnica,
            'estado' => 0
        ]);

        return response()->json(array('status' => true, 'msn' => 'OK'));
    }

    public function ajax_edit($id)
    {
        $ie = IndicadorGeneral::find($id);
        if ($ie) {
            if ($ie->oficina_id > 0) {
                $oficina = Entidad::find($ie->oficina_id);
                $entidad = Entidad::find($oficina->dependencia);
                $ie->oficinan = $oficina->nombre;
                $ie->entidad = $entidad->id;
                $ie->entidadn = $entidad->nombre;
            } else {
                $ie->oficinan = '';
                $ie->entidad = 0;
                $ie->entidadn = '';
            }
        }

        return response()->json(compact('ie'));
    }

    public function buscar($id)
    {
        $ie = IndicadorGeneralRepositorio::findNoFichatecnica($id);
        if ($ie) {
            if ($ie->instrumento_id > 0) {
                $inst = IndicadorGeneralRepositorio::instrumento($ie->instrumento_id);
                $ie->instrumento = $inst->nombre;
            }
            if ($ie->tipo_id > 0) {
                $inst = IndicadorGeneralRepositorio::tipo($ie->tipo_id);
                $ie->tipo = $inst->nombre;
            }
            if ($ie->oficina_id > 0) {
                $oficina = Entidad::find($ie->oficina_id);
                $entidad = Entidad::find($oficina->dependencia);
                $ie->oficinan = $oficina->nombre;
                $ie->entidad = $entidad->id;
                $ie->entidadn = $entidad->nombre;
            } else {
                $ie->oficinan = '';
                $ie->entidad = 0;
                $ie->entidadn = '';
            }
        }

        return response()->json(compact('ie'));
    }

    // public function generar-Codigo($codigo)
    // {
    //     $valor = (int)substr($codigo, 3, 7);
    //     $valor++;
    //     $codigo = '';
    //     for ($i = 0; $i < 4 - strlen('' . $valor); $i++) {
    //         $codigo .= '0';
    //     }
    //     return 'IND' . $codigo . $valor;
    // }

    public function generarCodigo(Request $rq)
    {
        $inst = DB::table('par_instrumento')->where('id', $rq->instrumento)->first();
        $sect = Sector::where('id', $rq->sector)->first();

        switch ($rq->instrumento) {
            case '6':
                break;

            default:
                $codigo = $inst ? $inst->abreviado . '-' : '';
                $codigo .= $sect ? substr($sect->nombre, 0, 3) . '-' : '';
                break;
        }
        // if ($rq->instrumento != 6) {
        //     $codigo = $inst ? $inst->abreviado . '-' : '';
        //     $codigo .= $sect ? substr($sect->nombre, 0, 3) . '-' : '';
        // }

        $conteo = strlen($codigo) + 2;

        return response()->json(compact('codigo', 'conteo'));
    }

    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $file = $request->file('fichatecnica');
        if ($file) {
            $fichatecnica = NULL;
            $name = $file->getClientOriginalName();
            $ext = \File::extension($name);
            if ($ext == 'pdf') {
                $ruta = $file->getRealPath();
                $fichatecnica = file_get_contents($ruta);
                $fichatecnica = base64_encode($fichatecnica);
            } else {
                return response()->json(array('status' => false, 'msn' => 'Solo archivos PDF'));
            }
        } else {
            $fichatecnica = NULL;
        }

        $indicador = IndicadorGeneral::find($request->id);
        $indicador->codigo = $request->codigo;
        $indicador->nombre = strtoupper($request->nombre);
        $indicador->descripcion = $request->descripcion;
        $indicador->numerador = $request->numerador;
        $indicador->denominador = $request->denominador;
        $indicador->instrumento_id = $request->instrumento;
        $indicador->tipo_id = $request->tipo;
        // $indicador->dimension_id = $request->dimension;
        $indicador->unidad_id = $request->unidad;
        $indicador->frecuencia_id = $request->frecuencia;
        $indicador->fuente_dato = $request->fuentedato;
        $indicador->anio_base = $request->aniobase;
        $indicador->valor_base = $request->valorbase;
        $indicador->sector_id = $request->sector;
        $indicador->oficina_id = $request->oficina;
        if ($file)
            $indicador->ficha_tecnica = $fichatecnica;
        $indicador->save();
        return response()->json(array('status' => true));
    }

    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = IndicadorGeneral::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }

    public function ajax_estado($id)
    {
        $ig = IndicadorGeneral::find($id);
        $ig->estado = $ig->estado == 1 ? 0 : 1;
        $ig->save();
        return response()->json(array('status' => true, 'estado' => $ig->estado));
    }

    public function exportarPDF($id)
    {
        $ind = IndicadorGeneral::select('codigo', 'ficha_tecnica')->where('id', $id)->first();
        if ($ind->ficha_tecnica) {
            header('Content-Type: application/pdf');
            echo base64_decode($ind->ficha_tecnica);

            // $b64d = base64_decode($ind->ficha_tecnica);
            // $pdf = fopen('aaa.pdf', 'w');
            // fwrite($pdf, $b64d);
            // fclose($pdf);
            // echo $b64d;
            //echo file_put_contents("aaaa.pdf", base64_decode($ind->ficha_tecnica));
        } else {
            echo 'archivo PDF no encontrado';
        }
    }

    public function ListarDTMeta(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicadorgeneral)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $ig = IndicadorGeneral::select('id', 'unidad_id')->where('id', $rq->indicadorgeneral)->first();

            $btn = '&nbsp;<a href="#" class="btn btn-primary btn-xs" onclick="editmeta(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrarmeta(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';

            $data[] = array(
                $key + 1,
                '<div style="text-align:center">' . $value->periodo . '</div>',
                '<div style="text-align:center">' . $value->anio . '</div>',
                '<div style="text-align:center">' . $value->valor . ($ig->unidad_id == 1 ? '%' : '') . '</div>',
                "<center>" . $btn . "</center>",
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

    private function _validate_meta($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->periodo == '') {
            $data['inputerror'][] = 'periodo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->anioesperado == '') {
            $data['inputerror'][] = 'anioesperado';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->valoresperado == '') {
            $data['inputerror'][] = 'valoresperado';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add_meta(Request $request)
    {
        $this->_validate_meta($request);
        $ind = IndicadorGeneral::find($request->indicadorgeneral);
        $ind->anio_base = $request->aniobase;
        $ind->valor_base = $request->valorbase;

        $meta = IndicadorGeneralMeta::Create([
            'indicadorgeneral' => $request->indicadorgeneral,
            'periodo' => $request->periodo,
            'distrito' => NULL,
            'anio_base' => 0,
            'valor_base' => '',
            'anio' => $request->anioesperado,
            'valor' => $request->valoresperado
        ]);
        return response()->json(['status' => true, 'ind' => $ind, 'meta' => $meta]);
    }

    public function ajax_find_meta($id)
    {
        $meta = IndicadorGeneralMeta::find($id);
        return response()->json(compact('meta'));
    }

    public function ajax_update_meta(Request $request)
    {
        $this->_validate_meta($request);
        $meta = IndicadorGeneralMeta::find($request->idmeta);
        $meta->periodo = $request->periodo;
        $meta->anio = $request->anioesperado;
        $meta->valor = $request->valoresperado;
        $meta->save();
        return response()->json(['status' => true, 'meta' => $meta]);
    }

    public function ajax_delete_meta($id) //elimina deverdad *o*
    {
        $rer = IndicadorGeneralMeta::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }

    public function ListarDTMeta_dit(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $ig = IndicadorGeneral::select('id', 'unidad_id')->where('id', $rq->indicadorgeneral)->first();
        $query = IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicadorgeneral)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $dis = Ubigeo::find($value->distrito);
            // $pro = Ubigeo::find($dis->dependencia);

            $btn = '&nbsp;<a href="#" class="btn btn-primary btn-xs" onclick="editmeta_dit(' . $value->id . ')"  title="MODIFICARxxx"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrarmeta(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';

            $data[] = array(
                $key + 1,
                // '<div style="text-align:center">' . $pro->nombre . '</div>',
                '<div>' . $dis->nombre . '</div>',
                // '<div style="text-align:center">' . $value->anio_base . '</div>',
                // '<div style="text-align:center">' . $value->valor_base . '</div>',
                '<div style="text-align:center">' . $value->anio . '</div>',
                '<div style="text-align:center">' . $value->valor . ($ig->unidad_id == 1 ? '%' : '') . '</div>',
                "<center>" . $btn . "</center>",
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

    private function _validate_meta_dit($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        // if ($request->aniobase_dit < 1) {
        //     $data['inputerror'][] = 'aniobase_dit';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // } else if ($request->aniobase_dit < 2022) {
        //     $data['inputerror'][] = 'aniobase_dit';
        //     $data['error_string'][] = 'Ingrese un año valido.';
        //     $data['status'] = FALSE;
        // }

        // if ($request->valorbase_dit == '') {
        //     $data['inputerror'][] = 'valorbase_dit';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($request->anioesperado_dit < 1) {
            $data['inputerror'][] = 'anioesperado_dit';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if ($request->anioesperado_dit < 2023) {
            $data['inputerror'][] = 'anioesperado_dit';
            $data['error_string'][] = 'Ingrese un Año valido.';
            $data['status'] = FALSE;
        }

        if ($request->idmeta_dit > 0) {
        } else {
            if ($request->distrito_dit == '0') {
                $data['inputerror'][] = 'distrito_dit';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            } else {
                $meta = IndicadorGeneralMeta::where('indicadorgeneral', $request->indicadorgeneral_dit)->where('distrito', $request->distrito_dit)->where('anio', $request->anioesperado_dit)->first();
                if ($meta) {
                    $data['inputerror'][] = 'distrito_dit';
                    $data['error_string'][] = 'Distrito ya registrado.';
                    $data['status'] = FALSE;
                }
            }
        }


        if ($request->valoresperado_dit == '') {
            $data['inputerror'][] = 'valoresperado_dit';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add_meta_dit(Request $request)
    {
        $this->_validate_meta_dit($request);
        $ind = IndicadorGeneral::find($request->indicadorgeneral_dit);
        $meta = IndicadorGeneralMeta::Create([
            'indicadorgeneral' => $request->indicadorgeneral_dit,
            'periodo' => '', //$request->periodo,
            'distrito' => $request->distrito_dit,
            'anio_base' => $ind->anio_base, // 0, //$request->aniobase_dit,
            'valor_base' => $ind->valor_base, // '', //$request->valorbase_dit,
            'anio' => $request->anioesperado_dit,
            'valor' => $request->valoresperado_dit
        ]);
        return response()->json(['status' => true, 'meta' => $meta]);
    }

    public function ajax_find_meta_dit($id)
    {
        $meta = IndicadorGeneralMeta::find($id);
        $dist = Ubigeo::find($meta->distrito);
        $prov = Ubigeo::find($dist->dependencia);
        return response()->json(compact('meta', 'dist', 'prov'));
    }

    public function ajax_update_meta_dit(Request $request)
    {
        $this->_validate_meta_dit($request);
        $meta = IndicadorGeneralMeta::find($request->idmeta_dit);
        $meta->distrito = $request->distrito_dit;
        $meta->anio = $request->anioesperado_dit;
        $meta->valor = $request->valoresperado_dit;
        $meta->save();
        return response()->json(['status' => true, 'meta' => $meta]);
    }


    public function ListarDTMeta_fed(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $ig = IndicadorGeneral::select('id', 'unidad_id')->where('id', $rq->indicadorgeneral)->first();
        $query = IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicadorgeneral)->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $dis = Ubigeo::find($value->distrito);
            // $pro = Ubigeo::find($dis->dependencia);

            $btn = '&nbsp;<a href="#" class="btn btn-primary btn-xs" onclick="editmeta_fed(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrarmeta(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';

            $data[] = array(
                $key + 1,
                // '<div style="text-align:center">' . $pro->nombre . '</div>',
                '<div>' . $dis->nombre . '</div>',
                // '<div style="text-align:center">' . $value->anio_base . '</div>',
                // '<div style="text-align:center">' . $value->valor_base . '</div>',
                '<div style="text-align:center">' . $value->anio . '</div>',
                '<div style="text-align:center">' . $value->valor . ($ig->unidad_id == 1 ? '%' : '') . '</div>',
                "<center>" . $btn . "</center>",
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

    private function _validate_meta_fed($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        // if ($request->aniobase_fed < 1) {
        //     $data['inputerror'][] = 'aniobase_fed';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // } else if ($request->aniobase_fed < 2022) {
        //     $data['inputerror'][] = 'aniobase_fed';
        //     $data['error_string'][] = 'Ingrese un año valido.';
        //     $data['status'] = FALSE;
        // }

        // if ($request->valorbase_fed == '') {
        //     $data['inputerror'][] = 'valorbase_fed';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($request->anioesperado_fed < 1) {
            $data['inputerror'][] = 'anioesperado_fed';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if ($request->anioesperado_fed < 2023) {
            $data['inputerror'][] = 'anioesperado_fed';
            $data['error_string'][] = 'Ingrese un Año valido.';
            $data['status'] = FALSE;
        }

        if ($request->idmeta_fed > 0) {
        } else {
            if ($request->distrito_fed == '0') {
                $data['inputerror'][] = 'distrito_fed';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            } else {
                $meta = IndicadorGeneralMeta::where('indicadorgeneral', $request->indicadorgeneral_fed)->where('distrito', $request->distrito_fed)->where('anio', $request->anioesperado_fed)->first();
                if ($meta) {
                    $data['inputerror'][] = 'distrito_fed';
                    $data['error_string'][] = 'Distrito ya registrado.';
                    $data['status'] = FALSE;
                }
            }
        }


        if ($request->valoresperado_fed == '') {
            $data['inputerror'][] = 'valoresperado_fed';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add_meta_fed(Request $request)
    {
        $this->_validate_meta_fed($request);
        $ind = IndicadorGeneral::find($request->indicadorgeneral_fed);
        $meta = IndicadorGeneralMeta::Create([
            'indicadorgeneral' => $request->indicadorgeneral_fed,
            'periodo' => '', //$request->periodo,
            'distrito' => $request->distrito_fed,
            'anio_base' => $ind->anio_base, // 0, //$request->aniobase_fed,
            'valor_base' => $ind->valor_base, // '', //$request->valorbase_fed,
            'anio' => $request->anioesperado_fed,
            'valor' => $request->valoresperado_fed
        ]);
        return response()->json(['status' => true, 'meta' => $meta]);
    }

    public function ajax_find_meta_fed($id)
    {
        $meta = IndicadorGeneralMeta::find($id);
        $dist = Ubigeo::find($meta->distrito);
        $prov = Ubigeo::find($dist->dependencia);
        return response()->json(compact('meta', 'dist', 'prov'));
    }

    public function ajax_update_meta_fed(Request $request)
    {
        $this->_validate_meta_fed($request);
        $meta = IndicadorGeneralMeta::find($request->idmeta_fed);
        $meta->distrito = $request->distrito_fed;
        $meta->anio = $request->anioesperado_fed;
        $meta->valor = $request->valoresperado_fed;
        $meta->save();
        return response()->json(['status' => true, 'meta' => $meta]);
    }

    public function descargarExcel($indicador)
    {
        $codigo = IndicadorGeneral::select('codigo')->where('id', $indicador)->first()->codigo;

        return Excel::download(new AgregarMetasExport($indicador), 'Metas ' . $codigo . ' ' . date('YmdHis') . ' .xlsx');
    }

    public function cargarExcel(Request $rq)
    {
        $rq->validate([
            'archivo' => 'required|mimes:xlsx,csv'
        ]);

        IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicador)->delete();
        Excel::import(new AgregarMetasImport, $rq->file('archivo'));

        return redirect()->back()->with('success', 'Archivo importado correctamente.');
    }

    public function cargarExcelFED(Request $rq)
    {
        $rq->validate([
            'archivo_fed' => 'required|mimes:xlsx,csv'
        ]);
        IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicador_fed)->delete();
        Excel::import(new AgregarMetasImport, $rq->file('archivo_fed'));

        return redirect()->back()->with('success', 'Archivo importado correctamente.');
    }
}
