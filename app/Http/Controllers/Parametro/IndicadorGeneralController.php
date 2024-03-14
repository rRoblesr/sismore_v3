<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Entidad;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Presupuesto\Sector;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $instrumento = IndicadorGeneralRepositorio::instrumento(0);
        $tipo = IndicadorGeneralRepositorio::tipo(0);
        $dimension = IndicadorGeneralRepositorio::dimension();
        $unidad = IndicadorGeneralRepositorio::unidad();
        $frecuencia = IndicadorGeneralRepositorio::frecuencia();
        $sector = Sector::all();

        return view('parametro.IndicadorGeneral.PrincipalEducacion', compact('instrumento', 'tipo', 'dimension', 'unidad', 'frecuencia', 'sector'));
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = IndicadorGeneral::select('id', 'codigo', 'nombre', 'descripcion', 'instrumento_id', 'tipo_id', 'dimension_id', 'unidad_id', 'frecuencia_id', 'fuente_dato', 'anio_base', 'valor_base', 'sector_id', 'oficina_id', 'estado')->orderBy('id', 'desc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $sector = Sector::find($value->sector_id);
            $tipo = DB::table('par_tipo')->where('id', $value->tipo_id)->first();
            $instrumento = DB::table('par_instrumento')->where('id', $value->instrumento_id)->first();

            $btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            if ($value->estado == 0) {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-power-off"></i></a> ';
            } else {
                $btn2 = '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',' . $value->estado . ')"><i class="fa fa-check"></i></a> ';
            }
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="verpdf(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fas fa-file"></i> </button>';
            $btn5 = '&nbsp;<a href="#" class="btn btn-success btn-xs" onclick="metas(' . $value->id . ')"  title="Agregar Metas"> <i class="fa fa-plus"></i> </a>';

            $data[] = array(
                $key + 1,
                '<div style="text-align:center">' . $value->codigo . '</div>',
                $value->nombre,
                $sector->nombre,
                '<div style="text-align:center">' . $tipo->nombre . '</div>',
                '<div style="text-align:center" title="' . $instrumento->nombre . '">' . $instrumento->abreviado . '</div>',
                '<div style="text-align:center">' . $value->anio_base . '</div>',
                "<center><div class='btn-group'>" . $btn1 .  $btn4  . ($instrumento->meta == 'SI' ? $btn5 : '')  . $btn3 . "</div></center>",
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

        if ($request->numerador == '') {
            $data['inputerror'][] = 'numerador';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->denominador == '') {
            $data['inputerror'][] = 'denominador';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->tipo == '') {
            $data['inputerror'][] = 'tipo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->dimension == '') {
            $data['inputerror'][] = 'dimension';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

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

        // if ($request->aniobase == '') {
        //     $data['inputerror'][] = 'aniobase';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        // if ($request->valorbase == '') {
        //     $data['inputerror'][] = 'valorbase';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

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
        $codigoSector = IndicadorGeneral::where('sector_id', $request->sector)->select(DB::raw('max(codigo) codigo'))->first();
        if ($codigoSector) {
            $codigo = $this->generarCodigo($codigoSector->codigo);
        } else {
            $codigo = 'IND0001';
        }
        IndicadorGeneral::Create([
            'codigo' => $codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'numerador' => $request->numerador,
            'denominador' => $request->denominador,
            'instrumento_id' => $request->instrumento,
            'tipo_id' => $request->tipo,
            'dimension_id' => $request->dimension,
            'unidad_id' => $request->unidad,
            'frecuencia_id' => $request->frecuencia,
            'fuente_dato' => $request->fuentedato,
            'anio_base' => 0, //$request->aniobase,
            'valor_base' => '', //$request->valorbase,
            'sector_id' => $request->sector,
            'oficina_id' => $request->oficina,
            'ficha_tecnica' => $fichatecnica,
            'estado' => 0
        ]);

        return response()->json(array('status' => true, 'msn' => 'OK'));
    }

    public function generarCodigo($codigo)
    {
        $valor = (int)substr($codigo, 3, 7);
        $valor++;
        $codigo = '';
        for ($i = 0; $i < 4 - strlen('' . $valor); $i++) {
            $codigo .= '0';
        }
        return 'IND' . $codigo . $valor;
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
        $indicador->nombre = $request->nombre;
        $indicador->descripcion = $request->descripcion;
        $indicador->numerador = $request->numerador;
        $indicador->denominador = $request->denominador;
        $indicador->instrumento_id = $request->instrumento;
        $indicador->tipo_id = $request->tipo;
        $indicador->dimension_id = $request->dimension;
        $indicador->unidad_id = $request->unidad;
        $indicador->frecuencia_id = $request->frecuencia;
        $indicador->fuente_dato = $request->fuentedato;
        // $indicador->anio_base = $request->aniobase;
        // $indicador->valor_base = $request->valorbase;
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
        /* $rer = Lengua::find($id);
        $rer->estado = $rer->estado == 1 ? 0 : 1;
        $rer->save();
        return response()->json(array('status' => true, 'estado' => $rer->estado)); */
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
            $btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrarmeta(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';

            $data[] = array(
                $key + 1,
                '<div style="text-align:center">' . $value->periodo . '</div>',
                '<div style="text-align:center">' . $value->anio . '</div>',
                '<div style="text-align:center">' . $value->valor . ($ig->unidad_id == 1 ? '%' : '') . '</div>',
                "<center>" . $btn3 . "</center>",
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

        // if ($request->periodo == '') {
        //     $data['inputerror'][] = 'periodo';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($request->aniobase == '') {
            $data['inputerror'][] = 'aniobase';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->valorbase == '') {
            $data['inputerror'][] = 'valorbase';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->distrito == '') {
            $data['inputerror'][] = 'distrito';
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
        IndicadorGeneralMeta::Create([
            'indicadorgeneral' => $request->indicadorgeneral,
            'periodo' => '', //$request->periodo,
            'distrito' => $request->distrito,
            'anio' => $request->anioesperado,
            'valor' => $request->valoresperado
        ]);
        return response()->json(array('status' => true));
    }

    public function ajax_delete_meta($id) //elimina deverdad *o*
    {
        $rer = IndicadorGeneralMeta::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }
}
