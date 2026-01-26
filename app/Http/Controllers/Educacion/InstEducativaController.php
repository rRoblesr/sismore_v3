<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\Caracteristica;
use App\Models\Educacion\Forma;
use App\Models\Educacion\Genero;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\SFL;
use App\Models\Educacion\TipoGestion;
use App\Models\Educacion\Turno;
use App\Models\Educacion\Ugel;
use App\Repositories\Educacion\InstEducativaRepositorio;
use App\Repositories\Educacion\InstitucionEducativaRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Utilities\Utilitario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstEducativaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $importaciones_padronweb = InstEducativaRepositorio::importaciones_padronweb();
        //return $importaciones_padronweb;

        $fecha_version = Utilitario::fecha_formato_texto_completo($importaciones_padronweb->first()->fechaActualizacion);
        //return $fecha_version;

        $total_tipoGestion = InstEducativaRepositorio::total_tipoGestion();
        //return $total_tipoGestion;

        $privadas = $total_tipoGestion->first()->privada;
        $publicas = $total_tipoGestion->first()->publica;


        return view('educacion.InstEducativa.Principal', compact('fecha_version', 'privadas', 'publicas'));
    }

    public function reporteDistrito()

    {
        $lista_resumen_porDistrito = InstEducativaRepositorio::resumen_porDistrito_tipoGestion();
        $lista_resumen_porProvincia = InstEducativaRepositorio::resumen_porProvincia_tipoGestion();
        // $sumatoria_Provincia = $this->sumatoria_Provincia($lista_resumen_porDistrito);
        $lista_resumen_porRegion = InstEducativaRepositorio::resumen_porRegion();

        return view('educacion.InstEducativa.ReporteDistrito', compact('lista_resumen_porDistrito', 'lista_resumen_porRegion', 'lista_resumen_porProvincia'));
    }

    public function sumatoria_Provincia($lista_resumen_porDistrito)
    {
        $lista_provincias = $lista_resumen_porDistrito->unique('provincia');

        $sumatoria_Provincia = [];

        foreach ($lista_provincias as $key => $item) {
            $suma_activas = 0;
            $suma_inactivas = 0;

            foreach ($lista_resumen_porDistrito as $key => $item2) {
                if ($item->provincia == $item2->provincia) {
                    $suma_activas += $item2->activas;
                    $suma_inactivas += $item2->inactivas;
                }
            }

            $sumatoria_Provincia[] = (['provincia' => $item->provincia, 'suma_activas' =>  $suma_activas, 'suma_inactivas' =>  $suma_inactivas]);
        }

        return $sumatoria_Provincia;
    }

    public function GraficoBarras_Instituciones_Distrito()
    {
        $lista_resumen_porProvincia = InstEducativaRepositorio::resumen_porProvincia();

        /************* GRAFICO BARRAS*******************/
        $categoria_nombres = [];
        $recorre = 1;

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($lista_resumen_porProvincia as $key => $lista) {

            $data = [];
            $data = array_merge($data, [intval($lista->activas)]);
            $puntos[] = ['name' => $lista->provincia, 'data' =>  $data];
        }

        $categoria_nombres[] = 'UGEL';

        $nombreGraficoBarra = 'GraficoBarras_Instituciones_Distrito'; // este nombre va de la mano con el nombre del DIV en la vista
        $titulo = 'INSTITUCIONES EDUCATIVAS POR DISTRITOS';
        $subTitulo = 'Fuente: PADRON WEB - ESCALE';
        $titulo_y = 'Numero de Instituciones Educativas';

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function completariiee(Request $rq)
    {
        $term = $rq->get('term');
        $query = InstitucionEducativa::where(DB::raw("concat(' ',codModular,nombreInstEduc)"), 'like', "%$term%")->orderBy('nombreInstEduc', 'asc')->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                "label" => $value->codModular . ' | ' . $value->nombreInstEduc,
                "id" => $value->id
            ];
        }
        return $data; //response()->json('data');
    }

    public function completariiee2(Request $rq)
    {
        $term = $rq->get('term');
        $query = InstitucionEducativaRepositorio::buscariiee2($term);
        $data = [];
        foreach ($query as $value) {
            $data[] = [
                "label" => $value->codigo_modular . ' | ' . $value->iiee,
                "id" => $value->id,
                "provincia" => $value->provincia,
                "distrito" => $value->distrito,
                "centro_poblado" => $value->centro_poblado,
                "codigo_local" => $value->codigo_local,
                "iiee" => $value->iiee,
                "codigo_nivel" => $value->codigo_nivel,
                "nivel_modalidad" => $value->nivel_modalidad,
                "estado" => $value->estado,
                "ugel" => $value->ugel,
            ];
        }
        return $data; //response()->json('data');
    }

    public function buscar_codmodular($local)
    {
        $query = InstitucionEducativa::select('id', 'codLocal as local', 'codModular as modular', 'nombreInstEduc as iiee')->where('codLocal', $local)->get();
        return $query;
    }

    public function cargar_distrito($provincia)
    {
        $query = InstitucionEducativa::distinct()->select('dt.*')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'edu_institucioneducativa.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id');
        if ($provincia) $query = $query->where('dt.dependencia', $provincia);
        $query = $query->get();
        return $query;
    }

    /* Mantenimiento */
    public function mantenimiento()
    {
        $nivel = NivelModalidad::whereIn('id', function($q){
            $q->select('NivelModalidad_id')->from('edu_institucionEducativa')->where('estado', 'AC');
        })->select('id', 'codigo', 'nombre')->where('estado', 'AC')->orderBy('codigo')->get();

        $ugels = Ugel::whereIn('id', function($q){
            $q->select('Ugel_id')->from('edu_institucionEducativa')->where('estado', 'AC');
        })->select('id', 'codigo', 'nombre')->where('estado', 'AC')->orderBy('codigo')->get();

        $provincias = DB::table('par_ubigeo as p')
            ->join('par_ubigeo as d', 'd.dependencia', '=', 'p.id')
            ->join('edu_centropoblado as cp', 'cp.Ubigeo_id', '=', 'd.id')
            ->join('edu_institucionEducativa as ie', 'ie.CentroPoblado_id', '=', 'cp.id')
            ->where('ie.estado', 'AC')
            ->select('p.id', 'p.nombre')
            ->distinct()
            ->orderBy('p.nombre')
            ->get();

        $distritos = DB::table('par_ubigeo as d')
            ->join('edu_centropoblado as cp', 'cp.Ubigeo_id', '=', 'd.id')
            ->join('edu_institucionEducativa as ie', 'ie.CentroPoblado_id', '=', 'cp.id')
            ->where('ie.estado', 'AC')
            ->select('d.id', 'd.nombre')
            ->distinct()
            ->orderBy('d.nombre')
            ->get();

        $forma = Forma::select('id',  'nombre')->where('estado', 'AC')->get();
        $carac = Caracteristica::select('id', 'codigo', 'nombre')->where('estado', 'AC')->get();
        $gener = Genero::select('id', 'codigo', 'nombre')->where('estado', 'AC')->orderBy('codigo')->get();
        $tipog = TipoGestion::select('id', 'codigo', 'nombre')->where('dependencia', '>', '0')->where('estado', 'AC')->orderBy('dependencia')->orderBy('codigo')->get();
        $areas = Area::select('id', 'codigo', 'nombre')->where('estado', 'AC')->orderBy('codigo')->get();
        $turno = Turno::select('id', 'codigo', 'nombre')->where('estado', 'AC')->orderBy('codigo')->get();
        
        return view('educacion.InstEducativa.Mantenimiento', compact('nivel', 'forma', 'carac', 'gener', 'tipog', 'ugels', 'areas', 'turno', 'provincias', 'distritos'));
    }

    public function cargarFiltros(Request $rq)
    {
        $queryBase = InstitucionEducativa::from('edu_institucioneducativa as ie')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('edu_ugel as u', 'u.id', '=', 'ie.Ugel_id')
            ->join('edu_nivelmodalidad as n', 'n.id', '=', 'ie.NivelModalidad_id')
            ->where('ie.estado', 'AC');

        // Provincias
        $qProv = clone $queryBase;
        if ($rq->ugel > 0) $qProv->where('u.id', $rq->ugel);
        $provincias = $qProv->select('p.id', 'p.nombre')->distinct()->orderBy('p.nombre')->get();

        // Distritos
        $qDist = clone $queryBase;
        if ($rq->ugel > 0) $qDist->where('u.id', $rq->ugel);
        if ($rq->provincia > 0) $qDist->where('p.id', $rq->provincia);
        $distritos = $qDist->select('d.id', 'd.nombre')->distinct()->orderBy('d.nombre')->get();

        // Niveles
        $qNivel = clone $queryBase;
        if ($rq->ugel > 0) $qNivel->where('u.id', $rq->ugel);
        if ($rq->provincia > 0) $qNivel->where('p.id', $rq->provincia);
        if ($rq->distrito > 0) $qNivel->where('d.id', $rq->distrito);
        $niveles = $qNivel->select('n.id', 'n.nombre', 'n.codigo')->distinct()->orderBy('n.codigo')->get();

        return response()->json([
            'provincias' => $provincias,
            'distritos' => $distritos,
            'niveles' => $niveles
        ]);
    }

    public function ListarDT(Request $rq)
    {
        $draw   = intval($rq->draw);
        $start  = intval($rq->start);
        $length = intval($rq->length);

        $estado = ['AC' => '<span class="badge badge-success" style="font-size: 12px">Activo</span>', 'EL' => '<span class="badge badge-danger" style="font-size: 12px">Inactivo</span>'];

        $query = InstitucionEducativa::from('edu_institucioneducativa as ie')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as d', 'd.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->join('edu_area as a', 'a.id', '=', 'ie.Area_id')
            ->join('edu_ugel as u', 'u.id', '=', 'ie.Ugel_id')
            ->join('edu_nivelmodalidad as n', 'n.id', '=', 'ie.NivelModalidad_id')
            ->join('edu_tipogestion as g', 'g.id', '=', 'ie.TipoGestion_id')
            ->select(
                'ie.id',
                'ie.codLocal as local',
                'ie.codModular as modular',
                'ie.nombreInstEduc as nombre',
                'u.nombre as ugel',
                'n.nombre as nivel',
                DB::raw('case  when g.dependencia in(1,2) then "PÚBLICA" when g.dependencia = 3 then "PRIVADA" else "" end as gestion'),
                'ie.es_eib as eib',
                'a.nombre as area',
                'p.nombre as provincia',
                'd.nombre as distrito',
                'ie.estado',
                'ie.modo_registro'
            )
            ->tap(function ($query) use ($rq) {
                if ($rq->ugel > 0) {
                    $query->where('u.id', $rq->ugel);
                }
                if ($rq->provincia > 0) {
                    $query->where('p.id', $rq->provincia);
                }
                if ($rq->distrito > 0) {
                    $query->where('d.id', $rq->distrito);
                }
                if ($rq->nivel > 0) {
                    $query->where('n.id', $rq->nivel);
                }
                // if ($rq->estado > 0) {
                //     $query->where('s.estado', $rq->estado);
                // }
            })
            ->orderBy('id', 'desc')->get();



        // Procesamos los resultados para formar el arreglo final para DataTables
        $data = [];
        foreach ($query as $key => $value) {
            $btn = '';

            if ($value->estado == 'AC') {
                $btn .= '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                $btn .= '&nbsp;<a class="btn btn-sm btn-dark btn-xs" href="javascript:void(0)" title="Desactivar" onclick="estado(' . $value->id . ',`' . $value->estado . '`)"><i class="fa fa-power-off"></i></a> ';
                if ($value->modo_registro == 1) {
                    $btn .= '&nbsp;<a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
                }
            } else {
                $btn .= '&nbsp;<a class="btn btn-sm btn-default btn-xs"  title="Activar" onclick="estado(' . $value->id . ',`' . $value->estado . '`)"><i class="fa fa-check"></i></a> ';
            }


            // Construir la fila de datos (aplicando filtro de estado si se ha enviado)
            $data[] = [
                ($key + 1),
                $value->local == 0 ? '' : str_pad($value->local, 6, '0', STR_PAD_LEFT),
                $value->modular,
                $value->nombre,
                $value->ugel,
                $value->nivel,
                $value->gestion,
                $value->area,
                $value->eib,
                $estado[$value->estado] ?? '',
                $btn,
            ];
        }

        $result = [
            "draw"            => $draw,
            "recordsTotal"    => $start,
            "recordsFiltered" => $length,
            "data"            => $data,
            // Opcional: para depuración, puedes comentar estos dos:
            // "xxx1"            => $queryResult,
            // "xxx2"            => $querySFL,
        ];

        return response()->json($result);
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $modular = InstitucionEducativa::where('codModular', $request->codModular)->first();

        // if ($request->codLocal == '') {
        //     $data['inputerror'][] = 'codLocal';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // } else if (strlen($request->codLocal) != 6) {
        //     $data['inputerror'][] = 'codLocal';
        //     $data['error_string'][] = 'Codigo Modular ya Existe.';
        //     $data['status'] = FALSE;
        // }

        if ($request->codModular == '') {
            $data['inputerror'][] = 'codModular';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->codModular) != 7) {
            $data['inputerror'][] = 'codModular';
            $data['error_string'][] = 'Codigo Modular debe tener 7 digitos.';
            $data['status'] = FALSE;
        } else if ($modular && $request->id == '') {
            $data['inputerror'][] = 'codModular';
            $data['error_string'][] = 'Codigo Modular ya Existe.';
            $data['status'] = FALSE;
        }

        if ($request->nombreInstEduc == '') {
            $data['inputerror'][] = 'nombreInstEduc';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->NivelModalidad_id == '') {
            $data['inputerror'][] = 'NivelModalidad_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->Forma_id == '') {
            $data['inputerror'][] = 'Forma_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->Caracteristica_id == '') {
            $data['inputerror'][] = 'Caracteristica_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->Genero_id == '') {
            $data['inputerror'][] = 'Genero_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->TipoGestion_id == '') {
            $data['inputerror'][] = 'TipoGestion_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->Ugel_id == '') {
            $data['inputerror'][] = 'Ugel_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->Area_id == '') {
            $data['inputerror'][] = 'Area_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->Turno_id == '') {
            $data['inputerror'][] = 'Turno_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
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
        $rq->merge(['EstadoInsEdu_id' => '3']);
        $rq->merge(['modo_registro' => '1']);
        $rq->merge(['CentroPoblado_id' => '1']);
        $rq->merge(['estado' => 'AC']);
        $ie = InstitucionEducativa::create($rq->all());

        /* aqui se guarda en sfl los permitidos */
        $aux = InstitucionEducativa::where('id', $ie->id)->where('estadoInsEdu_id', '3')->where('estado', 'AC')->where(DB::raw('length(codLocal)'), 6)->whereIn('TipoGestion_id', [4, 5, 7, 8])->whereNotIn('NivelModalidad_id', [14, 15])->first();
        if ($aux) {
            $auxsfl = SFL::where('institucioneducativa_id', $ie->id)->first();
            if ($auxsfl) {
                $auxsfl->estado_servicio = 1;
                $auxsfl->save();
            } else {
                SFL::create([
                    'institucioneducativa_id' => $ie->id,
                    'estado' => '4',
                    'tipo' => '0',
                    'partida_electronica' => null,
                    'zona_registral' => 'ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA',
                    'anotacion' => '0',
                    'fecha_registro' => null,
                    'fecha_inscripcion' => null,
                    'documento' => null,
                    'estado_servicio' => '1'
                ]);
            }
        }
        return response()->json(array('status' => true));
    }

    public function ajax_edit($id)
    {
        $ie = InstitucionEducativa::find($id);
        return response()->json($ie);
    }

    public function ajax_update(Request $rq)
    {
        $this->_validate($rq);
        $ie = InstitucionEducativa::find($rq->id);
        $ie->update($rq->all());

        /* aqui se guarda en sfl los permitidos */
        $aux = InstitucionEducativa::where('id', $ie->id)->where('estadoInsEdu_id', '3')->where('estado', 'AC')->where(DB::raw('length(codLocal)'), 6)->whereIn('TipoGestion_id', [4, 5, 7, 8])->whereNotIn('NivelModalidad_id', [14, 15])->first();
        if ($aux) {
            $auxsfl = SFL::where('institucioneducativa_id', $ie->id)->first();
            if ($auxsfl) {
                $auxsfl->estado_servicio = 1;
                $auxsfl->save();
            } else {
                SFL::create([
                    'institucioneducativa_id' => $ie->id,
                    'estado' => '4',
                    'tipo' => '0',
                    'partida_electronica' => null,
                    'zona_registral' => 'ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA',
                    'anotacion' => '0',
                    'fecha_registro' => null,
                    'fecha_inscripcion' => null,
                    'documento' => null,
                    'estado_servicio' => '1'
                ]);
            }
        }

        return response()->json(array('status' => true, 'aux' => $aux));
    }

    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = InstitucionEducativa::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }

    public function ajax_estado($id)
    {
        $ie = InstitucionEducativa::find($id);
        $ie->estado = $ie->estado == "AC" ? "EL" : "AC";
        $ie->save();

        /* aqui se guarda en sfl los permitidos */
        $aux = InstitucionEducativa::where('id', $ie->id)->where('estadoInsEdu_id', '3')->where('estado', 'AC')->where(DB::raw('length(codLocal)'), 6)->whereIn('TipoGestion_id', [4, 5, 7, 8])->whereNotIn('NivelModalidad_id', [14, 15])->first();
        if ($aux) {
            $auxsfl = SFL::where('institucioneducativa_id', $ie->id)->first();
            if ($auxsfl) {
                $auxsfl->estado_servicio = 1;
                $auxsfl->save();
            } else {
                SFL::create([
                    'institucioneducativa_id' => $ie->id,
                    'estado' => '4',
                    'tipo' => '0',
                    'partida_electronica' => null,
                    'zona_registral' => 'ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA',
                    'anotacion' => '0',
                    'fecha_registro' => null,
                    'fecha_inscripcion' => null,
                    'documento' => null,
                    'estado_servicio' => '1'
                ]);
            }
        }
        return response()->json(array('status' => true, 'estado' => $ie->estado));
    }
}
