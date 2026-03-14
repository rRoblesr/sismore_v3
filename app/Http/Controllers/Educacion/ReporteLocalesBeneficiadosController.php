<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\ImporLocalesBeneficiados;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Ubigeo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteLocalesBeneficiadosController extends Controller
{
    public function principal()
    {
        // Obtener años disponibles de la fuente 51 (Locales Beneficiados)
        $anios = Importacion::where('fuenteImportacion_id', 51)
            ->where('estado', 'PR')
            ->select(DB::raw('YEAR(fechaActualizacion) as anio'))
            ->distinct()
            ->orderBy('anio', 'desc')
            ->get();

        $aniomax = $anios->max('anio');

        // Obtener la última importación para mostrar la fecha de actualización
        $imp = Importacion::where('fuenteImportacion_id', 51)
            ->where('estado', 'PR')
            ->orderBy('fechaActualizacion', 'desc')
            ->first();

        $actualizado = '';
        if ($imp) {
            $fecha = Carbon::parse($imp->fechaActualizacion);
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            $actualizado = 'Actualizado al ' . $fecha->day . ' de ' . $meses[$fecha->month - 1] . ' del ' . $fecha->year;
        }

        return view('educacion.ReporteLocalesBeneficiados.Principal', compact('anios', 'aniomax', 'actualizado'));
    }

    public function reporte(Request $rq)
    {
        // Caso especial: cargar instituciones no requiere año ni importación
        if ($rq->div == 'cargar_instituciones') {
            $cod_local = $rq->input('cod_local');

            try {
                $instituciones = InstitucionEducativa::join('edu_nivelModalidad as nm', 'nm.id', '=', 'edu_institucionEducativa.NivelModalidad_id')
                    ->where('edu_institucionEducativa.codLocal', $cod_local)
                    ->select('edu_institucionEducativa.nombreInstEduc', 'nm.nombre as nivel')
                    ->get();

                if ($instituciones->isEmpty()) {
                    $html = '<div class="alert alert-warning">No se encontraron instituciones para este local (' . $cod_local . ').</div>';
                    return response()->json(['html' => $html]);
                }

                $html = '<table class="table table-sm table-bordered table-striped">';
                $html .= '<thead class="bg-success-0 text-white"><tr><th>Institución Educativa</th><th>Nivel</th></tr></thead>';
                $html .= '<tbody>';
                foreach ($instituciones as $inst) {
                    $html .= '<tr><td>' . $inst->nombreInstEduc . '</td><td>' . $inst->nivel . '</td></tr>';
                }
                $html .= '</tbody></table>';

                return response()->json(['html' => $html]);
            } catch (\Exception $e) {
                return response()->json(['html' => '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>']);
            }
        }

        $anio = $rq->get('anio');
        $ugel_id = $rq->get('ugel_id');
        $provincia_id = $rq->get('provincia_id');
        $distrito_id = $rq->get('distrito_id');

        // Buscar la importación correspondiente al año seleccionado (última importación del año)
        $importacion = Importacion::where('fuenteImportacion_id', 51)
            ->where('estado', 'PR')
            ->whereYear('fechaActualizacion', $anio)
            ->orderBy('fechaActualizacion', 'desc')
            ->first();

        if (!$importacion) {
            return response()->json(['error' => 'No hay datos para el año seleccionado']);
        }

        $impId = $importacion->id;

        // Base query con filtros
        $queryBase = ImporLocalesBeneficiados::where('importacion_id', $impId);

        if ($ugel_id && $ugel_id != '0') {
            $queryBase->where('ugel_id', $ugel_id);
        }

        if (($provincia_id && $provincia_id != '0') || ($distrito_id && $distrito_id != '0')) {
            $queryBase->join('par_ubigeo as dist', 'edu_impor_locales_beneficiados.ubigeo_id', '=', 'dist.id');

            if ($provincia_id && $provincia_id != '0') {
                // Asumiendo que el distrito tiene dependencia a la provincia
                $queryBase->where('dist.dependencia', $provincia_id);
            }

            if ($distrito_id && $distrito_id != '0') {
                $queryBase->where('edu_impor_locales_beneficiados.ubigeo_id', $distrito_id);
            }
        }

        switch ($rq->div) {
            case 'cargar_ugeles':
                $ugeles = ImporLocalesBeneficiados::where('importacion_id', $impId)
                    ->join('edu_ugel', 'edu_impor_locales_beneficiados.ugel_id', '=', 'edu_ugel.id')
                    ->select('edu_ugel.id', 'edu_ugel.nombre')
                    ->distinct()
                    ->orderBy('edu_ugel.nombre')
                    ->get();
                return response()->json($ugeles);

            case 'cargar_provincias':
                // Re-instanciar query para filtros de dependencia (UGEL)
                $qProv = ImporLocalesBeneficiados::where('importacion_id', $impId);
                if ($ugel_id && $ugel_id != '0') {
                    $qProv->where('ugel_id', $ugel_id);
                }

                $provincias = $qProv
                    ->join('par_ubigeo as dist', 'edu_impor_locales_beneficiados.ubigeo_id', '=', 'dist.id')
                    ->join('par_ubigeo as prov', 'dist.dependencia', '=', 'prov.id')
                    ->select('prov.id', 'prov.nombre')
                    ->distinct()
                    ->orderBy('prov.nombre')
                    ->get();
                return response()->json($provincias);

            case 'cargar_distritos':
                // Re-instanciar query para filtros de dependencia (UGEL, Provincia)
                $qDist = ImporLocalesBeneficiados::where('importacion_id', $impId);
                if ($ugel_id && $ugel_id != '0') {
                    $qDist->where('ugel_id', $ugel_id);
                }

                $qDist->join('par_ubigeo as dist', 'edu_impor_locales_beneficiados.ubigeo_id', '=', 'dist.id');

                if ($provincia_id && $provincia_id != '0') {
                    $qDist->where('dist.dependencia', $provincia_id);
                }

                $distritos = $qDist
                    ->select('dist.id', 'dist.nombre')
                    ->distinct()
                    ->orderBy('dist.nombre')
                    ->get();
                return response()->json($distritos);

            case 'head':
                // Clonar query base para conteos
                $q1 = clone $queryBase;
                $q2 = clone $queryBase;
                $q3 = clone $queryBase;

                // Datos para las tarjetas (Cards)
                $totalLocales = $q1->count();
                $totalMonto = $q2->sum(DB::raw('monto_asignado_mantenimiento_regular + monto_asignado_rutas'));
                $totalServicios = $q3->sum('numero_servicios');

                $card1 = number_format($totalLocales);
                $card2 = 'S/ ' . number_format($totalMonto, 2);
                $card3 = number_format($totalServicios);

                return response()->json(compact('card1', 'card2', 'card3'));

            case 'grafica_anios':
                // Datos para la gráfica histórica de montos por año
                // Obtenemos una importación representativa por año (la última de cada año)
                $historico = Importacion::where('fuenteImportacion_id', 51)
                    ->where('estado', 'PR')
                    ->select(DB::raw('YEAR(fechaActualizacion) as anio'), DB::raw('MAX(id) as id'))
                    ->groupBy(DB::raw('YEAR(fechaActualizacion)'))
                    ->orderBy('anio', 'asc')
                    ->get();

                $categories = [];
                $data = [];

                foreach ($historico as $h) {
                    $categories[] = $h->anio;

                    $qGraf = ImporLocalesBeneficiados::where('importacion_id', $h->id);

                    if ($ugel_id && $ugel_id != '0') {
                        $qGraf->where('ugel_id', $ugel_id);
                    }

                    if (($provincia_id && $provincia_id != '0') || ($distrito_id && $distrito_id != '0')) {
                        $qGraf->join('par_ubigeo as dist', 'edu_impor_locales_beneficiados.ubigeo_id', '=', 'dist.id');

                        if ($provincia_id && $provincia_id != '0') {
                            $qGraf->where('dist.dependencia', $provincia_id);
                        }

                        if ($distrito_id && $distrito_id != '0') {
                            $qGraf->where('edu_impor_locales_beneficiados.ubigeo_id', $distrito_id);
                        }
                    }

                    $monto = $qGraf->sum(DB::raw('monto_asignado_mantenimiento_regular + monto_asignado_rutas'));
                    $data[] = (float) $monto;
                }

                return response()->json(compact('categories', 'data'));

            case 'tabla':
                $qTabla = ImporLocalesBeneficiados::where('importacion_id', $impId)
                    ->leftJoin('edu_ugel', 'edu_impor_locales_beneficiados.ugel_id', '=', 'edu_ugel.id')
                    ->leftJoin('par_ubigeo as dist', 'edu_impor_locales_beneficiados.ubigeo_id', '=', 'dist.id')
                    ->leftJoin('par_ubigeo as prov', 'dist.dependencia', '=', 'prov.id')
                    ->select(
                        'edu_impor_locales_beneficiados.cod_local',
                        DB::raw('COALESCE(edu_ugel.nombre, "SIN UGEL") as ugel'),
                        DB::raw('COALESCE(prov.nombre, "SIN PROVINCIA") as provincia'),
                        DB::raw('COALESCE(dist.nombre, "SIN DISTRITO") as distrito'),
                        'edu_impor_locales_beneficiados.numero_servicios',
                        'edu_impor_locales_beneficiados.nombre_servicios',
                        DB::raw('(COALESCE(edu_impor_locales_beneficiados.monto_asignado_mantenimiento_regular, 0) + COALESCE(edu_impor_locales_beneficiados.monto_asignado_rutas, 0)) as monto_total')
                    );

                if ($ugel_id && $ugel_id != '0') {
                    $qTabla->where('edu_impor_locales_beneficiados.ugel_id', $ugel_id);
                }

                if ($provincia_id && $provincia_id != '0') {
                    $qTabla->where('dist.dependencia', $provincia_id);
                }

                if ($distrito_id && $distrito_id != '0') {
                    $qTabla->where('edu_impor_locales_beneficiados.ubigeo_id', $distrito_id);
                }

                return datatables()
                    ->of($qTabla)
                    ->editColumn('monto_total', function ($r) {
                        return 'S/ ' . number_format($r->monto_total, 2);
                    })
                    ->addColumn('servicios_btn', function ($r) {
                        // $servicios = $r->nombre_servicios ? e($r->nombre_servicios) : 'Sin servicios registrados';
                        // return '<button type="button" class="btn btn-primary btn-xs btn-servicios" data-servicios="' . $servicios . '"><i class="fa fa-eye"></i> Ver Servicios</button>';
                        return '<button type="button" class="btn btn-primary btn-xs btn-servicios" data-codlocal="' . $r->cod_local . '"><i class="fa fa-eye"></i> Ver Instituciones</button>';
                    })
                    ->rawColumns(['servicios_btn'])
                    ->make(true);

            case 'cargar_instituciones':
                $cod_local = $rq->input('cod_local');

                try {
                    $instituciones = InstitucionEducativa::join('edu_nivelModalidad as nm', 'nm.id', '=', 'edu_institucionEducativa.NivelModalidad_id')
                        ->where('edu_institucionEducativa.codLocal', $cod_local)
                        ->select('edu_institucionEducativa.nombreInstEduc', 'nm.nombre as nivel')
                        ->get();

                    if ($instituciones->isEmpty()) {
                        $html = '<div class="alert alert-warning">No se encontraron instituciones para este local (' . $cod_local . ').</div>';
                        return response()->json(['html' => $html]);
                    }

                    $html = '<table class="table table-sm table-bordered table-striped">';
                    $html .= '<thead class="bg-success-0 text-white"><tr><th>Institución Educativa</th><th>Nivel</th></tr></thead>';
                    $html .= '<tbody>';
                    foreach ($instituciones as $inst) {
                        $html .= '<tr><td>' . $inst->nombreInstEduc . '</td><td>' . $inst->nivel . '</td></tr>';
                    }
                    $html .= '</tbody></table>';

                    return response()->json(['html' => $html]);
                } catch (\Exception $e) {
                    return response()->json(['html' => '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>']);
                }

            default:
                return response()->json([]);
        }
    }
}
