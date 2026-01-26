<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\MatriculaRepositorio;
use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MatriculaDetalleController extends Controller
{
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


    public function basicaregular()
    {
        /* anos */
        $anios = MatriculaRepositorio::matriculas_anio();
        /* ugels */
        $ugels = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        /* gestion */
        $gestions = [["id" => 2, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        /* area geografica */
        $areas = Area::select('id', 'nombre')->get();
        /* ultimo reg */
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '8')->orderBy('fecha', 'desc')->take(1)->get();
        $importacion_id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));
        return view("educacion.MatriculaDetalle.BasicaRegular", compact('anios', 'gestions', 'areas', 'ugels', 'importacion_id', 'fecha'));
    }

    public function cargarEBRgrafica1(Request $rq)
    {
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            DB::raw("max(fechaActualizacion) as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")
            ->groupBy('ano')
            ->get();
        $fechas = [];
        $cat = [];
        foreach ($impfechas as $key => $value) {
            $fechas[] = $value->fecha;
            $cat[] = $value->ano;
        }
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            'id',
            DB::raw("fechaActualizacion as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")->whereIn('fechaActualizacion', $fechas)
            ->orderBy('ano', 'asc')
            ->get();
        $ids = '';
        foreach ($impfechas as $key => $value) {
            if ($key < count($impfechas) - 1)
                $ids .= $value->id . ',';
            else $ids .= $value->id;
        }

        $query = DB::table(DB::raw("(
            select
				case v5.nombre
					when 'Secundaria' then v5.nombre
                    when 'Primaria' then v5.nombre
                    else 'Inicial'
				end as nivel,
                year(v3.fechaActualizacion) as ano,
                SUM(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) as conteo
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v3.id in ($ids)
            group by nivel,ano
            ) as tb"))
            ->get();
        $data['cat'] = $cat;
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Inicial')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Inicial', 'data' => $xx];
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Primaria')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Primaria', 'data' => $xx];
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Secundaria')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Secundaria', 'data' => $xx];

        return $data;
    }

    public function cargarEBRgrafica2(Request $rq)
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
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by mes
            order by mes asc
            ) as xx"))->get();
        $error['base'] = $base;
        $data['cat'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $data['dat'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $data['dat'][$value->mes - 1] = (int)$value->y;
        }
        return $data;
    }

    public function cargarEBRgrafica3(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                case v5.nombre
                    when 'Secundaria' then v5.nombre
                    when 'Primaria' then v5.nombre
                    else 'Inicial'
                end as name,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) y,
                FORMAT(sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)),0) yx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by name
            ) as xx"))->get();
        /* $error['base'] = $base; */
        foreach ($base as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $base;
    }

    public function cargarEBRgrafica4(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                sum(v1.total_hombres) hy,
                sum(v1.total_mujeres) my,
                FORMAT(sum(v1.total_hombres),0) hyx,
                FORMAT(sum(v1.total_mujeres),0) myx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            ) as xx"))->get();
        $query = $base->first();
        $data[] = ['name' => 'MASCULINO', 'y' => (int)$query->hy, 'yx' => $query->hyx];
        $data[] = ['name' => 'FEMENINO', 'y' => (int)$query->my, 'yx' => $query->myx];
        return $data;
    }

    public function cargarEBRgrafica5(Request $rq)
    {
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            DB::raw("max(fechaActualizacion) as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")
            ->groupBy('ano')
            ->get();
        $fechas = [];
        $cat = [];
        foreach ($impfechas as $key => $value) {
            $fechas[] = $value->fecha;
            $cat[] = $value->ano;
        }
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            'id',
            DB::raw("fechaActualizacion as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")->whereIn('fechaActualizacion', $fechas)
            ->orderBy('ano', 'asc')
            ->get();
        $ids = '';
        foreach ($impfechas as $key => $value) {
            if ($key < count($impfechas) - 1)
                $ids .= $value->id . ',';
            else $ids .= $value->id;
        }

        $query = DB::table(DB::raw("(
            select
                'Inicial' as nivel,
                year(v3.fechaActualizacion) as ano,
                SUM(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) as conteo
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v3.id in ($ids) and v5.id=14
            group by nivel,ano
            ) as tb"))
            ->get();
        $data['cat'] = $cat;
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Inicial')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Inicial', 'data' => $xx];
        return $data;
    }

    public function cargarEBRgrafica6(Request $rq)
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
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea and v5.id=14 $optugel
            group by mes
            order by mes asc
            ) as xx"))->get();
        $error['base'] = $base;
        $data['cat'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        $data['dat'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $data['dat'][$value->mes - 1] = (int)$value->y;
        }
        return $data;
    }

    public function cargarEBRgrafica7(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                sum(v1.total_hombres) hy,
                sum(v1.total_mujeres) my,
                FORMAT(sum(v1.total_hombres),0) hyx,
                FORMAT(sum(v1.total_mujeres),0) myx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea and v5.id=14 $optugel
            ) as xx"))->get();
        $query = $base->first();
        $data[] = ['name' => 'MASCULINO', 'y' => (int)$query->hy, 'yx' => $query->hyx];
        $data[] = ['name' => 'FEMENINO', 'y' => (int)$query->my, 'yx' => $query->myx];
        return $data;
    }

    public function cargarEBRtabla1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(v1.total_hombres+v1.total_mujeres) tt,
                sum(v1.total_hombres) th,
                sum(v1.total_mujeres) tm,
                sum(IF(v5.nombre='Secundaria',v1.total_hombres,0)) sh,
                sum(IF(v5.nombre='Secundaria',v1.total_mujeres,0)) sm,
                sum(IF(v5.nombre='Primaria',v1.total_hombres,0)) ph,
                sum(IF(v5.nombre='Primaria',v1.total_mujeres,0)) pm,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ih,
                sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) im
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(v1.total_hombres+v1.total_mujeres) tt,
                    sum(v1.total_hombres) th,
                    sum(v1.total_mujeres) tm,
                    sum(IF(v5.nombre='Secundaria',v1.total_hombres,0)) sh,
                    sum(IF(v5.nombre='Secundaria',v1.total_mujeres,0)) sm,
                    sum(IF(v5.nombre='Primaria',v1.total_hombres,0)) ph,
                    sum(IF(v5.nombre='Primaria',v1.total_mujeres,0)) pm,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ih,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) im
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        $vv = 0;
        foreach ($base as $key => $value) {
            $value->ptt = 100 * $value->tt / $foot->tt;
            $vv += $value->ptt;
        }
        $foot->ptt = $vv;
        /* $data['body'] = $base;
        $data['foot'] = $foot; */
        //return $data;
        return view("educacion.MatriculaDetalle.BasicaRegularTabla1", compact('rq', 'base', 'foot'));
    }

    public function cargarEBRtabla2(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(v1.total_hombres+v1.total_mujeres) tt,
                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre+v1.cero_anios_mujer,0)) +
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre+v1.un_anio_mujer,0)) +
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre+v1.dos_anios_mujer,0)) ICI,

                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre+v1.tres_anios_mujer,0)) +
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre+v1.cuatro_anios_mujer,0)) +
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre+v1.cinco_anios_mujer,0)) +
                sum(IF(v5.nombre like 'Inicial%',v1.mas_cinco_anios_hombre+v1.mas_cinco_anios_mujer,0)) ICII,

                sum(IF(v5.nombre='Primaria',v1.primero_hombre+v1.primero_mujer,0)) +
                sum(IF(v5.nombre='Primaria',v1.segundo_hombre+v1.segundo_mujer,0)) ICIII,

                sum(IF(v5.nombre='Primaria',v1.tercero_hombre+v1.tercero_mujer,0)) +
                sum(IF(v5.nombre='Primaria',v1.cuarto_hombre+v1.cuarto_mujer,0)) ICIV,

                sum(IF(v5.nombre='Primaria',v1.quinto_hombre+v1.quinto_mujer,0)) +
                sum(IF(v5.nombre='Primaria',v1.sexto_hombre+v1.sexto_mujer,0)) ICV,

                sum(IF(v5.nombre='Secundaria',v1.primero_hombre+v1.primero_mujer,0)) +
                sum(IF(v5.nombre='Secundaria',v1.segundo_hombre+v1.segundo_mujer,0)) ICVI,

                sum(IF(v5.nombre='Secundaria',v1.tercero_hombre+v1.tercero_mujer,0)) +
                sum(IF(v5.nombre='Secundaria',v1.cuarto_hombre+v1.cuarto_mujer,0)) +
                sum(IF(v5.nombre='Secundaria',v1.quinto_hombre+v1.quinto_mujer,0)) ICVII
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(v1.total_hombres+v1.total_mujeres) tt,
                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre+v1.cero_anios_mujer,0)) +
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre+v1.un_anio_mujer,0)) +
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre+v1.dos_anios_mujer,0)) ICI,

                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre+v1.tres_anios_mujer,0)) +
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre+v1.cuatro_anios_mujer,0)) +
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre+v1.cinco_anios_mujer,0)) +
                    sum(IF(v5.nombre like 'Inicial%',v1.mas_cinco_anios_hombre+v1.mas_cinco_anios_mujer,0)) ICII,

                    sum(IF(v5.nombre='Primaria',v1.primero_hombre+v1.primero_mujer,0)) +
                    sum(IF(v5.nombre='Primaria',v1.segundo_hombre+v1.segundo_mujer,0)) ICIII,

                    sum(IF(v5.nombre='Primaria',v1.tercero_hombre+v1.tercero_mujer,0)) +
                    sum(IF(v5.nombre='Primaria',v1.cuarto_hombre+v1.cuarto_mujer,0)) ICIV,

                    sum(IF(v5.nombre='Primaria',v1.quinto_hombre+v1.quinto_mujer,0)) +
                    sum(IF(v5.nombre='Primaria',v1.sexto_hombre+v1.sexto_mujer,0)) ICV,

                    sum(IF(v5.nombre='Secundaria',v1.primero_hombre+v1.primero_mujer,0)) +
                    sum(IF(v5.nombre='Secundaria',v1.segundo_hombre+v1.segundo_mujer,0)) ICVI,

                    sum(IF(v5.nombre='Secundaria',v1.tercero_hombre+v1.tercero_mujer,0)) +
                    sum(IF(v5.nombre='Secundaria',v1.cuarto_hombre+v1.cuarto_mujer,0)) +
                    sum(IF(v5.nombre='Secundaria',v1.quinto_hombre+v1.quinto_mujer,0)) ICVII
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        $vv = 0;
        foreach ($base as $key => $value) {
            $value->ptt = 100 * $value->tt / $foot->tt;
            $vv += $value->ptt;
        }
        $foot->ptt = $vv;
        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla2", compact('rq', 'base', 'foot'));
    }

    public function cargarEBRtabla3(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.id,
                v6.nombre ugel,

                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by id,ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();

        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla3", compact('rq', 'base', 'foot'));
    }

    public function cargarEBRtabla3_1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->provincia;

        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";
        $optugel = $ugel == 0 ? "" : " and v6.id=$ugel";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $head = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea
            group by ugel
            ) as xx"))->get();

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                vB.id,
                vB.nombre distrito,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea
            group by ugel,id,distrito
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea
                ) as xx"))->get()->first();

        /* $data['head'] = $head;
        $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla3_1", compact('rq', 'base', 'foot', 'head'));
    }

    public function cargarEBRtabla3_2(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $distrito = $rq->distrito;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $ndistrito = $distrito == 0 ? 'EN GENERAL' : 'DE ' . Ubigeo::find($distrito)->nombre;
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";
        $optdistrito = $distrito == 0 ? "" : " and vB.id=$distrito";


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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v4.nombreInstEduc iiee,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v5.nombre like '%Inicial%' and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optdistrito $optgestion $optarea $optugel
            group by iiee
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
                inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
                inner join par_ubigeo as vC on vC.id=vB.dependencia
                where v3.estado='PR' and v5.tipo in ('EBR') and v5.nombre like '%Inicial%' and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optdistrito $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return ["tabla" => view("educacion.MatriculaDetalle.BasicaRegularTabla3_2", compact('rq', 'base', 'foot'))->render(), "distrito" => $ndistrito];
    }

    public function cargarEBRtabla3_3(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $distrito = $rq->distrito;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $ndistrito = Ubigeo::find($distrito)->nombre;
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";


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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                vA.nombre centro_poblado,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') and vB.id=$distrito $optgestion $optarea $optugel
            group by centro_poblado order by tti desc
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres+v1.total_mujeres,0)) tti,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_hombres,0)) ttih,
                    sum(IF(v5.nombre like 'Inicial%',v1.total_mujeres,0)) ttim,

                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_hombre,0)) ICI0H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cero_anios_mujer,0)) ICI0M,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_hombre,0)) ICI1H,
                    sum(IF(v5.nombre like 'Inicial%',v1.un_anio_mujer,0)) ICI1M,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_hombre,0)) ICI2H,
                    sum(IF(v5.nombre like 'Inicial%',v1.dos_anios_mujer,0)) ICI2M,

                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                    sum(IF(v5.nombre like 'Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                    sum(IF(v5.nombre like 'Inicial%',v1.cinco_anios_mujer,0)) ICII5M

                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
                inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
                inner join par_ubigeo as vC on vC.id=vB.dependencia
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') and vB.id=$distrito $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /*  $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return ["tabla" => view("educacion.MatriculaDetalle.BasicaRegularTabla3_3", compact('rq', 'base', 'foot'))->render(), "distrito" => $ndistrito];
    }

    public function cargarEBRtabla4(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.id,
                v6.nombre ugel,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by id,ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                    sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();

        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla4", compact('rq', 'base', 'foot'));
    }

    public function cargarEBRtabla4_1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->provincia;

        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";
        $optugel = $ugel == 0 ? "" : " and v6.id=$ugel";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $head = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                vB.id,
                vB.nombre distrito,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea $optugel
            group by ugel,id,distrito
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                    sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea $optugel
                ) as xx"))->get()->first();

        /* $data['head'] = $head;
        $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla4_1", compact('rq', 'base', 'foot', 'head'));
    }

    public function cargarEBRtabla4_2(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $distrito = $rq->distrito;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $ndistrito = $distrito == 0 ? 'EN GENERAL' : 'DE ' . Ubigeo::find($distrito)->nombre;
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";
        $optdistrito = $distrito == 0 ? "" : " and vB.id=$distrito";


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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v4.nombreInstEduc iiee,
                sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                    sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v5.nombre like '%Primaria%' and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optdistrito $optgestion $optarea $optugel
            group by iiee order by iiee asc
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres+v1.total_mujeres,0)) ttp,
                    sum(IF(v5.nombre like 'Primaria',v1.total_hombres,0)) ttph,
                    sum(IF(v5.nombre like 'Primaria',v1.total_mujeres,0)) ttpm,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_hombre,0)) ICIII1H,
                    sum(IF(v5.nombre like 'Primaria',v1.primero_mujer,0))  ICIII1M,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_hombre,0)) ICIII2H,
                    sum(IF(v5.nombre like 'Primaria',v1.segundo_mujer,0))  ICIII2M,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_hombre,0)) ICIV3H,
                    sum(IF(v5.nombre like 'Primaria',v1.tercero_mujer,0))  ICIV3M,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_hombre,0))  ICIV4H,
                    sum(IF(v5.nombre like 'Primaria',v1.cuarto_mujer,0))   ICIV4M,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_hombre,0))  ICV5H,
                    sum(IF(v5.nombre like 'Primaria',v1.quinto_mujer,0))   ICV5M,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_hombre,0))   ICV6H,
                    sum(IF(v5.nombre like 'Primaria',v1.sexto_mujer,0))    ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
                inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
                inner join par_ubigeo as vC on vC.id=vB.dependencia
                where v3.estado='PR' and v5.tipo in ('EBR') and v5.nombre like '%Primaria%' and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optdistrito $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /*  $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return ["tabla" => view("educacion.MatriculaDetalle.BasicaRegularTabla4_2", compact('rq', 'base', 'foot'))->render(), "distrito" => $ndistrito];
    }

    public function cargarEBRtabla5(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.id,
                v6.nombre ugel,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by id,ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                    sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                    sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                    sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                    sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                    sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                    sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                    sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                    sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                    sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                    sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                    sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                    sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();

        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla5", compact('rq', 'base', 'foot'));
    }

    public function cargarEBRtabla5_1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->provincia;

        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";
        $optugel = $ugel == 0 ? "" : " and v6.id=$ugel";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $head = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                vB.id,
                vB.nombre distrito,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea $optugel
            group by ugel,id,distrito
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                    sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                    sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                    sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                    sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                    sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                    sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                    sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                    sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                    sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                    sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                    sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                    sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M,
                    sum(IF(v5.nombre like 'Secundaria',v1.sexto_hombre,0))   ICV6H,
                    sum(IF(v5.nombre like 'Secundaria',v1.sexto_mujer,0))    ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optugel $optgestion $optarea $optugel
                ) as xx"))->get()->first();

        /* $data['head'] = $head;
        $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla5_1", compact('rq', 'base', 'foot', 'head'));
    }

    public function cargarEBRtabla5_2(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $distrito = $rq->distrito;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $ndistrito = $distrito == 0 ? 'EN GENERAL' : 'DE ' . Ubigeo::find($distrito)->nombre;
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";
        $optdistrito = $distrito == 0 ? "" : " and vB.id=$distrito";


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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v4.nombreInstEduc iiee,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_hombre,0))   ICV6H,
                sum(IF(v5.nombre like 'Secundaria',v1.sexto_mujer,0))    ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
            inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
            inner join par_ubigeo as vC on vC.id=vB.dependencia
            where v3.estado='PR' and v5.tipo in ('EBR') and v5.nombre like '%Secundaria%' and v2.anio_id=$ano and
                    v3.fechaActualizacion in ('$fx') $optdistrito $optgestion $optarea $optugel
            group by iiee order by iiee asc
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(IF(v5.nombre like 'Secundaria',v1.total_hombres+v1.total_mujeres,0)) tts,
                    sum(IF(v5.nombre like 'Secundaria',v1.total_hombres,0)) ttsh,
                    sum(IF(v5.nombre like 'Secundaria',v1.total_mujeres,0)) ttsm,
                    sum(IF(v5.nombre like 'Secundaria',v1.primero_hombre,0)) ICVI1H,
                    sum(IF(v5.nombre like 'Secundaria',v1.primero_mujer,0))  ICVI1M,
                    sum(IF(v5.nombre like 'Secundaria',v1.segundo_hombre,0)) ICVI2H,
                    sum(IF(v5.nombre like 'Secundaria',v1.segundo_mujer,0))  ICVI2M,
                    sum(IF(v5.nombre like 'Secundaria',v1.tercero_hombre,0)) ICVII3H,
                    sum(IF(v5.nombre like 'Secundaria',v1.tercero_mujer,0))  ICVII3M,
                    sum(IF(v5.nombre like 'Secundaria',v1.cuarto_hombre,0))  ICVII4H,
                    sum(IF(v5.nombre like 'Secundaria',v1.cuarto_mujer,0))   ICVII4M,
                    sum(IF(v5.nombre like 'Secundaria',v1.quinto_hombre,0))  ICVII5H,
                    sum(IF(v5.nombre like 'Secundaria',v1.quinto_mujer,0))   ICVII5M,
                    sum(IF(v5.nombre like 'Secundaria',v1.sexto_hombre,0))   ICV6H,
                    sum(IF(v5.nombre like 'Secundaria',v1.sexto_mujer,0))    ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                inner join edu_centropoblado as vA on vA.id=v4.CentroPoblado_id
                inner join par_ubigeo as vB on vB.id=vA.Ubigeo_id
                inner join par_ubigeo as vC on vC.id=vB.dependencia
                where v3.estado='PR' and v5.tipo in ('EBR') and v5.nombre like '%Secundaria%' and v2.anio_id=$ano and
                        v3.fechaActualizacion in ('$fx') $optdistrito $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /*  $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return ["tabla" => view("educacion.MatriculaDetalle.BasicaRegularTabla5_2", compact('rq', 'base', 'foot'))->render(), "distrito" => $ndistrito];
    }

    public function cargarEBRtabla6(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(v1.total_hombres+v1.total_mujeres) tt,
                sum(v1.total_hombres) tth,
                sum(v1.total_mujeres) ttm
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea and v5.id=14 $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(v1.total_hombres+v1.total_mujeres) tt,
                    sum(v1.total_hombres) tth,
                    sum(v1.total_mujeres) ttm
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea and v5.id=14 $optugel
                ) as xx"))->get()->first();

        $foot->ptt = 0;
        foreach ($base as $key => $value) {
            $value->ptt = 100 * $value->tt / $foot->tt;
            $foot->ptt += $value->ptt;
        }

        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaRegularTabla6", compact('rq', 'base', 'foot'));
    }


    public function basicaespecial()
    {
        /* anos */
        $anios = MatriculaRepositorio::matriculas_anio();
        /* ugels */
        $ugels = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        /* gestion */
        $gestions = [["id" => 2, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        /* area geografica */
        $areas = Area::select('id', 'nombre')->get();
        /* ultimo reg */
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '8')->orderBy('fecha', 'desc')->take(1)->get();
        $importacion_id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));
        return view("educacion.MatriculaDetalle.BasicaEspecial", compact('anios', 'gestions', 'areas', 'ugels', 'importacion_id', 'fecha'));
    }

    public function cargarEBEgrafica1(Request $rq)
    {
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            DB::raw("max(fechaActualizacion) as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")
            ->groupBy('ano')
            ->get();
        $fechas = [];
        $cat = [];
        foreach ($impfechas as $key => $value) {
            $fechas[] = $value->fecha;
            $cat[] = $value->ano;
        }
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            'id',
            DB::raw("fechaActualizacion as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")->whereIn('fechaActualizacion', $fechas)
            ->orderBy('ano', 'asc')
            ->get();
        $ids = '';
        foreach ($impfechas as $key => $value) {
            if ($key < count($impfechas) - 1)
                $ids .= $value->id . ',';
            else $ids .= $value->id;
        }

        $query = DB::table(DB::raw("(
            select
				case v5.nombre_matricula
					when 'Básica Especial-Inicial' then 'Inicial'
                    when 'Básica Especial-Primaria' then 'Primaria'
                    when 'Básica Especial - PRITE' then 'Prite'
                    else 'Sin Definir'
				end as nivel,
                year(v3.fechaActualizacion) as ano,
                SUM(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) as conteo
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            where v3.estado='PR' and v5.tipo in ('EBE') and v3.id in ($ids)
            group by nivel,ano
            ) as tb"))
            ->get();
        $data['cat'] = $cat;
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Inicial')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Inicial', 'data' => $xx];
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Primaria')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Primaria', 'data' => $xx];
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Prite')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Prite', 'data' => $xx];
        return $data;
    }

    public function cargarEBEgrafica2(Request $rq)
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
            where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by mes
            order by mes asc
            ) as xx"))->get();
        $error['base'] = $base;
        $data['cat'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        //$data['cat'] = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $data['dat'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $data['dat'][$value->mes - 1] = (int)$value->y;
        }
        return $data;
    }

    public function cargarEBEgrafica3(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                case v5.nombre_matricula
                    when 'Básica Especial-Inicial' then 'Inicial'
                    when 'Básica Especial-Primaria' then 'Primaria'
                    when 'Básica Especial - PRITE' then 'Prite'
                    else 'Sin Definir'
                end as name,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) y,
                FORMAT(sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)),0) yx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by name
            ) as xx"))->get();
        /* $error['base'] = $base; */
        foreach ($base as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $base;
    }

    public function cargarEBEgrafica4(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                sum(v1.total_hombres) hy,
                sum(v1.total_mujeres) my,
                FORMAT(sum(v1.total_hombres),0) hyx,
                FORMAT(sum(v1.total_mujeres),0) myx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            ) as xx"))->get();
        $query = $base->first();
        $data[] = ['name' => 'MASCULINO', 'y' => (int)$query->hy, 'yx' => $query->hyx];
        $data[] = ['name' => 'FEMENINO', 'y' => (int)$query->my, 'yx' => $query->myx];
        return $data;
    }

    public function cargarEBEtabla1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(v1.total_hombres+v1.total_mujeres) tt,
                sum(v1.total_hombres) tth,
                sum(v1.total_mujeres) ttm,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_mujer,0)) ICII5M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_hombre,0)) ICIII1H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_mujer,0)) ICIII1M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_hombre,0)) ICIII2H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_mujer,0)) ICIII2M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_hombre,0)) ICIV3H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_mujer,0)) ICIV3M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_hombre,0)) ICIV4H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_mujer,0)) ICIV4M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_hombre,0)) ICV5H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_mujer,0)) ICV5M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_hombre,0)) ICV6H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_mujer,0)) ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(v1.total_hombres+v1.total_mujeres) tt,
                    sum(v1.total_hombres) tth,
                    sum(v1.total_mujeres) ttm,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_mujer,0)) ICII5M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_hombre,0)) ICIII1H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_mujer,0)) ICIII1M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_hombre,0)) ICIII2H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_mujer,0)) ICIII2M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_hombre,0)) ICIV3H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_mujer,0)) ICIV3M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_hombre,0)) ICIV4H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_mujer,0)) ICIV4M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_hombre,0)) ICV5H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_mujer,0)) ICV5M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_hombre,0)) ICV6H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_mujer,0)) ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /* $vv = 0;
        foreach ($base as $key => $value) {
            $value->ptt = 100 * $value->tt / $foot->tt;
            $vv += $value->ptt;
        }
        $foot->ptt = $vv; */
        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaEspecialTabla1", compact('rq', 'base', 'foot'));
    }

    public function cargarEBEtabla2(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) tt,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.total_hombres+v1.total_mujeres,0)) inc,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.total_hombres+v1.total_mujeres,0)) prm,
                sum(IF(v5.nombre_matricula like '%PRITE%',IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) prt
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) tt,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.total_hombres+v1.total_mujeres,0)) inc,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.total_hombres+v1.total_mujeres,0)) prm,
                    sum(IF(v5.nombre_matricula like '%PRITE%',IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) prt
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBE') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.BasicaEspecialTabla2", compact('rq', 'base', 'foot'));
    }

    public function interculturalbilingue()
    {
        /* anos */
        $anios = MatriculaRepositorio::matriculas_anio();
        /* ugels */
        $ugels = Ugel::select('id', 'nombre')->where('dependencia', 2)->get();
        /* gestion */
        $gestions = [["id" => 2, "nombre" => "Pública"], ["id" => 3, "nombre" => "Privada"]];
        /* area geografica */
        $areas = Area::select('id', 'nombre')->get();
        /* ultimo reg */
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '8')->orderBy('fecha', 'desc')->take(1)->get();
        $importacion_id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));

        $data['rer'] = 781;
        $data['pres'] = 607;
        $data['iiee'] = 0;
        $data['alumnos'] = 36331;
        $data['docentes'] = 2453;

        return view("educacion.MatriculaDetalle.InterculturalBilingue", compact('anios', 'gestions', 'areas', 'ugels', 'importacion_id', 'fecha', 'data'));
    }

    public function cargarEIBgrafica1(Request $rq)
    {
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            DB::raw("max(fechaActualizacion) as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")
            ->groupBy('ano')
            ->get();
        $fechas = [];
        $cat = [];
        foreach ($impfechas as $key => $value) {
            $fechas[] = $value->fecha;
            $cat[] = $value->ano;
        }
        $impfechas = Importacion::select(
            DB::raw("year(fechaActualizacion) as ano"),
            'id',
            DB::raw("fechaActualizacion as fecha")
        )
            ->where('estado', 'PR')->where('fuenteImportacion_id', "8")->whereIn('fechaActualizacion', $fechas)
            ->orderBy('ano', 'asc')
            ->get();
        $ids = '';
        foreach ($impfechas as $key => $value) {
            if ($key < count($impfechas) - 1)
                $ids .= $value->id . ',';
            else $ids .= $value->id;
        }

        $query = DB::table(DB::raw("(
            select
				case v5.nombre_matricula
					when 'Primaria' then 'Primaria'
                    when 'Secundaria' then 'Secundaria'
                    else 'Inicial'
				end as nivel,
                year(v3.fechaActualizacion) as ano,
                SUM(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) as conteo
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v3.id in ($ids)
            group by nivel,ano
            ) as tb"))
            ->get();
        $data['cat'] = $cat;
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Inicial')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Inicial', 'data' => $xx];
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Primaria')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Primaria', 'data' => $xx];
        $xx = [];
        foreach ($query as $key1 => $value) {
            if ($value->nivel == 'Secundaria')
                foreach ($cat as $key2 => $value2) {
                    if ($value2 == $value->ano)
                        $xx[] = (int)$value->conteo;
                }
        }
        $data['dat'][] = ['name' => 'Secundaria', 'data' => $xx];
        return $data;
    }

    public function cargarEIBgrafica2(Request $rq)
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
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) y
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ($fx) $optgestion $optarea $optugel
            group by mes
            order by mes asc
            ) as xx"))->get();
        $error['base'] = $base;
        $data['cat'] = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
        //$data['cat'] = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $data['dat'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $data['dat'][$value->mes - 1] = (int)$value->y;
        }
        return $data;
    }

    public function cargarEIBgrafica3(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                case v5.nombre_matricula
                    when 'Primaria' then 'Primaria'
                    when 'Secundaria' then 'Secundaria'
                    else 'Inicial'
                end as name,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) y,
                FORMAT(sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)),0) yx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by name
            ) as xx"))->get();
        /* $error['base'] = $base; */
        foreach ($base as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $base;
    }
    public function cargarEIBgrafica4(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                sum(v1.total_hombres) hy,
                sum(v1.total_mujeres) my,
                FORMAT(sum(v1.total_hombres),0) hyx,
                FORMAT(sum(v1.total_mujeres),0) myx
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            ) as xx"))->get();
        $query = $base->first();
        $data[] = ['name' => 'MASCULINO', 'y' => (int)$query->hy, 'yx' => $query->hyx];
        $data[] = ['name' => 'FEMENINO', 'y' => (int)$query->my, 'yx' => $query->myx];
        return $data;
    }

    public function cargarEIBtabla1(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(v1.total_hombres+v1.total_mujeres) tt,
                sum(v1.total_hombres) tth,
                sum(v1.total_mujeres) ttm,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_mujer,0)) ICII5M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_hombre,0)) ICIII1H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_mujer,0)) ICIII1M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_hombre,0)) ICIII2H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_mujer,0)) ICIII2M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_hombre,0)) ICIV3H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_mujer,0)) ICIV3M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_hombre,0)) ICIV4H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_mujer,0)) ICIV4M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_hombre,0)) ICV5H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_mujer,0)) ICV5M,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_hombre,0)) ICV6H,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_mujer,0)) ICV6M
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                    sum(v1.total_hombres+v1.total_mujeres) tt,
                    sum(v1.total_hombres) tth,
                    sum(v1.total_mujeres) ttm,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_hombre,0)) ICII3H,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.tres_anios_mujer,0)) ICII3M,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_hombre,0)) ICII4H,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cuatro_anios_mujer,0)) ICII4M,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_hombre,0)) ICII5H,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.cinco_anios_mujer,0)) ICII5M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_hombre,0)) ICIII1H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.primero_mujer,0)) ICIII1M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_hombre,0)) ICIII2H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.segundo_mujer,0)) ICIII2M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_hombre,0)) ICIV3H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.tercero_mujer,0)) ICIV3M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_hombre,0)) ICIV4H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.cuarto_mujer,0)) ICIV4M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_hombre,0)) ICV5H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.quinto_mujer,0)) ICV5M,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_hombre,0)) ICV6H,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.sexto_mujer,0)) ICV6M
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /* $vv = 0;
        foreach ($base as $key => $value) {
            $value->ptt = 100 * $value->tt / $foot->tt;
            $vv += $value->ptt;
        }
        $foot->ptt = $vv; */
        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.interculturalbilingueTabla1", compact('rq', 'base', 'foot'));
    }

    public function cargarEIBtabla2(Request $rq)
    {
        $ano = $rq->ano;
        $gestion = $rq->gestion;
        $area = $rq->area;
        $ugel = $rq->ugel;

        $optugel = ($ugel == 0 ? "" : " and v6.id=$ugel ");
        $optgestion = ($gestion == 0 ? "" : ($gestion == 3 ? " and v8.id=$gestion " : " and v8.id!=3 "));
        $optarea = $area == 0 ? "" : " and v9.id=$area ";

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
            order by mes desc
                ) as xx"))->take(1)->get();

        $fx = $fechas->first()->fecha;

        $base = DB::table(DB::raw("(
            select
                v6.nombre ugel,
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) tt,
                sum(IF(v5.nombre_matricula like '%Inicial%',v1.total_hombres+v1.total_mujeres,0)) inc,
                sum(IF(v5.nombre_matricula like '%Primaria%',v1.total_hombres+v1.total_mujeres,0)) prm,
                sum(IF(v5.nombre_matricula like '%PRITE%',IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) prt
            from edu_matricula_detalle as v1
            inner join edu_matricula as v2 on v2.id=v1.matricula_id
            inner join par_importacion as v3 on v3.id=v2.importacion_id
            inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
            inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
            inner join edu_ugel as v6 on v6.id=v4.Ugel_id
            inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
            inner join edu_tipogestion as v8 on v8.id=v7.dependencia
            inner join edu_area as v9 on v9.id=v4.Area_id
            where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
            group by ugel
            ) as xx"))->get();
        $foot = DB::table(DB::raw("(
                select
                sum(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) tt,
                    sum(IF(v5.nombre_matricula like '%Inicial%',v1.total_hombres+v1.total_mujeres,0)) inc,
                    sum(IF(v5.nombre_matricula like '%Primaria%',v1.total_hombres+v1.total_mujeres,0)) prm,
                    sum(IF(v5.nombre_matricula like '%PRITE%',IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres),0)) prt
                from edu_matricula_detalle as v1
                inner join edu_matricula as v2 on v2.id=v1.matricula_id
                inner join par_importacion as v3 on v3.id=v2.importacion_id
                inner join edu_institucioneducativa as v4 on v4.id=v1.institucioneducativa_id and v4.es_eib='SI'
                inner join edu_nivelmodalidad as v5 on v5.id=v4.NivelModalidad_id
                inner join edu_ugel as v6 on v6.id=v4.Ugel_id
                inner join edu_tipogestion as v7 on v7.id=v4.TipoGestion_id
                inner join edu_tipogestion as v8 on v8.id=v7.dependencia
                inner join edu_area as v9 on v9.id=v4.Area_id
                where v3.estado='PR' and v5.tipo in ('EBR') and v2.anio_id=$ano and v3.fechaActualizacion in ('$fx') $optgestion $optarea $optugel
                ) as xx"))->get()->first();
        /* $data['body'] = $base;
        $data['foot'] = $foot;
        return $data; */
        return view("educacion.MatriculaDetalle.interculturalbilingueTabla2", compact('rq', 'base', 'foot'));
    }

    public function cargarpresupuestoxxx()
    {
        return view("educacion.MatriculaDetalle.presupuestoxxx");
    }
    public function cargarpresupuestoview1()
    {
        return view("educacion.MatriculaDetalle.presupuestoview1");
    }

    public function cargarpresupuestoview2()
    {
        return view("educacion.MatriculaDetalle.presupuestoview2");
    }

    public function cargarpresupuestoview3()
    {
        return view("educacion.MatriculaDetalle.presupuestoview3");
    }

    public function cargarpresupuestoview11()
    {
        return view("educacion.MatriculaDetalle.presupuestoview11");
    }
    public function cargarpresupuestoview12()
    {
        return view("educacion.MatriculaDetalle.presupuestoview12");
    }
    public function cargarpresupuestoview13()
    {
        return view("educacion.MatriculaDetalle.presupuestoview13");
    }
    public function cargarpresupuestoview14()
    {
        return view("educacion.MatriculaDetalle.presupuestoview14");
    }
    public function cargarpresupuestoview15()
    {
        return view("educacion.MatriculaDetalle.presupuestoview15");
    }
}
