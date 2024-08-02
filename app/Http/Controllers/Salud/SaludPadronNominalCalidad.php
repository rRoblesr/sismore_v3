<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Salud\PadronCalidad;

class SaludPadronNominalCalidad extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $sector = session('usuario_sector');
        // $nivel = session('usuario_nivel');
        // $codigo = session('usuario_codigo_institucion');
        // return compact('sector', 'nivel', 'codigo');

        return view('salud.padron.calidad');
    }

    public function listadoGeneral()
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        $sector = session('usuario_sector');
        $nivel = session('usuario_nivel');
        $codigo_institucion = session('usuario_codigo_institucion');
        $nombre_columna = ($sector == '14') ? "cod_eess_atencion" : "ubigeo";
        //dd($sector, ' - ', $nivel, ' - ', $nombre_columna, ' . ', $codigo_institucion);
        $tablon = PadronCalidad::select('codigo_calidad', 'nombre_calidad', DB::raw('COUNT(*) AS cantidad'));
        if(($sector=='14' && $nivel==4) || $sector=='2')
            $tablon = $tablon->where($nombre_columna, $codigo_institucion);
        if( $sector='14' && $nivel==2 ) {   
            $ipress = DB::table('m_establecimiento')
                ->select('cod_2000')
                ->where('cod_disa', '34')->where('cod_red', $codigo_institucion);

            $tablon = $tablon->joinSub($ipress, 'es', function ($join) {
                    $join->on('cod_eess_atencion', '=', 'es.cod_2000');
                });
        }
            
        $tablon = $tablon->groupBy('codigo_calidad', 'nombre_calidad')->get();

        //dd($tablon);
        $data = [];
        foreach ($tablon as $key => $value) {
            $boton = "<button type='button' onclick=\"verListadoTipo('$value->codigo_calidad')\" class='btn btn-primary btn-xs'><i class='fa fa-list'></i> </button>";
            $data[] = array(
                $key + 1,
                $value->codigo_calidad,
                $value->nombre_calidad,
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

    public function listadoTipo($tipo)
    {
        $draw = 0;
        $start = 0;
        $length = 0;
        $sector = session('usuario_sector');$nivel = session('usuario_nivel');
        $codigo_institucion = session('usuario_codigo_institucion');
        $nombre_columna = ($sector == '14') ? "cod_eess_atencion" : "ubigeo";

        $calidad = PadronCalidad::select('codigo_calidad', 'nombre_calidad')->where('codigo_calidad', $tipo)->first();
        //dd($calidad);
        $tablon = PadronCalidad::select('*')->where('codigo_calidad', $tipo);

        if(($sector=='14' && $nivel==4) || $sector=='2')
            $tablon = $tablon->where($nombre_columna, $codigo_institucion);
        if( $sector='14' && $nivel==2 ) {   
            $ipress = DB::table('m_establecimiento')
                ->select('cod_2000')
                ->where('cod_disa', '34')->where('cod_red', $codigo_institucion);

            $tablon = $tablon->joinSub($ipress, 'es', function ($join) {
                    $join->on('cod_eess_atencion', '=', 'es.cod_2000');
                });
        }
            
        $tablon = $tablon->get();


        //dd($tablon);
        //$ugels = Ugel::select('id', 'nombre', 'codigo')->where('codigo', 'like', '25%')->orderBy('nombre', 'asc')->get();
        return view('salud.padron.calidadListadoTipo', compact('tablon', 'calidad'));
    }

    public function mostrarDatos($codigoCalidad, $codigoPadron)
    {
        $tablon = PadronCalidad::where('cod_padron', $codigoPadron)->first();

        return response()->json($tablon);
    }
}
