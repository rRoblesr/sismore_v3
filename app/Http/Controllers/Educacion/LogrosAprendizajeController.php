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
                return response()->json([]);

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
