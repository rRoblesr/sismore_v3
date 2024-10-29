<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Mes;
use App\Models\Salud\ImporPadronNominal;
use App\Models\Salud\PadronCalidad;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Salud\PadronNominalRepositorioSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadronNominal extends Controller
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

    public function tablero_calidad()
    {
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE); //nexus $imp3
        $mes = Mes::find($imp->mes);
        $anios = ImportacionRepositorio::anios_porfuente(ImporPadronNominalController::$FUENTE);
        $provincias = UbigeoRepositorio::provincia_select('25');
        // compact('imp', 'mes');
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $mes->mes . ' del ' . $imp->anio;
        return view('salud.PadronNominal.TableroCalidad', compact('actualizado', 'anios', 'provincias'));
    }

    public function tablero_calidad_reporte(Request $rq)
    {
        $fuente = ImporPadronNominalController::$FUENTE;
        switch ($rq->div) {
            case 'head':
                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                // $impMaxAnio = DB::table(DB::raw("($sql1) as tb"))->setBindings([$fuente, $fuente, $rq->anio])->first();
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;

                $card1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->count();
                $card2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('tipo_doc', 'DNI')->count();
                $card3 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->where(function ($query) {
                        $query->whereRaw("FIND_IN_SET('1',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('2',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('3',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('4',seguro) > 0")
                            ->orWhereNotNull('seguro');
                    })->count();
                $card4 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('cui_atencion', '>', 0)->count();

                $card1 = number_format($card1, 0);
                $card2 = number_format($card2, 0);
                $card3 = number_format($card3, 0);
                $card4 = number_format($card4, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                // $impMaxAnio = DB::table(DB::raw("($sql1) as tb"))->setBindings([$fuente, $fuente, $rq->anio])->first();
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;
                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->count();
                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('visita', '1')->count();
                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance'));
            case 'anal2':
                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                // $impMaxAnio = DB::table(DB::raw("($sql1) as tb"))->setBindings([$fuente, $fuente, $rq->anio])->first();
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;
                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->count();
                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('tipo_doc', 'DNI')->count();
                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance'));
            case 'anal3':
                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;
                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->count();
                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')
                    ->where(function ($query) {
                        $query->whereRaw("FIND_IN_SET('1',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('2',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('3',seguro) > 0")
                            ->orWhereRaw("FIND_IN_SET('4',seguro) > 0")
                            ->orWhereNotNull('seguro');
                    })->count();
                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance'));
            case 'anal4':
                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;
                $v2 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->count();
                $v1 = ImporPadronNominal::where('importacion_id', $impMaxAnio)->where('repetido', '1')->where('cui_atencion', '>', 0)->count();
                $avance = round($v2 > 0 ? 100 * $v1 / $v2 : 0, 1);
                return response()->json(compact('avance', 'v1', 'v2'));
            case 'tabla1':
                $sql1 = "SELECT * FROM par_importacion
                        WHERE fuenteimportacion_id = ? AND estado = 'PR'
                            AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                                SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                                WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                            )
                        ORDER BY fechaActualizacion DESC limit 1";
                $query1 = DB::select($sql1, [$fuente, $fuente, $rq->anio]);
                $impMaxAnio = $query1 ? $query1[0]->id : 0;
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
                return response()->json(compact('base'));
            default:
                # code...
                return [];
        }
    }
}
