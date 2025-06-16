<?php

namespace App\Repositories\Educacion;

use App\Http\Controllers\Educacion\ImporCensoDocenteController;
use App\Models\Educacion\Area;
use App\Models\Educacion\ImporCensoDocente;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\TipoGestion;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class ImporCensoDocenteRepositorio
{
    public static function _1AReportes($div, $anio, $provincia, $distrito, $gestion, $area)
    {
        switch ($div) {
            case 'head':

                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                            when 2019 then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                            when 2020 then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                            when 2021 then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                            when 2022 then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                            when 2023 then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                        end
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $titulados = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then (case year(par_importacion.fechaActualizacion)
                                                         when 2018 then v1.d01+v1.d02+v1.d03+v1.d04
                                                         when 2023 then v1.d01+v1.d02+v1.d03+v1.d04
                                                     end)
                                    when "C310" then (case year(par_importacion.fechaActualizacion)
                                                         when 2019 then v1.d01+v1.d02+v1.d03+v1.d04
                                                         when 2020 then v1.d01+v1.d02+v1.d03+v1.d04
                                                         when 2021 then v1.d01+v1.d02+v1.d03+v1.d04
                                                         when 2022 then v1.d01+v1.d02+v1.d03+v1.d04
                                                     end)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $titulados = $titulados->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $titulados = $titulados->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $titulados = $titulados->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                return compact('docentes', 'titulados');
            case 'dianal0':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();

                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case 
                            when year(par_importacion.fechaActualizacion)     in(2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                            when year(par_importacion.fechaActualizacion) not in(2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                        end
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $titulados = Importacion::select(
                    DB::raw('year(fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case 
                                    when cuadro="C309" and year(fechaActualizacion) in(2018,2023,2024)      then v1.d01+v1.d02+v1.d03+v1.d04
                                    when cuadro="C310" and year(fechaActualizacion) in(2019,2020,2021,2022) then v1.d01+v1.d02+v1.d03+v1.d04
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $titulados = $titulados->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $titulados = $titulados->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $titulados = $titulados->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                return compact('anios', 'docentes', 'titulados');
            case 'dianal1':
            case 'dianal2':
                $query = Importacion::select(
                    DB::raw('year(fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d02,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d03,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d04,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                return $query;

            case 'dianal3':
                $query = Importacion::select(
                    DB::raw('year(fechaActualizacion) as anio'),
                    'area_censo as area',
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                            end) as d'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('anio', 'area')->orderBy('area', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                return $query;

            case 'ctabla1':
                $query = Importacion::select(
                    'cod_mod as modular',
                    DB::raw('max(case v1.area_censo when "1" then "Urbana" when "2" then "Rural" end) as area'),
                    DB::raw('max(case when v1.ges_dep in("A1","A2","A3","A4") then "Pública" when v1.ges_dep in("B3","B4") then "Privada" end) as gestion'),
                    DB::raw('max(ds.nombre) as distrito'),
                    DB::raw('sum(IF(cuadro="C305" and tipdato in("01","05"),
                                case
                                    when year(fechaActualizacion)     in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                                    when year(fechaActualizacion) not in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                                end ,0)) as total'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01,0)
                                end ,0)) as d01'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d02,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d02,0)
                                end ,0)) as d02'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d03,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d03,0)
                                end ,0)) as d03'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d04,0)
                                end ,0)) as d04'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end ,0)) as tt'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end ,0)) as ttn'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("01","03","07","08"),
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end ,0)) as ttc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_ubigeo as ds', 'ds.codigo', '=', 'v1.codgeo')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305', 'C309', 'C310'])->whereIn('v1.tipdato', ['01', '05', '03', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('modular')->get();

                return $query;

            case 'ctabla2':
                $query = Importacion::select(
                    DB::raw('uu.codigo as cod_ugel'),
                    DB::raw('uu.nombre as ugel'),
                    DB::raw('sum(IF(cuadro="C305" and tipdato in("01","05"),
                                case
                                    when year(fechaActualizacion)     in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                                    when year(fechaActualizacion) not in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11
                                end ,0)) as td'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as tt'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d03,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d03,0)
                                end) as tth'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d02+v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d02+v1.d04,0)
                                end) as ttm'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02,0)
                                end) as ttn'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d03+v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d03+v1.d04,0)
                                end) as ttc'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024) and v1.ges_dep in("A1","A2","A3","A4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024) and v1.ges_dep in("A1","A2","A3","A4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                            end) as pub'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024) and v1.ges_dep in("B3","B4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024) and v1.ges_dep in("B3","B4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                            end) as pri'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024) and v1.area_censo=1,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024) and v1.area_censo=1,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            end) as urb'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024) and v1.area_censo=2,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024) and v1.area_censo=2,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            end) as rur'),

                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_ugel as uu', 'uu.codigo', '=', 'v1.codooii')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305', 'C309', 'C310'])->whereIn('v1.tipdato', ['05', '01', '03', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('cod_ugel', 'ugel')->get();
 
                return $query;

            default:
                return response()->json([]);
        }
    }

    public static function _3APReportes($div, $anio, $provincia, $distrito, $gestion, $area)
    {
        switch ($div) {
            case 'head':
                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case
                                    when year(par_importacion.fechaActualizacion)     in(2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                                    when year(par_importacion.fechaActualizacion) not in(2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                // if ($ambito > 0) {
                //     $area = Area::find($ambito);
                //     $docentes = $docentes->where('v1.area_censo', $area->codigo);
                // }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $titulados = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)     in(2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion) not in(2018,2023),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $titulados = $titulados->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $titulados = $titulados->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                // if ($ambito > 0) {
                //     $area = Area::find($ambito);
                //     $titulados = $titulados->where('v1.area_censo', $area->codigo);
                // }
                $titulados = $titulados->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                return compact('docentes', 'titulados');
            case 'dpanal0':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', ImporCensoDocenteController::$FUENTE)->where('estado', 'PR')->orderBy('anio')->get();

                $titulados = Importacion::select(
                    DB::raw('year(fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(fechaActualizacion)     in (2018,2019),(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(fechaActualizacion) not in (2018,2019),v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);

                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $titulados = $titulados->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $titulados = $titulados->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                if ($area > 0) {
                    $areax = Area::find($area);
                    $titulados = $titulados->where('v1.area_censo', $areax->codigo);
                }
                $titulados = $titulados->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(fechaActualizacion) in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                // if ($ambito > 0) {
                //     $area = Area::find($ambito);
                //     $docentes = $docentes->where('v1.area_censo', $area->codigo);
                // }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();
                return compact('anios', 'docentes', 'titulados');

            case 'dsanal1':
            case 'dsanal2':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion) in (2018,2023,2024),v1.d02,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)    in (2018,2023,2024),v1.d03,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)not in (2018,2023,2024),v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                return $query;

            case 'dsanal3':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    'area_censo as area',
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                return $query = $query->groupBy('anio', 'area')->orderBy('area', 'asc')->orderBy('v1.tipdato', 'desc')->get();

            case 'ctabla1':
                $query = Importacion::select(
                    'cod_mod as modular',
                    DB::raw('max(case v1.area_censo when "1" then "Urbana" when "2" then "Rural" end) as area'),
                    DB::raw('max(case when v1.ges_dep in("A1","A2","A3","A4") then "Pública" when v1.ges_dep in("B3","B4") then "Privada" end) as gestion'),
                    DB::raw('max(ds.nombre) as distrito'),
                    DB::raw('sum(IF(cuadro="C305" and tipdato in("01","05"),
                                    case
                                        when year(fechaActualizacion)     in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                                        when year(fechaActualizacion) not in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15
                                    end ,0)) as total'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01,0)
                                    end ,0)) as d01'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d02,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d02,0)
                                    end ,0)) as d02'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d03,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d03,0)
                                    end ,0)) as d03'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d04,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d04,0)
                                    end ,0)) as d04'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    end ,0)) as tt'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    end ,0)) as ttn'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato in("02","04","07","08"),
                                    case v1.cuadro
                                        when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                        when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    end ,0)) as ttc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_ubigeo as ds', 'ds.codigo', '=', 'v1.codgeo')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305', 'C309', 'C310'])->whereIn('v1.tipdato', ['01', '05', '02', '04', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('modular')->get();

                return $query;

            case 'ctabla2':
                $query = Importacion::select(
                    DB::raw('uu.codigo as cod_ugel'),
                    DB::raw('uu.nombre as ugel'),
                    DB::raw('sum(IF(cuadro="C305" and tipdato in("01","05"),
                                    case
                                        when year(fechaActualizacion)     in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13
                                        when year(fechaActualizacion) not in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15
                                    end ,0)) as td'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as tt'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d03,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d03,0)
                                end) as tth'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024),v1.d02+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024),v1.d02+v1.d04,0)
                                end) as ttm'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02,0)
                                end) as ttn'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024),v1.d03+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024),v1.d03+v1.d04,0)
                                end) as ttc'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024) and v1.ges_dep in("A1","A2","A3","A4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024) and v1.ges_dep in("A1","A2","A3","A4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as pub'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024) and v1.ges_dep in("B3","B4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024) and v1.ges_dep in("B3","B4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as pri'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024) and v1.area_censo=1,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024) and v1.area_censo=1,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as urb'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(tipdato in("02","04","07","08") and year(fechaActualizacion)     in (2018,2023,2024) and v1.area_censo=2,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(tipdato in("02","04","07","08") and year(fechaActualizacion) not in (2018,2023,2024) and v1.area_censo=2,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as rur'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_ugel as uu', 'uu.codigo', '=', 'v1.codooii')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305', 'C309', 'C310'])->whereIn('v1.tipdato', ['01', '05', '02', '04', '07', '08'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('cod_ugel', 'ugel')->get();

                return $query;
            default:
                return response()->json([]);
        }
    }

    public static function _3ASReportes($div, $anio, $provincia, $distrito, $gestion, $area)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($div) {
            case 'head':
                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                            case
                                when year(par_importacion.fechaActualizacion)     in(2018,2019)
                                    then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25
                                when year(par_importacion.fechaActualizacion) not in(2018,2019)
                                    then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26
                            end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $titulados = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $titulados = $titulados->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $titulados = $titulados->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $titulados = $titulados->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                return compact('docentes', 'titulados');
            case 'dsanal0':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();

                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(fechaActualizacion)     in (2018,2019),(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                        IF(year(fechaActualizacion) not in (2018,2019),v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $titulados = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case 
                                    when v1.cuadro="C309" and year(fechaActualizacion) in(2018,2023,2024)       then v1.d01+v1.d02+v1.d03+v1.d04
                                    when v1.cuadro="C310" and year(fechaActualizacion) in(2019,2020,2021,2022)  then v1.d01+v1.d02+v1.d03+v1.d04
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $titulados = $titulados->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $titulados = $titulados->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $titulados = $titulados->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $titulados = $titulados->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                return compact('anios', 'docentes', 'titulados');

            case 'dsanal1':
            case 'dsanal2':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d02,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d03,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d04,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                return $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

            case 'dsanal3':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    'area_censo as area',
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                return $query = $query->groupBy('anio', 'area')->orderBy('area', 'asc')->orderBy('v1.tipdato', 'desc')->get();

            case 'ctabla1':
                $query = Importacion::select(
                    'cod_mod as modular',
                    DB::raw('max(case v1.area_censo when "1" then "Urbana" when "2" then "Rural" end) as area'),
                    DB::raw('max(case when v1.ges_dep in("A1","A2","A3","A4") then "Pública" when v1.ges_dep in("B3","B4") then "Privada" end) as gestion'),
                    DB::raw('max(ds.nombre) as distrito'),
                    DB::raw('sum(IF(cuadro="C305" and tipdato in("01","05"),
                                        case
                                            when year(fechaActualizacion)     in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25
                                            when year(fechaActualizacion) not in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26
                                        end ,0)) as total'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01,0)
                                        end ,0)) as d01'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d02,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d02,0)
                                        end ,0)) as d02'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d03,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d03,0)
                                        end ,0)) as d03'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d04,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d04,0)
                                        end ,0)) as d04'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                        end ,0)) as tt'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                        end ,0)) as ttn'),
                    DB::raw('sum(IF(cuadro in("C309","C310") and tipdato not in("01","02","03","04","05","06","42"),
                                        case v1.cuadro
                                            when "C309" then if(year(fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                            when "C310" then if(year(fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                        end ,0)) as ttc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('par_ubigeo as ds', 'ds.codigo', '=', 'v1.codgeo')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305', 'C309', 'C310'])->whereNotIn('v1.tipdato', ['02', '03', '04', '06', '42'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('modular')->get();

                return $query;
            case 'ctabla2':
                $query = Importacion::select(
                    DB::raw('uu.codigo as cod_ugel'),
                    DB::raw('uu.nombre as ugel'),
                    DB::raw('sum(
                                case
                                    when cuadro="C305" and tipdato in("01","05") and year(fechaActualizacion)     in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25
                                    when cuadro="C305" and tipdato in("01","05") and year(fechaActualizacion) not in (2018,2019) then v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26
                                    else 0
                                end ) as td'),
                    DB::raw('sum(
                                case  
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as tt'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d03,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d03,0)
                                end) as tth'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d02+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d02+v1.d04,0)
                                end) as ttm'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d01+v1.d02,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d01+v1.d02,0)
                                end) as ttn'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024),v1.d03+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024),v1.d03+v1.d04,0)
                                end) as ttc'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024) and v1.ges_dep in("A1","A2","A3","A4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024) and v1.ges_dep in("A1","A2","A3","A4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as pub'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024) and v1.ges_dep in("B3","B4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024) and v1.ges_dep in("B3","B4"),v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as pri'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024) and v1.area_censo=1,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024) and v1.area_censo=1,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as urb'),
                    DB::raw('sum(
                                case
                                    when cuadro="C309" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion)     in (2018,2023,2024) and v1.area_censo=2,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when cuadro="C310" and tipdato not in("01","05") then if(year(par_importacion.fechaActualizacion) not in (2018,2023,2024) and v1.area_censo=2,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as rur'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->join('edu_ugel as uu', 'uu.codigo', '=', 'v1.codooii')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305', 'C309', 'C310'])->whereNotIn('v1.tipdato', ['02', '03', '04', '06', '42'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->groupBy('cod_ugel', 'ugel')->get();

                return $query;
            default:
                return response()->json([]);
        }
    }

    public static function basicaregular($div, $anio, $provincia, $distrito, $gestion, $area)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($div) {
            case 'censodocente001':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                //IF(year(par_importacion.fechaActualizacion) in (2018,2019),(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                //IF(year(par_importacion.fechaActualizacion) not in (2018,2019),v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('case nroced when "1A" then "Inicial" when "3AP" then "Primaria" when "3AS" then "Secundaria" end as nivel'),
                    DB::raw('sum(
                                case year(par_importacion.fechaActualizacion)
                                    when 2018 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end
                                    when 2019 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end
                                    when 2020 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end
                                    when 2021 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end
                                    when 2022 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end
                                    when 2023 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end
                                    when 2024 then
                                                case nroced
                                                    when  "1A" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                    when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                    when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                end                                                
                                end) as conteo')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS'])
                    ->whereIn('v1.cuadro', ['C301']);
                //->whereIn('v1.tipdato', ['01', '05']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $docentes->groupBy('anio', 'nroced')->orderBy('anio', 'asc')->get();


                return $docentes; //compact('anios', 'docentes');

            default:
                return response()->json([]);
        }
    }

    public static function PersonaDocente($div, $anio, $provincia, $distrito, $gestion, $area)
    {
        switch ($div) {
            case 'head':
                $docentes = Importacion::select(
                    DB::raw('sum(
                        case nroced
                            when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                            when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                            when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                            when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                            when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                            when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                            when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                            when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                            when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                            when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                            when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                            when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                            else 0
                        end
                                ) as docentes'),
                    DB::raw('sum(
                            case nroced
                                when "1A"  then IF(cuadro="C305" and tipdato in("01","05"),d01+d02+d03+d04,0)
                                when "3AP" then IF(cuadro="C305" and tipdato in("01","05"),d01+d02+d03+d04,0)
                                when "3AS" then IF(cuadro="C305" and tipdato in("01","05"),d01+d02+d03+d04+d09+d10+d11+d12+d13+d14+d15+d16+d17,0)
                                else 0
                            end
                                ) as directores'),
                    DB::raw('sum(
                            case nroced
                                when "1A"  then IF(cuadro="C305" and tipdato in("01","05"),d05+d06,0)
                                when "3AP" then IF(cuadro="C305" and tipdato in("01","05"),d05+d06,0)
                                when "3AS" then IF(cuadro="C305" and tipdato in("01","05"),d05+d06,0)
                                else 0
                            end
                                ) as subdirectores'),
                    DB::raw('sum(
                            case nroced
                                when "1A"  then IF(cuadro="C305" and tipdato in("01","05"),d12,0)
                                when "3AP" then IF(cuadro="C305" and tipdato in("01","05"),d16,0)
                                when "3AS" then IF(cuadro="C305" and tipdato in("01","05"),d27,0)
                                else 0
                            end
                                ) as auxiliares'),

                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '2A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C302', 'C303', 'C305'])->whereIn('v1.tipdato', ['01', '02', '05', '06'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                return $docentes = $docentes->first();
                //return compact('docentes');
            case 'anal1':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', ImporCensoDocenteController::$FUENTE)->where('estado', 'PR')->orderBy('anio')->get();

                $docentes = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01","02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '2A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C302', 'C303'])->whereIn('v1.tipdato', ['01', '02', '06']);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $docentes = $docentes->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $docentes = $docentes->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $docentes = $docentes->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $docentes->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                return compact('anios', 'docentes');
            case 'anal2':
                $query = Importacion::select(
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("01"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as dh'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06,0)
                                                when "8AI" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(cuadro="C303" and tipdato in("02"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as dm'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    // ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C303'])->whereIn('v1.tipdato', ['01', '02'])
                    ->whereIn('v1.nroced', ['1A', '2A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C302', 'C303'])->whereIn('v1.tipdato', ['01', '02', '06'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $query = $query->first();

                return $query;
            case 'anal3':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', ImporCensoDocenteController::$FUENTE)->where('estado', 'PR')->orderBy('anio')->get();
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as dn'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as dc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C304'])->whereIn('v1.tipdato', ['01', '02']);
                //->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                return compact('anios', 'docentes');

            case 'anal4':
                $query = Importacion::select(
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as dn'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as dc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C304'])->whereIn('v1.tipdato', ['01', '02'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $query->first();

                return $docentes;


            case 'anal5':
                $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', ImporCensoDocenteController::$FUENTE)->where('estado', 'PR')->orderBy('anio')->get();
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as pub'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as pri'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C304'])->whereIn('v1.tipdato', ['01', '02']);
                //->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                return compact('anios', 'docentes');

            case 'anal6':
                $query = Importacion::select(
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced
                                                    when "1A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as pub'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced
                                                    when "1A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8AI" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as pri'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C304'])->whereIn('v1.tipdato', ['01', '02'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                // $docentes = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();
                $docentes = $query->first();

                return $docentes;
            case 'tabla1':
                $query = Importacion::select(
                    'codooii',
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            else 0
                        end
                                ) as tt'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as tpubn'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as tpubc'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as tprin'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as tpric'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as turbn'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as turbc'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as trurn'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as trurc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C304'])->whereIn('v1.tipdato', ['01', '02'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $query->groupBy('codooii')->get();

                $ugel = Ugel::all();

                foreach ($docentes as $dd) {
                    foreach ($ugel as $uu) {
                        if ($uu->codigo == $dd->codooii)
                            $dd->ugel = $uu->nombre;
                    }
                }

                return $docentes;

            case 'tabla2':
                $query = Importacion::select(
                    'niv_mod as codigo',
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                when "4AI" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                when "4AA" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13
                                                when "8AI" then d01+d02+d03+d04+d05+d06+d07+d08
                                                when "8AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                when "5A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32
                                                when "6A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24
                                                when "7A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32
                                                when "9A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                when "4AI" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                when "4AA" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13
                                                when "8AI" then d01+d02+d03+d04+d05+d06+d07+d08
                                                when "8AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                when "5A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32
                                                when "6A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24
                                                when "7A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32
                                                when "9A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                                                when "3AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                                                when "3AS" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                when "4AI" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                when "4AA" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13
                                                when "8AI" then d01+d02+d03+d04+d05+d06+d07+d08
                                                when "8AP" then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14
                                                when "5A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32
                                                when "6A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24
                                                when "7A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32
                                                when "9A"  then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                                                else 0
                                            end
                            else 0
                        end
                                ) as tt'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                when "8AI" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "5A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "9A"  then IF(tipdato="01" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as tpubn'),
                    DB::raw('sum(
                        case year(par_importacion.fechaActualizacion)
                            when 2018 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2019 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2020 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2021 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2022 then case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2023 then  case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            when 2024 then  case nroced
                                                when "1A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                when "3AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                when "3AS" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                when "4AA" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                when "8AI" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "8AP" then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                when "5A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "6A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                when "7A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                when "9A"  then IF(tipdato="02" and ges_dep in("A1","A2","A3","A4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                else 0
                                            end
                            else 0
                        end
                                ) as tpubc'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as tprin'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and ges_dep in("B2","B3","B4"),d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as tpric'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                                        
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as turbn'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and area_censo=1,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as turbc'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="01" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as trurn'),
                    DB::raw('sum(
                                    case year(par_importacion.fechaActualizacion)
                                        when 2018 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2019 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2020 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2021 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2022 then case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2023 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        when 2024 then  case nroced
                                                            when "1A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                            when "3AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                            when "3AS" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            when "4AI" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                            when "4AA" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13,0)
                                                            when "8AI" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "8AP" then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                            when "5A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "6A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                            when "7A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                            when "9A"  then IF(tipdato="02" and area_censo=2,d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                            else 0
                                                        end
                                        else 0
                                    end
                                            ) as trurc'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '8AI', '8AP', '5A', '6A', '7A', '9A'])->whereIn('v1.cuadro', ['C304'])->whereIn('v1.tipdato', ['01', '02'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $query->groupBy('codigo')->get();

                $nivel = NivelModalidad::all();

                foreach ($docentes as $dd) {
                    foreach ($nivel as $nn) {
                        if ($nn->codigo == $dd->codigo) {
                            $dd->nivel = $nn->nombre;
                            $dd->modalidad = $nn->tipo;
                        }
                    }
                }

                return $docentes;

            case 'tabla3':
                $query = Importacion::select(
                    'cod_mod as modular',
                    DB::raw('max(codgeo)     as coddistrito'),
                    DB::raw('max(niv_mod)    as codnivel'),
                    DB::raw('max(ges_dep)    as codgestion'),
                    DB::raw('max(area_censo) as idarea'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced 
                                                    when "1A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as tt'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as tth'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced
                                                    when "1A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C303" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as ttm'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="01",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as ttn'),
                    DB::raw('sum(
                            case year(par_importacion.fechaActualizacion)
                                when 2018 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2019 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2020 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2021 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2022 then case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2023 then  case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                when 2024 then  case nroced
                                                    when "1A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11,0)
                                                    when "3AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15,0)
                                                    when "3AS" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26,0)
                                                    when "4AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "4AA" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14,0)
                                                    when "5A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "6A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    when "7A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26+d27+d28+d29+d30+d31+d32,0)
                                                    when "8A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06,0)
                                                    when "8AI" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "8AP" then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08,0)
                                                    when "9A"  then IF(cuadro="C304" and tipdato="02",d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24,0)
                                                    else 0
                                                end
                                else 0
                            end
                                    ) as ttc'),

                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A', '3AP', '3AS', '4AI', '4AA', '5A', '6A', '7A', '8A', '8AI', '8AP', '9A'])->whereIn('v1.cuadro', ['C303', 'C304'])->whereIn('v1.tipdato', ['01', '02'])
                    ->where('par_importacion.id', $anio);
                if ($provincia > 0) {
                    $prov = Ubigeo::find($provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($distrito > 0) {
                    $dist = Ubigeo::find($distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $gestionx = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    } else {
                        $gestionx = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestionx);
                    }
                }
                $docentes = $query->groupBy('modular')->get();

                $area = Area::pluck('nombre', 'id'); //Area::all();
                $nivel_nombre = NivelModalidad::where('id', '!=', '15')->pluck('nombre', 'codigo'); //NivelModalidad::all();
                $nivel_modalidad = NivelModalidad::where('id', '!=', '15')->pluck('tipo', 'codigo');
                $ubigeo = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'codigo'); //Ubigeo::where(DB::raw('length(codigo)'), 6)->get();
                $iiee = InstitucionEducativa::select('codModular as modular', 'nombreInstEduc as nombre')->pluck('nombre', 'modular'); //InstitucionEducativa::select('codModular as modular', 'nombreInstEduc as nombre')->get();

                foreach ($docentes as $dd) {
                    // foreach ($area as $aa) {
                    //     if ($aa->id == $dd->idarea)
                    //         $dd->area = $aa->nombre;
                    // }
                    $dd->area = $area[$dd->idarea] ?? 'ERROR';

                    if ($dd->codgestion == "A1" || $dd->codgestion == "A2" || $dd->codgestion == "A3" || $dd->codgestion == "A4") {
                        $dd->gestion = "Pública";
                    } else {
                        $dd->gestion = "Privada";
                    }

                    // foreach ($nivel as $nn) {
                    //     if ($nn->codigo == $dd->codnivel) {
                    //         $dd->nivel = $nn->nombre;
                    //         $dd->modalidad = $nn->tipo;
                    //         break;
                    //     }
                    // }
                    $dd->nivel = $nivel_nombre[$dd->codnivel] ?? 'Error';
                    $dd->modalidad = $nivel_modalidad[$dd->codnivel] ?? 'Error';

                    // foreach ($ubigeo as $uu) {
                    //     if ($uu->codigo == $dd->coddistrito) {
                    //         $dd->distrito = $uu->nombre;
                    //         break;
                    //     }
                    // }
                    $dd->distrito = $ubigeo[$dd->coddistrito] ?? 'Error';

                    // foreach ($iiee as $ie) {
                    //     if ($ie->modular == $dd->modular) {
                    //         $dd->iiee = $ie->nombre;
                    //         break;
                    //     }
                    // }
                    $dd->iiee =  $iiee[$dd->modular] ?? 'Error';
                }

                return $docentes;

            default:
                return response()->json([]);
        }
    }

    //=>DOCENTES TITULADOS EBR SECUNDARIA
    public static function PersonaDocenteTitulado3AS($importacion, $provincia, $distrito, $gestion, $area)
    {
        //get valor denominador
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw(
                'sum(
                        case 
                            when year(par_importacion.fechaActualizacion) in(2018, 2019)                   then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25
                            when year(par_importacion.fechaActualizacion) in(2020, 2021, 2022, 2023, 2024) then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15+d16+d17+d18+d19+d20+d21+d22+d23+d24+d25+d26
                            else 0
                        end
                                ) as v'
            )
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $importacion);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('v1.codgeo', $dist->codigo);
        }
        if ($gestion > 0) {
            if ($gestion == 3) {
                $gestionx = ['B3', 'B4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            } else {
                $gestionx = ['A1', 'A2', 'A3', 'A4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            }
        }
        if ($area > 0) {
            $areax = Area::find($area);
            $query = $query->where('v1.area_censo', $areax->codigo);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
        $den = $query ? $query->v : 0;

        //get valor numerador
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then v1.d01+v1.d02+v1.d03+v1.d04
                                end) as v')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])->where('par_importacion.id', $importacion);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('v1.codgeo', $dist->codigo);
        }
        if ($gestion > 0) {
            if ($gestion == 3) {
                $gestionx = ['B3', 'B4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            } else {
                $gestionx = ['A1', 'A2', 'A3', 'A4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            }
        }
        if ($area > 0) {
            $areax = Area::find($area);
            $query = $query->where('v1.area_censo', $areax->codigo);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
        $num = $query ? $query->v : 0;

        $imp = Importacion::find($importacion);
        return ['avance' => $den > 0 ? round(100 * $num / $den, 1) : 0, 'fecha' => date('d/m/Y', strtotime($imp->fechaActualizacion)), 'num' => $num, 'den' => $den, 'brecha' => $den - $num];
    }

    //=>DOCENTES TITULADOS EBR PRIMARIA
    public static function PersonaDocenteTitulado3AP($importacion, $provincia, $distrito, $gestion, $area)
    {
        //get valor denominador
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw(
                'sum(
                        case 
                            when year(par_importacion.fechaActualizacion) in(2018, 2019)                   then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13
                            when year(par_importacion.fechaActualizacion) in(2020, 2021, 2022, 2023, 2024) then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13+d14+d15
                            else 0
                        end
                                ) as v'
            )
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $importacion);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('v1.codgeo', $dist->codigo);
        }
        if ($gestion > 0) {
            if ($gestion == 3) {
                $gestionx = ['B3', 'B4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            } else {
                $gestionx = ['A1', 'A2', 'A3', 'A4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            }
        }
        if ($area > 0) {
            $areax = Area::find($area);
            $query = $query->where('v1.area_censo', $areax->codigo);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
        $den = $query ? $query->v : 0;

        //get valor numerador
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then v1.d01+v1.d02+v1.d03+v1.d04
                                end) as v')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $importacion);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('v1.codgeo', $dist->codigo);
        }
        if ($gestion > 0) {
            if ($gestion == 3) {
                $gestionx = ['B3', 'B4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            } else {
                $gestionx = ['A1', 'A2', 'A3', 'A4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            }
        }
        if ($area > 0) {
            $areax = Area::find($area);
            $query = $query->where('v1.area_censo', $areax->codigo);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
        $num = $query ? $query->v : 0;

        $imp = Importacion::find($importacion);
        return ['avance' => $den > 0 ? round(100 * $num / $den, 1) : 0, 'fecha' => date('d/m/Y', strtotime($imp->fechaActualizacion)), 'num' => $num, 'den' => $den, 'brecha' => $den - $num];
    }

    //=>DOCENTES TITULADOS EBR INCIAL ESCOLARIZADO
    public static function PersonaDocenteTitulado1A($importacion, $provincia, $distrito, $gestion, $area)
    {
        //get valor denominador
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw(
                'sum(
                        case 
                            when year(par_importacion.fechaActualizacion) in(2018, 2019)                   then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11+d12+d13
                            when year(par_importacion.fechaActualizacion) in(2020, 2021, 2022, 2023, 2024) then d01+d02+d03+d04+d05+d06+d07+d08+d09+d10+d11
                            else 0
                        end
                                ) as v'
            )
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $importacion);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('v1.codgeo', $dist->codigo);
        }
        if ($gestion > 0) {
            if ($gestion == 3) {
                $gestionx = ['B3', 'B4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            } else {
                $gestionx = ['A1', 'A2', 'A3', 'A4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            }
        }
        if ($area > 0) {
            $areax = Area::find($area);
            $query = $query->where('v1.area_censo', $areax->codigo);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
        $den = $query ? $query->v : 0;

        //get valor numerador
        $query = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then v1.d01+v1.d02+v1.d03+v1.d04
                                end) as v')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])->where('par_importacion.id', $importacion);
        if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($distrito > 0) {
            $dist = Ubigeo::find($distrito);
            $query = $query->where('v1.codgeo', $dist->codigo);
        }
        if ($gestion > 0) {
            if ($gestion == 3) {
                $gestionx = ['B3', 'B4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            } else {
                $gestionx = ['A1', 'A2', 'A3', 'A4'];
                $query = $query->whereIn('v1.ges_dep', $gestionx);
            }
        }
        if ($area > 0) {
            $areax = Area::find($area);
            $query = $query->where('v1.area_censo', $areax->codigo);
        }
        $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
        $num = $query ? $query->v : 0;
        $imp = Importacion::find($importacion);
        return ['avance' => $den > 0 ? round(100 * $num / $den, 1) : 0, 'fecha' => date('d/m/Y', strtotime($imp->fechaActualizacion)), 'num' => $num, 'den' => $den, 'brecha' => $den - $num];
    }
}
