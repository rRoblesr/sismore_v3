<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Imports\Educacion\ImporLocalesBeneficiadosImport;
use App\Imports\Educacion\ImporNexusImport;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\educacion\ImporLocalesBeneficiados;
use App\Models\educacion\ImporNexus;
use App\Models\Educacion\Importacion;
use App\Repositories\Educacion\ImporNexusRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Exception;

class ImporLocalesBeneficiadosController extends Controller
{
    public $fuente = 51;
    public static $FUENTE = 51;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = $this->fuente;
        return view('educacion.ImporGeneral.Importar', compact('fuente'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mensaje = '';
        return view('educacion.ImporLocalesBeneficiados.Exportar', compact('mensaje', 'imp'));
    }

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
            // return response()->json([
            //     'error' => 'Ya existe una importación pendiente o procesada para esta fuente y fecha.',
            //     'importacion_id' => $importacionExistente->id,
            //     'estado' => $importacionExistente->estado,
            // ], 422); // 422 Unprocessable Entity
        }

        $importacion = Importacion::create([
            'fuenteImportacion_id' => $this->fuente,
            'usuarioId_Crea' => $usuarioId,
            'fechaActualizacion' => $fechaActualizacion,
            'estado' => 'PE',
        ]);

        try {
            Excel::import(new ImporLocalesBeneficiadosImport($importacion->id), $rq->file('file'), null, \Maatwebsite\Excel\Excel::XLSX, 0);
            $importacion->update(['estado' => 'PR']);

            $errores = ImporLocalesBeneficiados::where('importacion_id', $importacion->id)
                ->where(function ($query) {
                    $query
                        ->whereNull('ubigeo_id')
                        ->orWhereNull('ugel_id');
                })
                ->count();

            $msg = 'Archivo importado exitosamente.';
            if ($errores > 0) {
                $msg .= ' Se encontraron ' . $errores . ' registros sin ubigeo o UGEL identificados.';
            }

            return $this->json_output(200, $msg);

            // return response()->json([
            //     'message' => 'Archivo importado exitosamente.',
            //     'importacion_id' => $importacion->id,
            //     'total_registros' => DB::table('edu_impor_nexus')->where('importacion_id', $importacion->id)->count(),
            // ], 200);
        } catch (\InvalidArgumentException $e) {
            return $this->json_output(
                400,
                'Archivo inválido: ' . $e->getMessage()
            );
            // return response()->json(['error' => 'Archivo inválido: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            $importacion->update(['estado' => 'EL']);
            return $this->json_output(
                400,
                'Error al importar el archivo: ' . $e->getMessage()
            );
            // return response()->json([
            //     'error' => 'Error al importar el archivo: ' . $e->getMessage(),
            // ], 500);
        }

        // try {
        //     DB::select('call edu_pa_procesarImporNexus(?)', [$importacion->id]);
        // } catch (Exception $e) {
        //     $importacion->update(['estado' => 'EL']);

        //     $mensaje = "Error al procesar la normalizacion de datos.<br>" . $e;
        //     $this->json_output(400, $mensaje);
        // }

        return $this->json_output(
            200,
            'Archivo importado exitosamente.'
        );
    }

    public function ListaImportada($importacion_id)
    {
        $data = ImporLocalesBeneficiados::where('edu_impor_locales_beneficiados.importacion_id', $importacion_id)
            ->leftJoin('edu_ugel', 'edu_impor_locales_beneficiados.ugel_id', '=', 'edu_ugel.id')
            ->leftJoin('par_ubigeo as dist', 'edu_impor_locales_beneficiados.ubigeo_id', '=', 'dist.id')
            ->leftJoin('par_ubigeo as prov', 'dist.dependencia', '=', 'prov.id')
            ->leftJoin('par_ubigeo as dpto', 'prov.dependencia', '=', 'dpto.id')
            ->select(
                'edu_impor_locales_beneficiados.*',
                DB::raw('COALESCE(edu_ugel.nombre, "SIN UGEL") as ugel_nombre'),
                DB::raw('COALESCE(dpto.nombre, "SIN DEPARTAMENTO") as departamento'),
                DB::raw('COALESCE(prov.nombre, "SIN PROVINCIA") as provincia'),
                DB::raw('COALESCE(dist.nombre, "SIN DISTRITO") as distrito')
            )
            ->get();

        return DataTables::of($data)->make(true);
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
        $data = [];
        foreach ($query as $key => $value) {
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            $ape = '';
            if (strlen($value->capellido1) > 0) {
                $xx = explode(' ', $value->capellido1 . ' ' . $value->capellido2);
                $ape = $xx[0];
            }

            $ent = Entidad::find($value->entidad);

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs" id="eliminar' . $value->id . '"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date('d/m/Y', strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $ape,
                $ent ? $ent->abreviado : '',
                date('d/m/Y', strtotime($value->created_at)),
                $value->estado == 'PR' ? 'PROCESADO' : ($value->estado == 'PE' ? 'PENDIENTE' : 'ELIMINADO'),
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            'draw' => $draw,
            'recordsTotal' => $start,
            'recordsFiltered' => $length,
            'data' => $data
        );
        return response()->json($result);
    }

    public function ListarDTImportFuenteTodosx()
    {
        $data = ImportacionRepositorio::Listar_FuenteTodos('2');
        return datatables()
            ->of($data)
            ->editColumn('fechaActualizacion', '{{date("d/m/Y",strtotime($fechaActualizacion))}}')
            ->editColumn('created_at', '{{date("d/m/Y",strtotime($created_at))}}')
            ->editColumn('estado', function ($query) {
                return $query->estado == 'PR' ? 'PROCESADO' : ($query->estado == 'PE' ? 'PENDIENTE' : 'ELIMINADO');
            })
            ->addColumn('accion', function ($oo) {
                if (date('Y-m-d', strtotime($oo->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
                    $msn = '<button type="button" onclick="geteliminar(' . $oo->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
                else
                    $msn = '';
                return $msn;
            })
            ->addColumn('nombrecompleto', function ($oo) {
                $nom = '';
                if (strlen($oo->cnombre) > 0) {
                    $xx = explode(' ', $oo->cnombre);
                    $nom = $xx[0];
                }
                $ape = '';
                if (strlen($oo->capellido1) > 0) {
                    $xx = explode(' ', $oo->capellido1 . ' ' . $oo->capellido2);
                    $ape = $xx[0];
                }
                return $nom . ' ' . $ape;
            })
            ->rawColumns(['fechaActualizacion', 'estado', 'accion', 'nombrecompleto'])
            ->toJson();
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $Lista = ImporNexusRepositorio::Listar_Por_Importacion_id($importacion_id);

        return datatables()->of($Lista)->toJson();;
    }

    public function procesar($importacion_id)
    {
        $procesar = DB::select('call edu_pa_procesarCuadroAsigPersonal(?,?)', [$importacion_id, auth()->user()->id]);
        return view('correcto');
    }

    public function eliminarx($id)
    {
        ImporNexus::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }

    public function eliminar($id)
    {
        try {
            ImporLocalesBeneficiados::where('importacion_id', $id)->delete();
            $importacion = Importacion::findOrFail($id);
            $importacion->delete();

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar importación ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['status' => false], 500);
        }
    }

    public function download()
    {
        // ini_set('memory_limit', '-1');
        // set_time_limit(0);
        // $name = 'NEXUS ' . date('Y-m-d') . '.xlsx';
        // return Excel::download(new ImporPadronNexusExport, $name);
    }
}
