<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Salud\PadronCalidad;
use App\Repositories\Salud\PadronNominalRepositorioSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PadronNominal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('salud.padron.principal');
    }

    public function control_calidad()
    {
        return view('salud.padron.principal');
    }

    public function listar()
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        $idNivel = (session('usuario_id') == '123') ? '1' : ((session('usuario_id') == '176') ? '2' : ((session('usuario_id') == '177') ? '3' : '4'));
        $institucion = $idNivel == '3' ? 'CALLERIA' : ($idNivel == '2' ? '5556' : '');
        /*if($idNivel=='3')
            $tablon = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'))->where('distrito', $institucion)->groupBy('codigo_calidad','nombre_calidad')->get();
        elseif($idNivel=='2')
            $tablon = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'))->where('cod_eess_atencion', $institucion)->groupBy('codigo_calidad','nombre_calidad')->get();
        else $tablon = "";
        */
        $query = PadronNominalRepositorioSalud::Listar_PadronSabana($idNivel, $institucion);
        $data = [];
        foreach ($query as $key => $value) {
            $boton = '<button type="button" onclick="geteliminar(' . $value->DNI . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            $boton2 = '<button type="button" onclick="monitor(' . $value->DNI . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                $value->Distrito,
                $value->EESS,
                $value->CNV,
                $value->DNI,
                $value->nino,
                $value->Edad_Anio,
                $value->f_hb1,
                $value->Rhb1,
                $value->f_hb2,
                $value->Rhb2,
                $value->f_hb3,
                $value->Rhb3,
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data
        );
        return response()->json($result);
    }

    public function seguimiento_ninio()
    {
        $actualizado = '';
        return view('salud.PadronNominal.seguimiento', compact('actualizado'));
    }

    public function calidad()
    {
        // return $red = PadronCalidad::from('sal_padron_calidad as pc')
        //     ->join('sal_establecimiento as e', 'e.codigo_unico', '=', 'pc.cod_eess_atencion')
        //     ->join('sal_microred as mr', 'mr.id', '=', 'e.microrred_id')
        //     ->join('sal_red as r', 'r.id', '=', 'mr.red_id')->distinct()->select('r.*')->get();
        $actualizado = '';
        return view('salud.PadronNominal.calidad', compact('actualizado'));
    }

    public function calidadListado(Request $rq)
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        // $sector = session('usuario_sector');
        // $nivel = session('usuario_nivel');
        // $codigo_institucion = session('usuario_codigo_institucion');
        // $nombre_columna = ($sector == '14') ? "cod_eess_atencion" : "ubigeo";
        // //dd($sector, ' - ', $nivel, ' - ', $nombre_columna, ' . ', $codigo_institucion);
        // $tablon = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'));
        // if (($sector == '14' && $nivel == 4) || $sector == '2')
        //     $tablon = $tablon->where($nombre_columna, $codigo_institucion);
        // if ($sector = '14' && $nivel == 2) {
        //     $ipress = DB::table('m_establecimiento')
        //         ->select('cod_2000')
        //         ->where('cod_disa', '34')->where('cod_red', $codigo_institucion);

        //     $tablon = $tablon->joinSub($ipress, 'es', function ($join) {
        //         $join->on('cod_eess_atencion', '=', 'es.cod_2000');
        //     });
        // }
        // $tablon = $tablon->groupBy('codigo_calidad', 'nombre_calidad')->get();

        $query = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'));
        // if ($rq->red > 0) $query = $query->where('red', $rq->red);
        // if ($rq->microred > 0) $query = $query->where('microred', $rq->microred);
        // if ($rq->eess > 0) $query = $query->where('ipress', $rq->eess);
        $query = $query->groupBy('codigo_calidad', 'nombre_calidad')->get();

        //dd($tablon);
        $data = [];
        foreach ($query as $key => $value) {
            $boton = "<button type='button' onclick=\"verListadoTipo('$value->codigo_calidad')\" class='btn btn-primary btn-xs'><i class='fa fa-list'></i> </button>";
            $data[] = array(
                $key + 1,
                $value->codigo_calidad,
                $value->nombre_calidad,
                $value->cantidad,
                $boton . '&nbsp;',
            );
        }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data
        );
        return response()->json($result);
    }
}
