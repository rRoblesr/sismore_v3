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
        $ugels = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        $gestions = [["id" => 12, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        $areas = Area::select('id', 'nombre')->get();
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '8')->orderBy('fecha', 'desc')->first();
        $importacion_id = $imp->id;
        $fecha = date('d/m/Y', strtotime($imp->fecha));
        $actualizado = '';
        return view("educacion.evaluacionmuestral.principal", compact('actualizado', 'aniomax', 'anios',  'gestions', 'areas', 'ugels', 'importacion_id', 'fecha'));
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
                $data[] = ['name' => 'Previo al inicio', 'data' => $x1];
                $data[] = ['name' => 'En inicio', 'data' => $x2];
                $data[] = ['name' => 'En proceso', 'data' => $x3];
                $data[] = ['name' => 'Satisfactorio', 'data' => $x4];
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
