<?php

namespace App\Repositories\Educacion;

use App\Http\Controllers\Educacion\ImporCensoMatriculaController;
use App\Models\Educacion\Area;
use App\Models\Educacion\ImporCensoDocente;
use App\Models\Educacion\ImporCensoMatricula;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class ImporCensoMatriculaRepositorio
{
    public static function anios() //usando desde 2018
    {
        $query = ImporCensoMatricula::distinct()->select(DB::raw('year(v1.fechaActualizacion) as anio'))
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
            ->where(DB::raw('year(v1.fechaActualizacion)'), '>', 2017)->where('estado', 'PR')
            ->get();
        return $query;
    }

    public static function anioMax()
    {
        $query = ImporCensoMatricula::select(DB::raw('max(year(v1.fechaActualizacion)) as anio'))
            ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
            ->where('estado', 'PR')
            ->first()->anio;
        return $query;
    }

    public static function listarAnios()
    {
        return Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))
            ->where('fuenteImportacion_id', ImporCensoMatriculaController::$FUENTE)
            ->where('estado', 'PR')
            ->orderBy('anio', 'asc')->get();
    }

    public static function listarAnios6()
    {
        return Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))
            ->where('fuenteImportacion_id', ImporCensoMatriculaController::$FUENTE)
            ->where('estado', 'PR')
            ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)
            ->orderBy('anio', 'asc')->get();
    }

    public static function ugels($cedula)
    {
        $ugel = ImporCensoMatricula::distinct()->select('codooii as codigo')->where('nroced', $cedula)->get();
        $ugel2 = Ugel::all();
        foreach ($ugel as $value) {
            foreach ($ugel2 as $value2) {
                if ($value->codigo == $value2->codigo) {
                    $value->nombre = $value2->nombre; //strtoupper($value2->nombre);
                    break;
                }
            }
        }
        return $ugel;
    }

    public static function area($cedula)
    {
        $ugel = ImporCensoMatricula::distinct()->select('area_censo as codigo')->where('nroced', $cedula)->get();
        $ugel2 = Area::all();
        foreach ($ugel as $value) {
            foreach ($ugel2 as $value2) {
                if ($value->codigo == $value2->codigo) {
                    $value->nombre = strtoupper($value2->nombre);
                    break;
                }
            }
        }
        return $ugel;
    }

    public static function iiee($anio, $cedula)
    {
        $query = ImporCensoMatricula::distinct()->select('cod_mod')
            ->join('par_importacion as imp', 'imp.id', '=', 'edu_impor_censomatricula.importacion_id')
            ->where(DB::raw('year(imp.fechaActualizacion)'), $anio)->where('nroced', $cedula)->get();
        foreach ($query as $key => $value) {
            $value->nombre = InstitucionEducativa::where('codModular', $value->cod_mod)->first()->nombreInstEduc;
        }
        // $query->orderBy('nombre');
        return $query;
    }

    public static function _5APrincipalHead($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                switch ($anio) {
                    //case 2021:$cuadro = 'C201';break;
                    default:
                        $cuadro = 'C201';
                        break;
                }
                $query = ImporCensoMatricula::distinct()->select('cod_mod')
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '5A')->where('cuadro', $cuadro)->where('v1.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->get()->count();
            case 2:
                switch ($anio) {
                    //case 2021:$cuadro = 'C201';break;
                    default:
                        $cuadro = 'C201';
                        break;
                }
                $query = ImporCensoMatricula::select(DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '5A')->where('cuadro', $cuadro);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            case 3:
                $query = Importacion::select(
                    DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as conteo'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C207'])->where('tipdato', '!=', '0100')
                    ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query->first()->conteo;
            case 4:
                $query = ImporCensoDocente::select(DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censodocente.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '5A')->where('cuadro', 'C304'); //->whereIn('tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            default:
                return 0;
        }
    }

    public static function _5AReportes($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    ELSE IF(cuadro="C201",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    END) as total')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)->where('par_importacion.estado', 'PR')
                    ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C201']);

                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 2:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",v1.d01+v1.d02,0)
                    WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",v1.d01+v1.d02,0)
                    ELSE IF(cuadro="C203",d01+d02,0)
                    END) as at'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",v1.d03+v1.d04,0)
                    WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",v1.d03+v1.d04,0)
                    ELSE IF(cuadro="C203",d03+d04,0)
                    END) as t')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)
                    ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C203'])->where('par_importacion.estado', 'PR');

                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 3:
                $query = Importacion::select(
                    DB::raw('v2.grupo as name'),
                    DB::raw('sum(v1.d01+v1.d03+v1.d05+v1.d07+v1.d09+v1.d11+v1.d13+v1.d15+v1.d17+v1.d19) as h'),
                    DB::raw('sum(v1.d02+v1.d04+v1.d06+v1.d08+v1.d10+v1.d12+v1.d14+v1.d16+v1.d18+v1.d20) as m'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_grupoedad as v2', 'v2.edad', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C201'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');

                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('name', 'asc')->get();
                return $query;
            case 4:
                $query = Importacion::select(
                    DB::raw('v2.lengua as name'),
                    DB::raw('sum(v1.d01+v1.d03+v1.d05+v1.d07+v1.d09+v1.d11+v1.d13+v1.d15+v1.d17+v1.d19) as h'),
                    DB::raw('sum(v1.d02+v1.d04+v1.d06+v1.d08+v1.d10+v1.d12+v1.d14+v1.d16+v1.d18+v1.d20) as m'),
                    DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as tt'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('censo_lengua as v2', 'v2.codigo', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C207'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->whereNotIn('v2.codigo', ['0100', '01'])->where('par_importacion.estado', 'PR');

                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('tt', 'desc')->get();
                return $query;
            case 5:
                $query = Importacion::select(
                    'cod_mod as modular',
                    'v2.nombreInstEduc as iiee',
                    'codgeo as distrito',
                    'ges_dep as gestion',
                    'area_censo as area',
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",v1.d01+v1.d03,0)
                    WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",v1.d01+v1.d03,0)
                    ELSE IF(cuadro="C201",d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    END) as at'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",v1.d02+v1.d04,0)
                    WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",v1.d02+v1.d04,0)
                    ELSE IF(cuadro="C201",d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    END) as t')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_institucioneducativa as v2', 'v2.codModular', '=', 'cod_mod')
                    ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C201'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('modular', 'distrito', 'iiee', 'gestion', 'area')->get();
                return $query;
                break;
            default:
                return 0;
        }
    }

    public static function _5ATotalEstudianteAnio($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(CASE
            WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",v1.d01+v1.d02+v1.d03+v1.d04,0)
            WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",v1.d01+v1.d02+v1.d03+v1.d04,0)
            ELSE IF(cuadro="C201",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
            END) as total')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR')
            ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C201']);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->first();
        return $query;
    }

    public static function _5ATotalEstudiantesAnioMeta($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(CASE
            WHEN year(fechaActualizacion)=20019 THEN IF(cuadro="C202",v1.d01+v1.d02+v1.d03+v1.d04,0)
            WHEN year(fechaActualizacion)=20021 THEN IF(cuadro="C201",v1.d01+v1.d02+v1.d03+v1.d04,0)
            ELSE IF(cuadro="C201",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
            END) as meta')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C201'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _5ATotalDocentesAnioModular($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(if(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)) as n'),
            DB::raw('sum(if(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)) as c'),
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['5A'])->whereIn('v1.cuadro', ['C304'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _6APrincipalHead($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                switch ($anio) {
                    case 2017:
                        $cuadro = ['C201', 'C202'];
                        break;
                    case 2018:
                        $cuadro = ['C201', 'C202'];
                        break;
                    case 2023:
                        $cuadro = ['C201', 'C208', 'C215'];
                        break;
                    case 2024:
                        $cuadro = ['C208'];
                        break;
                    default:
                        $cuadro = ['C201', 'C202', 'C203'];
                        break;
                }
                $query = ImporCensoMatricula::distinct()->select('cod_mod')
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '6A')->whereIn('cuadro', $cuadro)
                    ->where('v1.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->get()->count();
            case 2:
                $query = ImporCensoMatricula::select(
                    DB::raw('sum(CASE
                WHEN year(fechaActualizacion) in (2017,2018) THEN IF(cuadro in ("C201","C202"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16,0)
                WHEN year(fechaActualizacion) in (2023) THEN IF(cuadro in ("C201","C208","C215"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                WHEN year(fechaActualizacion) in (2024) THEN IF(cuadro in ("C208"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                ELSE IF(cuadro in ("C201","C202","C203"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                END ) as conteo'),
                )
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '6A')->whereIn('cuadro', ['C201', 'C202', 'C203', 'C208', 'C215']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            case 3:
                $query = Importacion::select(
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019
                        THEN IF(cuadro in("C217","C218","C219"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    WHEN year(fechaActualizacion)=2023
                        THEN IF(cuadro in("C206","C213","C220"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    WHEN year(fechaActualizacion)=2024
                        THEN IF(cuadro in("C213"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    ELSE IF(cuadro in("C218","C219","C220"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    END) as conteo'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C206', 'C213', 'C217', 'C218', 'C219', 'C220'])->whereNotIn('tipdato', ['0100', '01'])
                    ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query->first()->conteo;
            case 4:
                $query = ImporCensoDocente::select(DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censodocente.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '6A')->where('cuadro', 'C304'); //->whereIn('tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            default:
                return 0;
        }
    }

    public static function _6AReportes($anio, $provincia, $distrito, $iiee,  $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1: //$cuadro = ['C201', 'C208', 'C215'];
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in("C201","C202"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16,0)
                    WHEN year(fechaActualizacion) in(2023) THEN IF(cuadro in("C201","C208","C215"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    WHEN year(fechaActualizacion) in(2024) THEN IF(cuadro in("C208"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    ELSE IF(cuadro in("C201","C202","C203"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    END ) as total')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)->where('par_importacion.estado', 'PR')
                    ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203', 'C208', 'C215']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 2:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in("C205","C206"),v1.d01+v1.d02,0)
                    ELSE IF(cuadro in("C207","C208","C209"),d01+d02,0)
                    END) as at'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in("C205","C206"),v1.d03+v1.d04,0)
                    ELSE IF(cuadro in("C207","C208","C209"),d03+d04,0)
                    END) as t')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)
                    ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C205', 'C206', 'C207', 'C208', 'C209'])->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 3:
                $query = Importacion::select(
                    DB::raw('v2.grupo as name'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in("C201","C202"),d01+d03+d05+d07+d09+d11+d13+d15,0)
                    WHEN year(fechaActualizacion) in(2023) THEN IF(cuadro in("C201","C208","C215"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    WHEN year(fechaActualizacion) in(2024) THEN IF(cuadro in("C208"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    ELSE IF(cuadro in("C201","C202","C203"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    END ) as h'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in("C201","C202"),d02+d04+d06+d08+d10+d12+d14+d16,0)
                    WHEN year(fechaActualizacion) in(2023) THEN IF(cuadro in("C201","C208","C215"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    WHEN year(fechaActualizacion) in(2024) THEN IF(cuadro in("C208"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    ELSE IF(cuadro in("C201","C202","C203"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    END ) as m'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_grupoedad as v2', 'v2.edad', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203', 'C208', 'C215'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('name', 'asc')->get();
                return $query;
            case 4:
                $query = Importacion::select(
                    DB::raw('v2.lengua as name'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019
                        THEN IF(cuadro in("C217","C218","C219"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    WHEN year(fechaActualizacion)=2023
                        THEN IF(cuadro in("C206","C213","C220"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                        WHEN year(fechaActualizacion)=2024
                        THEN IF(cuadro in("C213"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    ELSE IF(cuadro in("C218","C219","C220"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    END) as h'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019
                        THEN IF(cuadro in("C217","C218","C219"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    WHEN year(fechaActualizacion)=2023
                        THEN IF(cuadro in("C206","C213","C220"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                        WHEN year(fechaActualizacion)=2024
                        THEN IF(cuadro in("C213"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    ELSE IF(cuadro in("C218","C219","C220"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    END) as m'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019
                        THEN IF(cuadro in("C217","C218","C219"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    WHEN year(fechaActualizacion)=2023
                        THEN IF(cuadro in("C206","C213","C220"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                        WHEN year(fechaActualizacion)=2024
                        THEN IF(cuadro in("C213"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    ELSE IF(cuadro in("C218","C219","C220"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    END) as tt'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('censo_lengua as v2', 'v2.codigo', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C206', 'C213', 'C217', 'C218', 'C219', 'C220'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('v2.codigo', '!=', '0100')->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('tt', 'desc')->get();
                return $query;
            case 5:
                $query = Importacion::select(
                    'cod_mod as modular',
                    'v2.nombreInstEduc as iiee',
                    'codgeo as distrito',
                    'ges_dep as gestion',
                    'area_censo as area',
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in("C201","C202"),d01+d03+d05+d07+d09+d11+d13+d15,0)
                    WHEN year(fechaActualizacion) in(2023) THEN IF(cuadro in("C201","C208","C215"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    WHEN year(fechaActualizacion) in(2024) THEN IF(cuadro in("C208"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    ELSE IF(cuadro in("C201","C202","C203"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                    END ) as at'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro="C201" OR cuadro="C202",d02+d04+d06+d08+d10+d12+d14+d16,0)
                    WHEN year(fechaActualizacion) in(2023) THEN IF(cuadro in("C201","C208","C215"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    WHEN year(fechaActualizacion) in(2024) THEN IF(cuadro in("C208"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    ELSE IF(cuadro in("C201","C202","C203"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                    END ) as t'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_institucioneducativa as v2', 'v2.codModular', '=', 'cod_mod')
                    ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203', 'C208', 'C215'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');

                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('modular', 'distrito', 'iiee', 'gestion', 'area')->get();
                return $query;
                break;
            default:
                return 0;
        }
    }

    public static function _6ATotalEstudianteAnio($anio, $provincia, $distrito, $iiee,  $area, $gestion)
    {
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(CASE
                        WHEN year(fechaActualizacion) in(2017,2018) THEN IF(cuadro in ("C201","C202"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16,0)
                        WHEN year(fechaActualizacion) in(2023) THEN IF(cuadro in("C201","C208","C215"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                        WHEN year(fechaActualizacion) in(2024) THEN IF(cuadro in("C208"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                        ELSE IF(cuadro in("C201","C202","C203"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    END ) as total')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR')
            ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203', 'C208', 'C215']);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->first();
        return $query;
    }

    public static function _6ATotalEstudiantesAnioMeta($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(CASE
            WHEN year(fechaActualizacion) in(2017,2018)
                THEN IF(cuadro="C201" OR cuadro="C202",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
            ELSE IF(cuadro="C201" OR cuadro="C202" OR cuadro="C203" OR cuadro="C208",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
            END ) as meta')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _6ATotalDocentesAnioModular($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(if(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)) as n'),
            DB::raw('sum(if(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)) as c'),
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['6A'])->whereIn('v1.cuadro', ['C304'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }


    public static function _7APrincipalHead($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                $query = ImporCensoMatricula::distinct()->select('cod_mod')
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '7A')->whereIn('cuadro', ['C201', 'C202'])->where('v1.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->get()->count();
            case 2:
                switch ($anio) {
                    //case 2021:$cuadro = 'C201';break;
                    default:
                        $cuadro = 'C202';
                        break;
                }
                $query = ImporCensoMatricula::select(DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '7A')->whereIn('cuadro', ['C201', 'C202']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            case 3:
                $query = Importacion::select(
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019 or year(fechaActualizacion)=2020
                        THEN IF(cuadro="C212" or cuadro="C213",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    ELSE IF(cuadro="C214" or cuadro="C215",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                    END) as conteo')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C212', 'C213', 'C214', 'C215'])->where('tipdato', '!=', '0100')
                    ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query->first()->conteo;
            case 4:
                $query = ImporCensoDocente::select(DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censodocente.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '7A')->where('cuadro', 'C304'); //->whereIn('tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            default:
                return 0;
        }
    }

    public static function _7AReportes($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    //DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as total'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C201" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                                when "C209" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                                else 0
                                            end
                                when 2024 then
                                            case cuadro
                                                when "C201" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                                when "C209" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                                else 0
                                            end                                            
                                else
                                    case cuadro
                                        when "C201" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                        when "C202" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                        else 0
                                    end
                            end ) as total')

                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)->where('par_importacion.estado', 'PR')
                    ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C209']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 2:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    // DB::raw('sum(d01+d02) as at'),
                    // DB::raw('sum(d03+d04) as t'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C211" then d01+d02
                                                else 0
                                            end
                                else
                                    case cuadro
                                        when "C205" then d01+d02
                                        when "C206" then d01+d02
                                        else 0
                                    end
                            end ) as at'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C211" then d03+d04
                                                else 0
                                            end
                                else
                                    case cuadro
                                        when "C205" then d03+d04
                                        when "C206" then d03+d04
                                        else 0
                                    end
                            end ) as t'),

                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)
                    ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C205', 'C206', 'C211'])->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 3:
                $query = Importacion::select(
                    DB::raw('v2.grupo as name'),
                    // DB::raw('sum(d01+d03+d05+d07+d09+d11+d13+d15+d17+d19) as h'),
                    // DB::raw('sum(d02+d04+d06+d08+d10+d12+d14+d16+d18+d20) as m'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C201" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                                when "C209" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                                else 0
                                            end
                                else
                                    case cuadro
                                        when "C201" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                        when "C202" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                        else 0
                                    end
                            end ) as h'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C201" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                                when "C209" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                                else 0
                                            end
                                else
                                    case cuadro
                                        when "C201" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                        when "C202" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                        else 0
                                    end
                            end ) as m'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_grupoedad as v2', 'v2.edad', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C209'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('name', 'asc')->get();
                return $query;
            case 4:
                $query = Importacion::select(
                    DB::raw('v2.lengua as name'),
                    DB::raw('sum(CASE
                                    WHEN year(fechaActualizacion)=2018 THEN 0
                                    WHEN year(fechaActualizacion) in(2019,2020)
                                        THEN IF(cuadro in("C212","C213"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                                    WHEN year(fechaActualizacion)=2023
                                        THEN IF(cuadro in("C206","C214"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                                    ELSE IF(cuadro in("C214","C215"),d01+d03+d05+d07+d09+d11+d13+d15+d17+d19,0)
                                END) as h'),
                    DB::raw('sum(CASE
                                    WHEN year(fechaActualizacion)=2018 THEN 0
                                    WHEN year(fechaActualizacion) in(2019,2020)
                                        THEN IF(cuadro in("C212","C213"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                                    WHEN year(fechaActualizacion)=2023
                                        THEN IF(cuadro in("C206","C214"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                                    ELSE IF(cuadro in("C214","C215"),d02+d04+d06+d08+d10+d12+d14+d16+d18+d20,0)
                                END) as m'),
                    DB::raw('sum(CASE
                                    WHEN year(fechaActualizacion)=2018 THEN 0
                                    WHEN year(fechaActualizacion) in(2019,2020)
                                        THEN IF(cuadro in("C212","C213"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                                    WHEN year(fechaActualizacion)=2023
                                        THEN IF(cuadro in("C206","C214"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                                    ELSE IF(cuadro in("C214","C215"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20,0)
                                END) as tt')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('censo_lengua as v2', 'v2.codigo', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C206', 'C212', 'C213', 'C214', 'C215'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('tt', 'desc')->get();
                return $query;
            case 5:
                $query = Importacion::select(
                    'cod_mod as modular',
                    'v2.nombreInstEduc as iiee',
                    'codgeo as distrito',
                    'ges_dep as gestion',
                    'area_censo as area',
                    // DB::raw('sum(d01+d03+d05+d07+d09+d11+d13+d15+d17+d19) as at'),
                    // DB::raw('sum(d02+d04+d06+d08+d10+d12+d14+d16+d18+d20) as t'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C201" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                                when "C209" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                                else 0
                                            end
                                else
                                    case cuadro
                                        when "C201" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                        when "C202" then d01+d03+d05+d07+d09+d11+d13+d15+d17+d19
                                        else 0
                                    end
                            end ) as at'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2023 then
                                            case cuadro
                                                when "C201" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                                when "C209" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                                else 0
                                            end
                                else
                                    case cuadro
                                        when "C201" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                        when "C202" then d02+d04+d06+d08+d10+d12+d14+d16+d18+d20
                                        else 0
                                    end
                            end ) as t'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_institucioneducativa as v2', 'v2.codModular', '=', 'cod_mod')
                    ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C209'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('modular', 'distrito', 'iiee', 'gestion', 'area')->get();
                return $query;
                break;
            default:
                return 0;
        }
    }

    public static function _7ATotalEstudianteAnio($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            //DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as total'),
            DB::raw('sum(
                case year(par_importacion.fechaActualizacion)
                    when 2023 then
                            case cuadro
                                when "C201" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                when "C209" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                                else 0
                            end
                    else
                        case cuadro
                            when "C201" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                            when "C202" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20
                            else 0
                        end
                end ) as total')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR')
            ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C209']);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->first();
        return $query;
    }

    public static function _7ATotalEstudiantesAnioMeta($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20) as meta')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C201', 'C202'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _7ATotalDocentesAnioModular($anio, $provincia, $distrito, $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(if(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)) as n'),
            DB::raw('sum(if(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)) as c'),
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['7A'])->whereIn('v1.cuadro', ['C304'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _9ATotalEstudianteAnio($anio, $provincia, $distrito,  $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(CASE
                        WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d01+v1.d02+v1.d03+v1.d04,0)
                        WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d01+v1.d02+v1.d03+v1.d04,0)
                        ELSE IF(cuadro="C203",v1.d01+v1.d02+v1.d03+v1.d04,0)
                        END) as total')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR')
            ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203']);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->first();
        return $query;
    }

    public static function _9ATotalEstudiantesAnioMeta($anio, $provincia, $distrito,  $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(CASE
            WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d01+v1.d02+v1.d03+v1.d04,0)
            WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d01+v1.d02+v1.d03+v1.d04,0)
            ELSE IF(cuadro="C203",v1.d01+v1.d02+v1.d03+v1.d04,0)
            END) as meta')
        )
            ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _9ATotalDocentesAnioModular($anio, $provincia, $distrito,  $iiee, $area, $gestion)
    {
        $query = Importacion::select(
            'cod_mod as modular',
            DB::raw('sum(if(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)) as n'),
            DB::raw('sum(if(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)) as c'),
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C304'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('codgeo', $dist->codigo);
        }
        if ($iiee > 0) {
            $query = $query->where('cod_mod', $iiee);
        }
        if ($area > 0) {
            $query = $query->where('area_censo', $area);
        }
        if ($gestion > 0) {
            if ($gestion == 3)
                $query = $query->whereIn('ges_dep', ['B3', 'B4']);
            else
                $query = $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
        }
        $query = $query->groupBy('modular')->get();
        return $query;
    }

    public static function _9APrincipalHead($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                switch ($anio) {
                    case 2021:
                        $cuadro = 'C201';
                        break;
                    default:
                        $cuadro = 'C203';
                        break;
                }
                $query = ImporCensoMatricula::distinct()->select('cod_mod')
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '9A')->where('cuadro', $cuadro)->where('v1.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->get()->count();
            case 2:
                switch ($anio) {
                    case 2021:
                        $cuadro = 'C201';
                        break;
                    default:
                        $cuadro = 'C203';
                        break;
                }
                $query = ImporCensoMatricula::select(DB::raw('sum(d01+d02+d03+d04) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censomatricula.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '9A')->where('cuadro', $cuadro);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            case 3:
                $query = Importacion::select(
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019 or year(fechaActualizacion)=2020 or year(fechaActualizacion)=2021
                        THEN IF(cuadro="C206",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    ELSE IF(cuadro="C207",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    END) as conteo')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C206', 'C207'])->whereNotIn('tipdato', ['0100', '01'])
                    ->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query->first()->conteo;
            case 4:
                $query = ImporCensoDocente::select(DB::raw('sum(d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24) as conteo'))
                    ->join('par_importacion as v1', 'v1.id', '=', 'edu_impor_censodocente.importacion_id')
                    ->where(DB::raw('year(v1.fechaActualizacion)'), $anio)->where('nroced', '9A')->where('cuadro', 'C305')->whereIn('tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                return $query = $query->first()->conteo;
            default:
                return 0;
        }
    }

    public static function _9AReportes($anio, $provincia, $distrito, $iiee, $area, $gestion, $valor)
    {
        switch ($valor) {
            case 1:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    ELSE IF(cuadro="C203",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    END) as total')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)->where('par_importacion.estado', 'PR')
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 2:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d01+v1.d02,0)
                    WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d01+v1.d02,0)
                    ELSE IF(cuadro="C203",v1.d01+v1.d02,0)
                    END) as at'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d03+v1.d04,0)
                    WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d03+v1.d04,0)
                    ELSE IF(cuadro="C203",v1.d03+v1.d04,0)
                    END) as t')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '>', 2017)
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203'])->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query;
            case 3:
                $query = Importacion::select(
                    DB::raw('v2.grupo as name'),
                    DB::raw('sum(v1.d01+v1.d03+v1.d05+v1.d07+v1.d09+v1.d11+v1.d13+v1.d15+v1.d17+v1.d19) as h'),
                    DB::raw('sum(v1.d02+v1.d04+v1.d06+v1.d08+v1.d10+v1.d12+v1.d14+v1.d16+v1.d18+v1.d20) as m'),
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_grupoedad as v2', 'v2.edad', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201'])->where(DB::raw('year(fechaActualizacion)'), $anio)->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('name', 'asc')->get();
                return $query;
            case 4:
                $query = Importacion::select(
                    DB::raw('v2.lengua as name'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019 or year(fechaActualizacion)=2020 or year(fechaActualizacion)=2021
                        THEN IF(cuadro="C206",v1.d01+v1.d03,0)
                    ELSE IF(cuadro="C207",v1.d01+v1.d03,0)
                    END) as h'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019 or year(fechaActualizacion)=2020 or year(fechaActualizacion)=2021
                        THEN IF(cuadro="C206",v1.d02+v1.d04,0)
                    ELSE IF(cuadro="C207",v1.d02+v1.d04,0)
                    END) as m'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2018 THEN 0
                    WHEN year(fechaActualizacion)=2019 or year(fechaActualizacion)=2020 or year(fechaActualizacion)=2021
                        THEN IF(cuadro="C206",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    ELSE IF(cuadro="C207",v1.d01+v1.d02+v1.d03+v1.d04,0)
                    END) as tt')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('censo_lengua as v2', 'v2.codigo', '=', 'tipdato')
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C206', 'C207'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->whereNotIn('v2.codigo', ['0100', '01'])->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('name')->orderBy('tt', 'desc')->get();
                return $query;
            case 5:
                $query = Importacion::select(
                    'cod_mod as modular',
                    'v2.nombreInstEduc as iiee',
                    'codgeo as distrito',
                    'ges_dep as gestion',
                    'area_censo as area',
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d01+v1.d03,0)
                    WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d01+v1.d03,0)
                    ELSE IF(cuadro="C203",v1.d01+v1.d03,0)
                    END) as at'),
                    DB::raw('sum(CASE
                    WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d02+v1.d04,0)
                    WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d02+v1.d04,0)
                    ELSE IF(cuadro="C203",v1.d02+v1.d04,0)
                    END) as t')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_institucioneducativa as v2', 'v2.codModular', '=', 'cod_mod')
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203'])->where(DB::raw('year(fechaActualizacion)'), $anio)
                    ->where('par_importacion.estado', 'PR');
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('modular', 'distrito', 'iiee', 'gestion', 'area')->get();
                return $query;
                /* case 6:
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(CASE
                        WHEN year(fechaActualizacion)=2019 THEN IF(cuadro="C202",v1.d01+v1.d02+v1.d03+v1.d04,0)
                        WHEN year(fechaActualizacion)=2021 THEN IF(cuadro="C201",v1.d01+v1.d02+v1.d03+v1.d04,0)
                        ELSE IF(cuadro="C203",v1.d01+v1.d02+v1.d03+v1.d04,0)
                        END) as total')
                )
                    ->join('edu_impor_censomatricula as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->where(DB::raw('year(fechaActualizacion)'), '=', $anio)->where('par_importacion.estado', 'PR')
                    ->whereIn('v1.nroced', ['9A'])->whereIn('v1.cuadro', ['C201', 'C202', 'C203']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('codgeo', $dist->codigo);
                }
                if ($iiee > 0) {
                    $query = $query->where('cod_mod', $iiee);
                }
                if ($area > 0) {
                    $query = $query->where('area_censo', $area);
                }
                if ($gestion > 0) {
                    $query = $gestion == 3 ? $query->whereIn('ges_dep', ['B3', 'B4']) : $query->whereIn('ges_dep', ['A1', 'A2', 'A3', 'A4']);
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->get();
                return $query; */
            default:
                return 0;
        }
    }
}
