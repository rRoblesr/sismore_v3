<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\ImporPadronWebExport;
use App\Exports\tablaXExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\ImporPadronWeb;
use App\Repositories\Educacion\ImporPadronWebRepositorio;
use App\Utilities\Utilitario;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class ImporPadronWebController extends Controller
{
    public $fuente = 1;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        return view('educacion.ImporPadronWeb.Importar', compact('mensaje'));
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

    public function guardar(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request->fechaActualizacion, 1);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            $tipo = 'danger';
            //return view('Educacion.ImporPadronWeb.Importar', compact('mensaje', 'tipo'));
            $this->json_output(400, $mensaje);
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PR($request->fechaActualizacion, 1);
        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos procesados para la fecha de versión ingresada";
            $tipo = 'danger';
            //return view('Educacion.ImporPadronWeb.Importar', compact('mensaje', 'tipo'));
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
                        $row['cod_mod'] . //cod_mod
                        $row['cod_local'] . //codlocal
                        $row['institucion_educativa'] . //cen_edu
                        $row['cod_nivelmod'] . //niv_mod
                        $row['nivel_modalidad'] . //d_niv_mod

                        $row['forma'] . //d_forma
                        $row['cod_car'] . //cod_car
                        $row['caracteristica'] . //d_cod_car
                        $row['cod_genero'] . //tipssexo
                        $row['genero'] . //d_tipssexo

                        $row['cod_gest'] . //gestion
                        $row['gestion'] . //d_gestion
                        $row['cod_ges_dep'] . //ges_dep
                        $row['gestion_dependencia'] . //d_ges_dep
                        $row['director'] . //director

                        $row['telefono'] . //telefono
                        $row['email'] . //email
                        $row['direccion_centro_educativo'] . //dir_cen
                        $row['localidad'] . //localidad
                        $row['codcp_inei'] . //codcp_inei

                        $row['cod_ccpp'] . //codccpp
                        $row['centro_poblado'] . //cen_pob
                        $row['cod_area'] . //area_censo
                        $row['area_geografica'] . //dareacenso
                        $row['codgeo'] . //codgeo

                        $row['provincia'] . //d_prov
                        $row['distrito'] . //d_dist
                        $row['codooii'] . //codooii
                        $row['ugel'] . //d_dreugel
                        $row['nlat_ie'] . //no tenia

                        $row['nlong_ie'] . //no tenia
                        $row['cod_tur'] . //cod_tur
                        $row['turno'] . //d_cod_tur
                        $row['cod_estado'] . //estado
                        $row['estado'] . //d_estado

                        $row['talum_hom'] . //talum_hom
                        $row['talum_muj'] . //talum_muj
                        $row['talumno'] . //talumno
                        $row['tdocente'] . //tdocente
                        $row['tseccion'] . //tseccion

                        $row['fecha_registro'] . //fechareg
                        $row['fecha_act']; //fecha_act

                    //$row['anexo'] .
                    //$row['pagweb'] . //pagweb
                    //$row['referencia'] . //referencia
                    //$row['d_dpto'] . //d_dpto
                    //$row['d_region'] . //d_region
                    //$row['d_fte_dato'] . //d_fte_dato
                    //$row['tipoprog'] . //tipoprog
                    //$row['d_tipoprog'] . //d_tipoprog
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto";
            $tipo = 'danger';
            //return view('Educacion.ImporPadronWeb.Importar', compact('mensaje', 'tipo'));
            $this->json_output(403, $mensaje);
        }

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => 1, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $padronWeb = ImporPadronWeb::Create([
                        'importacion_id' => $importacion->id,
                        'cod_Mod' => $row['cod_mod'],
                        'cod_Local' => $row['cod_local'],
                        'cen_Edu' => $row['institucion_educativa'],
                        'niv_Mod' => $row['cod_nivelmod'],
                        'd_Niv_Mod' => $row['nivel_modalidad'],

                        'd_Forma' => $row['forma'],
                        'cod_Car' => $row['cod_car'],
                        'd_Cod_Car' => $row['caracteristica'],
                        'TipsSexo' => $row['cod_genero'],
                        'd_TipsSexo' => $row['genero'],

                        'gestion' => $row['cod_gest'],
                        'd_Gestion' => $row['gestion'],
                        'ges_Dep' => $row['cod_ges_dep'],
                        'd_Ges_Dep' => $row['gestion_dependencia'],
                        'director' => $row['director'],

                        'telefono' => $row['telefono'],
                        'email' => $row['email'],
                        'dir_Cen' => $row['direccion_centro_educativo'],
                        'localidad' => $row['localidad'],
                        'codcp_Inei' => $row['codcp_inei'],

                        'codccpp' => $row['cod_ccpp'],
                        'cen_Pob' => $row['centro_poblado'],
                        'area_Censo' => $row['cod_area'],
                        'd_areaCenso' => $row['area_geografica'],
                        'codGeo' => $row['codgeo'],

                        'd_Prov' => $row['provincia'],
                        'd_Dist' => $row['distrito'],
                        'codOOII' => $row['codooii'],
                        'd_DreUgel' => $row['ugel'],
                        'nLat_IE' => $row['nlat_ie'],

                        'nLong_IE' => $row['nlong_ie'],
                        'cod_Tur' => $row['cod_tur'],
                        'estado' => $row['cod_estado'],
                        'd_Estado' => $row['estado'],
                        'D_Cod_Tur' => $row['turno'],
                        'tAlum_Hom' => $row['talum_hom'],
                        'tAlum_Muj' => $row['talum_muj'],

                        'tAlumno' => $row['talumno'],
                        'tDocente' => $row['tdocente'],
                        'tSeccion' => $row['tseccion'],
                        'fechaReg' => $row['fecha_registro'] == '' ? NULL : date('Y-m-d', strtotime($row['fecha_registro'])),
                        //'fecha_Act' => date('Y-m-d', strtotime('29-12-2021')), //Utilitario::Fecha_ConFormato_DMY($row['fecha_act']),
                        //'anexo' => $row['anexo'],
                        //'pagWeb' => $row['pagweb'],
                        //'referencia' => $row['referencia'],
                        //'d_Dpto' => $row['d_dpto'],
                        //'d_Region' => $row['d_region'],
                        //'tipoProg' => is_null($row['tipoprog']) ? '' : $row['tipoprog'],
                        //'d_TipoProg' => is_null($row['d_tipoprog']) ? '' : $row['d_tipoprog'],

                        //'d_Fte_Dato' => $row['d_fte_dato'],
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema" . $e->getMessage();
            $tipo = 'danger';
            //return view('Educacion.ImporPadronWeb.Importar', compact('mensaje', 'tipo'));
            $this->json_output(400, $mensaje);
        }

        //return redirect()->route('PadronWeb.PadronWeb_Lista', $importacion->id);
        try {
            $procesar = DB::select('call edu_pa_procesarPadronWeb(?)', [$importacion->id]);
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();

            $mensaje = "Error al procesar la normalizacion de datos." . $e;
            $tipo = 'danger';
            //return view('Educacion.ImporPadronWeb.Importar', compact('mensaje', 'tipo'));
            $this->json_output(400, $mensaje);
        }
        $mensaje = "Archivo excel subido y Procesado correctamente .";
        $tipo = 'primary';
        //return view('Educacion.ImporPadronWeb.Importar', compact('mensaje', 'tipo'));
        $this->json_output(200, $mensaje, '');
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = ImportacionRepositorio::Listar_FuenteTodos('1');
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
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
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
                $boton /* . '&nbsp;' . $boton2, */
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
        $data = ImportacionRepositorio::Listar_FuenteTodos('1');
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
            ->rawColumns(['fechaActualizacion', 'estado', 'accion', 'nombrecompleto'])
            ->toJson();
    }

    public function guardar_csv(Request $request)
    {
        $this->validate($request, ['file' => 'required|mimes:csv']);

        $file = $request->file('file');
        $lines = file($file);
        $utf8_lines = array_map('utf8_encode', $lines);
        $array = array_map('str_getcsv', $utf8_lines);

        $importacion = Importacion::Create([
            'fuenteImportacion_id' => 1, // valor predeterminado
            'usuarioId_Crea' => auth()->user()->id,
            'usuarioId_Aprueba' => null,
            'fechaActualizacion' => $request['fechaActualizacion'],
            'comentario' => $request['comentario'],
            'estado' => 'PE'
        ]);

        for ($i = 1; $i < sizeof($array); ++$i) {
            $padronWeb = ImporPadronWeb::Create([
                'importacion_id' => $importacion->id,
                'cod_Mod' => $array[$i][0],
                'anexo' => $array[$i][1],
                'cod_Local' => $array[$i][2],
                'cen_Edu' => $array[$i][3],
                'niv_Mod' => $array[$i][4],
                'd_Niv_Mod' => $array[$i][5],
                'd_Forma' => $array[$i][6],
                'cod_Car' => $array[$i][7],
                'd_Cod_Car' => $array[$i][8],
                'TipsSexo' => $array[$i][9],
                'd_TipsSexo' => $array[$i][10],
                'gestion' => $array[$i][11],
                'd_Gestion' => $array[$i][12],
                'ges_Dep' => $array[$i][13],
                'd_Ges_Dep' => $array[$i][14],
                'director' => $array[$i][15],
                'telefono' => $array[$i][16],
                'email' => $array[$i][17],
                'pagWeb' => $array[$i][18],
                'dir_Cen' => $array[$i][19],
                'referencia' => $array[$i][20],
                'localidad' => $array[$i][21],
                'codcp_Inei' => $array[$i][22],
                'codccpp' => $array[$i][23],
                'cen_Pob' => $array[$i][24],
                'area_Censo' => $array[$i][25],
                'd_areaCenso' => $array[$i][26],
                'codGeo' => $array[$i][27],
                'd_Dpto' => $array[$i][28],
                'd_Prov' => $array[$i][29],
                'd_Dist' => $array[$i][30],
                'd_Region' => $array[$i][31],
                'codOOII' => $array[$i][32],
                'd_DreUgel' => $array[$i][33],
                'nLat_IE' => 1,
                'nLong_IE' => 2,
                'tipoProg' => $array[$i][36],
                'd_TipoProg' => $array[$i][37],
                'cod_Tur' => $array[$i][38],
                'D_Cod_Tur' => $array[$i][39],
                'estado' => $array[$i][40],
                'd_Estado' => $array[$i][41],
                'd_Fte_Dato' => $array[$i][42],
                'tAlum_Hom' => $array[$i][43],
                'tAlum_Muj' => $array[$i][44],
                'tAlumno' => $array[$i][45],
                'tDocente' => $array[$i][46],
                'tSeccion' => $array[$i][47],
                'fechaReg' => null,
                'fecha_Act' => Utilitario::Fecha_ConFormato_DMY($array[$i][49]),
            ]);
        }

        return redirect()->route('PadronWeb.PadronWeb_Lista', $importacion->id);
        //$importacion_id = $importacion->id;
        //return view('ImportarEducacion.PadronWebLista_importada',compact('importacion_id'));
    }

    public function ListaImportada(Request $rq)
    {
        $importacion_id = $rq->importacion_id; //get('importacion_id');
        $data = ImporPadronWeb::where('importacion_id', $importacion_id)->get();
        return DataTables::of($data)->make(true);
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = ImporPadronWebRepositorio::Listar_Por_Importacion_id($importacion_id);

        return  datatables()->of($padronWebLista)->toJson();;
    }

    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
        //Importacion::where('id',$importacion_id)->first();

        return view('educacion.ImporPadronWeb.Aprobar', compact('importacion_id', 'importacion'));
    }

    public function procesar($importacion_id)
    {
        $procesar = DB::select('call edu_pa_procesarPadronWeb(?)', [$importacion_id]);
        return view('correcto');
    }

    public function download()
    {
        $name = 'Padron Web ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ImporPadronWebExport, $name);
    }
}
