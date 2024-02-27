<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\AvanceMatricula1Export;
use App\Exports\CensoDocenteInicialExport;
use App\Exports\CensoDocentePrimariaExport;
use App\Exports\CensoDocenteSecundariaExport;
use App\Http\Controllers\Controller;
use App\Models\Administracion\Sistema;
use App\Models\Educacion\Area;
use App\Models\Educacion\ImporCensoDocente;
use App\Models\Educacion\ImporCensoMatricula;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Indicador;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\Materia;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Clasificador;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Parametro\Lengua;
use App\Models\Parametro\Ubigeo;
use App\Models\Vivienda\EstadoConexion;
use App\Repositories\Educacion\CensoRepositorio;
use App\Repositories\Educacion\EceRepositorio;
use App\Repositories\Educacion\GradoRepositorio;
use App\Repositories\Educacion\ImporCensoDocenteRepositorio;
use App\Repositories\Educacion\ImporCensoMatriculaRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\IndicadorRepositorio;
use App\Repositories\Educacion\MateriaRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Repositories\Educacion\MatriculaGeneralRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Educacion\PadronWebRepositorio;
use App\Repositories\Educacion\PlazaRepositorio;
use App\Repositories\Educacion\ServiciosBasicosRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Vivienda\CentroPobladoDatassRepositorio;
use App\Repositories\Vivienda\CentroPobladoRepositotio;
use App\Repositories\Vivienda\EmapacopsaRepositorio;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;

class IndicadorController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public $mess = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $ugels = Ugel::select('id', 'codigo', 'nombre')->where('dependencia', '>', '0')->get();
        $mensaje = "";
        $lenguas = Lengua::where('estado', 0)->get();
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(12);
        //return Importacion::where('fuenteImportacion_id', $this->fuente)->where('estado', 'PR')->orderBy('fechaActualizacion','desc')->first();
        //return Importacion::all();

        return view('parametro.indicador.principal', compact('mensaje', 'lenguas', 'ugels', 'imp'));
        //return view('parametro.indicador.principal');
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = IndicadorRepositorio::listar(0, 0, 0);
        $data = [];
        foreach ($query as $key => $value) {
            $fuente = FuenteImportacion::find($value->fuenteimportacion_id);
            $sistema = Sistema::find($value->sistema_id);
            $clasificador = Clasificador::find($value->clasificador_id);
            //$btn1 = '<a href="#" class="btn btn-info btn-xs" onclick="edit(' . $value->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            //$btn3 = '&nbsp;<a href="#" class="btn btn-danger btn-xs" onclick="borrar(' . $value->id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i> </a>';
            $btn4 = '&nbsp;<button type="button" onclick="ver(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                $sistema->nombre,
                $clasificador->nombre,
                $fuente->nombre,
                $value->nombre,
                $value->url,
                $value->posicion,
                $btn4 //$btn1  . $btn4 . $btn3,
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

    public function indicadorEducacion($indicador_id)
    {
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
        switch ($indicador_id) {
            case '1': //CULMINACION
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 37;
                $inds = IndicadorRepositorio::listar_indicador1('1');
                return view('parametro.indicador.educat1', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case '2': //CULMINACION
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 38;
                $inds = IndicadorRepositorio::listar_indicador1('2');
                return view('parametro.indicador.educat1', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case '3': //CULMINACION
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 0; // ES MUY VARIBLE
                $inds = IndicadorRepositorio::listar_indicador1('3');
                return view('parametro.indicador.educat1', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case '4': //LOGROS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 2;
                $tipo = 0;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);
                return $this->vistaEducacionCat2($indicador_id, $title, $grado, $tipo, $sinaprobar);
            case '5': //LOGROS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 8;
                $tipo = 0;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);
                return $this->vistaEducacionCat2($indicador_id, $title, $grado, $tipo, $sinaprobar);
            case '6': //LOGROS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 4;
                $tipo = 0;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);
                return $this->vistaEducacionCat2($indicador_id, $title, $grado, $tipo, $sinaprobar);
            case '7': //LOGROS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 4;
                $tipo = 1; //EIB
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);
                return $this->vistaEducacionCat2($indicador_id, $title, $grado, $tipo, $sinaprobar);
            case '8': //ACCESO
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 1;
                $inds = IndicadorRepositorio::listar_indicador1('8');
                return view('parametro.indicador.educat3', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case '9': //ACCESO
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 37;
                $inds = IndicadorRepositorio::listar_indicador1('9');
                return view('parametro.indicador.educat3', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case '10': //ACCESO
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 38;
                $inds = IndicadorRepositorio::listar_indicador1('10');
                return view('parametro.indicador.educat3', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case '11': //PROFESORES
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 'INICIAL'; // [1, 2, 14]; //31
                return $this->vistaEducacionCat4($title, $nivel);
            case '12': //PROFESORES
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 'PRIMARIA'; // [7];
                return $this->vistaEducacionCat4($title, $nivel);
            case 13: //PROFESORES
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 'SECUNDARIA'; //[8];
                return $this->vistaEducacionCat4($title, $nivel);
            case 31: //SERVICIOS BASICOS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat5', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 32: //SERVICIOS BASICOS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat5', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 33: //SERVICIOS BASICOS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat5', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 34: //SERVICIOS BASICOS
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat5', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 40: //ACCESO A TIC
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel_id = '7';

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat6', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 41: //ACCESO A TIC
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = '8';

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat6', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 42: //ACCESO A TIC
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat6', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 43: //ACCESO A TIC
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.educat6_2', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            default:
                return 'sin datos';
                break;
        }
    }

    public function vistaEducacionCat1($title, $grado, $tipo, $sinaprobar)
    {
        return 'sin informacion';
    }
    public function vistaEducacionCat2($indicador_id, $title, $grado, $tipo, $sinaprobar)
    {
        $gt = GradoRepositorio::buscar_grado1($grado);
        $materias = MateriaRepositorio::buscar_materia3($grado, $tipo);

        foreach ($materias as $key => $materia) {
            $materia->indicador = EceRepositorio::listar_indicadoranio(date('Y'), $grado, $tipo, $materia->id, 'asc');
            $materia->previo = 0;
            foreach ($materia->indicador as $item) {
                $materia->previo += $item->previo;
            }
        }
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
        return view('parametro.indicador.educat2', compact('indicador_id', 'title', 'grado', 'tipo', 'sinaprobar', 'materias', 'gt', 'breadcrumb'));
    }
    public function indDetEdu($indicador_id, $grado, $tipo, $materia)
    { //desplegable ugel
        $gt = GradoRepositorio::buscar_grado1($grado);
        $mt = Materia::find($materia);
        $title = 'Estudiantes del ' . $gt[0]->grado . ' grado de ' . $gt[0]->nivel . ' que logran el nivel satisfactorio en ' . $mt->descripcion;
        $anios = EceRepositorio::listarAniosIngresados($grado, $tipo);
        foreach ($anios as $anio) {
            $anio->indicador = EceRepositorio::listar_indicadorugel($anio->anio, $grado, $tipo, $materia);
            $anio->previo = 0;
            foreach ($anio->indicador as $indicador) {
                $indicador->ugel = str_replace('UGEL', '', $indicador->ugel);
                $anio->previo += $indicador->previo;
            }
        }
        //return $anios;
        //return response()->json(compact('anios'));
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => url()->previous()], ['titulo' => 'Detalle', 'url' => '']];
        return view('parametro.indicador.educat2detalle', compact('title', 'grado', 'tipo', 'materia', 'anios', 'breadcrumb'));
    }
    public function indResEdu($indicador_id, $grado, $tipo, $materia)
    { //desplegable institucion
        $gt = GradoRepositorio::buscar_grado1($grado);
        $mt = Materia::find($materia);
        $title = 'Estudiantes del ' . $gt[0]->grado . ' grado de ' . $gt[0]->nivel . ' que logran el nivel satisfactorio en ' . $mt->descripcion;
        $anios = EceRepositorio::listarAniosIngresados($grado, $tipo);
        $areas = Area::all();
        $gestions = EceRepositorio::listar_gestion1($grado, $tipo);
        $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => url()->previous()], ['titulo' => 'Resumen', 'url' => '']];
        return view('parametro.indicador.educat2resumen', compact('title', 'grado', 'tipo', 'indicador_id', 'mt', 'anios', 'areas', 'gestions', 'provincias', 'breadcrumb'));
    }
    public function vistaEducacionCat3($title, $grado, $tipo, $sinaprobar)
    {
        return 'sin informacion';
    }
    public function vistaEducacionCat4($title, $nivel)
    {
        //$nivel = NivelModalidad::find($nivel_id);
        //$nivel =
        $ingresos = ImportacionRepositorio::Listar_dePLaza();

        $provincias = PlazaRepositorio::listar_provincia();
        //return $nivel;

        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
        return view('parametro.indicador.educat4', compact('title', 'nivel', 'ingresos', 'provincias', 'breadcrumb'));
    }
    public function vistaEducacionCat5($title, $nivel_id)
    {
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '01')], ['titulo' => 'Indicadores', 'url' => '']];
        return view('parametro.indicador.educat5', compact('title', 'breadcrumb'));
    }
    /****** */
    public function indicadorDRVCS($indicador_id)
    {
        switch ($indicador_id) {
            case 20: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL1
            case 21: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL2
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $ingresos = ImportacionRepositorio::Listar_deDatass();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '02')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.vivcat1', compact('title', 'breadcrumb', 'provincias', 'indicador_id', 'ingresos'));
            case 22: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL3
            case 23: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL4
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $provincias =  Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $ingresos = ImportacionRepositorio::Listar_deDatass();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '02')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.vivcat1', compact('title', 'breadcrumb', 'provincias', 'indicador_id', 'ingresos'));
            case 24: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL5

            case 25: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL6
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $provincias = EmapacopsaRepositorio::listarProvincias(); //Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $econexion = EstadoConexion::all();
                //return EmapacopsaRepositorio::listarDistrito(35);
                $ingresos = ImportacionRepositorio::Listar_deEmapacopsa();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '02')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.vivcat2', compact('title', 'breadcrumb', 'provincias', 'indicador_id', 'ingresos', 'econexion'));
            case 26: //PROGRAMA NACIONAL DE SANEAMIENTO RURAL7
            case 27: //PROGRAMAS DE VIVIENDA
            case 28: //PROGRAMAS DE VIVIENDA
            case 29: //PROGRAMAS DE VIVIENDA
            case 30: //PROGRAMAS DE VIVIENDA
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $ingresos = ImportacionRepositorio::Listar_deDatass();
                $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '02')], ['titulo' => 'Indicadores', 'url' => '']];
                return view('parametro.indicador.vivcat1', compact('title', 'breadcrumb', 'provincias', 'indicador_id', 'ingresos'));
            default:
                return 'sin informacion';
                break;
        }
    }
    /****** */
    public function indicadorPDRC($indicador_id)
    {
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '04')], ['titulo' => 'Indicadores', 'url' => '']];
        switch ($indicador_id) {
            case 14:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 2;
                $tipo = 0;
                $materia = 1;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);

                $gt = GradoRepositorio::buscar_grado1($grado);
                $materias = MateriaRepositorio::buscar_materia3($grado, $tipo, $materia);
                foreach ($materias as $key => $materia) {
                    $materia->previo = 0;
                    $materia->indicador = EceRepositorio::listar_indicadoranio(date('Y'), $grado, $tipo, $materia->id, 'asc');
                    foreach ($materia->indicador as $item) {
                        $materia->previo += $item->previo;
                    }
                }
                return view('parametro.indicador.pdrc1', compact('title', 'grado', 'tipo', 'sinaprobar', 'materias', 'gt', 'breadcrumb'));
            case 15:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 2;
                $tipo = 0;
                $materia = 2;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);

                $gt = GradoRepositorio::buscar_grado1($grado);
                $materias = MateriaRepositorio::buscar_materia3($grado, $tipo, $materia);
                foreach ($materias as $key => $materia) {
                    $materia->previo = 0;
                    $materia->indicador = EceRepositorio::listar_indicadoranio(date('Y'), $grado, $tipo, $materia->id, 'asc');
                    foreach ($materia->indicador as $item) {
                        $materia->previo += $item->previo;
                    }
                }
                return view('parametro.indicador.pdrc1', compact('title', 'grado', 'tipo', 'sinaprobar', 'materias', 'gt', 'breadcrumb'));
            case 16:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 38;
                $inds = IndicadorRepositorio::listar_indicador1('2');
                return view('parametro.indicador.educat1', compact('title', 'nivel', 'inds', 'breadcrumb'));
            case 17:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 37;
                $inds = IndicadorRepositorio::listar_indicador1('1');
                return view('parametro.indicador.educat1', compact('title', 'nivel', 'inds', 'breadcrumb'));
            default:
                return 'sin informacion';
                break;
        }
    }
    /****** */
    public function indicadorOEI($indicador_id)
    {
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '05')], ['titulo' => 'Indicadores', 'url' => '']];
        switch ($indicador_id) {
            case 18:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 8;
                $tipo = 0;
                $materia = 2;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);
                return $this->vistaOEI($indicador_id, $title, $grado, $tipo, $sinaprobar, $materia);
            case 19:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $grado = 4;
                $tipo = 1; //EIB
                $materia = 5;
                $sinaprobar = ImportacionRepositorio::listar_ImportacionSinAprobarEce($grado, $tipo);
                return $this->vistaOEI($indicador_id, $title, $grado, $tipo, $sinaprobar, $materia);

            case 35:
                return  redirect()->route('CuadroAsigPersonal.ReportePedagogico');
            case 37:
                return  redirect()->route('CuadroAsigPersonal.Bilingues');
            case 38:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                return view('parametro.indicador.educat5', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 39:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = '38';

                $provincias = Ubigeo::whereRaw('LENGTH(codigo)=4')->get();
                $fechas = CensoRepositorio::listar_anios();
                return view('parametro.indicador.educat6', compact('title', 'breadcrumb', 'provincias', 'fechas', 'indicador_id'));
            case 36:
                $indicador = Indicador::find($indicador_id);
                $title = $indicador->nombre;
                $nivel = 38;
                $inds = IndicadorRepositorio::listar_indicador1('10');
                return view('parametro.indicador.educat3', compact('title', 'nivel', 'inds', 'breadcrumb'));
            default:
                return 'sin informacion';
                break;
        }
    }
    public function vistaOEI($indicador_id, $title, $grado, $tipo, $sinaprobar, $materia)
    {
        $gt = GradoRepositorio::buscar_grado1($grado);
        //$anios = IndicadorRepositorio::listarAniosIngresados($grado, $tipo);
        $aniosx = EceRepositorio::listarAniosIngresados($grado, $tipo);
        $areas = Area::all();
        $gestions = EceRepositorio::listar_gestion1($grado, $tipo);

        $materias = MateriaRepositorio::buscar_materia3($grado, $tipo, $materia);
        foreach ($materias as $key => $materiax) {
            $materiax->indicador = EceRepositorio::listar_indicadoranio(date('Y'), $grado, $tipo, $materiax->id, 'asc');
            $materiax->previo = 0;
            foreach ($materiax->indicador as $item) {
                $materiax->previo += $item->previo;
            }
        }
        $anios = EceRepositorio::listarAniosIngresados($grado, $tipo);
        foreach ($anios as $anio) {
            $anio->indicador = EceRepositorio::listar_indicadorugel($anio->anio, $grado, $tipo, $materia);
            $anio->previo = 0;
            foreach ($anio->indicador as $indicador) {
                $indicador->ugel = str_replace('UGEL', '', $indicador->ugel);
                $anio->previo += $indicador->previo;
            }
        }
        //return $anios;
        $breadcrumb = [['titulo' => 'Relacion de indicadores', 'url' => route('Clasificador.menu', '05')], ['titulo' => 'Indicadores', 'url' => '']];
        return view('parametro.indicador.oei1', compact('indicador_id', 'title', 'grado', 'tipo', 'materia', 'sinaprobar', 'materias', 'gt', 'anios', 'aniosx', 'areas', 'gestions', 'breadcrumb'));
    }
    /*****OTRAS OPCIONES */
    public function cargarprovincias()
    {
        $provincias = UbigeoRepositorio::provincia25();
        return response()->json($provincias);
    }
    public function cargardistritos($provincia)
    {
        $distritos = UbigeoRepositorio::distrito25($provincia);
        return response()->json(compact('distritos'));
    }
    public function cargargrados(Request $request)
    {
        $grados = GradoRepositorio::buscar_grados1($request->nivel);
        return response()->json(compact('grados'));
    }
    public function reporteSatisfactorioMateria(Request $request)
    {
        $inds = EceRepositorio::listar_indicadorsatisfactorio1($request->anio, $request->grado, $request->tipo, $request->materia);
        //return $inds;
        $card = '';
        foreach ($inds as $ind) {
            $card .= '<div class="col-md-6 col-xl-6">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-success rounded-circle mr-2">
                            <i class="ion-md-contacts avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="my-0 font-weight-bold"><span data-plugin="counterup">' . $ind->p4 . '</span>%</h4>
                                <p class="mb-0 mt-1 text-truncate">' . $ind->materia . ' - porcentaje</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   ';
            $card .= '<div class="col-md-6 col-xl-6">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md bg-success rounded-circle mr-2">
                            <i class="ion-md-contacts avatar-title font-26 text-white"></i>
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="my-0 font-weight-bold"><span data-plugin="counterup">' . $ind->satisfactorio . '</span></h4>
                                <p class="mb-0 mt-1 text-truncate">' . $ind->materia . ' - cantidad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   ';
        }
        return $card;
    }

    public function reporteUbigeoAjax(Request $request)
    {
        $materia = Materia::find($request->materia);
        if ($request->provincia == 0) {
            $inds = EceRepositorio::listar_indicadorprovincia($request->anio, $request->grado, $request->tipo, $materia->id);
            $inds2 = EceRepositorio::listar_indicadordepartamento($request->anio, $request->grado, $request->tipo, $materia->id);
            $inds[$inds->count()] = $inds2[0];
        } else {
            if ($request->distrito == 0) {
                $inds = EceRepositorio::listar_indicadordistrito($request->anio, $request->grado, $request->tipo, $materia->id, $request->provincia);
                $inds2 = EceRepositorio::listar_indicadorprovincia($request->anio, $request->grado, $request->tipo, $materia->id, $request->provincia);
                $inds[$inds->count()] = $inds2[0];
            } else {
                $inds = EceRepositorio::listar_indicadordistrito($request->anio, $request->grado, $request->tipo, $materia->id, $request->provincia, $request->distrito);
            }
        }
        return $inds;
    }

    public function reporteGestionAreaDT($anio, $grado, $tipo, $materia, $gestion, $area)
    {
        $inds = EceRepositorio::listar_indicadorInstitucion($anio, $grado, $tipo, $materia, $gestion, $area);
        //return response()->json(compact('anio','grado','tipo','materia','gestion','area'));
        return  datatables()->of($inds)
            ->editColumn('nombre', '<div class="">{{$nombre}}</div>')
            ->editColumn('previo', '<div class="text-center">{{$previo}}</div>')
            ->editColumn('p1', '<div class="text-center">{{$p1}}%</div>')
            ->editColumn('inicio', '<div class="text-center">{{$inicio}}</div>')
            ->editColumn('p2', '<div class="text-center">{{$p2}}%</div>')
            ->editColumn('proceso', '<div class="text-center">{{$proceso}}</div>')
            ->editColumn('p3', '<div class="text-center">{{$p3}}%</div>')
            ->editColumn('satisfactorio', '<div class="text-success text-center">{{$satisfactorio}}</div>')
            ->editColumn('p4', '<div class="text-success text-center">{{$p4}}%</div>')
            ->editColumn('evaluados', '<div class="text-center">{{$evaluados}}</div>')
            ->rawColumns(['nombre', 'previo', 'p1', 'inicio', 'p2', 'proceso', 'p3', 'satisfactorio', 'p4', 'evaluados',])
            ->toJson();
    }
    public function ReporteCPVivDT($provincia, $distrito, $importacion_id, $indicador_id)
    {
        $inds = CentroPobladoRepositotio::listar_porProvinciaDistrito($provincia, $distrito, $importacion_id, $indicador_id);
        //return response()->json(compact('provincia', 'distrito', 'indicador_id', 'importacion_id'));
        return  datatables()->of($inds)->toJson(); //*/
    }
    public function indicadorvivpnsrcab($provincia, $distrito, $indicador_id, $fecha)
    {
        $cp = CentroPobladoRepositotio::ListarSINO_porIndicador($provincia, $distrito, $indicador_id, $fecha);
        return response()->json($cp);
    }
    public function indicadorviv2pnsrcab($provincia, $distrito, $indicador_id,  $fecha)
    {
        $cp = EmapacopsaRepositorio::ListarSINO_porIndicador($provincia, $distrito, $indicador_id,  $fecha);
        return response()->json($cp);
    }
    public function ajaxEdu5v1($provincia, $distrito, $indicador_id, $anio_id)
    {
        switch ($indicador_id) {
            case 31:
                $cp = CensoRepositorio::listar_conElectricidad($provincia, $distrito, $indicador_id, $anio_id);
                break;
            case 32:
                $cp = CensoRepositorio::listar_conAguaPotable($provincia, $distrito, $indicador_id, $anio_id);
                break;
            case 33:
                $cp = CensoRepositorio::listar_conDesague($provincia, $distrito, $indicador_id, $anio_id);
                break;
            case 34:
            case 38:
                $cp = CensoRepositorio::listar_conServicioBasico($provincia, $distrito, $indicador_id, $anio_id);
                break;
            default:
                return [];
                break;
        }

        return response()->json($cp);
    }
    public function ajaxEdu6v1($provincia, $distrito, $indicador_id, $anio_id)
    {
        switch ($indicador_id) {
            case 40:
                $nivel = '7';
                $cp = CensoRepositorio::Listar_IE_nivel($provincia, $distrito, $indicador_id, $anio_id, $nivel);
                break;
            case 41:
            case 39:
                $nivel = '8';
                $cp = CensoRepositorio::Listar_IE_nivel($provincia, $distrito, $indicador_id, $anio_id, $nivel);
                break;
            case 42:
                $cp = [];
                break;
            case 43:
                $cp = CensoRepositorio::Listar_IE_computo($provincia, $distrito, $indicador_id, $anio_id);
                break;
            default:
                return [];
                break;
        }

        return response()->json($cp);
    }




    public function panelControlEduacionNuevoindicador01() //se paso matriculageneralcontroller
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE); //nexus

        $strSiagie = strtotime($imp->fecha);
        $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        $anios = MatriculaGeneralRepositorio::anios();
        $aniomax = MatriculaGeneralRepositorio::anioMax();
        $provincia = UbigeoRepositorio::provincia25();

        return  view('parametro.indicador.educacion.inicioEducacionIndicador01', compact('anios', 'aniomax', 'provincia', 'actualizado',));
    }

    public function panelControlEduacionNuevoindicador01head(Request $rq) //se paso matriculageneralcontroller
    {
        $xx = MatriculaGeneralRepositorio::indicador01head($rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 1);
        $valor1 = $xx->basica;
        $valor2 = $xx->ebr;
        $valor3 = $xx->ebe;
        $valor4 = $xx->eba;
        $aa = Anio::find($rq->anio);
        $aav =  -1 + (int)$aa->anio;
        $aa = Anio::where('anio', $aav)->first();
        $xx = MatriculaGeneralRepositorio::indicador01head($aa->id, $rq->provincia, $rq->distrito,  $rq->gestion, 1);
        $valor1x = $xx->basica;
        $valor2x = $xx->ebr;
        $valor3x = $xx->ebe;
        $valor4x = $xx->eba;

        $ind1 = number_format($valor1x > 0 ? 100 * $valor1 / $valor1x : 0, 1);
        $ind2 = number_format($valor2x > 0 ? 100 * $valor2 / $valor2x : 0, 1);
        $ind3 = number_format($valor3x > 0 ? 100 * $valor3 / $valor3x : 0, 1);
        $ind4 = number_format($valor4x > 0 ? 100 * $valor4 / $valor4x : 0, 1);

        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);

        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4', 'ind1', 'ind2', 'ind3', 'ind4'));
    }

    public function panelControlEduacionNuevoindicador01Tabla(Request $rq) //se paso matriculageneralcontroller
    {
        switch ($rq->div) {
            case 'anal1':
                $datax = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0, 0);
                $info['series'] = [];
                $alto = 0;
                $btotal = 0;
                $data2017 = 80000;
                //$dx2 = [];
                $dx3 = [];
                $dx4 = [];
                foreach ($datax as $key => $value) {
                    //$dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($datax as $keyi => $ii) {
                    $info['categoria'][] = $ii->anio;
                    $n = (int)$ii->suma;
                    $d = $ii->anio == 2018 ? $n : (int)$datax[$keyi - 1]['suma'];
                    //$dx2[$keyi] = $d;
                    $dx3[$keyi] = $n;
                    $dx4[$keyi] = $d > 0 ? round(100 * $n / $d, 1) : 100;
                    $alto = $n > $alto ? $n : $alto;
                }
                //$alto = 0;
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados',  'data' => $dx3];
                //$info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Matriculados', 'data' => $dx2];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Avance', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal2':
                $datax = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0, 0);
                $info['cat'] = [];
                $info['dat'] = [];
                $xx = 0;
                foreach ($datax as $key => $value) {
                    $info['cat'][] = $this->mess[$value->mes - 1];
                    $xx += $value->conteo;
                    $info['dat'][] = $xx;
                }
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal3':
                $info = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0, 0);
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'anal4':
                $info = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0, 0);
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info', 'reg'));
            case 'tabla1':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaUgel($anioy->id, $rq->provincia, $rq->distrito,  $rq->gestion, 0);
                $base = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0, 0);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->ugel == $mm->ugel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                $excel = view('parametro.indicador.educacion.inicioEducacionIndicador01Table1', compact('base', 'foot'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));

            case 'tabla2':
                $aniox = Anio::find($rq->anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaNivel($anioy->id, $rq->provincia, $rq->distrito,  $rq->gestion,  $rq->ugel);
                $base = MatriculaGeneralRepositorio::indicador01tabla($rq->div, $rq->anio, $rq->provincia, $rq->distrito,  $rq->gestion, 0,  $rq->ugel);
                $head = [];
                $foot = [];
                if ($base->count() > 0) {
                    $ii = 0;
                    foreach ($base->unique('tipo')->sortByDesc('tipo') as $key => $value) {
                        $head[$ii++] = clone $value;
                    }
                    foreach ($head as $key => $value) {
                        $value->meta = 0;
                        $value->ene = 0;
                        $value->feb = 0;
                        $value->mar = 0;
                        $value->abr = 0;
                        $value->may = 0;
                        $value->jun = 0;
                        $value->jul = 0;
                        $value->ago = 0;
                        $value->sep = 0;
                        $value->oct = 0;
                        $value->nov = 0;
                        $value->dic = 0;
                    }

                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;


                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->nivel == $mm->nivel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;

                        foreach ($head as $key => $hh) {
                            if ($hh->tipo == $value->tipo) {
                                $hh->meta += $value->meta;
                                $hh->ene += $value->ene;
                                $hh->feb += $value->feb;
                                $hh->mar += $value->mar;
                                $hh->abr += $value->abr;
                                $hh->may += $value->may;
                                $hh->jun += $value->jun;
                                $hh->jul += $value->jul;
                                $hh->ago += $value->ago;
                                $hh->sep += $value->sep;
                                $hh->oct += $value->oct;
                                $hh->nov += $value->nov;
                                $hh->dic += $value->dic;
                            }
                        }

                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    foreach ($head as $key => $hh) {
                        $hh->total = $hh->ene + $hh->feb + $hh->mar + $hh->abr + $hh->may + $hh->jun + $hh->jul + $hh->ago + $hh->sep + $hh->oct + $hh->nov + $hh->dic;
                        $hh->avance = $hh->meta > 0 ? 100 * $hh->total / $hh->meta : 100;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                $excel = view('parametro.indicador.educacion.inicioEducacionIndicador01Table2', compact('base', 'foot', 'head'))->render();
                // return response()->json(compact('excel'));
                $reg['fuente'] = 'Siagie - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);
                $reg['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('excel', 'reg'));
            default:
                return [];
        }
    }

    public function panelControlEduacionNuevoindicador01Export($div, $anio, $provincia, $distrito, $gestion, $ugel) //se paso matriculageneralcontroller
    {
        switch ($div) {
            case 'tabla1':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaUgel($anioy->id, $provincia, $distrito,  $gestion, 0);
                $base = MatriculaGeneralRepositorio::indicador01tabla($div, $anio, $provincia, $distrito,  $gestion, 0, 0);
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;

                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->ugel == $mm->ugel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;
                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                return compact('base', 'foot');

            case 'tabla2':
                $aniox = Anio::find($anio);
                $anioy = Anio::where('anio', $aniox->anio - 1)->first();
                $meta = MatriculaGeneralRepositorio::metaNivel($anioy->id, $provincia, $distrito,  $gestion,  $ugel);
                $base = MatriculaGeneralRepositorio::indicador01tabla($div, $anio, $provincia, $distrito,  $gestion, 0,  $ugel);
                $head = [];
                $foot = [];
                if ($base->count() > 0) {
                    $ii = 0;
                    foreach ($base->unique('tipo')->sortByDesc('tipo') as $key => $value) {
                        $head[$ii++] = clone $value;
                    }
                    foreach ($head as $key => $value) {
                        $value->meta = 0;
                        $value->ene = 0;
                        $value->feb = 0;
                        $value->mar = 0;
                        $value->abr = 0;
                        $value->may = 0;
                        $value->jun = 0;
                        $value->jul = 0;
                        $value->ago = 0;
                        $value->sep = 0;
                        $value->oct = 0;
                        $value->nov = 0;
                        $value->dic = 0;
                    }

                    $foot = clone $base[0];
                    $foot->meta = 0;
                    $foot->ene = 0;
                    $foot->feb = 0;
                    $foot->mar = 0;
                    $foot->abr = 0;
                    $foot->may = 0;
                    $foot->jun = 0;
                    $foot->jul = 0;
                    $foot->ago = 0;
                    $foot->sep = 0;
                    $foot->oct = 0;
                    $foot->nov = 0;
                    $foot->dic = 0;


                    foreach ($base as $key => $value) {
                        $value->meta = 0;
                        foreach ($meta as $kk => $mm) {
                            if ($value->nivel == $mm->nivel) {
                                $value->meta = $mm->conteo;
                                break;
                            }
                        }
                        $value->total = $value->ene + $value->feb + $value->mar + $value->abr + $value->may + $value->jun + $value->jul + $value->ago + $value->sep + $value->oct + $value->nov + $value->dic;
                        $value->avance = $value->meta > 0 ? 100 * $value->total / $value->meta : 100;

                        foreach ($head as $key => $hh) {
                            if ($hh->tipo == $value->tipo) {
                                $hh->meta += $value->meta;
                                $hh->ene += $value->ene;
                                $hh->feb += $value->feb;
                                $hh->mar += $value->mar;
                                $hh->abr += $value->abr;
                                $hh->may += $value->may;
                                $hh->jun += $value->jun;
                                $hh->jul += $value->jul;
                                $hh->ago += $value->ago;
                                $hh->sep += $value->sep;
                                $hh->oct += $value->oct;
                                $hh->nov += $value->nov;
                                $hh->dic += $value->dic;
                            }
                        }

                        $foot->meta += $value->meta;
                        $foot->ene += $value->ene;
                        $foot->feb += $value->feb;
                        $foot->mar += $value->mar;
                        $foot->abr += $value->abr;
                        $foot->may += $value->may;
                        $foot->jun += $value->jun;
                        $foot->jul += $value->jul;
                        $foot->ago += $value->ago;
                        $foot->sep += $value->sep;
                        $foot->oct += $value->oct;
                        $foot->nov += $value->nov;
                        $foot->dic += $value->dic;
                    }
                    foreach ($head as $key => $hh) {
                        $hh->total = $hh->ene + $hh->feb + $hh->mar + $hh->abr + $hh->may + $hh->jun + $hh->jul + $hh->ago + $hh->sep + $hh->oct + $hh->nov + $hh->dic;
                        $hh->avance = $hh->meta > 0 ? 100 * $hh->total / $hh->meta : 100;
                    }
                    $foot->total = $foot->ene + $foot->feb + $foot->mar + $foot->abr + $foot->may + $foot->jun + $foot->jul + $foot->ago + $foot->sep + $foot->oct + $foot->nov + $foot->dic;
                    $foot->avance = $foot->meta > 0 ? 100 * $foot->total / $foot->meta : 100;
                }
                return compact('head', 'base', 'foot');
            default:
                return [];
        }
    }

    public function panelControlEduacionNuevoindicador01Download($div, $anio, $provincia, $distrito, $gestion, $ugel) //se paso matriculageneralcontroller
    {
        if ($anio) {
            if ($div == 'tabla1') {
                $name = 'Avance_Matricula_provincia_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new AvanceMatricula1Export($div, $anio, $provincia, $distrito, $gestion, $ugel), $name);
            } else {
                $name = 'Avance_Matricula_distrito_' . date('Y-m-d') . '.xlsx';
                return Excel::download(new AvanceMatricula1Export($div, $anio, $provincia, $distrito, $gestion, $ugel), $name);
            }
        }
    }

    public function pagina()
    {
        // $actualizado = '';
        // $tipo_acceso = 0;

        // $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporMatriculaGeneralController::$FUENTE);

        // $strSiagie = strtotime($imp->fecha);
        // $actualizado = 'Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;

        // $anios = MatriculaGeneralRepositorio::anios();
        // $aniomax = MatriculaGeneralRepositorio::anioMax();
        // $provincia = UbigeoRepositorio::provincia25();

        // $pdf = Pdf::loadView('parametro.indicador.educacion.inicioEducacionIndicador01PDF', compact('anios', 'aniomax', 'provincia', 'actualizado'));
        // return $pdf->stream();

        $pdf = Browsershot::url(url()->current())
            ->setIncludePath('/usr/bin')
            ->save('pagina_actual.pdf');
        return $pdf;
    }

    public function panelControlEduacionNuevoindicador02()
    {
        $actualizado = '';
        $tipo_acceso = 0;

        $imp = ImportacionRepositorio::Max_yearPadronWeb(); //padron web
        $imp2 = ImportacionRepositorio::Max_yearSiagieMatricula(); //siagie
        $imp3 = ImportacionRepositorio::Max_porfuente(2); //nexus

        $importacion_id = $imp->first()->id;
        $matricula_id = $imp2->first()->mat;

        $info['se'] = MatriculaDetalleRepositorio::count_matriculados($imp2->first()->mat); //PadronWebRepositorio::count_institucioneducativa($imp->first()->id);
        $info['le'] = MatriculaDetalleRepositorio::count_matriculados3($imp2->first()->mat, 0, 0, 0, 0); //PadronWebRepositorio::count_localesescolares($imp->first()->id);
        $info['tm'] = MatriculaDetalleRepositorio::count_matriculados4($imp2->first()->mat, 0, 0, 0, 0);
        $info['do'] = MatriculaDetalleRepositorio::count_matriculados5($imp2->first()->mat, 0, 0, 0, 0);

        $info['dt0'] = MatriculaDetalleRepositorio::listar_estudiantesMatriculadosDeEducacionBasicaPorUgel($imp2);
        $info['dt1'] = PadronWebRepositorio::listar_totalServicosLocalesSecciones($imp);

        $strSiagie = strtotime($imp2->first()->fecha);
        $actualizado = 'Actualizado al ' . date('d', $strSiagie) . ' de ' . $this->mes[date('m', $strSiagie) - 1] . ' del ' . date('Y', $strSiagie);

        $anios = MatriculaRepositorio::matriculas_anio();
        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();

        return  view(
            'parametro.indicador.educacion.inicioEducacionIndicador02',
            compact(
                'importacion_id',
                'anios',
                'provincias',
                'distritos',
                'info',
                'imp',
                'actualizado',
                'tipo_acceso',
            )
        );
    }

    public function panelControlEduacionNuevoindicador04()
    {
        $actualizado = '';

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
        $importacion_id = $imp->id;
        $anioMax = $imp->anio;
        $actualizado = 'Fuente: Censo Educativo, Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;
        $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteimportacion_id', ImporCensoDocenteController::$FUENTE)->orderBy('anio', 'asc')->where('estado', 'PR')->get();
        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();

        return  view(
            'parametro.indicador.educacion.inicioEducacionIndicador04',
            compact(
                'importacion_id',
                'anioMax',
                'anios',
                'provincias',
                'distritos',
                'actualizado',
            )
        );
    }

    public function panelControlEduacionNuevoindicador04Head(Request $rq)
    {
        // $denonimador = ImporCensoDocenteRepositorio::_3ASTotalDocente($rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->ambito);
        // $numerador = ImporCensoDocenteRepositorio::_3ASTotalTitulado($rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->ambito);
        $base = ImporCensoDocenteRepositorio::_3ASReportes('head', $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, 0);
        $denonimador = $base['docentes'];
        $numerador = $base['titulados'];

        $v1 = isset($numerador->d) ? $numerador->d : 0;
        $v2 = isset($denonimador->d) ? $denonimador->d : 0;

        $valor1 = $v2 > 0 ? 100 * $v1 / $v2 : 0;
        $valor2 = $v2;
        $valor3 = $v1;
        $valor4 = $v2 - $v1;

        $valor1 = number_format($valor1, 1);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);
        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
    }

    public function panelControlEduacionNuevoindicador04Tabla(Request $rq)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($rq->div) {
            case 'dsanal0':
                $data = ImporCensoDocenteRepositorio::_3ASReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                foreach ($data['anios'] as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }
                $dx = $data['docentes'];
                $nx = $data['titulados'];

                $info['series'] = [];
                $alto = 0;
                $dx2[] = null;
                $dx3[] = null;
                $dx4[] = null;
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'],  'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'dsanal1':
                $query = ImporCensoDocenteRepositorio::_3ASReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d03) ? $query->d03 : 0;
                $v2 = isset($query->d02) ? $query->d02 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal2':
                $query = ImporCensoDocenteRepositorio::_3ASReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d02) ? $query->d02 : 0;
                $v2 = isset($query->d03) ? $query->d03 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal3':
                $query = ImporCensoDocenteRepositorio::_3ASReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $puntos[] = ['name' => 'Rural', 'y' => isset($query[0]->d) ? (int)$query[0]->d : 0];
                $puntos[] = ['name' => 'Urbano', 'y' => isset($query[1]->d) ? (int)$query[1]->d : 0];

                return response()->json(compact('puntos'));
            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_3ASReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->tt = $value->d01 + $value->d02 + $value->d03 + $value->d04;
                        $value->ttn = $value->d01 + $value->d02;
                        $value->ttc = $value->d03 + $value->d04;
                        $value->avance = $value->total ? 100 * $value->tt / $value->total : 0;

                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }
                    $foot->avance = $foot->total ? 100 * $foot->tt / $foot->total : 0;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador04Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_3ASReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    // return compact('base', 'foot');
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador04Table2excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador04Table2excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
        }
    }

    public function panelControlEduacionNuevoindicador04Export($div, $anio, $provincia, $distrito, $gestion)
    {
        switch ($div) {
            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_3ASReportes($div, $anio, $provincia, $distrito, $gestion, 0);

                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->tt = $value->d01 + $value->d02 + $value->d03 + $value->d04;
                        $value->ttn = $value->d01 + $value->d02;
                        $value->ttc = $value->d03 + $value->d04;
                        $value->avance = $value->total ? 100 * $value->tt / $value->total : 0;

                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }
                    $foot->avance = $foot->total ? 100 * $foot->tt / $foot->total : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }
            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_3ASReportes($div, $anio, $provincia, $distrito, $gestion, 0);

                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $value->avance = $value->td > 0 ? (100 * $value->tt / $value->td) : 0;
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    $foot->avance = $foot->td > 0 ? (100 * $foot->tt / $foot->td) : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }
        }
    }

    public function panelControlEduacionNuevoindicador04Download($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'DOCENTE CON TÍTULO EN EDUCACIÓN SECUNDARIA' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocenteSecundariaExport('ctabla1', $anio, $provincia, $distrito, $gestion), $name);
        }
    }

    public function panelControlEduacionNuevoindicador04Download2($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'NÚMERO DE PERSONAL DOCENTE CON TÍTULO PEDAGÓGICO EN EDUCACIÓN SECUNDARIA, SEGÚN UGEL' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocenteSecundariaExport('ctabla2', $anio, $provincia, $distrito, $gestion), $name);
        }
    }

    public function panelControlEduacionNuevoindicador05()
    {
        $actualizado = '';

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
        $importacion_id = $imp->id;
        $anioMax = $imp->anio;
        $actualizado = 'Fuente: Censo Educativo, Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;
        $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteimportacion_id', ImporCensoDocenteController::$FUENTE)->orderBy('anio', 'asc')->get();
        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();

        return  view(
            'parametro.indicador.educacion.inicioEducacionIndicador05',
            compact(
                'importacion_id',
                'anioMax',
                'anios',
                'provincias',
                'distritos',
                'actualizado',
            )
        );
    }

    public function panelControlEduacionNuevoindicador05Head(Request $rq)
    {
        $base = ImporCensoDocenteRepositorio::_3APReportes('head', $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, 0);
        $denonimador = $base['docentes'];
        $numerador = $base['titulados'];

        $v1 = isset($numerador->d) ? $numerador->d : 0;
        $v2 = isset($denonimador->d) ? $denonimador->d : 0;

        $valor1 = $v2 > 0 ? 100 * $v1 / $v2 : 0;
        $valor2 = $v2;
        $valor3 = $v1;
        $valor4 = $v2 - $v1;

        $valor1 = number_format($valor1, 1);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);
        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
    }

    public function panelControlEduacionNuevoindicador05Tabla(Request $rq)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($rq->div) {
            case 'dpanal0':
                $data = ImporCensoDocenteRepositorio::_3APReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                foreach ($data['anios'] as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $nx = $data['docentes'];
                $dx = $data['titulados'];

                $info['series'] = [];
                $alto = 0;
                $dx2[] = null;
                $dx3[] = null;
                $dx4[] = null;
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'],  'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'dsanal1':
                $query = ImporCensoDocenteRepositorio::_3APReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d03) ? $query->d03 : 0;
                $v2 = isset($query->d02) ? $query->d02 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal2':
                $query = ImporCensoDocenteRepositorio::_3APReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d02) ? $query->d02 : 0;
                $v2 = isset($query->d03) ? $query->d03 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal3':
                $query = ImporCensoDocenteRepositorio::_3APReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $puntos[] = ['name' => 'Rural', 'y' => isset($query[0]->d) ? (int)$query[0]->d : 0];
                $puntos[] = ['name' => 'Urbano', 'y' => isset($query[1]->d) ? (int)$query[1]->d : 0];

                return response()->json(compact('puntos'));

            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_3APReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->tt = $value->d01 + $value->d02 + $value->d03 + $value->d04;
                        $value->ttn = $value->d01 + $value->d02;
                        $value->ttc = $value->d03 + $value->d04;
                        $value->avance = $value->total ? 100 * $value->tt / $value->total : 0;

                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }
                    $foot->avance = $foot->total ? 100 * $foot->tt / $foot->total : 0;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }

            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_3APReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    $foot->avance = $foot->total ? 100 * $foot->tt / $foot->total : 0;
                    // return compact('base', 'foot');
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table2excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table2excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
        }
    }

    public function panelControlEduacionNuevoindicador05Export($div, $anio, $provincia, $distrito, $gestion)
    {
        switch ($div) {
            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_3APReportes($div, $anio, $provincia, $distrito, $gestion, 0);

                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->tt = $value->d01 + $value->d02 + $value->d03 + $value->d04;
                        $value->ttn = $value->d01 + $value->d02;
                        $value->ttc = $value->d03 + $value->d04;
                        $value->avance = $value->total ? 100 * $value->tt / $value->total : 0;

                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }
                    $foot->avance = $foot->total ? 100 * $foot->tt / $foot->total : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }

            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_3APReportes($div, $anio, $provincia, $distrito, $gestion, 0);

                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $value->avance = $value->td > 0 ? (100 * $value->tt / $value->td) : 0;
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    $foot->avance = $foot->td > 0 ? (100 * $foot->tt / $foot->td) : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }
        }
    }

    public function panelControlEduacionNuevoindicador05Download($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'DOCENTE CON TÍTULO EN EDUCACIÓN PRIMARIA' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocentePrimariaExport('ctabla1', $anio, $provincia, $distrito, $gestion), $name);
        }
    }

    public function panelControlEduacionNuevoindicador05Download2($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'NÚMERO DE PERSONAL DOCENTE CON TÍTULO PEDAGÓGICO EN EDUCACIÓN PRIMARIA, SEGÚN UGEL ' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocentePrimariaExport('ctabla2', $anio, $provincia, $distrito, $gestion), $name);
        }
    }

    public function panelControlEduacionNuevoindicador06()
    {
        $actualizado = '';

        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
        $importacion_id = $imp->id;
        $anioMax = $imp->anio;
        $actualizado = 'Fuente: Censo Educativo, Actualizado al ' . $imp->dia . ' de ' . $this->mes[$imp->mes - 1] . ' del ' . $imp->anio;
        $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteimportacion_id', ImporCensoDocenteController::$FUENTE)->orderBy('anio', 'asc')->get();
        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();

        return  view(
            'parametro.indicador.educacion.inicioEducacionIndicador06',
            compact(
                'importacion_id',
                'anioMax',
                'anios',
                'provincias',
                'distritos',
                'actualizado',
            )
        );
    }

    public function panelControlEduacionNuevoindicador06Head(Request $rq)
    {
        $base = ImporCensoDocenteRepositorio::_1AReportes('head', $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, 0);
        $denonimador = $base['docentes'];
        $numerador = $base['titulados'];

        $v1 = isset($numerador->d) ? $numerador->d : 0;
        $v2 = isset($denonimador->d) ? $denonimador->d : 0;

        $valor1 = $v2 > 0 ? 100 * $v1 / $v2 : 0;
        $valor2 = $v2;
        $valor3 = $v1;
        $valor4 = $v2 - $v1;

        $valor1 = number_format($valor1, 1);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);
        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
    }

    public function panelControlEduacionNuevoindicador06Tabla(Request $rq)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($rq->div) {
            case 'dianal0':
                $data = ImporCensoDocenteRepositorio::_1AReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                foreach ($data['anios'] as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }
                $dx = $data['docentes'];
                $nx = $data['titulados'];

                $info['series'] = [];
                $alto = 0;

                $dx2[] = null;
                $dx3[] = null;
                $dx4[] = null;
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denominador', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'dianal1':
                $query = ImporCensoDocenteRepositorio::_1AReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d03) ? $query->d03 : 0;
                $v2 = isset($query->d02) ? $query->d02 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dianal2':
                $query = ImporCensoDocenteRepositorio::_1AReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d02) ? $query->d02 : 0;
                $v2 = isset($query->d03) ? $query->d03 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dianal3':
                $query = ImporCensoDocenteRepositorio::_1AReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);

                $puntos[] = ['name' => 'Rural', 'y' => isset($query[0]->d) ? (int)$query[0]->d : 0];
                $puntos[] = ['name' => 'Urbano', 'y' => isset($query[1]->d) ? (int)$query[1]->d : 0];

                return response()->json(compact('puntos'));
            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_1AReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->avance = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->avance = $value->total > 0 ? (100 * $value->tt / $value->total) : 0;
                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                    }
                    $foot->avance = $foot->total > 0 ? (100 * $foot->tt / $foot->total) : 0;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador06Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador06Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }

            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_1AReportes($rq->div, $rq->anio, $rq->provincia, $rq->distrito, $rq->tipogestion, $rq->area);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    // return compact('base', 'foot');
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador06Table2excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador06Table2excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
        }
    }

    public function panelControlEduacionNuevoindicador06Export($div, $anio, $provincia, $distrito, $gestion)
    {
        switch ($div) {
            case 'ctabla1':
                $base = ImporCensoDocenteRepositorio::_1AReportes($div, $anio, $provincia, $distrito, $gestion, 0);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;
                    $foot->tt = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $value->avance = $value->total ? (100 * $value->tt / $value->total) : 0;
                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                        $foot->tt += $value->tt;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                    }

                    $foot->avance = $foot->total ? (100 * $foot->tt / $foot->total) : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }
            case 'ctabla2':
                $base = ImporCensoDocenteRepositorio::_1AReportes($div, $anio, $provincia, $distrito, $gestion, 0);
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->td = 0;
                    $foot->tt = 0;
                    $foot->tth = 0;
                    $foot->ttm = 0;
                    $foot->ttn = 0;
                    $foot->ttc = 0;
                    $foot->pub = 0;
                    $foot->pri = 0;
                    $foot->urb = 0;
                    $foot->rur = 0;

                    foreach ($base as $key => $value) {
                        $value->avance = $value->td > 0 ? (100 * $value->tt / $value->td) : 0;
                        $foot->td += $value->td;
                        $foot->tt += $value->tt;
                        $foot->tth += $value->tth;
                        $foot->ttm += $value->ttm;
                        $foot->ttn += $value->ttn;
                        $foot->ttc += $value->ttc;
                        $foot->pub += $value->pub;
                        $foot->pri += $value->pri;
                        $foot->urb += $value->urb;
                        $foot->rur += $value->rur;
                    }
                    $foot->avance = $foot->td > 0 ? (100 * $foot->tt / $foot->td) : 0;
                    return compact('base', 'foot');
                } else {
                    $base = [];
                    $foot = null;
                    return compact('base', 'foot');
                }

            default:
                return [];
        }
    }

    public function panelControlEduacionNuevoindicador06Download($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'DOCENTE CON TÍTULO EN EDUCACIÓN INICIAL' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocenteInicialExport('ctabla1', $anio, $provincia, $distrito, $gestion), $name);
        }
    }

    public function panelControlEduacionNuevoindicador06Download2($anio, $provincia, $distrito, $gestion)
    {
        if ($anio) {
            $name = 'NÚMERO DE PERSONAL DOCENTE CON TÍTULO PEDAGÓGICO EN EDUCACIÓN INICIAL, SEGÚN UGEL' . date('Y-m-d') . '.xlsx';
            return Excel::download(new CensoDocenteInicialExport('ctabla2', $anio, $provincia, $distrito, $gestion), $name);
        }
    }
}
