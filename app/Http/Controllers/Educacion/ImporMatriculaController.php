<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\ImporPadronSiagieExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporMatricula;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
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

class ImporMatriculaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 8, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mat = Matricula::where('importacion_id', $imp->id)->first();
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();
        return view('educacion.ImporMatricula.Importar', compact('mensaje', 'anios', 'mat'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => 8, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
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

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, 8);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, 8);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos procesados para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

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
                        $row['dre'] .
                        $row['ugel'] .
                        $row['departamento'] .
                        $row['provincia'] .
                        $row['distrito'] ./*  */
                        $row['centro_poblado'] .
                        $row['cod_mod'] .
                        $row['institucion_educativa'] .
                        $row['cod_nivelmod'] .
                        $row['nivel_modalidad'] ./* 10 */
                        $row['cod_ges_dep'] .
                        $row['gestion_dependencia'] .
                        $row['total_matriculados'] . //total_estudiantes
                        $row['matricula_definitiva'] .
                        $row['matricula_proceso'] ./*  */
                        $row['dni_validado'] .
                        $row['dni_sin_validar'] .
                        $row['registrado_sin_dni'] .
                        $row['total_grados'] .
                        $row['total_secciones'] ./* 20 */
                        /* $row['nominas_generadas'] .
                        $row['nominas_aprobadas'] .
                        $row['nominas_por_rectificar'] . */
                        $row['tres_anios_hombre'] .
                        $row['tres_anios_mujer'] .
                        $row['cuatro_anios_hombre'] .
                        $row['cuatro_anios_mujer'] .
                        $row['cinco_anios_hombre'] ./*  */
                        $row['cinco_anios_mujer'] .
                        $row['primero_hombre'] .
                        $row['primero_mujer'] .
                        $row['segundo_hombre'] .
                        $row['segundo_mujer'] ./* 30 */
                        $row['tercero_hombre'] .
                        $row['tercero_mujer'] .
                        $row['cuarto_hombre'] .
                        $row['cuarto_mujer'] .
                        $row['quinto_hombre'] ./*  */
                        $row['quinto_mujer'] .
                        $row['sexto_hombre'] .
                        $row['sexto_mujer'] .
                        $row['cero_anios_hombre'] .
                        $row['cero_anios_mujer'] ./* 40 */
                        $row['un_anio_hombre'] .
                        $row['un_anio_mujer'] .
                        $row['dos_anios_hombre'] .
                        $row['dos_anios_mujer'] .
                        $row['mas_cinco_anios_hombre'] ./* 45 */
                        $row['mas_cinco_anios_mujer'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $this->json_output(403, $mensaje);
        }
        /* $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, $array ); */
        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => 8, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);
            $anio = Anio::where('anio', $request['anio'])->first();

            $matricula = Matricula::Create([
                'importacion_id' => $importacion->id,
                'anio_id' => $anio->id,
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $padronWeb = ImporMatricula::Create([
                        'matricula_id' => $matricula->id,
                        'dre' => $row['dre'],
                        'ugel' => $row['ugel'],
                        'departamento' => $row['departamento'],
                        'provincia' => $row['provincia'],
                        'distrito' => $row['distrito'],/*  */
                        'centro_poblado' => $row['centro_poblado'],
                        'cod_mod' => $row['cod_mod'],
                        'institucion_educativa' => $row['institucion_educativa'],
                        'cod_nivelmod' => $row['cod_nivelmod'],
                        'nivel_modalidad' => $row['nivel_modalidad'],/* 10 */
                        'cod_ges_dep' => $row['cod_ges_dep'],
                        'gestion_dependencia' => $row['gestion_dependencia'],
                        'total_estudiantes' => $row['total_matriculados'], //total_estudiantes
                        'matricula_definitiva' => $row['matricula_definitiva'],
                        'matricula_proceso' => $row['matricula_proceso'],/*  */
                        'dni_validado' => $row['dni_validado'],
                        'dni_sin_validar' => $row['dni_sin_validar'],
                        'registrado_sin_dni' => $row['registrado_sin_dni'],
                        'total_grados' => $row['total_grados'],
                        'total_secciones' => $row['total_secciones'],/* 20 */
                        /* 'nominas_generadas' => $row['nominas_generadas'],
                        'nominas_aprobadas' => $row['nominas_aprobadas'],
                        'nominas_por_rectificar' => $row['nominas_por_rectificar'], */
                        'tres_anios_hombre' => $row['tres_anios_hombre'],
                        'tres_anios_mujer' => $row['tres_anios_mujer'],
                        'cuatro_anios_hombre' => $row['cuatro_anios_hombre'],
                        'cuatro_anios_mujer' => $row['cuatro_anios_mujer'],
                        'cinco_anios_hombre' => $row['cinco_anios_hombre'],/*  */
                        'cinco_anios_mujer' => $row['cinco_anios_mujer'],
                        'primero_hombre' => $row['primero_hombre'],
                        'primero_mujer' => $row['primero_mujer'],
                        'segundo_hombre' => $row['segundo_hombre'],
                        'segundo_mujer' => $row['segundo_mujer'],/* 30 */
                        'tercero_hombre' => $row['tercero_hombre'],
                        'tercero_mujer' => $row['tercero_mujer'],
                        'cuarto_hombre' => $row['cuarto_hombre'],
                        'cuarto_mujer' => $row['cuarto_mujer'],
                        'quinto_hombre' => $row['quinto_hombre'],/*  */
                        'quinto_mujer' => $row['quinto_mujer'],
                        'sexto_hombre' => $row['sexto_hombre'],
                        'sexto_mujer' => $row['sexto_mujer'],
                        'cero_anios_hombre' => $row['cero_anios_hombre'],
                        'cero_anios_mujer' => $row['cero_anios_mujer'],/* 40 */
                        'un_anio_hombre' => $row['un_anio_hombre'],
                        'un_anio_mujer' => $row['un_anio_mujer'],
                        'dos_anios_hombre' => $row['dos_anios_hombre'],
                        'dos_anios_mujer' => $row['dos_anios_mujer'],
                        'mas_cinco_anios_hombre' => $row['mas_cinco_anios_hombre'],/* 45 */
                        'mas_cinco_anios_mujer' => $row['mas_cinco_anios_mujer'],
                    ]);
                }
            }
        } catch (Exception $e) {
            $matricula->estado = 'EL';
            $matricula->save();

            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        try {
            $procesar = DB::select('call edu_pa_procesarImporMatricula(?,?)', [$matricula->id, $importacion->usuarioId_Crea]);
        } catch (Exception $e) {
            $matricula->estado = 'EL';
            $matricula->save();

            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e;
            $tipo = 'danger';
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

        $query = ImportacionRepositorio::Listar_FuenteTodos('8');
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

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
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
        $data = ImportacionRepositorio::Listar_FuenteTodos('8');
        return datatables()
            ->of($data)
            ->editColumn('fechaActualizacion', '{{date("d/m/Y",strtotime($fechaActualizacion))}}')
            ->editColumn('created_at', '{{date("d/m/Y",strtotime($created_at))}}')
            ->editColumn('estado', function ($query) {
                return $query->estado == "PR" ? "PROCESADO" : ($query->estado == "PE" ? "PENDIENTE" : "ELIMINADO");
            })
            ->addColumn('accion', function ($oo) {
                if (date('Y-m-d', strtotime($oo->created_at)) == date('Y-m-d') || session('perfil_id') == 3 || session('perfil_id') == 8 || session('perfil_id') == 9 || session('perfil_id') == 10 || session('perfil_id') == 11)
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
        $data = ImporMatricula::where('matricula_id', $rq->matricula_id)->get();
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
        $entidad = Importacion::find($id);
        $entidad->estado = 'EL';
        $entidad->save();

        $matricula = Matricula::where('importacion_id', $entidad->id)->first();
        $matricula->estado = 'EL';
        $matricula->save();

        return response()->json(array('status' => true));
    }

    public function download()
    {
        $name = 'SIAGIE MATRICULAS ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronSiagieExport, $name);
    }
}
