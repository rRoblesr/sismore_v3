<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Tableta;
use App\Models\Educacion\TabletaDetalle;
use Illuminate\Support\Facades\DB;

class TabletaRepositorio
{

    public static function tableta_mas_actual()
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_tableta as tab', 'imp.id', '=', 'tab.importacion_id')
            ->join('par_anio as vanio', 'tab.anio_id', '=', 'vanio.id')
            ->where('imp.estado', '=', 'PR')
            ->orderBy('vanio.anio', 'desc')
            ->orderBy('imp.fechaActualizacion', 'desc')
            ->select('tab.id', 'imp.fechaActualizacion')
            ->limit(1)
            ->get();

        return $data;
    }

    public static function tableta_anio()
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_tableta as tab', 'imp.id', '=', 'tab.importacion_id')
            ->join('par_anio as vanio', 'tab.anio_id', '=', 'vanio.id')
            ->where('imp.estado', '=', 'PR')
            ->orderBy('vanio.anio', 'desc')
            ->select('vanio.id', 'vanio.anio')
            ->distinct()
            ->get();

        return $data;
    }

    public static function fechas_tabletas_anio($anio_id)
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_tableta as tab', 'imp.id', '=', 'tab.importacion_id')
            ->join('par_anio as vanio', 'tab.anio_id', '=', 'vanio.id')
            ->where('vanio.id', '=', $anio_id)
            ->where('imp.estado', '=', 'PR')
            ->orderBy('imp.fechaActualizacion', 'desc')
            ->select('tab.id as tableta_id', 'imp.fechaActualizacion', 'vanio.id', 'vanio.anio')
            ->get();

        return $data;
    }

    public static function datos_tableta($id)
    {
        $data = Tableta::select('imp.fechaactualizacion')
            ->join('par_importacion as imp', 'edu_tableta.importacion_id', '=', 'imp.id')
            ->where("edu_tableta.id", "=", $id)
            ->get();

        return $data;
    }

    public static function resumen_tabletas_ugel($tableta_id)
    {
        $data = DB::table('edu_tableta as tab')
            ->join('edu_tableta_detalle as tabDet', 'tab.id', '=', 'tabDet.tableta_id')
            ->join('edu_institucioneducativa as inst', 'tabDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            ->where('tab.id', '=', $tableta_id)
            ->orderBy('ugel.nombre', 'asc')
            ->groupBy('ugel.nombre')
            ->get([
                DB::raw('ugel.nombre as ugel'),
                DB::raw('sum(ifnull(aDistribuir_estudiantes,0) + ifnull(aDistribuir_docentes,0)) as total_aDistribuir'),
                DB::raw('sum(case when (aDistribuir_estudiantes + aDistribuir_docentes)> 0 then 1 else 0 end) as nroInstituciones_aDistribuir'),

                DB::raw('sum(ifnull(despachadas_estudiantes,0) + ifnull(despachadas_docentes,0)) as total_Despachado'),
                DB::raw('sum(case when (despachadas_estudiantes + despachadas_docentes)> 0 then 1 else 0 end) as nroInstituciones_Despachado'),

                DB::raw('sum(ifnull(recepcionadas_estudiantes,0) + ifnull(recepcionadas_docentes,0)) as total_Recepcionadas'),
                DB::raw('sum(case when (recepcionadas_estudiantes + recepcionadas_docentes)> 0 then 1 else 0 end) as nroInstituciones_Recepcionadas'),

                DB::raw('sum(ifnull(asignadas_estudiantes,0) + ifnull(asignadas_docentes,0)) as total_Asignadas'),
                DB::raw('sum(case when (asignadas_estudiantes + asignadas_docentes)> 0 then 1 else 0 end) as nroInstituciones_Asignadas'),
            ]);

        return $data;
    }

    public static function resumen_tabletas_anio($anio_id)
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_tableta as tab', 'imp.id', '=', 'tab.importacion_id')
            ->join('edu_tableta_detalle as tabDet', 'tab.id', '=', 'tabDet.tableta_id')
            ->join('edu_institucioneducativa as inst', 'tabDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            ->where('tab.anio_id', '=', $anio_id)
            ->where('imp.estado', '=', 'PR')
            ->orderBy('ugel.nombre', 'asc')
            ->groupBy('fechaActualizacion')

            ->get([
                DB::raw('fechaActualizacion'),

                DB::raw('sum(ifnull(aDistribuir_estudiantes,0) + ifnull(aDistribuir_docentes,0)) as total_aDistribuir'),
                DB::raw('sum(ifnull(despachadas_estudiantes,0) + ifnull(despachadas_docentes,0)) as total_Despachado'),
                DB::raw('sum(ifnull(recepcionadas_estudiantes,0) + ifnull(recepcionadas_docentes,0)) as total_Recepcionadas'),
                DB::raw('sum(ifnull(asignadas_estudiantes,0) + ifnull(asignadas_docentes,0)) as total_Asignadas'),
            ]);

        return $data;
    }

    public static function tabletas_ultimaActualizacion()
    {
        $data = DB::table('par_importacion as imp')
            ->join('edu_tableta as tab', 'imp.id', '=', 'tab.importacion_id')
            ->join('edu_tableta_detalle as tabDet', 'tab.id', '=', 'tabDet.tableta_id')
            ->join('edu_institucioneducativa as inst', 'tabDet.institucioneducativa_id', '=', 'inst.id')
            ->join('edu_ugel as ugel', 'inst.Ugel_id', '=', 'ugel.id')
            ->where('imp.estado', '=', 'PR')
            ->orderBy('fechaActualizacion', 'desc')
            ->groupBy('fechaActualizacion')
            ->limit(1)
            ->get([
                DB::raw('fechaActualizacion'),

                DB::raw('sum(ifnull(aDistribuir_estudiantes,0) + ifnull(aDistribuir_docentes,0)) as total_aDistribuir'),
                DB::raw('sum(ifnull(despachadas_estudiantes,0) + ifnull(despachadas_docentes,0)) as total_Despachado'),
                DB::raw('sum(ifnull(recepcionadas_estudiantes,0) + ifnull(recepcionadas_docentes,0)) as total_Recepcionadas'),
                DB::raw('sum(ifnull(asignadas_estudiantes,0) + ifnull(asignadas_docentes,0)) as total_Asignadas'),
            ]);

        return $data;
    }

    public static function principalHead($anio, $provincia, $distrito, $area, $valor)
    {
        $tableta = Tableta::select('edu_tableta.*')
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_tableta.importacion_id')
            ->join('par_anio as v2', 'v2.id', '=', 'edu_tableta.anio_id')
            ->where('v2.id', $anio)
            ->first();
        $query = TabletaDetalle::join('edu_institucioneducativa as v1', 'v1.id', '=', 'edu_tableta_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
            ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v1.Area_id')
            ->where('edu_tableta_detalle.tableta_id', $tableta->id);
        if ($distrito > 0) $query = $query->where('v3.id', $distrito);
        if ($provincia > 0) $query = $query->where('v3.dependencia', $provincia);
        if ($area > 0) $query = $query->where('v4.id', $area);

        switch ($valor) {
            case 1:
                return $query = $query->select(DB::raw('sum(tabletas_programadas) conteo'))->first()->conteo;
            case 2:
                return $query = $query->select(DB::raw('sum(tabletas_asignadas) conteo'))->first()->conteo;
            case 3:
                return $query = $query->select(DB::raw('sum(cargadores_programadas) conteo'))->first()->conteo;
            case 4:
                return $query = $query->select(DB::raw('sum(cargadores_asignadas) conteo'))->first()->conteo;
            default:
                return 0;
        }
    }

    public static function principalTabla($anio, $provincia, $distrito, $area, $valor)
    {
        $tableta = Tableta::select('edu_tableta.*')
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_tableta.importacion_id')
            ->join('par_anio as v2', 'v2.id', '=', 'edu_tableta.anio_id')
            ->where('v2.id', $anio)
            ->first();
        $query = TabletaDetalle::join('edu_institucioneducativa as v1', 'v1.id', '=', 'edu_tableta_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
            ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v1.Area_id')
            ->where('edu_tableta_detalle.tableta_id', $tableta->id);

        if ($distrito > 0) $query = $query->where('v3.id', $distrito);
        if ($provincia > 0) $query = $query->where('v3.dependencia', $provincia);
        if ($area > 0) $query = $query->where('v4.id', $area);

        switch ($valor) {
            case 'anal1':
                return $query = $query->select(
                    DB::raw('sum(IF(v1.NivelModalidad_id=7,tabletas_asignadas,0)) as pta'),
                    DB::raw('sum(IF(v1.NivelModalidad_id=8,tabletas_asignadas,0)) as sta'),
                    DB::raw('sum(IF(v1.NivelModalidad_id=7,cargadores_asignadas,0)) as pca'),
                    DB::raw('sum(IF(v1.NivelModalidad_id=8,cargadores_asignadas,0)) as sca'),
                )->get();
            case 'anal2':
                return $query = $query->select(
                    DB::raw('sum(IF(v1.Area_id=1,tabletas_asignadas,0)) as uta'),
                    DB::raw('sum(IF(v1.Area_id=2,tabletas_asignadas,0)) as rta'),
                    DB::raw('sum(IF(v1.Area_id=1,cargadores_asignadas,0)) as uca'),
                    DB::raw('sum(IF(v1.Area_id=2,cargadores_asignadas,0)) as rca'),
                )->get();
            case 'anal3':
                return $query = $query->select(
                    DB::raw('sum(tabletas_asignadas_estudiantes) as ata'),
                    DB::raw('sum(cargadores_asignadas_estudiantes) as aca'),
                    DB::raw('sum(tabletas_asignadas_docentes) as dta'),
                    DB::raw('sum(cargadores_asignadas_docentes) as dca'),
                )->get();
            default:
                return [];
        }
    }

    public static function principalTablaTipo2($anio, $provincia, $distrito, $area, $valor)
    {
        $tableta = Tableta::select('edu_tableta.*')
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_tableta.importacion_id')
            ->join('par_anio as v2', 'v2.id', '=', 'edu_tableta.anio_id')
            ->where('v2.id', $anio)
            ->first();
        $query = TabletaDetalle::join('edu_institucioneducativa as v1', 'v1.id', '=', 'edu_tableta_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
            ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v1.Area_id')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v6', 'v6.id', '=', 'v1.NivelModalidad_id')
            ->where('edu_tableta_detalle.tableta_id', $tableta->id);

        if ($distrito > 0) $query = $query->where('v3.id', $distrito);
        if ($provincia > 0) $query = $query->where('v3.dependencia', $provincia);
        if ($area > 0) $query = $query->where('v4.id', $area);

        switch ($valor) {
            case 'tabla1':
                return $query = $query->select(
                    'v1.Ugel_id as ugel_id',
                    'v5.nombre as ugel',
                    DB::raw('sum(tabletas_programadas) as t1'),
                    DB::raw('sum(tabletas_asignadas) as t2'),
                    DB::raw('100*sum(tabletas_asignadas)/sum(tabletas_programadas) as t3'),
                    DB::raw('sum(tabletas_devueltas) as t4'),
                    DB::raw('sum(tabletas_perdidas) as t5'),
                    DB::raw('sum(cargadores_programadas) as c1'),
                    DB::raw('sum(cargadores_asignadas) as c2'),
                    DB::raw('100*sum(cargadores_asignadas)/sum(cargadores_programadas) as c3'),
                    DB::raw('sum(cargadores_devueltos) as c4'),
                    DB::raw('sum(cargadores_perdidos) as c5'),
                )->groupBy('ugel_id', 'ugel')->get();
            case 'tabla2':
                return $query = $query->select(
                    'v1.codModular as modular',
                    'v1.nombreInstEduc as iiee',
                    'v6.nombre_matricula as nivel',
                    'v4.nombre as area',
                    DB::raw('(tabletas_asignadas_estudiantes) as te'),
                    DB::raw('(tabletas_asignadas_docentes) as td'),
                    DB::raw('(tabletas_asignadas_estudiantes)+(tabletas_asignadas_docentes) as tt'),
                    DB::raw('(cargadores_asignadas_estudiantes) as ce'),
                    DB::raw('(cargadores_asignadas_docentes) as cd'),
                    DB::raw('(cargadores_asignadas_estudiantes)+(cargadores_asignadas_docentes) as ct'),
                )->get();
            default:
                return [];
        }
    }
}
