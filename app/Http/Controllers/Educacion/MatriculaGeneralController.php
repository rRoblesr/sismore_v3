<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\AvanceMatricula1Export;
use App\Exports\BasicaAlternativaExport;
use App\Exports\BasicaEspecialExport;
use App\Exports\BasicaRegularExport;
use App\Exports\NivelEducativoEBAExport;
use App\Exports\NivelEducativoEBEExport;
use App\Exports\NivelEducativoEBRExport;
use App\Exports\NivelEducativoExport;
use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Mes;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MatriculaGeneralController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $mess = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function rojos($mes, $nivel, $ano)
    {
        if ($mes > 1 && $mes < 13) {
            $mesA = $mes - 1;
            $nfi = '' . ($mesA < 10 ? '0' : '') . $mesA . '/' . $ano;
            $nff = '' . ($mes < 10 ? '0' : '') . $mes . '/' . $ano;
            $fechas = DB::table(DB::raw("(
                select mes, max(fecha) fecha from (
                    select
                        distinct
                        v3.fechaActualizacion fecha,
                        year(v3.fechaActualizacion) ano,
                        month(v3.fechaActualizacion) mes,
                        day(v3.fechaActualizacion) dia
                    from edu_matricula_detalle as v1
                    inner join edu_matricula as v2 on v2.id=v1.matricula_id
                    inner join par_importacion as v3 on v3.id=v2.importacion_id
                    inner join par_anio as v4 on v4.id=v2.anio_id
                    where v3.estado='PR' and year(v3.fechaActualizacion)=$ano and month(v3.fechaActualizacion)=$mesA
                    order by fecha desc
                ) as xx
                group by mes
                order by mes asc
                    ) as xx"))->get()->first();
            $fi = $fechas->fecha;

            $fechas = DB::table(DB::raw("(
                select mes, max(fecha) fecha from (
                    select
                        distinct
                        v3.fechaActualizacion fecha,
                        year(v3.fechaActualizacion) ano,
                        month(v3.fechaActualizacion) mes,
                        day(v3.fechaActualizacion) dia
                    from edu_matricula_detalle as v1
                    inner join edu_matricula as v2 on v2.id=v1.matricula_id
                    inner join par_importacion as v3 on v3.id=v2.importacion_id
                    inner join par_anio as v4 on v4.id=v2.anio_id
                    where v3.estado='PR' and year(v3.fechaActualizacion)=$ano and month(v3.fechaActualizacion)=$mes
                    order by fecha desc
                ) as xx
                group by mes
                order by mes asc
                    ) as xx"))->get()->first();
            $ff = $fechas->fecha;

            /* $baseA = DB::table(DB::raw("(
                        select
                            v5.id,
                            v5.tipo,
                            v5.nombre nivel,
                            sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) conteo
                        from edu_matricula_detalle as v1
                        inner join edu_matricula as v2 on v2.id=v1.matricula_id
                        inner join par_importacion as v3 on v3.id=v2.importacion_id
                        inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                        inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                        inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                        inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                        inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                        inner join edu_area as v9 on v9.id=v4.Area_id
                        where v3.estado='PR' and v3.fechaActualizacion='$fA' and v5.id=$nivel
                        group by id,tipo,nivel
                            ) as xx"))->get();
            $base = DB::table(DB::raw("(
                                select
                                    v5.id,
                                    v5.tipo,
                                    v5.nombre nivel,
                                    sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) conteo
                                from edu_matricula_detalle as v1
                                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                                inner join par_importacion as v3 on v3.id=v2.importacion_id
                                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                                inner join edu_area as v9 on v9.id=v4.Area_id
                                where v3.estado='PR' and v3.fechaActualizacion='$f' and v5.id=$nivel
                                group by id,tipo,nivel
                                    ) as xx"))->get(); */
            $base = DB::table('edu_matricula_detalle as v1')
                ->join('edu_matricula as v2', 'v2.id', '=', 'v1.matricula_id')
                ->join('par_importacion as v3', 'v3.id', '=', 'v2.importacion_id')
                ->join('edu_institucioneducativa as v4', 'v4.id', '=', 'v1.institucioneducativa_id')
                ->join('edu_nivelmodalidad as v5', 'v5.id', '=', 'v4.NivelModalidad_id')
                ->join('edu_ugel as v6', 'v6.id', '=', 'v4.Ugel_id')
                ->join('edu_tipogestion as v7', 'v7.id', '=', 'v4.TipoGestion_id')
                ->join('edu_tipogestion as v8', 'v8.id', '=', 'v7.dependencia')
                ->join('edu_area as v9', 'v9.id', '=', 'v4.Area_id')
                ->where('v3.estado', "PR")->where('v5.id', $nivel)->whereIn('v3.fechaActualizacion', [$fi, $ff])
                ->groupBy('modular', 'iiee')
                ->select(
                    'v4.codModular as modular',
                    'v4.nombreInstEduc as iiee',
                    DB::raw("sum(IF(month(v3.fechaActualizacion)=$mesA,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) cfi"),
                    DB::raw("sum(IF(month(v3.fechaActualizacion)=$mes,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) cff"),
                    DB::raw("sum(IF(month(v3.fechaActualizacion)=$mes,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0))-
                    sum(IF(month(v3.fechaActualizacion)=$mesA,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) as  ct")
                )
                ->get();
            $foot['cfi'] = 0;
            $foot['cff'] = 0;
            $foot['ct'] = 0;
            foreach ($base as $key => $value) {
                $foot['cfi'] += $value->cfi;
                $foot['cff'] += $value->cff;
                $foot['ct'] += $value->ct;
            }
            //return response()->json(compact('fechas', 'base'));
            return view("educacion.MatriculaDetalle.MatriculaAvanceRojos", compact('base', 'foot', 'nfi', 'nff'));
        }
        return 'xcxc';
    }

    public function avance()
    {
        /* anos */
        $anios = MatriculaRepositorio::matriculas_anio();
        $actual = 0;
        foreach ($anios as $key => $value) {
            $actual = $value->id;
        }
        /* ugels */
        $ugels = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        /* gestion */
        $gestions = [["id" => 12, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        /* area geografica */
        $areas = Area::select('id', 'nombre')->get();
        /* ultimo reg subido */
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '8')->orderBy('fecha', 'desc')->first();
        $importacion_id = $imp->id;
        $fecha = date('d/m/Y', strtotime($imp->fecha));
        return view("educacion.MatriculaDetalle.MatriculaAvance", compact('anios', 'actual', 'gestions', 'areas', 'ugels', 'importacion_id', 'fecha'));
    }

    public function cargartabla0(Request $rq)
    {
        $ano = $rq->ano;
        $ugel = $rq->ugel;
        $gestion = $rq->gestion;
        $area = $rq->area;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

        $error['ano'] = $ano;
        $error['gestion'] = $gestion;
        $error['area'] = $area;


        $anios = Anio::orderBy('anio', 'desc')->get();
        $anonro = 0;
        $anoA = 0;
        foreach ($anios as $key => $value) {
            if ($value->id == $ano) $anonro = $value->anio - 1;
            if ($value->anio == $anonro) $anoA = $value->id;
        }
        $error['anios'] = $anios;
        $error['anonro'] = $anonro;
        $error['anoA'] = $anoA;


        $fechas = DB::table(DB::raw("(
            select mes, max(fecha) fecha from (
                select
                    distinct
                    v3.fechaActualizacion fecha,
                    year(v3.fechaActualizacion) ano,
                    month(v3.fechaActualizacion) mes,
                    day(v3.fechaActualizacion) dia
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join par_anio as v4 on v4.id=v2.anio_id
                where v3.estado='PR' and v2.anio_id=$ano
                order by fecha desc
            ) as xx
            group by mes
            order by mes asc
                ) as xx"))->get();

        $error['fechas'] = $fechas;


        $fx = '';
        $anoI = 0;
        $anoF = 0;
        foreach ($fechas as $key => $value) {
            if ($key < count($fechas) - 1)
                $fx .= "'$value->fecha',";
            else
                $fx .= "'$value->fecha'";
            if ($key == 0) $anoI = $value->mes;
            if ($key == (count($fechas) - 1)) $anoF = $value->mes + 1;
        }

        $error['fx'] = $fx;
        $error['anoI'] = $anoI;
        $error['anoF'] = $anoF;

        $baseA = DB::table(DB::raw("(
        select
            v6.nombre ugel,
            sum(IF(month(v3.fechaActualizacion)=12,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) dic
        from edu_matricula_detalle as v1
        inner join edu_matricula as v2 on v2.id=v1.matricula_id
        inner join par_importacion as v3 on v3.id=v2.importacion_id
        inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
        inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
        inner join edu_ugel as v6 on v6.id=v4.Ugel_id
        inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
        inner join edu_tipogestion as v8 on v8.id=v7.dependencia
        inner join edu_area as v9 on v9.id=v4.Area_id
        where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$anoA and month(v3.fechaActualizacion)=12 $optgestion $optarea $optugel
        group by ugel
        order by ugel asc
            ) as xx"))->get();
        if (count($baseA) == 0) {
            $baseA = Ugel::where('dependencia', '2')->select('nombre as ugel', DB::raw('0 as dic'))->get();
        }
        $error['baseA'] = $baseA;


        $base = DB::table(DB::raw("(
            select
                v6.id,
                v6.nombre ugel,
                sum(IF(month(v3.fechaActualizacion)= 1,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) ene,
                sum(IF(month(v3.fechaActualizacion)= 2,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 1,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) feb,
                sum(IF(month(v3.fechaActualizacion)= 3,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 2,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) mar,
                sum(IF(month(v3.fechaActualizacion)= 4,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 3,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) abr,
                sum(IF(month(v3.fechaActualizacion)= 5,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 4,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) may,
                sum(IF(month(v3.fechaActualizacion)= 6,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 5,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) jun,
                sum(IF(month(v3.fechaActualizacion)= 7,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 6,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) jul,
                sum(IF(month(v3.fechaActualizacion)= 8,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 7,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) ago,
                sum(IF(month(v3.fechaActualizacion)= 9,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 8,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) `set`,
                sum(IF(month(v3.fechaActualizacion)=10,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 9,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) oct,
                sum(IF(month(v3.fechaActualizacion)=11,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)=10,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) nov,
                sum(IF(month(v3.fechaActualizacion)=12,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)=11,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) dic,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) total
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by id,ugel
            order by ugel asc
            ) as xx"))->get();

        $foot = ['meta' => 0, 'ene' => 0, 'feb' => 0, 'mar' => 0, 'abr' => 0, 'may' => 0, 'jun' => 0, 'jul' => 0, 'ago' => 0, 'set' => 0, 'oct' => 0, 'nov' => 0, 'dic' => 0, 'total' => 0, 'avance' => 0,];

        foreach ($base as $reg => $bb) {
            $bb->treg = ($anoF != 1 ? $bb->ene : 0) + ($anoF != 2 ? $bb->feb : 0) + ($anoF != 3 ? $bb->mar : 0) + ($anoF != 4 ? $bb->abr : 0) +  ($anoF != 5 ? $bb->may : 0) +  ($anoF != 6 ? $bb->jun : 0) +  ($anoF != 7 ? $bb->jul : 0) +  ($anoF != 8 ? $bb->ago : 0) + ($anoF != 9 ? $bb->set : 0) + ($anoF != 10 ? $bb->oct : 0) +  ($anoF != 11 ? $bb->nov : 0) + ($anoF != 12 ? $bb->dic : 0);

            foreach ($baseA as $key2 => $bA) {
                if ($bA->ugel == $bb->ugel)
                    $bb->tregA = $bA->dic;
            }
            $bb->avance = $bb->tregA > 0 ? $bb->treg / $bb->tregA : 1;

            $foot['meta'] += $bb->tregA;
            $foot['ene'] += $bb->ene;
            $foot['feb'] += $bb->feb;
            $foot['mar'] += $bb->mar;
            $foot['abr'] += $bb->abr;
            $foot['may'] += $bb->may;
            $foot['jun'] += $bb->jun;
            $foot['jul'] += $bb->jul;
            $foot['ago'] += $bb->ago;
            $foot['set'] += $bb->set;
            $foot['oct'] += $bb->oct;
            $foot['nov'] += $bb->nov;
            $foot['dic'] += $bb->dic;
            $foot['total'] += $bb->treg;
        }
        $foot['avance'] = $foot['meta'] > 0 ? $foot['total'] / $foot['meta'] : 1;
        $error['base'] = $base;



        //return $error;/
        return view("educacion.MatriculaDetalle.MatriculaAvancetabla0", compact('rq', 'base', 'anoI', 'anoF', 'foot'));
    }

    public function cargartabla1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

        /* $error['ano'] = $ano;
        $error['gestion'] = $gestion;
        $error['area'] = $area; */


        $anios = Anio::orderBy('anio', 'desc')->get();
        $anonro = 0;
        $anoA = 0;
        $anos = 0;
        foreach ($anios as $key => $value) {
            if ($value->id == $ano) {
                $anonro = $value->anio - 1;
                $anos = $value->anio;
            }
            if ($value->anio == $anonro) $anoA = $value->id;
        }

        /* $error['anios'] = $anios;
        $error['anonro'] = $anonro;
        $error['anoA'] = $anoA; */


        $fechas = DB::table(DB::raw("(
            select mes, max(fecha) fecha from (
                select
                    distinct
                    v3.fechaActualizacion fecha,
                    year(v3.fechaActualizacion) ano,
                    month(v3.fechaActualizacion) mes,
                    day(v3.fechaActualizacion) dia
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join par_anio as v4 on v4.id=v2.anio_id
                where v3.estado='PR' and v2.anio_id=$ano
                order by fecha desc
            ) as xx
            group by mes
            order by mes asc
                ) as xx"))->get();

        /* $error['fechas'] = $fechas; */


        $fx = '';
        $anoI = 0;
        $anoF = 0;
        foreach ($fechas as $key => $value) {
            if ($key < count($fechas) - 1)
                $fx .= "'$value->fecha',";
            else
                $fx .= "'$value->fecha'";
            if ($key == 0) $anoI = $value->mes;
            if ($key == (count($fechas) - 1)) $anoF = $value->mes + 1;
        }

        /* $error['fx'] = $fx;
        $error['anoI'] = $anoI;
        $error['anoF'] = $anoF; */

        $baseA = DB::table(DB::raw("(
        select
            v5.id,
            v5.tipo,
            v5.nombre nivel,
            sum(IF(month(v3.fechaActualizacion)=12,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) dic
        from edu_matricula_detalle as v1
        inner join edu_matricula as v2 on v2.id=v1.matricula_id
        inner join par_importacion as v3 on v3.id=v2.importacion_id
        inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
        inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
        inner join edu_ugel as v6 on v6.id=v4.Ugel_id
        inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
        inner join edu_tipogestion as v8 on v8.id=v7.dependencia
        inner join edu_area as v9 on v9.id=v4.Area_id
        where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$anoA and month(v3.fechaActualizacion)=12 $optgestion $optarea $optugel
        group by id,tipo,nivel
            ) as xx"))->get();
        if (count($baseA) == 0) {
            $baseA = NivelModalidad::whereIn('tipo', ['EBR', 'EBE'])->select('id', 'tipo', DB::raw('0 as dic'))->get();
        }
        /* $error['baseA'] = $baseA; */


        $base = DB::table(DB::raw("(
            select
                v5.id,
                v5.tipo,
                v5.nombre nivel,
                sum(IF(month(v3.fechaActualizacion)= 1,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) ene,
                sum(IF(month(v3.fechaActualizacion)= 2,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 1,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) feb,
                sum(IF(month(v3.fechaActualizacion)= 3,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 2,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) mar,
                sum(IF(month(v3.fechaActualizacion)= 4,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 3,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) abr,
                sum(IF(month(v3.fechaActualizacion)= 5,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 4,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) may,
                sum(IF(month(v3.fechaActualizacion)= 6,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 5,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) jun,
                sum(IF(month(v3.fechaActualizacion)= 7,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 6,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) jul,
                sum(IF(month(v3.fechaActualizacion)= 8,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 7,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) ago,
                sum(IF(month(v3.fechaActualizacion)= 9,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 8,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) `set`,
                sum(IF(month(v3.fechaActualizacion)=10,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 9,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) oct,
                sum(IF(month(v3.fechaActualizacion)=11,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)=10,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) nov,
                sum(IF(month(v3.fechaActualizacion)=12,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)=11,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) dic,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) total
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by id,tipo,nivel
            ) as xx"))->get();



        $foot = ['meta' => 0, 'ene' => 0, 'feb' => 0, 'mar' => 0, 'abr' => 0, 'may' => 0, 'jun' => 0, 'jul' => 0, 'ago' => 0, 'set' => 0, 'oct' => 0, 'nov' => 0, 'dic' => 0, 'total' => 0, 'avance' => 0,];

        foreach ($base as $reg => $bb) {
            $bb->treg = ($anoF != 1 ? $bb->ene : 0) + ($anoF != 2 ? $bb->feb : 0) + ($anoF != 3 ? $bb->mar : 0) + ($anoF != 4 ? $bb->abr : 0) +  ($anoF != 5 ? $bb->may : 0) +  ($anoF != 6 ? $bb->jun : 0) +  ($anoF != 7 ? $bb->jul : 0) +  ($anoF != 8 ? $bb->ago : 0) + ($anoF != 9 ? $bb->set : 0) + ($anoF != 10 ? $bb->oct : 0) +  ($anoF != 11 ? $bb->nov : 0) + ($anoF != 12 ? $bb->dic : 0);

            foreach ($baseA as $key2 => $bA) {
                if ($bA->id == $bb->id)
                    $bb->tregA = $bA->dic;
            }
            $bb->avance = $bb->tregA > 0 ? $bb->treg / $bb->tregA : 1;

            $foot['meta'] += $bb->tregA;
            $foot['ene'] += $bb->ene;
            $foot['feb'] += $bb->feb;
            $foot['mar'] += $bb->mar;
            $foot['abr'] += $bb->abr;
            $foot['may'] += $bb->may;
            $foot['jun'] += $bb->jun;
            $foot['jul'] += $bb->jul;
            $foot['ago'] += $bb->ago;
            $foot['set'] += $bb->set;
            $foot['oct'] += $bb->oct;
            $foot['nov'] += $bb->nov;
            $foot['dic'] += $bb->dic;
            $foot['total'] += $bb->treg;
        }
        $foot['avance'] = $foot['meta'] > 0 ? $foot['total'] / $foot['meta'] : 1;

        /* $error['base'] = $base; */

        $headA = DB::table(DB::raw("(
            select
                v5.tipo,
                sum(IF(month(v3.fechaActualizacion)=12,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) dic
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$anoA and month(v3.fechaActualizacion)=12 $optgestion $optarea $optugel
            group by tipo
                ) as xx"))->get();
        if (count($headA) == 0) {
            $headA = NivelModalidad::whereIn('tipo', ['EBR', 'EBE'])->distinct()->select('tipo', DB::raw('0 as dic'))->get();
        }
        /* $error['headA'] = $headA; */

        $head = DB::table(DB::raw("(
            select
                v5.tipo,
                sum(IF(month(v3.fechaActualizacion)= 1,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) ene,
                sum(IF(month(v3.fechaActualizacion)= 2,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 1,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) feb,
                sum(IF(month(v3.fechaActualizacion)= 3,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 2,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) mar,
                sum(IF(month(v3.fechaActualizacion)= 4,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 3,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) abr,
                sum(IF(month(v3.fechaActualizacion)= 5,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 4,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) may,
                sum(IF(month(v3.fechaActualizacion)= 6,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 5,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) jun,
                sum(IF(month(v3.fechaActualizacion)= 7,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 6,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) jul,
                sum(IF(month(v3.fechaActualizacion)= 8,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 7,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) ago,
                sum(IF(month(v3.fechaActualizacion)= 9,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 8,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) `set`,
                sum(IF(month(v3.fechaActualizacion)=10,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)= 9,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) oct,
                sum(IF(month(v3.fechaActualizacion)=11,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)=10,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) nov,
                sum(IF(month(v3.fechaActualizacion)=12,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) -
                sum(IF(month(v3.fechaActualizacion)=11,IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) dic,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) total
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by tipo
            ) as xx"))->get();

        foreach ($head as $reg => $bb) {
            $bb->treg = ($anoF != 1 ? $bb->ene : 0) + ($anoF != 2 ? $bb->feb : 0) + ($anoF != 3 ? $bb->mar : 0) + ($anoF != 4 ? $bb->abr : 0) +  ($anoF != 5 ? $bb->may : 0) +  ($anoF != 6 ? $bb->jun : 0) +  ($anoF != 7 ? $bb->jul : 0) +  ($anoF != 8 ? $bb->ago : 0) + ($anoF != 9 ? $bb->set : 0) + ($anoF != 10 ? $bb->oct : 0) +  ($anoF != 11 ? $bb->nov : 0) + ($anoF != 12 ? $bb->dic : 0);
            foreach ($headA as $key2 => $bA) {

                if ($bA->tipo == $bb->tipo)
                    $bb->tregA = $bA->dic;
            }
            $bb->avance = $bb->tregA > 0 ? $bb->treg / $bb->tregA : 1;
        }

        /* $error['head'] = $head; */

        //return $error;
        return view("educacion.MatriculaDetalle.MatriculaAvancetabla1", compact('rq', 'head', 'base', 'anoI', 'anoF', 'foot', 'anos'));
    }

    public function cargargrafica1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

        $error['ano'] = $ano;
        $error['gestion'] = $gestion;
        $error['area'] = $area;

        $anios = Anio::orderBy('anio', 'desc')->get();
        $anonro = 0;
        $anoA = 0;
        foreach ($anios as $key => $value) {
            if ($value->id == $ano) $anonro = $value->anio - 1;
            if ($value->anio == $anonro) $anoA = $value->id;
        }
        $error['anios'] = $anios;
        $error['anonro'] = $anonro;
        $error['anoA'] = $anoA;

        $fechas = DB::table(DB::raw("(
            select mes, max(fecha) fecha from (
                select
                    distinct
                    v3.fechaActualizacion fecha,
                    year(v3.fechaActualizacion) ano,
                    month(v3.fechaActualizacion) mes,
                    day(v3.fechaActualizacion) dia
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join par_anio as v4 on v4.id=v2.anio_id
                where v3.estado='PR' and v2.anio_id=$ano
                order by fecha desc
            ) as xx
            group by mes
            order by mes asc
                ) as xx"))->get();

        $error['fechas'] = $fechas;

        $fx = '';
        $anoI = 0;
        $anoF = 0;
        foreach ($fechas as $key => $value) {
            if ($key < count($fechas) - 1)
                $fx .= "'$value->fecha',";
            else
                $fx .= "'$value->fecha'";
            if ($key == 0) $anoI = $value->mes;
            if ($key == (count($fechas) - 1)) $anoF = $value->mes + 1;
        }

        $error['fx'] = $fx;
        $error['anoI'] = $anoI;
        $error['anoF'] = $anoF;

        $base = DB::table(DB::raw("(
            select
                month(v3.fechaActualizacion) mes,
                case month(v3.fechaActualizacion)
                    WHEN 1 THEN 'ENERO'
                    WHEN 2 THEN 'FEBRERO'
                    WHEN 3 THEN 'MARZO'
                    WHEN 4 THEN 'ABRIL'
                    WHEN 5 THEN 'MAYO'
                    WHEN 6 THEN 'JUNIO'
                    WHEN 7 THEN 'JULIO'
                    WHEN 8 THEN 'AGOSTO'
                    WHEN 9 THEN 'SETIEMBRE'
                    WHEN 10 THEN 'OCTUBRE'
                    WHEN 11 THEN 'NOVIEMBRE'
                    WHEN 12 THEN 'DICIEMBRE'
                END AS name,
            sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) y
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR','EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by mes,name
            order by mes asc
            ) as xx"))->get();
        $error['base'] = $base;
        /* foreach ($base as $key => $value) {
            $value->y = (int)$value->y;
        } */
        $data['cat'] = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        //$data['dat'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $data['dat'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $data['dat'][$value->mes - 1] = (int)$value->y;
        }
        return $data;
        /* return $error; */
    }

    /*  */
    public function vista0001() //viene de indicadorcontroller como panelControlEduacionNuevoindicador01
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);

        $strSiagie = strtotime($imp->fecha);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $provincia = UbigeoRepositorio::provincia('25');

        return  view('parametro.indicador.educacion.inicioEducacionIndicador01', compact('anios', 'aniomax', 'provincia', 'actualizado',));
    }

    public function vista0001head(Request $rq) //viene de indicadorcontroller como panelControlEduacionNuevoindicador01
    {
        $xx = MatriculaGeneralRepositorio::indicador01head($rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area);
        $valor1 = $xx->basica;
        $valor2 = $xx->ebr;
        $valor3 = $xx->ebe;
        $valor4 = $xx->eba;
        $aa = Anio::find($rq->anio);
        $aav =  -1 + (int)$aa->anio;
        $aa = Anio::where('anio', $aav)->first();
        $xx = MatriculaGeneralRepositorio::indicador01head($aa->id, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area);
        $valor1x = $xx->basica;
        $valor2x = $xx->ebr;
        $valor3x = $xx->ebe;
        $valor4x = $xx->eba;

        $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 0, 1);
        $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 0, 1);
        $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 0, 1);
        $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 0, 1);

        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);

        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
    }

    public function vista0001Tabla(Request $rq) //viene de indicadorcontroller como panelControlEduacionNuevoindicador01
    {
        switch ($rq->div) {
            case 'anal1':
                $datax = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $info['series'] = [];
                $alto = 0;
                $btotal = 0;
                //$dx2 = [];
                $dx3 = [];
                $dx4 = [];
                foreach ($datax as $key => $value) {
                    //$dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($datax as $keyi => $ii) {
                    $info['categoria'][] = $ii->anio;
                    $n = (int)$ii->suma;
                    $d = $ii->anio == 2018 ? $n : (int)$datax[$keyi - 1]['suma'];
                    //$dx2[$keyi] = $d;
                    $dx3[$keyi] = $n;
                    $dx4[$keyi] = $d > 0 ? round(100 * $n / $d, 1) : 100;
                    $alto = $n > $alto ? $n : $alto;
                }
                //$alto = 0;
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados', 'data' => $dx2];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Avance', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $periodo = Mes::select('codigo', 'abreviado as mes', DB::raw('0 as conteo'))->get();
                $datax = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $info['cat'] = [];
                $info['dat'] = [];
                $mesmax = $datax->max('mes');
                foreach ($periodo as $key => $pp) {
                    $info['cat'][$key] = $pp->mes;
                    if ($pp->codigo > $mesmax) {
                        $info['dat'][$key] = null;
                    } else {
                        $info['dat'][$key] = 0;
                        foreach ($datax as $dd) {
                            if ($dd->mes == $pp->codigo) {
                                $info['dat'][$key] = $key > 0 ? $info['dat'][$key - 1] + $dd->conteo : $dd->conteo;
                                break;
                            }
                        }
                    }
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal3':
                $info = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal4':
                $info = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaUgel($anioy->id, $rq->provincia, $rq->distrito,  $rq->gestion, 0);
                $base = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area, 0);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->ugel == $mm->ugel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                $excel = view('parametro.indicador.educacion.inicioEducacionIndicador01Table1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));

            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaNivel($anioy->id, $rq->provincia, $rq->distrito,  $rq->gestion,  $rq->ugel);
                $base = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, $rq->area,  $rq->ugel);
                $head = [];
                $foot = [];
                if ($base->count() > 0) {
                    $ii = 0;
                    foreach ($base->unique('tipo')->sortByDesc('tipo') as $key => $value) {
                        $head[$ii++] = clone $value;
                    }
                    foreach ($head as $key => $value) {
                        $value->meta = 0;
                        $value->ene = 0;
                        $value->feb = 0;
                        $value->mar = 0;
                        $value->abr = 0;
                        $value->may = 0;
                        $value->jun = 0;
                        $value->jul = 0;
                        $value->ago = 0;
                        $value->sep = 0;
                        $value->oct = 0;
                        $value->nov = 0;
                        $value->dic = 0;
                    }

                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;


                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->nivel == $mm->nivel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;

                        foreach ($head as $key => $hh) {
                            if ($hh->tipo == $value->tipo) {
                                $hh->meta += $value->meta;
                                $hh->ene += $value->ene;
                                $hh->feb += $value->feb;
                                $hh->mar += $value->mar;
                                $hh->abr += $value->abr;
                                $hh->may += $value->may;
                                $hh->jun += $value->jun;
                                $hh->jul += $value->jul;
                                $hh->ago += $value->ago;
                                $hh->sep += $value->sep;
                                $hh->oct += $value->oct;
                                $hh->nov += $value->nov;
                                $hh->dic += $value->dic;
                            }
                        }

                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    foreach ($head as $key => $hh) {
                        $hh->total = $hh->ene + $hh->feb + $hh->mar + $hh->abr + $hh->may + $hh->jun + $hh->jul + $hh->ago + $hh->sep + $hh->oct + $hh->nov + $hh->dic;
                        $hh->avance = $hh->meta > 0 ? 100 * $hh->total / $hh->meta : 100;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                $excel = view('parametro.indicador.educacion.inicioEducacionIndicador01Table2', compact('base', 'foot', 'head'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            default:
                return [];
        }
    }

    public function vista0001Export($div, $anio, $provincia, $distrito, $gestion, $ugel) //viene de indicadorcontroller como panelControlEduacionNuevoindicador01
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaUgel($anioy->id, $provincia, $distrito,  $gestion, 0);
                $base = MatriculaGeneralRepositorio::indicador01tabla($div, $anio, $provincia, $distrito,  $gestion, 0, 0);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->ugel == $mm->ugel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                return compact('base', 'foot');

            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaNivel($anioy->id, $provincia, $distrito,  $gestion,  $ugel);
                $base = MatriculaGeneralRepositorio::indicador01tabla($div, $anio, $provincia, $distrito,  $gestion, 0,  $ugel);
                $head = [];
                $foot = [];
                if ($base->count() > 0) {
                    $ii = 0;
                    foreach ($base->unique('tipo')->sortByDesc('tipo') as $key => $value) {
                        $head[$ii++] = clone $value;
                    }
                    foreach ($head as $key => $value) {
                        $value->meta = 0;
                        $value->ene = 0;
                        $value->feb = 0;
                        $value->mar = 0;
                        $value->abr = 0;
                        $value->may = 0;
                        $value->jun = 0;
                        $value->jul = 0;
                        $value->ago = 0;
                        $value->sep = 0;
                        $value->oct = 0;
                        $value->nov = 0;
                        $value->dic = 0;
                    }

                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;


                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->nivel == $mm->nivel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;

                        foreach ($head as $key => $hh) {
                            if ($hh->tipo == $value->tipo) {
                                $hh->meta += $value->meta;
                                $hh->ene += $value->ene;
                                $hh->feb += $value->feb;
                                $hh->mar += $value->mar;
                                $hh->abr += $value->abr;
                                $hh->may += $value->may;
                                $hh->jun += $value->jun;
                                $hh->jul += $value->jul;
                                $hh->ago += $value->ago;
                                $hh->sep += $value->sep;
                                $hh->oct += $value->oct;
                                $hh->nov += $value->nov;
                                $hh->dic += $value->dic;
                            }
                        }

                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    foreach ($head as $key => $hh) {
                        $hh->total = $hh->ene + $hh->feb + $hh->mar + $hh->abr + $hh->may + $hh->jun + $hh->jul + $hh->ago + $hh->sep + $hh->oct + $hh->nov + $hh->dic;
                        $hh->avance = $hh->meta > 0 ? 100 * $hh->total / $hh->meta : 100;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                return compact('head', 'base', 'foot');
            default:
                return [];
        }
    }

    public function vista0001Download($div, $anio, $provincia, $distrito, $gestion, $ugel) //viene de indicadorcontroller como panelControlEduacionNuevoindicador01
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Avance_Matricula_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new AvanceMatricula1Export($div, $anio, $provincia, $distrito, $gestion, $ugel), $name);
            } else {
                $name = 'Avance_Matricula_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new AvanceMatricula1Export($div, $anio, $provincia, $distrito, $gestion, $ugel), $name);
            }
        }
    }

    /*  */

    public function basicaregularx()
    {
        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $ugels = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        $gestions = [["id" => 21, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        $areas = Area::select('id', 'nombre')->get();

        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '8')->orderBy('fecha', 'desc')->take(1)->get();
        $importacion_id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));
        return view("educacion.MatriculaGeneral.BasicaRegular", compact('anios', 'aniomax', 'gestions', 'areas', 'ugels', 'importacion_id', 'fecha'));
    }

    public function basicaregulartablax(Request $rq)
    {
        switch ($rq->div) {
            case 'gra1':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = [];
                }
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key][] = $value->conteo;
                        }
                        /* foreach ($info['cat'] as $cat) {
                            if ($value->anio == $cat && $value->nivel == $dat) {
                                $xx[$key][] = $value->conteo;
                            }
                        } */
                    }
                }
                $info['dat'] = [];
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$key]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));

            case 'gra2':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                $xx = 0;
                foreach ($data as $key => $value) {
                    $info['cat'][] = $this->mess[$value->mes - 1];
                    $xx += $value->conteo;
                    $info['dat'][] = $xx;
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg', 'data'));

            case 'gra3':
                $info = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                foreach ($info as $key => $value) {
                    $value->yx = number_format($value->yx, 0);
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));

            case 'gra4':
                $info = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                foreach ($info as $key => $value) {
                    $value->yx = number_format($value->yx, 0);
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));
            case 'vista1':
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->ths = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    foreach ($base as $key => $value) {
                        $value->ttp = $foot->tt > 0 ? 100 * $value->tt / $foot->tt : 0;
                    }
                    $foot->ttp = 100;
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return view('educacion.MatriculaGeneral.BasicaRegularTabla1', compact('base', 'foot'))->render();
                //return response()->json(compact('base', 'foot', 'reg'));
            case 'vista2':
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    foreach ($base as $key => $value) {
                        $value->ttp = $foot->tt > 0 ? 100 * $value->tt / $foot->tt : 0;
                    }
                    $foot->ttp = 100;
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return view('educacion.MatriculaGeneral.BasicaRegularTabla2', compact('base', 'foot'))->render();
                //return response()->json(compact('base', 'foot', 'reg'));
            case 'vista1i':
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->cih = 0;
                    $foot->cim = 0;
                    $foot->cii3h = 0;
                    $foot->cii3m = 0;
                    $foot->cii4h = 0;
                    $foot->cii4m = 0;
                    $foot->cii5h = 0;
                    $foot->cii5m = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->cih += $value->cih;
                        $foot->cim += $value->cim;
                        $foot->cii3h += $value->cii3h;
                        $foot->cii3m += $value->cii3m;
                        $foot->cii4h += $value->cii4h;
                        $foot->cii4m += $value->cii4m;
                        $foot->cii5h += $value->cii5h;
                        $foot->cii5m += $value->cii5m;
                    }
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return view('educacion.MatriculaGeneral.BasicaRegularTablai1', compact('base', 'foot'))->render();
                //return response()->json(compact('base', 'foot', 'reg'));
            case 'vista2i':
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $head = [];
                $foot = [];
                if ($base->count() > 0) {
                    //$hxx = clone $base;
                    //$head = $hxx->unique('ugel'); //->sortByDesc('tt');
                    foreach ($base as $key => $value) {
                        $i = 0;
                        foreach ($base->unique('ugel') as $key => $value) {
                            $head[$i++] = clone $value;
                        }
                    }
                    foreach ($head as $key => $value) {
                        $value->tt = 0;
                        $value->th = 0;
                        $value->tm = 0;
                        $value->cih = 0;
                        $value->cim = 0;
                        $value->cii3h = 0;
                        $value->cii3m = 0;
                        $value->cii4h = 0;
                        $value->cii4m = 0;
                        $value->cii5h = 0;
                        $value->cii5m = 0;
                    }

                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->cih = 0;
                    $foot->cim = 0;
                    $foot->cii3h = 0;
                    $foot->cii3m = 0;
                    $foot->cii4h = 0;
                    $foot->cii4m = 0;
                    $foot->cii5h = 0;
                    $foot->cii5m = 0;
                    foreach ($base as $key => $value) {
                        foreach ($head as $hh) {
                            if ($value->ugel == $hh->ugel) {
                                $hh->tt += $value->tt;
                                $hh->th += $value->th;
                                $hh->tm += $value->tm;
                                $hh->cih += $value->cih;
                                $hh->cim += $value->cim;
                                $hh->cii3h += $value->cii3h;
                                $hh->cii3m += $value->cii3m;
                                $hh->cii4h += $value->cii4h;
                                $hh->cii4m += $value->cii4m;
                                $hh->cii5h += $value->cii5h;
                                $hh->cii5m += $value->cii5m;
                            }
                        }

                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->cih += $value->cih;
                        $foot->cim += $value->cim;
                        $foot->cii3h += $value->cii3h;
                        $foot->cii3m += $value->cii3m;
                        $foot->cii4h += $value->cii4h;
                        $foot->cii4m += $value->cii4m;
                        $foot->cii5h += $value->cii5h;
                        $foot->cii5m += $value->cii5m;
                    }
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return view('educacion.MatriculaGeneral.BasicaRegularTablai2', compact('base', 'foot', 'head'))->render();
                // return response()->json(compact('base', 'foot', 'reg', 'head'));
            case 'vista3i':
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->cih = 0;
                    $foot->cim = 0;
                    $foot->cii3h = 0;
                    $foot->cii3m = 0;
                    $foot->cii4h = 0;
                    $foot->cii4m = 0;
                    $foot->cii5h = 0;
                    $foot->cii5m = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->cih += $value->cih;
                        $foot->cim += $value->cim;
                        $foot->cii3h += $value->cii3h;
                        $foot->cii3m += $value->cii3m;
                        $foot->cii4h += $value->cii4h;
                        $foot->cii4m += $value->cii4m;
                        $foot->cii5h += $value->cii5h;
                        $foot->cii5m += $value->cii5m;
                    }
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return view('educacion.MatriculaGeneral.BasicaRegularTablai3', compact('base', 'foot'))->render();
                // return response()->json(compact('base', 'foot', 'reg'));
            default:
                break;
        }
    }

    public function basicaregular()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        //$provincia = UbigeoRepositorio::provincia25();
        $ugel = MatriculaGeneralRepositorio::ugels();
        $area = MatriculaGeneralRepositorio::areas();

        $fecha = '';

        return view('educacion.MatriculaGeneral.BasicaRegular', compact('anios', 'aniomax', 'actualizado', 'ugel', 'area', 'fecha'));
    }

    public function basicaregularhead(Request $rq) //eliminar
    {
        $xx = MatriculaGeneralRepositorio::indicador01head($rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 1);
        $valor1 = $xx->basica;
        $valor2 = $xx->ebr;
        $valor3 = $xx->ebe;
        $valor4 = $xx->eba;
        $aa = Anio::find($rq->anio);
        $aav =  -1 + (int)$aa->anio;
        $aa = Anio::where('anio', $aav)->first();
        $xx = MatriculaGeneralRepositorio::indicador01head($aa->id, $rq->provincia, $rq->distrito,  $rq->gestion, 1);
        $valor1x = $xx->basica;
        $valor2x = $xx->ebr;
        $valor3x = $xx->ebe;
        $valor4x = $xx->eba;

        $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 0, 1);
        $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 0, 1);
        $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 0, 1);
        $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 0, 1);

        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);

        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
    }

    public function basicaregulartabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh = MatriculaGeneralRepositorio::basicaregulartabla('mhead', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $valor1 = (int)$mh->conteo;
                $valor2 = (int)$mh->conteoi;
                $valor3 = (int)$mh->conteop;
                $valor4 = (int)$mh->conteos;
                $aa = Anio::find($rq->anio);
                $aav =  -1 + (int)$aa->anio;
                $aa = Anio::where('anio', $aav)->first();
                $mh = MatriculaGeneralRepositorio::metaEBR($rq->anio == 3 ? 3 : $aa->id, $rq->ugel, $rq->gestion,  $rq->area);
                $valor1x = (int)$mh->conteo;
                $valor2x = (int)$mh->conteoi;
                $valor3x = (int)$mh->conteop;
                $valor4x = (int)$mh->conteos;

                $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 0, 1);
                $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 0, 1);
                $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 0, 1);
                $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 0, 1);

                $valor1 = number_format($valor1, 0);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
            case 'anal1':
                $datax = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['series'] = [];
                $alto = 0;
                $btotal = 0;
                $anioi = 0;
                $aniof = 0;
                foreach ($datax as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($datax as $keyi => $ii) {
                    $info['categoria'][] = $ii->anio;
                    $n = (int)$ii->conteo;
                    $d = $ii->anio == 2018 ? $n : (int)$datax[$keyi - 1]->conteo;
                    $dx3[$keyi] = $n;
                    $dx4[$keyi] = $d > 0 ? round(100 * $n / $d, 1) : 0;
                    $alto = $n > $alto ? $n : $alto;
                    if ($keyi == 0) $anioi = $ii->anio;
                    if ($keyi == $datax->count() - 1) $aniof = $ii->anio;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Avance', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;

                $reg['fuente'] = 'Siagie - MINEDU';
                $reg['periodo'] = "$anioi - $aniof";
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $periodo = Mes::select('codigo', 'abreviado as mes', DB::raw('0 as conteo'))->get();
                $datax = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                $mesmax = $datax->max('mes');
                foreach ($periodo as $key => $pp) {
                    $info['cat'][$key] = $pp->mes;
                    if ($pp->codigo > $mesmax) {
                        $info['dat'][$key] = null;
                    } else {
                        $info['dat'][$key] = 0;
                        foreach ($datax as $dd) {
                            if ($dd->mes == $pp->codigo) {
                                $info['dat'][$key] = $key > 0 ? $info['dat'][$key - 1] + $dd->conteo : $dd->conteo;
                                break;
                            }
                        }
                    }
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'datax'));
            case 'anal3':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = [];
                }
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key][] = $value->conteo;
                        }
                    }
                }
                $info['dat'] = [];
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$key]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal4':
                $info = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal5':
                $info = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                }
                $ii = 0;
                foreach ($data->unique('eib') as $key => $value) {
                    $xx[$value->eib] = [];
                    $xa[$ii++] = $value->eib;
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => []];
                }
                foreach ($data as $key => $value) {
                    $xx[$value->eib][] = $value->conteo;
                }

                $xy = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $va = $xx[$xa[0]][$key];
                    $vb = $xx[$xa[1]][$key];
                    $vap = round(100 * $va / ($va + $vb), 0);
                    $vbp = round(100 * $vb / ($va + $vb), 0);
                    $xy[$xa[0]][$key] = $vap;
                    $xy[$xa[1]][$key] = $vbp;
                }

                $info['series'] = [];
                foreach ($data->unique('eib') as $key => $value) {
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => $xy[$value->eib]];
                }
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));

            case 'anal7':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal8':
                $data = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRProvincia($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla1', compact('base', 'foot'))->render();

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla1x':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRProvincia($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRDistrito($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }

                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla2', compact('base', 'foot'))->render();
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla2x':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRDistrito($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }

                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla2', compact('base', 'foot'))->render();
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla3':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRCentroPoblado($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->centropoblado == $mm->centropoblado) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla3', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));

            case 'tabla3x':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRCentroPoblado($rq->anio == 3 ? 3 : $anioy->id, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area, $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->centropoblado == $mm->centropoblado) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                $excel = view('educacion.MatriculaGeneral.BasicaRegularTabla3', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));
            default:
                return [];
        }
    }

    public function basicaregulartablaExport($div, $anio, $ugel, $gestion, $area, $provincia)
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRProvincia($anio == 3 ? 3 : $anioy->id, $ugel, $gestion,  $area);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($div, $anio, $ugel, $gestion,  $area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return compact('base', 'foot');

            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRDistrito($anio == 3 ? 3 : $anioy->id, $ugel, $gestion,  $area, $provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($div, $anio, $ugel, $gestion,  $area, $provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');

            case 'tabla3':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRCentroPoblado($anio == 3 ? 3 : $anioy->id, $ugel, $gestion,  $area, $provincia);
                $base = MatriculaGeneralRepositorio::basicaregulartabla($div, $anio, $ugel, $gestion,  $area, $provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->ci = 0;
                    $foot->cii = 0;
                    $foot->ciii = 0;
                    $foot->civ = 0;
                    $foot->cv = 0;
                    $foot->cvi = 0;
                    $foot->cvii = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->centropoblado == $mm->centropoblado) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 0;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->ci += $value->ci;
                        $foot->cii += $value->cii;
                        $foot->ciii += $value->ciii;
                        $foot->civ += $value->civ;
                        $foot->cv += $value->cv;
                        $foot->cvi += $value->cvi;
                        $foot->cvii += $value->cvii;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');
            default:
                return [];
        }
    }
    public function basicaregularDownload($div, $anio, $ugel, $gestion, $area, $provincia)
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Basica_regular_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaRegularExport($div, $anio, $ugel, $gestion, $area, $provincia), $name);
            }
            if ($div == 'tabla2') {
                $name = 'Basica_regular_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaRegularExport($div, $anio, $ugel, $gestion, $area, $provincia), $name);
            } else {
                $name = 'Basica_regular_CentroPoblado_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaRegularExport($div, $anio, $ugel, $gestion, $area, $provincia), $name);
            }
        }
    }

    public function basicaespecial()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);

        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $distrito = MatriculaGeneralRepositorio::distritosEBE(0);
        $ugel = MatriculaGeneralRepositorio::ugelsEBE();
        $dependencia = MatriculaGeneralRepositorio::dependencia(0);

        $fecha = '';

        return view('educacion.MatriculaGeneral.BasicaEspecial', compact('actualizado', 'anios', 'aniomax',  'ugel', 'distrito', 'dependencia', 'fecha'));
    }

    public function basicaespecialtabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh = MatriculaGeneralRepositorio::basicaespecialtabla('mhead', $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $valor1 = (int)$mh->conteo;
                $valor2 = (int)$mh->conteox;
                $valor3 = (int)$mh->conteoi;
                $valor4 = (int)$mh->conteop;
                $aa = Anio::find($rq->anio);
                $aav =  -1 + (int)$aa->anio;
                $aa = Anio::where('anio', $aav)->first();
                $mh = MatriculaGeneralRepositorio::metaEBE($rq->anio == 3 ? 3 : $aa->id,  $rq->ugel, $rq->distrito,  $rq->dependencia);
                $valor1x = (int)$mh->conteo;
                $valor2x = (int)$mh->conteox;
                $valor3x = (int)$mh->conteoi;
                $valor4x = (int)$mh->conteop;

                $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 100, 1);
                $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 100, 1);
                $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 100, 1);
                $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 100, 1);

                $valor1 = number_format($valor1, 0);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
            case 'anal1':
                $datax = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $info['series'] = [];
                $alto = 0;
                $btotal = 0;
                foreach ($datax as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($datax as $keyi => $ii) {
                    $info['categoria'][] = $ii->anio;
                    $n = (int)$ii->conteo;
                    $d = $ii->anio == $datax[0]->anio ? $n : (int)$datax[$keyi - 1]->conteo;
                    $dx3[$keyi] = $n;
                    $dx4[$keyi] = $d > 0 ? round(100 * $n / $d, 1) : 0;
                    $alto = $n > $alto ? $n : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Avance', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $periodo = Mes::select('codigo', 'abreviado as mes', DB::raw('0 as conteo'))->get();
                $datax = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $info['cat'] = [];
                $info['dat'] = [];
                $mesmax = $datax->max('mes');
                foreach ($periodo as $key => $pp) {
                    $info['cat'][$key] = $pp->mes;
                    if ($pp->codigo > $mesmax) {
                        $info['dat'][$key] = null;
                    } else {
                        $info['dat'][$key] = 0;
                        foreach ($datax as $dd) {
                            if ($dd->mes == $pp->codigo) {
                                $info['dat'][$key] = $key > 0 ? $info['dat'][$key - 1] + $dd->conteo : $dd->conteo;
                                break;
                            }
                        }
                    }
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'datax'));
            case 'anal3':
                $data = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $info['cat'] = [];
                $info['dat'] = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                    $xa[$value->anio] = $ix++;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = array_fill(0, count($xa), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key][$xa[$value->anio]] = $value->conteo;
                        }
                    }
                }
                $info['dat'] = [];
                $ii = 0;
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$ii++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal4':
                $info = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div, $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal5':
                $info = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div, $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                }
                $ii = 0;
                foreach ($data->unique('eib') as $key => $value) {
                    $xx[$value->eib] = [];
                    $xa[$ii++] = $value->eib;
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => []];
                }
                foreach ($data as $key => $value) {
                    $xx[$value->eib][] = $value->conteo;
                }

                $xy = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $va = $xx[$xa[0]][$key];
                    $vb = $xx[$xa[1]][$key];
                    $vap = round(100 * $va / ($va + $vb), 0);
                    $vbp = round(100 * $vb / ($va + $vb), 0);
                    $xy[$xa[0]][$key] = $vap;
                    $xy[$xa[1]][$key] = $vbp;
                }

                $info['series'] = [];
                foreach ($data->unique('eib') as $key => $value) {
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => $xy[$value->eib]];
                }
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));

            case 'anal7':
                $data = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal8':
                $data = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEProvincia($rq->anio == 3 ? 3 : $anioy->id,  $rq->ugel, $rq->distrito,  $rq->dependencia);
                $base = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                $excel = view('educacion.MatriculaGeneral.basicaespecialTabla1', compact('base', 'foot'))->render();
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEDistrito($rq->anio == 3 ? 3 : $anioy->id,  $rq->ugel, $rq->distrito,  $rq->dependencia,  $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaespecialtabla($rq->div,  $rq->anio, $rq->ugel, $rq->distrito,  $rq->dependencia,  $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                $excel = view('educacion.MatriculaGeneral.basicaespecialTabla2', compact('base', 'foot'))->render();

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            default:
                return [];
        }
    }

    public function basicaespecialtablaExport($div, $anio, $ugel, $distrito, $dependencia, $provincia)
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEProvincia($anio == 3 ? 3 : $anioy->id,  $ugel, $distrito, $dependencia);
                $base = MatriculaGeneralRepositorio::basicaespecialtabla($div,  $anio, $ugel, $distrito, $dependencia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return compact('base', 'foot');
            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEDistrito($anio == 3 ? 3 : $anioy->id,  $ugel, $distrito, $dependencia, $provincia);
                $base = MatriculaGeneralRepositorio::basicaespecialtabla($div,  $anio, $ugel, $distrito, $dependencia, $provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return compact('base', 'foot');

            default:
                return [];
        }
    }

    public function basicaespecialDownload($div,  $anio, $ugel, $distrito, $dependencia, $provincia)
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Basica_especial_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaEspecialExport($div,  $anio, $ugel, $distrito, $dependencia, $provincia), $name);
            } else {
                $name = 'Basica_especial_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaEspecialExport($div,  $anio, $ugel, $distrito, $dependencia, $provincia), $name);
            }
        }
    }

    public function basicaalternativa()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::aniosModalidad('EBA');
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        //$provincia = UbigeoRepositorio::provincia25();
        $ugel = MatriculaGeneralRepositorio::ugels();
        $area = MatriculaGeneralRepositorio::areas();

        $fecha = '';

        return view('educacion.MatriculaGeneral.basicaalternativa', compact('anios', 'aniomax', 'actualizado', 'ugel', 'area', 'fecha'));
    }

    public function basicaalternativatabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh = MatriculaGeneralRepositorio::basicaalternativatabla('mhead', $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $valor1 = (int)$mh->conteo;
                $valor2 = (int)$mh->conteox;
                $valor3 = (int)$mh->conteoi;
                $valor4 = (int)$mh->conteop;
                $aa = Anio::find($rq->anio);
                $aav =  -1 + (int)$aa->anio;
                $aa = Anio::where('anio', $aav)->first();
                $mh = MatriculaGeneralRepositorio::metaEBA($aa->id, $rq->ugel, $rq->gestion,  $rq->area);
                $valor1x = (int)$mh->conteo;
                $valor2x = (int)$mh->conteox;
                $valor3x = (int)$mh->conteoi;
                $valor4x = (int)$mh->conteop;

                $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 100, 1);
                $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 100, 1);
                $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 100, 1);
                $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 100, 1);

                $valor1 = number_format($valor1, 0);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
            case 'anal1':
                $datax = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['series'] = [];
                $alto = 0;
                $btotal = 0;
                foreach ($datax as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($datax as $keyi => $ii) {
                    $info['categoria'][] = $ii->anio;
                    $n = (int)$ii->conteo;
                    $d = $ii->anio == $datax[0]->anio ? $n : (int)$datax[$keyi - 1]->conteo;
                    $dx3[$keyi] = $n;
                    $dx4[$keyi] = $d > 0 ? round(100 * $n / $d, 1) : 0;
                    $alto = $n > $alto ? $n : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Avance', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                $reg['periodo'] = '' . $datax[0]->anio . ' - ' . $datax[$datax->count() - 1]->anio;
                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $periodo = Mes::select('codigo', 'abreviado as mes', DB::raw('0 as conteo'))->get();
                $datax = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                $mesmax = $datax->max('mes');
                foreach ($periodo as $key => $pp) {
                    $info['cat'][$key] = $pp->mes;
                    if ($pp->codigo > $mesmax) {
                        $info['dat'][$key] = null;
                    } else {
                        $info['dat'][$key] = 0;
                        foreach ($datax as $dd) {
                            if ($dd->mes == $pp->codigo) {
                                $info['dat'][$key] = $key > 0 ? $info['dat'][$key - 1] + $dd->conteo : $dd->conteo;
                                break;
                            }
                        }
                    }
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'datax'));
            case 'anal3':
                $data = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['cat'] = [];
                $info['dat'] = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('anio') as $key => $value) {
                    $info['cat'][] = $value->anio;
                    $xa[$value->anio] = $ix++;
                }
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => []];
                    $xx[] = [];
                }
                $xx[0] = [0, 0];
                $xx[1] = [0, 0];
                $xx[2] = [0, 0];

                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->nivel == $dat['name']) {
                            $xx[$key][$xa[$value->anio]] = $value->conteo;
                        }
                    }
                }
                $info['dat'] = [];
                $ii = 0;
                foreach ($data->unique('nivel') as $key => $value) {
                    $info['dat'][] = ["name" => $value->nivel, "data" => $xx[$ii++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal4':
                $info = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal5':
                $info = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                }
                $ii = 0;
                foreach ($data->unique('eib') as $key => $value) {
                    $xx[$value->eib] = [];
                    $xa[$ii++] = $value->eib;
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => []];
                }
                foreach ($data as $key => $value) {
                    $xx[$value->eib][] = $value->conteo;
                }

                $xy = [];
                foreach ($data->unique('ugel') as $key => $value) {
                    $va = $xx[$xa[0]][$key];
                    $vb = $xx[$xa[1]][$key];
                    $vap = round(100 * $va / ($va + $vb), 0);
                    $vbp = round(100 * $vb / ($va + $vb), 0);
                    $xy[$xa[0]][$key] = $vap;
                    $xy[$xa[1]][$key] = $vbp;
                }

                $info['series'] = [];
                foreach ($data->unique('eib') as $key => $value) {
                    $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => $value->eib,  'data' => $xy[$value->eib]];
                }
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));

            case 'anal7':
                $data = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal8':
                $data = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $info['categoria'] = [];
                $info['series'] = [];
                $hh = [];
                $mm = [];
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $hh[] = (int)$value->th;
                    $mm[] = (int)$value->tm;
                }
                $info['series'][] = ['name' => 'HOMBRE', 'data' => $hh];
                $info['series'][] = ['name' => 'MUJER', 'data' => $mm];

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBAProvincia($anioy->id, $rq->ugel, $rq->gestion,  $rq->area);
                $base = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                $excel = view('educacion.MatriculaGeneral.basicaalternativaTabla1', compact('base', 'foot'))->render();
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBADistrito($anioy->id, $rq->ugel, $rq->gestion,  $rq->area,  $rq->provincia);
                $base = MatriculaGeneralRepositorio::basicaalternativatabla($rq->div, $rq->anio, $rq->ugel, $rq->gestion,  $rq->area,  $rq->provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                $excel = view('educacion.MatriculaGeneral.basicaalternativaTabla2', compact('base', 'foot'))->render();

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('excel', 'reg'));
            default:
                return [];
        }
    }

    public function basicaalternativatablaExport($div, $anio, $ugel, $gestion, $area, $provincia)
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBAProvincia($anioy->id, $ugel, $gestion,  $area);
                $base = MatriculaGeneralRepositorio::basicaalternativatabla($div, $anio, $ugel, $gestion,  $area);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->provincia == $mm->provincia) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return compact('base', 'foot');
            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBADistrito($anioy->id, $ugel, $gestion,  $area, $provincia);
                $base = MatriculaGeneralRepositorio::basicaalternativatabla($div, $anio, $ugel, $gestion,  $area, $provincia);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->thi = 0;
                    $foot->tmi = 0;
                    $foot->thp = 0;
                    $foot->tmp = 0;
                    $foot->ths = 0;
                    $foot->tms = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->distrito == $mm->distrito) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->thi += $value->thi;
                        $foot->tmi += $value->tmi;
                        $foot->thp += $value->thp;
                        $foot->tmp += $value->tmp;
                        $foot->ths += $value->ths;
                        $foot->tms += $value->tms;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return compact('base', 'foot');
            default:
                return [];
        }
    }
    public function basicaalternativaDownload($div, $anio, $ugel, $gestion, $area, $provincia)
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Basica_Alternativa_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaAlternativaExport($div, $anio, $ugel, $gestion, $area, $provincia), $name);
            } else {
                $name = 'Basica_Alternativo_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new BasicaAlternativaExport($div, $anio, $ugel, $gestion, $area, $provincia), $name);
            }
        }
    }

    /*  */

    public function niveleducativoEBR()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $provincia = UbigeoRepositorio::provincia('25');
        //$ugel = MatriculaGeneralRepositorio::ugels();
        //$area = MatriculaGeneralRepositorio::areas();
        $nivel = NivelModalidad::select('id', 'codigo', DB::raw('case when codigo="A2" then "JARDÍN" when codigo="A3" then "CUNA-JARDÍN" when codigo="A5" then "PRONOEI" else upper(nombre) end as nombre'))->where('tipo', 'EBR')->orderBy('nombre')->get();

        $fecha = '';

        return view('educacion.MatriculaGeneral.NivelEducativo', compact('anios', 'aniomax', 'actualizado', 'provincia', 'nivel', 'fecha'));
    }

    public function niveleducativoEBRtabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh1 = MatriculaGeneralRepositorio::niveleducativoEBRtabla('head1', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh2 = MatriculaGeneralRepositorio::niveleducativoEBRtabla('head2', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh3 = MatriculaGeneralRepositorio::niveleducativoEBRtabla('head3', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh4 = MatriculaGeneralRepositorio::niveleducativoEBRtabla('head4', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $valor1 = (int)$mh1->conteo;
                $valor2 = (int)$mh2->conteo;
                $valor3 = (int)$mh3->conteo;
                $valor4 = (int)$mh4->conteo;

                $valor1 = number_format($valor1, 0);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
            case 'anal1':
                $data = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                    $xa[$value->ugel] = $ix++;
                }
                $ii = 0;
                foreach ($data->unique('eib') as $key => $value) {
                    $info['series'][] = ['name' => $value->eib,  'data' => []];
                    $xx[] = array_fill(0, $data->unique('ugel')->count(), 0);
                }

                foreach ($data as $key => $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->eib == $dat['name']) {
                            $xx[$key][$xa[$value->ugel]] = $value->conteo;
                        }
                    }
                }

                $info['series'] = [];
                $ix = 0;
                foreach ($data->unique('eib') as $key => $value) {
                    $info['series'][] = ['name' => $value->eib,  'data' => $xx[$ix++]];
                }
                // return compact('data', 'info', 'xx', 'xa');
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $info = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal3':
                $data = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['cat'] = [];
                $info['dat'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('grupos') as $key => $value) {
                    $info['cat'][] = $value->grupos;
                    $xa[$value->grupos] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['dat'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('grupos')->count(), 0);
                }
                // return compact('data', 'info');
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->grupos]] = $value->conteo;
                        }
                    }
                }
                // return compact('data', 'info', 'xx', 'xx');
                $info['dat'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['dat'][] = ["name" => $value->sexo, "data" => $xx[$ix++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal4':
                $info = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));


            case 'anal5':
                $data = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('pais') as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $xa[$value->pais] = $ix++;
                }
                foreach ($data->unique('sexo')->sortBy('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('pais')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->pais]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                foreach ($data->unique('sexo')->sortBy('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$key]];
                }
                // return compact('data', 'info', 'xx', 'xa');

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('discapacidad') as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $xa[$value->discapacidad] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('discapacidad')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->discapacidad]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                // return response()->json([$data->unique('sexo'), $xx]);
                $pos = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$pos++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;

                    $foot->tpubh = 0;
                    $foot->tpubm = 0;
                    $foot->tpub = 0;

                    $foot->tprih = 0;
                    $foot->tprim = 0;
                    $foot->tpri = 0;

                    $foot->turh = 0;
                    $foot->turm = 0;
                    $foot->tur = 0;

                    $foot->truh = 0;
                    $foot->trum = 0;
                    $foot->tru = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;

                        $foot->tpubh += $value->tpubh;
                        $foot->tpubm += $value->tpubm;
                        $foot->tpub += $value->tpub;

                        $foot->tprih += $value->tprih;
                        $foot->tprim += $value->tprim;
                        $foot->tpri += $value->tpri;

                        $foot->turh += $value->turh;
                        $foot->turm += $value->turm;
                        $foot->tur += $value->tur;

                        $foot->truh += $value->truh;
                        $foot->trum += $value->trum;
                        $foot->tru += $value->tru;
                    }
                    $foot->avance = 100;
                    foreach ($base as $key => $value) {
                        $value->avance = $foot->tt > 0 ? 100 * $value->tt / $foot->tt : 100;
                    }
                }
                // return compact('base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoTable1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRInicial($anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                // return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e0 = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e0 += $value->e0;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoTable2', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            case 'tabla3':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRPrimaria($anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoTable3', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            case 'tabla4':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRSecundaria($anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoTable4', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));
            default:
                return [];
        }
    }

    public function niveleducativoEBRtablaExport($div, $anio, $provincia, $distrito, $nivel)
    {
        switch ($div) {
            case 'tabla1':
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($div, $anio, $provincia, $distrito,  $nivel);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;

                    $foot->tpubh = 0;
                    $foot->tpubm = 0;
                    $foot->tpub = 0;

                    $foot->tprih = 0;
                    $foot->tprim = 0;
                    $foot->tpri = 0;

                    $foot->turh = 0;
                    $foot->turm = 0;
                    $foot->tur = 0;

                    $foot->truh = 0;
                    $foot->trum = 0;
                    $foot->tru = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;

                        $foot->tpubh += $value->tpubh;
                        $foot->tpubm += $value->tpubm;
                        $foot->tpub += $value->tpub;

                        $foot->tprih += $value->tprih;
                        $foot->tprim += $value->tprim;
                        $foot->tpri += $value->tpri;

                        $foot->turh += $value->turh;
                        $foot->turm += $value->turm;
                        $foot->tur += $value->tur;

                        $foot->truh += $value->truh;
                        $foot->trum += $value->trum;
                        $foot->tru += $value->tru;
                    }
                    $foot->avance = 100;
                    foreach ($base as $key => $value) {
                        $value->avance = $foot->tt > 0 ? 100 * $value->tt / $foot->tt : 100;
                    }
                }
                return compact('base', 'foot');

            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRInicial($anioy->id, $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($div . 'a', $anio, $provincia, $distrito,  $nivel);

                $foot = [];
                // return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e0 = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e0 += $value->e0;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');

            case 'tabla3':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRPrimaria($anioy->id,  $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($div . 'a', $anio, $provincia, $distrito,  $nivel);

                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');

            case 'tabla4':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRSecundaria($anioy->id,  $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBRtabla($div . 'a', $anio, $provincia, $distrito,  $nivel);

                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');


            default:
                return [];
        }
    }

    public function niveleducativoEBRDownload($div, $anio, $provincia, $distrito, $nivel)
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Nivel_Educativo_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBRExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla2') {
                $name = 'Nivel_Educativo_Inicial_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBRExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla3') {
                $name = 'Nivel_Educativo_Primaria_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBRExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla4') {
                $name = 'Nivel_Educativo_Secundaria_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBRExport($div, $anio, $provincia, $distrito, $nivel), $name);
            } else {
                $name = 'Nivel_Educativo_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBRExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
        }
    }


    /*  */

    public function niveleducativoEBE()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $provincia = UbigeoRepositorio::provincia('25');
        //$ugel = MatriculaGeneralRepositorio::ugels();
        //$area = MatriculaGeneralRepositorio::areas();
        $nivel = NivelModalidad::select('id', 'codigo', DB::raw('case when codigo="E0" then "Prite" when codigo="E1" then "Inicial" when codigo="E2" then "Primaria" else nombre end as nombre'))->where('tipo', 'EBE')->orderBy('nombre')->get();

        $fecha = '';

        return view('educacion.MatriculaGeneral.NivelEducativoEBE', compact('anios', 'aniomax', 'actualizado', 'provincia', 'nivel', 'fecha'));
    }

    public function niveleducativoEBEtabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh1 = MatriculaGeneralRepositorio::niveleducativoEBEtabla('head1', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh2 = MatriculaGeneralRepositorio::niveleducativoEBEtabla('head2', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh3 = MatriculaGeneralRepositorio::niveleducativoEBEtabla('head3', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh4 = MatriculaGeneralRepositorio::niveleducativoEBEtabla('head4', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $valor1 = (int)$mh1;
                $valor2 = (int)$mh2->conteo;
                $valor3 = (int)$mh3->conteo;
                $valor4 = (int)$mh4->conteo;

                $valor1 = number_format($valor1, 0);
                $valor2 = number_format($valor2, 0);
                $valor3 = number_format($valor3, 0);
                $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
            case 'anal1':
                $data = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                    $xa[$value->ugel] = $ix++;
                }
                $ii = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ['name' => $value->sexo,  'data' => []];
                    $xx[] = array_fill(0, $data->unique('ugel')->count(), 0);
                }

                foreach ($data as $key => $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->ugel]] = $value->conteo;
                        }
                    }
                }

                $info['series'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ['name' => $value->sexo,  'data' => $xx[$ix++]];
                }
                // return compact('data', 'info', 'xx', 'xa');
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $info = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal3':
                $data = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['cat'] = [];
                $info['dat'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('grupos') as $key => $value) {
                    $info['cat'][] = $value->grupos;
                    $xa[$value->grupos] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['dat'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('grupos')->count(), 0);
                }
                // return compact('data', 'info');
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->grupos]] = $value->conteo;
                        }
                    }
                }
                // return compact('data', 'info', 'xx', 'xx');
                $info['dat'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['dat'][] = ["name" => $value->sexo, "data" => $xx[$ix++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal4':
                $data = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('discapacidad') as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $xa[$value->discapacidad] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('discapacidad')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->discapacidad]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$ix++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));


            case 'anal5':
                $data = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('pais') as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $xa[$value->pais] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('pais')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->pais]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$key]];
                }
                // return compact('data', 'info', 'xx', 'xa');

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('discapacidad') as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $xa[$value->discapacidad] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('discapacidad')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->discapacidad]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$key]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));


            case 'tabla1':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEInicial($rq->anio == 3 ? 3 : $anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                // return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e0 = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e0 += $value->e0;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoEBETable1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEPrimaria($rq->anio == 3 ? 3 : $anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBEtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoEBETable2', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            default:
                return [];
        }
    }

    public function niveleducativoEBEtablaExport($div, $anio, $provincia, $distrito, $nivel)
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEInicial($anio == 3 ? 3 : $anioy->id, $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBEtabla($div, $anio, $provincia, $distrito,  $nivel);
                $nbase = $base->count();
                $foot = [];
                // return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e0 = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e0 += $value->e0;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return compact('base', 'foot');

            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBEPrimaria($anio == 3 ? 3 : $anioy->id, $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBEtabla($div, $anio, $provincia, $distrito,  $nivel);
                $nbase = $base->count();
                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return  compact('base', 'foot');

            case 'tabla3':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRPrimaria($anioy->id,  $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBEtabla($div, $anio, $provincia, $distrito,  $nivel);

                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;
                    $foot->e6 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                        $foot->e6 += $value->e6;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');

            case 'tabla4':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBRSecundaria($anioy->id,  $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBEtabla($div, $anio, $provincia, $distrito,  $nivel);

                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 0;
                }
                return  compact('base', 'foot');


            default:
                return [];
        }
    }

    public function niveleducativoEBEDownload($div, $anio, $provincia, $distrito, $nivel)
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Nivel_Educativo_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBEExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla2') {
                $name = 'Nivel_Educativo_Inicial_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBEExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla3') {
                $name = 'Nivel_Educativo_Primaria_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBEExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla4') {
                $name = 'Nivel_Educativo_Secundaria_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBEExport($div, $anio, $provincia, $distrito, $nivel), $name);
            } else {
                $name = 'Nivel_Educativo_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBEExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
        }
    }


    /*  */

    /*  */

    public function niveleducativoEBA()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::aniosModalidad('EBA');
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $provincia = UbigeoRepositorio::provincia('25');
        //$ugel = MatriculaGeneralRepositorio::ugels();
        //$area = MatriculaGeneralRepositorio::areas();
        $nivel = NivelModalidad::select('id', 'codigo', DB::raw('case when codigo="D1" then "Inicial e Intermedio" when codigo="D2" then "Avanzado"  else nombre end as nombre'))->where('tipo', 'EBA')->orderBy('codigo')->get();

        $fecha = '';

        return view('educacion.MatriculaGeneral.niveleducativoEBA', compact('anios', 'aniomax', 'actualizado', 'provincia', 'nivel', 'fecha'));
    }

    public function niveleducativoEBAtabla(Request $rq)
    {
        switch ($rq->div) {
            case 'head':
                $mh1 = MatriculaGeneralRepositorio::niveleducativoEBAtabla('head1', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh2 = MatriculaGeneralRepositorio::niveleducativoEBAtabla('head2', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh3 = MatriculaGeneralRepositorio::niveleducativoEBAtabla('head3', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $mh4 = MatriculaGeneralRepositorio::niveleducativoEBAtabla('head4', $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $valor1 = (int)$mh1;
                $valor2 = (int)$mh2->conteo;
                $valor3 = (int)$mh3->conteo;
                $valor4 = (int)$mh4->conteo;

                // $valor1 = number_format($valor1, 0);
                // $valor2 = number_format($valor2, 0);
                // $valor3 = number_format($valor3, 0);
                // $valor4 = number_format($valor4, 0);

                return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
            case 'anal1':
                $data = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['series'] = [];
                $info['categoria'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('ugel') as $key => $value) {
                    $info['categoria'][] = $value->ugel;
                    $xa[$value->ugel] = $ix++;
                }
                $ii = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ['name' => $value->sexo,  'data' => []];
                    $xx[] = array_fill(0, $data->unique('ugel')->count(), 0);
                }

                foreach ($data as $key => $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->ugel]] = $value->conteo;
                        }
                    }
                }

                $info['series'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ['name' => $value->sexo,  'data' => $xx[$ix++]];
                }
                // return compact('data', 'info', 'xx', 'xa');
                $info['maxbar'] = 0;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $info = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal3':
                $data = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['cat'] = [];
                $info['dat'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('grupos') as $key => $value) {
                    $info['cat'][] = $value->grupos;
                    $xa[$value->grupos] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['dat'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('grupos')->count(), 0);
                }
                // return compact('data', 'info');
                foreach ($data as $value) {
                    foreach ($info['dat'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->grupos]] = $value->conteo;
                        }
                    }
                }
                // return compact('data', 'info', 'xx', 'xx');
                $info['dat'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['dat'][] = ["name" => $value->sexo, "data" => $xx[$ix++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal4':
                $data = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('discapacidad') as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $xa[$value->discapacidad] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('discapacidad')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->discapacidad]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                $ix = 0;
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$ix++]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));

            case 'anal5':
                $data = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('pais') as $key => $value) {
                    $info['categoria'][] = $value->pais;
                    $xa[$value->pais] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('pais')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->pais]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$key]];
                }
                // return compact('data', 'info', 'xx', 'xa');

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg', 'data'));
            case 'anal6':
                $data = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $info['categoria'] = [];
                $info['series'] = [];
                $xx = [];
                $xa = [];
                $ix = 0;
                foreach ($data->unique('discapacidad') as $key => $value) {
                    $info['categoria'][] = $value->discapacidad;
                    $xa[$value->discapacidad] = $ix++;
                }
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => []];
                    $xx[] = array_fill(0, $data->unique('discapacidad')->count(), 0);
                }
                foreach ($data as $value) {
                    foreach ($info['series'] as $key => $dat) {
                        if ($value->sexo == $dat['name']) {
                            $xx[$key][$xa[$value->discapacidad]] = $value->conteo;
                        }
                    }
                }
                $info['series'] = [];
                foreach ($data->unique('sexo') as $key => $value) {
                    $info['series'][] = ["name" => $value->sexo, "data" => $xx[$key]];
                }

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));


            case 'tabla1':
                $base = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;

                    $foot->tpubh = 0;
                    $foot->tpubm = 0;
                    $foot->tpub = 0;

                    $foot->tprih = 0;
                    $foot->tprim = 0;
                    $foot->tpri = 0;

                    $foot->turh = 0;
                    $foot->turm = 0;
                    $foot->tur = 0;

                    $foot->truh = 0;
                    $foot->trum = 0;
                    $foot->tru = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;

                        $foot->tpubh += $value->tpubh;
                        $foot->tpubm += $value->tpubm;
                        $foot->tpub += $value->tpub;

                        $foot->tprih += $value->tprih;
                        $foot->tprim += $value->tprim;
                        $foot->tpri += $value->tpri;

                        $foot->turh += $value->turh;
                        $foot->turm += $value->turm;
                        $foot->tur += $value->tur;

                        $foot->truh += $value->truh;
                        $foot->trum += $value->trum;
                        $foot->tru += $value->tru;
                    }
                    $foot->avance = 100;
                    foreach ($base as $key => $value) {
                        $value->avance = $foot->tt > 0 ? 100 * $value->tt / $foot->tt : 100;
                    }
                }
                // return compact('base', 'foot');
                $excel = view('educacion.MatriculaGeneral.NivelEducativoEBATable1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));

                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));
            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBAInicial($anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                // return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.niveleducativoEBATable2', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            case 'tabla3':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBAAvanzado($anioy->id, $rq->provincia, $rq->distrito,  $rq->nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBAtabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->nivel);
                $nbase = $base->count();
                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                // return compact('meta', 'base', 'foot');
                $excel = view('educacion.MatriculaGeneral.niveleducativoEBATable3', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg', 'nbase'));

            default:
                return [];
        }
    }

    public function niveleducativoEBAtablaExport($div, $anio, $provincia, $distrito, $nivel)
    {
        switch ($div) {
            case 'tabla1':
                $base = MatriculaGeneralRepositorio::niveleducativoEBAtabla($div, $anio, $provincia, $distrito,  $nivel);
                $nbase = $base->count();
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;

                    $foot->tpubh = 0;
                    $foot->tpubm = 0;
                    $foot->tpub = 0;

                    $foot->tprih = 0;
                    $foot->tprim = 0;
                    $foot->tpri = 0;

                    $foot->turh = 0;
                    $foot->turm = 0;
                    $foot->tur = 0;

                    $foot->truh = 0;
                    $foot->trum = 0;
                    $foot->tru = 0;
                    foreach ($base as $key => $value) {
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;

                        $foot->tpubh += $value->tpubh;
                        $foot->tpubm += $value->tpubm;
                        $foot->tpub += $value->tpub;

                        $foot->tprih += $value->tprih;
                        $foot->tprim += $value->tprim;
                        $foot->tpri += $value->tpri;

                        $foot->turh += $value->turh;
                        $foot->turm += $value->turm;
                        $foot->tur += $value->tur;

                        $foot->truh += $value->truh;
                        $foot->trum += $value->trum;
                        $foot->tru += $value->tru;
                    }
                    $foot->avance = 100;
                    foreach ($base as $key => $value) {
                        $value->avance = $foot->tt > 0 ? 100 * $value->tt / $foot->tt : 100;
                    }
                }
                return compact('base', 'foot');

            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBAInicial($anioy->id, $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBAtabla($div, $anio, $provincia, $distrito,  $nivel);
                $nbase = $base->count();
                $foot = [];
                // return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;
                    $foot->e5 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                        $foot->e5 += $value->e5;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return  compact('base', 'foot');

            case 'tabla3':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaEBAAvanzado($anioy->id, $provincia, $distrito,  $nivel);
                $base = MatriculaGeneralRepositorio::niveleducativoEBAtabla($div, $anio, $provincia, $distrito,  $nivel);
                $nbase = $base->count();
                $foot = [];
                //  return compact('meta', 'base', 'foot');
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->tt = 0;
                    $foot->th = 0;
                    $foot->tm = 0;
                    $foot->e1 = 0;
                    $foot->e2 = 0;
                    $foot->e3 = 0;
                    $foot->e4 = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->codmod == $mm->codmod) {
                                $value->meta = $mm->tt;
                                break;
                            }
                        }
                        $value->avance = $value->meta > 0 ? 100 * $value->tt / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->tt += $value->tt;
                        $foot->th += $value->th;
                        $foot->tm += $value->tm;
                        $foot->e1 += $value->e1;
                        $foot->e2 += $value->e2;
                        $foot->e3 += $value->e3;
                        $foot->e4 += $value->e4;
                    }
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->tt / $foot->meta : 100;
                }
                return  compact('base', 'foot');
            default:
                return [];
        }
    }

    public function niveleducativoEBADownload($div, $anio, $provincia, $distrito, $nivel)
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Nivel_Educativo_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new NivelEducativoEBAExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla2') {
                $name = 'Nivel_Educativo_Inicial_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBAExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla3') {
                $name = 'Nivel_Educativo_Primaria_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBAExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
            if ($div == 'tabla4') {
                $name = 'Nivel_Educativo_Secundaria_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBAExport($div, $anio, $provincia, $distrito, $nivel), $name);
            } else {
                $name = 'Nivel_Educativo_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new niveleducativoEBAExport($div, $anio, $provincia, $distrito, $nivel), $name);
            }
        }
    }


    /*  */
}
