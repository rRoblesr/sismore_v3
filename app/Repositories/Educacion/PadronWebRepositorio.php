<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\PadronWeb;
use App\Models\Parametro\Anio;
use Illuminate\Support\Facades\DB;

class PadronWebRepositorio
{
    public static function count_institucioneducativa($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v1.estadoinsedu_id', 3)
            ->distinct()
            ->select('v1.institucioneducativa_id')
            ->get();
        return $query->count();
    }

    public static function count_institucioneducativa2($importacion_id, $provincia, $distrito, $tipogestion, $ambito)
    {
        $query = PadronWeb::select(DB::raw('count(edu_padronweb.institucioneducativa_id) as conteo'))
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'edu_padronweb.institucioneducativa_id')
            ->join('par_centropoblado as v3', 'v3.id', '=', 'v2.CentroPoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.Ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->join('edu_area as v8', 'v8.id', '=', 'v2.Area_id')
            ->where('edu_padronweb.importacion_id', $importacion_id)
            ->where('edu_padronweb.estadoinsedu_id', 3);
        if ($provincia > 0) $query = $query->where('v5.id', $provincia);
        if ($distrito > 0) $query = $query->where('v4.id', $distrito);
        if ($tipogestion > 0) {
            if ($tipogestion == 3) {
                $query = $query->where('v7.id', 3);
            } else {
                $query = $query->where('v7.id', '!=', 3);
            }
        }
        if ($ambito > 0) $query = $query->where('v8.id', $ambito);
        $query = $query->first();
        return $query->conteo;
    }

    public static function count_localesescolares($importacion_id)
    {
        $query = DB::table(DB::raw("(
            select distinct `v2`.`codLocal`
            from `edu_padronweb` as `v1`
            inner join `edu_institucioneducativa` as `v2` on `v2`.`id` = `v1`.`institucioneducativa_id`
            where `v1`.`importacion_id` = $importacion_id and `v1`.`estadoinsedu_id` = 3
        ) as tb"))->select(DB::raw('count(codLocal) conteo'))->get();
        return $query->first()->conteo;
    }

    public static function count_localesescolares2($importacion_id, $provincia, $distrito, $tipogestion, $ambito)
    {
        $query = PadronWeb::distinct()->select('v2.codLocal')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'edu_padronweb.institucioneducativa_id')
            ->join('par_centropoblado as v3', 'v3.id', '=', 'v2.CentroPoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.Ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->join('edu_area as v8', 'v8.id', '=', 'v2.Area_id')
            ->where('edu_padronweb.importacion_id', $importacion_id)
            ->where('edu_padronweb.estadoinsedu_id', 3);
        if ($provincia > 0) $query = $query->where('v5.id', $provincia);
        if ($distrito > 0) $query = $query->where('v4.id', $distrito);
        if ($tipogestion > 0) {
            if ($tipogestion == 3) {
                $query = $query->where('v7.id', 3);
            } else {
                $query = $query->where('v7.id', '!=', 3);
            }
        }
        if ($ambito > 0) $query = $query->where('v8.id', $ambito);
        $query = $query->get();
        return $query->count();
    }

    public static function count_matriculados($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->where('v1.importacion_id', $importacion_id)
            ->select(DB::raw('SUM(v1.total_alumno) as conteo'))
            ->first();
        return $query->conteo;
    }

    public static function count_docente($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->where('v1.importacion_id', $importacion_id)
            ->select(DB::raw('SUM(v1.total_docente) as conteo'))
            ->first();
        return $query->conteo;
    }

    public static function grafica_serviciosylocaleseducativosporugel($importacion_id) //no usado
    {
        $query = DB::table(DB::raw('(
            select ugel as ugel_id, sum(codlocal) as codlocal,sum(codmodular) as codmodular from (
                select ugel,count(codlocal) as codlocal,0 as codmodular from (
                select distinct v2.Ugel_id as ugel, v2.codLocal as codlocal,"" as codmodular  from `edu_padronweb` as `v1`
                inner join `edu_institucioneducativa` as `v2` on `v2`.`id` = `v1`.`institucioneducativa_id`
                where `v1`.`importacion_id` = ' . $importacion_id . ' ) as tt
                group  by ugel
                union
                select ugel,0 as codlocal,count(codModular) as codmodular from (
                select distinct v2.Ugel_id as ugel,"" as codlocal, v2.codModular as codmodular   from `edu_padronweb` as `v1`
                inner join `edu_institucioneducativa` as `v2` on `v2`.`id` = `v1`.`institucioneducativa_id`
                where `v1`.`importacion_id` = ' . $importacion_id . ' ) as tt
                group  by ugel
            ) as td
            group by ugel_id
            ) as v1'))
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->select('v2.nombre as ugel', 'codlocal', 'codmodular')
            ->get();
        //return $query;
        /* $ugel = UgelRepositorio::listar_opt();
        $categoria = [];
        foreach ($ugel as $value)
            $categoria[] = $value->nombre; */
        $categoria = ["UGEL CORONEL PORTILLO",  "UGEL ATALAYA", "UGEL PADRE ABAD", "UGEL PURUS", "DRE UCAYALI"];

        $data[] = ['name' => 'SERVICIOS EDUCATIVOS', 'data' => []];
        $data[] = ['name' => 'LOCALES ESCOLARES', 'data' => []];

        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($query as  $value) {
                if ($value->ugel == $cat) {
                    $data[0]['data'][$pos] = $value->codmodular;
                    $data[1]['data'][$pos] = $value->codlocal;
                }
            }
        }

        $dato['categoria'] = $categoria;
        $dato['categoriax'] = '[';
        foreach ($categoria as $cc) {
            $dato['categoriax'] .= '"' . $cc . '",';
        }
        $dato['categoriax'] .= ']';
        $dato['data'] = $data;
        $dato['datax'] = '[';
        foreach ($data as $cc) {
            $con = '[';
            foreach ($cc['data'] as $dd) {
                $con .= $dd . ',';
            }
            $con .= ']';
            $dato['datax'] .= '{name:"' . $cc['name'] . '",data:' . $con . '},';
        }
        $dato['datax'] .= ']';
        return $dato;
    }
    public static function grafica_estudiantesmatriculadospormodalidad($importacion_id) //no usado
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo', 'v4.nombre')
            ->select('v3.tipo', 'v4.nombre as area', DB::raw('SUM(v1.total_alumno) as conteo'))
            ->get();

        /* $nivel = NivelModalidadRepositorio::distinct_tipo();
        $categoria = [];
        foreach ($nivel as $value)
            $categoria[] = $value->tipo; */
        $categoria = ["EBR", "SNU", "EBA",  "ETP", "EBE"];

        $data[] = ['name' => 'RURAL', 'data' => []];
        $data[] = ['name' => 'URBANA', 'data' => []];

        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($query as  $value) {

                if ($value->tipo == $cat && $value->area == 'Rural') {
                    $data[0]['data'][$pos] = $value->conteo;
                }
                if ($value->tipo == $cat && $value->area == 'Urbana') {
                    $data[1]['data'][$pos] = $value->conteo;
                }
            }
        }
        $dato['categoria'] = $categoria;
        $dato['categoriax'] = '[';
        foreach ($categoria as $cc) {
            $dato['categoriax'] .= '"' . $cc . '",';
        }
        $dato['categoriax'] .= ']';
        $dato['data'] = $data;
        $dato['datax'] = '[';
        foreach ($data as $cc) {
            $con = '[';
            foreach ($cc['data'] as $dd) {
                $con .= $dd . ',';
            }
            $con .= ']';
            $dato['datax'] .= '{name:"' . $cc['name'] . '",data:' . $con . '},';
        }
        $dato['datax'] .= ']';
        return $dato;
    }
    public static function grafica_estudiantessegunmodalidad($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo')
            ->orderBy('y', 'desc')
            ->select('v3.tipo as name', DB::raw('CAST(SUM(v1.total_alumno) AS SIGNED) as y'))
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function grafica_docentessegunmodalidad($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo')
            ->orderBy('y', 'desc')
            ->select('v3.tipo as name', DB::raw('CAST(SUM(v1.total_docente) as SIGNED) as y'))
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function grafica_matriculadosportipogestionyugel($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_tipogestion as v3', 'v3.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v5.nombre', 'v4.nombre')
            ->select('v5.nombre as ugel', 'v4.nombre as gestion', DB::raw('SUM(v1.total_alumno) as conteo'))
            ->get();
        //return $query;
        /* $ugel = UgelRepositorio::listar_opt();
        $categoria = [];
        foreach ($ugel as $value)
            $categoria[] = $value->nombre; */
        $categoria = ["UGEL CORONEL PORTILLO", "UGEL PADRE ABAD",  "UGEL ATALAYA", "DRE UCAYALI", "UGEL PURUS"];

        $data[] = ['name' => 'PRIVADA', 'data' => []];
        $data[] = ['name' => 'PUBLICA', 'data' => []];

        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($query as  $value) {

                if ($value->ugel == $cat && $value->gestion == 'Privada') {
                    $data[0]['data'][$pos] += $value->conteo;
                }
                if ($value->ugel == $cat && $value->gestion == 'Pública de gestión directa') {
                    $data[1]['data'][$pos] += $value->conteo;
                }
                if ($value->ugel == $cat && $value->gestion == 'Pública de gestión privada') {
                    $data[1]['data'][$pos] += $value->conteo;
                }
            }
        }
        $dato['categoria'] = $categoria;
        $dato['categoriax'] = '[';
        foreach ($categoria as $cc) {
            $dato['categoriax'] .= '"' . $cc . '",';
        }
        $dato['categoriax'] .= ']';
        $dato['data'] = $data;
        $dato['datax'] = '[';
        foreach ($data as $cc) {
            $con = '[';
            foreach ($cc['data'] as $dd) {
                $con .= $dd . ',';
            }
            $con .= ']';
            $dato['datax'] .= '{name:"' . $cc['name'] . '",data:' . $con . '},';
        }
        $dato['datax'] .= ']';
        return $dato;
    }
    public static function grafica_matriculadosporugel($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            //->join('edu_tipogestion as v3', 'v3.id', '=', 'v2.TipoGestion_id')
            //->join('edu_tipogestion as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v5.nombre'/* , 'v4.nombre' */)
            ->orderBy('y', 'desc')
            ->select('v5.nombre as name',/*  'v4.nombre as gestion', */ DB::raw('CAST(SUM(v1.total_alumno) as SIGNED) as y'))
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function grafica_docentesportipogestionyugel($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_tipogestion as v3', 'v3.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v5.nombre', 'v4.nombre')
            ->select('v5.nombre as ugel', 'v4.nombre as gestion', DB::raw('SUM(v1.total_docente) as conteo'))
            ->get();
        /* $ugel = UgelRepositorio::listar_opt();
        $categoria = [];
        foreach ($ugel as $value)
            $categoria[] = $value->nombre; */
        $categoria = ["UGEL CORONEL PORTILLO",  "UGEL ATALAYA", "UGEL PADRE ABAD",  "DRE UCAYALI", "UGEL PURUS"];

        $data[] = ['name' => 'PRIVADA', 'data' => []];
        $data[] = ['name' => 'PUBLICA', 'data' => []];

        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($query as  $value) {

                if ($value->ugel == $cat && $value->gestion == 'Privada') {
                    $data[0]['data'][$pos] += $value->conteo;
                }
                if ($value->ugel == $cat && $value->gestion == 'Pública de gestión directa') {
                    $data[1]['data'][$pos] += $value->conteo;
                }
                if ($value->ugel == $cat && $value->gestion == 'Pública de gestión privada') {
                    $data[1]['data'][$pos] += $value->conteo;
                }
            }
        }
        $dato['categoria'] = $categoria;
        $dato['categoriax'] = '[';
        foreach ($categoria as $cc) {
            $dato['categoriax'] .= '"' . $cc . '",';
        }
        $dato['categoriax'] .= ']';
        $dato['data'] = $data;
        $dato['datax'] = '[';
        foreach ($data as $cc) {
            $con = '[';
            foreach ($cc['data'] as $dd) {
                $con .= $dd . ',';
            }
            $con .= ']';
            $dato['datax'] .= '{name:"' . $cc['name'] . '",data:' . $con . '},';
        }
        $dato['datax'] .= ']';
        return $dato;
    }
    public static function grafica_docentesporugel($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_ugel as v5', 'v5.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('name')
            ->orderBy('y', 'desc')
            ->select('v5.nombre as name',  DB::raw('CAST(SUM(v1.total_docente) as SIGNED) as y'))
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function grafica_matriculadosporareageografica($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_area as v3', 'v3.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.nombre')
            ->select(
                'v3.nombre as name',
                DB::raw('cast(SUM(v1.total_alumno) as SIGNED) as y'),
                DB::raw('FORMAT(cast(SUM(v1.total_alumno)  as SIGNED),0) as conteo'),
            )
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function grafica_docentesporareageografica($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_area as v3', 'v3.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.nombre')
            ->select(
                'v3.nombre as name',
                DB::raw('cast(SUM(v1.total_docente) as SIGNED) as y'),
                DB::raw('FORMAT(cast(SUM(v1.total_docente)  as SIGNED),0) as conteo'),
            )
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function listar_nivelmodalidadvstipogestion($importacion_id)
    {
        $head = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_tipogestion as v4', 'v4.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v5', 'v5.id', '=', 'v4.dependencia')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo')
            ->select(
                'v3.tipo',
                DB::raw('sum(if(v5.id=3,v1.total_alumno_m,0)) as privadom'),
                DB::raw('sum(if(v5.id=3,v1.total_alumno_f,0)) as privadof'),
                DB::raw('sum(if(v5.id!=3,v1.total_alumno_m,0)) as publicom'),
                DB::raw('sum(if(v5.id!=3,v1.total_alumno_f,0)) as publicof'),
                DB::raw('sum(v1.total_alumno_m) as totalm'),
                DB::raw('sum(v1.total_alumno_f) as totalf'),
            )
            ->get();
        $body = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_tipogestion as v4', 'v4.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v5', 'v5.id', '=', 'v4.dependencia')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo', 'v3.nombre')
            ->select(
                'v3.tipo',
                'v3.nombre as nivel',
                DB::raw('sum(if(v5.id=3,v1.total_alumno_m,0)) as privadom'),
                DB::raw('sum(if(v5.id=3,v1.total_alumno_f,0)) as privadof'),
                DB::raw('sum(if(v5.id!=3,v1.total_alumno_m,0)) as publicom'),
                DB::raw('sum(if(v5.id!=3,v1.total_alumno_f,0)) as publicof'),
                DB::raw('sum(v1.total_alumno_m) as totalm'),
                DB::raw('sum(v1.total_alumno_f) as totalf'),
            )
            ->get();
        $foot = ['privadom' => 0, 'privadof' => 0, 'publicom' => 0, 'publicof' => 0, 'totalm' => 0, 'totalf' => 0];
        foreach ($body as $key => $value) {
            $foot['privadom'] += $value->privadom;
            $foot['privadof'] += $value->privadof;
            $foot['publicom'] += $value->publicom;
            $foot['publicof'] += $value->publicof;
            $foot['totalm'] += $value->totalm;
            $foot['totalf'] += $value->totalf;
        }
        foreach ($body as $key => $value) {
            $value->privadomp = round($value->privadom * 100 / $foot['privadom'], 2);
            $value->privadofp = round($value->privadof * 100 / $foot['privadof'], 2);
            $value->publicomp = round($value->publicom * 100 / $foot['publicom'], 2);
            $value->publicofp = round($value->publicof * 100 / $foot['publicof'], 2);
            $value->totalmp = round($value->totalm * 100 / $foot['totalm'], 2);
            $value->totalfp = round($value->totalf * 100 / $foot['totalf'], 2);
        }

        foreach ($head as $key => $value) {
            $value->privadomp = round($value->privadom * 100 / $foot['privadom'], 2);
            $value->privadofp = round($value->privadof * 100 / $foot['privadof'], 2);
            $value->publicomp = round($value->publicom * 100 / $foot['publicom'], 2);
            $value->publicofp = round($value->publicof * 100 / $foot['publicof'], 2);
            $value->totalmp = round($value->totalm * 100 / $foot['totalm'], 2);
            $value->totalfp = round($value->totalf * 100 / $foot['totalf'], 2);
        }

        $data['foot'] = $foot;
        $data['body'] = $body;
        $data['head'] = $head;
        return $data;
    }

    public static function listar_nivelmodalidadvsareageografica($importacion_id)
    {
        $head = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo')
            ->select(
                'v3.tipo',
                DB::raw('sum(if(v4.id=2,v1.total_alumno,0)) as alumnor'),
                DB::raw('sum(if(v4.id=2,v1.total_docente,0)) as docenter'),
                DB::raw('sum(if(v4.id!=2,v1.total_alumno,0)) as alumnou'),
                DB::raw('sum(if(v4.id!=2,v1.total_docente,0)) as docenteu'),
                DB::raw('sum(v1.total_alumno) as totala'),
                DB::raw('sum(v1.total_docente) as totald'),
            )
            ->get();
        $body = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_area as v4', 'v4.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v3.tipo', 'v3.nombre')
            ->select(
                'v3.tipo',
                'v3.nombre as nivel',
                DB::raw('sum(if(v4.id=2,v1.total_alumno,0)) as alumnor'),
                DB::raw('sum(if(v4.id=2,v1.total_docente,0)) as docenter'),
                DB::raw('sum(if(v4.id!=2,v1.total_alumno,0)) as alumnou'),
                DB::raw('sum(if(v4.id!=2,v1.total_docente,0)) as docenteu'),
                DB::raw('sum(v1.total_alumno) as totala'),
                DB::raw('sum(v1.total_docente) as totald'),
            )
            ->get();
        $foot = ['alumnor' => 0, 'docenter' => 0, 'alumnou' => 0, 'docenteu' => 0, 'totala' => 0, 'totald' => 0];
        foreach ($body as $key => $value) {
            $foot['alumnor'] += $value->alumnor;
            $foot['docenter'] += $value->docenter;
            $foot['alumnou'] += $value->alumnou;
            $foot['docenteu'] += $value->docenteu;
            $foot['totala'] += $value->totala;
            $foot['totald'] += $value->totald;
        }
        foreach ($body as $key => $value) {
            $value->alumnorp = round($value->alumnor * 100 / $foot['alumnor'], 2);
            $value->docenterp = round($value->docenter * 100 / $foot['docenter'], 2);
            $value->alumnoup = round($value->alumnou * 100 / $foot['alumnou'], 2);
            $value->docenteup = round($value->docenteu * 100 / $foot['docenteu'], 2);
            $value->totalap = round($value->totala * 100 / $foot['totala'], 2);
            $value->totaldp = round($value->totald * 100 / $foot['totald'], 2);
        }

        foreach ($head as $key => $value) {
            $value->alumnorp = round($value->alumnor * 100 / $foot['alumnor'], 2);
            $value->docenterp = round($value->docenter * 100 / $foot['docenter'], 2);
            $value->alumnoup = round($value->alumnou * 100 / $foot['alumnou'], 2);
            $value->docenteup = round($value->docenteu * 100 / $foot['docenteu'], 2);
            $value->totalap = round($value->totala * 100 / $foot['totala'], 2);
            $value->totaldp = round($value->totald * 100 / $foot['totald'], 2);
        }

        $data['foot'] = $foot;
        $data['body'] = $body;
        $data['head'] = $head;
        return $data;
    }

    public static function listar_nivelmodalidadvsugelhombremujer($importacion_id)
    {
        $body = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v4.nombre')
            ->select(
                'v4.nombre as ugel',
                DB::raw('sum(if(v3.tipo="EBA",v1.total_alumno_m,0)) as EBAm'),
                DB::raw('sum(if(v3.tipo="EBA",v1.total_alumno_f,0)) as EBAf'),
                DB::raw('sum(if(v3.tipo="EBE",v1.total_alumno_m,0)) as EBEm'),
                DB::raw('sum(if(v3.tipo="EBE",v1.total_alumno_f,0)) as EBEf'),
                DB::raw('sum(if(v3.tipo="EBR",v1.total_alumno_m,0)) as EBRm'),
                DB::raw('sum(if(v3.tipo="EBR",v1.total_alumno_f,0)) as EBRf'),
                DB::raw('sum(if(v3.tipo="ETP",v1.total_alumno_m,0)) as ETPm'),
                DB::raw('sum(if(v3.tipo="ETP",v1.total_alumno_f,0)) as ETPf'),
                DB::raw('sum(if(v3.tipo="SNU",v1.total_alumno_m,0)) as SNUm'),
                DB::raw('sum(if(v3.tipo="SNU",v1.total_alumno_f,0)) as SNUf'),
                DB::raw('sum(v1.total_alumno_m) as totalm'),
                DB::raw('sum(v1.total_alumno_f) as totalf'),
            )
            ->get();
        $foot = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->select(
                DB::raw('sum(if(v3.tipo="EBA",v1.total_alumno_m,0)) as EBAm'),
                DB::raw('sum(if(v3.tipo="EBA",v1.total_alumno_f,0)) as EBAf'),
                DB::raw('sum(if(v3.tipo="EBE",v1.total_alumno_m,0)) as EBEm'),
                DB::raw('sum(if(v3.tipo="EBE",v1.total_alumno_f,0)) as EBEf'),
                DB::raw('sum(if(v3.tipo="EBR",v1.total_alumno_m,0)) as EBRm'),
                DB::raw('sum(if(v3.tipo="EBR",v1.total_alumno_f,0)) as EBRf'),
                DB::raw('sum(if(v3.tipo="ETP",v1.total_alumno_m,0)) as ETPm'),
                DB::raw('sum(if(v3.tipo="ETP",v1.total_alumno_f,0)) as ETPf'),
                DB::raw('sum(if(v3.tipo="SNU",v1.total_alumno_m,0)) as SNUm'),
                DB::raw('sum(if(v3.tipo="SNU",v1.total_alumno_f,0)) as SNUf'),
                DB::raw('sum(v1.total_alumno_m) as totalm'),
                DB::raw('sum(v1.total_alumno_f) as totalf'),
            )
            ->get()->first();
        $data['foot'] = $foot;
        $data['body'] = $body;
        $data['head'] = [];
        return $data;
    }
    public static function listar_nivelmodalidadvsugeldocentedirectores($importacion_id)
    {
        $body = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v4.nombre')
            ->select(
                'v4.nombre as ugel',
                DB::raw('sum(if(v3.tipo="EBA",v1.total_docente,0)) as EBAm'),
                DB::raw('sum(if(v3.tipo="EBA" and v2.nombreDirector is not null,1,0)) as EBAf'),
                DB::raw('sum(if(v3.tipo="EBE",v1.total_docente,0)) as EBEm'),
                DB::raw('sum(if(v3.tipo="EBE"and v2.nombreDirector is not null,1,0)) as EBEf'),
                DB::raw('sum(if(v3.tipo="EBR",v1.total_docente,0)) as EBRm'),
                DB::raw('sum(if(v3.tipo="EBR"and v2.nombreDirector is not null,1,0)) as EBRf'),
                DB::raw('sum(if(v3.tipo="ETP",v1.total_docente,0)) as ETPm'),
                DB::raw('sum(if(v3.tipo="ETP"and v2.nombreDirector is not null,1,0)) as ETPf'),
                DB::raw('sum(if(v3.tipo="SNU",v1.total_docente,0)) as SNUm'),
                DB::raw('sum(if(v3.tipo="SNU"and v2.nombreDirector is not null,1,0)) as SNUf'),
                DB::raw('sum(v1.total_docente) as totalm'),
                DB::raw('sum(if(v2.nombreDirector is not null,1,0)) as totalf'),
            )
            ->get();
        $foot = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v2.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->select(
                DB::raw('sum(if(v3.tipo="EBA",v1.total_docente,0)) as EBAm'),
                DB::raw('sum(if(v3.tipo="EBA" and v2.nombreDirector is not null,1,0)) as EBAf'),
                DB::raw('sum(if(v3.tipo="EBE",v1.total_docente,0)) as EBEm'),
                DB::raw('sum(if(v3.tipo="EBE"and v2.nombreDirector is not null,1,0)) as EBEf'),
                DB::raw('sum(if(v3.tipo="EBR",v1.total_docente,0)) as EBRm'),
                DB::raw('sum(if(v3.tipo="EBR"and v2.nombreDirector is not null,1,0)) as EBRf'),
                DB::raw('sum(if(v3.tipo="ETP",v1.total_docente,0)) as ETPm'),
                DB::raw('sum(if(v3.tipo="ETP"and v2.nombreDirector is not null,1,0)) as ETPf'),
                DB::raw('sum(if(v3.tipo="SNU",v1.total_docente,0)) as SNUm'),
                DB::raw('sum(if(v3.tipo="SNU"and v2.nombreDirector is not null,1,0)) as SNUf'),
                DB::raw('sum(v1.total_docente) as totalm'),
                DB::raw('sum(if(v2.nombreDirector is not null,1,0)) as totalf'),
            )
            ->get()->first();
        $data['foot'] = $foot;
        $data['body'] = $body;
        $data['head'] = [];
        return $data;
    }

    public static function listar_tipogestionvsprovinciaestudiantesdocente($importacion_id)
    {
        $head = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v3', 'v3.id', '=', 'v2.CentroPoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.Ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->join('edu_area as v8', 'v8.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v5.nombre')
            ->select(
                'v5.nombre as provincia',
                DB::raw('sum(if(v7.id=3,v1.total_alumno,0)) as eprivada'),
                DB::raw('sum(if(v7.id=3,v1.total_docente,0)) as dprivada'),
                DB::raw('sum(if(v7.id!=3,v1.total_alumno,0)) as epublica'),
                DB::raw('sum(if(v7.id!=3,v1.total_docente,0)) as dpublica'),
                DB::raw('sum(if(v8.id=1,v1.total_alumno,0)) as eurbana'),
                DB::raw('sum(if(v8.id=1,v1.total_docente,0)) as durbana'),
                DB::raw('sum(if(v8.id=2,v1.total_alumno,0)) as erural'),
                DB::raw('sum(if(v8.id=2,v1.total_docente,0)) as drural'),
                DB::raw('sum(v1.total_alumno) as talumno'),
                DB::raw('sum(v1.total_docente) as tdocente'),
            )
            ->get();
        $body = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v3', 'v3.id', '=', 'v2.CentroPoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.Ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->join('edu_area as v8', 'v8.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('v5.nombre', 'v4.nombre')
            ->select(
                'v5.nombre as provincia',
                'v4.nombre as distrito',
                DB::raw('sum(if(v7.id=3,v1.total_alumno,0)) as eprivada'),
                DB::raw('sum(if(v7.id=3,v1.total_docente,0)) as dprivada'),
                DB::raw('sum(if(v7.id!=3,v1.total_alumno,0)) as epublica'),
                DB::raw('sum(if(v7.id!=3,v1.total_docente,0)) as dpublica'),
                DB::raw('sum(if(v8.id=1,v1.total_alumno,0)) as eurbana'),
                DB::raw('sum(if(v8.id=1,v1.total_docente,0)) as durbana'),
                DB::raw('sum(if(v8.id=2,v1.total_alumno,0)) as erural'),
                DB::raw('sum(if(v8.id=2,v1.total_docente,0)) as drural'),
                DB::raw('sum(v1.total_alumno) as talumno'),
                DB::raw('sum(v1.total_docente) as tdocente'),
            )
            ->get();
        $foot = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v3', 'v3.id', '=', 'v2.CentroPoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.Ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->join('edu_area as v8', 'v8.id', '=', 'v2.Area_id')
            ->where('v1.importacion_id', $importacion_id)
            ->select(
                DB::raw('sum(if(v7.id=3,v1.total_alumno,0)) as eprivada'),
                DB::raw('sum(if(v7.id=3,v1.total_docente,0)) as dprivada'),
                DB::raw('sum(if(v7.id!=3,v1.total_alumno,0)) as epublica'),
                DB::raw('sum(if(v7.id!=3,v1.total_docente,0)) as dpublica'),
                DB::raw('sum(if(v8.id=1,v1.total_alumno,0)) as eurbana'),
                DB::raw('sum(if(v8.id=1,v1.total_docente,0)) as durbana'),
                DB::raw('sum(if(v8.id=2,v1.total_alumno,0)) as erural'),
                DB::raw('sum(if(v8.id=2,v1.total_docente,0)) as drural'),
                DB::raw('sum(v1.total_alumno) as talumno'),
                DB::raw('sum(v1.total_docente) as tdocente'),
            )
            ->get()->first();
        $data['foot'] = $foot;
        $data['body'] = $body;
        $data['head'] = $head;
        return $data;
    }

    public static function grafica_matriculadosporEBR($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v3.tipo', 'EBR')
            ->groupBy('name')
            ->select(
                DB::raw('case
                when `v3`.`id`=2 || v3.id=1 || v3.id=14 then "Inicial"
                else v3.nombre
                end as `name`'),
                DB::raw('cast(SUM(v1.total_alumno) as SIGNED) as y'),
                DB::raw('FORMAT(cast(SUM(v1.total_alumno)  as SIGNED),0) as conteo'),
            )
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }

    public static function grafica_docentesporEBR($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v2.NivelModalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v3.tipo', 'EBR')
            ->groupBy('name')
            ->select(
                DB::raw('case
                when `v3`.`id`=2 || v3.id=1 || v3.id=14 then "Inicial"
                else v3.nombre
                end as `name`'),
                DB::raw('cast(SUM(v1.total_docente) as SIGNED) as y'),
                DB::raw('FORMAT(cast(SUM(v1.total_docente)  as SIGNED),0) as conteo'),
            )
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }

    public static function grafica_matriculadosportipogestion($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('name')
            ->select(
                DB::raw('case
                            when `v7`.`id`=1||v7.id=2 then "Publico"
                            else v7.nombre
                        end as `name`'),
                DB::raw('cast(SUM(v1.total_alumno) as SIGNED) as y'),
                DB::raw('FORMAT(cast(SUM(v1.total_alumno)  as SIGNED),0) as conteo'),
            )
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }
    public static function grafica_docentesportipogestion($importacion_id)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_tipogestion as v6', 'v6.id', '=', 'v2.TipoGestion_id')
            ->join('edu_tipogestion as v7', 'v7.id', '=', 'v6.dependencia')
            ->where('v1.importacion_id', $importacion_id)
            ->groupBy('name')
            ->select(
                DB::raw('case
                            when `v7`.`id`=1||v7.id=2 then "Público"
                            else v7.nombre
                        end as `name`'),
                DB::raw('cast(SUM(v1.total_docente) as SIGNED) as y'),
                DB::raw('FORMAT(cast(SUM(v1.total_docente)  as SIGNED),0) as conteo'),
            )
            ->get();
        foreach ($query as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $query;
    }


    public static function listar_totalServicosLocalesSecciones($importacion)
    {
        $ids = $importacion->first()->id;
        $tabla = "
        select
            v3.tipo,
            v3.nombre nivel,
            v2.codLocal locales,
            sum(IF(v5.nombre!='Privada' and v2.codLocal,1,0)) publico_locales,
            sum(IF(v5.nombre='Privada' and v2.codLocal,1,0)) privado_locales,
            count(v1.id) servicios,
            sum(IF(v5.nombre!='Privada',1,0)) publico_servicios,
            sum(IF(v5.nombre='Privada',1,0)) privado_servicios,
            sum(v1.total_seccion) secciones,
            sum(IF(v5.nombre!='Privada',v1.total_seccion,0)) publico_secciones ,
            sum(IF(v5.nombre='Privada',v1.total_seccion,0)) privado_secciones
        from edu_padronweb v1
        inner join edu_institucioneducativa v2 on v2.id=v1.institucioneducativa_id
        inner join edu_nivelmodalidad v3 on v3.id=v2.NivelModalidad_id
        inner join edu_tipogestion as v4 on v4.id=v2.TipoGestion_id
        inner join edu_tipogestion as v5 on v5.id=v4.dependencia
        inner join par_importacion as v6 on v6.id=v1.importacion_id
        where v6.estado='PR' and v6.id=$ids and v1.estadoinsedu_id=3
        group by v3.tipo, v3.nombre ,v2.codLocal";
        if ($importacion->count() > 0) {
            $foot = DB::table(DB::raw("(" . $tabla . ") as xx"))
                ->select(
                    DB::raw("SUM(publico_locales)+SUM(privado_locales) ttlc"),
                    DB::raw("SUM(publico_locales) pulc"),
                    DB::raw("SUM(privado_locales) prlc"),
                    DB::raw("SUM(publico_servicios)+SUM(privado_servicios) ttsr"),
                    DB::raw("SUM(publico_servicios) pusr"),
                    DB::raw("SUM(privado_servicios) prsr"),
                    DB::raw("SUM(publico_secciones)+SUM(privado_secciones) ttsc"),
                    DB::raw("SUM(publico_secciones) pusc"),
                    DB::raw("SUM(privado_secciones) prsc"),
                )
                ->get()->first();

            $body = DB::table(DB::raw("(" . $tabla . ") as xx"))
                ->select(
                    "tipo",
                    "nivel",
                    DB::raw("SUM(publico_locales)+SUM(privado_locales) ttlc"),
                    DB::raw("SUM(publico_locales) pulc"),
                    DB::raw("SUM(privado_locales) prlc"),
                    DB::raw("SUM(publico_servicios)+SUM(privado_servicios) ttsr"),
                    DB::raw("SUM(publico_servicios) pusr"),
                    DB::raw("SUM(privado_servicios) prsr"),
                    DB::raw("SUM(publico_secciones)+SUM(privado_secciones) ttsc"),
                    DB::raw("SUM(publico_secciones) pusc"),
                    DB::raw("SUM(privado_secciones) prsc"),
                )
                ->groupBy('tipo', 'nivel')
                ->get();
            $head = DB::table(DB::raw("(" . $tabla . ") as xx"))
                ->select(
                    "tipo",
                    DB::raw("SUM(publico_locales)+SUM(privado_locales) ttlc"),
                    DB::raw("SUM(publico_locales) pulc"),
                    DB::raw("SUM(privado_locales) prlc"),
                    DB::raw("SUM(publico_servicios)+SUM(privado_servicios) ttsr"),
                    DB::raw("SUM(publico_servicios) pusr"),
                    DB::raw("SUM(privado_servicios) prsr"),
                    DB::raw("SUM(publico_secciones)+SUM(privado_secciones) ttsc"),
                    DB::raw("SUM(publico_secciones) pusc"),
                    DB::raw("SUM(privado_secciones) prsc"),
                )
                ->groupBy('tipo')
                ->get();


            return ['head' => $head, 'body' => $body, 'foot' => $foot, 'fecha' => date('d/m/Y', strtotime($importacion->first()->fecha))];
        }
        return [];
    }

    public static function buscariiee($codigo_modular)
    {
        $query = DB::table('edu_padronweb as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucioneducativa_id')
            ->join('par_centropoblado as v3', 'v3.id', '=', 'v2.centropoblado_id')
            ->join('par_ubigeo as v4', 'v4.id', '=', 'v3.ubigeo_id')
            ->join('par_ubigeo as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_nivelmodalidad as v6', 'v6.id', '=', 'v2.nivelmodalidad_id')
            ->take(1)
            ->where('v2.codModular', $codigo_modular)
            ->select(
                'v2.codModular as codigo_modular',
                'v5.nombre as provincia',
                'v4.nombre as distrito',
                'v3.nombre as centro_poblado',
                'v2.codLocal as codigo_local',
                'v2.nombreInstEduc as iiee',
                'v6.codigo as codigo_nivel',
                'v6.nombre as nivel_modalidad',
                'v2.id as idiiee',
                'v2.es_eib as estado',
            )
            ->get();
        return $query;
    }
}
