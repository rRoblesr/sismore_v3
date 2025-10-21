<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\Educacion\SFLReportesExport;
use App\Http\Controllers\Controller;
use App\Repositories\Educacion\CuboPacto2Repositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\NexusRepositorio;
use App\Repositories\Educacion\SFLRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class NexusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function filtro_ugels($anio)
    {
        $data = NexusRepositorio::filtro_ugel_deanio($anio);
        return response()->json($data);
    }

    public function filtro_modalidad($anio, $ugel)
    {
        $data = NexusRepositorio::filtro_modalidad_deaniougel($anio, $ugel);
        return response()->json($data);
    }

    public function filtro_nivel($anio, $ugel, $modalidad)
    {
        $data = NexusRepositorio::filtro_nivel_deaniougelmodalidad($anio, $ugel, $modalidad);
        return response()->json($data);
    }

    public function reportes()
    {
        $anios = ImportacionRepositorio::anios_porfuente(ImporNexusController::$FUENTE);
        $aniomax = $anios->max('anio');
        $mensaje = "";
        return view('educacion.Nexus.Reportes', compact('anios', 'aniomax', 'mensaje'));
    }

    public function reportesreporte(Request $rq)
    {
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE);
        switch ($rq->div) {
            case 'head':
                $data2025 = NexusRepositorio::reportesreporte_head($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $data2024 = NexusRepositorio::reportesreporte_head($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $card1 = number_format(SFLRepositorio::reportesreporte_head($rq->ugel, $rq->modalidad, $rq->nivel), 0);
                $card2 = number_format(CuboPacto2Repositorio::reportesreporte_head($rq->ugel, $rq->modalidad, $rq->nivel, 0), 0);
                $card3 = number_format(CuboPacto2Repositorio::reportesreporte_head($rq->ugel, $rq->modalidad, $rq->nivel, 1), 0);
                $card4 = number_format(CuboPacto2Repositorio::reportesreporte_head($rq->ugel, $rq->modalidad, $rq->nivel, 2), 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4', 'data2025', 'data2024'));
            case 'anal1':
                $data = CuboPacto2Repositorio::reportesreporte_anal1($rq->ugel, $rq->modalidad, $rq->nivel);
                $info['series'][0]['name'] = 'SANEADO';
                $info['series'][1]['name'] = 'NO SANEADO';
                $info['series'][0]['color'] = '#5eb9aa';
                $info['series'][1]['color'] = '#e65310';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->provincia;
                    $info['series'][0]['data'][] = (int)$value->saneado;
                    $info['series'][1]['data'][] = (int)$value->nosaneado;
                }
                return response()->json(compact('info'));

            case 'anal2':
                $data = CuboPacto2Repositorio::reportesreporte_anal2($rq->ugel, $rq->modalidad, $rq->nivel);
                $info = $data->map(function ($y, $name) {
                    return ['name' => $name, 'y' => (int)$y];
                })->values();
                return response()->json(compact('info'));
                break;
            case 'anal3':
                $pe_pv = [
                    '2501' => 'pe-uc-cp',
                    '2502' => 'pe-uc-at',
                    '2503' => 'pe-uc-pa',
                    '2504' => 'pe-uc-pr',
                ];
                $data = CuboPacto2Repositorio::reportesreporte_anal3($rq->ugel, $rq->modalidad, $rq->nivel);
                $info = [];
                $valores = [];
                foreach ($data as $key => $value) {
                    $info[] = [$pe_pv[$value->codigo], (float)$value->indicador];
                    $valores[$pe_pv[$value->codigo]] = ['num' => (float)$value->saneado, 'dem' => (float)$value->nosaneado, 'ind' => (float)$value->indicador];
                }
                return response()->json(compact('info', 'valores'));

            case 'anal4':
                $data = CuboPacto2Repositorio::reportesreporte_anal4($rq->ugel, $rq->modalidad, $rq->nivel);
                $info['series'][0]['name'] = 'SANEADO';
                $info['series'][1]['name'] = 'NO SANEADO';
                $info['series'][0]['color'] = '#5eb9aa';
                $info['series'][1]['color'] = '#e65310';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->area;
                    $info['series'][0]['data'][] = (int)$value->saneado;
                    $info['series'][1]['data'][] = (int)$value->nosaneado;
                }
                return response()->json(compact('info', 'data'));

            case 'tabla1':
                $base = CuboPacto2Repositorio::reportesreporte_tabla1($rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->se = $base->sum('se');
                    $foot->ser = $base->sum('ser');
                    $foot->seu = $base->sum('seu');
                    $foot->le = $base->sum('le');
                    $foot->ler = $base->sum('ler');
                    $foot->leu = $base->sum('leu');
                    $foot->le1 = $base->sum('le1');
                    $foot->le2 = $base->sum('le2');
                    $foot->le3 = $base->sum('le3');
                    $foot->le4 = $base->sum('le4');
                    $foot->le1p = round(100 * $foot->le1 / $foot->le, 1);
                    $foot->le2p = round(100 * $foot->le2 / $foot->le, 1);
                    $foot->le3p = round(100 * $foot->le3 / $foot->le, 1);
                    $foot->le4p = round(100 * $foot->le4 / $foot->le, 1);
                }
                $excel = view('educacion.SFL.ReportesTabla1', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));

            case 'tabla2':
                $base = CuboPacto2Repositorio::reportesreporte_tabla2($rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->se = $base->sum('se');
                    $foot->ser = $base->sum('ser');
                    $foot->seu = $base->sum('seu');
                    $foot->le = $base->sum('le');
                    $foot->ler = $base->sum('ler');
                    $foot->leu = $base->sum('leu');
                    $foot->le1 = $base->sum('le1');
                    $foot->le2 = $base->sum('le2');
                    $foot->le3 = $base->sum('le3');
                    $foot->le4 = $base->sum('le4');
                    $foot->le1p = round(100 * $foot->le1 / $foot->le, 1);
                    $foot->le2p = round(100 * $foot->le2 / $foot->le, 1);
                    $foot->le3p = round(100 * $foot->le3 / $foot->le, 1);
                    $foot->le4p = round(100 * $foot->le4 / $foot->le, 1);
                }
                $excel = view('educacion.SFL.ReportesTabla2', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            case 'tabla3':
                $base = CuboPacto2Repositorio::reportesreporte_tabla3($rq->ugel, $rq->modalidad, $rq->nivel);
                $excel = view('educacion.SFL.ReportesTabla3', compact('base'))->render();
                return response()->json(compact('excel'));
            default:
                # code...
                return [];
        }
    }

    public function reportesrdownloadexcel($div, $ugel, $modalidad, $nivel)
    {
        switch ($div) {
            case 'tabla1':
                $name = 'LOCALES_ESCOLARES_PÚBLICOS_POR_UGEL_SEGÚN_ESTADOS_DEL_SFL.xlsx';
                break;
            case 'tabla2':
                $name = 'INSTITUCIONES_EDUCATIVAS_Y_LOCALES_EDUCATIVOS_PÚBLICOS_POR_DISTRITOS_SEGÚN_ESTADOS_DEL_SFL.xlsx';
                break;
            case 'tabla3':
                $name = 'INSTITUCIONES_EDUCATIVAS_PÚBLICAS_SEGÚN_ESTADOS_DEL_SFL.xlsx';
                break;
            default:
                $name = 'REPORTE_SFL.xlsx';
                break;
        }
        return Excel::download(new SFLReportesExport($div, $ugel, $modalidad, $nivel), $name);
    }
}
