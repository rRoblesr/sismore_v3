<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\PadronRER;
use App\Models\Educacion\PLaza;
use App\Models\Educacion\Ugel;
use Illuminate\Support\Facades\DB;

class PlazaRepositorio
{
    public static function listaImportada($id)
    {
        $query = DB::table(DB::raw("(
            select
                v1.id,
                v5.nombre dre,
                v4.nombre ugel,
                'UCAYALI' departamento,
                v8.nombre provincia,
                v7.nombre distrito,
                v6.nombre centropoblado,
                v3.codModular modular,
                v3.nombreInstEduc iiee,
                v9.codigo codnivel,
                v9.nombre nivel,
                v9.tipo tiponivel,
                v10.codigo codgestion,
                v10.nombre gestiondependencia,
                v11.codigo codtipogestion,
                v11.nombre tipogestion,
                v1.total_estudiantes,
                v1.matricula_definitiva,
                v1.matricula_proceso,
                v1.dni_validado,
                v1.dni_sin_validar,
                v1.registrado_sin_dni,
                v1.total_grados,
                v1.total_secciones,
                v1.nominas_generadas,
                v1.nominas_aprobadas,
                v1.nominas_por_rectificar,
                v1.tres_anios_hombre,
                v1.tres_anios_mujer,
                v1.cuatro_anios_hombre,
                v1.cuatro_anios_mujer,
                v1.cinco_anios_hombre,
                v1.cinco_anios_mujer,
                v1.primero_hombre,
                v1.primero_mujer,
                v1.segundo_hombre,
                v1.segundo_mujer,
                v1.tercero_hombre,
                v1.tercero_mujer,
                v1.cuarto_hombre,
                v1.cuarto_mujer,
                v1.quinto_hombre,
                v1.quinto_mujer,
                v1.sexto_hombre,
                v1.sexto_mujer,
                v1.cero_anios_hombre,
                v1.cero_anios_mujer,
                v1.un_anio_hombre,
                v1.un_anio_mujer,
                v1.dos_anios_hombre,
                v1.dos_anios_mujer,
                v1.mas_cinco_anios_hombre,
                v1.mas_cinco_anios_mujer,
                v1.total_hombres,
                v1.total_mujeres
            from edu_matricula_detalle v1
            inner join edu_matricula v2 on v2.id=v1.matricula_id
            inner join edu_institucioneducativa v3 on v3.id=v1.institucioneducativa_id
            inner join edu_ugel v4 on v4.id=v3.Ugel_id
            inner join edu_ugel v5 on v5.id=v4.dependencia
            inner join edu_centropoblado v6 on v6.id=v3.CentroPoblado_id
            inner join par_ubigeo v7 on v7.id=v6.Ubigeo_id
            inner join par_ubigeo v8 on v8.id=v7.dependencia
            inner join edu_nivelmodalidad v9 on v9.id=v3.NivelModalidad_id
            inner join edu_tipogestion v10 on v10.id=v3.TipoGestion_id
            inner join edu_tipogestion v11 on v11.id=v10.dependencia
            where v2.importacion_id=$id
        ) astb"))->get();
        return $query;
    }

    public static function listar_provincia()
    {
        $query = PLaza::select('v3.id', 'v3.nombre')
            ->join('par_ubigeo as v2', 'v2.id', '=', 'edu_plaza.ubigeo_id')
            ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.dependencia')
            ->groupBy('v3.id')->groupBy('v3.nombre')
            ->get();
        return $query;
    }

    public static function listar_distrito($provincia)
    {
        $query = PLaza::select('v2.id', 'v2.nombre')
            ->join('par_ubigeo as v2', 'v2.id', '=', 'edu_plaza.ubigeo_id')
            /*->join('par_ubigeo as v3', 'v3.id', '=', 'v2.dependencia')*/
            ->where('v2.dependencia', $provincia)
            ->groupBy('v2.id')->groupBy('v2.nombre')
            ->get();
        return $query;
    }

    public static function count_docente($importacion_id)
    {
        /*
         1: docente
        15: docente
        */
        $query = DB::table(
            DB::raw("(SELECT  distinct v1.documento_identidad dni FROM edu_plaza v1
            inner join edu_tipotrabajador v2 on v2.id=v1.tipoTrabajador_id
            where v1.importacion_id=$importacion_id and v2.dependencia=1 AND v2.id=15 and v1.documento_identidad!='') as tb")
        )
            ->select(DB::raw('count(dni) as conteo'))
            ->first();
        return $query->conteo;
    }

    public static function count_docente2($importacion_id, $provincia, $distrito, $tipogestion, $ambito)
    {
        /*
         1: docente
         8: docente
         9: docente
        15: docente
        */
        $sent = "(SELECT  distinct v1.documento_identidad dni FROM edu_plaza v1
        inner join edu_tipotrabajador v2 on v2.id=v1.tipoTrabajador_id
        inner join par_ubigeo v3 on v3.id=v1.ubigeo_id
        inner join par_zona v4 on v4.id=v1.zona_id
        where v1.importacion_id=$importacion_id and v2.dependencia=1 AND v2.id in (8,9,15) and v1.documento_identidad!='' ";
        if ($provincia > 0) $sent .= " and v3.dependencia=$provincia";
        if ($distrito > 0) $sent .= " and v3.id=$distrito";
        if ($tipogestion > 0) {
            if ($tipogestion == 3) {
                $sent .= " and v1.gestion='privada'";
            } else {
                //$sent += " and v3.";
            }
        }
        if ($ambito > 0) {
            if ($ambito == 1)
                $sent .= " and v4.nombre like '%URBANO%'";
            if ($ambito == 2)
                $sent .= " and v4.nombre like '%RURAL%'";
        }

        $sent .= " ) as tb";

        $query = DB::table(DB::raw($sent))
            ->select(DB::raw('count(dni) as conteo'))
            ->first();
        return $query->conteo;
    }

    public static function listar_profesorestitulados($importacion_id, $nivel, $provincia, $distrito)
    {
        $nivelxx = $nivel == 'SECUNDARIA' ? '8' : ($nivel == 'PRIMARIA' ? '7' : '1,2,14');
        $ubicacion = '';
        if ($provincia > 0 && $distrito > 0) $ubicacion = ' and v4.id=' . $distrito;
        else if ($provincia > 0 && $distrito == 0) $ubicacion = ' and v4.dependencia=' . $provincia;
        $query =  DB::table(DB::raw('(select if(v1.esTitulado=1,"SI","NO") as titulado,count(v1.esTitulado) as conteo from edu_plaza as v1
        inner join par_importacion as v2 on v2.id=v1.importacion_id
        inner join edu_tipotrabajador as v3 on v3.id=v1.tipoTrabajador_id
        inner join par_ubigeo as v4 on v4.id=v1.ubigeo_id
        where tipoTrabajador_id in (13,6) and v1.situacion="AC" and v1.importacion_id=' . $importacion_id . ' and v1.nivelModalidad_id in (' . $nivelxx . ')' . $ubicacion . '
        group by v1.esTitulado) as tb'))
            ->select('titulado as name', 'conteo as y')
            ->orderBy('titulado', 'desc')
            ->get();
        return $query;
    }

    public static function listar_profesorestituladougel($importacion_id, $nivel, $titulado = null)
    {
        $nivelxx = $nivel == 'SECUNDARIA' ? '8' : ($nivel == 'PRIMARIA' ? '7' : '1,2,14');
        $query = DB::table(DB::raw('(select v5.nombre as ugel,count(v5.nombre) as conteo from edu_plaza as v1
        inner join par_importacion as v2 on v2.id=v1.importacion_id
        inner join edu_tipotrabajador as v3 on v3.id=v1.tipoTrabajador_id
        inner join par_ubigeo as v4 on v4.id=v1.ubigeo_id
        inner join edu_ugel as v5 on v5.id=v1.ugel_id
        where tipoTrabajador_id in (13,6) and v1.situacion="AC" and v1.esTitulado=' . $titulado . ' and
              v1.importacion_id=' . $importacion_id . ' and v1.nivelModalidad_id in (' . $nivelxx . ')
        group by v5.nombre) as tb'))
            ->select('ugel as name', 'conteo as y')
            ->get();
        return $query;
    }
    public static function anitos()
    {
        $query = Area::all();
        return $query;
    }

    public static function listar_anios()
    {
        $query = PLaza::select(DB::raw('YEAR(fechaActualizacion) as anio'))
            ->distinct()
            ->join('par_importacion as v2', 'v2.id', '=', 'edu_plaza.importacion_id')
            ->where('v2.estado', 'PR')
            ->orderBy('anio', 'desc')
            ->get();
        return $query;
    }
    public static function listar_meses($anio)
    {
        $query = PLaza::select(DB::raw('MONTH(fechaActualizacion) as mes'))
            ->distinct()
            ->join('par_importacion as v2', 'v2.id', '=', 'edu_plaza.importacion_id')
            ->where('v2.estado', 'PR')
            ->where(DB::raw('YEAR(fechaActualizacion)'), $anio)
            ->orderBy('mes', 'desc')
            ->get();
        return $query;
    }
    public static function listar_importados($anio, $mes)
    {
        $query = PLaza::select('v2.id', 'v2.fechaActualizacion')
            ->join('par_importacion as v2', 'v2.id', '=', 'edu_plaza.importacion_id')
            ->where('v2.estado', 'PR')
            ->where(DB::raw('YEAR(fechaActualizacion)'), $anio)
            //->where(DB::raw('MONTH(fechaActualizacion)'), $mes)
            ->distinct()
            ->orderBy('v2.fechaActualizacion', 'desc')
            ->get();
        return $query;
    }

    public static function listar_tipotrabajadores($importacion_id, $tipoTrabajador_id, $ugel_id)
    {/* 1	DOCENTE
        2	ADMINISTRATIVO
        3	CAS
        4	PEC */
        $query = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v2', 'v2.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipo_registro_plaza as v3', 'v3.id', '=', 'v1.tipo_registro_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v1.ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v2.dependencia', $tipoTrabajador_id)
            ->where('v3.id', '!=', '3')
            ->select('v1.*');
        if ($ugel_id != 0) $query = $query->where('v4.id', $ugel_id);
        $query = $query->get();
        return $query;
    }
    public static function listar_docentesporniveleducativo_grafica($importacion_id)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v2', 'v2.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v1.nivelModalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v2.dependencia', '1')
            ->whereIn('v2.id', ['15', '16'])
            ->groupBy('v3.tipo', 'v2.nombre')
            ->select('v3.tipo', 'v2.nombre', DB::raw('count(v1.id) as conteo'))
            ->get();

        $categoriax = [];
        $categoria = [];
        $data[] = ['name' => 'TOTAL DOCENTES', 'data' => []];
        $data[] = ['name' => 'TOTAL AUXILIARES', 'data' => []];
        foreach ($result1 as $key => $value) {
            $categoriax[] = $value->tipo;
        }
        $categoriax = array_unique($categoriax);
        foreach ($categoriax as $value) {
            $categoria[] = $value;
        }
        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($result1 as  $value) {
                if ($value->tipo == $cat && $value->nombre == 'DOCENTE') {
                    $data[0]['data'][$pos] = $value->conteo;
                }
                if ($value->tipo == $cat && $value->nombre == 'AUXILIAR DE EDUCACION') {
                    $data[1]['data'][$pos] = $value->conteo;
                }
            }
        }
        $dato['categoria'] = $categoria;
        $dato['data'] = $data;
        return $dato;
    }
    public static function listar_docentesyauxiliaresporugel_grafica($importacion_id)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v2', 'v2.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_ugel as v3', 'v3.id', '=', 'v1.ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v2.dependencia', '1')
            ->whereIn('v2.id', ['15', '16'])
            ->groupBy('v3.nombre', 'v2.nombre')
            ->select('v3.nombre as ugel', 'v2.nombre as subtipo', DB::raw('count(v1.id) as conteo'))
            ->get();
        $categoriax = [];
        $categoria = [];
        $data[] = ['name' => 'TOTAL DOCENTES', 'data' => []];
        $data[] = ['name' => 'TOTAL AUXILIARES', 'data' => []];
        foreach ($result1 as $key => $value) {
            $categoriax[] = $value->ugel;
        }
        $categoriax = array_unique($categoriax);
        foreach ($categoriax as $value) {
            $categoria[] = $value;
        }
        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($result1 as  $value) {
                if ($value->ugel == $cat && $value->subtipo == 'DOCENTE') {
                    $data[0]['data'][$pos] = $value->conteo;
                }
                if ($value->ugel == $cat && $value->subtipo == 'AUXILIAR DE EDUCACION') {
                    $data[1]['data'][$pos] = $value->conteo;
                }
            }
        }
        $dato['categoria'] = $categoria;
        $dato['data'] = $data;
        return $dato;
    }
    public static function listar_trabajadoresadministrativosporugel_grafica($importacion_id)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v2', 'v2.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v2.dependencia')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v1.ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->whereIn('v3.id', ['2', '3'])
            ->groupBy('v4.nombre', 'v3.nombre')
            ->orderBy('v4.nombre')
            ->select('v4.nombre as ugel', 'v3.nombre as tipo', DB::raw('count(v1.id) as conteo'))
            ->get();
        $categoriax = [];
        $categoria = [];
        $data[] = ['name' => 'TOTAL ADMINISTRATIVOS', 'data' => []];
        $data[] = ['name' => 'TOTAL CAS', 'data' => []];
        foreach ($result1 as $key => $value) {
            $categoriax[] = $value->ugel;
        }
        $categoriax = array_unique($categoriax);
        foreach ($categoriax as $value) {
            $categoria[] = $value;
        }
        foreach ($categoria as $pos => $cat) {
            $data[0]['data'][$pos] = 0;
            $data[1]['data'][$pos] = 0;
            foreach ($result1 as  $value) {
                if ($value->ugel == $cat && $value->tipo == 'ADMINISTRATIVO') {
                    $data[0]['data'][$pos] = $value->conteo;
                }
                if ($value->ugel == $cat && $value->tipo == 'CAS') {
                    $data[1]['data'][$pos] = $value->conteo;
                }
            }
        }
        $dato['categoria'] = $categoria;
        $dato['data'] = $data;
        return $dato;
    }
    public static function listar_trabajadorespecporugel_grafica($importacion_id)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v2', 'v2.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v2.dependencia')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v1.ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v3.id', '4')
            ->groupBy('v4.nombre', 'v3.nombre')
            ->orderBy('v4.nombre')
            ->select('v4.nombre as name', DB::raw('count(v1.id) as y'))
            ->get();
        return $result1;
    }


    public static function listar_plazasegununidaddegestioneducativa_grafica($importacion_id, $ugel)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_institucioneducativa as v2', 'v2.id', '=', 'v1.institucionEducativa_id')
            ->join('edu_tipogestion as v3', 'v3.id', '=', 'v2.TipoGestion_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v1.Ugel_id')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('v4.nombre')
            ->select('v4.nombre as name', DB::raw('count(v1.id) as y'))
            ->orderBy('y', 'desc');
        if ($ugel != 0)
            $result1 = $result1->where('v4.id', $ugel);
        $result1 = $result1->get();
        foreach ($result1 as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $result1;
    }
    public static function listar_plazaseguntipodeniveleducactivo_grafica($importacion_id, $ugel)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_nivelmodalidad as v2', 'v2.id', '=', 'v1.NivelModalidad_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v1.Ugel_id')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('v2.tipo')
            ->select('v2.tipo as name', DB::raw('count(v1.id) as y'))
            ->orderBy('y', 'desc');
        if ($ugel != 0)
            $result1 = $result1->where('v4.id', $ugel);
        $result1 = $result1->get();
        foreach ($result1 as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $result1;
    }

    public static function listar_plazaseguntipotrabajador_grafica($importacion_id, $ugel)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v2', 'v2.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v2.dependencia')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v1.Ugel_id')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->groupBy('v2.nombre')
            ->select('v2.nombre as name', DB::raw('count(v1.id) as y'))
            ->orderBy('y', 'desc');
        if ($ugel != 0)
            $result1 = $result1->where('v4.id', $ugel);
        $result1 = $result1->get();
        foreach ($result1 as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $result1;
    }

    public static function listar_plazadocenteseguntipodeniveleducactivo_grafica($importacion_id, $ugel)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_nivelmodalidad as v2', 'v2.id', '=', 'v1.NivelModalidad_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_ugel as v6', 'v6.id', '=', 'v1.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->groupBy('v2.tipo')
            ->select('v2.tipo as name', DB::raw('count(v1.id) as y'))
            ->orderBy('y', 'desc');
        if ($ugel != 0)
            $result1 = $result1->where('v6.id', $ugel);
        $result1 = $result1->get();
        foreach ($result1 as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $result1;
    }

    public static function listar_plazadocentesegunsituacionlaboral_grafica($importacion_id, $ugel)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_ugel as v6', 'v6.id', '=', 'v1.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->where('v4.nombre', 'DOCENTE')
            ->groupBy('v2.nombre')
            ->select('v2.nombre as name', DB::raw('count(v1.id) as y'))
            ->orderBy('y', 'desc');
        if ($ugel != 0)
            $result1 = $result1->where('v6.id', $ugel);
        $result1 = $result1->get();
        foreach ($result1 as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $result1;
    }

    public static function listar_plazadocentesegunregimenlaboral_grafica($importacion_id, $ugel)
    {
        $result1 = DB::table('edu_plaza as v1')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_ugel as v6', 'v6.id', '=', 'v1.Ugel_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->where('v4.nombre', 'DOCENTE')
            ->groupBy('v1.ley')
            ->select('v1.ley as name', DB::raw('count(v1.id) as y'))
            ->orderBy('y', 'desc');
        if ($ugel != 0)
            $result1 = $result1->where('v6.id', $ugel);
        $result1 = $result1->get();
        foreach ($result1 as $key => $value) {
            $value->y = (int)$value->y;
        }
        return $result1;
    }

    public static function listar_plazassegunano_grafica($ugel)
    {
        $regs = Importacion::select(DB::raw("year(fechaActualizacion) ano"), DB::raw("max(id) id"))->where("estado", "PR")->where('fuenteImportacion_id', '2')
            ->groupBy('ano')->get();
        $ids = [];
        foreach ($regs as $key => $value) {
            $ids[] = $value->id;
        }
        $query = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->where('v6.estado', 'PR')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            //->where('v3.nombre', 'DOCENTE')->where('v4.nombre', 'DOCENTE')
            //->whereIn('v2.nombre', ["NOMBRADO", "CONTRATADO"])
            ->whereIn('v6.id', $ids)
            ->groupBy('name')
            ->select(
                DB::raw('YEAR(v6.fechaActualizacion) as name'),
                DB::raw('count(v1.id) as y')
            )
            ->orderBy('name', 'ASC');
        if ($ugel != 0)
            $query = $query->where('v7.id', $ugel);
        $query = $query->get();
        return $query;
        /* $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        foreach ($query as $key => $ba) {
            $data['categoria'][] = $ba->name;
            $dx1[] = $ba->y;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#317eeb', 'showInLegend' => false, 'data' => $dx1];
        return $data; */

        /* $query = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->where('v6.estado', 'PR')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->where('v4.nombre', 'DOCENTE')
            ->whereIn('v2.nombre', ["NOMBRADO", "CONTRATADO"])
            ->groupBy('name')
            ->select(
                DB::raw('YEAR(v6.fechaActualizacion) as name'),
                DB::raw('SUM(IF(v1.importacion_id=(
                    select max(xx.id)  from par_importacion as xx
                    where xx.estado="PR" and xx.fuenteImportacion_id=2 and year(xx.fechaActualizacion)=year(v6.fechaActualizacion)
                    group by year(xx.fechaActualizacion)
                    ),1,0)) as y')
            )
            ->orderBy('name', 'ASC')
            ->get();
        foreach ($query as $key => $value) {
            $value->name = "" . $value->name;
            $value->y = (int)$value->y;
        } */
    }

    public static function listar_plazassegunmes_grafica($importacion_id, $anio, $ugel)
    {
        $regs = Importacion::select(DB::raw("month(fechaActualizacion) mes"), DB::raw("max(id) id"))->where("estado", "PR")->where('fuenteImportacion_id', '2')
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->groupBy('mes')->get();
        $ids = [];

        foreach ($regs as $key => $value) {
            $ids[] = $value->id;
        }

        $query = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->where('v6.estado', 'PR')
            ->where(DB::raw('YEAR(v6.fechaActualizacion)'), '=', $anio)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            //->where('v3.nombre', 'DOCENTE')->where('v4.nombre', 'DOCENTE')
            //->whereIn('v2.nombre', ["NOMBRADO", "CONTRATADO"])
            ->whereIn('v6.id', $ids)
            ->groupBy('mes', 'name')
            ->select(
                DB::raw('month(`v6`.`fechaActualizacion`) as mes'),
                DB::raw('CASE
                WHEN month(`v6`.`fechaActualizacion`)=1 THEN "ENE"
                WHEN month(`v6`.`fechaActualizacion`)=2 THEN "FEB"
                WHEN month(`v6`.`fechaActualizacion`)=3 THEN "MAR"
                WHEN month(`v6`.`fechaActualizacion`)=4 THEN "ABR"
                WHEN month(`v6`.`fechaActualizacion`)=5 THEN "MAY"
                WHEN month(`v6`.`fechaActualizacion`)=6 THEN "JUN"
                WHEN month(`v6`.`fechaActualizacion`)=7 THEN "JUL"
                WHEN month(`v6`.`fechaActualizacion`)=8 THEN "AGO"
                WHEN month(`v6`.`fechaActualizacion`)=9 THEN "SET"
                WHEN month(`v6`.`fechaActualizacion`)=10 THEN "OCT"
                WHEN month(`v6`.`fechaActualizacion`)=11 THEN "NOV"
                WHEN month(`v6`.`fechaActualizacion`)=12 THEN "DIC"
                ELSE "" END as `name`'),
                DB::raw('count(v1.id) as y ')
            )
            ->orderBy('mes', 'ASC');
        if ($ugel != 0)
            $query = $query->where('v7.id', $ugel);
        $query = $query->get();

        $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        foreach ($query as $key => $ba) {
            $data['categoria'][] = $ba->name;
            $dx1[] = $ba->y;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#317eeb', 'showInLegend' => false, 'data' => $dx1];
        return $data;
        /* foreach ($query as $key => $value) {
            $value->name = "" . $value->name;
            $value->y = (int)$value->y;
        }
        return $query; */
    }

    public static function listar_plazadocentesegunano_grafica($ugel)
    {
        $regs = Importacion::select(DB::raw("year(fechaActualizacion) ano"), DB::raw("max(id) id"))->where("estado", "PR")->where('fuenteImportacion_id', '2')
            ->groupBy('ano')->get();
        $ids = [];
        foreach ($regs as $key => $value) {
            $ids[] = $value->id;
        }
        $query = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->where('v6.estado', 'PR')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->where('v4.nombre', 'DOCENTE')
            //->whereIn('v2.nombre', ["NOMBRADO", "CONTRATADO"])
            ->whereIn('v6.id', $ids)
            ->groupBy('name')
            ->select(
                DB::raw('YEAR(v6.fechaActualizacion) as name'),
                DB::raw('count(v1.id) as y')
            )
            ->orderBy('name', 'ASC');
        if ($ugel != 0)
            $query = $query->where('v7.id', $ugel);
        $query = $query->get();
        return $query;

        /* $query = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->where('v6.estado', 'PR')
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')
            ->where('v4.nombre', 'DOCENTE')
            ->whereIn('v2.nombre', ["NOMBRADO", "CONTRATADO"])
            ->groupBy('name')
            ->select(
                DB::raw('YEAR(v6.fechaActualizacion) as name'),
                DB::raw('SUM(IF(v1.importacion_id=(
                    select max(xx.id)  from par_importacion as xx
                    where xx.estado="PR" and xx.fuenteImportacion_id=2 and year(xx.fechaActualizacion)=year(v6.fechaActualizacion)
                    group by year(xx.fechaActualizacion)
                    ),1,0)) as y')
            )
            ->orderBy('name', 'ASC')
            ->get();
        foreach ($query as $key => $value) {
            $value->name = "" . $value->name;
            $value->y = (int)$value->y;
        } */
    }

    public static function listar_plazadocentesegunmes_grafica($importacion_id, $anio, $ugel)
    {
        $regs = Importacion::select(DB::raw("month(fechaActualizacion) mes"), DB::raw("max(id) id"))->where("estado", "PR")->where('fuenteImportacion_id', '2')
            ->where(DB::raw('year(fechaActualizacion)'), $anio)->groupBy('mes')->get();
        $ids = [];

        foreach ($regs as $key => $value) {
            $ids[] = $value->id;
        }

        $query = DB::table('edu_plaza as v1')
            ->join('edu_situacionlab as v2', 'v2.id', '=', 'v1.situacionLab_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('par_importacion as v6', 'v6.id', '=', 'v1.importacion_id')
            ->join('edu_ugel as v7', 'v7.id', '=', 'v1.Ugel_id')
            ->where('v6.estado', 'PR')
            ->where(DB::raw('YEAR(v6.fechaActualizacion)'), '=', $anio)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->where('v3.nombre', 'DOCENTE')->where('v4.nombre', 'DOCENTE')
            //->whereIn('v2.nombre', ["NOMBRADO", "CONTRATADO"])
            ->whereIn('v6.id', $ids)
            ->groupBy('mes', 'name')
            ->select(
                DB::raw('month(`v6`.`fechaActualizacion`) as mes'),
                DB::raw('CASE
                WHEN month(`v6`.`fechaActualizacion`)=1 THEN "ENE"
                WHEN month(`v6`.`fechaActualizacion`)=2 THEN "FEB"
                WHEN month(`v6`.`fechaActualizacion`)=3 THEN "MAR"
                WHEN month(`v6`.`fechaActualizacion`)=4 THEN "ABR"
                WHEN month(`v6`.`fechaActualizacion`)=5 THEN "MAY"
                WHEN month(`v6`.`fechaActualizacion`)=6 THEN "JUN"
                WHEN month(`v6`.`fechaActualizacion`)=7 THEN "JUL"
                WHEN month(`v6`.`fechaActualizacion`)=8 THEN "AGO"
                WHEN month(`v6`.`fechaActualizacion`)=9 THEN "SET"
                WHEN month(`v6`.`fechaActualizacion`)=10 THEN "OCT"
                WHEN month(`v6`.`fechaActualizacion`)=11 THEN "NOV"
                WHEN month(`v6`.`fechaActualizacion`)=12 THEN "DIC"
                ELSE "" END as `name`'),
                DB::raw('count(v1.id) as y ')
            )
            ->orderBy('mes', 'ASC');
        if ($ugel != 0)
            $query = $query->where('v7.id', $ugel);
        $query = $query->get();
        //return $query;
        $data['categoria'] = [];
        $data['series'] = [];
        $dx1 = [];
        foreach ($query as $key => $ba) {
            $data['categoria'][] = $ba->name;
            $dx1[] = $ba->y;
        }
        $data['series'][] = ['name' => 'GOBIERNO NACIONAL', 'color' => '#317eeb', 'showInLegend' => false, 'data' => $dx1];
        return $data;
        /* foreach ($query as $key => $value) {
            $value->name = "" . $value->name;
            $value->y = (int)$value->y;
        }
        return $query; */
    }

    public static function listar_totalplazacontratadoynombradossegunugelyniveleducativo($importacion_id, $ugel)
    {
        $bodys = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v1.nivelModalidad_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->where('v1.importacion_id', $importacion_id)
            ->whereIn('v6.nombre', ['NOMBRADO', 'CONTRATADO'])
            ->groupBy(/* 'v3.tipo', */'v3.nombre')
            ->select(
                /* 'v2.nombre as ugel', */
                'v3.nombre as nivel',
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="ADMINISTRATIVO",1,0)) as ACONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="ADMINISTRATIVO",1,0)) as ANOMBRADO'),
                DB::raw('sum(if(v5.nombre="ADMINISTRATIVO",1,0)) as ADMINISTRATIVO'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="DOCENTE",1,0)) as DCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="DOCENTE",1,0)) as DNOMBRADO'),
                DB::raw('sum(if(v5.nombre="DOCENTE",1,0)) as DOCENTE'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="CAS",1,0)) as CCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="CAS",1,0)) as CNOMBRADO'),
                DB::raw('sum(if(v5.nombre="CAS",1,0)) as CAS'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="PEC",1,0)) as PCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="PEC",1,0)) as PNOMBRADO'),
                DB::raw('sum(if(v5.nombre="PEC",1,0)) as PEC'),
                DB::raw('count(v1.id) as TOTAL')
            )
            ->orderBy('TOTAL', 'desc');
        if ($ugel != 0)
            $bodys = $bodys->where('v2.id', $ugel);
        $bodys = $bodys->get();
        /* $heads = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v1.nivelModalidad_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->where('v1.importacion_id', $importacion_id)
            ->whereIn('v6.nombre', ['NOMBRADO', 'CONTRATADO'])
            ->groupBy('v2.nombre')
            ->select(
                'v2.nombre as ugel',
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="ADMINISTRATIVO",1,0)) as ACONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="ADMINISTRATIVO",1,0)) as ANOMBRADO'),
                DB::raw('sum(if(v5.nombre="ADMINISTRATIVO",1,0)) as ADMINISTRATIVO'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="DOCENTE",1,0)) as DCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="DOCENTE",1,0)) as DNOMBRADO'),
                DB::raw('sum(if(v5.nombre="DOCENTE",1,0)) as DOCENTE'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="CAS",1,0)) as CCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="CAS",1,0)) as CNOMBRADO'),
                DB::raw('sum(if(v5.nombre="CAS",1,0)) as CAS'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="PEC",1,0)) as PCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="PEC",1,0)) as PNOMBRADO'),
                DB::raw('sum(if(v5.nombre="PEC",1,0)) as PEC'),
                DB::raw('count(v1.id) as TOTAL')
            )
            ->orderBy('TOTAL', 'desc')
            ->get(); */
        $foot = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_nivelmodalidad as v3', 'v3.id', '=', 'v1.nivelModalidad_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v5', 'v5.id', '=', 'v4.dependencia')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->where('v1.importacion_id', $importacion_id)
            ->whereIn('v6.nombre', ['NOMBRADO', 'CONTRATADO'])
            ->select(
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="ADMINISTRATIVO",1,0)) as ACONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="ADMINISTRATIVO",1,0)) as ANOMBRADO'),
                DB::raw('sum(if(v5.nombre="ADMINISTRATIVO",1,0)) as ADMINISTRATIVO'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="DOCENTE",1,0)) as DCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="DOCENTE",1,0)) as DNOMBRADO'),
                DB::raw('sum(if(v5.nombre="DOCENTE",1,0)) as DOCENTE'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="CAS",1,0)) as CCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="CAS",1,0)) as CNOMBRADO'),
                DB::raw('sum(if(v5.nombre="CAS",1,0)) as CAS'),
                DB::raw('sum(if(v6.nombre="CONTRATADO" and v5.nombre="PEC",1,0)) as PCONTRATADO'),
                DB::raw('sum(if(v6.nombre="NOMBRADO" and v5.nombre="PEC",1,0)) as PNOMBRADO'),
                DB::raw('sum(if(v5.nombre="PEC",1,0)) as PEC'),
                DB::raw('count(v1.id) as TOTAL')
            );
        if ($ugel != 0)
            $foot = $foot->where('v2.id', $ugel);
        $foot = $foot->get()->first();
        //->get()->first();
        $dt['table'] = view('educacion.Plaza.DocentesPrincipalTabla1', compact(/* 'heads', */'bodys', 'foot'))->render();
        return $dt;
    }

    public static function docentes_conteo_anual()
    {
        $impfechas = Importacion::select(DB::raw("year(fechaActualizacion) as ano"), DB::raw("max(fechaActualizacion) as fecha"))
            ->where('estado', 'PR')
            ->where('fuenteImportacion_id', "2")
            ->groupBy('ano')
            ->get();

        $fechas = [];
        foreach ($impfechas as $key => $value) {
            $fechas[] = $value->fecha;
        }

        $impfechas = Importacion::select(DB::raw("year(fechaActualizacion) as ano"), 'id', DB::raw("fechaActualizacion as fecha"))
            ->where('estado', 'PR')->where('fuenteImportacion_id', "2")->whereIn('fechaActualizacion', $fechas)
            ->orderBy('ano', 'asc')
            ->get();

        $ids = '';
        foreach ($impfechas as $key => $value) {
            if ($key < count($impfechas) - 1)
                $ids .= $value->id . ',';
            else $ids .= $value->id;
        }
        /*
         1: docente
        15: docente
        */
        $query = DB::table(DB::raw("(SELECT  distinct v3.fechaActualizacion as fecha,v1.documento_identidad dni FROM edu_plaza v1
        inner join edu_tipotrabajador v2 on v2.id=v1.tipoTrabajador_id
        inner join par_importacion v3 on v3.id=v1.importacion_id
        where v2.dependencia=1 and v2.id=15 and v3.id in ($ids) and v1.documento_identidad!='') as tb"))
            ->select(
                DB::raw('year(fecha) as name'),
                DB::raw('count(dni) as y')
            )
            ->groupBy('name')
            ->orderBy('name', 'asc')
            ->get();
        return $query;
    }

    public static function docentes_segungenero_anual()
    {
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '2')->orderBy('fecha', 'desc')->take(1)->get();
        $id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));
        /*
         1: docente
        15: docente
        */
        $query = DB::table(DB::raw("(SELECT  distinct v1.sexo,v1.documento_identidad dni FROM edu_plaza v1
        inner join edu_tipotrabajador v2 on v2.id=v1.tipoTrabajador_id
        where v1.importacion_id=$id and v2.dependencia=1 AND v2.id=15 and v1.documento_identidad!='' and sexo!='') as tb1"))
            ->select(
                DB::raw('sexo as `name`'),
                DB::raw('count(dni)  as y'),
            )
            ->groupBy('sexo')
            ->get();
        foreach ($query as $key => $value) {
            if ($value->name == '')
                $value->name = 'FEMENINO';
        }
        $data['puntos'] = $query;
        $data['fecha'] = $fecha;
        return $data;
    }

    public static function docentes_seguntipogestion()
    {
        $query = DB::table(DB::raw(
            "(  select v1.documento_identidad as dni,v2.fechaActualizacion as fecha,v5.id as gestion from edu_plaza v1
                inner join par_importacion v2 on v2.id=v1.importacion_id
                inner join edu_institucioneducativa as v3 on v3.id=v1.institucioneducativa_id
                inner join edu_tipogestion as v4 on v4.id=v3.TipoGestion_id
                inner join edu_tipogestion as v5 on v5.id=v4.dependencia
                where v2.estado='PR' and v1.documento_identidad!=''
                group by v2.fechaActualizacion,v1.documento_identidad,gestion
                ) as tb"
        ))
            ->select(
                DB::raw('year(fecha) as anio'),
                DB::raw('month(fecha) as mes'),
                DB::raw('day(fecha) as dia'),
                DB::raw('SUM(if(gestion!=3,1,0)) as publica'),
                DB::raw('SUM(if(gestion=3,1,0)) as privada')
            )
            ->groupBy('anio', 'mes', 'dia')
            ->orderBy('anio', 'desc')->orderBy('mes', 'desc')->orderBy('dia', 'desc')
            ->get()->first();
        return [['name' => 'Pública', 'y' => (int)$query->publica], ['name' => 'Privado', 'y' => (int)$query->privada]];
        /* $vista = "[{'name':'Pública', 'y':$query->publica},";
        $vista .= "{'name':'Privado', 'y':$query->privada}]";
        return ['pts' => $vista, 'anio' => $query->anio]; */
    }

    public static function docentes_segunareageograficas()
    {
        $imp = Importacion::select('id', 'fechaActualizacion as fecha')->where('estado', 'PR')->where('fuenteImportacion_id', '2')->orderBy('fecha', 'desc')->take(1)->get();
        $id = $imp->first()->id;
        $fecha = date('d/m/Y', strtotime($imp->first()->fecha));
        /*
         1: docente
        15: docente
        */
        $query = DB::table(DB::raw("(SELECT  distinct (case when v3.nombre like '%RURAL%' then 'Rural' when v3.nombre LIKE '%URBAN%' then 'Urbano' else 'Sin Informacion' end) as zona,v1.documento_identidad dni FROM edu_plaza v1
        inner join edu_tipotrabajador v2 on v2.id=v1.tipoTrabajador_id
        inner join par_zona v3 on v3.id=v1.zona_id
        where v1.importacion_id=$id and v2.dependencia=1 AND v2.id=15 and v3.id!=8 and v1.documento_identidad!='') as tb1"))
            ->select(
                DB::raw('zona as `name`'),
                DB::raw('count(dni)  as y'),
            )
            ->groupBy('name')
            ->get();
        $data['puntos'] = $query;
        $data['fecha'] = $fecha;
        return $data;
    }

    public static function docentes_segunugel()
    {
        $fechaMax = DB::table('edu_plaza as v1')
            ->join('par_importacion as v2', 'v2.id', '=', 'v1.importacion_id')
            ->join('edu_institucioneducativa as v3', 'v3.id', '=', 'v1.institucioneducativa_id')
            ->join('edu_ugel as v4', 'v4.id', '=', 'v3.Ugel_id')
            ->select(DB::raw('max(v2.fechaActualizacion) as fecha'))
            ->where('v2.estado', 'PR')->where('v1.documento_identidad', '!=', '')
            ->first()->fecha;
        if ($fechaMax) {
            $query = DB::table(DB::raw(
                "(  select v1.documento_identidad as dni,v2.fechaActualizacion as fecha,v4.nombre as ugel from edu_plaza v1
                    inner join par_importacion v2 on v2.id=v1.importacion_id
                    inner join edu_institucioneducativa as v3 on v3.id=v1.institucioneducativa_id
                    inner join edu_ugel as v4 on v4.id=v3.Ugel_id
                    where v2.estado='PR' and v1.documento_identidad!=''
                    group by fecha,dni,ugel
                    ) as tb"
            ))
                ->select(
                    DB::raw('ugel'),
                    DB::raw('count(dni) as conteo')
                )
                ->where('fecha', $fechaMax)
                ->groupBy('ugel')
                ->orderBy('conteo', 'desc')
                ->get();
            $vista = "[";
            foreach ($query as $val) {
                $vista .= "{'name':'$val->ugel', 'y':$val->conteo},";
            }
            $vista .= "]";
            return ['pts' => $vista, 'anio' => date('Y', strtotime($fechaMax))];
        }
        return [];
    }

    public static function cargarresumendeplazatabla2($importacion_id, $ugel)
    {/* titulo:: */
        $bodys = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('tipo', 'subtipo')
            ->select(
                'v4.nombre as tipo',
                'v3.nombre as subtipo',
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(if(v2.id=1,1,0)) as portillo'),
                DB::raw('SUM(if(v2.id=2,1,0)) as dre'),
                DB::raw('SUM(if(v2.id=3,1,0)) as atalaya'),
                DB::raw('SUM(if(v2.id=4,1,0)) as abad'),
                DB::raw('SUM(if(v2.id=5,1,0)) as purus'),
            );
        if ($ugel != 0)
            $bodys = $bodys->where('v2.id', $ugel);
        $bodys = $bodys->get();

        $heads = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('tipo')
            ->select(
                'v4.nombre as tipo',
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(if(v2.id=1,1,0)) as portillo'),
                DB::raw('SUM(if(v2.id=2,1,0)) as dre'),
                DB::raw('SUM(if(v2.id=3,1,0)) as atalaya'),
                DB::raw('SUM(if(v2.id=4,1,0)) as abad'),
                DB::raw('SUM(if(v2.id=5,1,0)) as purus'),
            );
        if ($ugel != 0)
            $heads = $heads->where('v2.id', $ugel);
        $heads = $heads->get();

        $foot = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->select(
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(if(v2.id=1,1,0)) as portillo'),
                DB::raw('SUM(if(v2.id=2,1,0)) as dre'),
                DB::raw('SUM(if(v2.id=3,1,0)) as atalaya'),
                DB::raw('SUM(if(v2.id=4,1,0)) as abad'),
                DB::raw('SUM(if(v2.id=5,1,0)) as purus'),
            );
        if ($ugel != 0)
            $foot = $foot->where('v2.id', $ugel);
        $foot = $foot->get()->first();
        //->get()->first();
        $dt['table'] = view('educacion.Plaza.DocentesPrincipalTabla2', compact('heads', 'bodys', 'foot'))->render();
        return $dt;
    }

    public static function cargarresumendeplazatabla3($importacion_id, $ugel)
    {/* titulo:: */
        $bodys = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('id', 'ugel')
            ->select(
                'v2.id',
                'v2.nombre as ugel',
                DB::raw('count(v1.id) total'),
                DB::raw('sum(IF(v6.id=4,1,0)) contratado'),
                DB::raw('sum(IF(v6.id=7,1,0)) desigconfian'),
                DB::raw('sum(IF(v6.id=8,1,0)) desigexcep'),
                DB::raw('sum(IF(v6.id=1,1,0)) designado'),
                DB::raw('sum(IF(v6.id=6,1,0)) destacado'),
                DB::raw('sum(IF(v6.id=2,1,0)) encargado'),
                DB::raw('sum(IF(v6.id=3,1,0)) nombrado'),
                DB::raw('sum(IF(v6.id=5,1,0)) vacante'),
            );
        if ($ugel != 0)
            $bodys = $bodys->where('v2.id', $ugel);
        $bodys = $bodys->get();
        /* $heads = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('tipo')
            ->select(
                'v4.nombre as tipo',
                DB::raw('count(v1.id) as total'),
                DB::raw('SUM(if(v2.id=1,1,0)) as portillo'),
                DB::raw('SUM(if(v2.id=2,1,0)) as dre'),
                DB::raw('SUM(if(v2.id=3,1,0)) as atalaya'),
                DB::raw('SUM(if(v2.id=4,1,0)) as abad'),
                DB::raw('SUM(if(v2.id=5,1,0)) as purus'),
            )
            ->get(); */
        $foot = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->select(
                DB::raw('count(v1.id) total'),
                DB::raw('sum(IF(v6.id=4,1,0)) contratado'),
                DB::raw('sum(IF(v6.id=7,1,0)) desigconfian'),
                DB::raw('sum(IF(v6.id=8,1,0)) desigexcep'),
                DB::raw('sum(IF(v6.id=1,1,0)) designado'),
                DB::raw('sum(IF(v6.id=6,1,0)) destacado'),
                DB::raw('sum(IF(v6.id=2,1,0)) encargado'),
                DB::raw('sum(IF(v6.id=3,1,0)) nombrado'),
                DB::raw('sum(IF(v6.id=5,1,0)) vacante'),
            );
        if ($ugel != 0)
            $foot = $foot->where('v2.id', $ugel);
        $foot = $foot->get()->first();

        $dt['table'] = view('educacion.Plaza.DocentesPrincipalTabla3', compact(/* 'heads',  */'bodys', 'foot'))->render();
        return $dt;
    }

    public static function cargarresumendeplazatabla4($rq, $importacion_id, $ugel)
    {/* titulo:: */
        $bodys = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->join('edu_nivelmodalidad as v7', 'v7.id', '=', 'v1.nivelModalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('tipo', 'nivel')
            ->select(
                'v7.tipo',
                'v7.nombre as nivel',
                DB::raw('count(v1.id) total'),
                DB::raw('sum(IF(v6.id=4,1,0)) contratado'),
                DB::raw('sum(IF(v6.id=7,1,0)) desigconfian'),
                DB::raw('sum(IF(v6.id=8,1,0)) desigexcep'),
                DB::raw('sum(IF(v6.id=1,1,0)) designado'),
                DB::raw('sum(IF(v6.id=6,1,0)) destacado'),
                DB::raw('sum(IF(v6.id=2,1,0)) encargado'),
                DB::raw('sum(IF(v6.id=3,1,0)) nombrado'),
                DB::raw('sum(IF(v6.id=5,1,0)) vacante'),
            );
        if ($ugel != 0)
            $bodys = $bodys->where('v2.id', $ugel);
        $bodys = $bodys->get();

        $heads = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->join('edu_nivelmodalidad as v7', 'v7.id', '=', 'v1.nivelModalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->groupBy('tipo')
            ->select(
                'v7.tipo',
                DB::raw('count(v1.id) total'),
                DB::raw('sum(IF(v6.id=4,1,0)) contratado'),
                DB::raw('sum(IF(v6.id=7,1,0)) desigconfian'),
                DB::raw('sum(IF(v6.id=8,1,0)) desigexcep'),
                DB::raw('sum(IF(v6.id=1,1,0)) designado'),
                DB::raw('sum(IF(v6.id=6,1,0)) destacado'),
                DB::raw('sum(IF(v6.id=2,1,0)) encargado'),
                DB::raw('sum(IF(v6.id=3,1,0)) nombrado'),
                DB::raw('sum(IF(v6.id=5,1,0)) vacante'),
            );
        if ($ugel != 0)
            $heads = $heads->where('v2.id', $ugel);
        $heads = $heads->get();

        $foot = DB::table('edu_plaza as v1')
            ->join('edu_ugel as v2', 'v2.id', '=', 'v1.ugel_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v1.tipoTrabajador_id')
            ->join('edu_tipotrabajador as v4', 'v4.id', '=', 'v3.dependencia')
            ->join('edu_tipo_registro_plaza as v5', 'v5.id', '=', 'v1.tipo_registro_id')
            ->join('edu_situacionlab as v6', 'v6.id', '=', 'v1.situacionLab_id')
            ->join('edu_nivelmodalidad as v7', 'v7.id', '=', 'v1.nivelModalidad_id')
            ->where('v1.importacion_id', $importacion_id)
            ->where('v5.nombre', '!=', 'POR REEMPLAZO')
            ->select(
                DB::raw('count(v1.id) total'),
                DB::raw('sum(IF(v6.id=4,1,0)) contratado'),
                DB::raw('sum(IF(v6.id=7,1,0)) desigconfian'),
                DB::raw('sum(IF(v6.id=8,1,0)) desigexcep'),
                DB::raw('sum(IF(v6.id=1,1,0)) designado'),
                DB::raw('sum(IF(v6.id=6,1,0)) destacado'),
                DB::raw('sum(IF(v6.id=2,1,0)) encargado'),
                DB::raw('sum(IF(v6.id=3,1,0)) nombrado'),
                DB::raw('sum(IF(v6.id=5,1,0)) vacante'),
            );
        if ($ugel != 0)
            $foot = $foot->where('v2.id', $ugel);
        $foot = $foot->get()->first();
        $dt['table'] = view('educacion.Plaza.DocentesPrincipalTabla4', compact('heads', 'bodys', 'foot'))->render();
        return $dt;
    }

    public static function cargarresumendeplazatabla5($rq, $importacion_id, $ugel)
    {
        $body = PLaza::select(
            'edu_plaza.id',
            'edu_plaza.documento_identidad as dni',
            'edu_plaza.codigoPlaza as plaza',
            'edu_plaza.codModular as modular',
            'edu_plaza.nivel_educativo_dato_adic as nivel',
            'edu_plaza.institucion_educativa_dato_adic as iiee'
        )
            ->join('edu_ugel as uu', 'uu.id', '=', 'edu_plaza.ugel_id')
            ->join('edu_estadoplaza as ep', 'ep.id', '=', 'edu_plaza.estadoPlaza_id')
            ->join('edu_situacionlab as sl', 'sl.id', '=', 'edu_plaza.situacionLab_id')
            ->join('edu_tipotrabajador as td', 'td.id', '=', 'edu_plaza.tipoTrabajador_id')
            ->join('edu_tipotrabajador as tt', 'tt.id', '=', 'td.dependencia')
            // ->join('edu_tipotrabajador as tt', 'tt.id', '=', 'td.dependencia')
            ->where('edu_plaza.importacion_id', $importacion_id)->where('bilingue', '>', 0)
            ->whereIn('ep.id', [4, 33])->where('sl.id', '!=', 5)->where('tt.id', 1)->whereNotIn('td.id', [13, 16]);

        if ($ugel > 0) $body = $body->where('uu.id', $ugel);
        // $body = $body->groupBy('edu_plaza.institucionEducativa_id');
        $body = $body->get();

        return $body;
    }

    public static function cargarresumendeplazatabla6($rq, $importacion_id, $ugel)
    {
        $opc1 = "v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25";
        $opc2 = "v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26";
        $dx = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw("sum(IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,$opc1,
                         IF(year(par_importacion.fechaActualizacion)>2019,$opc2,0))) as tt"),
            DB::raw("sum(IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019 and v1.tipdato='06',$opc1,
                         IF(year(par_importacion.fechaActualizacion)>2019 and v1.tipdato='06',$opc2,0))) as t1"),
            DB::raw("sum(IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019 and v1.tipdato='07',$opc1,
                         IF(year(par_importacion.fechaActualizacion)>2019 and v1.tipdato='07',$opc2,0))) as t2"),
            DB::raw("sum(IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019 and v1.tipdato='08',$opc1,
                         IF(year(par_importacion.fechaActualizacion)>2019 and v1.tipdato='08',$opc2,0))) as t3"),
            DB::raw("sum(IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019 and v1.tipdato='09',$opc1,
                         IF(year(par_importacion.fechaActualizacion)>2019 and v1.tipdato='09',$opc2,0))) as t4"),
            DB::raw("sum(IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019 and v1.tipdato='10',$opc1,
                         IF(year(par_importacion.fechaActualizacion)>2019 and v1.tipdato='10',$opc2,0))) as t5"),
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->where('importacion_id', $importacion_id)
            ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['06', '07', '08', '09', '10']);
        if ($ugel > 0) {
            $uu = Ugel::find($ugel);
            $dx = $dx->where('v1.codooii', $uu->codigo);
        }
        $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();
        return $dx;
    }

    public static function conteo_docentes_rer()
    {
        $imp = PLaza::where('v2.estado', 'PR')
            ->join('par_importacion as v2', 'v2.id', '=', 'edu_plaza.importacion_id')
            ->select(DB::raw('distinct edu_plaza.importacion_id'))
            ->orderBy('v2.id', 'desc')
            ->limit(1)
            ->first()->importacion_id;
        $query = PadronRER::where('v2.importacion_id', $imp)->where('v3.id', '!=', '16')->where('v3.dependencia', 1)
            ->join('edu_plaza as v2', 'v2.institucioneducativa_id', '=', 'edu_padron_rer.institucioneducativa_id')
            ->join('edu_tipotrabajador as v3', 'v3.id', '=', 'v2.tipoTrabajador_id')
            ->select(DB::raw('count(distinct(v2.documento_identidad)) as conteo'))
            ->first()->conteo;
        return $query;
    }
}
