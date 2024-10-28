<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Salud\ImporPadronNominal;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class importarPadronNominalController extends Controller
{
    /* codigo unico de la fuente de importacion */
    public $fuente = 45;
    public static $FUENTE = 45;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* metodo para la vista del formulario para importar */
    public function importar()
    {
        $mensaje = "";
        return view('salud.ImporPadronNominal.Importar', compact('mensaje'));
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

    /*  metodo que carga el excel y ejecuta un procedimiento almacenado*/
    public function guardar(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        /* se esta  */
        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, $this->fuente);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, $this->fuente);
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
                        $row['padron'] .
                        $row['cnv'] .
                        $row['cui'] .
                        $row['dni'] .
                        $row['num_doc'] .
                        $row['tipo_doc'] .
                        $row['apellido_paterno'] .
                        $row['apellido_materno'] .
                        $row['nombre'] .
                        $row['genero'] .
                        $row['fecha_nacimiento'] .
                        $row['direccion'] .
                        $row['ubigeo'] .
                        $row['centro_poblado'] .
                        $row['area_ccpp'] .
                        $row['cui_nacimiento'] .
                        $row['cui_atencion'] .
                        $row['seguro'] .
                        $row['programa_social'] .
                        $row['visita'] .
                        $row['menor_encontrado'] .
                        $row['codigo_ie'] .
                        $row['nombre_ie'] .
                        $row['tipo_doc_madre'] .
                        $row['num_doc_madre'] .
                        $row['apellido_paterno_madre'] .
                        $row['apellido_materno_madre'] .
                        $row['nombres_madre'] .
                        $row['celular_madre'] .
                        $row['grado_instruccion'] .
                        $row['lengua_madre'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $this->json_output(403, $mensaje);
        }

        /* ajustar fecha */
        $anio = Anio::where('anio', date('Y'))->first();
        if (!$anio) {
            Anio::Create(['anio' => date('Y')]);
        }
        /* fin ajuste */

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => $this->fuente, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'estado' => 'PR'
            ]);


            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $nuevo = ImporPadronNominal::Create([
                        'importacion_id' => $importacion->id,
                        'padron' => $row['padron'],
                        'cnv' => $row['cnv'],
                        'cui' => $row['cui'],
                        'dni' => $row['dni'],
                        'num_doc' => $row['num_doc'],
                        'tipo_doc' => $row['tipo_doc'],
                        'apellido_paterno' => $row['apellido_paterno'],
                        'apellido_materno' => $row['apellido_materno'],
                        'nombre' => $row['nombre'],
                        'genero' => $row['genero'],
                        'fecha_nacimiento' => $row['fecha_nacimiento'],
                        'direccion' => $row['direccion'],
                        'ubigeo' => $row['ubigeo'],
                        'centro_poblado' => $row['centro_poblado'],
                        'area_ccpp' => $row['area_ccpp'],
                        'cui_nacimiento' => $row['cui_nacimiento'],
                        'cui_atencion' => $row['cui_atencion'],
                        'seguro' => $row['seguro'],
                        'programa_social' => $row['programa_social'],
                        'visita' => $row['visita'],
                        'menor_encontrado' => $row['menor_encontrado'],
                        'codigo_ie' => $row['codigo_ie'],
                        'nombre_ie' => $row['nombre_ie'],
                        'tipo_doc_madre' => $row['tipo_doc_madre'],
                        'num_doc_madre' => $row['num_doc_madre'],
                        'apellido_paterno_madre' => $row['apellido_paterno_madre'],
                        'apellido_materno_madre' => $row['apellido_materno_madre'],
                        'nombres_madre' => $row['nombres_madre'],
                        'celular_madre' => $row['celular_madre'],
                        'grado_instruccion' => $row['grado_instruccion'],
                        'lengua_madre' => $row['lengua_madre']
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'PE';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema - " . $e->getMessage();
            $this->json_output(400, $mensaje);
        }

        // try {
        //     DB::select('call sal_pa_procesarPadronEstablecimiento(?,?)', [$importacion->id, auth()->user()->id]);
        // } catch (Exception $e) {
        //     $mensaje = "Error al procesar la normalizacion de datos." . $e;
        //     $this->json_output(400, $mensaje);
        // }
        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $this->json_output(200, $mensaje, '');
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        // Ajusta el query con paginación
        $query = ImportacionRepositorio::Listar_FuenteTodos($this->fuente)
            ->offset($start)
            ->limit($length)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $ent = Entidad::find($value->entidad);
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }

            $boton = '';
            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || in_array(session('perfil_administrador_id'), [3, 8, 9, 10, 11])) {
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';
            }
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button>';

            $data[] = [
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $value->capellido1,
                $ent ? $ent->abreviado : '',
                date("d/m/Y", strtotime($value->created_at)),
                $value->estado == "PR" ? "PROCESADO" : "PENDIENTE",
                $boton . '&nbsp;' . $boton2,
            ];
        }

        $totalRecords = ImportacionRepositorio::Listar_FuenteTodos($this->fuente)->count();

        $result = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ];

        return response()->json($result);
    }
}
