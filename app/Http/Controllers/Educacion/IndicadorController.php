<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Sistema;
use App\Models\Educacion\Area;
use App\Models\Educacion\ImporCensoDocente;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Indicador;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\Materia;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\Clasificador;
use App\Models\Parametro\FuenteImportacion;
use App\Models\Parametro\Lengua;
use App\Models\Ubigeo;
use App\Models\Vivienda\EstadoConexion;
use App\Repositories\Educacion\CensoRepositorio;
use App\Repositories\Educacion\EceRepositorio;
use App\Repositories\Educacion\GradoRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\IndicadorRepositorio;
use App\Repositories\Educacion\MateriaRepositorio;
use App\Repositories\Educacion\MatriculaDetalleRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Repositories\Educacion\PadronWebRepositorio;
use App\Repositories\Educacion\PlazaRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Vivienda\CentroPobladoDatassRepositorio;
use App\Repositories\Vivienda\CentroPobladoRepositotio;
use App\Repositories\Vivienda\EmapacopsaRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndicadorController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

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
        $provincias = UbigeoRepositorio::buscar_provincia1();
        return response()->json($provincias);
    }
    public function cargardistritos($provincia)
    {
        $distritos = UbigeoRepositorio::buscar_distrito1($provincia);
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

    public function panelControlEduacionHead(Request $rq)
    {
        $valor1 = PadronWebRepositorio::count_institucioneducativa2($rq->get('impidpadweb'), $rq->get('provincia'), $rq->get('distrito'), $rq->get('tipogestion'), $rq->get('ambito'),);
        $valor2 = PadronWebRepositorio::count_localesescolares2($rq->get('impidpadweb'), $rq->get('provincia'), $rq->get('distrito'), $rq->get('tipogestion'), $rq->get('ambito'),);
        $valor3 = MatriculaDetalleRepositorio::count_matriculados2($rq->get('impidsiagie'), $rq->get('provincia'), $rq->get('distrito'), $rq->get('tipogestion'), $rq->get('ambito'),);
        $valor4 = PlazaRepositorio::count_docente2($rq->get('impidnexus'), $rq->get('provincia'), $rq->get('distrito'), $rq->get('tipogestion'), $rq->get('ambito'),);
        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);
        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
    }

    public function panelControlEduacionGraficas(Request $rq)
    {
        //#ef5350 ->rojito
        //#317eeb ->azulito
        switch ($rq->div) {
            case 'anal1':
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));

                return response()->json(compact('info'));

            case 'sganal1':
                $info['categoria'] = [2018, 2019, 2020, 2021, 2022, 2023];
                $info['series'] = [];
                $dx3 = [14000, 15000, 16000, 18000, 19000, 19000];
                $dx4 = [50, 60, 70, 80, 90, 100];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => '', 'color' => '#5eb9aa', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];

                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('info'));

            case 'anal2':
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));
            case 'sganal2':
                $data['cat'] = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL'];
                $data['dat'] = [80000, 100000, 120000, 140000, 180000, 200000, 210000];

                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('data'));

            case 'anal3':
                $info['categoria'] = [2018, 2019, 2020, 2021, 2022, 2023];
                $info['series'] = [];
                $dx3 = [14000, 15000, 16000, 18000, 19000, 19000];
                $dx4 = [50, 60, 70, 80, 90, 100];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => '', 'color' => '#5eb9aa', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('info'));

            case 'sganal3':
                $puntos = [
                    ["name" => "Hombre", "y" => 51, "yx" => 51],
                    ["name" => "Mujer", "y" => 40, "yx" => 40],
                ];
                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';
                return response()->json(compact('puntos', 'info'));

            case 'anal4':
                $info['categoria'] = [2018, 2019, 2020, 2021, 2022, 2023];
                $info['series'] = [];
                $dx3 = [14000, 15000, 16000, 18000, 19000, 19000];
                $dx4 = [50, 60, 70, 80, 90, 100];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => '', 'color' => '#5eb9aa', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];

                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';

                return response()->json(compact('info'));

            case 'sganal4':
                $puntos = [
                    ["name" => "Urbano", "y" => 71],
                    ["name" => "Rural", "y" => 29],
                ];
                $info['fuente'] = 'MINEDU';
                $info['fecha'] = '31/12/2022';
                return response()->json(compact('puntos', 'info'));

            case 'dtanal1':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                $info['indicador'] = round(100 * $nx->d / $dx->d, 1);
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));

            case 'dtanal2':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                $info['indicador'] = round(100 * $nx->d / $dx->d, 1);
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));

            case 'dtanal3':
                $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporCensoDocenteController::$FUENTE);
                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])->where('par_importacion.id', $imp->id);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();
                $info['indicador'] = round(100 * $nx->d / $dx->d, 1);
                $info['fuente'] = 'Censo Educativo - MINEDU';
                $info['fecha'] = date('d/m/Y', strtotime($imp->fechaActualizacion));
                return response()->json(compact('info'));

            default:
                return response()->json([]);
        }
    }


    public function panelControlEduacionNuevoindicador01()
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
            'parametro.indicador.educacion.inicioEducacionIndicador01',
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
        $anios = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteimportacion_id', ImporCensoDocenteController::$FUENTE)->orderBy('anio', 'asc')->get();
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
        $denonimador = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                        ) as d')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $rq->anio);
        if ($rq->provincia > 0) {
            $prov = Ubigeo::find($rq->provincia);
            $denonimador = $denonimador->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($rq->distrito > 0) {
            $dist = Ubigeo::find($rq->distrito);
            $denonimador = $denonimador->where('v1.codgeo', $dist->codigo);
        }
        if ($rq->tipogestion > 0) {
            if ($rq->tipogestion == 3) {
                $gestion = ['B3', 'B4'];
                $denonimador = $denonimador->whereIn('v1.ges_dep', $gestion);
            } else {
                $gestion = ['A1', 'A2', 'A3', 'A4'];
                $denonimador = $denonimador->whereIn('v1.ges_dep', $gestion);
            }
        }
        if ($rq->ambito > 0) {
            $area = Area::find($rq->ambito);
            $denonimador = $denonimador->where('v1.area_censo', $area->codigo);
        }
        $denonimador = $denonimador->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

        $numerador  = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])->where('par_importacion.id', $rq->anio);
        if ($rq->provincia > 0) {
            $prov = Ubigeo::find($rq->provincia);
            $numerador = $numerador->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($rq->distrito > 0) {
            $dist = Ubigeo::find($rq->distrito);
            $numerador = $numerador->where('v1.codgeo', $dist->codigo);
        }
        if ($rq->tipogestion > 0) {
            if ($rq->tipogestion == 3) {
                $gestion = ['B3', 'B4'];
                $numerador = $numerador->whereIn('v1.ges_dep', $gestion);
            } else {
                $gestion = ['A1', 'A2', 'A3', 'A4'];
                $numerador = $numerador->whereIn('v1.ges_dep', $gestion);
            }
        }
        if ($rq->ambito > 0) {
            $area = Area::find($rq->ambito);
            $numerador = $numerador->where('v1.area_censo', $area->codigo);
        }
        $numerador = $numerador->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();


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
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'dsanal1':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                                when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                            end) as d02'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                                end) as d03'),
                    DB::raw('sum(
                                    case v1.cuadro
                                        when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                                        when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                                    end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d03) ? $query->d03 : 0;
                $v2 = isset($query->d02) ? $query->d02 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal2':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                                when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                            end) as d02'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                                end) as d03'),
                    DB::raw('sum(
                                    case v1.cuadro
                                        when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                                        when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                                    end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d02) ? $query->d02 : 0;
                $v2 = isset($query->d03) ? $query->d03 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal3':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    'area_censo as area',
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d'),
                    /* DB::raw('sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                    ) as d') */
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio', 'area')->orderBy('area', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $puntos[] = ['name' => 'Rural', 'y' => isset($query[0]->d) ? (int)$query[0]->d : 0];
                $puntos[] = ['name' => 'Urbano', 'y' => isset($query[1]->d) ? (int)$query[1]->d : 0];

                return response()->json(compact('puntos'));
            case 'ctabla1':

                $query = Importacion::select(
                    DB::raw('v1.cod_mod as modular'),
                    DB::raw('case v1.area_censo when "1" then "Urbana" when "2" then "Rural" end as area'),
                    DB::raw('v2.total as total'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->Join(DB::raw('(
                    select
                        v1.cod_mod as modularx,
                        sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                            ) as total
                    from par_importacion
                    inner join edu_impor_censodocente as v1 on v1.importacion_id = par_importacion.id
                    where v1.nroced in ("3AS") and v1.cuadro in ("C305") and v1.tipdato in ("01", "05") and par_importacion.id = ' . $rq->anio . '
                    group by modularx ) as v2 '), 'v2.modularx', '=', 'v1.cod_mod')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereNotIn('v1.tipdato', ['01', '02', '03', '04', '05', '06', '42'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('modular', 'area', 'total')->get();
                //$query = $query->groupBy('modular', 'area')->get();

                if (count($query) > 0) {
                    $base = $query;
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;

                    /* $iiee_total = Importacion::select(
                    DB::raw('v1.cod_mod as modular'),
                    DB::raw('sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                    ) as tt')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])
                    ->where('par_importacion.id', $rq->anio);
                $iiee_total = $iiee_total->groupBy('modular')->get(); */

                    //return response()->json(compact('iiee_total'));

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        /* foreach ($iiee_total as $key => $value2) {
                        if ($value2->modular == $value->modular) {
                            $value->total = $value2->tt;
                            $foot->total += $value2->tt;
                            break;
                        }
                    } */
                        $foot->total += $value->total;
                    }
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador04Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
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
        $denonimador = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                    IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                        ) as d')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $rq->anio);
        if ($rq->provincia > 0) {
            $prov = Ubigeo::find($rq->provincia);
            $denonimador = $denonimador->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($rq->distrito > 0) {
            $dist = Ubigeo::find($rq->distrito);
            $denonimador = $denonimador->where('v1.codgeo', $dist->codigo);
        }
        if ($rq->tipogestion > 0) {
            if ($rq->tipogestion == 3) {
                $gestion = ['B3', 'B4'];
                $denonimador = $denonimador->whereIn('v1.ges_dep', $gestion);
            } else {
                $gestion = ['A1', 'A2', 'A3', 'A4'];
                $denonimador = $denonimador->whereIn('v1.ges_dep', $gestion);
            }
        }
        if ($rq->ambito > 0) {
            $area = Area::find($rq->ambito);
            $denonimador = $denonimador->where('v1.area_censo', $area->codigo);
        }
        $denonimador = $denonimador->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

        $numerador  = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $rq->anio);
        if ($rq->provincia > 0) {
            $prov = Ubigeo::find($rq->provincia);
            $numerador = $numerador->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($rq->distrito > 0) {
            $dist = Ubigeo::find($rq->distrito);
            $numerador = $numerador->where('v1.codgeo', $dist->codigo);
        }
        if ($rq->tipogestion > 0) {
            if ($rq->tipogestion == 3) {
                $gestion = ['B3', 'B4'];
                $numerador = $numerador->whereIn('v1.ges_dep', $gestion);
            } else {
                $gestion = ['A1', 'A2', 'A3', 'A4'];
                $numerador = $numerador->whereIn('v1.ges_dep', $gestion);
            }
        }
        if ($rq->ambito > 0) {
            $area = Area::find($rq->ambito);
            $numerador = $numerador->where('v1.area_censo', $area->codigo);
        }
        $numerador = $numerador->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

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
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $dx = $dx->where('v1.area_censo', $area->codigo);
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                if ($rq->ambito > 0) {
                    $area = Area::find($rq->ambito);
                    $nx = $nx->where('v1.area_censo', $area->codigo);
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'dsanal1':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d03) ? $query->d03 : 0;
                $v2 = isset($query->d02) ? $query->d02 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal2':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d02) ? $query->d02 : 0;
                $v2 = isset($query->d03) ? $query->d03 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dsanal3':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    'area_censo as area',
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d'),
                    /* DB::raw('sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                    ) as d') */
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio', 'area')->orderBy('area', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $puntos[] = ['name' => 'Rural', 'y' => isset($query[0]->d) ? (int)$query[0]->d : 0];
                $puntos[] = ['name' => 'Urbano', 'y' => isset($query[1]->d) ? (int)$query[1]->d : 0];

                return response()->json(compact('puntos'));

            case 'ctabla1':

                $query = Importacion::select(
                    DB::raw('v1.cod_mod as modular'),
                    DB::raw('case v1.area_censo when "1" then "Urbana" when "2" then "Rural" end as area'),
                    DB::raw('v2.total as total'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->Join(DB::raw('(
                    select
                        v1.cod_mod as modularx,
                        sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                            ) as total
                    from par_importacion
                    inner join edu_impor_censodocente as v1 on v1.importacion_id = par_importacion.id
                    where v1.nroced in ("3AP") and v1.cuadro in ("C305") and v1.tipdato in ("01", "05") and par_importacion.id = ' . $rq->anio . '
                    group by modularx ) as v2 '), 'v2.modularx', '=', 'v1.cod_mod')
                    ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['02', '04', '07', '08'])->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('modular', 'area', 'total')->get();

                if (count($query) > 0) {
                    $base = $query;
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;

                    /* $iiee_total = Importacion::select(
                        DB::raw('v1.cod_mod as modular'),
                        DB::raw('sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15,0))
                                    ) as tt')
                    )
                        ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                        ->whereIn('v1.nroced', ['3AP'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $rq->anio);

                    $iiee_total = $iiee_total->groupBy('modular')->get();

                    return response()->json(compact('iiee_total')); */

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        $foot->total += $value->total;
                    }
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
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
        $denonimador = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                    IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11,0))
                        ) as d')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])->where('par_importacion.id', $rq->anio);
        if ($rq->provincia > 0) {
            $prov = Ubigeo::find($rq->provincia);
            $denonimador = $denonimador->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($rq->distrito > 0) {
            $dist = Ubigeo::find($rq->distrito);
            $denonimador = $denonimador->where('v1.codgeo', $dist->codigo);
        }
        if ($rq->tipogestion > 0) {
            if ($rq->tipogestion == 3) {
                $gestion = ['B3', 'B4'];
                $denonimador = $denonimador->whereIn('v1.ges_dep', $gestion);
            } else {
                $gestion = ['A1', 'A2', 'A3', 'A4'];
                $denonimador = $denonimador->whereIn('v1.ges_dep', $gestion);
            }
        }
        if ($rq->ambito > 0) {
            $area = Area::find($rq->ambito);
            $denonimador = $denonimador->where('v1.area_censo', $area->codigo);
        }
        $denonimador = $denonimador->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

        $numerador  = Importacion::select(
            DB::raw('year(par_importacion.fechaActualizacion) as anio'),
            DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d')
        )
            ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
            ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato', ['01', '03', '07', '08'])->where('par_importacion.id', $rq->anio);
        if ($rq->provincia > 0) {
            $prov = Ubigeo::find($rq->provincia);
            $numerador = $numerador->where('v1.codgeo', 'like', $prov->codigo . '%');
        }
        if ($rq->distrito > 0) {
            $dist = Ubigeo::find($rq->distrito);
            $numerador = $numerador->where('v1.codgeo', $dist->codigo);
        }
        if ($rq->tipogestion > 0) {
            if ($rq->tipogestion == 3) {
                $gestion = ['B3', 'B4'];
                $numerador = $numerador->whereIn('v1.ges_dep', $gestion);
            } else {
                $gestion = ['A1', 'A2', 'A3', 'A4'];
                $numerador = $numerador->whereIn('v1.ges_dep', $gestion);
            }
        }
        if ($rq->ambito > 0) {
            $area = Area::find($rq->ambito);
            $numerador = $numerador->where('v1.area_censo', $area->codigo);
        }
        $numerador = $numerador->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

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
                $imps = Importacion::select('id', DB::raw('year(fechaActualizacion) as anio'))->where('fuenteImportacion_id', 32)->where('estado', 'PR')->orderBy('anio')->get();
                foreach ($imps as $key => $value) {
                    $info['categoria'][] = $value->anio;
                }

                $dx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13),
                        IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11,0))
                                ) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $dx = $dx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $dx = $dx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $dx = $dx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $dx = $dx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $nx = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                                end) as d')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato',['01', '03', '07', '08']);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $nx = $nx->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $nx = $nx->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $nx = $nx->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $nx = $nx->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $info['series'] = [];
                $alto = 0;
                foreach ($imps as $key => $value) {
                    $dx2[] = null;
                    $dx3[] = null;
                    $dx4[] = null;
                }
                foreach ($nx as $key => $value) {
                    $dx2[$key] = (int)$value->d;
                    $dx3[$key] = (int)$dx[$key]->d;
                    $dx4[$key] = round(100 * (int)$value->d / (int)$dx[$key]->d, 1);
                    $alto = (int)$value->d > $alto ? (int)$value->d : $alto;
                    $alto = (int)$dx[$key]->d > $alto ? (int)$dx[$key]->d : $alto;
                }
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Numerador', 'color' => '#5eb9aa', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Denomidaor', 'color' => '#f5bd22', 'data' => $dx3];
                $info['series'][] = ['type' => 'spline', 'yAxis' => 1, 'name' => '%Indicador', 'tooltip' => ['valueSuffix' => ' %'], 'color' => '#ef5350', 'data' => $dx4];
                $info['maxbar'] = $alto;
                return response()->json(compact('info'));
            case 'dianal1':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                                when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                            end) as d02'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                                end) as d03'),
                    DB::raw('sum(
                                    case v1.cuadro
                                        when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                                        when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                                    end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato',['01', '03', '07', '08'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d03) ? $query->d03 : 0;
                $v2 = isset($query->d02) ? $query->d02 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Hombre', 'y' => $v1];
                $puntos[] = ['name' => 'Mujer', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dianal2':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                            case v1.cuadro
                                when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                                when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                            end) as d02'),
                    DB::raw('sum(
                                case v1.cuadro
                                    when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                                    when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                                end) as d03'),
                    DB::raw('sum(
                                    case v1.cuadro
                                        when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                                        when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                                    end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato',['01', '03', '07', '08'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio')->orderBy('anio', 'asc')->orderBy('v1.tipdato', 'desc')->first();

                $v1 = isset($query->d01) ? $query->d01 : 0;
                $v1 += isset($query->d02) ? $query->d02 : 0;
                $v2 = isset($query->d03) ? $query->d03 : 0;
                $v2 += isset($query->d04) ? $query->d04 : 0;
                $puntos[] = ['name' => 'Nombrados', 'y' => $v1];
                $puntos[] = ['name' => 'Contratados', 'y' => $v2];

                return response()->json(compact('puntos'));

            case 'dianal3':
                $query = Importacion::select(
                    DB::raw('year(par_importacion.fechaActualizacion) as anio'),
                    'area_censo as area',
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01+v1.d02+v1.d03+v1.d04,0)
                        end) as d'),
                    /* DB::raw('sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                    ) as d') */
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato',['01', '03', '07', '08'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('anio', 'area')->orderBy('area', 'asc')->orderBy('v1.tipdato', 'desc')->get();

                $puntos[] = ['name' => 'Rural', 'y' => isset($query[0]->d) ? (int)$query[0]->d : 0];
                $puntos[] = ['name' => 'Urbano', 'y' => isset($query[1]->d) ? (int)$query[1]->d : 0];

                return response()->json(compact('puntos'));
            case 'ctabla1':

                $query = Importacion::select(
                    DB::raw('v1.cod_mod as modular'),
                    DB::raw('case v1.area_censo when "1" then "Urbana" when "2" then "Rural" end as area'),
                    DB::raw('v2.total as total'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d01,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d01,0)
                        end) as d01'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d02,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d02,0)
                        end) as d02'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d03,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d03,0)
                        end) as d03'),
                    DB::raw('sum(
                        case v1.cuadro
                            when "C309" then if(year(par_importacion.fechaActualizacion)=2018,v1.d04,0)
                            when "C310" then if(year(par_importacion.fechaActualizacion)!=2018,v1.d04,0)
                        end) as d04'),
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->Join(DB::raw('(
                    select
                        v1.cod_mod as modularx,
                        sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                            ) as total
                    from par_importacion
                    inner join edu_impor_censodocente as v1 on v1.importacion_id = par_importacion.id
                    where v1.nroced in ("1A") and v1.cuadro in ("C305") and v1.tipdato in ("01", "05") and par_importacion.id = ' . $rq->anio . '
                    group by modularx ) as v2 '), 'v2.modularx', '=', 'v1.cod_mod')
                    ->whereIn('v1.nroced', ['1A'])->whereIn('v1.cuadro', ['C309', 'C310'])->whereIn('v1.tipdato',['01', '03', '07', '08'])
                    ->where('par_importacion.id', $rq->anio);
                if ($rq->provincia > 0) {
                    $prov = Ubigeo::find($rq->provincia);
                    $query = $query->where('v1.codgeo', 'like', $prov->codigo . '%');
                }
                if ($rq->distrito > 0) {
                    $dist = Ubigeo::find($rq->distrito);
                    $query = $query->where('v1.codgeo', $dist->codigo);
                }
                if ($rq->tipogestion > 0) {
                    if ($rq->tipogestion == 3) {
                        $gestion = ['B3', 'B4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    } else {
                        $gestion = ['A1', 'A2', 'A3', 'A4'];
                        $query = $query->whereIn('v1.ges_dep', $gestion);
                    }
                }
                $query = $query->groupBy('modular', 'area', 'total')->get();
                //$query = $query->groupBy('modular', 'area')->get();

                if (count($query) > 0) {
                    $base = $query;
                    $foot = clone $base[0];
                    $foot->d01 = 0;
                    $foot->d02 = 0;
                    $foot->d03 = 0;
                    $foot->d04 = 0;
                    $foot->total = 0;

                    /* $iiee_total = Importacion::select(
                    DB::raw('v1.cod_mod as modular'),
                    DB::raw('sum(
                            IF(year(par_importacion.fechaActualizacion)=2018 or year(par_importacion.fechaActualizacion)=2019,(v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25),
                            IF(year(par_importacion.fechaActualizacion)>2019,v1.d01+v1.d02+v1.d03+v1.d04+v1.d05+v1.d06+v1.d07+v1.d08+v1.d09+v1.d10+v1.d11+v1.d12+v1.d13+v1.d14+v1.d15+v1.d16+v1.d17+v1.d18+v1.d19+v1.d20+v1.d21+v1.d22+v1.d23+v1.d24+v1.d25+v1.d26,0))
                                    ) as tt')
                )
                    ->join('edu_impor_censodocente as v1', 'v1.importacion_id', '=', 'par_importacion.id')
                    ->whereIn('v1.nroced', ['3AS'])->whereIn('v1.cuadro', ['C305'])->whereIn('v1.tipdato', ['01', '05'])
                    ->where('par_importacion.id', $rq->anio);
                $iiee_total = $iiee_total->groupBy('modular')->get(); */

                    //return response()->json(compact('iiee_total'));

                    foreach ($base as $key => $value) {
                        $iiee = InstitucionEducativa::where('codModular', $value->modular)->first();
                        $value->iiee = $iiee ? $iiee->nombreInstEduc : '';
                        $foot->d01 += $value->d01;
                        $foot->d02 += $value->d02;
                        $foot->d03 += $value->d03;
                        $foot->d04 += $value->d04;
                        /* foreach ($iiee_total as $key => $value2) {
                        if ($value2->modular == $value->modular) {
                            $value->total = $value2->tt;
                            $foot->total += $value2->tt;
                            break;
                        }
                    } */
                        $foot->total += $value->total;
                    }
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador04Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                } else {
                    $base = [];
                    $foot = null;
                    $excel = view('parametro.indicador.educacion.inicioEducacionIndicador05Table1excel', compact('base', 'foot'))->render();
                    return response()->json(compact('excel'));
                }
            default:
                return response()->json([]);
        }
    }

}
