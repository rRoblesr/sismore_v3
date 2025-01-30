<?php

namespace App\Http\Controllers\Salud;

use App\Exports\pactoregionalSal1Export;
use App\Exports\pactoregionalSal2Export;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Educacion\ImporMatriculaController;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Models\Educacion\CuboPacto1;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Indicador;
use App\Models\Educacion\SFL;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CuboPacto1PadronNominal;
use App\Models\Salud\CuboPacto3PadronMaterno;
use App\Models\Salud\CuboPacto4Padron12Meses;
use App\Models\Salud\ImporPadronAnemia;
use App\Repositories\Educacion\CuboPacto1Repositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\SFLRepositorio;
use App\Repositories\Parametro\IndicadorGeneralMetaRepositorio;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use App\Repositories\Parametro\PoblacionPNRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Salud\CuboPacto1PadronNominalRepositorio;
use App\Repositories\Salud\CuboPacto2Repositorio;
use App\Repositories\Salud\CuboPacto3Repositorio;
use App\Repositories\Salud\CuboPacto4Repositorio;
use App\Repositories\Salud\PadronNominalRepositorio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class IndicadoresController extends Controller
{
    public $mes = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];
    public $mesname = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
    public static $pacto1_anio = 2023;
    public static $pacto1_mes = 5;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function findCodigo($codigo)
    {
        $data = IndicadorGeneral::select('codigo', 'nombre', 'descripcion', 'numerador', 'denominador')->where('codigo', $codigo)->first();
        return response()->json($data);
    }

    public function PactoRegionalMeta()
    {
        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('DIT-SAL-01');
        $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($ind->id);
        $distrito = UbigeoRepositorio::distrito_select('25', 0);
        // $aniomax = $imp->anio;
        $codigos = IndicadorGeneral::from('par_indicador_general as ig')
            ->select('ig.codigo', 'ig.nombre')
            // ->select('ig.nombre')
            ->where('ig.sector_id', 14)
            ->get();
        $data = IndicadorGeneral::from('par_indicador_general as ig')
            ->join('par_indicador_general_meta as igm', 'igm.indicadorgeneral', '=', 'ig.id')
            ->join('par_ubigeo as uu', 'uu.id', '=', 'igm.distrito')
            ->select('ig.codigo', 'ig.nombre', 'ig.unidad_id', 'igm.distrito as iddistrito', 'uu.nombre as distrito', 'igm.anio', 'igm.valor')
            ->where('ig.sector_id', 14)
            ->get();

        return view('indicadores.PactoRegional.PactoRegionalSalMeta', compact('anio', 'distrito', 'codigos', 'data'));
    }

    public function PactoRegional()
    {
        $sector = 14;
        $instrumento = 8;
        $indsal = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        $sector = 4;
        $instrumento = 8;
        $indedu = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        $sector = 18;
        $instrumento = 8;
        $indviv = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);

        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('DIT-SAL-01');
        $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($ind->id);
        $provincia = UbigeoRepositorio::provincia('25');

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
        // // return response()->json(compact('imp'));
        $aniomax = $imp->anio;

        return view('salud.Indicadores.PactoRegional', compact('indsal', 'indedu', 'indviv', 'anio', 'provincia', 'aniomax'));
    }

    public function PactoRegionalEdu()
    {
        $sector = 4;
        $instrumento = 8;
        $indedu = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);

        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('DIT-EDU-01');
        // $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($ind->id);
        // $anio = collect(range(2023, Carbon::now()->year));
        $updateMin1 = PoblacionPNRepositorio::actualizado();
        $updateMin2 = CuboPacto1Repositorio::actualizado();
        $updateMin3 = CuboPacto1Repositorio::actualizado();
        $anios = [$updateMin1->anio, $updateMin2->anio, $updateMin3->anio];
        $anio = array_unique($anios);

        $provincia = UbigeoRepositorio::provincia('25');

        // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
        // // return response()->json(compact('imp'));
        $aniomax = date('Y');

        return view('salud.Indicadores.PactoRegionalEdu', compact('indedu', 'anio', 'provincia', 'aniomax'));
    }

    public function PactoRegionalSal()
    {
        $sector = 14;
        $instrumento = 8;
        $indsal = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        // $sector = 4;
        // $instrumento = 8;
        // $indedu = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        // $sector = 18;
        // $instrumento = 8;
        // $indviv = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);

        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('DIT-SAL-01');
        $anio = collect(range(2023, Carbon::now()->year));
        // $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($ind->id);
        $provincia = UbigeoRepositorio::provincia('25');

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE);
        // // return response()->json(compact('imp'));
        $aniomax = $imp->anio;

        return view('salud.Indicadores.PactoRegionalSal', compact('indsal', 'anio', 'provincia', 'aniomax'));
    }

    public function PactoRegionalActualizar(Request $rq)
    {
        $imp = null;
        $gls = 0;
        $gl = 0;
        switch ($rq->codigo) {
            case 'DIT-SAL-01':
                $fuente = ImporPadronNominalController::$FUENTE;
                $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio, 0);
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE);

                $base = CuboPacto1PadronNominalRepositorio::pacto01Head($impMaxAnio, $rq->anio, 0, $rq->provincia, $rq->distrito);

                $gls = $base->gls;
                $gl = $base->gl;

                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-SAL-02':
                $info = CuboPacto2Repositorio::actualizado($rq->anio);
                $actualizado =  'Actualizado: SIN RESULTADOS';

                $gls = 0;
                $gl = 0;
                if ($info) {
                    // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_2']);                
                    // $base = IndicadorGeneralMetaRepositorio::getSalPacto2GLS2(0, $rq->anio, $imp->mes, $rq->provincia, $rq->distrito);
                    $base = CuboPacto2Repositorio::head($rq->anio, $info->mes, $rq->provincia, $rq->distrito);

                    $gls = $base->si;
                    $gl = $base->conteo;
                    // $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                    $actualizado =  'Actualizado: ' . $info->fecha;
                }

                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                break;
            case 'DIT-SAL-03':
                $info = CuboPacto3Repositorio::actualizado($rq->anio);
                $actualizado =  'Actualizado: SIN RESULTADOS';

                $gls = 0;
                $gl = 0;
                if ($info) {
                    // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_3']);
                    $base = CuboPacto3Repositorio::head($rq->anio, $info->mes, $rq->provincia, $rq->distrito);

                    $gls = $base->si;
                    $gl = $base->conteo;
                    // $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                    $actualizado =  'Actualizado: ' . $info->fecha;
                }
                $num = $gls;
                $den = $gl;

                break;
            case 'DIT-SAL-04':
                $info = CuboPacto4Repositorio::actualizado($rq->anio);
                $actualizado =  'Actualizado: SIN RESULTADOS';
                $gls = 0;
                $gl = 0;
                if ($info) {
                    // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_4']);
                    $base = CuboPacto4Repositorio::head($rq->anio, $info->mes, $rq->provincia, $rq->distrito);
                    $gls = $base->si;
                    $gl = $base->conteo;
                    $actualizado =  'Actualizado: ' . $info->fecha;
                }
                $num = $gls;
                $den = $gl;

                break;
                // case 'DIT-SAL-05':
                //     $gls = 0;
                //     $gl = 0;
                //     $num = $gls;
                //     $den = $gl;
                //     $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                //     break;
                // case 'DIT-SAL-06':
                //     $gls = 10;
                //     $gl = 19;
                //     $num = $gls;
                //     $den = $gl;
                //     $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                //     break;
                // case 'DIT-SAL-07':
                //     $gls = 60;
                //     $gl = 100;
                //     $num = $gls;
                //     $den = $gl;
                //     $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                //     break;
            case 'DIT-EDU-01':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $actualizado =  'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
                $updateMin1 = PoblacionPNRepositorio::actualizado();
                $updateMin2 = CuboPacto1Repositorio::actualizado();
                $mes = min($updateMin1->mes, $updateMin2->mes);

                $gl = (int)PoblacionPNRepositorio::conteo3a5_acumulado($rq->anio, $mes, $rq->provincia, $rq->distrito, 0);
                $gls = CuboPacto1Repositorio::pacto1_matriculados($rq->anio, $mes, $rq->provincia, $rq->distrito);
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);

                break;
            case 'DIT-EDU-02':
                $gl = SFLRepositorio::get_localsx($rq->anio, 0, $rq->provincia, $rq->distrito, 0);
                $gls = SFLRepositorio::get_localsx($rq->anio, 0, $rq->provincia, $rq->distrito, 1);
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                $fecha = SFLRepositorio::inscripcion_max($rq->anio, 0, $rq->provincia, $rq->distrito, 1);
                $actualizado =  'Actualizado: ' . date('d/m/Y', strtotime($fecha));
                break;
            case 'DIT-EDU-03':
                $gls = 0;
                $gl = 0;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: __/__/____';
                break;
            case 'DIT-EDU-04':
                $gls = 0;
                $gl = 0;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: __/__/____';
                break;
            case 'DIT-VIV-01':
                $gls = 25;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-VIV-02':
                $gls = 50;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-VIV-03':
                $gls = 75;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-VIV-04':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-ART-01':
                $gls = 25;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-ART-02':
                $gls = 50;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-ART-03':
                $gls = 75;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'DIT-ART-04':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'PDRC-EDU-01':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'PDRC-EDU-02':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'PDRC-EDU-03':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            case 'PDRC-EDU-04':
                $gls = 100.9;
                $gl = 100;
                $num = $gls;
                $den = $gl;
                $actualizado =  $imp ? 'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion)) : 'Actualizado: ' . date('d/m/Y');
                break;
            default:
                break;
        }
        $avance =  round(100 * ($gl > 0 ? $gls / $gl : 0), 1);
        $meta = '100%';
        $cumple = $gls >= $gl;
        return response()->json(compact('avance', 'actualizado', 'meta', 'cumple', 'num', 'den'));
    }

    public function PactoRegionalDetalle($indicador_id)
    {
        $ind = IndicadorGeneralRepositorio::findNoFichatecnica($indicador_id);
        switch ($ind->codigo) {
            case 'DIT-SAL-01':
                $fuente = ImporPadronNominalController::$FUENTE;
                $anio = ImportacionRepositorio::anios_porfuente_select(ImporPadronNominalController::$FUENTE);
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronNominalController::$FUENTE);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto1', compact('actualizado', 'fuente', 'anio', 'provincia', 'aniomax', 'ind'));

            case 'DIT-SAL-02':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_2']);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = ImporPadronAnemia::distinct()->select('anio')->get();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto2', compact('actualizado', 'anio', 'provincia', 'aniomax', 'ind'));

            case 'DIT-SAL-03':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_3']);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = CuboPacto3PadronMaterno::distinct()->select('anio')->get();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto3', compact('actualizado', 'anio',  'provincia', 'aniomax', 'ind'));
                // return '';

            case 'DIT-SAL-04':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_4']);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = CuboPacto4Padron12Meses::distinct()->select('anio')->get();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto4', compact('actualizado', 'anio',  'provincia', 'aniomax', 'ind'));
            case 'DIT-SAL-05':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto5', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));

            case 'DIT-SAL-06':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto6', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));

            case 'DIT-SAL-07':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
                // return response()->json([$imp]);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($indicador_id); // Anio::orderBy('anio')->get();
                $mes = Mes::all();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = $imp->anio;
                return view('salud.Indicadores.PactoRegionalSalPacto7', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax', 'ind'));

            case 'DIT-EDU-01':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                // $actualizado = 'Actualizado al ';
                // $anio = IndicadorGeneralMeta::distinct()->select('anio')->where('indicadorgeneral', $indicador_id)->get();
                $anio = CuboPacto1::distinct()->select('anio')->get();
                $aniomax = $anio->max('anio');

                $am = DB::table('edu_cubo_pacto01_matriculados')->whereIn('nivelmodalidad_codigo', ['A2', 'A3', 'A5'])->where('anio', $anio->where('anio', '<=', date('Y'))->max('anio'))->max('mes_id');
                $ap = PoblacionPN::where('anio', $anio->where('anio', '<=', date('Y'))->max('anio'))->max('mes_id');
                $mesmax = 0;
                if ($mesmax < $am) $mesmax = $am;
                if ($mesmax < $ap) $mesmax = $ap;
                $mesmin = 20;
                if ($mesmin > $am) $mesmin = $am;
                if ($mesmin > $ap) $mesmin = $ap;
                // $mes = Mes::select('id', 'mes')->where('codigo', '<=', ($aniomax < date('Y') ? 12 : $aniomax))->get();
                $mes = Mes::select('id', 'mes')->where('codigo', '<=', $mesmax)->get();
                $provincia = UbigeoRepositorio::provincia('25');
                // return response()->json(['anio' => $anio, 'am' => $am, 'ap' => $ap, 'aniomax' => $aniomax, 'aniomin' => $aniomin, 'mes' => $mes, 'provincia' => $provincia]);
                return view('salud.Indicadores.PactoRegionalEduPacto1', compact('actualizado', 'anio', 'mes', 'mesmin', 'aniomax', 'provincia',  'ind'));

            case 'DIT-EDU-02':
                $ff = SFL::select(DB::raw('max(fecha_inscripcion) as ff'))->first();
                $actualizado = 'Actualizado al ' . date('d', strtotime($ff->ff)) . ' de ' . $this->mesname[date('m', strtotime($ff->ff)) - 1] . ' del ' . date('Y', strtotime($ff->ff));
                // $anio = SFL::distinct()->select(DB::raw('year(fecha_inscripcion) as anio'))->orderBy('anio')->get();
                //  $anio = IndicadorGeneralMeta::distinct()->select('anio')->where('indicadorgeneral', $indicador_id)->get();
                $anio = SFL::selectRaw('DISTINCT YEAR(fecha_inscripcion) as anio')->whereNotNull('fecha_inscripcion')->get(); //pluck('anio');

                $provincia = UbigeoRepositorio::provincia('25');
                //SFL::from('edu_sfl as sfl')->distinct()->select(DB::raw('month(sfl.fecha_inscripcion) as mes')
                $mes = DB::table(DB::raw('(select distinct month(fecha_inscripcion) as mes from edu_sfl)as sfl'))
                    ->join('par_mes as m', 'm.id', '=', 'sfl.mes')->select('m.id', 'm.mes')->get();
                $aniomax = $anio->max('anio');
                $mesmax = $mes->max('id');
                return view('salud.Indicadores.PactoRegionalEduPacto2', compact('actualizado', 'anio', 'mes', 'provincia', 'aniomax',  'mesmax', 'ind'));
            case 'DIT-EDU-03':
                return '';
            case 'DIT-EDU-04':
                return '';
            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    // ############ salud pacto 1 #################
    public function PactoRegionalSalPacto1Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
        switch ($rq->div) {
            case 'head':
                $base = CuboPacto1PadronNominalRepositorio::pacto01Head($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gln = $base->gl - $base->gls;

                $ri = number_format($base->indicador, 1);
                $gls = number_format($base->gls, 0);
                $gln = number_format($base->gln, 0);
                $gl = number_format($base->gl, 0);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln, 'base' => $base]);

            case 'anal1':
                $base = CuboPacto1PadronNominalRepositorio::pacto01Anal01($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->distrito;
                    $info['serie'][] = ['y' => round($value->indicador, 1), 'color' => (round($value->indicador, 1) > 95 ? '#43beac' : (round($value->indicador, 1) > 50 ? '#eb960d' : '#ef5350'))];
                }
                return response()->json(compact('info'));

            case 'anal2':
                $base = CuboPacto1PadronNominalRepositorio::pacto01Anal02($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $base1 = collect($base['query'] ?? []);
                $base1 = $base1->pluck('indicador', 'mes');
                $mes = Mes::select('id', 'abreviado')->get();

                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->abreviado;
                    $info['dat'][$key] = $base1[$value->id] ?? null;
                    if ($info['dat'][$key] > 0) $info['dat'][$key] = (float)$info['dat'][$key];
                }

                return response()->json(compact('info', 'base1'));

            case 'anal3': //lineas
                $base = CuboPacto1PadronNominalRepositorio::pacto01Anal03($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'No Cumplen';
                $info['serie'][1]['name'] = 'Cumplen';
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->edades;
                    // $info['serie'][$key]['data'] = [$value->si, $value->no];
                    $info['serie'][0]['data'][] = (int)$value->no;
                    $info['serie'][1]['data'][] = (int)$value->si;
                }
                return response()->json(compact('info', 'base'));

            case 'tabla1':
                $base = CuboPacto1PadronNominalRepositorio::pacto01Tabla01($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel'));

            case 'tabla2':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboPacto1PadronNominalRepositorio::pacto01Tabla02($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);

                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->codigo,
                        $value->ipress,
                        $value->red,
                        $value->microrred,
                        $value->provincia,
                        $value->distrito,
                        $value->denominador,
                        $value->numerador,
                        // $value->indicador
                        $value->indicador < 51 ?
                            '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' . number_format($value->indicador, 1) . '%</span>' : ($value->indicador < 100 ?
                                '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' . number_format($value->indicador, 1) . '%</span>' :
                                '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' . number_format($value->indicador, 1) . '%</span>'
                            )
                    );
                }
                $result = array(
                    "draw" => $draw,
                    "recordsTotal" => $start,
                    "recordsFiltered" => $length,
                    "data" => $data,
                    // "data2" => $rq->all(),
                );
                return response()->json($result);
                break;

            case 'tabla0201':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboPacto1PadronNominal::where('importacion', $impMaxAnio)->where('cui_atencion', $rq->cod_unico);
                $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);

                // if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                // if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);

                $query = $query->get();

                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->tipo_doc,
                        $value->num_doc,
                        $value->nombre_completo,
                        $value->fecha_nacimiento,
                        $value->distrito,
                        $value->seguro,
                        $value->cui_atencion,
                        $value->nombre_establecimiento,
                        $value->num_doc_madre,
                        $value->nombre_completo_madre,
                        $value->num,
                    );
                }
                $result = array(
                    "draw" => $draw,
                    "recordsTotal" => $start,
                    "recordsFiltered" => $length,
                    "data" => $data,
                    // "data2" => $rq->all(),
                );
                return response()->json($result);
                break;
            case 'tabla2y':
                $base = CuboPacto1PadronNominalRepositorio::pacto01Tabla02($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla2', compact('base', 'ndis'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact( 'base'));

            case 'tabla2x':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboPacto1PadronNominal::where('importacion', $impMaxAnio);

                $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);

                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);

                $recordsTotal = $query->count();
                $recordsFiltered = $recordsTotal;

                $query = $query->skip($start)->take($length)->get();

                $query = $query->map(function ($item, $key) use ($start) {
                    $item->item = $start + $key + 1;
                    return $item;
                });

                $query->transform(function ($value) {
                    $value->nacimiento = date('d/m/Y', strtotime($value->fecha_nacimiento));
                    $value->ipress = str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT);
                    $value->estado = $value->num == 1
                        ? '<span class="badge badge-pill badge-success" style="font-size:90%;">CUMPLEN</span>'
                        :  '<span class="badge badge-pill badge-danger" style="font-size:90%;">NO CUMPLEN</span>';
                    return $value;
                });

                $result = [
                    "draw" => $draw,
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $query,
                    "input" => $rq->all(),
                    "queries" => $query->toArray(),
                ];

                return response()->json($result);

            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto1Reports2(Request $rq)
    {
        $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);

        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = CuboPacto1PadronNominal::where('importacion', $impMaxAnio);

        $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);

        if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
        if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);

        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;

        $query = $query->skip($start)->take($length)->get();

        $query = $query->map(function ($item, $key) use ($start) {
            $item->item = $start + $key + 1;
            return $item;
        });

        $query->transform(function ($value) {
            $value->nacimiento = $value->fecha_nacimiento ? date('d/m/Y', strtotime($value->fecha_nacimiento)) : null;
            $value->ipress = str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT);
            $value->estado = $value->num == 1
                ? '<span class="badge badge-pill badge-success" style="font-size:90%;">CUMPLE</span>'
                :  '<span class="badge badge-pill badge-danger" style="font-size:90%;">NO CUMPLE</span>';
            return $value;
        });

        $result = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $query,
            "input" => $rq->all(),
            "queries" => $query->toArray(),
            'importacion' => $impMaxAnio
        ];

        return response()->json($result);
    }

    public function PactoRegionalSalPacto1Reports3(Request $rq)
    {
        $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);

        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = CuboPacto1PadronNominal::where('importacion', $impMaxAnio);

        $query = $query->whereIn('tipo_doc', ['DNI', 'CNV']);

        if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
        if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);

        $recordsTotal = $query->count();
        $recordsFiltered = $recordsTotal;

        $query = $query->skip($start)->take($length)->get();

        $query = $query->map(function ($item, $key) use ($start) {
            $item->item = $start + $key + 1;
            return $item;
        });

        $estado = ['<i class="mdi mdi-thumb-down" style="font-size:12px;color:red" title="NO CUMPLE"></i>', '<i class="mdi mdi-thumb-up" style="font-size:12px;color:#43beac" title="CUMPLE"></i>'];
        $query->transform(function ($value) use ($estado) {
            $value->nacimiento = $value->fecha_nacimiento ? date('d/m/Y', strtotime($value->fecha_nacimiento)) : null;
            $value->c01 = $estado[$value->critero01];
            $value->c02 = $estado[$value->critero02];
            $value->c03 = $estado[$value->critero04];
            $value->c04 = $estado[$value->critero05];
            $value->c05 = $estado[$value->critero03];
            $value->c06 = $estado[$value->critero06];
            $value->c07 = $estado[$value->critero07];
            $value->c08 = $estado[$value->critero08];
            $value->c09 = $estado[$value->critero09];
            $value->c10 = $estado[$value->critero10];

            $value->estado = $value->num == 1
                ? '<span class="badge badge-pill badge-success" style="font-size:90%;">CUMPLEN</span>'
                :  '<span class="badge badge-pill badge-danger" style="font-size:90%;">NO CUMPLEN</span>';
            return $value;
        });

        $result = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $query,
            "input" => $rq->all(),
            "queries" => $query->toArray(),
            'importacion' => $impMaxAnio
        ];

        return response()->json($result);
    }

    public function PactoRegionalSalPacto1Export($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($distrito > 0) $ndis = Ubigeo::find($distrito)->nombre;
        else $ndis = '';
        switch ($div) {
                // case 'tabla1':
                //     $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($indicador, $anio, $mes);
                //     $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
                //     return compact('excel', 'base');

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getPacto1tabla2($indicador, $anio);
                $aniob = $anio;
                return compact('base', 'ndis', 'aniob');


            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto1download($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($anio > 0) {
            switch ($div) {
                    // case 'tabla1':
                    //     $name = 'Listado de establecimientos de salud ' . date('Y-m-d') . '.xlsx';
                    //     break;
                case 'tabla2':
                    $name = 'Evaluación de cumplimiento de los logros esperados por distrito ' . date('Y-m-d') . '.xlsx';
                    break;
                default:
                    $name = 'sin nombre.xlsx';
                    break;
            }

            return Excel::download(new pactoregionalSal1Export($div, $indicador, $anio, $mes, $provincia, $distrito), $name);
        }
    }

    // ############ salud pacto 2 #################
    public function PactoRegionalSalPacto2Reports(Request $rq)
    {
        $ndis = $rq->distrito > 0 ? Ubigeo::find($rq->distrito)->nombre : '';
        switch ($rq->div) {
            case 'head':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2GLS2($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gls = $base->si; // IndicadorGeneralMetaRepositorio::getSalPacto2GLS($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gl = $base->conteo; // IndicadorGeneralMetaRepositorio::getSalPacto2GL($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);

                $gln = intval($gl) - intval($gls);
                $ri = number_format(100 * ($gl > 0 ? $gls / $gl : 0), 1);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln, 'base' => $base]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2anal1(0, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->distrito;
                    $info['serie'][] = ['y' => round($value->indicador, 1), 'color' => (round($value->indicador, 1) > 95 ? '#43beac' : (round($value->indicador, 1) > 50 ? '#eb960d' : '#ef5350'))];
                }
                return response()->json(compact('info', 'base'));
            case 'anal2':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2Mensual($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                // return response()->json(compact('base'));
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax = $rq->anio == date('Y') ? date('m') : 12; //$base->max('name');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {
                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax) {
                        $mm->y = null;
                        foreach ($base as $bb) {
                            if ($bb->name == $mm->codigo) {
                                $mm->y = (float)$bb->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y = null;
                    }
                }
                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->mes;
                    // $value->y = $value->y;
                    // if ($key == 0)
                    //     $vv = $value->y;
                    // if ($key > 0) {
                    //     if ($value->y) {
                    //         $value->y += $vv;
                    //         $vv = $value->y;
                    //     }
                    // }
                    $info['dat'][] = $value->y;
                }
                return response()->json(compact('info', 'mes', 'base', 'mesmax'));
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla1($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                // $foot = clone $base[0];
                // $foot->valor = 0;
                // $foot->num = 0;
                // $foot->den = 0;
                // foreach ($base as $key => $value) {
                //     $foot->valor += $value->valor;
                //     $foot->num += $value->num;
                //     $foot->den += $value->den;
                // }
                // $foot->valor = round($foot->valor / 19, 1);
                // $foot->ind = round(100 * $foot->num / $foot->den, 1);
                // $foot->cumple = $foot->ind >= $foot->valor ? 1 : 0;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla2($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $foot = clone $base[0];
                $foot->num = 0;
                $foot->den = 0;
                $foot->ind = 0;
                foreach ($base as $key => $value) {
                    $foot->num += $value->num;
                    $foot->den += $value->den;
                }
                $foot->ind = round(100 * $foot->num / $foot->den, 1);
                $foot->cumple = 0;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla2', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla3($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                // foreach ($base as $key => $value) {
                //     $value->unico = str_pad($value->unico, 8, '0', STR_PAD_LEFT);
                // }
                // return response()->json(compact('base'));
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla3', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel'));

            case 'tabla0301':
                // $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla0301($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->cod_unico);
                // $aniob = $rq->anio;
                // $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla3', compact('base', 'ndis', 'aniob'))->render();
                // return response()->json(compact('excel', 'base'));

                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = IndicadorGeneralMetaRepositorio::getSalPacto2tabla0301($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->cod_unico);
                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->num_doc,
                        $value->fecha_nac,
                        $value->distrito,
                        $value->seguro,
                        $value->num_supt1,
                        $value->num_supt3,
                        $value->num_recup,
                        $value->num_dosaje,
                        $value->num,
                    );
                }
                $result = array(
                    "draw" => $draw,
                    "recordsTotal" => $start,
                    "recordsFiltered" => $length,
                    "data" => $data,
                    // "data2" => $rq->all(),
                );
                return response()->json($result);
            case 'tabla4':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla4($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla4', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));
            case 'tabla2tabla1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla2tabla1($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->red);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto2tabla2tabla1', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto2FindMes($anio)
    {
        $impMax = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_2']);
        $query = ImporPadronAnemia::from('sal_impor_padron_anemia as ipa')->join('par_mes as m', 'm.id', '=', 'ipa.mes')->distinct()->select('m.id', 'm.mes')->where('ipa.anio', $anio);
        if ($anio == date('Y')) $query = $query->where('ipa.mes', '<=', $impMax->mes);
        $query = $query->orderBy('ipa.mes')->get();
        return $query;
    }

    public function PactoRegionalSalPacto2Export($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        switch ($div) {
            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla3($indicador, $anio, $mes, $provincia, $distrito);
                foreach ($base as $key => $value) {
                    $value->unico = str_pad($value->unico, 8, '0', STR_PAD_LEFT);
                }
                return compact('base');
            case 'tabla4':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto2tabla4($indicador, $anio, $mes, $provincia, $distrito);
                $aniob = $anio;
                return compact('base', 'aniob');
            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto2download($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($anio > 0) {
            switch ($div) {
                case 'tabla3':
                    $name = 'Listado de establecimientos de salud ' . date('Y-m-d') . '.xlsx';
                    break;
                case 'tabla4':
                    $name = 'Evaluación de cumplimiento' . date('Y-m-d') . '.xlsx';
                    break;
                default:
                    $name = 'sin nombre.xlsx';
                    break;
            }

            return Excel::download(new pactoregionalSal2Export($div, $indicador, $anio, $mes, $provincia, $distrito), $name);
        }
    }

    // ############ salud pacto 3 #################

    public function PactoRegionalSalPacto3Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
        switch ($rq->div) {
            case 'head':
                $base = CuboPacto3Repositorio::head($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gln = $base->no;

                $ri = number_format($base->indicador, 1);
                $gls = number_format($base->si, 0);
                $gln = number_format($base->no, 0);
                $gl = number_format($base->conteo, 0);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln, 'base' => $base]);

            case 'anal1':
                $base = CuboPacto3Repositorio::Anal01($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->distrito;
                    $info['serie'][] = ['y' => round($value->indicador, 1), 'color' => (round($value->indicador, 1) > 95 ? '#43beac' : (round($value->indicador, 1) > 50 ? '#eb960d' : '#ef5350'))];
                }
                return response()->json(compact('info'));

            case 'anal2':
                $base = CuboPacto3Repositorio::Anal02($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $base1 = collect($base ?? []);
                $base1 = $base1->pluck('indicador', 'mes');
                $mes = Mes::select('id', 'abreviado')->get();

                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->abreviado;
                    $info['dat'][$key] = $base1[$value->id] ?? null;
                    if ($info['dat'][$key] > 0) $info['dat'][$key] = (float)$info['dat'][$key];
                }

                return response()->json(compact('info', 'base'));

            case 'anal3_': //lineas
                $base = CuboPacto3Repositorio::Anal03($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info['serie'] = [];
                $info['serie'][0]['name'] = 'Cumplen';
                $info['serie'][1]['name'] = 'No Cumplen';
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->edades;
                    // $info['serie'][$key]['data'] = [$value->si, $value->no];
                    $info['serie'][0]['data'][] = (int)$value->si;
                    $info['serie'][1]['data'][] = (int)$value->no;
                }
                return response()->json(compact('info', 'base'));

            case 'anal3': //lineas
                $base = CuboPacto3Repositorio::Anal03($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $base1 = collect($base ?? []);
                $base1 = $base1->pluck('si', 'mes');
                $mes = Mes::select('id', 'abreviado')->get();

                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->abreviado;
                    $info['dat'][$key] = $base1[$value->id] ?? null;
                    if ($info['dat'][$key] > 0) $info['dat'][$key] = (float)$info['dat'][$key];
                }

                return response()->json(compact('info', 'base', 'base1'));

            case 'tabla1':
                $base = CuboPacto3Repositorio::Tabla01($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = CuboPacto3Repositorio::Tabla02($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalSalPacto3tabla2', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla0201':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query =  CuboPacto3Repositorio::Tabla0201($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->cod_unico);
                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->num_doc,
                        $value->fecha_parto,
                        $value->distrito,
                        $value->num_exam_aux,
                        $value->num_apn,
                        $value->num_entrega_sfaf,
                        $value->numerador,
                    );
                }
                $result = array(
                    "draw" => $draw,
                    "recordsTotal" => $start,
                    "recordsFiltered" => $length,
                    "data" => $data,
                    // "data2" => $rq->all(),
                );
                return response()->json($result);

            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto3FindMes($anio)
    {
        $impMax = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_3']);
        $query = CuboPacto3PadronMaterno::from('sal_cubo_pacto3_padron_materno as ipa')->join('par_mes as m', 'm.id', '=', 'ipa.mes')->distinct()->select('m.id', 'm.mes')->where('ipa.anio', $anio);
        if ($anio == date('Y')) $query = $query->where('ipa.mes', '<=', $impMax->mes);
        $query = $query->orderBy('ipa.mes')->get();
        return $query;
    }

    public function PactoRegionalSalPacto3Reports_anterior(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        switch ($rq->div) {
            case 'head':
                $gls = IndicadorGeneralMetaRepositorio::getSalPacto3GLS($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gl = IndicadorGeneralMetaRepositorio::getSalPacto3GL($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gln = $gl - $gls;
                $ri = number_format(100 * ($gl > 0 ? $gls / $gl : 0));
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls ? $gls : 0, 'gln' => $gln]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getPacto3Mensual($rq->anio, $rq->distrito);
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax = $base->max('name');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {
                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax) {
                        $mm->y = 0;
                        foreach ($base as $bb) {
                            if ($bb->name == $mm->codigo) {
                                $mm->y = (int)$bb->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y = null;
                    }
                }
                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->mes;
                    $value->y = $value->y;
                    if ($key == 0)
                        $vv = $value->y;
                    if ($key > 0) {
                        if ($value->y) {
                            // $value->y += $vv;
                            $vv = $value->y;
                        }
                    }
                    $info['dat'][] = $value->y;
                }
                return response()->json(compact('info', 'mes', 'base', 'mesmax'));
            case 'anal2':
                $base = IndicadorGeneralMetaRepositorio::getPacto3Mensual2($rq->anio, $rq->distrito);
                // $base1 = IndicadorGeneralMetaRepositorio::getPacto1Mensual($rq->anio, $rq->distrito);
                // $base2 = IndicadorGeneralMetaRepositorio::getPacto1Mensual2($rq->anio, $rq->distrito);
                $info = [];
                $mes = Mes::select('codigo', 'abreviado as mes')->get();
                $mesmax1 = $base->max('name');
                // $mesmax2 = $base2->max('mes');
                $limit = $rq->anio == 2023 ? IndicadoresController::$pacto1_mes : 0;
                foreach ($mes as $mm) {

                    if ($mm->codigo >= $limit && $mm->codigo <= $mesmax1) {
                        $mm->y1 = 0;
                        foreach ($base as $bb1) {
                            if ($bb1->name == $mm->codigo) {
                                $mm->y1 = (float)$bb1->y;
                                break;
                            }
                        }
                    } else {
                        $mm->y1 = null;
                    }

                    // if ($mm->codigo >= $limit && $mm->codigo <= $mesmax2) {
                    //     $mm->y2 = 0;
                    //     foreach ($base2 as $bb2) {
                    //         if ($bb2->mes == $mm->codigo) {
                    //             $mm->y2 = (int)$bb2->y;
                    //             break;
                    //         }
                    //     }
                    // } else {
                    //     $mm->y2 = null;
                    // }
                    $info['cat'][] = $mm->mes;
                    $info['dat'][] = $mm->y1;
                    // $info['dat2'][] = $mm->y2;
                }
                // return response()->json(compact('info', 'base1', 'base2', 'mes'));
                return response()->json(compact('info', 'base', 'mes'));
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto3tabla1($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);

                $excel = view('salud.Indicadores.PactoRegionalSalPacto3tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getSalPacto3tabla2($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalSalPacto3tabla2', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));


            default:
                return [];
        }
    }

    // ############ salud pacto 4 #################

    public function PactoRegionalSalPacto4Reports(Request $rq)
    {
        $ndis = $rq->distrito > 0 ? Ubigeo::find($rq->distrito)->nombre : '';
        $impMaxAnio = PadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
        switch ($rq->div) {
            case 'head':
                $base = CuboPacto4Repositorio::head($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $gln = $base->no;

                $ri = number_format($base->indicador, 1);
                $gls = number_format($base->si, 0);
                $gln = number_format($base->no, 0);
                $gl = number_format($base->conteo, 0);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln, 'basexx' => $base]);

            case 'anal1':
                $base = CuboPacto4Repositorio::Anal01($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->distrito;
                    $info['serie'][] = ['y' => round($value->indicador, 1), 'color' => (round($value->indicador, 1) > 95 ? '#43beac' : (round($value->indicador, 1) > 50 ? '#eb960d' : '#ef5350'))];
                }
                return response()->json(compact('info', 'base'));

            case 'anal2':
                $base = CuboPacto4Repositorio::Anal02($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $base1 = collect($base ?? []);
                $base1 = $base1->pluck('indicador', 'mes');
                $mes = Mes::select('id', 'abreviado')->get();

                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->abreviado;
                    $info['dat'][$key] = $base1[$value->id] ?? null;
                    if ($info['dat'][$key] > 0) $info['dat'][$key] = (float)$info['dat'][$key];
                }

                return response()->json(compact('info', 'base'));

            case 'anal3': //lineas
                $base = CuboPacto4Repositorio::Anal03($impMaxAnio, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $base1 = collect($base ?? []);
                $base1 = $base1->pluck('si', 'mes');
                $mes = Mes::select('id', 'abreviado')->get();

                $info = [];
                foreach ($mes as $key => $value) {
                    $info['cat'][] = $value->abreviado;
                    $info['dat'][$key] = $base1[$value->id] ?? null;
                    if ($info['dat'][$key] > 0) $info['dat'][$key] = (float)$info['dat'][$key];
                }

                return response()->json(compact('info', 'base', 'base1'));

            case 'tabla1':
                $base = CuboPacto4Repositorio::Tabla01($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalSalPacto4tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = CuboPacto4Repositorio::Tabla02($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalSalPacto4tabla2', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla0201':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query =  CuboPacto4Repositorio::Tabla0201($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->cod_unico);
                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->num_doc,
                        $value->fecha_nac,
                        $value->distrito,
                        $value->seguro,
                        $value->num_cred,
                        $value->num_vac,
                        $value->num_esq,
                        $value->num_hb,
                        $value->num_dniemision,
                        $value->numerador,
                    );
                }
                $result = array(
                    "draw" => $draw,
                    "recordsTotal" => $start,
                    "recordsFiltered" => $length,
                    "data" => $data,
                    // "data2" => $rq->all(),
                );
                return response()->json($result);
            default:
                return [];
        }
    }


    public function PactoRegionalSalPacto4FindMes($anio)
    {
        $impMax = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_4']);
        $query = CuboPacto3PadronMaterno::from('sal_cubo_pacto4_padron_12meses as ipa')->join('par_mes as m', 'm.id', '=', 'ipa.mes')->distinct()->select('m.id', 'm.mes')->where('ipa.anio', $anio);
        if ($anio == date('Y')) $query = $query->where('ipa.mes', '<=', $impMax->mes);
        $query = $query->orderBy('ipa.mes')->get();
        return $query;
    }


    public function cargarMesPvica($anio)
    {
        $mes =  Importacion::from('par_importacion as ii')->join('par_mes as mm', 'mm.codigo', '=', DB::raw('month(ii.fechaActualizacion)'))
            ->where('ii.estado', 'PR')->where('ii.fuenteimportacion_id', ImporPadronPvicaController::$FUENTE)->where(DB::raw('year(ii.fechaActualizacion)'), $anio)
            ->select('mm.codigo as id', 'mm.mes')->get();
        return response()->json($mes);
    }

    // ############ educacion pacto 1 #################

    public function PactoRegionalEduPacto1Reports(Request $rq)
    {
        $ind = IndicadorGeneralRepositorio::findNoFichatecnica($rq->indicador);
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        switch ($rq->div) {
            case 'head':
                $loc = (int)PoblacionPNRepositorio::conteo3a5_acumulado($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0);
                $ssa = CuboPacto1Repositorio::pacto1_matriculados($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $nsa = $loc - $ssa;
                $nsa = $nsa >= 0 ? $nsa : 0;
                $rin = number_format(100 * ($loc > 0 ? $ssa / $loc : 0), 1);
                return response()->json(['rq' => $rq->all(), 'loc' => number_format($loc, 0), 'ssa' => number_format($ssa, 0), 'nsa' => number_format($nsa), 'rin' => $rin]);

            case 'anal1':
                $est = CuboPacto1Repositorio::pacto1_matriculados_mensual($rq->anio, 0, 0, $rq->distrito);
                $ppn = PoblacionPNRepositorio::conteo3a5_mensual($rq->anio, 0, 0, $rq->distrito, 0);
                $base = IndicadorGeneralMetaRepositorio::getEduPacto1anal1($rq->indicador, $rq->anio, 0, 0, $rq->distrito, 0);
                $info['categoria'] = [];
                $info['series'] = [];
                foreach ($base as $keyi => $ii) {
                    $info['categoria'][] = $ii->mes;
                    $info['serie'][] = $ii->ind;
                }
                return response()->json(compact('info', 'base', 'est', 'ppn'));

            case 'anal2':
                $info = [];
                $alto = 0;
                $info['categoria'] = ['3 Años', '4 Años', '5 Años'];
                $data1 = CuboPacto1Repositorio::pacto1_matriculados_edad($rq->anio, $rq->mes, 0, $rq->distrito);
                foreach ($data1 as $key => $value) {
                    $info['serie'][1][] = (int)$value->conteo;
                    if ($value->conteo > $alto) $alto = (int)$value->conteo;
                }

                $data2 = PoblacionPN::from('par_poblacion_padron_nominal as pn')->select(DB::raw('sum(3a) as a3'), DB::raw('sum(4a) as a4'), DB::raw('sum(5a) as a5'))
                    ->where('pn.anio', $rq->anio);
                if ($rq->mes > 0) $data2 = $data2->where('mes_id', $rq->mes);
                // if ($rq->provincia > 0) $data2 = $data2->where('mes_id', $rq->provincia);
                if ($rq->distrito > 0) $data2 = $data2->where('ubigeo_id', $rq->distrito);
                $data2 = $data2->get()->first();
                if ($data2->a3 > $alto) $alto = (int)$data2->a3;
                if ($data2->a4 > $alto) $alto = (int)$data2->a4;
                if ($data2->a5 > $alto) $alto = (int)$data2->a5;
                $info['serie'][0] = [(int)$data2->a3, (int)$data2->a4, (int)$data2->a5];
                $info['serie'][2] = [
                    round(100 * ($info['serie'][0][0] > 0 ? $info['serie'][1][0] / $info['serie'][0][0] : 0), 1),
                    round(100 * ($info['serie'][0][0] > 0 ? $info['serie'][1][1] / $info['serie'][0][1] : 0), 1),
                    round(100 * ($info['serie'][0][0] > 0 ?  $info['serie'][1][2] / $info['serie'][0][2] : 0), 1)
                ];
                return response()->json(compact('info', 'alto'));

            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto1tabla1($rq->indicador, $rq->anio, $rq->mes, 0, $rq->distrito);
                $excel = view('salud.Indicadores.PactoRegionalEduPacto1tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto1tabla2($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $foot = clone $base[0];
                $foot->conteo = 0;
                $foot->hconteo = 0;
                $foot->mconteo = 0;
                $foot->conteo3 = 0;
                $foot->hconteo3 = 0;
                $foot->mconteo3 = 0;
                $foot->conteo4 = 0;
                $foot->hconteo4 = 0;
                $foot->mconteo4 = 0;
                $foot->conteo5 = 0;
                $foot->hconteo5 = 0;
                $foot->mconteo5 = 0;

                foreach ($base as $key => $value) {
                    $foot->conteo += $value->conteo;
                    $foot->hconteo += $value->hconteo;
                    $foot->mconteo += $value->mconteo;
                    $foot->conteo3 += $value->conteo3;
                    $foot->hconteo3 += $value->hconteo3;
                    $foot->mconteo3 += $value->mconteo3;
                    $foot->conteo4 += $value->conteo4;
                    $foot->hconteo4 += $value->hconteo4;
                    $foot->mconteo4 += $value->mconteo4;
                    $foot->conteo5 += $value->conteo5;
                    $foot->hconteo5 += $value->hconteo5;
                    $foot->mconteo5 += $value->mconteo5;
                }
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto1tabla2', compact('base', 'foot', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel'));

            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto1tabla3($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto1tabla3', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            default:
                return [];
        }
    }

    public function PactoRegionalEduPacto1FindMes($anio)
    {
        $impMax = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaController::$FUENTE);
        $query = CuboPacto1::from('edu_cubo_pacto01_matriculados as ipa')->join('par_mes as m', 'm.id', '=', 'ipa.mes_id')->distinct()->select('m.id', 'm.mes')->where('ipa.anio', $anio);
        if ($anio == date('Y')) $query = $query->where('ipa.mes', '<=', $impMax->mes);
        $query = $query->orderBy('m.id')->get();
        return $query;
    }


    // ############ educacion pacto 2 #################

    public function PactoRegionalEduPacto2Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        switch ($rq->div) {
            case 'head':
                $loc = SFLRepositorio::get_localsx($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0); //->count();
                $ssa = SFLRepositorio::get_localsx($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1); //->count();
                $nsa = $loc - $ssa; //SFLRepositorio::listado_iiee($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1)->count();
                $rin = number_format(100 * ($loc > 0 ? $ssa / $loc : 0), 1);
                return response()->json(['rq' => $rq->all(), 'loc' => number_format($loc, 0), 'ssa' => number_format($ssa, 0), 'nsa' => number_format($nsa), 'rin' => $rin]);
                // return response()->json(['rq' => $rq->all(), 'loc' => $loc, 'ssa' => $ssa, 'nsa' => $nsa, 'rin' => $rin]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2anal1($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0);
                // return response()->json(compact('base'));
                $info['series'] = [];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' =>    'SANEADO', 'data' => []];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'NO SANEADO', 'data' => []];
                foreach ($base as $keyi => $ii) {
                    $info['categoria'][] = $ii->provincia;
                    $info['series'][0]['data'][] = (int)$ii->si;
                    $info['series'][1]['data'][] = (int)$ii->no;
                    // $dx1[$keyi] = (int)$ii->t1;
                    // $dx2[$keyi] = (int)$ii->tt - (int)$ii->t1;
                }
                return response()->json(compact('info', 'base'));
            case 'anal2':
                // $tt = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0)->count();
                // $ssa = SFLRepositorio::get_locals($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1)->count();
                $tt = SFLRepositorio::get_localsx($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0); //->count();
                $ssa = SFLRepositorio::get_localsx($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 1); //->count();
                $nsa = $tt - $ssa;
                $info = [];
                $info[] = ['name' => 'SANEADO', 'y' => $ssa];
                $info[] = ['name' => 'NO SANEADO', 'y' => $nsa];
                // $info = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $reg['fuente'] = 'Siagie - MINEDU';
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                // $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
                // return [];
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla1($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->estado);

                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                // $npro = Ubigeo::where(DB::raw('length(codigo)'), 4)->where('nombre', 'CORONEL PORTILLO')->first();
                // $ndis = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('nombre', 'CALLERIA')->first();
                // // $excel = DB::select('call edu_pa_sfl_porlocal_distrito(?,?,?,?)', [0, 0, 0, 0]);
                // return response()->json(compact('npro', 'ndis'));
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla2($rq->anio, $rq->ugel, $rq->provincia, $rq->distrito, 0);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla2', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla3':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla3($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla3', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));
            case 'tabla4':
                $base = SFLRepositorio::get_iiee(2024, 0, 0, 0, 0);
                // $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla3($rq->indicador, $rq->anio);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla4', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            default:
                return [];
        }
    }

    public function PactoRegionalEduPacto2FindMes($anio)
    {
        $impMax = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaController::$FUENTE);
        $query = CuboPacto1::from('edu_cubo_pacto01_matriculados as ipa')->join('par_mes as m', 'm.id', '=', 'ipa.mes_id')->distinct()->select('m.id', 'm.mes')->where('ipa.anio', $anio);
        if ($anio == date('Y')) $query = $query->where('ipa.mes', '<=', $impMax->mes);
        $query = $query->orderBy('m.id')->get();
        return $query;
    }


    public function exportarPDFx($id)
    {
        $ind = IndicadorGeneral::select('codigo', 'ficha_tecnica')->where('id', $id)->first();
        if ($ind->ficha_tecnica) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="xxxxx"'); // Forzar el nombre del archivo
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

    public function exportarPDFxx($id)
    {
        $ind = IndicadorGeneral::select('codigo', 'ficha_tecnica')->where('id', $id)->first();

        if (!$ind || !$ind->ficha_tecnica) {
            return response()->json(['error' => 'Archivo PDF no encontrado'], 404);
        }

        $nombreArchivo = 'Ficha_Tecnica_' . $ind->codigo . '.pdf'; // Nombre personalizado

        $pdfContenido = base64_decode($ind->ficha_tecnica);

        return response()->streamDownload(function () use ($pdfContenido) {
            echo $pdfContenido;
        }, $nombreArchivo, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"'
        ]);
    }

    public function exportarPDF($id)
    {
        $ind = IndicadorGeneral::select('codigo', 'ficha_tecnica')->where('id', $id)->first();

        if (!$ind || !$ind->ficha_tecnica) {
            return response()->json(['error' => 'Archivo PDF no encontrado'], 404);
        }

        $nombreArchivo = 'Ficha_Tecnica_' . $ind->codigo . '.pdf'; // Nombre personalizado
        $pdfContenido = base64_decode($ind->ficha_tecnica);

        return response($pdfContenido)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $nombreArchivo . '"');
    }




    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }

    public function PDRCActualizar(Request $rq)
    {
        $imp = null;
        $gls = 0;
        $gl = 0;
        switch ($rq->codigo) {
            case 'PDRC-EDU-01':
                $gls = 2169;
                $gl = 13233.94;
                $num = $gls;
                $den = $gl;
                $actualizado =    'Actualizado: 31/12/2023';
                break;
            case 'PDRC-EDU-02':
                $gls = 1307.6;
                $gl = 13233.94;
                $num = $gls;
                $den = $gl;
                $actualizado =  'Actualizado: 31/12/2023';
                break;
            case 'PDRC-EDU-03':
                $gls = 4052;
                $gl = 4757;
                $num = $gls;
                $den = $gl;
                $actualizado =  'Actualizado: 20/11/2023';
                break;
            case 'PDRC-EDU-04':
                $gls = 3158;
                $gl = 4075;
                $num = $gls;
                $den = $gl;
                $actualizado =   'Actualizado: 31/12/2023';
                break;
            case 'PDRC-EDU-05':
                $gls = 139;
                $gl = 1449;
                $num = $gls;
                $den = $gl;
                $actualizado =   'Actualizado: 31/12/2023';
                break;
            default:
                break;
        }

        $avance =  round(100 * ($gl > 0 ? $gls / $gl : 0), 1);

        $meta = '100%';

        $cumple = $gls >= $gl;

        return response()->json(compact('avance', 'actualizado', 'meta', 'cumple', 'num', 'den'));
    }

    public function PDRCDetalle($indicador_id)
    {
        $ind = IndicadorGeneralRepositorio::findNoFichatecnica($indicador_id);
        switch ($ind->codigo) {
            case 'PDRC-EDU-01':

                return redirect()->route('logrosaprendizaje.evaluacionmuestral');

            case 'PDRC-EDU-02':
                return redirect()->route('logrosaprendizaje.evaluacionmuestral');

            case 'PDRC-EDU-03':
                return redirect()->route('panelcontrol.educacion.indicador.nuevos.05');

            case 'PDRC-EDU-04':
                return redirect()->route('panelcontrol.educacion.indicador.nuevos.04');
            case 'PDRC-EDU-05':
                return redirect()->route('serviciosbasicos.principal');

            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    public function PDRC()
    {
        return view('salud.Indicadores.PDRC');
    }

    public function PDRCEdu()
    {
        $sector = 4;
        $instrumento = 1;
        $indedu = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);

        $ind = IndicadorGeneralRepositorio::findNoFichatecnicaCodigo('PDRC-EDU-01');
        $anio = IndicadorGeneralMetaRepositorio::getPacto1Anios($ind->id);
        $provincia = UbigeoRepositorio::provincia('25');

        // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronActasController::$FUENTE['pacto_1']);
        // // return response()->json(compact('imp'));
        $aniomax = date('Y');

        return view('salud.Indicadores.PDRCEdu', compact('indedu', 'anio', 'provincia', 'aniomax'));
    }

    public function PEI()
    {
        return view('salud.Indicadores.PEI');
    }
}
