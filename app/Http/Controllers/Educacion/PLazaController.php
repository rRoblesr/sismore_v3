<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\PLaza;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\PlazaRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PLazaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function DocentesPrincipal()
    {
        /* anios */
        $anios = PLazaRepositorio::listar_anios();
        /* ugels */
        $ugels = Ugel::select('id', 'nombre', 'codigo')->where('codigo', 'like', '25%')->orderBy('nombre', 'asc')->get();

        return view('educacion.Plaza.DocentesPrincipal', compact('anios', 'ugels'));
    }

    public function nemuDocente($importacion_id, $anio)
    {
        //$info['v1'] = PlazaRepositorio::listar_docentesporniveleducativo_grafica($importacion_id);
        //$info['v2'] = PlazaRepositorio::listar_docentesyauxiliaresporugel_grafica($importacion_id);
        //$info['v3'] = PlazaRepositorio::listar_trabajadoresadministrativosporugel_grafica($importacion_id);
        //$info['v4'] = PlazaRepositorio::listar_trabajadorespecporugel_grafica($importacion_id);
        /* $info['opt1'] = PlazaRepositorio::listar_tipotrabajadores($importacion_id, 1)->count();
        $info['opt2'] = PlazaRepositorio::listar_tipotrabajadores($importacion_id, 2)->count();
        $info['opt3'] = PlazaRepositorio::listar_tipotrabajadores($importacion_id, 3)->count();
        $info['opt4'] = PlazaRepositorio::listar_tipotrabajadores($importacion_id, 4)->count();
        $info['v1'] = PlazaRepositorio::listar_plazasegununidaddegestioneducativa_grafica($importacion_id);
        $info['v2'] = PlazaRepositorio::listar_plazaseguntipodeniveleducactivo_grafica($importacion_id);
        $info['v3'] = PlazaRepositorio::listar_plazaseguntipotrabajador_grafica($importacion_id);
        $info['v4'] = PlazaRepositorio::listar_plazadocenteseguntipodeniveleducactivo_grafica($importacion_id);
        $info['v5'] = PlazaRepositorio::listar_plazadocentesegunsituacionlaboral_grafica($importacion_id);
        $info['v6'] = PlazaRepositorio::listar_plazadocentesegunregimenlaboral_grafica($importacion_id);
        $info['v7'] = PlazaRepositorio::listar_plazadocentesegunano_grafica();
        $info['v8'] = PlazaRepositorio::listar_plazadocentesegunmes_grafica($importacion_id, $anio);
        $info['DT'] = PlazaRepositorio::listar_totalplazacontratadoynombradossegunugelyniveleducativo($importacion_id);
        return response()->json(compact('info')); */
    }

    public function DocentesPrincipalHead(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['opt1'] = PlazaRepositorio::listar_tipotrabajadores($imp, 1, $rq->ugel)->count();
        $info['opt2'] = PlazaRepositorio::listar_tipotrabajadores($imp, 2, $rq->ugel)->count();
        $info['opt3'] = PlazaRepositorio::listar_tipotrabajadores($imp, 3, $rq->ugel)->count();
        $info['opt4'] = PlazaRepositorio::listar_tipotrabajadores($imp, 4, $rq->ugel)->count();
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra1(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v1'] = PlazaRepositorio::listar_plazasegununidaddegestioneducativa_grafica($imp, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra2(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v2'] = PlazaRepositorio::listar_plazaseguntipodeniveleducactivo_grafica($imp, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra3(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v3'] = PlazaRepositorio::listar_plazaseguntipotrabajador_grafica($imp, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra4(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v4'] = PlazaRepositorio::listar_plazadocenteseguntipodeniveleducactivo_grafica($imp, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra5(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v5'] = PlazaRepositorio::listar_plazadocentesegunsituacionlaboral_grafica($imp, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra6(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v6'] = PlazaRepositorio::listar_plazadocentesegunregimenlaboral_grafica($imp, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra7(Request $rq)
    {
        //$imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v7'] = PlazaRepositorio::listar_plazassegunano_grafica($rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra8(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v8'] = PlazaRepositorio::listar_plazassegunmes_grafica($imp, $rq->anio, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra9(Request $rq)
    {
        //$imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v9'] = PlazaRepositorio::listar_plazadocentesegunano_grafica($rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalgra10(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0)->id;
        $info['v10'] = PlazaRepositorio::listar_plazadocentesegunmes_grafica($imp, $rq->anio, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalDT1(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0);
        $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
        $info['DT'] = PlazaRepositorio::listar_totalplazacontratadoynombradossegunugelyniveleducativo($imp->id, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalDT2(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0);
        $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
        $info['DT'] = PlazaRepositorio::cargarresumendeplazatabla2($imp->id, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalDT3(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0);
        $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
        $info['DT'] = PlazaRepositorio::cargarresumendeplazatabla3($imp->id, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalDT4(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0);
        $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
        $info['DT'] = PlazaRepositorio::cargarresumendeplazatabla4($rq, $imp->id, $rq->ugel);
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalDT5(Request $rq)
    {
        $imp = $this->cargarultimoimportado($rq->anio, 0);
        $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
        $body = PlazaRepositorio::cargarresumendeplazatabla5($rq, $imp->id, $rq->ugel);
        $info['DT'] = view('educacion.Plaza.DocentesPrincipalTabla5', compact('body'))->render();
        return response()->json(compact('info'));
    }

    public function DocentesPrincipalDT6(Request $rq)
    {
        $imp = 1952; //$this->cargarultimoimportado($rq->anio, 0);
        $info['fecha'] = date('d/m/Y', strtotime($imp));
        $body = PlazaRepositorio::cargarresumendeplazatabla6($rq, $imp, $rq->ugel);
        $info['DT'] = view('educacion.Plaza.DocentesPrincipalTabla6', compact('body'))->render();
        return response()->json(compact('info'));
    }

    public function cargardistritos($provincia)
    {
        $distritos = PlazaRepositorio::listar_distrito($provincia);
        return response()->json(compact('distritos'));
    }

    public function cargarmes($anio)
    {
        $meses = PlazaRepositorio::listar_meses($anio);
        return response()->json(compact('meses'));
    }

    public function cargarultimoimportado($anio, $mes)
    {
        $importados = PlazaRepositorio::listar_importados($anio, $mes);
        if (count($importados) > 0)
            $importado = $importados->first();
        else
            $importado = null;

        return $importado;
        //return response()->json(compact('importado'));
    }

    public function datoIndicadorPLaza(Request $request)
    {
        $dato['tt'] = PlazaRepositorio::listar_profesorestitulados($request->fecha, $request->nivel, $request->provincia, $request->distrito);
        $dato['tu'] = PlazaRepositorio::listar_profesorestituladougel($request->fecha, $request->nivel, 1);
        return response()->json(compact('dato'));
    }


    public function coberturaplaza()
    {
        /* anos */
        $anios = Importacion::select(DB::raw('YEAR(fechaActualizacion) as ano'))
            ->where('estado', 'PR')->where('fuenteImportacion_id', '2')
            ->orderBy('ano', 'desc')->distinct()->get();
        /* tipo modalidad */
        $tipo = NivelModalidad::select('tipo')->where(DB::raw('tipo is not null'), true)->groupBy('tipo')->get();
        /* ugels */
        $ugels = Ugel::select('id', 'nombre', 'codigo')->where('codigo', 'like', '25%')->orderBy('nombre', 'asc')->get();
        /* ultimo reg subido */
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '2')
            ->orderBy('fecha', 'desc')->take(1)->get();
        $importacion_id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));
        //return [$anios, $tipo, $nivel, $imp, $fecha];
        return view("educacion.Plaza.CoberturaPlaza", compact('anios', 'tipo', 'ugels', 'importacion_id', 'fecha'));
    }

    public function cargarcoberturaplazatabla1(Request $rq)
    {
        $ano = (int)$rq->ano;
        $tipo = $rq->tipo;
        $nivel = $rq->nivel;
        $ugel = $rq->ugel;

        //$anios = ['2022', '2021', '2020'];
        $anoA = 0;
        $anoA = $ano - 1;
        /* $error['anoA'] = $anoA; */

        $fechas = DB::table(DB::raw("(
                select
                    distinct
                    v3.fechaActualizacion fecha,
                    year(v3.fechaActualizacion) ano,
                    month(v3.fechaActualizacion) mes,
                    day(v3.fechaActualizacion) dia
                from edu_plaza as v1
                inner join par_importacion as v3 on v3.id=v1.importacion_id
                where v3.estado='PR' and YEAR(v3.fechaActualizacion)=$ano
                order by fecha desc
            ) as xx"))
            ->select('mes', DB::raw('max(fecha) fecha'))
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        /* $error['fechas'] = $fechas; */

        $fx = [];
        $anoI = 0;
        $anoF = 0;
        foreach ($fechas as $key => $value) {
            $fx[] = $value->fecha;
            if ($key == 0) $anoI = $value->mes;
            if ($key == (count($fechas) - 1)) $anoF = $value->mes + 1;
        }

        /* $error['fx'] = $fx;
        $error['anoI'] = $anoI;
        $error['anoF'] = $anoF; */

        $baseA = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v8', 'v8.id', '=', 'v1.nivelModalidad_id')
            ->where('v6.estado', 'PR')->where('v5.nombre', '!=', 'POR REEMPLAZO')
            //->where('v3.nombre', 'DOCENTE')->where('v4.nombre', 'DOCENTE')
            ->where(DB::raw('YEAR(v6.fechaActualizacion)'), $anoA)
            ->where(DB::raw('MONTH(v6.fechaActualizacion)'), 12)
            ->groupBy('id', 'ugel')
            ->select(
                'v7.id',
                DB::raw('v7.nombre as ugel'),
                DB::raw('count(v1.id) as dic'),
            );
        if ($ugel != 0) $baseA = $baseA->where('v7.id', $ugel);
        if ($tipo != 0) $baseA = $baseA->where('v8.tipo', $tipo);
        if ($nivel != 0) $baseA = $baseA->where('v8.id', $nivel);
        $baseA = $baseA->get();

        if (count($baseA) == 0) {
            $baseA = Ugel::select('nombre as ugel', DB::raw('0 as dic'))->get();
        }
        /* $error['baseA'] = $baseA; */

        $base = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v8', 'v8.id', '=', 'v1.nivelModalidad_id')
            ->where('v6.estado', 'PR')->where('v5.nombre', '!=', 'POR REEMPLAZO')
            //->where('v3.nombre', 'DOCENTE')->where('v4.nombre', 'DOCENTE')
            ->where(DB::raw('YEAR(v6.fechaActualizacion)'), $ano)
            ->whereIn('v6.fechaActualizacion', $fx)
            ->groupBy('id', 'ugel')
            ->select(
                'v7.id',
                DB::raw('v7.nombre as ugel'),
                DB::raw('count(v1.id) as conteo'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 1,1,0)) as `ene`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 2,1,0))-sum(IF(month(v6.fechaActualizacion)= 1,1,0)) as `feb`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 3,1,0))-sum(IF(month(v6.fechaActualizacion)= 2,1,0)) as `mar`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 4,1,0))-sum(IF(month(v6.fechaActualizacion)= 3,1,0)) as `abr`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 5,1,0))-sum(IF(month(v6.fechaActualizacion)= 4,1,0)) as `may`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 6,1,0))-sum(IF(month(v6.fechaActualizacion)= 5,1,0)) as `jun`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 7,1,0))-sum(IF(month(v6.fechaActualizacion)= 6,1,0)) as `jul`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 8,1,0))-sum(IF(month(v6.fechaActualizacion)= 7,1,0)) as `ago`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 9,1,0))-sum(IF(month(v6.fechaActualizacion)= 8,1,0)) as `set`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 10,1,0))-sum(IF(month(v6.fechaActualizacion)= 9,1,0)) as `oct`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 11,1,0))-sum(IF(month(v6.fechaActualizacion)= 10,1,0)) as `nov`'),
                DB::raw('sum(IF(month(v6.fechaActualizacion)= 12,1,0))-sum(IF(month(v6.fechaActualizacion)= 11,1,0)) as `dic`'),
            );
        if ($ugel != 0) $base = $base->where('v7.id', $ugel);
        if ($tipo != 0) $base = $base->where('v8.tipo', $tipo);
        if ($nivel != 0) $base = $base->where('v8.id', $nivel);
        $base = $base->get();

        $foot = ['meta' => 0, 'ene' => 0, 'feb' => 0, 'mar' => 0, 'abr' => 0, 'may' => 0, 'jun' => 0, 'jul' => 0, 'ago' => 0, 'set' => 0, 'oct' => 0, 'nov' => 0, 'dic' => 0, 'total' => 0, 'avance' => 0,];

        foreach ($base as $reg => $bb) {
            $bb->treg = ($anoF != 1 ? $bb->ene : 0) + ($anoF != 2 ? $bb->feb : 0) + ($anoF != 3 ? $bb->mar : 0) + ($anoF != 4 ? $bb->abr : 0) +
                ($anoF != 5 ? $bb->may : 0) +  ($anoF != 6 ? $bb->jun : 0) +  ($anoF != 7 ? $bb->jul : 0) +  ($anoF != 8 ? $bb->ago : 0) +
                ($anoF != 9 ? $bb->set : 0) + ($anoF != 10 ? $bb->oct : 0) +  ($anoF != 11 ? $bb->nov : 0) + ($anoF != 12 ? $bb->dic : 0);

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

        /* $error['base'] = $base; */

        //return $error;
        return view("educacion.Plaza.CoberturaPlazaTabla1", compact('rq', 'base', 'anoI', 'anoF', 'foot'));
    }

    public function cargarcoberturaplazatabla2(Request $rq)
    {
        $ano = (int)$rq->ano;
        $tipo = $rq->tipo;
        $nivel = $rq->nivel;
        $ugel = $rq->ugel;

        $imp = $this->cargarultimoimportado($ano, 0);

        $bases = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v8', 'v8.id', '=', 'v1.nivelModalidad_id')
            //->where('v3.nombre', 'DOCENTE')
            //->where('v4.nombre', 'DOCENTE')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v6.estado', 'PR')
            //->where(DB::raw('YEAR(v6.fechaActualizacion)'), $ano)
            ->where('v6.fechaActualizacion', $imp->fechaActualizacion)
            ->groupBy('tipox', 'subx')
            ->select(
                DB::raw('v4.nombre as tipox'),
                DB::raw('v3.nombre as subx'),
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(IF(v2.id=1,1,0)) as designado'),
                DB::raw('SUM(IF(v2.id=2,1,0)) as encargado'),
                DB::raw('SUM(IF(v2.id=3,1,0)) as nombrado'),
                DB::raw('SUM(IF(v2.id=4,1,0)) as contratado'),
                DB::raw('SUM(IF(v2.id=5,1,0)) as vacante'),
                DB::raw('SUM(IF(v2.id=6,1,0)) as destacado'),
                DB::raw('SUM(IF(v2.id=7,1,0)) as desigconfian'),
                DB::raw('SUM(IF(v2.id=8,1,0)) as desigexcep'),
                //DB::raw('SUM(IF(v2.id=9,1,0)) as desigtemp'),
            );
        if ($ugel != 0) $bases = $bases->where('v7.id', $ugel);
        if ($tipo != 0) $bases = $bases->where('v8.tipo', $tipo);
        if ($nivel != 0) $bases = $bases->where('v8.id', $nivel);
        $bases = $bases->get();

        $heads = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v8', 'v8.id', '=', 'v1.nivelModalidad_id')
            //->where('v3.nombre', 'DOCENTE')
            //->where('v4.nombre', 'DOCENTE')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v6.estado', 'PR')
            //->where(DB::raw('YEAR(v6.fechaActualizacion)'), $ano)
            ->where('v6.fechaActualizacion', $imp->fechaActualizacion)
            ->groupBy('tipox')
            ->select(
                DB::raw('v4.nombre as tipox'),
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(IF(v2.id=1,1,0)) as designado'),
                DB::raw('SUM(IF(v2.id=2,1,0)) as encargado'),
                DB::raw('SUM(IF(v2.id=3,1,0)) as nombrado'),
                DB::raw('SUM(IF(v2.id=4,1,0)) as contratado'),
                DB::raw('SUM(IF(v2.id=5,1,0)) as vacante'),
                DB::raw('SUM(IF(v2.id=6,1,0)) as destacado'),
                DB::raw('SUM(IF(v2.id=7,1,0)) as desigconfian'),
                DB::raw('SUM(IF(v2.id=8,1,0)) as desigexcep'),
            );
        if ($ugel != 0) $heads = $heads->where('v7.id', $ugel);
        if ($tipo != 0) $heads = $heads->where('v8.tipo', $tipo);
        if ($nivel != 0) $heads = $heads->where('v8.id', $nivel);
        $heads = $heads->get();

        $foot = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v8', 'v8.id', '=', 'v1.nivelModalidad_id')
            //->where('v3.nombre', 'DOCENTE')
            //->where('v4.nombre', 'DOCENTE')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v6.estado', 'PR')
            //->where(DB::raw('YEAR(v6.fechaActualizacion)'), $ano)
            ->where('v6.fechaActualizacion', $imp->fechaActualizacion)
            ->select(
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(IF(v2.id=1,1,0)) as designado'),
                DB::raw('SUM(IF(v2.id=2,1,0)) as encargado'),
                DB::raw('SUM(IF(v2.id=3,1,0)) as nombrado'),
                DB::raw('SUM(IF(v2.id=4,1,0)) as contratado'),
                DB::raw('SUM(IF(v2.id=5,1,0)) as vacante'),
                DB::raw('SUM(IF(v2.id=6,1,0)) as destacado'),
                DB::raw('SUM(IF(v2.id=7,1,0)) as desigconfian'),
                DB::raw('SUM(IF(v2.id=8,1,0)) as desigexcep'),
            );
        if ($ugel != 0) $foot = $foot->where('v7.id', $ugel);
        if ($tipo != 0) $foot = $foot->where('v8.tipo', $tipo);
        if ($nivel != 0) $foot = $foot->where('v8.id', $nivel);
        $foot = $foot->first();


        //return $base;
        return view("educacion.Plaza.CoberturaPlazaTabla2", compact('rq', 'bases', 'heads', 'foot'));
    }

    public function cargarcoberturaplazagrafica1(Request $rq)
    {
        $ano = (int)$rq->ano;
        $tipo = $rq->tipo;
        $nivel = $rq->nivel;
        $ugel = $rq->ugel;

        $fechas = DB::table(DB::raw("(
            select
                distinct
                v3.fechaActualizacion fecha,
                year(v3.fechaActualizacion) ano,
                month(v3.fechaActualizacion) mes,
                day(v3.fechaActualizacion) dia
            from edu_plaza as v1
            inner join par_importacion as v3 on v3.id=v1.importacion_id
            where v3.estado='PR' and YEAR(v3.fechaActualizacion)=$ano
            order by fecha desc
        ) as xx"))
            ->select('mes', DB::raw('max(fecha) fecha'))
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        /* $error['fechas'] = $fechas; */

        $fx = [];
        $anoI = 0;
        $anoF = 0;
        foreach ($fechas as $key => $value) {
            $fx[] = $value->fecha;
            if ($key == 0) $anoI = $value->mes;
            if ($key == (count($fechas) - 1)) $anoF = $value->mes + 1;
        }

        /* $error['fx'] = $fx;
        $error['anoI'] = $anoI;
        $error['anoF'] = $anoF; */

        $base = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->join('edu_nivelmodalidad as v8', 'v8.id', '=', 'v1.nivelModalidad_id')
            ->where('v6.estado', 'PR')->where('v5.nombre', '!=', 'POR REEMPLAZO')
            //->where('v3.nombre', 'DOCENTE')->where('v4.nombre', 'DOCENTE')
            ->where(DB::raw('YEAR(v6.fechaActualizacion)'), $ano)
            ->whereIn('v6.fechaActualizacion', $fx)
            ->groupBy('mes', 'name')
            ->select(
                DB::raw('month(v6.fechaActualizacion) as mes'),
                DB::raw("case month(v6.fechaActualizacion)
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
                        END AS name"),
                DB::raw('count(v1.id) as y'),

            );
        if ($ugel != 0) $base = $base->where('v7.id', $ugel);
        if ($tipo != 0) $base = $base->where('v8.tipo', $tipo);
        if ($nivel != 0) $base = $base->where('v8.id', $nivel);
        $base = $base->get();

        /* $error['base'] = $base; */

        $data['cat'] = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SETIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $data['dat'] = [null, null, null, null, null, null, null, null, null, null, null, null];
        foreach ($base as $key => $value) {
            $data['dat'][$value->mes - 1] = (int)$value->y;
        }

        return $data;
    }
}
