<?php

namespace App\Http\Controllers\Salud;

use App\Exports\TableroCalidadCriterioExport;
use App\Exports\TableroCalidadExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CalidadCriterio;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\ImporPadronNominal;
use App\Models\Salud\PadronCalidad;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Salud\EstablecimientoRepositorio;
use App\Repositories\Salud\PadronNominalRepositorio;
use App\Repositories\Salud\PadronNominalRepositorioSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
                $base[0]->criterio = 'Registro de Niños y Niñas sin Número de Documento(DNI, CNV, CUI)';
                $base[1]->criterio = 'Registro de Niños y Niñas Duplicados del Número de Documento';
                $base[2]->criterio = 'Registro de Niños y Niñas sin Nombre Completos';
                $base[3]->criterio = 'Registro de Niños y Niñas sin Seguro de Salud';
                $base[4]->criterio = 'Registro de Niños y Niñas sin Visitas Domiciliarias';
                $base[5]->criterio = 'Registro de Niños y Niñas Visitados y no Encontrados';
                $base[6]->criterio = 'Registro de Niños y Niñas sin Establecimiento de Atención';
                $base[7]->criterio = 'Registro de Niños y Niñas con Establecimiento de Atención de Otra Región';
                $base[8]->criterio = 'Registro de Niños y Niñas con Establecimiento de salud  de Otro Distrito';
                $base[9]->criterio = 'Registro de Niños y Niñas sin Nombres Completo de la Madre ';
                $base[10]->criterio = 'Registro de Niños y Niñas sin Grado de Instrucción de la Madre ';
                $base[11]->criterio = 'Registro de Niños y Niñas sin Lengua Habitual de la Madre ';
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

            case 'tabla1y':
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

            case 'tabla1':

                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $base = CalidadCriterio::where('importacion_id', $impMaxAnio)
                    ->join('sal_calidad_criterio_nombres as c', 'c.id', '=', 'sal_calidad_criterio.criterio')
                    ->select(
                        'c.nombre as criterio',
                        DB::raw('count(*) as total'),
                        DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                        DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                        DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                        DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                        DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                        DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                    );
                if ($rq->provincia > 0) $base = $base->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $base = $base->where('distrito_id', $rq->distrito);
                $base = $base->groupBy('criterio', 'c.nombre')->get();

                // foreach ($base as $key => $value) {
                //     $value->criterio = $cri[$value->criterio - 1] ?? '';
                // }

                $excel = view('salud.PadronNominal.TableroCalidadTabla1', compact('base', 'impMaxAnio'))->render();
                return response()->json(compact('base', 'excel'));

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

                $excel = view('salud.PadronNominal.TableroCalidadTabla2', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));

            case 'tabla3':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $base = ImporPadronNominal::join('par_ubigeo as u', 'u.id', '=', 'sal_impor_padron_nominal.distrito_id')
                    ->where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->select(
                        'sal_impor_padron_nominal.ubigeo',
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
                    )->groupBy('ubigeo', 'distrito')->orderBy('ubigeo')->get();

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

                $excel = view('salud.PadronNominal.TableroCalidadTabla3', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            case 'tabla3_1':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio);

                $base = ImporPadronNominal::join('par_ubigeo as u', 'u.id', '=', 'sal_impor_padron_nominal.distrito_id')
                    ->where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('ubigeo', $rq->ubigeo)
                    ->select(
                        'centro_poblado',
                        'centro_poblado_nombre',
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
                    )->groupBy('centro_poblado', 'centro_poblado_nombre')->orderBy('centro_poblado_nombre')->get();

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

                $excel = view('salud.PadronNominal.TableroCalidadTabla3_1', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            default:
                # code...
                return [];
        }
    }

    public function tablerocalidadreporteexport($div, $importacion, $anio, $mes, $provincia, $distrito, $ubigeo)
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        switch ($div) {
            case 'tabla2':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $anio);
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
                if ($provincia > 0) $base = $base->where('provincia_id', $provincia);
                if ($distrito > 0) $base = $base->where('distrito_id', $distrito);
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

                return compact('base', 'foot');

            case 'tabla3':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $anio);

                $base = ImporPadronNominal::join('par_ubigeo as u', 'u.id', '=', 'sal_impor_padron_nominal.distrito_id')
                    ->where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->select(
                        'sal_impor_padron_nominal.ubigeo',
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
                    )->groupBy('ubigeo', 'distrito')->orderBy('ubigeo')->get();
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

                return compact('base', 'foot');

            case 'tabla3_1':
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $anio);

                $base = ImporPadronNominal::join('par_ubigeo as u', 'u.id', '=', 'sal_impor_padron_nominal.distrito_id')
                    ->where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('ubigeo', $ubigeo)
                    ->select(
                        'centro_poblado',
                        'centro_poblado_nombre',
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
                    )->groupBy('centro_poblado', 'centro_poblado_nombre')->orderBy('centro_poblado_nombre')->get();

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

                return compact('base', 'foot');
            default:
                # code...
                return [];
        }
    }

    public function tablerocalidaddownload($div, $importacion, $anio, $mes, $provincia, $distrito, $ubigeo)
    {
        switch ($div) {
            case 'tabla2':
                $name = 'Población del Padrón Nominal de niños y niñas menores de 6 años por Seguro De Salud, Según Sexo y Edades.xlsx';
                break;
            case 'tabla3':
                $name = 'Población de niños y niñas menos de 6 años por distrito, segun sexo y edades.xlsx';
                break;
            case 'tabla3_1':
                $name = 'Población de niños y niñas menos de 6 años por Centro Poblado, segun sexo y edades.xlsx';
                break;
            default:
                $name = '';
                break;
        }
        return Excel::download(new TableroCalidadExport($div, $importacion, $anio, $mes, $provincia, $distrito, $ubigeo), $name);
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

    public function criterio_red($importacion, $criterio)
    {
        $query = CalidadCriterio::distinct()->select('red_id as id')->where('importacion_id', $importacion)->where('criterio', $criterio)->whereNotNull('red_id')->get();
        foreach ($query as $key => $value) {
            $value->nombre = DB::table('sal_red')->where('id', $value->id)->first()->nombre;
        }
        return $query;
    }

    public function criterio_microred($importacion, $red, $criterio)
    {
        $query = CalidadCriterio::distinct()->select('microred_id as id')->where('importacion_id', $importacion)->where('criterio', $criterio)->where('red_id', $red)->whereNotNull('microred_id')->get();
        foreach ($query as $key => $value) {
            $value->nombre = DB::table('sal_microred')->where('id', $value->id)->first()->nombre;
        }
        return $query;
    }

    public function criterio_establecimiento($importacion, $red, $microred, $criterio)
    {
        $query = CalidadCriterio::distinct()->select('establecimiento_id as id')->where('importacion_id', $importacion)->where('criterio', $criterio)
            ->where('red_id', $red)->where('microred_id', $microred)->whereNotNull('establecimiento_id')->get();
        foreach ($query as $key => $value) {
            $value->nombre = DB::table('sal_establecimiento')->where('id', $value->id)->first()->nombre_establecimiento;
        }
        return $query;
    }

    public function criterio_edades($importacion, $criterio)
    {
        $query = CalidadCriterio::select(
            DB::raw('case when tipo_edad in("D","M") then 1 else edad+1 end as edades_id'),
            DB::raw('case when tipo_edad in("D","M") then "MENOR DE 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades')
        )
            ->where('importacion_id', $importacion)->where('criterio', $criterio)
            ->groupBy('edades_id', 'edades')->get();
        return $query;
    }

    public function criterio_provincia($importacion, $criterio, $edad)
    {
        $query = CalidadCriterio::select('provincia_id')
            ->where('importacion_id', $importacion)->where('criterio', $criterio);
        if ($edad > 0) {
            if ($edad == 1) {
                $query = $query->where('tipo_edad', '!=', 'A');
            } else {
                $query = $query->where('tipo_edad', 'A')->where('edad', $edad - 1);
            }
        }
        $query = $query->groupBy('provincia_id')->get();
        foreach ($query as $key => $value) {
            $value->provincia = Ubigeo::find($value->provincia_id)->nombre;
        }
        return $query;
    }

    public function criterio_distrito($importacion, $criterio, $edad, $provincia)
    {
        $query = CalidadCriterio::select('distrito_id')
            ->where('importacion_id', $importacion)->where('criterio', $criterio);
        if ($edad > 0) {
            if ($edad == 1) {
                $query = $query->where('tipo_edad', '!=', 'A');
            } else {
                $query = $query->where('tipo_edad', 'A')->where('edad', $edad - 1);
            }
        }
        if ($provincia) {
            $query = $query->where('provincia_id', $provincia);
        }
        $query = $query->groupBy('distrito_id')->get();
        foreach ($query as $key => $value) {
            $value->distrito = Ubigeo::find($value->distrito_id)->nombre;
        }
        return $query;
    }

    public function tablerocalidadcriterio($importacion, $criterio)
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        $anio = 2024;
        $title = DB::table('sal_calidad_criterio_nombres')->find($criterio)->nombre;

        $edades = $this->criterio_edades($importacion, $criterio);
        // return $this->criterio_provincia($importacion, $criterio, 6);
        // return $this->criterio_distrito($importacion,  $criterio, 1, 35);

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE); //nexus $imp3
        $mes = Mes::find($imp->mes);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $mes->mes . ' del ' . $imp->anio;

        return view('salud.PadronNominal.TableroCalidadCriterio', compact('importacion', 'criterio', 'actualizado', 'edades', 'title'));
    }

    public function tablerocalidadcriteriolistar(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $seguro = [0 => 'NINGUNO', 1 => 'SIS', 2 => 'ESSALUD', 3 => 'SANIDAD', 4 => 'PRIVADO'];
        $programa = [0 => 'NINGUNO', 1 => 'PIN', 2 => 'PVL', 4 => 'JUNTOS', 5 => 'QALIWARMA', 7 => 'CUNA+ SCD', 8 => 'CUNA+ SAF'];

        $query = CalidadCriterio::where('importacion_id', $rq->importacion)->where('criterio', $rq->criterio);
        if ($rq->edades > 0) {
            if ($rq->edades == 1) {
                $query = $query->whereIn('tipo_edad', ['D', 'M']);
            } else {
                $query = $query->where('tipo_edad', 'A')->where('edad', $rq->edades - 1);
            }
        }
        if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
        if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);

        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;

        $query = $query->skip($start)->take($length)->get();

        $ubigeos = Ubigeo::whereIn('codigo', $query->pluck('ubigeo')->toArray())->get()->keyBy('codigo');
        $establecimientos = Establecimiento::whereIn('cod_unico', $query->pluck('cui_atencion')->toArray())->get()->keyBy('cod_unico');

        $sim = ['D' => 'DÍAS', 'M' => 'MESES', 'A' => 'AÑOS'];
        $data = [];

        foreach ($query as $key => $value) {
            $dis = $ubigeos[$value->ubigeo] ?? null;
            $eess = $establecimientos[$value->cui_atencion] ?? null;

            if (in_array($rq->criterio, range(1, 9))) {
                $data[] = [
                    $key + 1,
                    $value->padron,
                    $value->tipo_doc != 'Padron' ? $value->tipo_doc : '',
                    $value->tipo_doc != 'Padron' ? $value->num_doc : '',
                    $value->apellido_paterno . ' ' . $value->apellido_materno . ', ' . $value->nombre,
                    $value->edad . ' ' . ($sim[$value->tipo_edad] ?? ''),
                    $seguro[$value->seguro_id] ?? '',
                    $value->visita == 1 ? 'SI' : 'NO',
                    $value->encontrado == 1 ? 'SI' : 'NO',
                    $dis ? $dis->nombre : '',
                    $eess ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '',
                    $eess ? $eess->nombre_establecimiento : '',
                ];
            } else {
                $data[] = [
                    $key + 1,
                    $value->padron,
                    $value->tipo_doc_madre,
                    $value->num_doc_madre,
                    $value->apellido_paterno_madre . ' ' . $value->apellido_materno_madre . ', ' . $value->nombres_madre,
                    $value->celular_madre,
                    $value->grado_instruccion,
                    $value->lengua_madre,
                    $dis ? $dis->nombre : '',
                    $eess ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '',
                    $eess ? $eess->nombre_establecimiento : '',
                ];
            }
        }

        $result = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        return response()->json($result);
    }

    public function tablerocalidadcriterioreporte(Request $rq)
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        switch ($rq->div) {
            case 'anal1':
                $data = CalidadCriterio::select(
                    DB::raw('case when tipo_edad in("D","M") then 1 else edad+1 end as edades_id'),
                    DB::raw('case when tipo_edad in("D","M") then "< 1 AÑO" when tipo_edad="A" AND edad=1 then "1 AÑO" else concat(edad," AÑOS") end as edades'),
                    DB::raw('count(*) as total'),
                )
                    ->where('importacion_id', $rq->importacion)->where('criterio', $rq->criterio)
                    ->groupBy('edades_id', 'edades')->get();

                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'Cantidad';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->edades;
                    $info['serie'][0]['data'][] = (int)$value->total;
                }
                return response()->json(compact('info'));
            case 'anal2':
                $data = CalidadCriterio::from('sal_calidad_criterio as cc')->join('par_ubigeo as pro', 'pro.id', '=', 'cc.provincia_id')->select(
                    DB::raw('pro.nombre as provincia'),
                    DB::raw('count(*) as total'),
                )
                    ->where('importacion_id', $rq->importacion)->where('criterio', $rq->criterio);
                if ($rq->edades > 0) {
                    if ($rq->edades == 1) {
                        $data = $data->whereIn('tipo_edad', ['D', 'M']);
                    } else {
                        $data = $data->where('tipo_edad', 'A')->where('edad', $rq->edades - 1);
                    }
                }
                $data = $data->groupBy('provincia')->get();

                $info['categoria'] = [];
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'Cantidad';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = '' . $value->provincia;
                    $info['serie'][0]['data'][] = (int)$value->total;
                }
                return response()->json(compact('info'));

            default:
                # code...
                return [];
        }
    }

    public function tablerocalidadcriteriolistar_xx(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $seguro = [0 => 'NINGUNO', 1 => 'SIS', 2 => 'ESSALUD', 3 => 'SANIDAD', 4 => 'PRIVADO'];
        $programa = [0 => 'NINGUNO', 1 => 'PIN', 2 => 'PVL', 4 => 'JUNTOS', 5 => 'QALIWARMA', 7 => 'CUNA+ SCD', 8 => 'CUNA+ SAF'];

        $query = CalidadCriterio::where('importacion_id', $rq->importacion)->where('criterio', $rq->criterio);
        if ($rq->establecimiento > 0) $query = $query->where('establecimiento_id', $rq->establecimiento);
        if ($rq->microred > 0) $query = $query->where('microred_id', $rq->microred);
        if ($rq->red > 0) $query = $query->where('red_id', $rq->red);
        $query = $query->get();

        $data = [];
        if (
            $rq->criterio == 1 ||
            $rq->criterio == 2 ||
            $rq->criterio == 3 ||
            $rq->criterio == 4 ||
            $rq->criterio == 5 ||
            $rq->criterio == 6 ||
            $rq->criterio == 7 ||
            $rq->criterio == 8 ||
            $rq->criterio == 9
        ) {
            foreach ($query as $key => $value) {
                $dis = Ubigeo::where('codigo', $value->ubigeo)->first();
                $eess = Establecimiento::where('cod_unico', $value->cui_atencion)->first();
                $sim = ['D' => 'DÍAS', 'M' => 'MESES', 'A' => 'AÑOS'];
                $data[] = array(
                    $key + 1,
                    $value->padron,
                    $value->tipo_doc != 'Padron' ? $value->tipo_doc : '',
                    $value->tipo_doc != 'Padron' ? $value->num_doc : '',
                    $value->apellido_paterno . ' ' . $value->apellido_materno . ', ' . $value->nombre,
                    $value->edad . ' ' . $sim[$value->tipo_edad] ?? '', //date('d/m/Y', strtotime($value->fecha_nacimiento)),
                    $dis->nombre, //$value->ubigeo,
                    $value->centro_poblado_nombre,
                    $eess ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '',
                    $eess ? $eess->nombre_establecimiento : '',
                    $seguro[$value->seguro_id] ?? '',
                    $value->visita == 1 ? 'SI' : 'NO',
                    $value->encontrado == 1 ? 'SI' : 'NO',
                );
            }
        } else {
            foreach ($query as $key => $value) {
                $dis = Ubigeo::where('codigo', $value->ubigeo)->first();
                $eess = Establecimiento::where('cod_unico', $value->cui_atencion)->first();

                $data[] = array(
                    $key + 1,
                    $value->padron,
                    $value->tipo_doc != 'Padron' ? $value->num_doc : '',
                    $value->apellido_paterno . ' ' . $value->apellido_materno . ', ' . $value->nombre,
                    $dis->nombre, //$value->ubigeo,
                    $value->tipo_doc_madre,
                    $value->num_doc_madre,
                    $value->apellido_paterno_madre . ' ' . $value->apellido_materno_madre . ', ' . $value->nombres_madre,
                    $value->celular_madre,
                    $value->grado_instruccion,
                    $value->lengua_madre,
                );
            }
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }

    public function tablerocalidadcriteriofind1($importacion, $padron)
    {
        $seguro = [0 => 'NINGUNO', 1 => 'SIS', 2 => 'ESSALUD', 3 => 'SANIDAD', 4 => 'PRIVADO'];
        $programa = [0 => 'NINGUNO', 1 => 'PIN', 2 => 'PVL', 4 => 'JUNTOS', 5 => 'QALIWARMA', 7 => 'CUNA+ SCD', 8 => 'CUNA+ SAF'];
        $data = CalidadCriterio::where('importacion_id', $importacion)->where('padron', $padron)->first();
        $data->centro_poblado_nombre = !empty($data->centro_poblado_nombre) ? explode(', ', $data->centro_poblado_nombre)[0] : null;
        $data->seguro = $seguro[$data->seguro_id] ?? 'NINGUNO';
        $programaaux = null;
        if (!empty($data->programa_social)) {
            $programaIds = explode(',', trim($data->programa_social, ','));
            $programaaux = array_map(function ($id) use ($programa) {
                return isset($programa[$id]) ? $programa[$id] : null;
            }, $programaIds);

            $programaaux = implode(', ', array_filter($programaaux));
        } else {
            $programaaux = 'NINGUNO'; // Asignar null si programax está vacío
        }
        $data->programa_social = $programaaux;
        $data->fecha_nacimiento = date('d/m/Y', strtotime($data->fecha_nacimiento));
        $data->visita = $data->visita == 1 ? 'SI' : 'NO';
        $data->menor_encontrado = $data->menor_encontrado == 1 ? 'SI' : 'NO';
        $data->cui_atencion = $data->establecimiento_id > 0 ? Establecimiento::find($data->establecimiento_id)->nombre_establecimiento : 'NINGUNO';
        $data->distrito = $data->distrito_id > 0 ? Ubigeo::find($data->distrito_id)->nombre : '';
        $data->provincia = $data->provincia_id > 0 ? Ubigeo::find($data->provincia_id)->nombre : '';
        $data->departamento = 'UCAYALI';
        return  $data;
    }

    public function tablerocalidadcriteriofind2($importacion, $cui)
    {
        $data = Establecimiento::where('cod_unico', $cui)->first();
        $ubica = UbigeoRepositorio::ubicacion($data->ubigeo_id);
        $data->distrito = $ubica->disn;
        $data->provincia = $ubica->pron;
        $data->departamento = $ubica->depn;
        $diresa = EstablecimientoRepositorio::ubicacion($data->id);
        $data->micro = $diresa->min;
        $data->red = $diresa->ren;
        $data->disa = $diresa->dsn;
        return  $data;
    }

    public function tablerocalidadcriteriodownload($div, $importacion, $criterio, $edades, $provincia, $distrito)
    {
        $name = DB::table('sal_calidad_criterio_nombres')->where('id', $criterio)->first()->nombre . '.xlsx';
        return Excel::download(new TableroCalidadCriterioExport($div, $importacion, $criterio, $edades, $provincia, $distrito), $name);
    }

    public function tablerocalidadconsulta()
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        $anio = 2024;
        $importacion = PadronNominalRepositorio::PNImportacion_idmax($fuente, $anio);
        return view('salud.PadronNominal.TableroCalidadConsulta', compact('importacion'));
    }

    public function tablerocalidadconsultalistar(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $seguro = [0 => 'NINGUNO', 1 => 'SIS', 2 => 'ESSALUD', 3 => 'SANIDAD', 4 => 'PRIVADO'];
        $programa = [0 => 'NINGUNO', 1 => 'PIN', 2 => 'PVL', 4 => 'JUNTOS', 5 => 'QALIWARMA', 7 => 'CUNA+ SCD', 8 => 'CUNA+ SAF'];

        $query = ImporPadronNominal::where('importacion_id', $rq->importacion)
        ->when($rq->doc, function ($q) use ($rq) {
            return $q->where('tipo_doc', $rq->tip)->where('num_doc', $rq->doc);
        })
        ->when($rq->nom, function ($q) use ($rq) {
            return $q->where(function ($subQuery) use ($rq) {
                $subQuery->where('apellido_paterno', $rq->nom)
                         ->orWhere('apellido_materno', $rq->nom)
                         ->orWhere('nombre', $rq->nom);
            });
        });
        // if ($rq->doc != '') {
        //     $query = $query->where('tipo_doc', $rq->tip);
        // }

        // if ($rq->nom != '') {
        //     $query = $query->where('apellido_paterno', 'like', '%' . $rq->nom . '%')->where('apellido_materno', 'like', '%' . $rq->nom . '%')->where('nombre', 'like', '%' . $rq->nom . '%');
        // }


        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;

        $query = $query->skip($start)->take($length)->get();

        $ubigeos = Ubigeo::whereIn('codigo', $query->pluck('ubigeo')->toArray())->get()->keyBy('codigo');
        $establecimientos = Establecimiento::whereIn('cod_unico', $query->pluck('cui_atencion')->toArray())->get()->keyBy('cod_unico');

        $sim = ['D' => 'DÍAS', 'M' => 'MESES', 'A' => 'AÑOS'];
        $data = [];

        foreach ($query as $key => $value) {
            $dis = $ubigeos[$value->ubigeo] ?? null;
            $eess = $establecimientos[$value->cui_atencion] ?? null;

            $data[] = [
                $key + 1,
                $value->padron,
                $value->tipo_doc != 'Padron' ? $value->tipo_doc : '',
                $value->tipo_doc != 'Padron' ? $value->num_doc : '',
                $value->apellido_paterno . ' ' . $value->apellido_materno . ', ' . $value->nombre,
                $value->edad . ' ' . ($sim[$value->tipo_edad] ?? ''),
                $seguro[$value->seguro_id] ?? '',
                $value->visita == 1 ? 'SI' : 'NO',
                $value->encontrado == 1 ? 'SI' : 'NO',
                $dis ? $dis->nombre : '',
                $eess ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '',
                $eess ? $eess->nombre_establecimiento : '',
            ];
        }

        $result = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        return response()->json($result);
    }

    public function tablerocalidadconsultafind1($importacion, $tipo, $documento = '', $apellido = '')
    {
        $seguro = [0 => 'NINGUNO', 1 => 'SIS', 2 => 'ESSALUD', 3 => 'SANIDAD', 4 => 'PRIVADO'];
        $programa = [0 => 'NINGUNO', 1 => 'PIN', 2 => 'PVL', 4 => 'JUNTOS', 5 => 'QALIWARMA', 7 => 'CUNA+ SCD', 8 => 'CUNA+ SAF'];
        $data = CalidadCriterio::where('importacion_id', $importacion)
            ->where('tipo_doc', $tipo);
        if ($documento != 'documento')
            $data = $data->where('num_doc', $documento);
        if ($apellido != 'apellido')
            $data = $data->where(function ($query) use ($apellido) {
                $query->where('apellido_paterno', 'like', "%{$apellido}%")
                    ->orWhere('apellido_materno', 'like', "%{$apellido}%")
                    ->orWhere('nombre', 'like', "%{$apellido}%");
            });
        $data = $data->first();
        if ($data) {
            $data->centro_poblado_nombre = !empty($data->centro_poblado_nombre) ? explode(', ', $data->centro_poblado_nombre)[0] : null;
            $data->seguro = $seguro[$data->seguro_id] ?? 'NINGUNO';
            $programaaux = null;
            if (!empty($data->programa_social)) {
                $programaIds = explode(',', trim($data->programa_social, ','));
                $programaaux = array_map(function ($id) use ($programa) {
                    return isset($programa[$id]) ? $programa[$id] : null;
                }, $programaIds);

                $programaaux = implode(', ', array_filter($programaaux));
            } else {
                $programaaux = 'NINGUNO'; // Asignar null si programax está vacío
            }
            $data->programa_social = $programaaux;
            $data->fecha_nacimiento = date('d/m/Y', strtotime($data->fecha_nacimiento));
            $data->visita = $data->visita == 1 ? 'SI' : 'NO';
            $data->menor_encontrado = $data->menor_encontrado == 1 ? 'SI' : 'NO';
            $data->cui_atencion = $data->establecimiento_id > 0 ? Establecimiento::find($data->establecimiento_id)->nombre_establecimiento : 'NINGUNO';
            $data->distrito = $data->distrito_id > 0 ? Ubigeo::find($data->distrito_id)->nombre : '';
            $data->provincia = $data->provincia_id > 0 ? Ubigeo::find($data->provincia_id)->nombre : '';
            $data->departamento = 'UCAYALI';
        }
        return  response()->json($data);
    }
}
