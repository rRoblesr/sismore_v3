<?php

namespace App\Http\Controllers\Salud;

use App\Exports\eduConvenioFed1Export;
use App\Exports\eduConvenioFed2Export;
use App\Exports\pactoregionalSal1Export;
use App\Exports\pactoregionalSal2Export;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Educacion\ImporMatriculaController;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Http\Controllers\Educacion\ImporPadronNominalController as eduImporPadronNominalController;
use App\Models\Educacion\CuboFEDPN;
use App\Models\Educacion\CuboPacto1;
use App\Models\Educacion\CuboPacto2;
use App\Models\Educacion\Importacion;
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
use App\Repositories\Educacion\CuboPacto1Repositorio as EduCuboPacto1Repositorio;
use App\Repositories\Educacion\CuboPacto2Repositorio as EduCuboPacto2Repositorio;
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
use App\Repositories\Salud\PadronNominalRepositorio as salPadronNominalRepositorio;
use App\Repositories\Educacion\PadronNominalRepositorio as eduPadronNominalRepositorio;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

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
        $updateMin1 = PoblacionPNRepositorio::actualizado();
        $updateMin2 = EduCuboPacto1Repositorio::actualizado();
        $updateMin3 = EduCuboPacto2Repositorio::actualizado();
        $anios = [$updateMin1->anio, $updateMin2->anio, $updateMin3->anio];
        $anio = array_unique($anios);
        $provincia = UbigeoRepositorio::provincia('25');
        $aniomax = date('Y');
        // return compact('indedu', 'anio', 'provincia', 'aniomax');
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
                $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($fuente, $rq->anio, 0);
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

            case 'DIT-EDU-01':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $actualizado =  'Actualizado: ' . date('d/m/Y', strtotime($imp->fechaActualizacion));
                $updateMin1 = PoblacionPNRepositorio::actualizado();
                $updateMin2 = EduCuboPacto1Repositorio::actualizado();
                $mes = min($updateMin1->mes, $updateMin2->mes);

                $gl = (int)PoblacionPNRepositorio::conteo3a5_acumulado($rq->anio, $mes, $rq->provincia, $rq->distrito, 0);
                $gls = EduCuboPacto1Repositorio::pacto1_matriculados($rq->anio, $mes, $rq->provincia, $rq->distrito);
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);

                break;
            case 'DIT-EDU-02':
                $gl = EduCuboPacto2Repositorio::locales($rq->provincia, $rq->distrito, 0);
                $gls = EduCuboPacto2Repositorio::locales($rq->provincia, $rq->distrito, 1);
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                $fecha = EduCuboPacto2Repositorio::inscripcion_max($rq->provincia, $rq->distrito, 0);
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
            case 'DIT-EDU-06':
                $base = CuboFEDPN::where('anio', 2025)->select(
                    DB::raw('sum(num) as gl'),
                    DB::raw('sum(numx) as gls'),
                    DB::raw('sum(num)-sum(numx) as gln'),
                    DB::raw('round(100*sum(numx)/sum(num),1) as indicador')
                )->first();
                $gl = (int)$base->gl;
                $gls = (int)$base->gls;
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                $actualizado =  'Actualizado: 31/03/2025';
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
                $anio = EduCuboPacto1Repositorio::anios();
                $aniomax = $anio->max('anio');
                // $am = DB::table('edu_cubo_pacto01_matriculados')->whereIn('nivelmodalidad_codigo', ['A2', 'A3', 'A5'])->where('anio', $anio->where('anio', '<=', date('Y'))->max('anio'))->max('mes_id');
                $am = EduCuboPacto1Repositorio::ultimoaniodisponible($anio, date('Y'));
                // $ap = PoblacionPN::where('anio', $anio->where('anio', '<=', date('Y'))->max('anio'))->max('mes_id');
                $ap = PoblacionPNRepositorio::ultimoaniodisponible($anio, date('Y'));
                $mesmax = 0;
                if ($mesmax < $am) $mesmax = $am;
                if ($mesmax < $ap) $mesmax = $ap;
                $mesmin = 20;
                if ($mesmin > $am) $mesmin = $am;
                if ($mesmin > $ap) $mesmin = $ap;
                $mes = Mes::select('id', 'mes')->where('codigo', '<=', $mesmax)->get();
                $provincia = UbigeoRepositorio::provincia('25');
                return view('salud.Indicadores.PactoRegionalEduPacto1', compact('actualizado', 'anio', 'mes', 'mesmin', 'aniomax', 'provincia',  'ind'));

            case 'DIT-EDU-02':
                $f = EduCuboPacto2Repositorio::inscripcion_max(0, 0, 0);
                $actualizado = 'Actualizado al ' . $f->dia . ' de ' . $this->mesname[$f->mes - 1] . ' del ' . $f->anio;
                $anio = EduCuboPacto2Repositorio::anios_inscripcion();
                // $provincia = UbigeoRepositorio::provincia('25');
                // $mes = DB::table(DB::raw('(select distinct month(fecha_inscripcion) as mes from edu_sfl)as sfl'))
                //     ->join('par_mes as m', 'm.id', '=', 'sfl.mes')->select('m.id', 'm.mes')->get();
                $aniomax = $anio->max('anio');
                // $mesmax = $mes->max('id');
                return view('salud.Indicadores.PactoRegionalEduPacto2', compact('actualizado', 'anio', 'aniomax', 'ind'));
            case 'DIT-EDU-03':
                return '';
            case 'DIT-EDU-04':
                return '';
            case 'DIT-EDU-06':
                $fuente = eduImporPadronNominalController::$FUENTE;
                $anio = CuboFEDPN::distinct()->select('anio')->get();
                $aniomax = $anio->max('anio');
                // $actualizado = '30/04/2025';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(eduImporPadronNominalController::$FUENTE);
                $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $provincia = UbigeoRepositorio::provincia('25');
                return view('educacion.Indicadores.ConvenioFEDMC0502', compact('actualizado', 'fuente', 'anio', 'provincia', 'aniomax', 'ind'));

            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    // ############ salud pacto 1 #################
    public function PactoRegionalSalPacto1Reports(Request $rq)
    {
        ini_set('memory_limit', '-1');
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
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

                //===========================================
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
                        $value->numerador,
                        $value->denominador,
                        // $value->indicador
                        $value->indicador < 51 ?
                            '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' . number_format($value->indicador, 1) . '%</span>' : ($value->indicador < 100 ?
                                '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' . number_format($value->indicador, 1) . '%</span>' :
                                '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' . number_format($value->indicador, 1) . '%</span>'
                            ),
                        $value->indicador >= 90 ? 1 : 0
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
            //===============================
            case 'tabla0201':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboPacto1PadronNominal::where('importacion', $impMaxAnio)->where('cui_atencion', $rq->cod_unico)->whereIn('tipo_doc', ['DNI', 'CNV'])->get();

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
                );
                return response()->json($result);

            case 'tabla3':
                $query = DB::table('sal_cubo_pacto1_padron_nominal')->where('anio', $rq->anio)->where('mes', $rq->mes);

                // Aplicar filtros iniciales
                // if ($rq->filled('anio')) {
                //     $query->where('anio', $rq->anio);
                // }

                // if ($rq->filled('mes')) {
                //     $query->where('mes', $rq->mes);
                // }

                if ($rq->provincia > 0) {
                    $query->where('provincia_id', $rq->provincia);
                }

                if ($rq->distrito > 0) {
                    $query->where('distrito_id', $rq->distrito);
                }

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('documento_link', function ($row) {
                        return '<a href="#" class="btn-documento" data-id="' . $row->id . '" data-dni="' . $row->num_doc . '">' . $row->num_doc . '</a>';
                    })
                    ->addColumn('cui_atencion_formatted', function ($row) {
                        $cui = $row->cui_atencion > 0 ? str_pad($row->cui_atencion, 8, '0', STR_PAD_LEFT) : '';
                        return '<a href="#" class="btn-cui" data-cui="' . $row->cui_atencion . '" data-establecimiento="' . htmlspecialchars($row->nombre_establecimiento) . '">' . $cui . '</a>';
                    })
                    ->addColumn('estado_badge', function ($row) {
                        if ($row->num == 1) {
                            return '<span class="badge badge-pill badge-success" style="font-size:100%;">Cumple</span>';
                        } else {
                            return '<span class="badge badge-pill badge-danger" style="font-size:100%;">No Cumple</span>';
                        }
                    })
                    ->rawColumns(['documento_link', 'cui_atencion_formatted', 'estado_badge'])
                    ->make(true);

                // if ($rq->ajax()) {
                //     $query = CuboPacto1PadronNominal::query()
                //         ->select([
                //             'id',
                //             'tipo_doc',
                //             'num_doc',
                //             'departamento',
                //             'provincia',
                //             'distrito',
                //             'centro_poblado',
                //             'cui_atencion',
                //             'nombre_establecimiento',
                //             'num',
                //             'den',
                //             'nombre_completo',
                //             'fecha_nacimiento',
                //             'edad',
                //             'direccion',
                //             'seguro',
                //             'num_doc_madre',
                //             'nombre_completo_madre'
                //         ])
                //         ->where('anio', $rq->anio)
                //         ->where('mes', $rq->mes);

                //     if (!empty($rq->provincia)) {
                //         $query->where('provincia_id', $rq->provincia);
                //     }

                //     if (!empty($rq->distrito)) {
                //         $query->where('distrito_id', $rq->distrito);
                //     }

                //     return FacadesDataTables::eloquent($query)
                //         ->addColumn('estado', function ($row) {
                //             $estado = $row->num == 1 ? 'Cumple' : 'No Cumple';
                //             $badge = $row->num == 1 ? 'success' : 'danger';
                //             return "<span class='badge badge-pill badge-{$badge}'>{$estado}</span>";
                //         })
                //         ->addColumn('documento', function ($row) {
                //             return "<a href='#' class='text-primary documento-link' data-id='{$row->id}' data-toggle='modal' data-target='#registroModal'>{$row->num_doc}</a>";
                //         })
                //         ->addColumn('cui_atencion', function ($row) {
                //             $cui = $row->cui_atencion > 0 ? str_pad($row->cui_atencion, 8, '0', STR_PAD_LEFT) : '';
                //             return "<a href='#' class='text-info cui-link' data-cui='{$cui}' data-nombre='{$row->nombre_establecimiento}' data-toggle='modal' data-target='#cuiModal'>{$cui}</a>";
                //         })
                //         ->addColumn('DT_RowIndex', function ($row) use ($rq) {
                //             static $contador = 0;
                //             $contador++;
                //             return $contador;
                //         })
                //         ->rawColumns(['estado', 'documento', 'cui_atencion'])
                //         ->make(true);
                // }

                // if ($rq->ajax()) {
                //     $data = CuboPacto1PadronNominal::select([
                //         'id',
                //         'tipo_doc',
                //         'num_doc',
                //         'departamento',
                //         'provincia',
                //         'distrito',
                //         'centro_poblado',
                //         'cui_atencion',
                //         'nombre_establecimiento',
                //         'num',
                //         'den'
                //     ])->where('anio', $rq->anio)->where('mes', $rq->mes);

                //     if (!empty($rq->provincia)) {
                //         $data->where('provincia_id', $rq->provincia);
                //     }
                //     if (!empty($rq->distrito_id)) {
                //         $data->where('distrito_id', $rq->distrito);
                //     }

                //     $contador = $rq->start;

                //     return FacadesDataTables::eloquent($data)
                //         ->addColumn('DT_RowIndex', function ($row) use (&$contador) {
                //             return ++$contador;
                //         })
                //         ->addColumn('id', function ($row) use (&$contador) {
                //             return $contador; 
                //         })
                //         ->addColumn('estado', function ($row) {
                //             $estado = $row->num == 1 ? 'Cumple' : 'No Cumple';
                //             $badge = $row->num == 1 ? 'success' : 'danger';
                //             return "<span class='badge badge-pill badge-{$badge}' style=\"font-size:100%;\">{$estado}</span>";
                //         })
                //         ->addColumn('documento', function ($row) {
                //             return "<a href='#' class='text-primary documento-link' data-doc='{$row->num_doc}' data-toggle='modal' data-target='#documentoModal'>{$row->num_doc}</a>";
                //         })
                //         ->addColumn('cui_atencion', function ($row) {
                //             $cui = $row->cui_atencion > 0 ? str_pad($row->cui_atencion, 8, '0', STR_PAD_LEFT) : '';
                //             return "<a href='#' class='text-info cui-link' data-cui='{$row->cui_atencion}' data-toggle='modal' data-target='#cuiModal'>{$cui}</a>";
                //         })
                //         ->rawColumns(['estado', 'documento', 'cui_atencion'])
                //         ->make(true);
                // }

            case 'tabla3__':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboPacto1PadronNominalRepositorio::pacto01Tabla03($impMaxAnio, $rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                // $query = CuboPacto1PadronNominal::where('importacion', $impMaxAnio)->whereIn('tipo_doc', ['DNI', 'CNV']);
                // if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                // if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                // $query = $query->get();

                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->tipo_doc,
                        $value->num_doc,
                        $value->departamento,
                        $value->provincia,
                        $value->distrito,
                        $value->centro_poblado,
                        $value->cui_atencion > 0 ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '',
                        $value->cui_atencion > 0 ? $value->nombre_establecimiento : '',
                        $value->num,
                    );
                }
                $result = array(
                    "draw" => $draw,
                    "recordsTotal" => $start,
                    "recordsFiltered" => $length,
                    "data" => $data,
                );
                return response()->json($result);

            default:
                return [];
        }
    }

    public function getDetalle(Request $request)
    {
        $id = $request->get('id');

        $registro = DB::table('sal_cubo_pacto1_padron_nominal')
            ->where('id', $id)
            ->first();

        if (!$registro) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        return response()->json($registro);
    }

    public function PactoRegionalSalPacto1Reports2(Request $rq)
    {
        $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);

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
        $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);

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

    public function PactoRegionalSalPacto1Export($div, $fuente, $indicador, $anio, $mes, $provincia, $distrito)
    {

        switch ($div) {
            case 'tabla2':
                $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($fuente, $anio, $mes);
                $base = CuboPacto1PadronNominalRepositorio::pacto01Tabla02($impMaxAnio, $indicador, $anio, $mes, $provincia, $distrito);
                return compact('base');

            case 'tabla3':
                $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($fuente, $anio, $mes);
                $base = CuboPacto1PadronNominalRepositorio::pacto01Tabla03($impMaxAnio, $indicador, $anio, $mes, $provincia, $distrito);
                return compact('base');

            default:
                return [];
        }
    }

    public function PactoRegionalSalPacto1download($div, $fuente, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($anio > 0) {
            switch ($div) {
                case 'tabla2':
                    $name = 'Establecimiento de Salud ' . date('Y-m-d') . '.xlsx';
                    break;
                case 'tabla3':
                    $name = 'Padron Nominal ' . date('Y-m-d') . '.xlsx';
                    break;
                default:
                    $name = 'sin nombre.xlsx';
                    break;
            }

            return Excel::download(new pactoregionalSal1Export($div, $fuente, $indicador, $anio, $mes, $provincia, $distrito), $name);
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
        $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
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
                $excel = view('salud.Indicadores.PactoRegionalSalPacto3tabla1', compact('base', 'ndis'))->render();
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
        $impMaxAnio = salPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
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
                $ssa = EduCuboPacto1Repositorio::pacto1_matriculados($rq->anio, $rq->mes, $rq->provincia, $rq->distrito);
                $nsa = $loc - $ssa;
                $nsa = $nsa >= 0 ? $nsa : 0;
                $rin = number_format(100 * ($loc > 0 ? $ssa / $loc : 0), 1);
                return response()->json(['rq' => $rq->all(), 'loc' => number_format($loc, 0), 'ssa' => number_format($ssa, 0), 'nsa' => number_format($nsa), 'rin' => $rin]);

            case 'anal1':
                $est = EduCuboPacto1Repositorio::pacto1_matriculados_mensual($rq->anio, 0, 0, $rq->distrito);
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
                $data1 = EduCuboPacto1Repositorio::pacto1_matriculados_edad($rq->anio, $rq->mes, 0, $rq->distrito);
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
                $loc = EduCuboPacto2Repositorio::PactoRegionalEduPacto2Reports_locales($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0);
                $ssa = EduCuboPacto2Repositorio::PactoRegionalEduPacto2Reports_locales($rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 1);
                $nsa = $loc - $ssa;
                $rin = number_format(100 * ($loc > 0 ? $ssa / $loc : 0), 1);
                return response()->json(['loc' => number_format($loc, 0), 'ssa' => number_format($ssa, 0), 'nsa' => number_format($nsa), 'rin' => $rin]);

            case 'anal1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2anal1(0, 0, $rq->provincia, $rq->distrito, 0);
                $info['series'] = [];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' =>    'SANEADO', 'data' => []];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'NO SANEADO', 'data' => []];
                foreach ($base as $keyi => $ii) {
                    $info['categoria'][] = $ii->provincia;
                    $info['series'][0]['data'][] = (int)$ii->si;
                    $info['series'][1]['data'][] = (int)$ii->no;
                }
                return response()->json(compact('info', 'base'));
            case 'anal2':
                $info = EduCuboPacto2Repositorio::PactoRegionalEduPacto2Reports_anal2($rq->provincia, $rq->distrito);
                $reg['fuente'] = 'Siagie - MINEDU';
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla1_opt01($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->estado);
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla1', compact('base', 'ndis'))->render();
                // $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla1_opt02($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, $rq->estado);
                // $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla1_opt02', compact('base', 'ndis'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla2':
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla2(0, 0, $rq->provincia, $rq->distrito, 0);
                $aniob = $rq->anio;
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla2', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla3':
                $aniob = $rq->anio;
                $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla3_opt01($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0);
                $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla3', compact('base', 'ndis', 'aniob'))->render();
                // $base = IndicadorGeneralMetaRepositorio::getEduPacto2tabla3_opt02($rq->indicador, $rq->anio, $rq->mes, $rq->provincia, $rq->distrito, 0);
                // $excel = view('salud.Indicadores.PactoRegionalEduPacto2tabla3_opt02', compact('base', 'ndis', 'aniob'))->render();
                return response()->json(compact('excel', 'base'));

            case 'tabla4':
                $base = SFLRepositorio::get_iiee(2024, 0, 0, 0, 0);
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

    // public function ConvenioFED()
    // {
    //     return view('educacion.Indicadores.ConvenioFED');
    // }

    public function ConvenioFEDEdu()
    {
        $sector = 4;
        $instrumento = 6;
        $indedu = IndicadorGeneralRepositorio::find_pactoregional($sector, $instrumento);
        //$anio = CuboFEDPN::distinct()->select('anio')->get();
        $anio = CuboFEDPN::max('anio');
        $mes = CuboFEDPN::where('anio', $anio)->max('mes');
        $provincia = UbigeoRepositorio::provincia('25');
        $aniomax = date('Y');

        return view('educacion.Indicadores.ConvenioFED', compact('indedu', 'anio', 'mes', 'provincia', 'aniomax'));
    }

    public function ConvenioFEDEduActualizar(Request $rq)
    {
        $imp = null;
        $gls = 0;
        $gl = 0;
        switch ($rq->codigo) {
            case 'MC-05.01':
                $base = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)->select(
                    DB::raw('sum(den) as gl'),
                    DB::raw('sum(num) as gls'),
                    DB::raw('sum(den)-sum(num) as gln'),
                    DB::raw('round(100*sum(num)/sum(den),1) as indicador')
                )->first();
                $gl = (int)$base->gl;
                $gls = (int)$base->gls;
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                $actualizado =  'Actualizado: 30/04/2025';
                break;
            case 'MC-05.02':
                $base = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)->select(
                    DB::raw('sum(num) as gl'),
                    DB::raw('sum(numx) as gls'),
                    DB::raw('sum(num)-sum(numx) as gln'),
                    DB::raw('round(100*sum(numx)/sum(num),1) as indicador')
                )->first();
                $gl = (int)$base->gl;
                $gls = (int)$base->gls;
                $num = number_format($gls, 0);
                $den = number_format($gl, 0);
                $actualizado =  'Actualizado: 30/04/2025';
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
            default:
                break;
        }
        $avance =  round(100 * ($gl > 0 ? $gls / $gl : 0), 1);
        $meta = '100%';
        $cumple = $gls >= $gl;
        return response()->json(compact('avance', 'actualizado', 'meta', 'cumple', 'num', 'den'));
    }

    public function ConvenioFEDEduDetalle($indicador_id)
    {
        $ind = IndicadorGeneralRepositorio::findNoFichatecnica($indicador_id);
        switch ($ind->codigo) {
            case 'MC-05.01':
                $fuente = eduImporPadronNominalController::$FUENTE;
                $anio = CuboFEDPN::distinct()->select('anio')->get();
                $aniomax = $anio->max('anio');
                $actualizado = '30/04/2025';
                $provincia = UbigeoRepositorio::provincia('25');
                $ugel = CuboFEDPN::distinct()->select('ugel')->where('anio', $aniomax)->get();

                return view('educacion.Indicadores.ConvenioFEDMC0501', compact('actualizado', 'fuente', 'anio', 'ugel', 'provincia', 'aniomax', 'ind'));
            case 'MC-05.02':
                $fuente = eduImporPadronNominalController::$FUENTE;
                $anio = CuboFEDPN::distinct()->select('anio')->where('anio', 2025)->get(); //ImportacionRepositorio::anios_porfuente_select(eduImporPadronNominalController::$FUENTE);
                // $imp = ImportacionRepositorio::ImportacionMax_porfuente(eduImporPadronNominalController::$FUENTE);
                // $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mesname[$imp->mes - 1] . ' del ' . $imp->anio;
                $actualizado = '31/03/2025';
                $provincia = UbigeoRepositorio::provincia('25');
                $ugel = CuboFEDPN::distinct()->select('ugel')->where('anio', 2025)->get();
                $aniomax = $anio->max('anio');
                return view('educacion.Indicadores.ConvenioFEDMC0502', compact('actualizado', 'fuente', 'anio', 'ugel', 'provincia', 'aniomax', 'ind'));

            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    // ############ FED MC0501 #################
    public function ConvenioFEDEduMC0501Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        // $impMaxAnio = eduPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
        switch ($rq->div) {
            case 'head':
                $v = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)->select(
                    DB::raw('sum(den) as gl'),
                    DB::raw('sum(num) as gls'),
                    DB::raw('sum(den)-sum(num) as gln'),
                    DB::raw('round(100*sum(num)/sum(den),1) as indicador')
                );
                // if ($rq->ugel != 'TODOS') $v->where('ugel', $rq->ugel);
                if ($rq->provincia > 0) $v->where('dependencia', $rq->provincia);
                if ($rq->distrito > 0) $v->where('distrito_id', $rq->distrito);
                $v = $v->first();
                $ri = number_format($v->indicador, 1);
                $gls = number_format($v->gls, 0);
                $gln = number_format($v->gln, 0);
                $gl = number_format($v->gl, 0);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln, 'base' => $v, 'xx' => $rq->all()]);

            case 'anal1':
                $base = CuboFEDPN::select('distrito', DB::raw('100*sum(num)/sum(den) as indicador'))->where('anio', $rq->anio)->where('mes', $rq->mes);
                $base = $base->groupBy('distrito')->orderBy('indicador', 'desc')->get();
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->distrito;
                    $info['serie'][] = ['y' => round($value->indicador, 1), 'color' => (round($value->indicador, 1) > 95 ? '#43beac' : (round($value->indicador, 1) > 50 ? '#eb960d' : '#ef5350'))];
                }
                return response()->json(compact('info'));

            case 'anal2':
                $base = CuboFEDPN::select(
                    'provincia',
                    DB::raw('sum(if(num=1,1,0)) as pn'),
                    DB::raw('sum(if(den=1,1,0)) as pd'),
                    DB::raw('100*sum(num)/sum(den) as pi')
                )->where('anio', $rq->anio)->where('mes', $rq->mes);
                $base = $base->groupBy('provincia')->orderBy('pi', 'desc')->get();
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categorias'][] = $value->provincia;
                    $info['poblacion'][] = (int)$value->pd;
                    $info['matriculados'][] = (int)$value->pn;
                }
                return response()->json(compact('info', 'base'));

            case 'anal3': //lineas
                $base = CuboFEDPN::select(
                    'ugel',
                    DB::raw('sum(if(num=1,1,0)) as pn'),
                    DB::raw('sum(if(den=1,1,0)) as pd'),
                    DB::raw('100*sum(num)/sum(den) as pi')
                )->where('anio', $rq->anio)->where('mes', $rq->mes);
                $base = $base->groupBy('ugel')->orderBy('pi', 'desc')->get();
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categorias'][] = $value->ugel;
                    $info['poblacion'][] = (int)$value->pd;
                    $info['matriculados'][] = (int)$value->pn;
                }
                return response()->json(compact('info', 'base'));

            case 'tabla1':
                $base = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)
                    ->select(
                        'distrito_id',
                        'distrito',
                        DB::raw('sum(num) as numerador'),
                        DB::raw('sum(den) as denominador'),
                        DB::raw('round(100*sum(num)/sum(den),1) as indicador')
                    );
                $base = $base->groupBy('distrito_id', 'distrito')->orderBy('indicador', 'desc')->get();
                $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicador)->where('anio', $rq->anio)->pluck('valor', 'distrito');
                foreach ($base as $key => $value) {
                    $value->meta = $v3[$value->distrito_id] ?? 0;
                    $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
                }
                $excel = view('educacion.Indicadores.ConvenioFEDMC0501tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel'));

            case 'tabla2':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)
                    ->select(
                        'dni',
                        'provincia',
                        'distrito',
                        'centro_poblado_nombre',
                        'area_ccpp',
                        'eess',
                        'codmod_salud',
                        'iiee_salud',
                        'numx',
                        DB::raw('if(length(codmod_salud)=7,1,0) as cumple')
                    );
                // if ($rq->ugel != 'TODOS') $query->where('ugel', $rq->ugel);
                if ($rq->provincia > 0) $query->where('dependencia', $rq->provincia);
                if ($rq->distrito > 0) $query->where('distrito_id', $rq->distrito);
                if ($rq->mes > 0) $query->where('mes', $rq->mes);
                $query = $query->get();
                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->dni,
                        $value->provincia,
                        $value->distrito,
                        $value->centro_poblado_nombre,
                        $value->area_ccpp,
                        $value->eess,
                        // $value->cod_mod,
                        // $value->iiee,
                        $value->numx == 1 ? $value->codmod_salud : '',
                        $value->numx == 1 ? $value->iiee_salud : '',
                        $value->cumple == 0 ?
                            '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">NO</span>' :
                            '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">SI</span>'
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



            default:
                return [];
        }
    }

    public function ConvenioFEDEduMC0501Export($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        switch ($div) {
            // case 'tabla1':
            //     $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($indicador, $anio, $mes);
            //     $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
            //     return compact('excel', 'base');

            case 'tabla2':
                ini_set('memory_limit', '-1');
                set_time_limit(0);

                $base = CuboFEDPN::where('anio', $anio)
                    ->select(
                        'id',
                        'importacion_id',
                        'anio',
                        'mes',
                        'dni',
                        'apellido_paterno',
                        'apellido_materno',
                        'nombre',
                        'sexo',
                        'fecha_nacimiento',
                        'edad',
                        'tipo_edad',
                        'direccion',
                        'ubigeo',
                        'centro_poblado',
                        'centro_poblado_nombre',
                        'area_ccpp',
                        'codigo_ie',
                        'nombre_ie',
                        'tipo_doc_madre',
                        'num_doc_madre',
                        'apellido_paterno_madre',
                        'apellido_materno_madre',
                        'nombres_madre',
                        'celular_madre',
                        'grado_instruccion',
                        'lengua_madre',
                        'distrito_id',
                        'distrito',
                        'dependencia',
                        'provincia',
                        'ugel',
                        'eess',
                        'codmod_salud',
                        'iiee_salud',
                        'codmod_educacion',
                        'iiee_educacion',
                        'den',
                        'num',
                        'numx',
                        DB::raw('if(length(codmod_educacion)=7,1,0) as cumple')
                    );
                if ($mes > 0) $base->where('mes', $mes);
                if ($provincia > 0) $base->where('dependencia', $provincia);
                if ($distrito > 0) $base->where('distrito_id', $distrito);
                $base = $base->get();

                return compact('base');


            default:
                return [];
        }
    }

    public function ConvenioFEDEduMC0501Reports2(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = CuboFEDPN::where('anio', $rq->anio)
            ->select(
                'dni',
                'provincia',
                'distrito',
                'centro_poblado_nombre',
                'area_ccpp',
                'eess',
                'cod_mod',
                'iiee',
                DB::raw('if(length(cod_mod)=7,1,0) as cumple')
            );

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
        ];

        return response()->json($result);
    }

    public function ConvenioFEDEduMC0501Reports3(Request $rq)
    {
        $impMaxAnio = eduPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);

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

    public function ConvenioFEDEduMC0501Reports1download($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($anio > 0) {
            switch ($div) {
                // case 'tabla1':
                //     $name = 'Listado de establecimientos de salud ' . date('Y-m-d') . '.xlsx';
                //     break;
                case 'tabla2':
                    $name = 'Padrón Nominal ' . date('Y-m-d') . '.xlsx';
                    break;
                default:
                    $name = 'sin nombre.xlsx';
                    break;
            }

            return Excel::download(new eduConvenioFed1Export($div, $indicador, $anio, $mes, $provincia, $distrito), $name);
        }
    }

    public function ConvenioFEDbuscarninio($dni)
    {
        $menor = CuboFEDPN::where('dni', $dni)->firstOrFail();
        $ubigeo = UbigeoRepositorio::ubicacionUbigeo($menor->ubigeo);
        return response()->json([
            'dni' => $menor->dni,
            'apellidos' => $menor->apellido_paterno . ' ' . $menor->apellido_materno,
            'nombres' => $menor->nombre,
            'sexo' => $menor->sexo,
            'nacimiento' => $menor->fecha_nacimiento,
            'edad' => $menor->edad,
            'departamento' => $ubigeo->depn,
            'provincia' => $menor->provincia,
            'distrito' => $menor->distrito,
            'centroPoblado' => $menor->centro_poblado_nombre,
            'direccion' => $menor->direccion,
            'celular' => $menor->celular_madre,
            'apellidosMadre' => $menor->apellido_paterno_madre . ' ' . $menor->apellido_materno_madre,
            'nombresMadre' => $menor->nombres_madre
        ]);
    }

    // ############ FED MC0502 #################
    public function ConvenioFEDEduMC0502Reports(Request $rq)
    {
        if ($rq->distrito > 0) $ndis = Ubigeo::find($rq->distrito)->nombre;
        else $ndis = '';
        // $impMaxAnio = eduPadronNominalRepositorio::PNImportacion_idmax($rq->fuente, $rq->anio, $rq->mes);
        switch ($rq->div) {
            case 'head':
                $v = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)->select(
                    DB::raw('sum(num) as gl'),
                    DB::raw('sum(numx) as gls'),
                    DB::raw('sum(num)-sum(numx) as gln'),
                    DB::raw('round(100*sum(numx)/sum(num),1) as indicador')
                );
                // if ($rq->ugel != 'TODOS') $v->where('ugel', $rq->ugel);
                if ($rq->provincia > 0) $v->where('dependencia', $rq->provincia);
                if ($rq->distrito > 0) $v->where('distrito_id', $rq->distrito);
                $v = $v->first();
                $ri = number_format($v->indicador, 1);
                $gls = number_format($v->gls, 0);
                $gln = number_format($v->gln, 0);
                $gl = number_format($v->gl, 0);
                return response()->json(['aa' => $rq->all(), 'ri' => $ri, 'gl' => $gl, 'gls' => $gls, 'gln' => $gln, 'base' => $v]);

            case 'anal1':
                $base = CuboFEDPN::select('distrito', DB::raw('100*sum(numx)/sum(num) as indicador'))->where('anio', $rq->anio)->where('mes', $rq->mes);
                $base = $base->groupBy('distrito')->orderBy('indicador', 'desc')->get();
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categoria'][] = $value->distrito;
                    $info['serie'][] = ['y' => round($value->indicador, 1), 'color' => (round($value->indicador, 1) > 95 ? '#43beac' : (round($value->indicador, 1) > 50 ? '#eb960d' : '#ef5350'))];
                }
                return response()->json(compact('info'));

            case 'anal2':
                $base = CuboFEDPN::select(
                    'provincia',
                    DB::raw('sum(numx) as pn'),
                    DB::raw('sum(num) as pd'),
                    DB::raw('100*sum(numx)/sum(num) as pi')
                )->where('anio', $rq->anio)->where('mes', $rq->mes)->where('num', '1');
                $base = $base->groupBy('provincia')->orderBy('pi', 'desc')->get();

                $info = [];
                foreach ($base as $key => $value) {
                    $info['categorias'][] = $value->provincia;
                    $info['poblacion'][] = (int)$value->pd;
                    $info['matriculados'][] = (int)$value->pn;
                }

                return response()->json(compact('info', 'base'));

            case 'anal3': //lineas
                $base = CuboFEDPN::select(
                    'ugel',
                    DB::raw('sum(numx) as pn'),
                    DB::raw('sum(num) as pd'),
                    DB::raw('100*sum(numx)/sum(num) as pi')
                )->where('anio', $rq->anio)->where('mes', $rq->mes)->where('num', '1');
                $base = $base->groupBy('ugel')->orderBy('pi', 'desc')->get();
                $info = [];
                foreach ($base as $key => $value) {
                    $info['categorias'][] = $value->ugel;
                    $info['poblacion'][] = (int)$value->pd;
                    $info['matriculados'][] = (int)$value->pn;
                }
                return response()->json(compact('info', 'base'));

            case 'tabla1':
                $base = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)
                    ->select(
                        'distrito_id',
                        'distrito',
                        DB::raw('sum(numx) as numerador'),
                        DB::raw('sum(num) as denominador'),
                        DB::raw('round(100*sum(numx)/sum(num),1) as indicador')
                    );
                $base = $base->groupBy('distrito_id', 'distrito')->orderBy('indicador', 'desc')->get();
                $v3 = IndicadorGeneralMeta::where('indicadorgeneral', $rq->indicador)->where('anio', $rq->anio)->pluck('valor', 'distrito');
                foreach ($base as $key => $value) {
                    $value->meta = $v3[$value->distrito_id] ?? 0;
                    $value->cumple = $value->indicador >= $value->meta ? 1 : 0;
                }
                $excel = view('educacion.Indicadores.ConvenioFEDMC0502tabla1', compact('base', 'ndis'))->render();
                return response()->json(compact('excel'));

            case 'tabla2':
                $draw = intval($rq->draw);
                $start = intval($rq->start);
                $length = intval($rq->length);

                $query = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)->where('num', '1')
                    ->select(
                        'dni',
                        'provincia',
                        'distrito',
                        'centro_poblado_nombre',
                        'area_ccpp',
                        'eess',
                        // DB::raw('if(length(cod_mod)=7 and numx=1,cod_mod,"") as cod_mod'),
                        'codmod_salud',
                        // DB::raw('if(length(cod_mod)=7 and numx=1,iiee,"") as iiee'),
                        'iiee_salud',
                        DB::raw('if(length(codmod_salud)=7 and numx=1,1,0) as cumple')
                    );
                // if ($rq->ugel != 'TODOS') $query->where('ugel', $rq->ugel);
                if ($rq->provincia > 0) $query->where('dependencia', $rq->provincia);
                if ($rq->distrito > 0) $query->where('distrito_id', $rq->distrito);
                $query = $query->get();
                $data = [];
                foreach ($query as $key => $value) {
                    $data[] = array(
                        $key + 1,
                        $value->dni,
                        $value->provincia,
                        $value->distrito,
                        $value->centro_poblado_nombre,
                        $value->area_ccpp,
                        $value->eess,
                        $value->codmod_salud,
                        $value->iiee_salud,
                        $value->cumple == 0 ?
                            '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">NO</span>' :
                            '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">SI</span>'
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

            case 'tabla3':
                $base = CuboFEDPN::where('anio', $rq->anio)->where('mes', $rq->mes)
                    ->select(
                        'provincia',
                        DB::raw('round(100*sum(numx)/sum(num),1) as it'),
                        DB::raw('sum(if(area_ccpp="URBANA" and num=1,1,0)) as mu'),
                        DB::raw('sum(if(area_ccpp="RURAL" and num=1,1,0)) as mr'),
                        DB::raw('sum(num) as mt'),

                        DB::raw('sum(if(area_ccpp="URBANA" and numx=1,1,0)) as hu'),
                        DB::raw('sum(if(area_ccpp="RURAL" and numx=1,1,0)) as hr'),
                        DB::raw('sum(numx) as ht'),

                        DB::raw('sum(if(area_ccpp="URBANA" and num=1,1,0)) as um'),
                        DB::raw('sum(if(area_ccpp="URBANA" and numx=1,1,0)) as uh'),
                        DB::raw('round(100*sum(if(area_ccpp="URBANA" and numx=1,1,0))/sum(if(area_ccpp="URBANA" and num=1,1,0)),1) as uit'),

                        DB::raw('sum(if(area_ccpp="RURAL" and num=1,1,0)) as rm'),
                        DB::raw('sum(if(area_ccpp="RURAL" and numx=1,1,0)) as rh'),
                        DB::raw('round(100*sum(if(area_ccpp="RURAL" and numx=1,1,0))/sum(if(area_ccpp="RURAL" and num=1,1,0)),1) as rit'),
                    );
                $base = $base->groupBy('provincia')->orderBy('it', 'desc')->get();
                $foot = clone $base[0];
                $foot->it = 0;

                $foot->mu = 0;
                $foot->mr = 0;
                $foot->mt = 0;

                $foot->hu = 0;
                $foot->hr = 0;
                $foot->ht = 0;

                $foot->um = 0;
                $foot->uh = 0;
                $foot->uit = 0;

                $foot->rm = 0;
                $foot->rh = 0;
                $foot->rit = 0;

                foreach ($base as $key => $value) {

                    $foot->mu += $value->mu;
                    $foot->mr += $value->mr;
                    $foot->mt += $value->mt;

                    $foot->hu += $value->hu;
                    $foot->hr += $value->hr;
                    $foot->ht += $value->ht;

                    $foot->um += $value->um;
                    $foot->uh += $value->uh;

                    $foot->rm += $value->rm;
                    $foot->rh += $value->rh;
                }
                $foot->it = round(100 * $foot->ht / $foot->mt, 1);
                $foot->uit = round(100 * $foot->uh / $foot->um, 1);
                $foot->rit = round(100 * $foot->rh / $foot->rm, 1);
                $excel = view('educacion.Indicadores.ConvenioFEDMC0502tabla2', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));


            default:
                return [];
        }
    }

    public function ConvenioFEDEduMC0502Reports1download($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        if ($anio > 0) {
            switch ($div) {
                // case 'tabla1':$name = 'Listado de establecimientos de salud ' . date('Y-m-d') . '.xlsx';break;
                case 'tabla2':
                    $name = 'Padrón Nominal ' . date('Y-m-d') . '.xlsx';
                    break;
                default:
                    $name = 'sin nombre.xlsx';
                    break;
            }

            return Excel::download(new eduConvenioFed2Export($div, $indicador, $anio, $mes, $provincia, $distrito), $name);
        }
    }

    public function ConvenioFEDEduMC0502Export($div, $indicador, $anio, $mes, $provincia, $distrito)
    {
        switch ($div) {
            // case 'tabla1':
            //     $base = IndicadorGeneralMetaRepositorio::getPacto1tabla1($indicador, $anio, $mes);
            //     $excel = view('salud.Indicadores.PactoRegionalSalPacto1tabla1', compact('base', 'ndis'))->render();
            //     return compact('excel', 'base');

            case 'tabla2':
                $base = CuboFEDPN::where('anio', $anio)->where('num', '1')
                    ->select(
                        'id',
                        'importacion_id',
                        'anio',
                        'mes',
                        'dni',
                        'apellido_paterno',
                        'apellido_materno',
                        'nombre',
                        'sexo',
                        'fecha_nacimiento',
                        'edad',
                        'tipo_edad',
                        'direccion',
                        'ubigeo',
                        'centro_poblado',
                        'centro_poblado_nombre',
                        'area_ccpp',
                        'codigo_ie',
                        'nombre_ie',
                        'tipo_doc_madre',
                        'num_doc_madre',
                        'apellido_paterno_madre',
                        'apellido_materno_madre',
                        'nombres_madre',
                        'celular_madre',
                        'grado_instruccion',
                        'lengua_madre',
                        'distrito_id',
                        'distrito',
                        'dependencia',
                        'provincia',
                        'ugel',
                        'eess',
                        'codmod_salud',
                        'iiee_salud',
                        'codmod_educacion',
                        'iiee_educacion',
                        'den',
                        'num',
                        'numx',
                        DB::raw('if(length(codmod_educacion)=7,1,0) as cumple')
                    );
                if ($mes > 0) $base->where('mes', $mes);
                if ($provincia > 0) $base->where('dependencia', $provincia);
                if ($distrito > 0) $base->where('distrito_id', $distrito);
                $base = $base->get();

                return compact('base');


            default:
                return [];
        }
    }

    public function ConvenioGestion()
    {
        $indicadores = [
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=f949fcff58741d51b3e2",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=7593d8e8934e0b6b6698",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=f9c4ba0e40a6b06775a9",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=b125ff6d190047140b4b",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=1adb37f8ca70044b1a7d",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=d7bfd8272316342c078e",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=080bd5233819181188c6",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=77d56fe33099c476e2c1",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=4fc8c54ca23e78a03b7a",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=79d5ec590a7785c707e1",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=98547b064d0a859585bd",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=63942bbc036658348194",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=8f8262d7eb4e662e9d23",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=2d3189ecb4779a80839c",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=36230e6a93177cb6b128",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=48658675ee6044751996",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=ce0bc94526e00ad0c828",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=a144cf0c8ca3a1a3edb7",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=75a43930808d890664a5",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=3efbe9854a435ec4d53b",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=b552aae1e06e79a9d348",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=c2ed9308e99984006203",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=36adf0ac7e65a9c0b324",
            "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=aff690b33e9647e21508"
        ];

        // $indicadores = [
        //     [
        //         "codigo" => "01",
        //         "numerador" => 100,
        //         "denominador" => 20,
        //         "titulo" => "Porcentaje de niñas/niños de 12 a 18 meses, con diagnóstico de anemia entre los 6 y 11 meses, que se han recuperado",
        //         "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=f949fcff58741d51b3e2"
        //     ],
        //     [
        //         "codigo" => "02",
        //         "numerador" => 100,
        //         "denominador" => 40,
        //         "titulo" => "Porcentaje de niñas/niños menores de 12 meses, que reciben un paquete integrado de servicios: CRED, vacunas, dosaje de hemoglobina para descarte de anemia y suplementación con hierro.",
        //         "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=7593d8e8934e0b6b6698"
        //     ],
        //     [
        //         "codigo" => "03",
        //         "numerador" => 100, "denominador" => 30,  "titulo" => "Porcentaje de niños y niñas de 6 a 35 meses con diagnóstico de anemia del Total de los casos esperados de anemia.",
        //         "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=f9c4ba0e40a6b06775a9"
        //     ],
        //     [
        //         "codigo" => "04",
        //         "numerador" => 100,
        //         "denominador" => 10,
        //         "titulo" => "Porcentaje de recién nacidos que reciben vacunas BCG, HvB, controles CRED y tamizaje neonatal metabólico",
        //         "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=b125ff6d190047140b4b"
        //     ],
        //     [
        //         "codigo" => "05",
        //         "numerador" => 100,
        //         "denominador" => 50,
        //         "titulo" => "Niñas y niños menores de 2 años en condición de crecimiento inadecuado que luego de un periodo de seguimiento mejora sus condiciones nutricionales.",
        //         "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=1adb37f8ca70044b1a7d"
        //     ]
        // ];


        $indicadores = [
            [
                "codigo" => "01",
                "numerador" =>   35,
                "denominador" =>    211,
                "titulo" => "Porcentaje de niñas/niños de 12 a 18 meses, con diagnóstico de anemia entre los 6 y 11 meses, que se han recuperado",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=f949fcff58741d51b3e2"
            ],
            [
                "codigo" => "02",
                "numerador" =>    0,
                "denominador" =>      0,
                "titulo" => "Porcentaje de niñas/niños menores de 12 meses, que reciben un paquete integrado de servicios: CRED, vacunas, dosaje de hemoglobina para descarte de anemia y suplementación con hierro.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=7593d8e8934e0b6b6698"
            ],
            [
                "codigo" => "03",
                "numerador" =>  443,
                "denominador" =>   2917,
                "titulo" => "Porcentaje de niños y niñas de 6 a 35 meses con diagnóstico de anemia del Total de los casos esperados de anemia.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=f9c4ba0e40a6b06775a9"
            ],
            [
                "codigo" => "04",
                "numerador" =>  189,
                "denominador" =>    956,
                "titulo" => "Porcentaje de recién nacidos que reciben vacunas BCG, HvB, controles CRED y tamizaje neonatal metabólico",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=b125ff6d190047140b4b"
            ],
            [
                "codigo" => "05",
                "numerador" =>   56,
                "denominador" =>   1188,
                "titulo" => "Niñas y niños menores de 2 años en condición de crecimiento inadecuado que luego de un periodo de seguimiento mejora sus condiciones nutricionales.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=1adb37f8ca70044b1a7d"
            ],
            [
                "codigo" => "06",
                "numerador" =>   87,
                "denominador" =>   3511,
                "titulo" => "Niñas y niños de 1 año vacunados con dos dosis de vacuna sarampión, parotiditis y rubeola (SPR)",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=d7bfd8272316342c078e"
            ],
            [
                "codigo" => "07",
                "numerador" =>  298,
                "denominador" =>    556,
                "titulo" => "Recién nacidos de parto institucional, vacunados con BCG y Anti hepatitis B, dentro de las 24 horas después del nacimiento.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=080bd5233819181188c6"
            ],
            [
                "codigo" => "08",
                "numerador" =>   16,
                "denominador" =>     51,
                "titulo" => "Tasa de éxito de tratamiento para TB Sensible",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=77d56fe33099c476e2c1"
            ],
            [
                "codigo" => "09",
                "numerador" =>    0,
                "denominador" =>      2,
                "titulo" => "Porcentaje de contactos menores de 5 años de edad que culminan Terapia Preventiva para TB ",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=4fc8c54ca23e78a03b7a"
            ],
            [
                "codigo" => "11",
                "numerador" =>  174,
                "denominador" =>  53778,
                "titulo" => "Mujeres de 30 a 49 años con tamizaje para la detección de lesiones premalignas e incipientes de cáncer de cuello uterino",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=79d5ec590a7785c707e1"
            ],
            [
                "codigo" => "12",
                "numerador" =>    1,
                "denominador" =>    510,
                "titulo" => "Porcentaje de niñas y niños (6 meses a 6 años) que reciben procedimientos estomatológicos preventivos",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=98547b064d0a859585bd"
            ],
            [
                "codigo" => "13",
                "numerador" =>    4,
                "denominador" =>   4960,
                "titulo" => "Porcentaje de personas que acceden a algún método anticonceptivo moderno de planificación familiar",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=63942bbc036658348194"
            ],
            [
                "codigo" => "15",
                "numerador" =>   65,
                "denominador" =>    707,
                "titulo" => "Gestantes con paquete preventivo completo.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=8f8262d7eb4e662e9d23"
            ],
            [
                "codigo" => "16",
                "numerador" =>   43,
                "denominador" =>   4111,
                "titulo" => "Porcentaje de adolescentes que reciben prestaciones priorizadas para el cuidado integral de la salud.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=2d3189ecb4779a80839c"
            ],
            [
                "codigo" => "17",
                "numerador" =>    0,
                "denominador" =>      1,
                "titulo" => "Porcentaje de niños y niñas menores de 5 años con deficiencias o factores de riesgo de discapacidad, con dos o más atenciones en la UPSS Medicina de Rehabilitación.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=36230e6a93177cb6b128"
            ],
            [
                "codigo" => "18",
                "numerador" =>    4,
                "denominador" =>     10,
                "titulo" => "Rendimiento cama en Unidades de Hospitalización de Salud Mental y Adicciones en hospitales.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=48658675ee6044751996"
            ],
            [
                "codigo" => "19",
                "numerador" =>   47,
                "denominador" =>    278,
                "titulo" => "Porcentaje de personas con diagnóstico de Depresión que recibieron paquete mínimo de intervenciones terapéuticas en centro de salud mental comunitaria.",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=ce0bc94526e00ad0c828"
            ],
            [
                "codigo" => "20",
                "numerador" =>    0,
                "denominador" =>   2432,
                "titulo" => "Porcentaje de Resolutividad",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=a144cf0c8ca3a1a3edb7"
            ],
            [
                "codigo" => "23",
                "numerador" =>  101,
                "denominador" =>    214,
                "titulo" => "Porcentaje de Ocupación Cama",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=75a43930808d890664a5"
            ],
            [
                "codigo" => "24",
                "numerador" =>    0,
                "denominador" =>    657,
                "titulo" => "Intervalo de Sustitución de Cama",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=3efbe9854a435ec4d53b"
            ],
            [
                "codigo" => "25",
                "numerador" => 3045,
                "denominador" =>     99,
                "titulo" => "Promedio de Espera para la Atención en Consulta Externa de un Paciente Referido ",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=b552aae1e06e79a9d348"
            ],
            [
                "codigo" => "26",
                "numerador" => 1754,
                "denominador" =>     10,
                "titulo" => "Promedio de Espera para la Atención en Apoyo al Diagnóstico de un Paciente Referido",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=c2ed9308e99984006203"
            ],
            [
                "codigo" => "27",
                "numerador" => 8975,
                "denominador" =>      1,
                "titulo" => " Productividad Hora Médico ",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=36adf0ac7e65a9c0b324"
            ],
            [
                "codigo" => "32",
                "numerador" =>  255,
                "denominador" => 507895,
                "titulo" => "Utilización de los servicios de telemedicina (teleinterconsultas - teleconsultas - telemonitoreo)",
                "enlace" => "https://app.powerbi.com/view?r=eyJrIjoiZTVjMWI5ZjEtNmY3NC00MGM5LWI2NDgtMDUxYjYyNjFlOGM2IiwidCI6IjU3OTIyZTZhLTIzY2EtNDFhYy04ZGYxLTI5YTZmODgxYzBiYiIsImMiOjl9&pageName=aff690b33e9647e21508"
            ]
        ];
        return view('salud.Indicadores.ConvenioGestion', compact('indicadores'));
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
