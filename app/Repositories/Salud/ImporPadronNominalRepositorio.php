<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\Ubigeo;
use App\Models\Salud\ImporPadronNominal;
use Illuminate\Support\Facades\DB;

class ImporPadronNominalRepositorio
{
    public static function head_lista_indicadores($div, $indicador, $importacion, $edades)
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
            case 'anal1':
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
                        return $data->select(
                            'distrito_id',
                            DB::raw('round(100*sum(case when codigo_ie != "" or codigo_ie is not null then 1 else 0 end)/count(*),2) as ii'),
                        )->orderBy('ii', 'desc')->groupBy('distrito_id')->get();
                    default:
                        return [];
                }

            case 'anal2':
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
                        $data = $data->whereRaw("codigo_ie != '' and codigo_ie is not null");
                        return $data->select(
                            DB::raw('case genero when"M" then "HOMBRE" when "F" then "MUJER" ELSE "NO ESPECIFICADO" end as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('genero')->orderByRaw("FIELD(genero,'M','F')")->orderBy('genero')->get();

                    default:
                        return [];
                }
            case 'anal3':
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
                        $data = $data->whereRaw("codigo_ie != '' and codigo_ie is not null");
                        return $data->select(
                            DB::raw('area_ccpp as name'),
                            DB::raw("count(*) AS y"),
                        )->groupBy('area_ccpp')->get();
                    default:
                        return [];
                }
            case 'tabla1':
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
}
