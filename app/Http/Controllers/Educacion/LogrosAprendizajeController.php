<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\AvanceMatricula1Export;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImporEvaluacionMuestralRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LogrosAprendizajeController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function EvaluacionMuestral()
    {
        $anios = ImporEvaluacionMuestralRepositorio::anios();
        $aniomax = 0;
        foreach ($anios as $key => $value) {
            $aniomax = $value->anio;
        }
        $imp = ImportacionRepositorio::ImportacionMax_porfuente_mesletra(ImporEvaluacionMuestralController::$FUENTE);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $imp->mes . ' del ' . $imp->anio;

        return view("educacion.evaluacionmuestral.principal", compact('actualizado', 'aniomax', 'anios'));
    }

    public function EvaluacionMuestralReportes(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $data = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesHead($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                $card1 = number_format($data->ponderado, 1);
                $card2 = number_format($data->satisfactorio, 1);
                $card3 = number_format($data->evaluados, 0);
                $card4 = number_format($data->locales, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4', 'data'));

            case 'anal1':
                $base = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesanal1($rq->div, $rq->nivel, $rq->grado, $rq->curso);
                $categoria = [];
                $data = [];
                $x1 = [];
                $x2 = [];
                $x3 = [];
                $x4 = [];
                foreach ($base as $key => $value) {
                    $categoria[] = $value->anio;
                    $x1[] = $value->a1;
                    $x2[] = $value->i1;
                    $x3[] = $value->p1;
                    $x4[] = $value->s1;
                }
                $data[] = ['name' => 'Satisfactorio', 'data' => $x4];
                $data[] = ['name' => 'En proceso', 'data' => $x3];
                $data[] = ['name' => 'En inicio', 'data' => $x2];
                $data[] = ['name' => 'Previo al inicio', 'data' => $x1];

                return response()->json(compact('base', 'categoria', 'data'));
            case 'anal2':
                $base = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesanal2($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                $categoria = ['PUBLICO', 'PRIVADO'];
                $data = [];
                $data[] = ['name' => 'Previo al inicio', 'data' => [$base->a1, $base->a2]];
                $data[] = ['name' => 'En inicio', 'data' => [$base->i1, $base->i2]];
                $data[] = ['name' => 'En proceso', 'data' => [$base->p1, $base->p2]];
                $data[] = ['name' => 'Satisfactorio', 'data' => [$base->s1, $base->s2]];
                // {
                //     name: 'Previo al inicio',
                //     data: [5, 10]
                // }
                return response()->json(compact('base', 'categoria', 'data'));
            case 'anal3':
                $base = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesanal3($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                $categoria = ['RURAL', 'URBANO'];
                $data = [];
                $data[] = ['name' => 'Previo al inicio', 'data' => [$base->a1, $base->a2]];
                $data[] = ['name' => 'En inicio', 'data' => [$base->i1, $base->i2]];
                $data[] = ['name' => 'En proceso', 'data' => [$base->p1, $base->p2]];
                $data[] = ['name' => 'Satisfactorio', 'data' => [$base->s1, $base->s2]];
                return response()->json(compact('base', 'categoria', 'data'));
            case 'anal4':
                $base = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesanal4($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                $categoria = ['HOMBRE', 'MUJER'];
                $data = [];
                $data[] = ['name' => 'Previo al inicio', 'data' => [$base->a1, $base->a2]];
                $data[] = ['name' => 'En inicio', 'data' => [$base->i1, $base->i2]];
                $data[] = ['name' => 'En proceso', 'data' => [$base->p1, $base->p2]];
                $data[] = ['name' => 'Satisfactorio', 'data' => [$base->s1, $base->s2]];
                return response()->json(compact('base', 'categoria', 'data'));
            case 'tabla1':
                $base = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesTabla1($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                // return response()->json(compact('base'));
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ponderado = 0;
                    $foot->iiee = 0;
                    $foot->iiee_publico = 0;
                    $foot->iiee_privado = 0;
                    $foot->alumnos = 0;
                    $foot->alumnos_hombres = 0;
                    $foot->alumnos_mujeres = 0;
                    $foot->s = 0;
                    $foot->p = 0;
                    $foot->i = 0;
                    $foot->a = 0;
                    foreach ($base as $key => $value) {
                        $foot->ponderado += $value->ponderado;
                        $foot->iiee += $value->iiee;
                        $foot->iiee_publico += $value->iiee_publico;
                        $foot->iiee_privado += $value->iiee_privado;
                        $foot->alumnos += $value->alumnos;
                        $foot->alumnos_hombres += $value->alumnos_hombres;
                        $foot->alumnos_mujeres += $value->alumnos_mujeres;
                        $foot->s += $value->s;
                        $foot->p += $value->p;
                        $foot->i += $value->i;
                        $foot->a += $value->a;
                    }
                }
                $excel = view('educacion.EvaluacionMuestral.principalTable1', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'foot'));

            case 'tabla1_1':
                $base = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesTabla1_1($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso, $rq->provincia);
                // return response()->json(compact('base'));
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ponderado = 0;
                    $foot->iiee = 0;
                    $foot->iiee_publico = 0;
                    $foot->iiee_privado = 0;
                    $foot->alumnos = 0;
                    $foot->alumnos_hombres = 0;
                    $foot->alumnos_mujeres = 0;
                    $foot->s = 0;
                    $foot->p = 0;
                    $foot->i = 0;
                    $foot->a = 0;
                    foreach ($base as $key => $value) {
                        $foot->ponderado += $value->ponderado;
                        $foot->iiee += $value->iiee;
                        $foot->iiee_publico += $value->iiee_publico;
                        $foot->iiee_privado += $value->iiee_privado;
                        $foot->alumnos += $value->alumnos;
                        $foot->alumnos_hombres += $value->alumnos_hombres;
                        $foot->alumnos_mujeres += $value->alumnos_mujeres;
                        $foot->s += $value->s;
                        $foot->p += $value->p;
                        $foot->i += $value->i;
                        $foot->a += $value->a;
                    }
                }
                $excel = view('educacion.EvaluacionMuestral.principalTable1_1', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));
            default:
                # code...
                return response()->json([]);
        }
    }

    public function InstitucionesEducativas()
    {
        $anios = ImporEvaluacionMuestralRepositorio::anios();
        $aniomax = 0;
        foreach ($anios as $key => $value) {
            $aniomax = $value->anio;
        }
        $imp = ImportacionRepositorio::ImportacionMax_porfuente_mesletra(ImporEvaluacionMuestralController::$FUENTE);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $imp->mes . ' del ' . $imp->anio;
        return view("educacion.EvaluacionMuestral.institucioneseducativas", compact('actualizado', 'aniomax', 'anios'));
    }

    public function InstitucionesEducativasReportes(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $data = ImporEvaluacionMuestralRepositorio::EvaluacionMuestralReportesHead($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                $card1 = number_format($data->ponderado, 1);
                $card2 = number_format($data->satisfactorio, 1);
                $card3 = number_format($data->evaluados, 0);
                $card4 = number_format($data->locales, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4', 'data'));
            case 'tabla1':
                $base = ImporEvaluacionMuestralRepositorio::InstitucionesEducativasTabla1($rq->div, $rq->anio, $rq->nivel, $rq->grado, $rq->curso);
                // return response()->json(compact('base'));
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->alumnos = 0;
                    $foot->alumnos_hombres = 0;
                    $foot->alumnos_mujeres = 0;
                    $foot->s = 0;
                    $foot->p = 0;
                    $foot->i = 0;
                    $foot->a = 0;
                    foreach ($base as $key => $value) {
                        $foot->alumnos += $value->alumnos;
                        $foot->alumnos_hombres += $value->alumnos_hombres;
                        $foot->alumnos_mujeres += $value->alumnos_mujeres;
                        $foot->s += $value->s;
                        $foot->p += $value->p;
                        $foot->i += $value->i;
                        $foot->a += $value->a;
                    }
                }
                $excel = view('educacion.EvaluacionMuestral.institucioneseducativasTable1', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            default:
                # code...
                return response()->json([]);
        }
    }

    public function cargarnivel($anio)
    {
        $nivel = ImporEvaluacionMuestralRepositorio::nivel($anio);
        return $nivel;
    }

    public function cargargrado($anio, $nivel)
    {
        $nivel = ImporEvaluacionMuestralRepositorio::grado($anio, $nivel);
        return $nivel;
    }

    public function cargarcurso($anio, $nivel, $grado)
    {
        $query = ImporEvaluacionMuestralRepositorio::curso($anio, $nivel, $grado);
        return $query;
    }
}