<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Parametro\ImporPoblacion;
use App\Models\Parametro\Poblacion;
use App\Models\Parametro\PoblacionDetalle;
use App\Repositories\Salud\PadronNominalRepositorioSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SaludPadronNominalSeguimiento extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id_grupo = 1, $cod_2000 = 'NULL')
    {
        $grupo_edad = [['id' => 1, 'nombre' => 'Recién Nacido'], ['id' => 2, 'nombre' => 'Menor de 12 meses'], ['id' => 3, 'nombre' => 'De 1 año'], ['id' => 4, 'nombre' => 'De 2 años'],];

        $sector = session('usuario_sector');
        $nivel = session('usuario_nivel');
        $codigo = session('usuario_codigo_institucion');        

        $codigo_institucion = ($cod_2000 == "NULL") ?  $codigo : $cod_2000;
        //$nivel = ($cod_2000 == "NULL") ?  $nivel : '4';
        

        $codigo_institucion = ($sector == "22") ? '250101' : $codigo_institucion;

        //return compact('sector', 'nivel', 'codigo_institucion');



        $nombre_columna = $this->columna($sector, $nivel); //  ($sector == '14') ? "re.cod_2000" : "re.ubigeo";
        $nombre_columna = ($cod_2000 == "NULL") ? $nombre_columna : "re.cod_2000";

        //return compact('sector', 'nivel', 'codigo_institucion', 'nombre_columna');

        $dato_ipress = DB::table('m_establecimiento as re')->select('re.cod_2000', 're.nom_est', 're.cod_mic', 're.nom_mic', 're.cod_red', 're.nom_red')->where('re.cod_disa','34')->where($nombre_columna, $codigo_institucion)->first();

        //return compact('dato_ipress');

        $query = DB::table('m_establecimiento as re')->select('re.cod_red', 're.nom_red')->where('cod_disa', '34');
        if ($sector == '14' and $nivel >= '2') $query->where('re.cod_red', $dato_ipress->cod_red);
        $grupo_red = $query->groupBy('re.cod_red', 're.nom_red')->get();

        $query = DB::table('m_establecimiento as re')->select('re.cod_mic', 're.nom_mic')->where('cod_disa', '34');
        if ($sector == '14' and $nivel >= '3') $query->where('re.cod_mic', $dato_ipress->cod_mic)->where('re.cod_red', $dato_ipress->cod_red);
        $grupo_microred = $query->groupBy('re.cod_mic', 're.nom_mic')->get();

        $query = DB::table('m_establecimiento as re')->select('re.cod_2000', 're.nom_est')->where('cod_disa', '34');
        if ($sector == '14' and $nivel == '4') $query->where('re.cod_2000', $codigo_institucion);
        $query->where('re.cod_mic', $dato_ipress->cod_mic)->where('re.cod_red', $dato_ipress->cod_red);
        $grupo_ipress = $query->groupBy('re.cod_2000', 're.nom_est')->get();

        // return compact('id_grupo', 'codigo_institucion', 'grupo_edad', 'grupo_red', 'grupo_microred', 'grupo_ipress', 'dato_ipress');

        $actualizado = '';

        $entidad = Entidad::find(auth()->user()->entidad);
        $entidad = Entidad::find($entidad->dependencia);
        $entidad = $entidad->codigo . ' - ' . $entidad->nombre;
        return view('salud.padron.seguimiento', compact('id_grupo', 'codigo_institucion', 'grupo_edad', 'grupo_red', 'grupo_microred', 'grupo_ipress', 'dato_ipress', 'actualizado', 'entidad'));
    }

    public function columna($sector, $nivel)
    {
        if ($sector == '14') {
            switch ($nivel) {
                case '1':
                    return "re.cod_disa";
                case '2':
                    return "re.cod_red";
                case '3':
                    return "re.cod_mic";
                case '4':
                    return "re.cod_2000";
                default:
                    return "";
            }
        } else if ($sector == '2') {
            switch ($nivel) {
                case '1':
                    return "re.ubigeo";
                default:
                    return "";
            }
        } else if ($sector == '22') {
            switch ($nivel) {
                case '2':
                    return "re.ubigeo";
                default:
                    return "";
            }
        }

    }


    public function listar($id_grupo = 1, $cod_2000 = 'NULL')
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        $sector = session('usuario_sector');
        $nivel = session('usuario_nivel');
        $codigo = session('usuario_codigo_institucion');

        //return compact('sector', 'nivel', 'codigo', 'cod_2000');

        $codigo_institucion = ($cod_2000 == "NULL") ? '0' : $cod_2000;
        $codigo_institucion = ($sector == "22") ? $codigo : $codigo_institucion;
        //$nombre_columna = $this->columna($sector, $nivel); //  ($sector == '14') ? "re.cod_2000" : "re.ubigeo";
        //echo $nombre_columna." - ".$codigo_institucion;

        $nombre_columna = (strlen($cod_2000) != "9") ? $this->columna($sector, $nivel) : "re.cod_2000";
        //return $nombre_columna." - ".$codigo_institucion;

        $query = PadronNominalRepositorioSalud::Listar_PadronSabana($nombre_columna, $codigo_institucion, $id_grupo);
        //return $query;
        $data = [];
        foreach ($query as $key => $value) {
            $suma_controles = 0;
            for ($i = 1; $i <= 4; $i++) {
                $columna = 'f_credrn' . $i;
                $suma_controles += ($value->$columna == '-') ? 0 : 1;
            }

            $suma_controles = 0;
            for ($i = 1; $i <= 11; $i++) {
                $columna = 'f_cred0' . $i;
                $suma_controles += ($value->$columna == '-') ? 0 : 1;
            }

            $suma_suplemento = 0;
            for ($i = 1; $i <= 20; $i++) {
                $columna = 'f_sup' . $i;
                $suma_suplemento += ($value->$columna == '-') ? 0 : 1;
            }

            $suma_hemoglobina = 0;
            for ($i = 1; $i <= 6; $i++) {
                $columna = 'f_hb' . $i;
                $suma_hemoglobina += ($value->$columna == '-') ? 0 : 1;
            }

            $suma_anemia = 0;
            for ($i = 1; $i <= 3; $i++) {
                $columna = 'f_ane' . $i;
                $suma_anemia += ($value->$columna == '-') ? 0 : 1;
            }

            $suma_tratamiento = 0;
            for ($i = 1; $i <= 6; $i++) {
                $columna = 'f_sup' . $i;
                $suma_tratamiento += ($value->$columna == '-') ? 0 : 1;
            }

            $suma_vacuna = (($value->f_vhep == '-') ? 0 : 1) + (($value->f_vbcg == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vpenta1 == '-') ? 0 : 1) + (($value->f_vpenta2 == '-') ? 0 : 1) + (($value->f_vpenta3 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vipv1 == '-') ? 0 : 1) + (($value->f_vipv2 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vapo1 == '-') ? 0 : 1) + (($value->f_vapo2 == '-') ? 0 : 1) + (($value->f_vapo3 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vapo4 == '-') ? 0 : 1) + (($value->f_vapo5 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vrota1 == '-') ? 0 : 1) + (($value->f_vrota2 == '-') ? 0 : 1) + (($value->f_vrota3 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vneumo1 == '-') ? 0 : 1) + (($value->f_vneumo2 == '-') ? 0 : 1) + (($value->f_vneumo3 == '-') ? 0 : 1) + (($value->f_vneumo4 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vinflu1 == '-') ? 0 : 1) + (($value->f_vinflu2 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vspr1 == '-') ? 0 : 1) + (($value->f_vspr2 == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vama == '-') ? 0 : 1);
            $suma_vacuna += (($value->f_vdpt1 == '-') ? 0 : 1) + (($value->f_vdpt2 == '-') ? 0 : 1);

            $boton2 = '<button type="button" onclick="mostrarDatosSeguimiento(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            if ($sector != '14' or $nivel != '4') $boton2 = "";
            $documento = ($value->dni == "" or $value->dni == "-") ? ("CNV - " . $value->cnv) : ("DNI - " . $value->dni);
            // $documento = substr($documento, 0, -2) . "xx";
            $partesNombre = explode(" ", $value->nombre_nino);
            $apellidoPaterno = $partesNombre[0];
            $apellidoMaterno = $partesNombre[1];
            $nombrePersona = implode(" ", array_slice($partesNombre, 2));


            $data[] = array(
                $key + 1,
                $value->distrito,
                $value->eess,
                $documento,
                $nombrePersona . " " . $apellidoPaterno . " " . $apellidoMaterno . "",
                // substr($nombrePersona, 0, 1) . ". " . $apellidoPaterno . " " . substr($apellidoMaterno, 0, 1) . ".",
                $value->edad_anio,
                $suma_controles,
                $suma_suplemento,
                $suma_hemoglobina,
                $suma_anemia,
                $suma_tratamiento,
                $suma_vacuna,
                $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "codigo_institucion" => $codigo_institucion,
            "id_grupo" => $id_grupo
        );
        return response()->json($result);
    }



    public function mostrarDatos($id)
    {
        $tablon = PadronNominalRepositorioSalud::Listar_UnDatoSabana($id);
        $tablon->dni = substr($tablon->dni, 0, -2) . "xx";
        $tablon->renaes = str_pad($tablon->renaes, 9, '0', STR_PAD_LEFT);
        $partesNombre = explode(" ", $tablon->nombre_nino);
        $tablon->nombre_nino = substr(implode(" ", array_slice($partesNombre, 2)), 0, 1) . ". " . $partesNombre[0] . " " . substr($partesNombre[1], 0, 1) . ".";
        $tablon->celular = substr($tablon->celular, 0, -2) . "xx";
        $partes_fecha = explode("/", $tablon->fecha_nacimiento);
        $tablon->fecha_nacimiento = $partes_fecha[1] . '/' . $partes_fecha[2];

        return response()->json($tablon);
    }
}
