<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Imports\Parametro\ImporPoblacionPNImport;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\Sexo;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImportacionRepositorio;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporPoblacionPNController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public $fuente = 47;
    public static $FUENTE = 47;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* metodo para la vista del formulario para importar */
    public function importar()
    {
        $mensaje = "";
        return view('parametro.ImporPoblacionPN.Importar', compact('mensaje'));
    }

    /* metodo para tener una salida de respuesta de la carga del excel */
    function json_output($status = 200, $msg = 'OK!!', $data = null)
    {
        header('Content-Type:application/json');
        echo json_encode([
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ]);
        die;
    }

    public function guardar(Request $rq)
    {
        $rq->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'fechaActualizacion' => 'required|date_format:Y-m-d',
        ]);

        $fechaActualizacion = Carbon::createFromFormat('Y-m-d', $rq->fechaActualizacion)->startOfDay();
        $usuarioId = auth()->user()->id;

        $importacionExistente = Importacion::where('fuenteImportacion_id', $this->fuente)
            ->whereDate('fechaActualizacion', $fechaActualizacion)
            ->whereIn('estado', ['PE', 'PR'])
            ->first();

        if ($importacionExistente) {
            return $this->json_output(
                400,
                'Ya existe una importación pendiente o procesada para esta fuente y fecha.'
            );
        }

        $importacion = Importacion::create([
            'fuenteImportacion_id' => $this->fuente,
            'usuarioId_Crea' => $usuarioId,
            'fechaActualizacion' => $fechaActualizacion,
            'estado' => 'PE',
        ]);

        try {
            PoblacionPN::truncate();
            Excel::import(new ImporPoblacionPNImport($importacion->id), $rq->file('file'), null, \Maatwebsite\Excel\Excel::XLSX, 0);
            $importacion->update(['estado' => 'PR']);
        } catch (\InvalidArgumentException $e) {
            return $this->json_output(
                400,
                'Archivo inválido: ' . $e->getMessage()
            );
        } catch (\Exception $e) {
            $importacion->update(['estado' => 'EL']);
            return $this->json_output(
                400,
                'Error al importar el archivo: ' . $e->getMessage()
            );
        }
 
        return $this->json_output(
            200,
            'Archivo importado exitosamente.'
        );
    }

    /* metodo para listar las importaciones */
    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $query = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
        $data = [];
        foreach ($query as $key => $value) {
            $ent = Entidad::find($value->entidad);
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $value->capellido1,
                $ent ? $ent->abreviado : '',
                date("d/m/Y", strtotime($value->created_at)),
                $value->estado == "PR" ? "PROCESADO" : "PENDIENTE",
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

    /* metodo para cargar una importacion especifica */
    public function ListaImportada(Request $rq)
    {
        $data = PoblacionPN::where('importacion_id', $rq->importacion_id)
            ->join('par_ubigeo as uu', 'uu.id', '=', 'par_poblacion_padron_nominal.ubigeo_id')
            ->join('par_sexo as ss', 'ss.id', '=', 'par_poblacion_padron_nominal.sexo_id')
            ->join('par_mes as mm', 'mm.id', '=', 'par_poblacion_padron_nominal.mes_id')
            ->select(
                'par_poblacion_padron_nominal.anio',
                'mm.codigo as mes_codigo',
                'uu.codigo as ubigeo_codigo',
                'ss.nombre as sexo_nombre',
                'par_poblacion_padron_nominal.seguro',
                'par_poblacion_padron_nominal.cnv',
                'par_poblacion_padron_nominal.0a',
                'par_poblacion_padron_nominal.1a',
                'par_poblacion_padron_nominal.2a',
                'par_poblacion_padron_nominal.3a',
                'par_poblacion_padron_nominal.4a',
                'par_poblacion_padron_nominal.5a',
                'par_poblacion_padron_nominal.28dias',
                'par_poblacion_padron_nominal.0_5meses',
                'par_poblacion_padron_nominal.6_11meses'
            );
        return DataTables::of($data)->make(true);
    }

    /* metodo para eliminar una importacion */
    public function eliminar($id)
    {
        PoblacionPN::where('importacion_id', $id)->first();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }
}
