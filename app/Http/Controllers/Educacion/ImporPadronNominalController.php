<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\ImporPadronWebExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\ImporPadronNominal;
use App\Models\Educacion\Importacion;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporPadronNominalController extends Controller
{
    public $fuente = 50;
    public static $FUENTE = 50;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $fuente = $this->fuente;
        return view('educacion.ImporPadronNominal.Importar', compact('fuente'));
        //$mensaje = "";return view('educacion.ImporPadronWeb.Importar', compact('mensaje'));
    }

    public function exportar()
    {
        $imp = Importacion::where(['fuenteimportacion_id' => $this->fuente, 'estado' => 'PR'])->orderBy('fechaActualizacion', 'desc')->first();
        $mensaje = "";
        return view('educacion.ImporPadronWeb.Exportar', compact('mensaje', 'imp'));
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
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        if (ImportacionRepositorio::Importacion_PE($rq->fechaActualizacion, $this->fuente) !== null) {
            return $this->json_output(400, "Error, ya existe un archivo pendiente de aprobación para la fecha ingresada");
        }

        if (ImportacionRepositorio::Importacion_PR($rq->fechaActualizacion, $this->fuente) !== null) {
            return $this->json_output(400, "Error, ya existe un archivo procesado para la fecha ingresada");
        }

        $this->validate($rq, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $rq->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        $encabezadosEsperados = [
            'cod_mod',
            'modalidad',
            'dni',
            'validacion_dni',
            'apellido_paterno',
            'apellido_materno',
            'nombres',
            'sexo',
            'nacionalidad',
            'fecha_nacimiento',
            'lengua_materna',
            'grado',
            'seccion',
            'fecha_matricula',
        ];

        $encabezadosArchivo = array_keys($array[0][0]);

        $faltantes = array_diff($encabezadosEsperados, $encabezadosArchivo);
        if (!empty($faltantes)) {
            return $this->json_output(400, 'Error: Los encabezados del archivo no coinciden con el formato esperado. Faltan columnas esperadas.', $faltantes);
        }

        try {
            DB::beginTransaction();

            $importacion = Importacion::create([
                'fuenteImportacion_id' => $this->fuente,
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $rq->fechaActualizacion,
                'estado' => 'PR'
            ]);

            // $distritos = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=6')->pluck('id', 'codigo');
            // $provincias = Ubigeo::where('codigo', 'like', '25%')->whereRaw('length(codigo)=4')->pluck('id', 'codigo');

            $batchSize = 500;
            $dataBatch = [];
            foreach ($array[0] as $row) {
                $dataBatch[] = [
                    'importacion_id' => $importacion->id,
                    'cod_mod' => $row['cod_mod'],
                    'modalidad' => $row['modalidad'],
                    'dni' => $row['dni'],
                    'validacion_dni' => $row['validacion_dni'],
                    'apellido_paterno' => $row['apellido_paterno'],
                    'apellido_materno' => $row['apellido_materno'],
                    'nombres' => $row['nombres'],
                    'sexo' => $row['sexo'],
                    'nacionalidad' => $row['nacionalidad'],
                    'fecha_nacimiento' => $row['fecha_nacimiento'] === 'NULL' ? null : $row['fecha_nacimiento'],
                    'lengua_materna' => $row['lengua_materna'],
                    'grado' => $row['grado'],
                    'seccion' => $row['seccion'],
                    'fecha_matricula' => $row['fecha_matricula'] === 'NULL' ? null : $row['fecha_matricula'],
                ];

                if (count($dataBatch) >= $batchSize) {
                    ImporPadronNominal::insert($dataBatch);
                    $dataBatch = [];
                }
            }

            if (!empty($dataBatch)) {
                ImporPadronNominal::insert($dataBatch);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $importacion->estado = 'PE';
            $importacion->save();

            return $this->json_output(400, "Error en la carga de datos: " . $e->getMessage());
        }

        try {
            DB::select('call edu_pa_procesarImporPadronNominal(?,?)', [$importacion->id, '2025-03-31']);
        } catch (Exception $e) {
            $importacion->estado = 'PE';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos edu_pa_procesarPadronWeb." . $e;
            return $this->json_output(400, $mensaje);
        }
        /*
        try {
            DB::select('call edu_pa_procesar_cubo_pacto2_01(?)', [$importacion->id]);
        } catch (Exception $e) {
            $importacion->estado = 'PE';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos edu_pa_procesar_cubo_pacto2_01." . $e;
            return $this->json_output(400, $mensaje);
        }*/

        $this->json_output(200, "Archivo Excel subido y procesado correctamente.");
    }

    public function fechax($fecha)
    {
        try {
            $ff = '1900-01-01'; // str_replace('/', '-', $fecha);
            return date('Y-m-d', strtotime($ff . " + $fecha days - 1 days"));
        } catch (Exception $e) {
            return NULL;
        }
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = ImportacionRepositorio::Listar_FuenteTodos(ImporPadronNominalController::$FUENTE);
        $data = [];
        foreach ($query as $key => $value) {
            $registros = ImporPadronNominal::where('importacion_id', $value->id)->count();
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
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $ape,
                $ent ? $ent->abreviado : '',
                date("d/m/Y", strtotime($value->created_at)),
                $registros,
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }

    public function ListaImportada($importacion_id) //(Request $request, $importacion_id)
    {
        //$data = ImporPadronWeb::where('importacion_id', $importacion_id)->get();
        $data = DB::table(DB::raw("(
        SELECT
            ie.codModular as cod_mod,      ie.codLocal     as cod_local,                  ie.nombreInstEduc as institucion_educativa, nm.codigo as cod_nivelmod,         nm.nombre as nivel_modalidad,
            ff.nombre as forma,            cc.codigo       as cod_car,                    cc.nombre as carasteristica,                gg.codigo as cod_genero,           gg.nombre as genero,
            tg1.codigo as cod_gest,        tg1.nombre      as gestion,                    tg2.codigo as cod_ges_dep,                  tg2.nombre as gestion_dependencia, ie.nombreDirector as director,
            ie.telefono,                   ie.direccion    as direccion_centro_educativo, cp.codINEI as codcp_inei,                   cp.codUEMinedu as cod_ccpp,        cp.nombre as centro_poblado,
            aa.codigo as cod_area,         aa.nombre       as area_geografica,            ub1.codigo as codgeo,                       ub2.nombre as provincia,           ub1.nombre as distrito,
            uu2.nombre as d_region,        uu1.codigo      as codooii,                    uu1.nombre as ugel,                         ie.coorGeoLatitud as nlat_ie,      ie.coordGeoLongitud as nlong_ie,
            tt.codigo as cod_tur,          tt.nombre       as turno,                      ei.codigo as cod_estado,                    ei.nombre as estado,               pw.total_alumno_m as talum_hom,
            pw.total_alumno_f as talum_muj,pw.total_alumno as talumno,                    pw.total_docente as tdocente,               pw.total_seccion as tseccion
        FROM edu_padronweb as pw
        inner join edu_institucioneducativa as ie 	on ie.id=pw.institucioneducativa_id
        inner join edu_nivelmodalidad as nm 		on nm.id=ie.NivelModalidad_id
        inner join edu_forma as ff 				    on ff.id=ie.Forma_id
        inner join edu_caracteristica as cc 		on cc.id=ie.Caracteristica_id
        inner join edu_genero as gg 				on gg.id=ie.Genero_id
        inner join edu_tipogestion as tg1 			on tg1.id=ie.TipoGestion_id
        inner join edu_tipogestion as tg2 			on tg2.id=tg1.dependencia
        inner join edu_ugel as uu1 				    on uu1.id=ie.Ugel_id
        left join edu_ugel as uu2 					on uu2.id=uu1.dependencia
        inner join edu_area as aa 					on aa.id=ie.Area_id
        inner join edu_estadoinsedu as ei 			on ei.id=ie.EstadoInsEdu_id
        inner join edu_turno as tt 				    on tt.id=ie.Turno_id
        inner join edu_centropoblado as cp 		    on cp.id=ie.CentroPoblado_id
        inner join par_ubigeo as ub1 				on ub1.id=cp.Ubigeo_id
        inner join par_ubigeo as ub2 				on ub2.id=ub1.dependencia
        WHERE pw.importacion_id=$importacion_id
        ) as tb"))->get();
        return DataTables::of($data)->make(true);
    }

    // public function procesar($importacion_id)
    // {
    //     $procesar = DB::select('call edu_pa_procesarPadronWeb(?)', [$importacion_id]);
    //     return view('correcto');
    // }

    public function eliminar($id)
    {
        ImporPadronNominal::where('importacion_id', $id)->delete();
        Importacion::find($id)->delete();
        return response()->json(array('status' => true));
    }

    public function download()
    {
        $name = 'Padron Web ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronWebExport, $name);
    }

    public function cargar_edupaprocesarimporpadronnominal($importacion_id)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try {
            DB::select('call edu_pa_procesarImporPadronNominal(?,?)', [$importacion_id, '2025-03-31']);
            return response()->json(['status' => 'success', 'message' => 'Proceso completado correctamente']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error en la ejecución del proceso', 'error' => $e->getMessage()], 500);
        }
    }
}
