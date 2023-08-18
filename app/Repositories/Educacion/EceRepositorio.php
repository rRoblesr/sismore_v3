<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Grado;
use App\Models\Educacion\NivelModalidad;
use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;

class EceRepositorio
{
    public static function listar_importaciones()
    {
        $query = DB::table('edu_ece as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->join('edu_grado as v3', 'v3.id', '=', 'v1.grado_id')
            ->join('edu_nivelmodalidad as v4', 'v4.id', '=', 'v3.nivelmodalidad_id')
            ->join('par_anio as v5', 'v5.id', '=', 'v1.anio_id')
            ->select('v1.id', 'v1.importacion_id', 'v5.anio', 'v1.tipo', 'v2.fechaActualizacion as fecha', 'v3.descripcion as grado', 'v4.nombre as nivel', 'v2.estado')
            ->orderBy('v1.id', 'desc')
            ->get();
        return $query;
    }
    public static function ListarImportados($importacion_id)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
            ->join('edu_institucioneducativa as v4', 'v4.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_materia as v5', 'v5.id', '=', 'v1.materia_id')
            ->where('v3.id', $importacion_id)
            ->select('v1.*', 'v4.codModular as codigo_modular', 'v5.descripcion as materia')
            ->get();
        return $query;
    }
    public static function buscar_ece1($importacion_id)
    {
        $query = DB::table('edu_ece as v1')
            ->join('edu_grado as v2', 'v2.id', '=', 'v1.grado_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.nivelmodalidad_id')
            ->join('par_anio as v4', 'v4.id', '=', 'v1.anio_id')
            ->where('v1.importacion_id', $importacion_id)
            ->select('v1.*', 'v4.anio', 'v2.descripcion as grado', 'v3.nombre as nivel')
            ->first();
        return $query;
    }
    public static function listar_eceresultado1($ece)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_materia as v3', 'v3.id', '=', 'v1.materia_id')
            ->where('v1.ece_id', $ece)
            ->select('v1.*', 'v2.codModular as codigo_modular', 'v3.descripcion as materia')
            ->get();
        return $query;
    }

    public static function listarAniosIngresados($grado, $tipo)
    {
        $query = DB::table('edu_ece as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->join('par_anio as v3', 'v3.id', '=', 'v1.anio_id')
            ->where('v1.grado_id', $grado)
            ->where('v1.tipo', $tipo)
            ->where('v2.estado', 'PR')
            ->select('v3.anio')
            ->distinct('v3.anio')
            ->orderBy('v3.anio', 'desc')
            ->get();
        return $query;
    }
    public static function getaniomax($grado, $tipo)
    {
        $query = DB::table('edu_ece as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->where('v1.grado_id', $grado)
            ->where('v1.tipo', $tipo)
            ->where('v2.estado', 'PR')
            ->select('v1.anio')
            ->orderBy('v1.anio', 'desc')
            ->get(['max(v1.anio)']);
        return $query;
    }
    public static function get($grado, $tipo)
    {
        $query = DB::table('edu_ece as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->where('v1.grado_id', $grado)
            ->where('v1.tipo', $tipo)
            ->where('v2.estado', 'PR')
            ->select('v1.anio')
            ->orderBy('v1.anio', 'desc')
            ->get(['max(v1.anio)']);
        return $query;
    }
    public static function listar_indicadorsatisfactorio1($anio, $grado, $tipo, $materia)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_materia as v3', 'v3.id', '=', 'v1.materia_id')
            ->join('par_anio as v4', 'v4.id', '=', 'v2.anio_id')
            ->where('v2.grado_id', $grado)
            ->where('v4.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->groupBy('v1.materia_id')
            ->groupBy('v3.descripcion')
            ->get([
                'v1.materia_id',
                'v3.descripcion as materia',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('ROUND(100*sum(previo)/sum(evaluados),2) as p1'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('ROUND(100*sum(inicio)/sum(evaluados),2) as p2'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('ROUND(100*sum(proceso)/sum(evaluados),2) as p3'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
                DB::raw('ROUND(100*sum(satisfactorio)/sum(evaluados),2) as p4'),
            ]);
        return $query;
    }
    public static function listar_indicadorsatisfactorio($anio, $grado, $tipo) //esta por ver 
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_materia as v3', 'v3.id', '=', 'v1.materia_id')
            ->where('v2.grado_id', $grado)
            ->where('v2.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->orderBy('v1.id', 'asc')
            ->groupBy('v3.descripcion')
            ->get([
                'v3.descripcion as materia',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo)'),
                DB::raw('sum(inicio)'),
                DB::raw('sum(proceso)'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
            ]);
        return $query;
    }
    public static function listar_indicadoranio($anio, $grado, $tipo, $materia, $order)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
            ->join('par_anio as v4', 'v4.id', '=', 'v2.anio_id')
            ->where('v2.grado_id', $grado)
            ->where('v4.anio', '<=', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->where('v3.estado', 'PR')
            ->orderBy('v4.anio', $order)
            ->groupBy('v4.anio')
            ->get([
                'v4.anio',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
            ]);
        return $query;
    }
    public static function listar_indicadorugel($anio, $grado, $tipo, $materia)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v3.Ugel_id')
            ->join('par_importacion as v5', 'v5.id', '=', 'v2.importacion_id')
            ->join('par_anio as v6', 'v6.id', '=', 'v2.anio_id')
            ->where('v2.grado_id', $grado)
            ->where('v6.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->where('v5.estado', 'PR')
            ->orderBy('v4.id', 'asc')
            ->groupBy('v4.nombre')
            ->groupBy('v4.id')
            ->get([
                'v4.id',
                'v4.nombre as ugel',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
            ]);
        return $query;
    }
    public static function listar_indicadordistrito($anio, $grado, $tipo, $materia, $provincia, $id = null)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->join('par_anio as v6', 'v6.id', '=', 'v2.anio_id')
            ->where('v2.grado_id', $grado)
            ->where('v6.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->where('v5.dependencia', $provincia);
        if ($id) $query = $query->where('v5.id', $id);
        $query = $query->groupBy('v5.id')
            ->get([
                'v5.id',
                DB::raw('(SELECT nombre FROM `par_ubigeo` WHERE id=v5.id) as ubigeo'),
                //'v5.id as distrito',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('ROUND(100*sum(previo)/sum(evaluados),2) as p1'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('ROUND(100*sum(inicio)/sum(evaluados),2) as p2'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('ROUND(100*sum(proceso)/sum(evaluados),2) as p3'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
                DB::raw('ROUND(100*sum(satisfactorio)/sum(evaluados),2) as p4'),
            ]);
        foreach ($query as $key => $value) {
            $prov = Ubigeo::find($value->id);
            $value->distrito = $prov->nombre;
        }
        return $query;
    }
    public static function listar_indicadorprovincia($anio, $grado, $tipo, $materia, $dependencia = null)
    {

        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->join('par_anio as v6', 'v6.id', '=', 'v2.anio_id')
            ->where('v2.grado_id', $grado)
            ->where('v6.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia);
        if ($dependencia) $query = $query->where('v5.dependencia', $dependencia);
        $query = $query->groupBy('v5.dependencia')
            ->get([
                'v5.dependencia as id',
                DB::raw('(SELECT nombre FROM `par_ubigeo` WHERE id=v5.dependencia) as ubigeo'),
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('ROUND(100*sum(previo)/sum(evaluados),2) as p1'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('ROUND(100*sum(inicio)/sum(evaluados),2) as p2'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('ROUND(100*sum(proceso)/sum(evaluados),2) as p3'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
                DB::raw('ROUND(100*sum(satisfactorio)/sum(evaluados),2) as p4'),
            ]);
        foreach ($query as $key => $value) {
            $prov = Ubigeo::find($value->id);
            $value->provincia = $prov->nombre;
        }
        return $query;
    }
    public static function listar_indicadordepartamento($anio, $grado, $tipo, $materia)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->join('par_anio as v6', 'v6.id', '=', 'v2.anio_id')
            ->where('v2.grado_id', $grado)
            ->where('v6.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->get([
                DB::raw(' 34 as id'),
                DB::raw('"TODOS(UCAYALI)" as ubigeo'),
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('ROUND(100*sum(previo)/sum(evaluados),2) as p1'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('ROUND(100*sum(inicio)/sum(evaluados),2) as p2'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('ROUND(100*sum(proceso)/sum(evaluados),2) as p3'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
                DB::raw('ROUND(100*sum(satisfactorio)/sum(evaluados),2) as p4'),
            ]);
        return $query;
    }

    public static function listar_gestion1($grado, $tipo)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_tipogestion as v4', 'v4.id', '=', 'v3.TipoGestion_id')
            ->join('par_importacion as v5', 'v5.id', '=', 'v2.importacion_id')
            ->where('v2.grado_id', $grado)
            ->where('v2.tipo', $tipo)
            ->where('v4.estado', 'AC')
            ->where('v5.estado', 'PR')
            ->select('v4.*')
            ->distinct()
            ->get();
        return $query;
    }
    public static function listar_indicadorGestion($anio, $grado, $tipo, $materia, $gestion = null)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_tipogestion as v4', 'v4.id', '=', 'v3.TipoGestion_id')
            //->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            //->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->where('v1.materia_id', $materia)
            ->where('v2.grado_id', $grado)
            ->where('v2.anio', $anio)
            ->where('v2.tipo', $tipo)
            //->where('v3.TipoGestion_id', $gestion)
            ->groupBy('v4.id')
            ->groupBy('v4.nombre')
            ->get([
                'v4.id',
                'v4.nombre',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
            ]);
        return $query;
    }
    public static function listar_indicadorArea($anio, $grado, $tipo, $materia, $area = null)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v3.Area_id')
            ->where('v1.materia_id', $materia)
            ->where('v2.grado_id', $grado)
            ->where('v2.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->groupBy('v4.id')
            ->groupBy('v4.nombre')
            ->get([
                'v4.id',
                'v4.nombre',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
            ]);
        return $query;
    }
    public static function listar_indicadorInstitucion($anio, $grado, $tipo, $materia, $gestion, $area)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->join('par_anio as v6', 'v6.id', '=', 'v2.anio_id')
            ->where('v1.materia_id', $materia)->where('v2.grado_id', $grado)->where('v6.anio', $anio)->where('v2.tipo', $tipo);
        if ($gestion > 0) $query = $query->where('v3.TipoGestion_id', $gestion);
        if ($area > 0) $query = $query->where('v3.Area_id', $area);
        $query = $query->orderBy('provincia')->orderBy('distrito')->orderBy('v3.nombreInstEduc')->groupBy('v3.id')
            ->groupBy('v3.nombreInstEduc')->groupBy('v5.nombre')->groupBy('v5.dependencia')
            ->get([
                'v3.id',
                'v3.nombreInstEduc as nombre',
                DB::raw('(SELECT nombre FROM par_ubigeo WHERE id=v5.dependencia) as provincia'),
                'v5.nombre as distrito',
                DB::raw('sum(evaluados) as evaluados'),
                DB::raw('sum(previo) as previo'),
                DB::raw('ROUND(100*sum(previo)/sum(evaluados),2) as p1'),
                DB::raw('sum(inicio) as inicio'),
                DB::raw('ROUND(100*sum(inicio)/sum(evaluados),2) as p2'),
                DB::raw('sum(proceso) as proceso'),
                DB::raw('ROUND(100*sum(proceso)/sum(evaluados),2) as p3'),
                DB::raw('sum(satisfactorio) as satisfactorio'),
                DB::raw('ROUND(100*sum(satisfactorio)/sum(evaluados),2) as p4'),
            ]);
        return $query;
    }

    /*public static function listar_eceresultado1($ece)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_materia as v3', 'v3.id', '=', 'v1.materia_id')
            ->where('v1.ece_id', $ece)
            ->select('v1.*', 'v2.codModular as codigo_modular', 'v3.descripcion as materia')
            ->get();
        return $query;
    }*/
    /*
    public static function buscar_resultado1($anio, $grado, $tipo, $materia, $provincia)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->where('v2.grado_id', $grado)
            ->where('v2.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->where('v5.dependencia', $provincia)
            ->get([
                DB::raw('sum(v1.programados) as programados'),
                DB::raw('sum(v1.evaluados) as evaluados'),
                DB::raw('sum(v1.satisfactorio) as satisfactorio')
            ]);
        return $query;
    }
    public static function buscar_resultado2($anio, $grado, $tipo, $materia)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->where('v2.grado_id', $grado)
            ->where('v2.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->get([
                DB::raw('sum(v1.programados) as programados'),
                DB::raw('sum(v1.evaluados) as evaluados'),
                DB::raw('sum(v1.satisfactorio) as satisfactorio')
            ]);
        return $query;
    }
    public static function buscar_resultado3($anio, $grado, $tipo, $materia, $distrito)
    {
        $query = DB::table('edu_eceresultado as v1')
            ->join('edu_ece as v2', 'v2.id', '=', 'v1.ece_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v4', 'v4.id', '=', 'v3.CentroPoblado_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.Ubigeo_id')
            ->where('v2.grado_id', $grado)
            ->where('v2.anio', $anio)
            ->where('v2.tipo', $tipo)
            ->where('v1.materia_id', $materia)
            ->where('v5.id', $distrito)
            ->get([
                DB::raw('sum(v1.programados) as programados'),
                DB::raw('sum(v1.evaluados) as evaluados'),
                DB::raw('sum(v1.satisfactorio) as satisfactorio')
            ]);
        return $query;
    }
    public static function buscar_ece1($importacion_id)
    {
        $query = DB::table('edu_ece as v1')
            ->join('edu_grado as v2', 'v2.id', '=', 'v1.grado_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.nivelmodalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->select('v1.*', 'v2.descripcion as grado', 'v3.nombre as nivel')
            ->first();
        return $query;
    }*/

    /*public static function buscar_nivel1()
    {
        $query = NivelModalidad::whereIn('id', ['37', '38'])->get();
        return $query;
    }
    public static function buscar_grado1($grado, $nivel) //no usado todavia
    {
        $query = Grado::where('id', $grado)->where('nivelmodalidad_id', $nivel)->first();
        return $query;
    }
    public static function buscar_grados1($nivel)
    {
        $query = Grado::where('nivelmodalidad_id', $nivel)->get();
        return $query;
    }*/
}
