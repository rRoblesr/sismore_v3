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

class SaludPadronNominal extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   return view('salud.padron.principal');
    }
	
	public function listar()
	{	$draw = 0;        $start = 0;        $length = 0;
        $idNivel = (session('usuario_id')=='123')?'1':((session('usuario_id')=='154')?'2':((session('usuario_id')=='155')?'3':'4'));
        $institucion = ($idNivel=='3')?'CALLERIA':(($idNivel=='2')?'5556':'');
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

    
}
