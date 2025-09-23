<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\Ubigeo;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\ImporPadronNominal;
use App\Models\Salud\Microrred;
use Illuminate\Support\Facades\DB;

class ImporPadronNominalRepositorio
{
    public static function head_lista_indicadores($div, $indicador, $importacion, $area, $edades, $ubigeo)
    {
        switch ($div) {
            case 'head':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }

                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(tipo_doc = 'DNI', 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(tipo_doc != 'DNI', 1, 0)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(tipo_doc = 'DNI', 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(tipo_doc = 'DNI', 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(tipo_doc != 'DNI', 1, 0)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(tipo_doc = 'DNI', 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });

                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(tipo_doc = 'DNI', 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(tipo_doc != 'DNI', 1, 0)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(tipo_doc = 'DNI', 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        // $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(seguro_id = 1, 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(seguro_id = 1, 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(seguro_id = 1, 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        // $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(!(programa_social = '0,' or programa_social is null), 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(!(programa_social = '0,' or programa_social is null), 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(!(programa_social = '0,' or programa_social is null), 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF((cui_atencion > 0 and cui_atencion is not null), 1, 0)) AS cdni"),
                            DB::raw("SUM(IF((cui_atencion > 0 and cui_atencion is not null), 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF((cui_atencion > 0 and cui_atencion is not null), 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        // $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(visita = 1, 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(visita = 1, 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(visita = 1, 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        // $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF((menor_encontrado = 2), 1, 0)) AS cdni"),
                            DB::raw("SUM(IF((menor_encontrado = 2), 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF((menor_encontrado = 2), 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        // $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF((visita = 1 and menor_encontrado = 2), 1, 0)) AS cdni"),
                            DB::raw("SUM(IF((visita = 1 and menor_encontrado = 2), 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF((visita = 1 and menor_encontrado = 2), 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        // $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('COUNT(*) AS conteo'),
                            DB::raw("SUM(IF(!(codigo_ie = '' or codigo_ie is null), 1, 0)) AS cdni"),
                            DB::raw("SUM(IF(!(codigo_ie = '' or codigo_ie is null), 0, 1)) AS sdni"),
                            DB::raw("ROUND(100 * SUM(IF(!(codigo_ie = '' or codigo_ie is null), 1, 0)) / COUNT(*), 1) AS avance")
                        )->first();
                    default:
                        return [];
                }
            case 'anal01':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when tipo_doc="DNI" then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();

                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when tipo_doc="DNI" then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when tipo_doc="DNI" then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when !(programa_social = "0," or programa_social is null) then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when (cui_atencion > 0 and cui_atencion is not null) then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when menor_encontrado = 2 then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when visita = 1 and menor_encontrado = 2 then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when codigo_ie != "" or codigo_ie is not null then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    default:
                        return [];
                }

            case 'anal02':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("programa_social != '0,' AND programa_social IS NOT NULL");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("cui_atencion > 0 and cui_atencion is not null");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("visita = 1");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("menor_encontrado = 2");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("visita = 1 and menor_encontrado = 2");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("codigo_ie != '' and codigo_ie is not null");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    default:
                        return [];
                }
            case 'anal03':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('seguro_id', '1');
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("programa_social != '0,' AND programa_social IS NOT NULL");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("cui_atencion > 0 and cui_atencion is not null");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("visita = 1");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("menor_encontrado = 2");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("visita = 1 and menor_encontrado = 2");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->whereRaw("codigo_ie != '' and codigo_ie is not null");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    default:
                        return [];
                }
            case 'tabla01':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc="DNI" then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when tipo_doc!="DNI" then 1 else 0 end) as sdni'),
                            DB::raw('round(100*sum(case when tipo_doc="DNI" then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc="DNI" then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when tipo_doc!="DNI" then 1 else 0 end) as sdni'),
                            DB::raw('round(100*sum(case when tipo_doc="DNI" then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc="DNI" then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when tipo_doc!="DNI" then 1 else 0 end) as sdni'),
                            DB::raw('round(100*sum(case when tipo_doc="DNI" then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when seguro_id = 1 then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when !(programa_social = "0," or programa_social is null) then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when !(programa_social = "0," or programa_social is null) then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when !(programa_social = "0," or programa_social is null) then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when (cui_atencion > 0 and cui_atencion is not null) then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when (cui_atencion > 0 and cui_atencion is not null) then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when (cui_atencion > 0 and cui_atencion is not null) then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when visita = 1 then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when menor_encontrado = 2 then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when menor_encontrado = 2 then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when menor_encontrado = 2 then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when visita = 1 and menor_encontrado = 2 then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when visita = 1 and menor_encontrado = 2 then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when visita = 1 and menor_encontrado = 2 then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when codigo_ie != "" or codigo_ie is not null then 1 else 0 end) as cdni'),
                            DB::raw('sum(case when codigo_ie != "" or codigo_ie is not null then 0 else 1 end) as sdni'),
                            DB::raw('round(100*sum(case when codigo_ie != "" or codigo_ie is not null then 1 else 0 end)/count(*),1) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    default:
                        return [];
                }
            case 'tabla02':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'distrito_id',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),1) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),1) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),1) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),1) as ii4'),
                        )->orderBy('ubigeo')->groupBy('distrito_id')->get();
                        $dd = Ubigeo::where(DB::raw('length(codigo)'), 6)->where('codigo', 'like', '25%')->pluck('nombre', 'id');
                        foreach ($data as $key => $value) {
                            $value->distrito = $dd[$value->distrito_id] ?? 'No Especificado';
                        }
                        return $data;
                    default:
                        return [];
                }
            case 'tabla0201':
                switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where('tipo_edad', 'D')->where('edad', '<=', 30);
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->where(function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->where('tipo_edad', 'D')->where('edad', '<=', 31);
                            })
                                ->orWhere(function ($subQuery) {
                                    $subQuery->where('tipo_edad', 'M')->where('edad', '<=', 2);
                                });
                        });
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '4': //Niñas y Niños con Seguro de Salud
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '5': //Niñas y Niños con Programas Sociales
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '8': //Niñas y Niños No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    case '10': //Niñas y Niños con Institución Educativa
                        $data = ImporPadronNominal::where('importacion_id', $importacion)->where('distrito_id', $ubigeo);
                        if ($edades > 0) {
                            if ($edades == 1) {
                                $data = $data->whereIn('tipo_edad', ['D', 'M']);
                            } else {
                                $data = $data->where('tipo_edad', 'A')->where('edad', $edades - 1);
                            }
                        }
                        if ($area > 0) {
                            $data = $data->where('area_ccpp', ($area == 1 ? 'RURAL' : 'URBANA'));
                        }
                        $data = $data->select(
                            'centro_poblado',
                            'centro_poblado_nombre',
                            DB::raw('count(*) as total'),
                            DB::raw('sum(case when tipo_doc = "DNI" then 1 else 0 end) as cdni'),
                            DB::raw('round(100*sum(case when tipo_doc = "DNI" then 1 else 0 end)/count(*),2) as ii1'),

                            DB::raw('sum(case when seguro_id = 1 then 1 else 0 end) as cseguro'),
                            DB::raw('round(100*sum(case when seguro_id = 1 then 1 else 0 end)/count(*),2) as ii2'),

                            DB::raw('sum(case when cui_atencion > 0 then 1 else 0 end) as ceess'),
                            DB::raw('round(100*sum(case when cui_atencion > 0 then 1 else 0 end)/count(*),2) as ii3'),

                            DB::raw('sum(case when visita = 1 then 1 else 0 end) as cvisita'),
                            DB::raw('round(100*sum(case when visita = 1 then 1 else 0 end)/count(*),2) as ii4'),
                        )->orderBy('centro_poblado')->groupBy('centro_poblado', 'centro_poblado_nombre')->get();
                        return $data;
                    default:
                        return [];
                }
            default:
                return [];
        }
    }

    /* 
    switch ($indicador) {
                    case '1': //Niñas y Niños con DNI
                        return [];
                    case '2': //Niñas y Niños con DNI de 0 a 30 días
                        return [];
                    case '3': //Niñas y Niños con DNI menores a 60 días 
                        return [];
                    case '4': //Niñas y Niños con Seguro de Salud
                        return [];
                    case '5': //Niñas y Niños con Programas Sociales
                        return [];
                    case '6': //Niñas y Niños con Establecimientos de Salud de Atención
                        return [];
                    case '7': //Niñas y Niños con Visita Domiciliaria
                        return [];
                    case '8': //Niñas y Niños No Encontrados
                        return [];
                    case '9': //Niñas y Niños Visitados y No Encontrados
                        return [];
                    case '10': //Niñas y Niños con Institución Educativa
                        return [];
                    default:
                        return [];
                }
    */

    public static function PNImportacion_idmax_($fuente, $anio, $mes)
    {
        $sql1 = "SELECT * FROM par_importacion
                WHERE fuenteimportacion_id = ? AND estado = 'PR'
                AND DATE_FORMAT(fechaActualizacion, '%Y-%m') = (
                    SELECT DATE_FORMAT(MAX(fechaActualizacion), '%Y-%m') FROM par_importacion 
                    WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ? AND MONTH(fechaActualizacion) = ?
                )
                ORDER BY fechaActualizacion DESC limit 1";
        $query1 = DB::select($sql1, [$fuente, $fuente, $anio, $mes]);
        return $query1 ? $query1[0]->id : 0;
    }

    public static function PNImportacion_idmax($fuente, $anio, $mes = null)
    {
        if ($mes > 0) {
            $sql = "SELECT id, fechaActualizacion FROM par_importacion
                WHERE fuenteimportacion_id = ? 
                  AND estado = 'PR'
                  AND YEAR(fechaActualizacion) = ? 
                  AND MONTH(fechaActualizacion) = ?
                ORDER BY fechaActualizacion DESC 
                LIMIT 1";

            $query = DB::select($sql, [$fuente, $anio, $mes]);
        } else {
            $sql = "SELECT id, fechaActualizacion FROM par_importacion
                WHERE fuenteimportacion_id = ? 
                  AND estado = 'PR'
                  AND YEAR(fechaActualizacion) = ?
                  AND fechaActualizacion = (
                      SELECT MAX(fechaActualizacion) 
                      FROM par_importacion 
                      WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
                  )
                LIMIT 1";

            $query = DB::select($sql, [$fuente, $anio, $fuente, $anio]);
        }

        return $query ? $query[0]->id : 0;
    }

    public static function PNImportacion_idmax_p1($fuente, $anio, $mes)
    {
        $sql = "SELECT id FROM par_importacion
            WHERE fuenteimportacion_id = ? 
              AND estado = 'PR'
              AND YEAR(fechaActualizacion) = ? 
              AND MONTH(fechaActualizacion) = ?
            ORDER BY fechaActualizacion DESC 
            LIMIT 1";

        $query = DB::select($sql, [$fuente, $anio, $mes]);
        return $query ? $query[0]->id : 0;
    }

    public static function PNImportacion_idmax_p2($fuente, $anio)
    {
        $sql = "SELECT id, fechaActualizacion FROM par_importacion
            WHERE fuenteimportacion_id = ? 
              AND estado = 'PR'
              AND YEAR(fechaActualizacion) = ?
              AND fechaActualizacion = (
                  SELECT MAX(fechaActualizacion) 
                  FROM par_importacion 
                  WHERE fuenteimportacion_id = ? AND estado = 'PR' AND YEAR(fechaActualizacion) = ?
              )
            LIMIT 1";

        $query = DB::select($sql, [$fuente, $anio, $fuente, $anio]);
        return $query ? $query[0]->id : 0;
    }

    public static function Listar_UnDatoSabana($id) {}

    public static function TableroCalidadEESS_head($importacion, $red, $microrred, $eess, $tipo_doc = FALSE, $seguro = FALSE)
    {
        $filtros = function ($query) use ($red, $microrred, $eess, $tipo_doc, $seguro) {
            if ($red > 0) $query->where('m.red_id', $red);
            if ($microrred > 0) $query->where('m.id', $microrred);
            if ($eess > 0) $query->where('e.id', $eess);
            if ($tipo_doc) $query->where('pn.tipo_doc', 'DNI');
            if ($seguro) $query->where('pn.seguro_id', '>', '0');
        };
        return ImporPadronNominal::from('sal_impor_padron_nominal as pn')
            ->join('sal_establecimiento as e', 'e.id', '=', 'pn.establecimiento_id')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->where('pn.importacion_id', $importacion)
            ->where('pn.repetido', 1)
            ->where('m.cod_disa', 34)
            // ->where('e.categoria','=', 'SIN CATEGORÍA') // Excluir EESS de Categoria 6,7,8,9
            // ->whereIn('m.red_id', [9, 10, 11, 12])
            ->tap($filtros)
            ->count();
    }

    public static function TableroCalidadEESS_tabla02($importacion, $red, $microrred)
    {
        $filtros = function ($query) use ($red, $microrred) {
            if ($red > 0) $query->where('m.red_id', $red);
            if ($microrred > 0) $query->where('m.id', $microrred);
        };
        return ImporPadronNominal::from('sal_impor_padron_nominal as pn')
            ->join('sal_establecimiento as e', 'e.id', '=', 'pn.establecimiento_id')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->where('pn.importacion_id', $importacion)
            ->where('pn.repetido', '1')
            ->whereIn('m.red_id', [9, 10, 11, 12])
            ->tap($filtros)
            ->select(
                DB::raw('case when seguro_id=1 then "SIS" when seguro_id=2 then "ESSALUD" when seguro_id=3 then "SANIDAD" when seguro_id=4 then "PRIVADO" else "NINGUNO" end as nseguro'),
                DB::raw('count(*) as pob'),
                DB::raw('sum(if(genero="M",1,0)) as pobm'),
                DB::raw('sum(if(genero="F",1,0)) as pobf'),
                DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                DB::raw('sum(if(tipo_doc="DNI",1,0)) as dni'),
                DB::raw('sum(if(tipo_doc="CNV",1,0)) as seguro'),
                DB::raw('sum(if(tipo_doc="CUI",1,0)) as programa'),
                DB::raw('sum(if(tipo_doc="Padron",1,0)) as padron'),
            )
            ->groupBy('nseguro')
            ->orderByRaw('field(nseguro,"SIS","ESSALUD","SANIDAD","PRIVADO","NINGUNO")')
            ->get();
    }

    public static function TableroCalidadEESS_tabla03($importacion, $red, $microrred, $eess)
    {
        $filtros = function ($query) use ($red, $microrred, $eess) {
            if ($red > 0) $query->where('e.red_id', $red);
            if ($microrred > 0) $query->where('e.microrred_id', $microrred);
            if ($eess > 0) $query->where('e.id', $eess);
        };
        return ImporPadronNominal::from('sal_impor_padron_nominal as pn')
            ->join('par_ubigeo as u', 'u.id', '=', 'pn.distrito_id')
            ->join('sal_establecimiento as e', 'e.id', '=', 'pn.establecimiento_id')
            ->where('pn.importacion_id', $importacion)->where('repetido', '1')
            ->whereIn('e.red_id', [9, 10, 11, 12])
            ->tap($filtros)
            ->select(
                'e.id',
                'e.codigo_unico as codigo',
                'e.nombre_establecimiento as nombre',
                DB::raw('count(*) as pob'),
                DB::raw('sum(if(genero="M",1,0)) as pobm'),
                DB::raw('sum(if(genero="F",1,0)) as pobf'),
                DB::raw('sum(if(tipo_edad in("D","M"),1,0)) as pob0'),
                DB::raw('sum(if(edad=1 and tipo_edad="A",1,0)) as pob1'),
                DB::raw('sum(if(edad=2 and tipo_edad="A",1,0)) as pob2'),
                DB::raw('sum(if(edad=3 and tipo_edad="A",1,0)) as pob3'),
                DB::raw('sum(if(edad=4 and tipo_edad="A",1,0)) as pob4'),
                DB::raw('sum(if(edad=5 and tipo_edad="A",1,0)) as pob5'),
                DB::raw('sum(if(tipo_doc="DNI",1,0)) as dni'),
                DB::raw('sum(case when FIND_IN_SET("1", seguro) > 0 then 1 
                              when FIND_IN_SET("2", seguro) > 0 then 1 
                              when FIND_IN_SET("3", seguro) > 0 then 1 
                              when FIND_IN_SET("4", seguro) > 0 then 1 
                              else 0 end) as seguro'),
                DB::raw('sum(case when FIND_IN_SET("1", programa_social) > 0 then 1 
                              when FIND_IN_SET("2", programa_social) > 0 then 1 
                              when FIND_IN_SET("5", programa_social) > 0 then 1 
                              when FIND_IN_SET("5", programa_social) > 0 then 1 
                              when FIND_IN_SET("7", programa_social) > 0 then 1 
                              when FIND_IN_SET("8", programa_social) > 0 then 1 
                              else 0 end) as programa')
            )
            ->groupBy('e.id', 'e.codigo_unico', 'e.nombre_establecimiento')
            ->orderBy('pob', 'desc')
            ->get();
    }

    public static function TableroCalidadEESS_tabla0301($importacion, $red, $microrred, $eess, $ubigeo)
    {
        $filtros = function ($query) use ($red, $microrred, $eess) {
            if ($red > 0) $query->where('e.red_id', $red);
            if ($microrred > 0) $query->where('e.microrred_id', $microrred);
            // if ($eess > 0) $query->where('e.id', $eess);
        };
        return ImporPadronNominal::from('sal_impor_padron_nominal as pn')
            ->join('sal_establecimiento as e', 'e.id', '=', 'pn.establecimiento_id')
            ->join('par_seguro as s', 's.id', '=', 'pn.seguro_id')
            ->join('par_ubigeo as u', 'u.id', '=', 'pn.distrito_id')
            ->where('pn.importacion_id', $importacion)
            ->where('repetido', '1')
            ->where('e.id', $ubigeo) //->where('ubigeo', $ubigeo)
            ->whereIn('e.red_id', [9, 10, 11, 12])
            ->tap($filtros)
            ->select(
                'pn.id',
                'pn.tipo_doc as tipo',
                'pn.num_doc as documento',
                DB::raw('concat(pn.apellido_paterno," ",pn.apellido_materno," ",pn.nombre) as nombre_completo'),
                'pn.fecha_nacimiento as nacimiento',
                'u.nombre as distrito',
                'pn.centro_poblado_nombre as cpnombre',
                's.codigo as cseguro',
                DB::raw('concat(pn.apellido_paterno_madre," ",pn.apellido_materno_madre," ",pn.nombres_madre) as nombre_completo_madre'),
            )
            ->orderBy('nombre_completo')
            ->get();
    }

    public static function microrred_minsa($importacion, $red)
    {
        $query = Microrred::from('sal_microrred as m')
            ->join('sal_establecimiento as e', 'e.microrred_id', '=', 'm.id')
            ->join('sal_impor_padron_nominal as ipn', 'ipn.establecimiento_id', '=', 'e.id')
            ->where('e.cod_disa', 34)
            ->where('ipn.importacion_id', $importacion)
            ->where('ipn.establecimiento_id', '>', 0);
        if ($red > 0) $query->where('m.red_id', $red);
        return $query->select('m.*')->distinct()->get();
    }

    public static function eess_minsa($importacion, $red, $microrred)
    {
        $filtros = function ($query) use ($red, $microrred) {
            if ($red > 0) $query->where('m.red_id', $red);
            if ($microrred > 0) $query->where('e.microrred_id', $microrred);
        };
        return Establecimiento::from('sal_establecimiento as e')
            ->join('sal_impor_padron_nominal as ipn', 'ipn.establecimiento_id', '=', 'e.id')
            ->join('sal_microrred as m', 'm.id', '=', 'e.microrred_id')
            ->where('e.cod_disa', 34)
            ->where('e.categoria', '<>', 'SIN CATEGORÍA') // Excluir EESS de Categoria 6,7,8,9
            ->where('ipn.importacion_id', $importacion)
            ->tap($filtros)
            ->select('e.id', 'e.codigo_unico as codigo', 'e.nombre_establecimiento as nombre')
            ->groupBy('e.id', 'e.codigo_unico', 'e.nombre_establecimiento')
            ->get();
    }
}
