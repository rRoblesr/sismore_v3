<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\ImporPadronNominal;
use App\Models\Salud\PadronCalidad;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Salud\PadronNominalRepositorio;
use App\Repositories\Salud\PadronNominalRepositorioSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadronNominalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function meses($anio)
    {
        $mes = Importacion::distinct()->select(DB::raw('month(fechaActualizacion) as id'))
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('estado', 'PR')->where('fuenteImportacion_id', ImporPadronNominalController::$FUENTE)
            ->orderBy('id')->get();
        foreach ($mes as $key => $value) {
            $value->mes = Mes::find($value->id)->mes;
        }
        return response()->json($mes);
    }

    public function index()
    {
        return view('salud.padron.principal');
    }

    public function control_calidad()
    {
        return view('salud.padron.principal');
    }

    public function listar()
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        $idNivel = (session('usuario_id') == '123') ? '1' : ((session('usuario_id') == '176') ? '2' : ((session('usuario_id') == '177') ? '3' : '4'));
        $institucion = $idNivel == '3' ? 'CALLERIA' : ($idNivel == '2' ? '5556' : '');
        /*if($idNivel=='3')
            $tablon = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'))->where('distrito', $institucion)->groupBy('codigo_calidad','nombre_calidad')->get();
        elseif($idNivel=='2')
            $tablon = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'))->where('cod_eess_atencion', $institucion)->groupBy('codigo_calidad','nombre_calidad')->get();
        else $tablon = "";
        */
        $query = PadronNominalRepositorioSalud::Listar_PadronSabana($idNivel, $institucion);
        $data = [];
        foreach ($query as $key => $value) {
            $boton = '<button type="button" onclick="geteliminar(' . $value->DNI . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            $boton2 = '<button type="button" onclick="monitor(' . $value->DNI . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                $value->Distrito,
                $value->EESS,
                $value->CNV,
                $value->DNI,
                $value->nino,
                $value->Edad_Anio,
                $value->f_hb1,
                $value->Rhb1,
                $value->f_hb2,
                $value->Rhb2,
                $value->f_hb3,
                $value->Rhb3,
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data
        );
        return response()->json($result);
    }

    public function seguimiento_ninio()
    {
        $actualizado = '';
        return view('salud.PadronNominal.seguimiento', compact('actualizado'));
    }

    public function tablerocalidad()
    {
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE); //nexus $imp3
        $mes = Mes::find($imp->mes);
        $anios = ImportacionRepositorio::anios_porfuente_select(ImporPadronNominalController::$FUENTE);
        $provincias = UbigeoRepositorio::provincia_select('25');
        // compact('imp', 'mes');
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $mes->mes . ' del ' . $imp->anio;
        return view('salud.PadronNominal.TableroCalidad', compact('actualizado', 'anios', 'provincias'));
    }

    public function tablerocalidadreporte(Request $rq)
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        switch ($rq->div) {
            case 'head':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $card1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1');
                if ($rq->provincia > 0) $card1 = $card1->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $card1 = $card1->where('distrito_id', $rq->distrito);
                $card1 = $card1->count();

                $card2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('tipo_doc', 'DNI');
                if ($rq->provincia > 0) $card2 = $card2->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $card2 = $card2->where('distrito_id', $rq->distrito);
                $card2 = $card2->count();

                $card3 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->where(function ($query) {
                        $query->whereRaw("FIND_IN_SET('1',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('2',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('3',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('4',seguro) > 0");
                            // ->orWhereNotNull('seguro');
                    });
                if ($rq->provincia > 0) $card3 = $card3->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $card3 = $card3->where('distrito_id', $rq->distrito);
                $card3 = $card3->count();

                $card4 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('cui_atencion', '>', 0);
                if ($rq->provincia > 0) $card4 = $card4->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $card4 = $card4->where('distrito_id', $rq->distrito);
                $card4 = $card4->count();

                $card1 = number_format($card1, 0);
                $card2 = number_format($card2, 0);
                $card3 = number_format($card3, 0);
                $card4 = number_format($card4, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1');
                if ($rq->provincia > 0) $v2 = $v2->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v2 = $v2->where('distrito_id', $rq->distrito);
                $v2 = $v2->count();

                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('visita', '1');
                if ($rq->provincia > 0) $v1 = $v1->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v1 = $v1->where('distrito_id', $rq->distrito);
                $v1 = $v1->count();

                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance'));
            case 'anal2':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1');
                if ($rq->provincia > 0) $v2 = $v2->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v2 = $v2->where('distrito_id', $rq->distrito);
                $v2 = $v2->count();

                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('tipo_doc', 'DNI');
                if ($rq->provincia > 0) $v1 = $v1->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v1 = $v1->where('distrito_id', $rq->distrito);
                $v1 = $v1->count();

                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance'));
            case 'anal3':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1');
                if ($rq->provincia > 0) $v2 = $v2->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v2 = $v2->where('distrito_id', $rq->distrito);
                $v2 = $v2->count();

                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->where(function ($query) {
                        $query->whereRaw("FIND_IN_SET('1',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('2',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('3',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('4',seguro) > 0")
                            ->orWhereNotNull('seguro');
                    });
                if ($rq->provincia > 0) $v1 = $v1->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v1 = $v1->where('distrito_id', $rq->distrito);
                $v1 = $v1->count();

                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance'));
            case 'anal4':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1');
                if ($rq->provincia > 0) $v2 = $v2->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v2 = $v2->where('distrito_id', $rq->distrito);
                $v2 = $v2->count();

                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('cui_atencion', '>', 0);
                if ($rq->provincia > 0) $v1 = $v1->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $v1 = $v1->where('distrito_id', $rq->distrito);
                $v1 = $v1->count();

                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance', 'v1', 'v2'));


            case 'tabla1x':
                function obtenerCriterio($importacion_id, $filtros)
                {
                    return ImporPadronNominal::select(
                        DB::raw('count(*) as pob'),
                        DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                        DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                        DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                        DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                        DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                        DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5')
                    )->where('importacion_id', $importacion_id)
                        ->where($filtros)
                        ->first();
                }

                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;
                $filtrosCriterios = [
                    ['tipo_doc' => 'padron'],  // Criterio 0
                    ['repetido' => '2'],       // Criterio 1
                    function ($q) {            // Criterio 2
                        $q->where('apellido_paterno', '')->orWhere('apellido_materno', '')->orWhere('nombre', '')->orWhereNull('apellido_paterno')->orWhereNull('apellido_materno')->orWhereNull('nombre');
                    },
                    function ($q) {            // Criterio 3
                        $q->whereRaw("seguro = '0' or seguro = '0,'")->orWhereNull('seguro');
                    },
                    ['visita', '!=', '1'],     // Criterio 4
                    ['visita' => '1', 'menor_encontrado', '!=', '1'], // Criterio 5
                    ['cui_atencion' => '0'],   // Criterio 6
                    ['repetido' => '2'],       // Criterio 7 (puede ser redundante)
                    function ($q) {            // Criterio 8
                        $q->where('repetido', '2')->where('region', '!=', 'region_actual');
                    },
                    function ($q) {            // Criterio 9
                        $q->where('apellido_paterno_madre', '')->orWhere('apellido_materno_madre', '')->orWhere('nombres_madre', '')->orWhereNull('apellido_paterno_madre')->orWhereNull('apellido_materno_madre')->orWhereNull('nombres_madre');
                    }
                ];

                $base = Mes::select('id')->get();
                $base[0]->criterio = 'Registro sin Número de Documento(DNI, CNV, CUI)';
                $base[1]->criterio = 'Registro Duplicados del Número de Documento';
                $base[2]->criterio = 'Registro sin Nombre Completos';
                $base[3]->criterio = 'Registro sin Seguro de Salud';
                $base[4]->criterio = 'Registro sin Visitas Domiciliarias';
                $base[5]->criterio = 'Registro de Niños y Niñas Visitados y no Encontrados';
                $base[6]->criterio = 'Registro sin Establecimiento de Atención';
                $base[7]->criterio = 'Registro de Establecimiento de Atención de Otra Región';
                $base[8]->criterio = 'Registro de Establecimiento de salud  de Otro Distrito';
                $base[9]->criterio = 'Registro sin Nombres Completo de la Madre ';
                $base[10]->criterio = 'Registro sin Grado de Instrucción de la Madre ';
                $base[11]->criterio = 'Registro sin Lengua Habitual de la Madre ';
                // Aplicando los criterios a cada elemento del arreglo $base
                foreach ($filtrosCriterios as $index => $filtro) {
                    $resultado = obtenerCriterio($impMaxAnio, $filtro);
                    $base[$index]->total = $resultado->pob;
                    $base[$index]->pob0 = $resultado->pob0;
                    $base[$index]->pob1 = $resultado->pob1;
                    $base[$index]->pob2 = $resultado->pob2;
                    $base[$index]->pob3 = $resultado->pob3;
                    $base[$index]->pob4 = $resultado->pob4;
                    $base[$index]->pob5 = $resultado->pob5;
                }
                $excel = view('salud.PadronNominal.TableroCalidadTabla1excel', compact('base'))->render();
                return response()->json(compact('excel'));

            case 'tabla1':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $base = Mes::select('id')->get();
                $base[0]->criterio = 'Registro sin Número de Documento(DNI, CNV, CUI)';
                $base[1]->criterio = 'Registro Duplicados del Número de Documento';
                $base[2]->criterio = 'Registro sin Nombre Completos';
                $base[3]->criterio = 'Registro sin Seguro de Salud';
                $base[4]->criterio = 'Registro sin Visitas Domiciliarias';
                $base[5]->criterio = 'Registro de Niños y Niñas Visitados y no Encontrados';
                $base[6]->criterio = 'Registro sin Establecimiento de Atención';
                $base[7]->criterio = 'Registro de Establecimiento de Atención de Otra Región';
                $base[8]->criterio = 'Registro de Establecimiento de salud  de Otro Distrito';
                $base[9]->criterio = 'Registro sin Nombres Completo de la Madre ';
                $base[10]->criterio = 'Registro sin Grado de Instrucción de la Madre ';
                $base[11]->criterio = 'Registro sin Lengua Habitual de la Madre ';

                $cri1 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('tipo_doc', 'padron');
                if ($rq->provincia > 0) $cri1 = $cri1->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri1 = $cri1->where('distrito_id', $rq->distrito);
                $cri1 = $cri1->first();

                $base[0]->total = $cri1->pob;
                $base[0]->pob0 = $cri1->pob0;
                $base[0]->pob1 = $cri1->pob1;
                $base[0]->pob2 = $cri1->pob2;
                $base[0]->pob3 = $cri1->pob3;
                $base[0]->pob4 = $cri1->pob4;
                $base[0]->pob5 = $cri1->pob5;

                // $cri2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '2')->count();
                $cri2 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('repetido', '2');
                if ($rq->provincia > 0) $cri2 = $cri2->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri2 = $cri2->where('distrito_id', $rq->distrito);
                $cri2 = $cri2->first();
                $base[1]->total = $cri2->pob;
                $base[1]->pob0 = $cri2->pob0;
                $base[1]->pob1 = $cri2->pob1;
                $base[1]->pob2 = $cri2->pob2;
                $base[1]->pob3 = $cri2->pob3;
                $base[1]->pob4 = $cri2->pob4;
                $base[1]->pob5 = $cri2->pob5;
                // $cri3 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where(function ($q) {
                //     $q->where('apellido_paterno', '')->orWhere('apellido_materno', '')->orWhere('nombre', '')->orWhereNull('apellido_paterno')->orWhereNull('apellido_materno')->orWhereNull('nombre');
                // })->count();

                $cri3 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where(function ($q) {
                    $q->where('apellido_paterno', '')->orWhere('apellido_materno', '')->orWhere('nombre', '')->orWhereNull('apellido_paterno')->orWhereNull('apellido_materno')->orWhereNull('nombre');
                });
                if ($rq->provincia > 0) $cri3 = $cri3->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri3 = $cri3->where('distrito_id', $rq->distrito);
                $cri3 = $cri3->first();

                $base[2]->total = $cri3->pob;
                $base[2]->pob0 = $cri3->pob0;
                $base[2]->pob1 = $cri3->pob1;
                $base[2]->pob2 = $cri3->pob2;
                $base[2]->pob3 = $cri3->pob3;
                $base[2]->pob4 = $cri3->pob4;
                $base[2]->pob5 = $cri3->pob5;

                // $cri4 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where(function ($q) {
                //     $q->whereRaw("seguro = '0' or seguro = '0,'")->orWhereNull('seguro');
                // })->count();

                $cri4 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where(function ($q) {
                    $q->whereRaw("seguro = '0' or seguro = '0,'")->orWhereNull('seguro');
                });
                if ($rq->provincia > 0) $cri4 = $cri4->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri4 = $cri4->where('distrito_id', $rq->distrito);
                $cri4 = $cri4->first();

                $base[3]->total = $cri4->pob;
                $base[3]->pob0 = $cri4->pob0;
                $base[3]->pob1 = $cri4->pob1;
                $base[3]->pob2 = $cri4->pob2;
                $base[3]->pob3 = $cri4->pob3;
                $base[3]->pob4 = $cri4->pob4;
                $base[3]->pob5 = $cri4->pob5;

                // $cri5 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('visita', '!=', '1')->count();
                $cri5 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('visita', '!=', '1');
                if ($rq->provincia > 0) $cri5 = $cri5->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri5 = $cri5->where('distrito_id', $rq->distrito);
                $cri5 = $cri5->first();

                $base[4]->total = $cri5->pob;
                $base[4]->pob0 = $cri5->pob0;
                $base[4]->pob1 = $cri5->pob1;
                $base[4]->pob2 = $cri5->pob2;
                $base[4]->pob3 = $cri5->pob3;
                $base[4]->pob4 = $cri5->pob4;
                $base[4]->pob5 = $cri5->pob5;

                // $cri6 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('visita', '1')->where('menor_encontrado', '!=', '1')->count();
                $cri6 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('visita', '1')->where('menor_encontrado', '!=', '1');
                if ($rq->provincia > 0) $cri6 = $cri6->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri6 = $cri6->where('distrito_id', $rq->distrito);
                $cri6 = $cri6->first();

                $base[5]->total = $cri6->pob;
                $base[5]->pob0 = $cri6->pob0;
                $base[5]->pob1 = $cri6->pob1;
                $base[5]->pob2 = $cri6->pob2;
                $base[5]->pob3 = $cri6->pob3;
                $base[5]->pob4 = $cri6->pob4;
                $base[5]->pob5 = $cri6->pob5;

                // $cri7 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('cui_atencion', '0')->count();
                $cri7 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('cui_atencion', '0');
                if ($rq->provincia > 0) $cri7 = $cri7->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri7 = $cri7->where('distrito_id', $rq->distrito);
                $cri7 = $cri7->first();

                $base[6]->total = $cri7->pob;
                $base[6]->pob0 = $cri7->pob0;
                $base[6]->pob1 = $cri7->pob1;
                $base[6]->pob2 = $cri7->pob2;
                $base[6]->pob3 = $cri7->pob3;
                $base[6]->pob4 = $cri7->pob4;
                $base[6]->pob5 = $cri7->pob5;

                // $cri8 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '2')->count();
                $cri8 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('repetido', '2');
                if ($rq->provincia > 0) $cri8 = $cri8->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri8 = $cri8->where('distrito_id', $rq->distrito);
                $cri8 = $cri8->first();
                $base[7]->total = $cri8->pob;
                $base[7]->pob0 = $cri8->pob0;
                $base[7]->pob1 = $cri8->pob1;
                $base[7]->pob2 = $cri8->pob2;
                $base[7]->pob3 = $cri8->pob3;
                $base[7]->pob4 = $cri8->pob4;
                $base[7]->pob5 = $cri8->pob5;

                // $cri9 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '2')->count();
                $cri9 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)->where('repetido', '2');
                if ($rq->provincia > 0) $cri9 = $cri9->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri9 = $cri9->where('distrito_id', $rq->distrito);
                $cri9 = $cri9->first();
                $base[8]->total = $cri9->pob;
                $base[8]->pob0 = $cri9->pob0;
                $base[8]->pob1 = $cri9->pob1;
                $base[8]->pob2 = $cri9->pob2;
                $base[8]->pob3 = $cri9->pob3;
                $base[8]->pob4 = $cri9->pob4;
                $base[8]->pob5 = $cri9->pob5;

                // $cri10 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where(function ($q) {
                //     $q->where('apellido_paterno_madre', '')->orWhere('apellido_materno_madre', '')->orWhere('nombres_madre', '')->orWhereNull('apellido_paterno_madre')->orWhereNull('apellido_materno_madre')->orWhereNull('nombres_madre');
                // })->count();
                $cri10 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)
                    ->where(function ($q) {
                        $q->where('apellido_paterno_madre', '')->orWhere('apellido_materno_madre', '')->orWhere('nombres_madre', '')->orWhereNull('apellido_paterno_madre')->orWhereNull('apellido_materno_madre')->orWhereNull('nombres_madre');
                    });
                if ($rq->provincia > 0) $cri10 = $cri10->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri10 = $cri10->where('distrito_id', $rq->distrito);
                $cri10 = $cri10->first();
                $base[9]->total = $cri10->pob;
                $base[9]->pob0 = $cri10->pob0;
                $base[9]->pob1 = $cri10->pob1;
                $base[9]->pob2 = $cri10->pob2;
                $base[9]->pob3 = $cri10->pob3;
                $base[9]->pob4 = $cri10->pob4;
                $base[9]->pob5 = $cri10->pob5;

                // $cri11 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where(function ($q) {
                //     $q->where('grado_instruccion', '')->orWhereNull('grado_instruccion');
                // })->count();
                $cri11 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)
                    ->where(function ($q) {
                        $q->where('grado_instruccion', '')->orWhereNull('grado_instruccion');
                    });
                if ($rq->provincia > 0) $cri11 = $cri11->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri11 = $cri11->where('distrito_id', $rq->distrito);
                $cri11 = $cri11->first();
                $base[10]->total = $cri11->pob;
                $base[10]->pob0 = $cri11->pob0;
                $base[10]->pob1 = $cri11->pob1;
                $base[10]->pob2 = $cri11->pob2;
                $base[10]->pob3 = $cri11->pob3;
                $base[10]->pob4 = $cri11->pob4;
                $base[10]->pob5 = $cri11->pob5;

                // $cri12 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where(function ($q) {
                //     $q->where('lengua_madre', '')->orWhereNull('lengua_madre');
                // })->count();
                $cri12 = ImporPadronNominal::select(
                    DB::raw('count(*) as pob'),
                    DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                    DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                    DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                    DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                    DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                    DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                )->where('importacion_id', $impMaxAnio)
                    ->where(function ($q) {
                        $q->where('lengua_madre', '')->orWhereNull('lengua_madre');
                    });
                if ($rq->provincia > 0) $cri12 = $cri12->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $cri12 = $cri12->where('distrito_id', $rq->distrito);
                $cri12 = $cri12->first();
                $base[11]->total = $cri12->pob;
                $base[11]->pob0 = $cri12->pob0;
                $base[11]->pob1 = $cri12->pob1;
                $base[11]->pob2 = $cri12->pob2;
                $base[11]->pob3 = $cri12->pob3;
                $base[11]->pob4 = $cri12->pob4;
                $base[11]->pob5 = $cri12->pob5;

                // $foot = [];
                // if ($base->count() > 0) {
                //     $foot = clone $base[0];
                // }
                $excel = view('salud.PadronNominal.TableroCalidadTabla1excel', compact('base', 'impMaxAnio'))->render();
                return response()->json(compact('excel'));

            case 'tabla2':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $base = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->select(
                        DB::raw('case when seguro_id=1 then "SIS" when seguro_id=2 then "ESSALUD" when seguro_id=3 then "SANIDAD" when seguro_id=4 then "PRIVADO" else "NINGUNO" end as nseguro'),
                        DB::raw('count(*) as pob'),
                        DB::raw('sum(if(genero="M",1,0)) as pobm'),
                        DB::raw('sum(if(genero="F",1,0)) as pobf'),
                        DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                        DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                        DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                        DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                        DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                        DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                        DB::raw('sum(if(tipo_doc="DNI",1,0)) as dni'),
                        DB::raw('sum(if(tipo_doc="CNV",1,0)) as seguro'),
                        DB::raw('sum(if(tipo_doc="CUI",1,0)) as programa')
                    );
                if ($rq->provincia > 0) $base = $base->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $base = $base->where('distrito_id', $rq->distrito);
                $base = $base->groupBy('nseguro')->orderByRaw('field(nseguro,"SIS","ESSALUD","SANIDAD","PRIVADO","NINGUNO")')->get();

                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->pob = 0;
                    $foot->pobm = 0;
                    $foot->pobf = 0;
                    $foot->pob0 = 0;
                    $foot->pob1 = 0;
                    $foot->pob2 = 0;
                    $foot->pob3 = 0;
                    $foot->pob4 = 0;
                    $foot->pob5 = 0;
                    $foot->dni = 0;
                    $foot->seguro = 0;
                    $foot->programa = 0;
                    foreach ($base as $key => $value) {
                        $foot->pob += $value->pob;
                        $foot->pobm += $value->pobm;
                        $foot->pobf += $value->pobf;
                        $foot->pob0 += $value->pob0;
                        $foot->pob1 += $value->pob1;
                        $foot->pob2 += $value->pob2;
                        $foot->pob3 += $value->pob3;
                        $foot->pob4 += $value->pob4;
                        $foot->pob5 += $value->pob5;
                        $foot->dni += $value->dni;
                        $foot->seguro += $value->seguro;
                        $foot->programa += $value->programa;
                    }
                }

                $excel = view('salud.PadronNominal.TableroCalidadTabla2excel', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));

            case 'tabla3':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $base = ImporPadronNominal::join('par_ubigeo as u', 'u.id', '=', 'sal_impor_padron_nominal.distrito_id')
                    ->where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->select(
                        'u.nombre as distrito',
                        DB::raw('count(*) as pob'),
                        DB::raw('sum(if(genero="M",1,0)) as pobm'),
                        DB::raw('sum(if(genero="F",1,0)) as pobf'),
                        DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                        DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                        DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                        DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                        DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                        DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                        DB::raw('sum(if(tipo_doc="DNI",1,0)) as dni'),
                        DB::raw('sum(case when FIND_IN_SET("1", seguro) > 0 then 1 
                              when FIND_IN_SET("2", seguro) > 0 then 1 
                              when FIND_IN_SET("3", seguro) > 0 then 1 
                              when FIND_IN_SET("4", seguro) > 0 then 1 
                              else 0 end) as seguro'),
                        DB::raw('sum(case when FIND_IN_SET("1", programa_social) > 0 then 1 
                              when FIND_IN_SET("2", programa_social) > 0 then 1 
                              when FIND_IN_SET("5", programa_social) > 0 then 1 
                              when FIND_IN_SET("5", programa_social) > 0 then 1 
                              when FIND_IN_SET("7", programa_social) > 0 then 1 
                              when FIND_IN_SET("8", programa_social) > 0 then 1 
                              else 0 end) as programa')
                    )->groupBy('distrito')->orderBy('ubigeo')->get();

                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->pob = 0;
                    $foot->pobm = 0;
                    $foot->pobf = 0;
                    $foot->pob0 = 0;
                    $foot->pob1 = 0;
                    $foot->pob2 = 0;
                    $foot->pob3 = 0;
                    $foot->pob4 = 0;
                    $foot->pob5 = 0;
                    $foot->dni = 0;
                    $foot->seguro = 0;
                    $foot->programa = 0;
                    foreach ($base as $key => $value) {
                        $foot->pob += $value->pob;
                        $foot->pobm += $value->pobm;
                        $foot->pobf += $value->pobf;
                        $foot->pob0 += $value->pob0;
                        $foot->pob1 += $value->pob1;
                        $foot->pob2 += $value->pob2;
                        $foot->pob3 += $value->pob3;
                        $foot->pob4 += $value->pob4;
                        $foot->pob5 += $value->pob5;
                        $foot->dni += $value->dni;
                        $foot->seguro += $value->seguro;
                        $foot->programa += $value->programa;
                    }
                }

                $excel = view('salud.PadronNominal.TableroCalidadTabla3excel', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));

            default:
                # code...
                return [];
        }
    }


    public function criterio1_red($importacion, $criterio)
    {
        switch ($criterio) {
            case '1':
                $sql = "SELECT distinct r.id, r.nombre from (select distinct cui_atencion as eess from sal_impor_padron_nominal where importacion_id=? and cui_atencion != 0 AND tipo_doc='Padron' order by eess asc) as aeess 
                join sal_establecimiento as e on e.cod_unico = aeess.eess join sal_microred as m on m.id = e.microrred_id join sal_red as r on r.id=m.red_id order by e.cod_disa";
                return DB::select($sql, [$importacion]);

            default:
                return [];
        }
    }

    public function criterio1_microred($importacion, $red, $criterio)
    {

        switch ($criterio) {
            case '1':
                $sql = "SELECT distinct m.id, m.nombre from (select distinct cui_atencion as eess from sal_impor_padron_nominal where importacion_id=? and cui_atencion != 0 AND tipo_doc='Padron' order by eess asc) as aeess
                join sal_establecimiento as e on e.cod_unico = aeess.eess join sal_microred as m on m.id = e.microrred_id join sal_red as r on r.id=m.red_id where r.id = ? order by m.cod_disa, red_id";
                // return response()->json(DB::select($sql, [$importacion, $red]));
                return DB::select($sql, [$importacion, $red]);

            default:
                return [];
        }
    }

    public function criterio1_establecimiento($importacion_id, $red, $microred, $criterio)
    {
        switch ($criterio) {
            case '1':
                $sql = "SELECT distinct e.id, e.nombre_establecimiento as nombre from (select distinct cui_atencion as eess from sal_impor_padron_nominal where importacion_id=? and cui_atencion != 0 AND tipo_doc='Padron' order by eess asc) as aeess
                join sal_establecimiento as e on e.cod_unico = aeess.eess join sal_microred as m on m.id = e.microrred_id join sal_red as r on r.id=m.red_id where r.id = ? and m.id = ? order by e.cod_disa, red_id, microrred_id ";
                return DB::select($sql, [$importacion_id, $red, $microred]);

            default:
                return [];
        }
    }


    public function tablerocalidadcriterio($importacion, $criterio)
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        $anio = 2024;
        // $sql1 = "SELECT * FROM par_importacion
        // WHERE fuenteimportacion_id = ? AND estado = 'PR'
        //     AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
        //         SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
        //         WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
        //     )
        // ORDER BY fechaActualizacion DESC limit 1";
        // $query1 = DB::select($sql1, [$fuente, $fuente, $anio]);
        // $impMaxAnio = $query1 ? $query1[0]->id : 0;

        // $criterio = 1;
        $title[0] = 'Registros sin Número de Documento (DNI, CNV, CUI) del Menor';
        $title[1] = 'Registro Duplicados del Número de Documento';
        $title[2] = 'Registro sin Nombre Completos';
        $title[3] = 'Registro sin Seguro de Salud';
        $title[4] = 'Registro sin Visitas Domiciliarias';
        $title[5] = 'Registro de Niños y Niñas Visitados y no Encontrados';
        $title[6] = 'Registro sin Establecimiento de Atención';
        $title[7] = 'Registro de Establecimiento de Atención de Otra Región';
        $title[8] = 'Registro de Establecimiento de salud  de Otro Distrito';
        $title[9] = 'Registro sin Nombres Completo de la Madre ';
        $title[10] = 'Registro sin Grado de Instrucción de la Madre ';
        $title[11] = 'Registro sin Lengua Habitual de la Madre ';

        $red = $this->criterio1_red($importacion, $criterio);
        // return $this->criterio1_microred($impMaxAnio, 10, 1);
        // return $this->criterio1_establecimiento($impMaxAnio, 10,41,1);

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE); //nexus $imp3
        $mes = Mes::find($imp->mes);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $mes->mes . ' del ' . $imp->anio;

        return view('salud.PadronNominal.TableroCalidadCriterio', compact('importacion', 'criterio', 'actualizado', 'red', 'title'));
    }

    public function tablerocalidadcriteriolistar(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        switch ($rq->criterio) {
            case '1':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('tipo_doc', 'padron')->get();
                break;
            case '2':
                // $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->whereIn('repetido', [1, 2, 3])->groupBy('num_doc')->havingRaw('count(distinct repetido) = 3')->get();
                $repetido2 = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('repetido', 2)->pluck('num_doc');
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
                    ->whereIn('num_doc', $repetido2)
                    ->orderBy('num_doc')->get();
                break;
            case '3':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
                    ->where('importacion_id', $rq->importacion)->where(function ($q) {
                        $q->where('apellido_paterno', '')->orWhere('apellido_materno', '')->orWhere('nombre', '')->orWhereNull('apellido_paterno')->orWhereNull('apellido_materno')->orWhereNull('nombre');
                    })
                    ->get();
                break;
            case '4':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
                    ->where(function ($q) {
                        $q->whereRaw("seguro = '0' or seguro = '0,'")->orWhereNull('seguro');
                    })
                    ->get();
                break;
            case '5':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('visita', '!=', '1')->get();
                break;
            case '6':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('visita', '1')->where('menor_encontrado', '!=', '1')->get();
                break;
            case '7':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('cui_atencion', '0')->get();
                break;
            case '8':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('tipo_doc', 'padron')->get();
                break;
            case '9':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)->where('tipo_doc', 'padron')->get();
                break;
            case '10':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
                    ->where(function ($q) {
                        $q->where('apellido_paterno_madre', '')->orWhere('apellido_materno_madre', '')->orWhere('nombres_madre', '')->orWhereNull('apellido_paterno_madre')->orWhereNull('apellido_materno_madre')->orWhereNull('nombres_madre');
                    })
                    ->get();
                break;
            case '11':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
                    ->where(function ($q) {
                        $q->where('grado_instruccion', '')->orWhereNull('grado_instruccion');
                    })
                    ->get();
                break;
            case '12':
                $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
                    ->where(function ($q) {
                        $q->where('lengua_madre', '')->orWhereNull('lengua_madre');
                    })
                    ->get();
                break;
            default:
                $query = [];
                break;
        }

        $data = [];
        foreach ($query as $key => $value) {
            $dis = Ubigeo::where('codigo', $value->ubigeo)->first();
            $eess = Establecimiento::where('cod_unico', $value->cui_atencion)->first();
            // $boton = '';
            // if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || in_array(session('perfil_administrador_id'), [3, 8, 9, 10, 11])) {
            //     $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';
            // }
            // $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                $value->padron,
                $value->tipo_doc != 'Padron' ? $value->tipo_doc : '',
                $value->tipo_doc != 'Padron' ? $value->num_doc : '',
                $value->apellido_paterno . ' ' . $value->apellido_materno . ', ' . $value->nombre,
                date('d/m/Y', strtotime($value->fecha_nacimiento)),
                $dis->nombre, //$value->ubigeo,
                $value->centro_poblado_nombre,
                $eess ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '',
                $eess ? $eess->nombre_establecimiento : ''
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

    public function tablerocalidadcriteriofind($importacion, $padron)
    {
        return  $data = ImporPadronNominal::where('importacion_id', $importacion)->where('padron', $padron)->first();
    }
}