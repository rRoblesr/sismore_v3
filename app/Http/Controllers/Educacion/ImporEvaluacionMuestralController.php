<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\ImporPadronSiagieExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporEvaluacionMuestral;
use App\Models\Educacion\ImporMatricula;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaDetalle;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\ImporMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporEvaluacionMuestralController extends Controller
{
    public $fuente = 46;
    public static $FUENTE = 46;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = $this->fuente;
        return view('educacion.ImporGeneral.Importar', compact('fuente'));
        //$imp = Importacion::where(['fuenteimportacion_id' => 8, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        //$/mat = Matricula::where('importacion_id', $imp->id)->first();
        //$mensaje = "";
        ///$anios = Anio::orderBy('anio', 'desc')->get();
        ///return view('educacion.ImporMatricula.Importar', compact('mensaje', 'anios', 'mat'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        $mensaje = "";
        return view('educacion.ImporMatricula.Exportar', compact('mensaje', 'imp', 'mat'));
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

    public function guardar(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        // $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente);
        // if ($existeMismaFecha != null) {
        //     $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
        //     $this->json_output(400, $mensaje);
        // }

        // $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, $this->fuente);
        // if ($existeMismaFecha != null) {
        //     $mensaje = "Error, Ya existe archivos procesados para la fecha de versión ingresada";
        //     $this->json_output(400, $mensaje);
        // }

        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        if (count($array) != 1) {
            $this->json_output(400, 'Error de Hojas, Solo debe tener una HOJA, el LIBRO EXCEL');
        }

        try {
            foreach ($array as $value) {
                foreach ($value as $celda => $row) {
                    if ($celda > 0) break;
                    $cadena =
                        $row['anio'] .
                        $row['cod_mod'] .
                        $row['institucion_educativa'] .
                        $row['nivel'] .
                        $row['grado'] .
                        $row['seccion'] .
                        $row['gestion'] .
                        $row['caracteristica'] .
                        $row['codooii'] .
                        $row['codgeo'] .
                        $row['area_geografica'] .
                        $row['sexo'] .
                        $row['medida_l'] .
                        $row['grupo_l'] .
                        $row['peso_l'] .
                        $row['medida_m'] .
                        $row['grupo_m'] .
                        $row['peso_m'] .
                        $row['medida_cn'] .
                        $row['grupo_cn'] .
                        $row['peso_cn'] .
                        $row['medida_cs'] .
                        $row['grupo_cs'] .
                        $row['peso_cs'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $this->json_output(403, $mensaje);
        }
        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'estado' => 'PE'
            ]);
            // $anio = Anio::where('anio', $request['anio'])->first();

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $padronWeb = ImporEvaluacionMuestral::Create([
                        'importacion_id' => $importacion->id,
                        'anio' => $row['anio'],
                        'cod_mod' => $row['cod_mod'],
                        'institucion_educativa' => $row['institucion_educativa'],
                        'nivel' => $row['nivel'],
                        'grado' => $row['grado'],
                        'seccion' => $row['seccion'],
                        'gestion' => $row['gestion'],
                        'caracteristica' => $row['caracteristica'],
                        'codooii' => $row['codooii'],
                        'codgeo' => $row['codgeo'],
                        'area_geografica' => $row['area_geografica'],
                        'sexo' => $row['sexo'],
                        'medida_l' => $row['medida_l'],
                        'grupo_l' => $row['grupo_l'],
                        'peso_l' => $row['peso_l'],
                        'medida_m' => $row['medida_m'],
                        'grupo_m' => $row['grupo_m'],
                        'peso_m' => $row['peso_m'],
                        'medida_cn' => $row['medida_cn'],
                        'grupo_cn' => $row['grupo_cn'],
                        'peso_cn' => $row['peso_cn'],
                        'medida_cs' => $row['medida_cs'],
                        'grupo_cs' => $row['grupo_cs'],
                        'peso_cs' => $row['peso_cs']
                    ]);
                }
            }
        } catch (Exception $e) {
            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            // $procesar = DB::select('call edu_pa_procesarImporMatricula(?,?)', [$matricula->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $mensaje = "Error al procesar la normalizacion de datos." . $e;
            $this->json_output(400, $mensaje);
        }
        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
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

            $ent = Entidad::select('adm_entidad.*');
            $ent = $ent->join('adm_entidad as v2', 'v2.dependencia', '=', 'adm_entidad.id');
            $ent = $ent->join('adm_entidad as v3', 'v3.dependencia', '=', 'v2.id');
            $ent = $ent->where('v3.id', $value->entidad);
            $ent = $ent->first();

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs" id="eliminar' . $value->id . '"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $ape,
                $ent ? $ent->apodo : '',
                date("d/m/Y", strtotime($value->created_at)),
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
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
    public function ListarDTImportFuenteTodosx()
    {
        $permitidos = [3, 8, 9, 10, 11];
        $data = ImportacionRepositorio::Listar_FuenteTodos($this->fuente);
        return datatables()
            ->of($data)
            ->editColumn('fechaActualizacion', '{{date("d/m/Y",strtotime($fechaActualizacion))}}')
            ->editColumn('created_at', '{{date("d/m/Y",strtotime($created_at))}}')
            ->editColumn('estado', function ($query) {
                return $query->estado == "PR" ? "PROCESADO" : ($query->estado == "PE" ? "PENDIENTE" : "ELIMINADO");
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
            ->rawColumns(['fechaActualizacion', 'estado', 'accion', 'created_at', 'nombrecompleto'])
            ->toJson();
    }

    public function ListaImportada(Request $rq) //(Request $request, $importacion_id)
    {
        //$data = MatriculaDetalleRepositorio::listaImportada($importacion_id);
        $mat = Matricula::where('importacion_id', $rq->importacion_id)->first();
        $data = ImporMatricula::where('matricula_id', $mat->id)->get();
        //return response()->json($data);
        return DataTables::of($data)->make(true);
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = ImporMatriculaRepositorio::Listar_Por_Importacion_id($importacion_id);
        return  datatables()->of($padronWebLista)->toJson();
    }

    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
        //Importacion::where('id',$importacion_id)->first();

        return view('educacion.ImporMatricula.Aprobar', compact('importacion_id', 'importacion'));
    }

    public function procesar($importacion_id)
    {
        $procesar = DB::select('call edu_pa_procesarPadronWeb(?)', [$importacion_id]);
        return view('correcto');
    }

    public function eliminar($id)
    {
        $ii = Importacion::find($id);
        ImporEvaluacionMuestral::where('importacion_id', $id)->delete();
        $ii->delete();
        return response()->json(array('status' => true));
    }

    public function download()
    {
        $name = 'SIAGIE MATRICULAS ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronSiagieExport, $name);
    }
}
