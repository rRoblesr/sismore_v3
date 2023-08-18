<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Censo;
use App\Models\Educacion\CensoResultado;
use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;

class CensoRepositorio
{

    public static function Listar_Por_Importacion_id($importacion_id)
    {
        $data = CensoResultado::select(
            'codLocal',
            'codigosModulares',
            'nombreInstitucion',
            'codigoGestion',
            'descripcionGestion',
            'codigoOrganoInter',
            'nombreDre_Ugel',
            'codigoUbigeo',
            'Departamento',
            'Provincia',
            'Distrito',
            'centoPoblado',
            'direccion',
            'areaGeo',
            'estadoCenso',
            'totalAulas',
            'aulasBuenas',
            'aulasRegulares',
            'aulasMalas',
            'noPuedePrecisarEstadoAulas',
            'elLocalEs',
            'propietarioLocal',
            'cuenta_con_itse',
            'plan_contingencia',
            'plan_desastre',
            'plandesastre_act',
            'compuEscri_operativos',
            'compuEscri_inoperativos',
            'compuPorta_operativos',
            'compuPorta_inoperativos',
            'lapto_operativos',
            'lapto_inoperativos',
            'tieneInternet',
            'tipoConexion',
            'fuenteEnergiaElectrica',
            'empresaEnergiaElect',
            'tieneEnergiaElectTodoDia',
            'fuenteAgua',
            'empresaAgua',
            'tieneAguaPotTodoDia',
            'desagueInfo'
        )
            ->join('edu_censo', 'edu_censo.id', '=', 'edu_censoresultado.censo_id')
            ->where("edu_censo.importacion_id", "=", $importacion_id)
            ->get();

        return $data;
    }

    public static function censo_Por_Importacion_id($importacion_id)
    {
        $data = Censo::select('edu_censo.id', 'edu_censo.estado', 'anio')
            ->join('par_anio', 'par_anio.id', '=', 'edu_censo.anio_id')
            ->where("edu_censo.importacion_id", "=", $importacion_id)
            ->get();

        return $data;
    }

    public static function censo_Por_anio_estado($anio, $estado)
    {
        $data = Censo::select('edu_censo.id', 'edu_censo.estado', 'anio')
            ->join('par_anio', 'par_anio.id', '=', 'edu_censo.anio_id')
            ->where("par_anio.anio", "=", $anio)
            ->where("edu_censo.estado", "=", $estado)
            ->get();

        return $data;
    }

    public static function listar_anios()
    {
        $query = DB::table('edu_censo as v1')
            ->join('par_anio as v2', 'v2.id', '=', 'v1.anio_id')
            ->where('v1.estado', 'PR')
            ->distinct('v2.*')
            ->select('v2.*')
            ->get();
        return $query;
    }

    public static function listar_conElectricidad($provincia, $distrito, $indicador_id, $anio_id)
    {
        if ($distrito > 0) {
            $prov = Ubigeo::find($distrito);
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteEnergiaElectrica', '!=', '1.Red pública')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->whereIn('v2.fuenteEnergiaElectrica', ['1.Red pública', '2.Generador o motor del Municipio'])
                ->groupBy('v2.fuenteEnergiaElectrica')
                ->orderBy('v2.fuenteEnergiaElectrica', 'asc')
                ->get(['v2.fuenteEnergiaElectrica as name', DB::raw('count(v2.id) as y')]);
        } else if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteEnergiaElectrica', '!=', '1.Red pública')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->whereIn('v2.fuenteEnergiaElectrica', ['1.Red pública', '2.Generador o motor del Municipio'])
                ->groupBy('v2.fuenteEnergiaElectrica')
                ->orderBy('v2.fuenteEnergiaElectrica', 'asc')
                ->get(['v2.fuenteEnergiaElectrica as name', DB::raw('count(v2.id) as y')]);
        } else {
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.fuenteEnergiaElectrica', '!=', '1.Red pública')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->whereIn('v2.fuenteEnergiaElectrica', ['1.Red pública', '2.Generador o motor del Municipio'])
                ->groupBy('v2.fuenteEnergiaElectrica')
                ->orderBy('v2.fuenteEnergiaElectrica', 'asc')
                ->get(['v2.fuenteEnergiaElectrica as name', DB::raw('count(v2.id) as y')]);
        }
        foreach ($query1 as $item) {
            if ($item->name == '2.Generador o motor del Municipio') {
                $item->y = $query->first()->y;
                $item->name = '2.Otros';
            }
        }
        $data['indicador'] = $query1;
        return $data;
    }
    public static function listar_conAguaPotable($provincia, $distrito, $indicador_id, $anio_id)
    {
        if ($distrito > 0) {
            $prov = Ubigeo::find($distrito);
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteAgua', '!=', '1.Red pública')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->whereIn('v2.fuenteAgua', ['1.Red pública', '2.Pilón de uso público'])
                ->groupBy('v2.fuenteAgua')
                ->orderBy('v2.fuenteAgua', 'asc')
                ->get(['v2.fuenteAgua as name', DB::raw('count(v2.id) as y')]);
        } else if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteAgua', '!=', '1.Red pública')
                ->get([DB::raw('count(v2.id) as y')]);
            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->whereIn('v2.fuenteAgua', ['1.Red pública', '2.Pilón de uso público'])
                ->groupBy('v2.fuenteAgua')
                ->orderBy('v2.fuenteAgua', 'asc')
                ->get(['v2.fuenteAgua as name', DB::raw('count(v2.id) as y')]);
        } else {
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.fuenteAgua', '!=', '1.Red pública')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->whereIn('v2.fuenteAgua', ['1.Red pública', '2.Pilón de uso público'])
                ->groupBy('v2.fuenteAgua')
                ->orderBy('v2.fuenteAgua', 'asc')
                ->get(['v2.fuenteAgua as name', DB::raw('count(v2.id) as y')]);
        }
        foreach ($query1 as $item) {
            if ($item->name == '2.Pilón de uso público') {
                $item->y = $query->first()->y;
                $item->name = '2.Otros';
            }
        }
        $data['indicador'] = $query1;
        return $data;
    }
    public static function listar_conDesague($provincia, $distrito, $indicador_id, $anio_id)
    {
        if ($distrito > 0) {
            $prov = Ubigeo::find($distrito);
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.desagueInfo', '!=', '1.Desemboca en una red pública de desagüe')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->whereIn('v2.desagueInfo', ['1.Desemboca en una red pública de desagüe', '2. Utiliza pozo séptico/tanque séptico'])
                ->groupBy('v2.desagueInfo')
                ->orderBy('v2.desagueInfo', 'asc')
                ->get(['v2.desagueInfo as name', DB::raw('count(v2.id) as y')]);
        } else if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.desagueInfo', '!=', '1.Desemboca en una red pública de desagüe')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->whereIn('v2.desagueInfo', ['1.Desemboca en una red pública de desagüe', '2. Utiliza pozo séptico/tanque séptico'])
                ->groupBy('v2.desagueInfo')
                ->orderBy('v2.desagueInfo', 'asc')
                ->get(['v2.desagueInfo as name', DB::raw('count(v2.id) as y')]);
        } else {
            $query = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.desagueInfo', '!=', '1.Desemboca en una red pública de desagüe')
                ->get([DB::raw('count(v2.id) as y')]);

            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->whereIn('v2.desagueInfo', ['1.Desemboca en una red pública de desagüe', '2. Utiliza pozo séptico/tanque séptico'])
                ->groupBy('v2.desagueInfo')
                ->orderBy('v2.desagueInfo', 'asc')
                ->get(['v2.desagueInfo as name', DB::raw('count(v2.id) as y')]);
        }
        foreach ($query1 as $item) {
            if ($item->name == '2. Utiliza pozo séptico/tanque séptico') {
                $item->y = $query->first()->y;
                $item->name = '2.Otros';
            }
        }
        $data['indicador'] = $query1;
        return $data;
    }
    public static function listar_conServicioBasico($provincia, $distrito, $indicador_id, $anio_id)
    {
        if ($distrito > 0) {
            $prov = Ubigeo::find($distrito);
            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteEnergiaElectrica', '=', '1.Red pública')
                ->where('v2.fuenteAgua', '=', '1.Red pública')
                ->where('v2.desagueInfo', '=', '1.Desemboca en una red pública de desagüe')
                ->get([DB::raw('count(v2.id) as y')]);
            $query2 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteEnergiaElectrica', '!=', 'NULL')
                ->where('v2.fuenteAgua', '!=', 'NULL')
                ->where('v2.desagueInfo', '!=', 'NULL')
                ->get([DB::raw('count(v2.id) as y')]);
        } else if ($provincia > 0) {
            $prov = Ubigeo::find($provincia);
            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteEnergiaElectrica', '=', '1.Red pública')
                ->where('v2.fuenteAgua', '=', '1.Red pública')
                ->where('v2.desagueInfo', '=', '1.Desemboca en una red pública de desagüe')
                ->get([DB::raw('count(v2.id) as y')]);
            $query2 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.codigoUbigeo', 'like', $prov->codigo . '%')
                ->where('v2.fuenteEnergiaElectrica', '!=', 'NULL')
                ->where('v2.fuenteAgua', '!=', 'NULL')
                ->where('v2.desagueInfo', '!=', 'NULL')
                ->get([DB::raw('count(v2.id) as y')]);
        } else {
            $query1 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.fuenteEnergiaElectrica', '=', '1.Red pública')
                ->where('v2.fuenteAgua', '=', '1.Red pública')
                ->where('v2.desagueInfo', '=', '1.Desemboca en una red pública de desagüe')
                ->get([DB::raw('count(v2.id) as y')]);
            $query2 = DB::table('edu_censo as v1')
                ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
                ->where('v1.anio_id', $anio_id)
                ->where('v1.estado', 'PR')
                ->where('v2.fuenteEnergiaElectrica', '!=', 'NULL')
                ->where('v2.fuenteAgua', '!=', 'NULL')
                ->where('v2.desagueInfo', '!=', 'NULL')
                ->get([DB::raw('count(v2.id) as y')]);
        }
        $data['indicador'] = [
            ['name' => '1.Tres Servicios Basicos', 'y' => $query1->first()->y],
            ['name' => '2.Otros', 'y' => $query2->first()->y - $query1->first()->y]
        ];
        return $data;
    }

    public static function Listar_IE_nivel($provincia, $distrito, $indicador_id, $anio_id, $nivel_id)
    {
        $query1 = DB::table('edu_censo as v1')
            ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
            ->where('v1.anio_id', $anio_id)
            ->where('v1.estado', 'PR')
            ->select('v2.tieneInternet', 'v2.codigosmodulares as modular')
            ->get();
        foreach ($query1 as $item) {
            if ($item->tieneInternet != 'Si') $item->tieneInternet = 'No';
        }
        if ($provincia > 0 && $distrito > 0) {
            $prov = Ubigeo::find($distrito);
            $query = DB::table('edu_institucioneducativa as v1')
                ->join('par_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
                ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
                ->where('v1.NivelModalidad_id', $nivel_id)
                ->where('v3.codigo', 'like', $prov->codigo . '%')
                ->select('v1.codModular as modular', 'v1.NivelModalidad_id as nivel')
                ->get();
        } else if ($provincia > 0 && $distrito == 0) {
            $prov = Ubigeo::find($provincia);
            $query = DB::table('edu_institucioneducativa as v1')
                ->join('par_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
                ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
                ->where('v1.NivelModalidad_id', $nivel_id)
                ->where('v3.codigo', 'like', $prov->codigo . '%')
                ->select('v1.codModular as modular', 'v1.NivelModalidad_id as nivel')
                ->get();
        } else {
            $query = DB::table('edu_institucioneducativa as v1')
                ->where('v1.NivelModalidad_id', $nivel_id)
                ->select('v1.codModular as modular', 'v1.NivelModalidad_id as nivel')
                ->get();
        }
        $conteo = [['name' => 'Si', 'y' => 0], ['name' => 'No', 'y' => 0]];
        $xmodular = 0;
        foreach ($query as $inst) {
            foreach ($query1 as $censo) {
                if (strpos($censo->modular, $inst->modular)) {
                    if ($censo->tieneInternet == 'Si') $conteo[0]['y']++;
                    else $conteo[1]['y']++;
                    $xmodular++;
                    break;
                }
            }
        }
        $data['conteo'] = $conteo;
        $data['total'] = $xmodular;

        return $data;
    }
    public static function Listar_IE_computo($provincia, $distrito, $indicador_id, $anio_id)
    {
        $query1 = DB::table('edu_censo as v1')
            ->join('edu_censoresultado as v2', 'v2.censo_id', '=', 'v1.id')
            ->where('v1.anio_id', $anio_id)
            ->where('v1.estado', 'PR')
            ->select('v2.compuEscri_operativos as escritorio', 'v2.compuPorta_operativos as portatil', 'v2.lapto_operativos as laptop', 'v2.codigosmodulares as modulares')
            ->get();

        if ($provincia > 0 && $distrito > 0) {
            $prov = Ubigeo::find($distrito);
            $query = DB::table('edu_institucioneducativa as v1')
                ->join('par_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
                ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
                ->where('v3.codigo', 'like', $prov->codigo . '%')
                ->select('v1.codModular as modular', 'v1.NivelModalidad_id as nivel')
                ->get();
        } else if ($provincia > 0 && $distrito == 0) {
            $prov = Ubigeo::find($provincia);
            $query = DB::table('edu_institucioneducativa as v1')
                ->join('par_centropoblado as v2', 'v2.id', '=', 'v1.CentroPoblado_id')
                ->join('par_ubigeo as v3', 'v3.id', '=', 'v2.Ubigeo_id')
                ->where('v3.codigo', 'like', $prov->codigo . '%')
                ->select('v1.codModular as modular', 'v1.NivelModalidad_id as nivel')
                ->get();
        } else {
            $query = DB::table('edu_institucioneducativa as v1')
                ->select('v1.codModular as modular', 'v1.NivelModalidad_id as nivel')
                ->get();
        }
        $pc = [['name' => 'Si', 'y' => 0], ['name' => 'No', 'y' => 0]];
        $pp = [['name' => 'Si', 'y' => 0], ['name' => 'No', 'y' => 0]];
        $lc = [['name' => 'Si', 'y' => 0], ['name' => 'No', 'y' => 0]];
        $npc = 0;
        $nlc = 0;
        foreach ($query as $inst) {
            foreach ($query1 as $censo) {
                if (strpos($censo->modulares, $inst->modular)) {
                    if ($censo->escritorio > 0) $pc[0]['y']++;
                    else $pc[1]['y']++;
                    if ($censo->laptop > 0) $lc[0]['y']++;
                    else $lc[1]['y']++;
                    if ($censo->portatil > 0) $pp[0]['y']++;
                    else $pp[1]['y']++;
                    $npc++;
                    break;
                }
            }
        }
        $data['pc'] = $pc;
        $data['total'] = $npc;
        $data['lc'] = $lc;
        $data['pp'] = $pp;
        //$data['xx'] = $query;

        return $data;
    }
}
