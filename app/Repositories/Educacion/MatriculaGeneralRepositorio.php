<?php

namespace App\Repositories\Educacion;

use App\Http\Controllers\Educacion\ImporPadronEibController;
use App\Models\Educacion\ImporPadronEib;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaGeneralDetalle;
use Illuminate\Support\Facades\DB;

class MatriculaGeneralRepositorio
{

    public static function count_matriculados2($matricula, $provincia, $distrito, $tipogestion, $ambito)
    {
        $mg = Importacion::select('mg.*')->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id');
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($tipogestion > 0) {
            if ($tipogestion == 3) {
                $query = $query->where('tg.dependencia', 3);
            } else {
                $query = $query->where('tg.dependencia', '!=', 3);
            }
        }
        if ($ambito > 0) $query = $query->where('ie.Area_id', $ambito);
        $query = $query->count();
        return $query;
    }

    public static function anios()
    {
        $query = Importacion::distinct()->select('aa.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as aa', 'aa.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')
            ->orderBy('anio', 'asc')->get();
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

    public static function ugelsEBE()
    {
        $mg = Importacion::select('mg.*')->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBE')
            ->distinct()->select('uu.id', 'uu.nombre', 'uu.dependencia');
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

    public static function dependencia($gestion)
    {
        $mg = Importacion::select('mg.*')->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')->where('estado', 'PR')->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBE');
        if ($gestion > 0) $query = $query->where('tg.dependencia', $gestion);
        $query = $query->distinct()->select('tg.id', 'tg.nombre');
        return $query->get();
    }

    public static function total3a5($anio_id)
    {
        $mg = Importacion::select('mg.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->where('estado', 'PR')->where('anio_id', $anio_id)
            ->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::select(DB::raw('count(matriculageneral_id) as conteo'));
        $query = $query->where('matriculageneral_id', $mg->id)->whereIn('edad', [3, 4, 5]);
        return $query = $query->first()->conteo;
    }

    public static function total6a11($anio_id)
    {
        $mg = Importacion::select('mg.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->where('estado', 'PR')->where('anio_id', $anio_id)
            ->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::select(DB::raw('count(matriculageneral_id) as conteo'));
        $query = $query->where('matriculageneral_id', $mg->id)->whereIn('edad', [6, 7, 8, 9, 10, 11]);
        return $query = $query->first()->conteo;
    }

    public static function total12a16($anio_id)
    {
        $mg = Importacion::select('mg.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->where('estado', 'PR')->where('anio_id', $anio_id)
            ->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::select(DB::raw('count(matriculageneral_id) as conteo'));
        $query = $query->where('matriculageneral_id', $mg->id)->whereIn('edad', [12, 13, 14, 15, 16]);
        return $query = $query->first()->conteo;
    }

    public static function indicador01__head($anio, $provincia, $distrito,  $gestion, $area)
    {
        $mg = Importacion::select('mg.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->where('estado', 'PR')->where('anio_id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();
        $query = MatriculaGeneralDetalle::select(
            DB::raw('count(matriculageneral_id) as basica'),
            DB::raw('sum(IF(modalidad_id=1,1,0)) as ebr'),
            DB::raw('sum(IF(modalidad_id=2,1,0)) as ebe'),
            DB::raw('sum(IF(modalidad_id=3,1,0)) as eba'),
        );
        if ($provincia > 0 || $distrito > 0 || $gestion > 0 || $area > 0)
            $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id');
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($gestion > 0) {
            if ($gestion == 3) {
                $query = $query->where('tg.dependencia', 3);
            } else {
                $query = $query->where('tg.dependencia', '!=', 3);
            }
        }
        $query = $query->where('matriculageneral_id', $mg->id);
        return $query = $query->first();
    }

    public static function indicador01head($anio, $provincia, $distrito,  $gestion, $area)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ubigeos = "";
        if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
        if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $iiees = "SELECT ie.id
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions ";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        if ($provincia > 0 || $distrito > 0 || $gestion > 0 || $area > 0)
            $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(
            DB::raw('count(matriculageneral_id) as basica'),
            DB::raw('sum(IF(modalidad_id=1,1,0)) as ebr'),
            DB::raw('sum(IF(modalidad_id=2,1,0)) as ebe'),
            DB::raw('sum(IF(modalidad_id=3,1,0)) as eba'),
        )->get();
        return $query->first();
    }

    public static function indicador01tabla($div, $anio, $provincia, $distrito,  $gestion, $area = 0, $ugel = 0)
    {
        switch ($div) {
            case 'anal1': //usando panel de control
                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        WHERE imp.estado = 'PR') as tb
                    WHERE rn = 1";

                $ubigeos = "";
                if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
                if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";

                $iiees = "SELECT ie.id
                        from edu_institucioneducativa as ie
                        inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
                        inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
                        inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', DB::raw('count(mg.anio) as suma'))->orderBy('anio', 'asc')->groupBy('anio')->get();

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')
                //     ->orderBy('fechaActualizacion', 'desc')->get();
                // foreach ($mg as $key => $value) {
                //     $vmg[] = $value->id;
                // }
                // $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')->whereIn('matriculageneral_id', $vmg)
                //     ->join('edu_matricula_general as mg', 'mg.id', '=', 'mgd.matriculageneral_id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                // if ($provincia > 0 || $distrito > 0 || $gestion > 0)
                //     $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'mgd.institucioneducativa_id')
                //         ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //         ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //         ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id');

                // if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select('anio', DB::raw('count(anio) as suma'))->orderBy('anio', 'asc')->groupBy('anio')->get();
                return $query;

            case 'anal2':
                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";
                $ubigeos = "";
                if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
                if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $iiees = "SELECT ie.id
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                if ($provincia > 0 || $distrito > 0 || $gestion > 0 || $area > 0)
                    $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(mgd.id) as conteo'));
                $query = $query->orderBy('mes', 'asc')->groupBy('mes')->get();
                return $query;

            case 'anal3':
                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ubigeos = "";
                if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
                if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $iiees = "SELECT ie.id
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join('par_sexo as ss', 'ss.id', '=', 'mgd.sexo_id');;
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                if ($provincia > 0 || $distrito > 0 || $gestion > 0 || $area > 0)
                    $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(DB::raw('ss.nombre as name'), DB::raw('count(mgd.id) as y'))->groupBy('name')->get();
                return $query;

            case 'anal4':
                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ubigeos = "";
                if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
                if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $iiees = "SELECT ie.id, aa.nombre
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                // if ($provincia > 0 || $distrito > 0 || $gestion > 0 || $area > 0)
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(DB::raw('ie.nombre as name'), DB::raw('count(mgd.id) as y'))->groupBy('name')->get();
                return $query;

            case 'tabla1':
                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ubigeos = "";
                if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
                if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $iiees = "SELECT ie.id, uu.id as idugel, uu.nombre as ugel
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(
                    DB::raw('ie.idugel'),
                    DB::raw('ie.ugel'),
                    DB::raw('sum(IF(month(fecha_registro)=1,1,0)) as ene'),
                    DB::raw('sum(IF(month(fecha_registro)=2,1,0)) as feb'),
                    DB::raw('sum(IF(month(fecha_registro)=3,1,0)) as mar'),
                    DB::raw('sum(IF(month(fecha_registro)=4,1,0)) as abr'),
                    DB::raw('sum(IF(month(fecha_registro)=5,1,0)) as may'),
                    DB::raw('sum(IF(month(fecha_registro)=6,1,0)) as jun'),
                    DB::raw('sum(IF(month(fecha_registro)=7,1,0)) as jul'),
                    DB::raw('sum(IF(month(fecha_registro)=8,1,0)) as ago'),
                    DB::raw('sum(IF(month(fecha_registro)=9,1,0)) as sep'),
                    DB::raw('sum(IF(month(fecha_registro)=10,1,0)) as oct'),
                    DB::raw('sum(IF(month(fecha_registro)=11,1,0)) as nov'),
                    DB::raw('sum(IF(month(fecha_registro)=12,1,0)) as dic')
                )->groupBy('idugel', 'ugel')->get();
                return $query;
            case 'tabla2':
                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ubigeos = "";
                if ($provincia > 0) $ubigeos .= " AND dd.dependencia=$provincia ";
                if ($distrito > 0) $ubigeos .= " AND dd.id=$distrito ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $iiees = "SELECT ie.id, nm.tipo, nm.nombre as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(
                    DB::raw('ie.tipo'),
                    DB::raw('ie.nivel'),
                    DB::raw('sum(IF(month(fecha_registro)=1,1,0)) as ene'),
                    DB::raw('sum(IF(month(fecha_registro)=2,1,0)) as feb'),
                    DB::raw('sum(IF(month(fecha_registro)=3,1,0)) as mar'),
                    DB::raw('sum(IF(month(fecha_registro)=4,1,0)) as abr'),
                    DB::raw('sum(IF(month(fecha_registro)=5,1,0)) as may'),
                    DB::raw('sum(IF(month(fecha_registro)=6,1,0)) as jun'),
                    DB::raw('sum(IF(month(fecha_registro)=7,1,0)) as jul'),
                    DB::raw('sum(IF(month(fecha_registro)=8,1,0)) as ago'),
                    DB::raw('sum(IF(month(fecha_registro)=9,1,0)) as sep'),
                    DB::raw('sum(IF(month(fecha_registro)=10,1,0)) as oct'),
                    DB::raw('sum(IF(month(fecha_registro)=11,1,0)) as nov'),
                    DB::raw('sum(IF(month(fecha_registro)=12,1,0)) as dic')
                )->groupBy('tipo', 'nivel')->get();
                return $query;
            default:
                return [];
        }
    }

    public static function educacionbasicasexougel($anio, $provincia, $distrito,  $gestion, $area = 0, $ugel = 0)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($gestion > 0) {
            if ($gestion == 3) {
                $query = $query->where('tg.dependencia', 3);
            } else {
                $query = $query->where('tg.dependencia', '!=', 3);
            }
        }
        if ($area > 0) $query = $query->where('aaf.id', $area);
        $query = $query->select(
            DB::raw('uu.id as idugel'),
            DB::raw('uu.nombre as ugel'),
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
            DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
            DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
            DB::raw('sum(IF(nm.tipo="EBR" and sexo_id=1,1,0)) as EBRth'),
            DB::raw('sum(IF(nm.tipo="EBR" and sexo_id=2,1,0)) as EBRtm'),
            DB::raw('sum(IF(nm.tipo="EBE" and sexo_id=1,1,0)) as EBEth'),
            DB::raw('sum(IF(nm.tipo="EBE" and sexo_id=2,1,0)) as EBEtm'),
            DB::raw('sum(IF(nm.tipo="EBA" and sexo_id=1,1,0)) as EBAth'),
            DB::raw('sum(IF(nm.tipo="EBA" and sexo_id=2,1,0)) as EBAtm'),
        )->groupBy('idugel', 'ugel')->get();
        return $query;
    }

    public static function metaNivel($anio, $provincia, $distrito,  $gestion, $ugel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id');

        if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($gestion > 0) {
            if ($gestion == 3) {
                $query = $query->where('tg.dependencia', 3);
            } else {
                $query = $query->where('tg.dependencia', '!=', 3);
            }
        }
        $query = $query->select(
            DB::raw('nm.tipo'),
            DB::raw('nm.nombre as nivel'),
            DB::raw('count(nm.id) as conteo'),
        )->groupBy('tipo', 'nivel')->get();
        return $query;
    }

    public static function metaUgel($anio, $provincia, $distrito,  $gestion,  $area = 0) //esta a ver que otros formularios usan este metodo
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id');

        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($gestion > 0) {
            if ($gestion == 3) {
                $query = $query->where('tg.dependencia', 3);
            } else {
                $query = $query->where('tg.dependencia', '!=', 3);
            }
        }
        if ($area > 0) $query = $query->where('aa.id', $area);
        $query = $query->select(
            DB::raw('uu.nombre as ugel'),
            DB::raw('count(uu.id) as conteo'),
        )->groupBy('ugel')->get();
        return $query;
    }

    public static function basicaregulartabla($div, $anio, $ugel, $gestion,  $area, $provincia = 0)
    {
        switch ($div) {
            case 'mhead':
                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(
                    DB::raw('count(mgd.matriculageneral_id) as conteo'),
                    DB::raw('sum(IF(ie.codigo in ("A2","A3","A5"),1,0)) as conteoi'),
                    DB::raw('sum(IF(ie.codigo="B0",1,0)) as conteop'),
                    DB::raw('sum(IF(ie.codigo="F0",1,0)) as conteos')
                );
                return $query = $query->first();
            case 'head1':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(matriculageneral_id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');
                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR');
                return $query = $query->first();
            case 'head2':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');
                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR')->where('es_eib', 'SI');
                return $query = $query->first();
            case 'head3':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');
                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR')->where('pais_nacimiento', '!=', 'PERÚ');
                return $query = $query->first();
            case 'head4':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');
                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR')->where('discapacidad_id', '>', 0);
                return $query = $query->first();
            case 'anal1':/*  */

                // $mat_uni_max = "SELECT * FROM (SELECT * FROM (
                //         SELECT id, anio FROM(
                //             SELECT mg.id, aa.anio, ROW_NUMBER() OVER (PARTITION BY aa.anio ORDER BY mg.id DESC) AS rn
                //             FROM edu_matricula_general as mg
                //             INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id AND imp.estado = 'PR'
                //             INNER JOIN par_anio AS aa ON aa.id = mg.anio_id
                //             ORDER BY aa.anio DESC ) as tb1
                //         WHERE rn = 1 LIMIT 6 ) as tb2 
                //     ORDER BY anio desc) as tb3";

                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id AND imp.estado = 'PR'
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        ) as tb1
                    WHERE rn = 1 ";

                $ugels = $ugel > 0 ? " AND uu.id=$ugel " : "";
                $gestions = $gestion > 0 ? ($gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ") : "";
                $areas = $area > 0 ? " AND aa.id=$area " : "";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', DB::raw('count(mgd.id) as conteo'))->orderBy('mg.anio', 'asc')->groupBy('mg.anio')->get();
                return $query;

            case 'anal2':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(mgd.id) as conteo'));
                $query = $query->orderBy('mes', 'asc')->groupBy('mes')->get();
                return $query;

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBR');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                // $query = $query->orderBy('mes', 'asc')->groupBy('mes')->get();
                // return $query;

            case 'anal3':/*  */

                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        WHERE imp.estado = 'PR') as tb
                    WHERE rn = 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area "; //nm.codigo, nm.nombre
                $iiees = "SELECT ie.id, case when nm.codigo='A2' OR nm.codigo='A3' OR nm.codigo='A5' then 'Inicial' else nm.nombre end as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', 'ie.nivel', DB::raw('count(mg.anio) as conteo'));
                $query = $query->orderBy('mg.anio', 'asc')->groupBy('mg.anio', 'nivel')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')
                //     ->orderBy('fechaActualizacion', 'desc')->get();
                // foreach ($mg as $key => $value) {
                //     $vmg[] = $value->id;
                // }
                // $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                //     ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBR');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(
                //     'anio',
                //     DB::raw('case when nm.codigo="A2" || nm.codigo="A3" || nm.codigo="A5" then "Inicial" else nm.nombre end as nivel'),
                //     DB::raw('count(anio) as conteo')
                // )->orderBy('anio', 'asc')->groupBy('anio', 'nivel')->get();
                // return $query;

            case 'anal4':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, case when nm.codigo='A2' OR nm.codigo='A3' OR nm.codigo='A5' then 'Inicial' else nm.nombre end as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('nivel as name'), DB::raw('count(mgd.id) as y'), DB::raw('count(mgd.id) as yx'));
                $query = $query->groupBy('name')->get();
                return $query;

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBR');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(
                //     DB::raw('case when nm.codigo="A2" || nm.codigo="A3" || nm.codigo="A5" then "Inicial" else nm.nombre end as name'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as y'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                // )->groupBy('name')->get();
                // return $query;

            case 'anal5':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                where estado = 'PR' and anio_id = $anio
                order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, ss.nombre as sexo
                from edu_institucioneducativa as ie
                inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                inner join edu_area as aa on aa.id = ie.Area_id $areas
                inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'
                inner join par_sexo as ss on ss.id=mgd.sexo_id";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select('ie.sexo as name', DB::raw('count(mgd.id) as y'), DB::raw('count(mgd.id) as yx'));
                $query = $query->groupBy('name')->get();
                return $query;


                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'ss.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;
            case 'anal6':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    DB::raw('case when ie.es_eib="NO" then "Intercural" else "Intercural Bilingue" end as eib'),
                    'uu.nombre as ugel',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                )->groupBy('eib', 'ugel')->get();
                return $query;
            case 'anal7':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->where('pais_nacimiento', '!=', 'PERÚ');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    DB::raw('(case when pais_nacimiento in ("VENEZUELA","COLOMBIA","BRASIL","ARGENTINA","CHILE","BOLIVIA","ECUADOR","ESTADOS UNIDOS DE AMERICA","JAPON") then pais_nacimiento else  "OTROS" end) as pais'),
                    //'pais_nacimiento',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm')
                )->orderBy('tt', 'desc')->groupBy('pais')->get();
                return $query;
            case 'anal8':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'edu_matricula_general_detalle.discapacidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'dd.nombre as discapacidad',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm')
                )->orderBy('tt', 'desc')->groupBy('discapacidad')->get();
                return $query;
            case 'tabla1':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo, pp.id as idprovincia, pp.nombre as provincia 
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR' 
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
                    inner join par_ubigeo as pp on pp.id = dd.dependencia ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(
                    'ie.idprovincia',
                    'ie.provincia',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(ie.codigo in ("A3","A5") and grado_id=1,1,0)) as ci'),
                    DB::raw('sum(IF(ie.codigo in ("A2","A3","A5") and grado_id in (2,3,4,5),1,0)) as cii'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (4,5),1,0)) as ciii'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (6,7),1,0)) as civ'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (8,9),1,0)) as cv'),
                    DB::raw('sum(IF(ie.codigo="F0" and grado_id in (10,11),1,0)) as cvi'),
                    DB::raw('sum(IF(ie.codigo="F0" and grado_id in (12,13,14),1,0)) as cvii'),

                );
                $query = $query->groupBy('idprovincia', 'provincia')->get();
                return $query;

            case 'tabla2':

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo, dd.id as iddistrito, dd.nombre as distrito 
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR' 
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
                    inner join par_ubigeo as pp on pp.id = dd.dependencia ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(
                    'ie.iddistrito',
                    'ie.distrito',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(ie.codigo in ("A3","A5") and grado_id=1,1,0)) as ci'),
                    DB::raw('sum(IF(ie.codigo in ("A2","A3","A5") and grado_id in (2,3,4,5),1,0)) as cii'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (4,5),1,0)) as ciii'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (6,7),1,0)) as civ'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (8,9),1,0)) as cv'),
                    DB::raw('sum(IF(ie.codigo="F0" and grado_id in (10,11),1,0)) as cvi'),
                    DB::raw('sum(IF(ie.codigo="F0" and grado_id in (12,13,14),1,0)) as cvii'),
                );
                $query = $query->groupBy('iddistrito', 'distrito')->get();
                return $query;

            case 'tabla3':

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = $ugel > 0 ? " AND uu.id=$ugel " : "";
                $gestions = $gestion > 0 ? ($gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ") : "";
                $areas = $area > 0 ? " AND aa.id=$area " : "";
                $distritos = $provincia > 0 ? " AND dd.id=$provincia " : "";
                $iiees = "SELECT ie.id,nm.codigo , cp.nombre as centropoblado 
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia 
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(
                    'ie.centropoblado',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(ie.codigo in ("A3","A5") and grado_id=1,1,0)) as ci'),
                    DB::raw('sum(IF(ie.codigo in ("A2","A3","A5") and grado_id in (2,3,4,5),1,0)) as cii'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (4,5),1,0)) as ciii'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (6,7),1,0)) as civ'),
                    DB::raw('sum(IF(ie.codigo="B0" and grado_id in (8,9),1,0)) as cv'),
                    DB::raw('sum(IF(ie.codigo="F0" and grado_id in (10,11),1,0)) as cvi'),
                    DB::raw('sum(IF(ie.codigo="F0" and grado_id in (12,13,14),1,0)) as cvii'),
                );
                return $query = $query->groupBy('centropoblado')->get();

            case 'vista2i':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'uu.nombre as ugel',
                    'dt.nombre as distrito',
                    DB::raw('sum(IF(nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tt'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id=1,1,0)) as cih'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id=1,1,0)) as cim'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5m'),
                )->groupBy('ugel', 'distrito')->get();
                return $query;
            case 'vista3i':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'ie.nombreInstEduc as iiee',
                    DB::raw('sum(IF(nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tt'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id=1,1,0)) as cih'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id=1,1,0)) as cim'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5m'),
                )->groupBy('iiee')->get();
                return $query;
            default:
                return [];
        }
    }

    public static function basicaregularopcion2($div, $anio, $provincia, $distrito,  $gestion, $area)
    {
        $impor_unicos = "SELECT id, anio FROM (SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn 
            FROM edu_matricula_general mg INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id INNER JOIN par_anio AS anio ON anio.id = mg.anio_id 
            WHERE imp.estado = 'PR') as iu WHERE rn = 1 ";

        $ubigeos = "";
        if ($provincia > 0) $ubigeos .= " AND dd.dependencia = $provincia ";
        if ($distrito > 0) $ubigeos .= " AND dd.id = $distrito ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $iiees = "SELECT ie.id, case when nm.codigo='A2' OR nm.codigo='A3' OR nm.codigo='A5' then 'Inicial' else nm.nombre end as nivel 
            from edu_institucioneducativa as ie 
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $ubigeos 
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions 
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($impor_unicos) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select('mg.anio', 'ie.nivel', DB::raw('count(mgd.id) as conteo'))->groupBy('mg.anio', 'ie.nivel')->get();
        return $query;
    }

    public static function metaEBR($anio, $ugel, $gestion,  $area)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $areas = "";
        if ($area > 0) $areas .= " AND aa.id=$area ";
        $iiees = "SELECT ie.id, nm.codigo
            from edu_institucioneducativa as ie
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(
            DB::raw('count(mgd.matriculageneral_id) as conteo'),
            DB::raw('sum(IF(ie.codigo in ("A2","A3","A5"),1,0)) as conteoi'),
            DB::raw('sum(IF(ie.codigo="B0",1,0)) as conteop'),
            DB::raw('sum(IF(ie.codigo="F0",1,0)) as conteos')
        );
        return $query = $query->first();
    }

    public static function metaEBRProvincia($anio, $ugel, $gestion,  $area)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $areas = "";
        if ($area > 0) $areas .= " AND aa.id=$area ";
        $iiees = "SELECT ie.id, pp.nombre as provincia
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
            inner join par_ubigeo as pp on pp.id = dd.dependencia
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.provincia'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('provincia')->get();
    }

    public static function metaEBRDistrito($anio, $ugel, $gestion,  $area, $provincia = 0)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = $ugel > 0 ? " AND uu.id=$ugel " : "";
        $gestions = $gestion > 0 ? ($gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ") : "";
        $areas = $area > 0 ? " AND aa.id=$area " : "";
        $iiees = "SELECT ie.id, nm.codigo, dd.id as iddistrito, dd.nombre as distrito 
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
            inner join par_ubigeo as pp on pp.id = dd.dependencia
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.distrito'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('distrito')->get();
    }

    public static function metaEBRCentroPoblado($anio, $ugel, $gestion,  $area, $provincia = 0)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = $ugel > 0 ? " AND uu.id=$ugel " : "";
        $gestions = $gestion > 0 ? ($gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ") : "";
        $areas = $area > 0 ? " AND aa.id=$area " : "";
        $distritos = $provincia > 0 ? " AND dd.id=$provincia " : "";
        $iiees = "SELECT ie.id, cp.nombre as centropoblado 
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
            inner join par_ubigeo as pp on pp.id = dd.dependencia 
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.centropoblado'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('centropoblado')->get();
    }

    public static function basicaregulartablaxx($div, $anio, $ugel, $gestion,  $area)/* eliminar */
    {
        switch ($div) {
            case 'gra1':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'anio',
                    DB::raw('case when nm.codigo="A2" || nm.codigo="A3" || nm.codigo="A5" then "Inicial" else nm.nombre end as nivel'),
                    DB::raw('count(anio) as conteo')
                )->orderBy('anio', 'asc')->groupBy('anio', 'nivel')->get();
                return $query;

            case 'gra2':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(edu_matricula_general_detalle.id) as conteo'))->orderBy('mes', 'asc')->groupBy('mes')->get();
                return $query;

            case 'gra3':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    DB::raw('case when nm.codigo="A2" || nm.codigo="A3" || nm.codigo="A5" then "Inicial" else nm.nombre end as name'),
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;

            case 'gra4':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'ss.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;

            case 'vista1':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'uu.nombre as ugel',
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2","A3","A5"),1,0)) as thi'),
                    DB::raw('sum(IF(sexo_id=2 and nm.codigo in ("A2","A3","A5"),1,0)) as tmi'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo="B0",1,0)) as thp'),
                    DB::raw('sum(IF(sexo_id=2 and nm.codigo="B0",1,0)) as tmp'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo="F0",1,0)) as ths'),
                    DB::raw('sum(IF(sexo_id=2 and nm.codigo="F0",1,0)) as tms'),
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                )->groupBy('ugel')->get();
                return $query;
            case 'vista2':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'uu.nombre as ugel',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(nm.codigo in ("A3","A5") and grado_id=1,1,0)) as ci'),
                    DB::raw('sum(IF(nm.codigo in ("A2","A3","A5") and grado_id in (2,3,4,5),1,0)) as cii'),
                    DB::raw('sum(IF(nm.codigo in ("B0") and grado_id in (4,5),1,0)) as ciii'),
                    DB::raw('sum(IF(nm.codigo in ("B0") and grado_id in (6,7),1,0)) as civ'),
                    DB::raw('sum(IF(nm.codigo in ("B0") and grado_id in (8,9),1,0)) as cv'),
                    DB::raw('sum(IF(nm.codigo in ("F0") and grado_id in (10,11),1,0)) as cvi'),
                    DB::raw('sum(IF(nm.codigo in ("F0") and grado_id in (12,13,14),1,0)) as cvii'),
                )->groupBy('ugel')->get();
                return $query;
            case 'vista1i':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'uu.nombre as ugel',
                    DB::raw('sum(IF(nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tt'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id=1,1,0)) as cih'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id=1,1,0)) as cim'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5m'),
                )->orderBy('tt', 'desc')->groupBy('ugel')->get();
                return $query;
            case 'vista2i':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'uu.nombre as ugel',
                    'dt.nombre as distrito',
                    DB::raw('sum(IF(nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tt'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id=1,1,0)) as cih'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id=1,1,0)) as cim'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5m'),
                )->groupBy('ugel', 'distrito')->get();
                return $query;
            case 'vista3i':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'ie.nombreInstEduc as iiee',
                    DB::raw('sum(IF(nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tt'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2","A3") and grado_id in (1,2,3,4,5),1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id=1,1,0)) as cih'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id=1,1,0)) as cim'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (3),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (2),1,0)) as cii3m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (4),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (3),1,0)) as cii4m'),
                    DB::raw('sum(IF(sexo_id=1 and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=1 and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5h'),
                    DB::raw('sum(IF(sexo_id=2  and nm.codigo in ("A2") and grado_id in (5),1,0))+sum(IF(sexo_id=2  and nm.codigo in ("A3") and grado_id in (4),1,0)) as cii5m'),
                )->groupBy('iiee')->get();
                return $query;
            default:
                return [];
        }
    }

    public static function basicaespecialtabla($div, $anio, $ugel, $distrito, $dependencia, $provincia = 0)
    {
        switch ($div) {
            case 'mhead':

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(
                    DB::raw('count(mgd.matriculageneral_id) as conteo'),
                    DB::raw('sum(IF(ie.codigo="E0",1,0)) as conteox'),
                    DB::raw('sum(IF(ie.codigo="E1",1,0)) as conteoi'),
                    DB::raw('sum(IF(ie.codigo="E2",1,0)) as conteop')
                );
                return $query = $query->first();


                // $mg = Importacion::select('mg.*')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->where('estado', 'PR')->where('anio_id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::select(
                //     DB::raw('count(matriculageneral_id) as conteo'),
                //     DB::raw('sum(IF(nm.codigo="E0",1,0)) as conteox'),
                //     DB::raw('sum(IF(nm.codigo="E1",1,0)) as conteoi'),
                //     DB::raw('sum(IF(nm.codigo="E2",1,0)) as conteop')
                // );
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                // $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBE');
                // return $query = $query->first();
            case 'anal1':/*  */

                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        WHERE imp.estado = 'PR') as tb
                    WHERE rn = 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', DB::raw('count(mgd.id) as conteo'))->orderBy('mg.anio', 'asc')->groupBy('mg.anio')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')
                //     ->orderBy('fechaActualizacion', 'desc')->get();
                // foreach ($mg as $value) {
                //     $vmg[] = $value->id;
                // }
                // $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                //     ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBE');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                // $query = $query->select(
                //     'anio',
                //     DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                // )->orderBy('anio', 'asc')->groupBy('anio')->get();
                // return $query;

            case 'anal2':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(mgd.id) as conteo'));
                $query = $query->orderBy('mes', 'asc')->groupBy('mes')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBE');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                // $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                // $query = $query->orderBy('mes', 'asc')->groupBy('mes')->get();
                // return $query;

            case 'anal3':/*  */

                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        WHERE imp.estado = 'PR') as tb
                    WHERE rn = 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id, case when nm.codigo='A2' OR nm.codigo='A3' OR nm.codigo='A5' then 'Inicial' else nm.nombre end as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', 'ie.nivel', DB::raw('count(mg.anio) as conteo'));
                $query = $query->orderBy('mg.anio', 'asc')->groupBy('mg.anio', 'nivel')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')
                //     ->orderBy('fechaActualizacion', 'desc')->get();
                // foreach ($mg as $key => $value) {
                //     $vmg[] = $value->id;
                // }
                // $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                //     ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBE');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                // $query = $query->select(
                //     'anio',
                //     DB::raw('case when nm.codigo="E0" then "Prite" when nm.codigo="E1" then "Inicial" when nm.codigo="E2" then "Primaria" else nm.nombre end as nivel'),
                //     //DB::raw('nm.codigo as nivel'),
                //     DB::raw('count(anio) as conteo')
                // )->orderBy('anio', 'asc')->groupBy('anio', 'nivel')->get();
                // return $query;

            case 'anal4':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id, case when nm.codigo='E0' then 'Prite' when nm.codigo='E1' then 'Inicial' when nm.codigo='E2' then 'Primaria' else nm.nombre end as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";


                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('nivel as name'), DB::raw('count(mgd.id) as y'), DB::raw('count(mgd.id) as yx'));
                $query = $query->groupBy('name')->get();
                return $query;

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBE');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                // $query = $query->select(
                //     DB::raw('case when nm.codigo="E0" then "Prite" when nm.codigo="E1" then "Inicial" when nm.codigo="E2" then "Primaria" else nm.nombre end as name'),
                //     //DB::raw('case when nm.codigo="A2" || nm.codigo="A3" || nm.codigo="A5" then "Inicial" else nm.nombre end as name'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as y'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                // )->groupBy('name')->get();
                // return $query;

            case 'anal5':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                $query = $query->select(
                    'ss.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;
            case 'anal6':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                $query = $query->select(
                    DB::raw('case when ie.es_eib="NO" then "Intercural" else "Intercural Bilingue" end as eib'),
                    'uu.nombre as ugel',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                )->groupBy('eib', 'ugel')->get();
                return $query;
            case 'anal7':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBE')->where('pais_nacimiento', '!=', 'PERÚ');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                $query = $query->select(
                    DB::raw('(case when pais_nacimiento in ("VENEZUELA","COLOMBIA","BRASIL","ARGENTINA","CHILE","BOLIVIA","ECUADOR","ESTADOS UNIDOS DE AMERICA","JAPON") then pais_nacimiento else  "OTROS" end) as pais'),
                    //'pais_nacimiento',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm')
                )->orderBy('tt', 'desc')->groupBy('pais')->get();
                return $query;
            case 'anal8':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'edu_matricula_general_detalle.discapacidad_id')
                    ->where('nm.tipo', 'EBE');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

                $query = $query->select(
                    'dd.nombre as discapacidad',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm')
                )->orderBy('tt', 'desc')->groupBy('discapacidad')->get();
                return $query;
            case 'tabla1':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id, nm.codigo, pp.id as idprovincia, pp.nombre as provincia 
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(
                    'ie.idprovincia',
                    'ie.provincia',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="E0",1,0)) as thi'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="E0",1,0)) as tmi'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="E1",1,0)) as thp'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="E1",1,0)) as tmp'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="E2",1,0)) as ths'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="E2",1,0)) as tms'),
                );
                $query = $query->groupBy('idprovincia', 'provincia')->get();
                return $query;

                // ######################################
                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBE');
                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);
                // $query = $query->select(
                //     'pv.id as idprovincia',
                //     'pv.nombre as provincia',
                //     DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                //     DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                //     DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                //     DB::raw('sum(IF(sexo_id=1 and nm.codigo="E0",1,0)) as thi'),
                //     DB::raw('sum(IF(sexo_id=2 and nm.codigo="E0",1,0)) as tmi'),
                //     DB::raw('sum(IF(sexo_id=1 and nm.codigo="E1",1,0)) as thp'),
                //     DB::raw('sum(IF(sexo_id=2 and nm.codigo="E1",1,0)) as tmp'),
                //     DB::raw('sum(IF(sexo_id=1 and nm.codigo="E2",1,0)) as ths'),
                //     DB::raw('sum(IF(sexo_id=2 and nm.codigo="E2",1,0)) as tms'),

                // )->groupBy('idprovincia', 'provincia')->get();
                // return $query;
            case 'tabla2':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $distritos = "";
                if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
                $provincias = "";
                if ($provincia > 0) $provincias .= " AND pp.id=$provincia ";
                $dependencias = "";
                if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
                $iiees = "SELECT ie.id, nm.codigo, dd.id as iddistrito, dd.nombre as distrito 
                    from edu_institucioneducativa as ie
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
                    inner join par_ubigeo as pp on pp.id = dd.dependencia $provincias
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(
                    'ie.iddistrito',
                    'ie.distrito',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="E0",1,0)) as thi'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="E0",1,0)) as tmi'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="E1",1,0)) as thp'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="E1",1,0)) as tmp'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="E2",1,0)) as ths'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="E2",1,0)) as tms'),
                );
                $query = $query->groupBy('iddistrito', 'distrito')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBE');
                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);
                // if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                // $query = $query->select(
                //     'dt.id as iddistrito',
                //     'dt.nombre as distrito',
                //     DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                //     DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                //     DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                //     DB::raw('sum(IF(sexo_id=1 and nm.codigo="E0",1,0)) as thi'),
                //     DB::raw('sum(IF(sexo_id=2 and nm.codigo="E0",1,0)) as tmi'),
                //     DB::raw('sum(IF(sexo_id=1 and nm.codigo="E1",1,0)) as thp'),
                //     DB::raw('sum(IF(sexo_id=2 and nm.codigo="E1",1,0)) as tmp'),
                //     DB::raw('sum(IF(sexo_id=1 and nm.codigo="E2",1,0)) as ths'),
                //     DB::raw('sum(IF(sexo_id=2 and nm.codigo="E2",1,0)) as tms'),

                // )->groupBy('iddistrito', 'distrito')->get();
                // return $query;

            default:
                return [];
        }
    }

    public static function metaEBE($anio, $ugel, $distrito, $dependencia)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $distritos = "";
        if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
        $dependencias = "";
        if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
        $iiees = "SELECT ie.id, nm.codigo
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
            inner join par_ubigeo as pp on pp.id = dd.dependencia
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(
            DB::raw('count(mgd.id) as conteo'),
            DB::raw('sum(IF(ie.codigo="E0",1,0)) as conteox'),
            DB::raw('sum(IF(ie.codigo="E1",1,0)) as conteoi'),
            DB::raw('sum(IF(ie.codigo="E2",1,0)) as conteop')
        );
        return $query = $query->first();


        // $mg = Importacion::select('mg.*')
        //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
        //     ->where('estado', 'PR')->where('anio_id', $anio)
        //     ->orderBy('fechaActualizacion', 'desc')->first();

        // $query = MatriculaGeneralDetalle::select(
        //     DB::raw('count(matriculageneral_id) as conteo'),
        //     DB::raw('sum(IF(nm.codigo="E0",1,0)) as conteox'),
        //     DB::raw('sum(IF(nm.codigo="E1",1,0)) as conteoi'),
        //     DB::raw('sum(IF(nm.codigo="E2",1,0)) as conteop')
        // );
        // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
        //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
        //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
        //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
        //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
        //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
        //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

        // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

        // $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBE');
        // return $query = $query->first();
    }

    public static function metaEBEProvincia($anio, $ugel, $distrito, $dependencia)
    {

        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $distritos = "";
        if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
        $dependencias = "";
        if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
        $iiees = "SELECT ie.id, pp.nombre as provincia
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
            inner join par_ubigeo as pp on pp.id = dd.dependencia
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.provincia'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('provincia')->get();


        // $mg = Importacion::select('mg.id')
        //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
        //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
        //     ->where('estado', 'PR')->where('ano.id', $anio)
        //     ->orderBy('fechaActualizacion', 'desc')->first();

        // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
        //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
        //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
        //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
        //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
        //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
        //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
        //     ->where('nm.tipo', 'EBE');

        // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        // if ($dependencia > 0) $query = $query->where('tg.id', $dependencia);

        // $query = $query->select(
        //     DB::raw('pv.nombre as provincia'),
        //     DB::raw('count(pv.id) as conteo'),
        // )->groupBy('provincia')->get();
        // return $query;
    }

    public static function metaEBEDistrito($anio, $ugel, $distrito, $dependencia, $provincia)
    {

        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $distritos = "";
        if ($distrito > 0) $distritos .= " AND dd.id=$distrito ";
        $provincias = "";
        if ($provincia > 0) $provincias .= " AND pp.id=$provincia ";
        $dependencias = "";
        if ($dependencia > 0) $dependencias .= " AND tg.id=$dependencia ";
        $iiees = "SELECT ie.id, nm.codigo, dd.id as iddistrito, dd.nombre as distrito 
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id $distritos
            inner join par_ubigeo as pp on pp.id = dd.dependencia $provincias
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $dependencias
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBE'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.distrito'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('distrito')->get();
    }

    public static function basicaalternativatabla($div, $anio, $ugel, $gestion,  $area, $provincia = 0)
    {
        switch ($div) {
            case 'mhead':

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select(
                    DB::raw('count(mgd.id) as conteo'),
                    DB::raw('sum(IF(ie.codigo="D1" and grado_id in (1,2),1,0)) as conteox'),
                    DB::raw('sum(IF(ie.codigo="D1" and grado_id in (3,4,5),1,0)) as conteoi'),
                    DB::raw('sum(IF(ie.codigo="D2",1,0)) as conteop')
                );
                return $query = $query->first();

                // $mg = Importacion::select('mg.*')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->where('estado', 'PR')->where('anio_id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::select(
                //     DB::raw('count(matriculageneral_id) as conteo'),
                //     DB::raw('sum(IF(nm.codigo="D1" and grado_id in (1,2),1,0)) as conteox'),
                //     DB::raw('sum(IF(nm.codigo="D1" and grado_id in (3,4,5),1,0)) as conteoi'),
                //     DB::raw('sum(IF(nm.codigo="D2",1,0)) as conteop')
                // );
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');
                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBA');
                // return $query = $query->first();
            case 'anal1':/*  */

                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        WHERE imp.estado = 'PR') as tb
                    WHERE rn = 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', DB::raw('count(mgd.id) as conteo'))->orderBy('mg.anio', 'asc')->groupBy('mg.anio')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')
                //     ->orderBy('fechaActualizacion', 'desc')->get();
                // foreach ($mg as $value) {
                //     $vmg[] = $value->id;
                // }
                // $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                //     ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBA');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(
                //     'anio',
                //     DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                // )->orderBy('anio', 'asc')->groupBy('anio')->get();
                // return $query;

            case 'anal2':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(mgd.id) as conteo'));
                $query = $query->orderBy('mes', 'asc')->groupBy('mes')->get();
                return $query;

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBA');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(DB::raw('month(fecha_registro) as mes'), DB::raw('count(edu_matricula_general_detalle.id) as conteo'))->orderBy('mes', 'asc')->groupBy('mes')->get();
                // return $query;

            case 'anal3':/*  */

                $mat_uni_max = "SELECT id, anio
                    FROM(
                        SELECT mg.id, anio.anio, ROW_NUMBER() OVER (PARTITION BY anio.anio ORDER BY mg.id DESC) AS rn
                        FROM edu_matricula_general as mg
                        INNER JOIN par_importacion AS imp ON imp.id = mg.importacion_id
                        INNER JOIN par_anio AS anio ON anio.id = mg.anio_id
                        WHERE imp.estado = 'PR') as tb
                    WHERE rn = 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area "; //nm.codigo, nm.nombre
                $iiees = "SELECT ie.id, case when nm.codigo='D1' then 'Inicial e Intermedio' when nm.codigo='D2' then 'Avanzado' else nm.nombre end as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd')
                    ->join(DB::raw("($mat_uni_max) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id')
                    ->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->select('mg.anio', 'ie.nivel', DB::raw('count(mg.anio) as conteo'));
                $query = $query->orderBy('mg.anio', 'asc')->groupBy('mg.anio', 'nivel')->get();
                return $query;


                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')
                //     ->orderBy('fechaActualizacion', 'desc')->get();
                // foreach ($mg as $key => $value) {
                //     $vmg[] = $value->id;
                // }
                // $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                //     ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBA');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(
                //     'anio',
                //     DB::raw('case when nm.codigo="D1" then "Inicial e Intermedio" when nm.codigo="D2" then "Avanzado" else nm.nombre end as nivel'),
                //     //DB::raw('nm.codigo as nivel'),
                //     DB::raw('count(anio) as conteo')
                // )->orderBy('anio', 'asc')->orderBy('nm.codigo')->groupBy('anio', 'nivel')->get();
                // return $query;

            case 'anal4':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, case when nm.codigo='A2' OR nm.codigo='A3' OR nm.codigo='A5' then 'Inicial' else nm.nombre end as nivel
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(DB::raw('nivel as name'), DB::raw('count(mgd.id) as y'), DB::raw('count(mgd.id) as yx'));
                $query = $query->groupBy('name')->get();
                return $query;

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBA');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(
                //     DB::raw('case when nm.codigo="D1" then "Inicial e Intermedio" when nm.codigo="D2" then "Avanzado" else nm.nombre end as name'),
                //     //DB::raw('case when nm.codigo="E0" then "Prite" when nm.codigo="E1" then "Inicial" when nm.codigo="E2" then "Primaria" else nm.nombre end as name'),
                //     //DB::raw('case when nm.codigo="A2" || nm.codigo="A3" || nm.codigo="A5" then "Inicial" else nm.nombre end as name'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as y'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                // )->orderBy('nm.codigo')->groupBy('name')->get();
                // return $query;

            case 'anal5':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                where estado = 'PR' and anio_id = $anio
                order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id 
                from edu_institucioneducativa as ie
                inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                inner join edu_area as aa on aa.id = ie.Area_id $areas
                inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
                $query = $query->join('par_sexo as ss', 'ss.id', '=', 'mgd.sexo_id');

                $query = $query->select('ss.nombre as name', DB::raw('count(mgd.id) as y'), DB::raw('count(mgd.id) as yx'));
                $query = $query->groupBy('name')->get();
                return $query;

                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                //->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id');
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBA');

                // if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                // if ($area > 0) $query = $query->where('aa.id', $area);
                // if ($gestion > 0) {
                //     if ($gestion == 3) {
                //         $query = $query->where('tg.dependencia', 3);
                //     } else {
                //         $query = $query->where('tg.dependencia', '!=', 3);
                //     }
                // }
                // $query = $query->select(
                //     'ss.nombre as name',
                //     DB::raw('count(edu_matricula_general_detalle.id) as y'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                // )->groupBy('name')->get();
                // return $query;
            case 'anal6':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    DB::raw('case when ie.es_eib="NO" then "Intercural" else "Intercural Bilingue" end as eib'),
                    'uu.nombre as ugel',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                )->groupBy('eib', 'ugel')->get();
                return $query;
            case 'anal7':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBA')->where('pais_nacimiento', '!=', 'PERÚ');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    DB::raw('(case when pais_nacimiento in ("VENEZUELA","COLOMBIA","BRASIL","ARGENTINA","CHILE","BOLIVIA","ECUADOR","ESTADOS UNIDOS DE AMERICA","JAPON") then pais_nacimiento else  "OTROS" end) as pais'),
                    //'pais_nacimiento',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm')
                )->orderBy('tt', 'desc')->groupBy('pais')->get();
                return $query;
            case 'anal8':/*  */
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'edu_matricula_general_detalle.discapacidad_id')
                    ->where('nm.tipo', 'EBA');

                if ($ugel > 0) $query = $query->where('uu.id', $ugel);
                if ($area > 0) $query = $query->where('aa.id', $area);
                if ($gestion > 0) {
                    if ($gestion == 3) {
                        $query = $query->where('tg.dependencia', 3);
                    } else {
                        $query = $query->where('tg.dependencia', '!=', 3);
                    }
                }
                $query = $query->select(
                    'dd.nombre as discapacidad',
                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm')
                )->orderBy('tt', 'desc')->groupBy('discapacidad')->get();
                return $query;
            case 'tabla1':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo, pp.id as idprovincia, pp.nombre as provincia 
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR' 
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
                    inner join par_ubigeo as pp on pp.id = dd.dependencia ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(
                    'ie.idprovincia',
                    'ie.provincia',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="D1" and grado_id in (1,2),1,0)) as thi'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="D1" and grado_id in (1,2),1,0)) as tmi'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="D1" and grado_id in (3,4,5),1,0)) as thp'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="D1" and grado_id in (3,4,5),1,0)) as tmp'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="D2",1,0)) as ths'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="D2",1,0)) as tms'),

                );
                $query = $query->groupBy('idprovincia', 'provincia')->get();
                return $query;

            case 'tabla2':/*  */

                $matricula_unica = "SELECT mg.id from par_importacion as imp
                    inner join edu_matricula_general as mg on mg.importacion_id = imp.id
                    where estado = 'PR' and anio_id = $anio
                    order by fechaActualizacion desc limit 1";

                $ugels = "";
                if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
                $gestions = "";
                if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
                $areas = "";
                if ($area > 0) $areas .= " AND aa.id=$area ";
                $iiees = "SELECT ie.id, nm.codigo, dd.id as iddistrito, dd.nombre as distrito 
                    from edu_institucioneducativa as ie
                    inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
                    inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
                    inner join edu_area as aa on aa.id = ie.Area_id $areas
                    inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBR' 
                    inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
                    inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
                    inner join par_ubigeo as pp on pp.id = dd.dependencia ";

                $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
                $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
                $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');

                $query = $query->select(
                    'ie.iddistrito',
                    'ie.distrito',
                    DB::raw('count(mgd.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2,1,0)) as tm'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="D1" and grado_id in (1,2),1,0)) as thi'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="D1" and grado_id in (1,2),1,0)) as tmi'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="D1" and grado_id in (3,4,5),1,0)) as thp'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="D1" and grado_id in (3,4,5),1,0)) as tmp'),
                    DB::raw('sum(IF(sexo_id=1 and ie.codigo="D2",1,0)) as ths'),
                    DB::raw('sum(IF(sexo_id=2 and ie.codigo="D2",1,0)) as tms'),
                );
                $query = $query->groupBy('iddistrito', 'distrito')->get();
                return $query;

            default:
                return [];
        }
    }

    public static function metaEBA($anio, $ugel, $gestion,  $area)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $areas = "";
        if ($area > 0) $areas .= " AND aa.id=$area ";
        $iiees = "SELECT ie.id, nm.codigo
            from edu_institucioneducativa as ie
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(
            DB::raw('count(mgd.id) as conteo'),
            DB::raw('sum(IF(ie.codigo in ("A2","A3","A5"),1,0)) as conteoi'),
            DB::raw('sum(IF(ie.codigo="B0",1,0)) as conteop'),
            DB::raw('sum(IF(ie.codigo="F0",1,0)) as conteos')
        );
        return $query = $query->first();
    }

    public static function metaEBAProvincia($anio, $ugel, $gestion,  $area)
    {

        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $areas = "";
        if ($area > 0) $areas .= " AND aa.id=$area ";
        $iiees = "SELECT ie.id, pp.nombre as provincia
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
            inner join par_ubigeo as pp on pp.id = dd.dependencia
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.provincia'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('provincia')->get();
    }

    public static function metaEBADistrito($anio, $ugel, $gestion,  $area, $provincia = 0)
    {
        $matricula_unica = "SELECT mg.id from par_importacion as imp
            inner join edu_matricula_general as mg on mg.importacion_id = imp.id
            where estado = 'PR' and anio_id = $anio
            order by fechaActualizacion desc limit 1";

        $ugels = "";
        if ($ugel > 0) $ugels .= " AND uu.id=$ugel ";
        $gestions = "";
        if ($gestion > 0) $gestions .= $gestion == 3 ? " AND tg.dependencia=3 " : " AND tg.dependencia!=3 ";
        $areas = "";
        if ($area > 0) $areas .= " AND aa.id=$area ";
        $iiees = "SELECT ie.id, nm.codigo, dd.id as iddistrito, dd.nombre as distrito 
            from edu_institucioneducativa as ie
            inner join edu_centropoblado as cp on cp.id = ie.CentroPoblado_id 
            inner join par_ubigeo as dd on dd.id = cp.Ubigeo_id 
            inner join par_ubigeo as pp on pp.id = dd.dependencia
            inner join edu_ugel as uu on uu.id = ie.Ugel_id $ugels
            inner join edu_tipogestion as tg on tg.id = ie.TipoGestion_id $gestions
            inner join edu_area as aa on aa.id = ie.Area_id $areas
            inner join edu_nivelmodalidad as nm on nm.id = ie.NivelModalidad_id AND nm.tipo='EBA'";

        $query = MatriculaGeneralDetalle::from('edu_matricula_general_detalle as mgd');
        $query = $query->join(DB::raw("($matricula_unica) as mg"), 'mg.id', '=', 'mgd.matriculageneral_id');
        $query = $query->join(DB::raw("($iiees) as ie"), 'ie.id', '=', 'mgd.institucioneducativa_id');
        $query = $query->select(DB::raw('ie.distrito'), DB::raw('count(mgd.id) as conteo'));
        return $query = $query->groupBy('distrito')->get();
    }
    /*  */
    public static function niveleducativoEBRtabla($div, $anio, $provincia, $distrito,  $nivel)
    {
        switch ($div) {
            case 'head1':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(matriculageneral_id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR');
                return $query = $query->first();
            case 'head2':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR')->where('es_eib', 'SI');
                return $query = $query->first();
            case 'head3':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR')->where('pais_nacimiento', '!=', 'PERÚ');
                return $query = $query->first();
            case 'head4':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBR')->where('discapacidad_id', '>', 0);
                return $query = $query->first();
            case 'anal1':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();
                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    // ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    DB::raw('case when ie.es_eib="NO" then "Intercultural" else "Intercultural Bilingue" end as eib'),
                    'uu.nombre as ugel',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                )->groupBy('eib', 'ugel')->get();
                return $query;

            case 'anal2':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'aa.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;

            case 'anal3':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    // ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    // ->join('par_grupoedad as ge', 'ge.edad', '=', 'edu_matricula_general_detalle.edad')
                    ->where('nm.tipo', 'EBR');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    // DB::raw('case when ge.grupo in ("00-04","05-09","10-14","15-19","20-24","25-29","30-34","35-39","40-44") then ge.grupo else "45 a mas" end as grupos'),
                    DB::raw('case when edad in (0,1,2,3,4) then "00-04"
                                  when edad in (5,6,7,8,9) then "05-09"
                                  when edad in (10,11,12,13,14) then "10-14"
                                  when edad in (15,16,17,18,19) then "15-19"
                                  when edad in (20,21,22,23,24) then "20-24"
                                  when edad in (25,26,27,28,29) then "25-29"
                                  when edad in (30,31,32,33,34) then "30-34"
                                  when edad in (35,36,37,38,39) then "35-39"
                                  when edad in (40,41,42,43,44) then "40-44"
                                  else "45 a mas" end as grupos'),
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->groupBy('grupos', 'sexo')->get();
                return $query;

            case 'anal4':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBR');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ss.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;

            case 'anal5':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBR')->where('pais_nacimiento', '!=', 'PERÚ');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    DB::raw('(case when pais_nacimiento in ("VENEZUELA","COLOMBIA","BRASIL","ARGENTINA","CHILE","BOLIVIA","ECUADOR","ESTADOS UNIDOS DE AMÉRICA","JAPON") then pais_nacimiento else  "OTROS" end) as pais'),
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->groupBy('pais', 'sexo')->orderBy('conteo', 'desc')->get();
                return $query;
            case 'anal6':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'discapacidad_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBR'); //->where('pais_nacimiento', '!=', 'PERÚ');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'dd.nombre as discapacidad',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('discapacidad', 'sexo')->get();
                return $query;
            case 'tabla1':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'uu.id as idugel',
                    'uu.nombre as ugel',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(sexo_id=1 and gg.codigo in (1,2),1,0)) as tpubh'),
                    DB::raw('sum(IF(sexo_id=2  and gg.codigo in (1,2),1,0)) as tpubm'),
                    DB::raw('sum(IF(gg.codigo in (1,2),1,0)) as tpub'),

                    DB::raw('sum(IF(sexo_id=1 and gg.codigo=3,1,0)) as tprih'),
                    DB::raw('sum(IF(sexo_id=2  and gg.codigo=3,1,0)) as tprim'),
                    DB::raw('sum(IF(gg.codigo=3,1,0))  as tpri'),

                    DB::raw('sum(IF(sexo_id=1 and aa.codigo=1,1,0)) as turh'),
                    DB::raw('sum(IF(sexo_id=2  and aa.codigo=1,1,0)) as turm'),
                    DB::raw('sum(IF(aa.codigo=1,1,0)) as tur'),

                    DB::raw('sum(IF(sexo_id=1 and aa.codigo=2,1,0)) as truh'),
                    DB::raw('sum(IF(sexo_id=2  and aa.codigo=2,1,0)) as trum'),
                    DB::raw('sum(IF(aa.codigo=2,1,0)) as tru'),

                )->groupBy('idugel', 'ugel')->get();
                return $query;

            case 'tabla2':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['A2', 'A3', 'A5']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(edad=0,1,0)) as e0'),
                    DB::raw('sum(IF(edad=1,1,0)) as e1'),
                    DB::raw('sum(IF(edad=2,1,0)) as e2'),
                    DB::raw('sum(IF(edad=3,1,0)) as e3'),
                    DB::raw('sum(IF(edad=4,1,0)) as e4'),
                    DB::raw('sum(IF(edad=5,1,0)) as e5'),
                    DB::raw('sum(IF(edad>5,1,0)) as e6'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;
            case 'tabla2a':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['A2', 'A3', 'A5']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',
                    'pv.nombre as provincia',
                    'dt.nombre as distrito',
                    'cp.nombre as centropoblado',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(edad=0,1,0)) as e0'),
                    DB::raw('sum(IF(edad=1,1,0)) as e1'),
                    DB::raw('sum(IF(edad=2,1,0)) as e2'),
                    DB::raw('sum(IF(edad=3,1,0)) as e3'),
                    DB::raw('sum(IF(edad=4,1,0)) as e4'),
                    DB::raw('sum(IF(edad=5,1,0)) as e5'),
                    DB::raw('sum(IF(edad>5,1,0)) as e6'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area', 'provincia', 'distrito', 'centropoblado')->get();
                return $query;
            case 'tabla3':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['B0']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=4,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=5,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=6,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=7,1,0)) as e4'),
                    DB::raw('sum(IF(grado_id=8,1,0)) as e5'),
                    DB::raw('sum(IF(grado_id=9,1,0)) as e6'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;

            case 'tabla3a':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['B0']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',
                    'pv.nombre as provincia',
                    'dt.nombre as distrito',
                    'cp.nombre as centropoblado',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=4,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=5,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=6,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=7,1,0)) as e4'),
                    DB::raw('sum(IF(grado_id=8,1,0)) as e5'),
                    DB::raw('sum(IF(grado_id=9,1,0)) as e6'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area', 'provincia', 'distrito', 'centropoblado')->get();
                return $query;

            case 'tabla4':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['F0']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=10,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=11,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=12,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=13,1,0)) as e4'),
                    DB::raw('sum(IF(grado_id=14,1,0)) as e5'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;

            case 'tabla4a':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['F0']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',
                    'pv.nombre as provincia',
                    'dt.nombre as distrito',
                    'cp.nombre as centropoblado',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=10,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=11,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=12,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=13,1,0)) as e4'),
                    DB::raw('sum(IF(grado_id=14,1,0)) as e5'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area', 'provincia', 'distrito', 'centropoblado')->get();
                return $query;


            default:
                return [];
        }
    }

    public static function metaEBRInicial($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['A2', 'A3', 'A5']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }

    public static function metaEBRPrimaria($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['B0']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }

    public static function metaEBRSecundaria($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBR')->whereIn('nm.codigo', ['F0']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }

    /*  */
    public static function niveleducativoEBEtabla($div, $anio, $provincia, $distrito,  $nivel)
    {
        switch ($div) {
            case 'head1':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::distinct()->select('ie.codModular');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBE');
                return $query = $query->get()->count();
            case 'head2':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBE'); //->where('es_eib', 'SI');
                return $query = $query->first();
            case 'head3':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBE')->where('pais_nacimiento', '!=', 'PERÚ');
                return $query = $query->first();
            case 'head4':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBE')->where('discapacidad_id', '>', 0);
                return $query = $query->first();
            case 'anal1':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    // ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    // DB::raw('case when ie.es_eib="NO" then "Intercural" else "Intercural Bilingue" end as eib'),
                    'uu.nombre as ugel',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                )->groupBy('ugel', 'sexo')->get();
                return $query;

            case 'anal2':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ss.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;

            case 'anal3':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    // ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_grupoedad as ge', 'ge.edad', '=', 'edu_matricula_general_detalle.edad')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    DB::raw('case when ge.grupo in ("00-04","05-09","10-14","15-19","20-24","25-29","30-34","35-39","40-44") then ge.grupo else "45 a mas" end as grupos'),
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->groupBy('grupos', 'sexo')->get();
                return $query;

            case 'anal4':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'discapacidad_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE')->where('discapacidad_id', '>', '0');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'dd.nombre as discapacidad',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('discapacidad', 'sexo')->get();
                return $query;

            case 'anal5':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE')->where('pais_nacimiento', '!=', 'PERÚ')->where('discapacidad_id', '>', '0');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    DB::raw('(case when pais_nacimiento in ("VENEZUELA","COLOMBIA","BRASIL","ARGENTINA","CHILE","BOLIVIA","ECUADOR","ESTADOS UNIDOS DE AMERICA","JAPON") then pais_nacimiento else  "OTROS" end) as pais'),
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('pais', 'sexo')->get();
                return $query;
            case 'anal6':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'discapacidad_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBE')->where('pais_nacimiento', '!=', 'PERÚ')->where('discapacidad_id', '>', '0');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'dd.nombre as discapacidad',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('discapacidad', 'sexo')->get();
                return $query;

            case 'tabla1':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBE')->whereIn('nm.codigo', ['E0', 'E1']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    DB::raw('case when nm.codigo="E0" then "Prite" when nm.codigo="E1" then "Inicial" else nm.nombre end as nivel'),
                    // 'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(edad=0,1,0)) as e0'),
                    DB::raw('sum(IF(edad=1,1,0)) as e1'),
                    DB::raw('sum(IF(edad=2,1,0)) as e2'),
                    DB::raw('sum(IF(edad=3,1,0)) as e3'),
                    DB::raw('sum(IF(edad=4,1,0)) as e4'),
                    DB::raw('sum(IF(edad=5,1,0)) as e5'),
                    DB::raw('sum(IF(edad>5,1,0)) as e6'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;

            case 'tabla2':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBE')->whereIn('nm.codigo', ['E2']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    DB::raw('case when nm.codigo="E0" then "Prite" when nm.codigo="E1" then "Inicial" when nm.codigo="E2" then "Primaria" else nm.nombre end as nivel'),
                    // 'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=6,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=7,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=8,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=9,1,0)) as e4'),
                    DB::raw('sum(IF(grado_id=10,1,0)) as e5'),
                    DB::raw('sum(IF(grado_id=11,1,0)) as e6'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;

            default:
                return [];
        }
    }

    public static function metaEBEInicial($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBE')->whereIn('nm.codigo', ['E0', 'E1']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }

    public static function metaEBEPrimaria($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBE')->whereIn('nm.codigo', ['E2']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }



    /*  */
    public static function niveleducativoEBAtabla($div, $anio, $provincia, $distrito,  $nivel)
    {
        switch ($div) {
            case 'head1':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::distinct()->select('ie.codModular');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBA');
                return $query = $query->get()->count();
            case 'head2':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBA'); //->where('es_eib', 'SI');
                return $query = $query->first();
            case 'head3':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBA')->where('pais_nacimiento', '!=', 'PERÚ');
                return $query = $query->first();
            case 'head4':
                $mg = Importacion::select('mg.*')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->where('estado', 'PR')->where('anio_id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::select(DB::raw('count(edu_matricula_general_detalle.id) as conteo'));
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->where('matriculageneral_id', $mg->id)->where('nm.tipo', 'EBA')->where('discapacidad_id', '>', 0);
                return $query = $query->first();
            case 'anal1':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();
                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    // ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBA');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    // DB::raw('case when ie.es_eib="NO" then "Intercural" else "Intercural Bilingue" end as eib'),
                    'uu.nombre as ugel',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo')
                )->groupBy('ugel', 'sexo')->get();
                return $query;

            case 'anal2':
                // $mg = Importacion::select('mg.id')
                //     ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                //     ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                //     ->where('estado', 'PR')->where('ano.id', $anio)
                //     ->orderBy('fechaActualizacion', 'desc')->first();

                // $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                // $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                //     ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                //     ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                //     ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                //     ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                //     ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                //     ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                //     ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                //     ->where('nm.tipo', 'EBA');

                // if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                // if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                // if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                // $query = $query->select(
                //     'aa.nombre as name',
                //     DB::raw('count(edu_matricula_general_detalle.id) as y'),
                //     DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                // )->groupBy('name')->get();
                // return $query;

                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBA');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ss.nombre as name',
                    DB::raw('count(edu_matricula_general_detalle.id) as y'),
                    DB::raw('count(edu_matricula_general_detalle.id) as yx'),
                )->groupBy('name')->get();
                return $query;

            case 'anal3':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    // ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    // ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_grupoedad as ge', 'ge.edad', '=', 'edu_matricula_general_detalle.edad')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBA');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    DB::raw('case when ge.grupo in ("00-04","05-09","10-14","15-19","20-24","25-29","30-34","35-39","40-44") then ge.grupo else "45 a mas" end as grupos'),
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->groupBy('grupos', 'sexo')->get();
                return $query;

            case 'anal4':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'discapacidad_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBA')->where('discapacidad_id', '>', '0');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'dd.nombre as discapacidad',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('discapacidad', 'sexo')->get();
                return $query;

            case 'anal5':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBA')->where('pais_nacimiento', '!=', 'PERÚ')->where('discapacidad_id', '>', '0');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    DB::raw('(case when pais_nacimiento in ("VENEZUELA","COLOMBIA","BRASIL","ARGENTINA","CHILE","BOLIVIA","ECUADOR","ESTADOS UNIDOS DE AMERICA","JAPON") then pais_nacimiento else  "OTROS" end) as pais'),
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('pais', 'sexo')->get();
                return $query;
            case 'anal6':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')
                    ->orderBy('fechaActualizacion', 'desc')->get();
                foreach ($mg as $key => $value) {
                    $vmg[] = $value->id;
                }
                $query = MatriculaGeneralDetalle::whereIn('matriculageneral_id', $vmg)
                    ->join('edu_matricula_general as mg', 'mg.id', '=', 'edu_matricula_general_detalle.matriculageneral_id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id');
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_discapacidad as dd', 'dd.id', '=', 'discapacidad_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->join('par_sexo as ss', 'ss.id', '=', 'edu_matricula_general_detalle.sexo_id')
                    ->where('nm.tipo', 'EBA')->where('pais_nacimiento', '!=', 'PERÚ')->where('discapacidad_id', '>', '0');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'dd.nombre as discapacidad',
                    'ss.nombre as sexo',
                    DB::raw('count(edu_matricula_general_detalle.id) as conteo'),
                )->orderBy('conteo', 'desc')->groupBy('discapacidad', 'sexo')->get();
                return $query;

            case 'tabla1':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBA');

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'uu.id as idugel',
                    'uu.nombre as ugel',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(sexo_id=1 and gg.codigo in (1,2),1,0)) as tpubh'),
                    DB::raw('sum(IF(sexo_id=2  and gg.codigo in (1,2),1,0)) as tpubm'),
                    DB::raw('sum(IF(gg.codigo in (1,2),1,0)) as tpub'),

                    DB::raw('sum(IF(sexo_id=1 and gg.codigo=3,1,0)) as tprih'),
                    DB::raw('sum(IF(sexo_id=2  and gg.codigo=3,1,0)) as tprim'),
                    DB::raw('sum(IF(gg.codigo=3,1,0))  as tpri'),

                    DB::raw('sum(IF(sexo_id=1 and aa.codigo=1,1,0)) as turh'),
                    DB::raw('sum(IF(sexo_id=2  and aa.codigo=1,1,0)) as turm'),
                    DB::raw('sum(IF(aa.codigo=1,1,0)) as tur'),

                    DB::raw('sum(IF(sexo_id=1 and aa.codigo=2,1,0)) as truh'),
                    DB::raw('sum(IF(sexo_id=2  and aa.codigo=2,1,0)) as trum'),
                    DB::raw('sum(IF(aa.codigo=2,1,0)) as tru'),

                )->groupBy('idugel', 'ugel')->get();
                return $query;
            case 'tabla2':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    // ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBA')->whereIn('nm.codigo', ['D1']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    DB::raw('case when nm.codigo="D1" then "Inicial e Intermedio" when nm.codigo="D2" then "Avanzado" else nm.nombre end as nivel'),
                    // 'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=1,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=2,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=3,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=4,1,0)) as e4'),
                    DB::raw('sum(IF(grado_id=5,1,0)) as e5'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;

            case 'tabla3':
                $mg = Importacion::select('mg.id')
                    ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
                    ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
                    ->where('estado', 'PR')->where('ano.id', $anio)
                    ->orderBy('fechaActualizacion', 'desc')->first();

                $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
                $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                    ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
                    ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
                    ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
                    ->where('nm.tipo', 'EBA')->whereIn('nm.codigo', ['D2']);

                if ($provincia > 0) $query = $query->where('pv.id', $provincia);
                if ($distrito > 0) $query = $query->where('dt.id', $distrito);
                if ($nivel > 0) $query = $query->where('nm.id', $nivel);

                $query = $query->select(
                    'ie.id as idiiee',
                    'ie.codModular as codmod',
                    'ie.nombreInstEduc as iiee',
                    DB::raw('case when nm.codigo="D1" then "Inicial e Intermedio" when nm.codigo="D2" then "Avanzado" else nm.nombre end as nivel'),
                    // 'nm.nombre as nivel',
                    DB::raw('case when gg.codigo!=3 then "Publico" else "Privada" end as gestion'),
                    'aa.nombre as area',

                    DB::raw('count(edu_matricula_general_detalle.id) as tt'),
                    DB::raw('sum(IF(sexo_id=1,1,0)) as th'),
                    DB::raw('sum(IF(sexo_id=2 ,1,0)) as tm'),

                    DB::raw('sum(IF(grado_id=6,1,0)) as e1'),
                    DB::raw('sum(IF(grado_id=7,1,0)) as e2'),
                    DB::raw('sum(IF(grado_id=8,1,0)) as e3'),
                    DB::raw('sum(IF(grado_id=9,1,0)) as e4'),
                )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
                return $query;

            default:
                return [];
        }
    }

    public static function metaEBAInicial($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBA')->whereIn('nm.codigo', ['D1']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }

    public static function metaEBAAvanzado($anio, $provincia, $distrito,  $nivel)
    {
        $mg = Importacion::select('mg.id')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->join('par_anio as ano', 'ano.id', '=', 'mg.anio_id')
            ->where('estado', 'PR')->where('ano.id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::where('matriculageneral_id', $mg->id);
        $query = $query->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('nm.tipo', 'EBA')->whereIn('nm.codigo', ['D2']);

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($nivel > 0) $query = $query->where('nm.id', $nivel);

        $query = $query->select(
            'ie.id as idiiee',
            'ie.codModular as codmod',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'gg.nombre as gestion',
            'aa.nombre as area',
            DB::raw('count(edu_matricula_general_detalle.id) as tt'),
        )->groupBy('idiiee', 'codmod', 'iiee', 'nivel', 'gestion', 'area')->get();
        return $query;
    }

    public static function estudiantesModeloEIB($anio, $provincia, $distrito, $ugel, $area, $gestion)
    {
        $imp = ImportacionRepositorio::aniosMax_porfuente(ImporPadronEibController::$FUENTE);
        $ipeib = $imp->id;
        $mg = Importacion::select('mg.*')
            ->join('edu_matricula_general as mg', 'mg.importacion_id', '=', 'par_importacion.id')
            ->where('estado', 'PR')->where('anio_id', $anio)
            ->orderBy('fechaActualizacion', 'desc')->first();

        $query = MatriculaGeneralDetalle::select('edu_matricula_general_detalle.id')
            ->join(DB::raw("(SELECT institucioneducativa_id FROM edu_padron_eib where importacion_id=$ipeib) as eib"), 'eib.institucioneducativa_id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_matricula_general_detalle.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
            ->join('edu_tipogestion as tg', 'tg.id', '=', 'ie.TipoGestion_id')
            ->join('edu_tipogestion as gg', 'gg.id', '=', 'tg.dependencia');

        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($area > 0) $query = $query->where('aa.id', $area);
        if ($gestion > 0) $query = $query->where('gg.id', $gestion);

        $query = $query->where('matriculageneral_id', $mg->id); //->where('nm.tipo', 'EBA');
        return $query = $query->get()->count();
    }
}
