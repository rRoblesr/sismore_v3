<?php

namespace App\Repositories\Educacion;

use App\Http\Controllers\Educacion\ImporServiciosBasicosController;
use App\Models\Educacion\ImporServiciosBasicos;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaGeneralDetalle;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class ServiciosBasicosRepositorio
{
    public static function anios()
    {
        $query = Importacion::distinct()->select('year(fechaActualizacion) as anios')
            ->where('estado', 'PR')
            ->where('fuenteImportacion_id', ImporServiciosBasicosController::$FUENTE)
            ->get();
        return $query;
    }

    public static function aniosModalidad($tipo)
    {
        $query = Importacion::distinct()->select('aa.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('edu_matricula_general_detalle as mgd', 'mgd.matriculageneral_id', '=', 'mg.id')
            ->join('par_anio as aa', 'aa.id', '=', 'mg.anio_id')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'mgd.institucioneducativa_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('par_importacion.estado', 'PR')->where('nm.tipo', $tipo)
            ->orderBy('anio', 'asc')->get();
        return $query;
    }

    public static function anioMax()
    {
        $query = Importacion::distinct()->select('aa.anio')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as aa', 'aa.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')
            ->orderBy('anio', 'desc')->first()->anio;
        return $query;
    }

    public static function anioId()
    {
        $query = Importacion::distinct()->select('aa.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as aa', 'aa.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')
            ->orderBy('anio', 'desc')->first()->id;
        return $query;
    }

    public static function ugels()
    {
        $mg = Importacion::select('mg.*')->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->distinct()->select('uu.id', 'uu.nombre', 'uu.dependencia');
        return $query->get();
    }

    public static function areas()
    {
        $mg = Importacion::select('mg.*')->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->distinct()->select('aa.id', 'aa.nombre');
        return $query->get();
    }

    public static function distritosEBE($provincia)
    {
        $mg = Importacion::select('mg.*')->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            //->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBE');
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        $query = $query->distinct()->select('dt.id', 'dt.nombre');
        return $query->get();
    }

    public static function principalTabla($div, $anio, $ugel, $gestion, $area, $servicio)
    {
        switch ($div) {
            case 'head1':
                return 0;

            case 'head2':
                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%');
                // ->join(DB::raw('(select codLocal,max(Ugel_id) as Ugel_id from edu_institucioneducativa group by codLocal) as ie'), 'ie.codLocal', '=', 'edu_impor_serviciosbasicos.codlocal')
                // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id');
                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($gestion > 0) {
                    if ($gestion == 3) $query = $query->where('pub_priv', 'Privada');
                    else $query = $query->where('pub_priv', 'Pública');
                }
                if ($area > 0) $query = $query->where('area_censo', $area);
                $query = $query->count();
                return $query;

            case 'head3':
                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%');
                if ($servicio == 1)
                    $query = $query->where('agua_final', '>', 0);
                else if ($servicio == 2)
                    $query = $query->where('desague_final', '>', 0);
                else if ($servicio == 3)
                    $query = $query->where('luz_final', '>', 0);
                else if ($servicio == 4)
                    $query = $query->where('tres_servicios_final', '>', 0);
                else if ($servicio == 5)
                    $query = $query->where('internet_final', '>', 0);

                if ($gestion > 0) {
                    if ($gestion == 3) $query = $query->where('pub_priv', 'Privada');
                    else $query = $query->where('pub_priv', 'Pública');
                }
                if ($area > 0) $query = $query->where('area_censo', $area);

                $query = $query->count();
                return $query;

            case 'head4':
                return 0;

            case 'anal1':
                //$total = ImporServiciosBasicos::select('id')->count();
                if ($servicio == 1) $tipo = 'agua_final';
                else if ($servicio == 2) $tipo = 'desague_final';
                else if ($servicio == 3) $tipo = 'luz_final';
                else if ($servicio == 4) $tipo = 'tres_servicios_final';
                else if ($servicio == 5) $tipo = 'internet_final';

                $query = ImporServiciosBasicos::select(
                    'dpto as name',
                    //DB::raw("(100*count(id)/$total) as y"),
                    DB::raw("(100*sum(IF($tipo=1,1,0))/count(id)) as y"),
                );

                if ($gestion > 0) {
                    if ($gestion == 3) $query = $query->where('pub_priv', 'Privada');
                    else $query = $query->where('pub_priv', 'Pública');
                }
                if ($area > 0) $query = $query->where('area_censo', $area);

                $query = $query->groupBy('name')->orderBy('y', 'desc')->get();

                foreach ($query as $key => $value) {
                    $value->y = round($value->y, 1);
                    $value->color = $value->name == 'UCAYALI' ? '#ef5350' : '#317eeb';
                }
                return $query;

            case 'tabla1':
                $total = ImporServiciosBasicos::select('id')->where('codgeo', 'like', '25%')->count();

                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%');
                if ($servicio == 1)
                    $query = $query->select(
                        'dist',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(agua_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(agua_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador")
                    );
                else if ($servicio == 2)
                    $query = $query->select(
                        'dist',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(desague_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(desague_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador")
                    );
                else if ($servicio == 3)
                    $query = $query->select(
                        'dist',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(luz_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(luz_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador")
                    );
                else if ($servicio == 4)
                    $query = $query->select(
                        'dist',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(tres_servicios_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(tres_servicios_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador")
                    );
                else if ($servicio == 5)
                    $query = $query->select(
                        'dist',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(internet_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(internet_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador")
                    );

                if ($gestion > 0) {
                    if ($gestion == 3) $query = $query->where('pub_priv', 'Privada');
                    else $query = $query->where('pub_priv', 'Pública');
                }
                if ($area > 0) $query = $query->where('area_censo', $area);

                $query = $query->groupBy('dist')->orderBy('indicador', 'desc')->get();

                return $query;

            case 'tabla2':
                $total = ImporServiciosBasicos::select('id')->where('codgeo', 'like', '25%')->count();

                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%');

                if ($servicio == 1)
                    $query = $query->select(
                        'prov',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(agua_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(agua_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and agua_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and agua_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and agua_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and agua_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and agua_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and agua_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 2)
                    $query = $query->select(
                        'prov',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(desague_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(desague_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and desague_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and desague_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and desague_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and desague_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and desague_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and desague_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 3)
                    $query = $query->select(
                        'prov',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(luz_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(luz_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and luz_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and luz_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and luz_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and luz_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and luz_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and luz_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 4)
                    $query = $query->select(
                        'prov',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(tres_servicios_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(tres_servicios_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and tres_servicios_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and tres_servicios_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and tres_servicios_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and tres_servicios_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and tres_servicios_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and tres_servicios_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 5)
                    $query = $query->select(
                        'prov',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(internet_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(internet_final=1,1,0)) as sin"),
                        DB::raw("(100*count(id)/$total) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and internet_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and internet_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and internet_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and internet_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and internet_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and internet_final=1,1,0)) as EBAsin"),
                    );

                if ($gestion > 0) {
                    if ($gestion == 3) $query = $query->where('pub_priv', 'Privada');
                    else $query = $query->where('pub_priv', 'Pública');
                }
                if ($area > 0) $query = $query->where('area_censo', $area);

                $query = $query->groupBy('prov')->orderBy('indicador', 'desc')->get();

                return $query;

            case 'tabla3':
                $total = ImporServiciosBasicos::select('id')->where('codgeo', 'like', '25%')->count();

                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')
                    ->join('edu_institucioneducativa as ie', 'ie.codLocal', '=', 'edu_impor_serviciosbasicos.codlocal')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                $query = $query->select(
                    'edu_impor_serviciosbasicos.codlocal as local',
                    'ie.codModular as modular',
                    'ie.nombreInstEduc as iiee',
                    'dt.nombre as distrito',
                    'nm.tipo as modalidad',
                    'nm.nombre as nivel',
                    'nm.tipo as modalidad',
                    'tg.dependencia as gestion',
                    'aa.nombre as area',
                    DB::raw("IF(agua_final=1,'SI','NO') as agua"),
                    DB::raw("IF(desague_final=1,'SI','NO') as desague"),
                    DB::raw("IF(luz_final=1,'SI','NO') as luz"),
                    DB::raw("IF(tres_servicios_final=1,'SI','NO') as servicios"),
                    DB::raw("IF(internet_final=1,'SI','NO') as internet"),

                );

                if ($gestion > 0) {
                    if ($gestion == 3) $query = $query->where('pub_priv', 'Privada');
                    else $query = $query->where('pub_priv', 'Pública');
                }
                if ($area > 0) $query = $query->where('area_censo', $area);

                $query = $query->get();

                return $query;

            default:
                return [];
        }
    }

    public static function indicador($anio, $provincia, $distrito, $gestion, $area, $servicio)
    {
        $imp = ImportacionRepositorio::aniosMax_porfuente(ImporServiciosBasicosController::$FUENTE);
        $total = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $imp->id);
        if ($provincia > 0) {
            $uu1 = Ubigeo::find($provincia);
            $total = $total->where('provincia', $uu1->nombre);
        }
        if ($distrito > 0) {
            $uu2 = Ubigeo::find($distrito);
            $total = $total->where('distrito', $uu2->nombre);
        }
        if ($gestion > 0) {
            $total = $gestion == 3 ? $total->where('cod_gest', 3) : $total->where('cod_gest', '!=', 3);
        }
        if ($area > 0) $total = $total->where('cod_area', $area);
        $total = $total->count();

        $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $imp->id);
        if ($servicio == 1)
            $query = $query->where('agua_final', '>', 0);
        else if ($servicio == 2)
            $query = $query->where('desague_final', '>', 0);
        else if ($servicio == 3)
            $query = $query->where('luz_final', '>', 0);
        else if ($servicio == 4)
            $query = $query->where('tres_servicios_final', '>', 0);
        else if ($servicio == 5)
            $query = $query->where('internet_final', '>', 0);

        if ($provincia > 0) {
            $uu1 = Ubigeo::find($provincia);
            $query = $query->where('provincia', $uu1->nombre);
        }
        if ($distrito > 0) {
            $uu2 = Ubigeo::find($distrito);
            $query = $query->where('distrito', $uu2->nombre);
        }
        if ($gestion > 0) {
            $total = $gestion == 3 ? $total->where('cod_gest', 3) : $total->where('cod_gest', '!=', 3);
        }
        if ($area > 0) $query = $query->where('cod_area', $area);

        $query = $query->count();

        return round(100 * ($total > 0 ? $query / $total : 0), 1);
    }

    public static function aguapotableTabla($div, $anio, $provincia, $distrito, $area, $servicio)
    {
        switch ($div) {
            case 'head1':
                return 0;

            case 'head2':
                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $anio);
                // ->join(DB::raw('(select codLocal,max(Ugel_id) as Ugel_id from edu_institucioneducativa group by codLocal) as ie'), 'ie.codLocal', '=', 'edu_impor_serviciosbasicos.codlocal')
                // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id');
                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($provincia > 0) {
                    $pp = Ubigeo::find($provincia);
                    $query = $query->where('provincia', $pp->nombre);
                }
                if ($distrito > 0) {
                    $dd = Ubigeo::find($distrito);
                    $query = $query->where('distrito', $dd->nombre);
                }
                if ($area > 0) $query = $query->where('cod_area', $area);
                $query = $query->count();
                return $query;

            case 'head3':
                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $anio);
                if ($servicio == 1)
                    $query = $query->where('agua_final', '>', 0);
                else if ($servicio == 2)
                    $query = $query->where('desague_final', '>', 0);
                else if ($servicio == 3)
                    $query = $query->where('luz_final', '>', 0);
                else if ($servicio == 4)
                    $query = $query->where('tres_servicios_final', '>', 0);
                else if ($servicio == 5)
                    $query = $query->where('internet_final', '>', 0);

                if ($provincia > 0) {
                    $pp = Ubigeo::find($provincia);
                    $query = $query->where('provincia', $pp->nombre);
                }
                if ($distrito > 0) {
                    $dd = Ubigeo::find($distrito);
                    $query = $query->where('distrito', $dd->nombre);
                }
                if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->count();
                return $query;

            case 'head4':
                return 0;

            case 'anal1':
                //$total = ImporServiciosBasicos::select('id')->count();
                if ($servicio == 1) $tipo = 'agua_final';
                else if ($servicio == 2) $tipo = 'desague_final';
                else if ($servicio == 3) $tipo = 'luz_final';
                else if ($servicio == 4) $tipo = 'tres_servicios_final';
                else if ($servicio == 5) $tipo = 'internet_final';

                $query = ImporServiciosBasicos::select(
                    DB::raw('year(fechaActualizacion) as anio'),
                    // 'ugel as ugel',
                    //DB::raw("(100*count(id)/$total) as y"),
                    // DB::raw("(100*sum(IF($tipo=1,1,0))/count(id)) as y"),
                    DB::raw("sum(IF($tipo=1,1,0)) as y"),
                    DB::raw("count(*) as x"),
                    DB::raw("round(100*sum(IF($tipo=1,1,0))/count(*),1) as z"),
                )->join('par_importacion as imp', 'imp.id', '=', 'importacion_id');
                if ($provincia > 0) {
                    $pp = Ubigeo::find($provincia);
                    $query = $query->where('provincia', $pp->nombre);
                }
                if ($distrito > 0) {
                    $dd = Ubigeo::find($distrito);
                    $query = $query->where('distrito', $dd->nombre);
                }
                if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->groupBy('anio')->orderBy('anio')->get();

                // foreach ($query as $key => $value) {
                //     $value->y = round($value->y, 0);
                //     $value->color = '#317eeb'; // $value->name == 'UCAYALI' ? '#ef5350' : '#317eeb';
                // }
                return $query;

            case 'anal2':
                //$total = ImporServiciosBasicos::select('id')->count();
                if ($servicio == 1) $tipo = 'agua_final';
                else if ($servicio == 2) $tipo = 'desague_final';
                else if ($servicio == 3) $tipo = 'luz_final';
                else if ($servicio == 4) $tipo = 'tres_servicios_final';
                else if ($servicio == 5) $tipo = 'internet_final';

                $query = ImporServiciosBasicos::select(
                    'ugel',
                    //DB::raw("(100*count(id)/$total) as y"),
                    // DB::raw("(100*sum(IF($tipo=1,1,0))/count(id)) as y"),
                    DB::raw("sum(IF($tipo=1,1,0)) as y"),
                    DB::raw("count(id) as x"),
                )->where('importacion_id', $anio);
                if ($provincia > 0) {
                    $pp = Ubigeo::find($provincia);
                    $query = $query->where('provincia', $pp->nombre);
                }
                if ($distrito > 0) {
                    $dd = Ubigeo::find($distrito);
                    $query = $query->where('distrito', $dd->nombre);
                }
                if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->groupBy('ugel')->orderBy('y', 'desc')->get();

                // foreach ($query as $key => $value) {
                //     $value->y = round($value->y, 0);
                //     $value->color = '#317eeb'; // $value->name == 'UCAYALI' ? '#ef5350' : '#317eeb';
                // }
                return $query;

            case 'anal3':
                //$total = ImporServiciosBasicos::select('id')->count();
                if ($servicio == 1) $tipo = 'agua_final';
                else if ($servicio == 2) $tipo = 'desague_final';
                else if ($servicio == 3) $tipo = 'luz_final';
                else if ($servicio == 4) $tipo = 'tres_servicios_final';
                else if ($servicio == 5) $tipo = 'internet_final';

                $query = ImporServiciosBasicos::select(
                    'distrito as name',
                    //DB::raw("(100*count(id)/$total) as y"),
                    DB::raw("(100*sum(IF($tipo=1,1,0))/count(id)) as y"),
                    // DB::raw("sum(IF($tipo=1,1,0)) as y"),
                    // DB::raw("round(100*sum(IF($tipo=1,1,0))/count(*),1) as y"),
                    DB::raw("count(*) as x"),
                )->where('importacion_id', $anio);
                // if ($provincia > 0) {
                //     $pp = Ubigeo::find($provincia);
                //     $query = $query->where('provincia', $pp->nombre);
                // }
                // if ($distrito > 0) {
                //     $dd = Ubigeo::find($distrito);
                //     $query = $query->where('distrito', $dd->nombre);
                // }
                // if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->groupBy('name')->orderBy('y', 'desc')->get();

                // foreach ($query as $key => $value) {
                //     $value->y = round($value->y, 0);
                //     // $value->color = '#317eeb'; // $value->name == 'UCAYALI' ? '#ef5350' : '#317eeb';
                // }
                return $query;

            case 'tabla1':
                $total = ImporServiciosBasicos::select('id')->where('codgeo', 'like', '25%')->where('importacion_id', $anio)->count();

                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $anio);
                if ($servicio == 1)
                    $query = $query->select(
                        'distrito',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(agua_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(agua_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(agua_final=1,1,0))/count(id)) as indicador")
                    );
                else if ($servicio == 2)
                    $query = $query->select(
                        'distrito',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(desague_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(desague_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(desague_final=1,1,0))/count(id)) as indicador")
                    );
                else if ($servicio == 3)
                    $query = $query->select(
                        'distrito',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(luz_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(luz_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(luz_final=1,1,0))/count(id)) as indicador")
                    );
                else if ($servicio == 4)
                    $query = $query->select(
                        'distrito',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(tres_servicios_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(tres_servicios_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(tres_servicios_final=1,1,0))/count(id)) as indicador")
                    );
                else if ($servicio == 5)
                    $query = $query->select(
                        'distrito',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(internet_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(internet_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(internet_final=1,1,0))/count(id)) as indicador")
                    );
                // if ($provincia > 0) {
                //     $pp = Ubigeo::find($provincia);
                //     $query = $query->where('provincia', $pp->nombre);
                // }
                // if ($distrito > 0) {
                //     $dd = Ubigeo::find($distrito);
                //     $query = $query->where('distrito', $dd->nombre);
                // }
                // if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->groupBy('distrito')->orderBy('indicador', 'desc')->get();

                return $query;

            case 'tabla2':
                $total = ImporServiciosBasicos::select('id')->where('codgeo', 'like', '25%')->where('importacion_id', $anio)->count();

                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $anio);

                if ($servicio == 1)
                    $query = $query->select(
                        'provincia',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(agua_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(agua_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(agua_final=1,1,0))/count(id)) as indicador"),
                        DB::raw("sum(IF(modalidad='EBR',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='EBR' and agua_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='EBR',1,0))-sum(IF(modalidad='EBR' and agua_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='EBE',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='EBE' and agua_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='EBE',1,0))-sum(IF(modalidad='EBE' and agua_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='EBA',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='EBA' and agua_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='EBA',1,0))-sum(IF(modalidad='EBA' and agua_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 2)
                    $query = $query->select(
                        'provincia',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(desague_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(desague_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(desague_final=1,1,0))/count(id)) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and desague_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and desague_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and desague_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and desague_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and desague_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and desague_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 3)
                    $query = $query->select(
                        'provincia',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(luz_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(luz_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(luz_final=1,1,0))/count(id)) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and luz_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and luz_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and luz_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and luz_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and luz_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and luz_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 4)
                    $query = $query->select(
                        'provincia',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(tres_servicios_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(tres_servicios_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(tres_servicios_final=1,1,0))/count(id)) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and tres_servicios_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and tres_servicios_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and tres_servicios_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and tres_servicios_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and tres_servicios_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and tres_servicios_final=1,1,0)) as EBAsin"),
                    );
                else if ($servicio == 5)
                    $query = $query->select(
                        'provincia',
                        DB::raw("count(id) as total"),
                        DB::raw("sum(IF(internet_final=1,1,0)) as con"),
                        DB::raw("count(id)-sum(IF(internet_final=1,1,0)) as sin"),
                        DB::raw("(100*sum(IF(internet_final=1,1,0))/count(id)) as indicador"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0)) as EBRtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)' and internet_final=1,1,0)) as EBRcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Regular (EBR)',1,0))-sum(IF(modalidad='Educación Básica Regular (EBR)' and internet_final=1,1,0)) as EBRsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0)) as EBEtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)' and internet_final=1,1,0)) as EBEcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Especial (EBE)',1,0))-sum(IF(modalidad='Educación Básica Especial (EBE)' and internet_final=1,1,0)) as EBEsin"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0)) as EBAtotal"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)' and internet_final=1,1,0)) as EBAcon"),
                        DB::raw("sum(IF(modalidad='Educación Básica Alternativa (EBA)',1,0))-sum(IF(modalidad='Educación Básica Alternativa (EBA)' and internet_final=1,1,0)) as EBAsin"),
                    );
                if ($provincia > 0) {
                    $pp = Ubigeo::find($provincia);
                    $query = $query->where('provincia', $pp->nombre);
                }
                if ($distrito > 0) {
                    $dd = Ubigeo::find($distrito);
                    $query = $query->where('distrito', $dd->nombre);
                }
                if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->groupBy('provincia')->orderBy('indicador', 'desc')->get();

                return $query;

            case 'tabla3':
                // $total = ImporServiciosBasicos::select('id')->where('codgeo', 'like', '25%')->where('importacion_id', $anio)->count();

                $query = ImporServiciosBasicos::where('codgeo', 'like', '25%')->where('importacion_id', $anio)
                    ->join('edu_institucioneducativa as ie', 'ie.codLocal', '=', 'edu_impor_serviciosbasicos.codlocal')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                $query = $query->select(
                    'edu_impor_serviciosbasicos.codlocal as local',
                    'ie.codModular as modular',
                    'ie.nombreInstEduc as iiee',
                    'dt.nombre as distrito',
                    'nm.tipo as modalidad',
                    'nm.nombre as nivel',
                    'nm.tipo as modalidad',
                    'tg.dependencia as gestion',
                    'aa.nombre as area',
                    DB::raw("IF(agua_final=1,'SI','NO') as agua"),
                    DB::raw("IF(desague_final=1,'SI','NO') as desague"),
                    DB::raw("IF(luz_final=1,'SI','NO') as luz"),
                    DB::raw("IF(tres_servicios_final=1,'SI','NO') as servicios"),
                    DB::raw("IF(internet_final=1,'SI','NO') as internet"),

                );
                if ($provincia > 0) {
                    $pp = Ubigeo::find($provincia);
                    $query = $query->where('provincia', $pp->nombre);
                }
                if ($distrito > 0) {
                    $dd = Ubigeo::find($distrito);
                    $query = $query->where('distrito', $dd->nombre);
                }
                if ($area > 0) $query = $query->where('cod_area', $area);

                $query = $query->get();

                return $query;

            default:
                return [];
        }
    }
}
