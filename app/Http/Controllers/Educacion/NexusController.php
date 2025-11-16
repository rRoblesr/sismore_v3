<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\Educacion\NexusReportesExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\NexusRegimenLaboral;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\NexusRepositorio;
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
        $anios = ImportacionRepositorio::anios_porfuente_select(ImporNexusController::$FUENTE);
        $aniomax = $anios->max('anio');
        $mensaje = "";
        // return json_encode(compact('anios', 'aniomax', 'mensaje'));
        return view('educacion.Nexus.Reportes', compact('anios', 'aniomax', 'mensaje'));
    }

    public function reportesreporte(Request $rq)
    {
        $div = $rq->div;
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE);
        switch ($rq->div) {
            case 'head':
                $data2025 = NexusRepositorio::reportesreporte_head($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $data2024 = NexusRepositorio::reportesreporte_head($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $card1 = number_format($data2025->docentes, 0);
                $card2 = number_format($data2025->auxiliar, 0);
                $card3 = number_format($data2025->promotor, 0);
                $card4 = number_format($data2025->administrativo, 0);
                $pcard1 = round($data2024->docentes > 0 ? (100 * $data2025->docentes / $data2024->docentes) : 0, 1);
                $pcard2 = round($data2024->auxiliar > 0 ? (100 * $data2025->auxiliar / $data2024->auxiliar) : 0, 1);
                $pcard3 = round($data2024->promotor > 0 ? (100 * $data2025->promotor / $data2024->promotor) : 0, 1);
                $pcard4 = round($data2024->administrativo > 0 ? (100 * $data2025->administrativo / $data2024->administrativo) : 0, 1);
                return response()->json(compact('card1', 'card2', 'card3', 'card4', 'pcard1', 'pcard2', 'pcard3', 'pcard4'));
            case 'anal1':
                $pe_pv = [
                    '2501' => 'pe-uc-cp',
                    '2502' => 'pe-uc-at',
                    '2503' => 'pe-uc-pa',
                    '2504' => 'pe-uc-pr',
                ];
                $info = [];
                $valores = [];
                $data = NexusRepositorio::reportesreporte_anal1($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $total = $data->sum('conteo');
                foreach ($data as $key => $value) {
                    $info[] = [$value->codigo, round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                    $valores[$value->codigo] = ['num' => (int)$value->conteo, 'dem' => $total, 'ind' => round($total > 0 ? 100 * $value->conteo / $total : 0, 1)];
                }
                return response()->json(compact('info', 'valores', 'data'));


            case 'anal2':
                $data = NexusRepositorio::reportesreporte_anal2($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $info = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->mes;
                    $info['data'][] = $value->conteo;
                }
                // $data = CuboPacto2Repositorio::reportesreporte_anal2($rq->ugel, $rq->modalidad, $rq->nivel);
                // $info = $data->map(function ($y, $name) {
                //     return ['name' => $name, 'y' => (int)$y];
                // })->values();
                return response()->json(compact('info', 'data'));
                break;
            case 'anal3':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data = NexusRepositorio::reportesreporte_anal3($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                foreach ($data as $key => $value) {
                    $value->color = $color[$key % count($color)];
                }
                return response()->json($data);

            case 'anal4':
                $color = ['#43beac', '#ffc107', '#ef5350'];
                $data = NexusRepositorio::reportesreporte_anal4($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                foreach ($data as $key => $value) {
                    $value->color = $color[$key % count($color)];
                }
                return response()->json($data);

            case 'tabla1':
                $base = NexusRepositorio::reportesreporte_tabla01($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ugel = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                    $foot->tpc = $base->sum('tpc');
                }
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla2':
                $base = NexusRepositorio::reportesreporte_tabla02($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ley = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                    $foot->tpc = $base->sum('tpc');
                }
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));
                // return response()->json(compact('div', 'base', 'foot'));

            case 'tabla3':
                $base = NexusRepositorio::reportesreporte_tabla03($rq->anio, $rq->ugel, $rq->modalidad, $rq->nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->distrito = 'TOTAL';
                    $foot->td = $base->sum('td');
                    $foot->tdn = $base->sum('tdn');
                    $foot->tdc = $base->sum('tdc');
                    $foot->tde = $base->sum('tde');
                    $foot->tdd = $base->sum('tdd');
                    $foot->tdv = $base->sum('tdv');
                    $foot->ta = $base->sum('ta');
                    $foot->tan = $base->sum('tan');
                    $foot->tac = $base->sum('tac');
                    $foot->tav = $base->sum('tav');
                    $foot->tpc = $base->sum('tpc');
                }
                $excel = view('educacion.Nexus.ReportesTablas', compact('div', 'base', 'foot'))->render();
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

    public function reportesrdownloadexcel($div, $anio, $ugel, $modalidad, $nivel)
    {
        switch ($div) {
            case 'tabla1':
                $name = 'NÚMERO DE PLAZAS DOCENTE Y AUXILIARES DE EDUCACIÓN POR UGEL, SEGÚN SITUACIÓN LABORAL.xlsx';
                break;
            case 'tabla2':
                $name = 'NÚMERO DE PLAZAS DOCENTE Y AUXILIARES DE EDUCACIÓN POR LEY DE CONTRATO, SEGÚN SITUACIÓN LABORAL.xlsx';
                break;
            case 'tabla3':
                $name = 'NÚMERO DE PLAZAS DOCENTE Y AUXILIARES DE EDUCACIÓN POR DISTRITO, SEGÚN SITUACIÓN LABORAL.xlsx';
                break;
            case 'tabla4':
                $name = 'NÚMERO DE PLAZAS POR INSTITUCIÓN EDUCATIVAS.xlsx';
                break;
            default:
                $name = 'EXCEL_VACIO.xlsx';
                break;
        }
        return Excel::download(new NexusReportesExport($div, $anio, $ugel, $modalidad, $nivel), $name);
    }

    public function consultas()
    {
        $subtipo = NexusRegimenLaboral::where('dependencia', '1')->orderBy('nombre')->pluck('nombre', 'id');
        return view('educacion.Nexus.Consultas', compact('subtipo'));
    }

    public function consultasreportexx(Request $rq)
    {
        switch ($rq->div) {
            case 'consulta':
                if ($rq->dni == '') {
                    return response()->json(['data' => null, 'status' => 'NO', 'dni' => $rq->dni]);
                } elseif ($rq->nombre_completo == '') {
                    return response()->json(['data' => null, 'status' => 'NO', 'nombre_completo' => $rq->nombre_completo]);
                }
                $data = NexusRepositorio::consultasreporte_consulta($rq->tipo, $rq->dni, $rq->nombre_completo);
                return response()->json(['data' => $data, 'status' => $data ? 'OK' : 'NO']);
            default:
                return [];
        }
    }

    public function consultasreporte(Request $rq)
    {
        $div = $rq->div;
        switch ($rq->div) {
            case 'consulta':
                $documento = trim($rq->dni ?? '');
                $nombres   = trim($rq->nombre_completo ?? '');

                // Validación: al menos uno debe tener valor
                if ($documento === '' && $nombres === '') {
                    return response()->json([
                        'data' => null,
                        'status' => 'NO',
                        'dni' => $documento,
                        'nombre_completo' => $nombres,
                        'message' => 'Debe ingresar al menos el número de documento o el nombre completo.'
                    ]);
                }

                // Llamar al repositorio con ambos parámetros (el repositorio decidirá cómo usarlos)
                $data = NexusRepositorio::consultasreporte_consulta(
                    $rq->tipo,
                    $documento !== '' ? $documento : null,
                    $nombres !== '' ? $nombres : null
                );
                $excel = null;
                if ($data) {
                    $data->fn = date('d/m/Y', strtotime($data->fecha_nacimiento));
                    $data->fr = date('d/m/Y', strtotime($data->fecha_nombramiento));

                    $base = NexusRepositorio::consultasreporte_tabla01($data->dni);
                    if ($base) {
                        $div = 'tabla1';
                        $excel = view('educacion.Nexus.ConsultasTablas', compact('div', 'base'))->render();
                    }
                }

                return response()->json([
                    'data' => $data,
                    'excel' => $excel,
                    'status' => $data ? 'OK' : 'NO'
                ]);
            case 'tabla2':
                $base = NexusRepositorio::consultasreporte_tabla0102($rq->iiee);
                // $excel = view('educacion.Nexus.ConsultasTablas', compact('div', 'base'))->render();
                return response()->json(compact('base'));

            default:
                return response()->json(['data' => null, 'status' => 'NO', 'message' => 'Acción no válida.']);
        }
    }
}
