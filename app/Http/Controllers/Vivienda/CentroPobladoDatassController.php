<?php

namespace App\Http\Controllers\Vivienda;

use App\Http\Controllers\Controller;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Vivienda\CentroPobladoDatassRepositorio;
use Illuminate\Http\Request;
use App\Utilities\Utilitario;

use App\Repositories\Vivienda\DatassRepositorio;
class CentroPobladoDatassController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function saneamiento()
    {
        $ingresos = ImportacionRepositorio::Listar_deCentroPobladoDatass();
        $provincias = CentroPobladoDatassRepositorio::listar_provincia();
        //$provincias=CentroPobladoDatassRepositorio::listar_distrito(35);
        return view('vivienda.CentroPobladoDatass.Saneamiento', compact('ingresos', 'provincias'));
    }
    public function cargardistrito($provincia)
    {
        $distritos = CentroPobladoDatassRepositorio::listar_distrito($provincia);
        return response()->json(compact('distritos'));
    }
    public function datosSaneamiento(Request $request)
    {
        $dato['psa'] = CentroPobladoDatassRepositorio::poblacion_servicio_agua($request->fecha, $request->provincia, $request->distrito);
        $dato['pde'] = CentroPobladoDatassRepositorio::poblacion_disposicion_excretas($request->fecha, $request->provincia, $request->distrito);
        $dato['vsa'] = CentroPobladoDatassRepositorio::viviendas_servicio_agua($request->fecha, $request->provincia, $request->distrito);
        $dato['vde'] = CentroPobladoDatassRepositorio::viviendas_disposicion_excretas($request->fecha, $request->provincia, $request->distrito);
        return response()->json(compact('dato'));
    }
    public function DTsaneamiento($provincia, $distrito, $importacion_id)
    {
        $data = CentroPobladoDatassRepositorio::listar_porubigeo($provincia, $distrito, $importacion_id);

        return  datatables()::of($data)
            /*->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
               return '';
            })*/
            //->rawColumns(['action', 'icono', 'estado'])
            ->make(true);
    }
    public function infraestructurasanitaria()
    {
        //$ingresos = ImportacionRepositorio::Listar_soloYear();
        $ingresos = ImportacionRepositorio::Listar_deCentroPobladoDatass();
        $provincias = CentroPobladoDatassRepositorio::listar_provincia();
        //$provincias=CentroPobladoDatassRepositorio::listar_distrito(35);
        return view('vivienda.CentroPobladoDatass.InfraestructuraSanitaria', compact('ingresos', 'provincias'));
    }
    public function datoInfraestructuraSanitaria(Request $request)
    {
        $dato['csa'] = CentroPobladoDatassRepositorio::centropoplado_porServicioAgua($request->fecha, $request->provincia, $request->distrito);
        $dato['cde'] = CentroPobladoDatassRepositorio::centropoplado_porDisposicionExcretas($request->fecha, $request->provincia, $request->distrito);
        $dato['cts'] = CentroPobladoDatassRepositorio::centropoplado_porTipoServicioAgua($request->fecha, $request->provincia, $request->distrito);
        $dato['cad'] = CentroPobladoDatassRepositorio::centropoplado_porServicioAguaSINO($request->fecha, $request->provincia, $request->distrito);
        return response()->json(compact('dato'));
    }
    public function prestadorservicio()
    {
        // $datos = DatassRepositorio::datos_PorRegion_tipo_organizacion_comunal(516);
        // $importacion_id = ImportacionRepositorio::Max_porfuente('7');

        $importacion = ImportacionRepositorio::ImportacionMax_porfuente('7');

        $importacion_id = $importacion->id;

        $fechaVersion = Utilitario::fecha_formato_texto_completo($importacion->fechaActualizacion) ;

        return view('vivienda.CentroPobladoDatass.PrestadorYCalidadServicio', compact('importacion_id','fechaVersion'));
    }

    public function prestadorservicio0()
    {
        $ingresos = ImportacionRepositorio::Listar_deCentroPobladoDatass();
        $provincias = CentroPobladoDatassRepositorio::listar_provincia();
        //$provincias=CentroPobladoDatassRepositorio::listar_distrito(35);
         return view('vivienda.CentroPobladoDatass.PrestadorServicio', compact('ingresos', 'provincias'));
    }

    public function Grafico_PorRegion_segunColumna($columnaBD)
    {
        $datos =  DatassRepositorio::datos_PorRegion_segunColumna($columnaBD);

        $categoria1 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->valor_Si_Porcentual)]);
            $categoria_nombres[] = $lista->Periodo.'('.$lista->valor_Si.' de ' . ($lista->valor_Si + $lista->valor_No).')';
        }

        $name_Y1 = "Periodo";

        $titulo_grafico = '';

        switch ($columnaBD) {
            case 'cuota_familiar':
                $titulo_grafico = "Porcentaje de Cumplimiento con la Cuota Familiar";
                break;
            case 'servicio_agua_continuo':
                $titulo_grafico = "Servicio de Agua continuo las 24 Horas";
                break;
            case 'realiza_cloracion_agua':
                $titulo_grafico = "Porcentaje de Organizaciones Comunales que Realizan Cloración";
                break;
            default:
            $name_Y1 = "SI";
            break;
        }


        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];

        $titulo = $titulo_grafico ;
        $subTitulo = '';
        $titulo_y = 'Porcentaje';

        $nombreGraficoBarra = 'Grafico'.$columnaBD; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.BarraPorcentual',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }


    public function Grafico_PorRegion_CP_Periodos( )
    {
        $datos =  DatassRepositorio::datos_PorRegion_CP_Periodos();

        $categoria1 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->total)]);
            $categoria_nombres[] = $lista->Periodo;
        }

        $name_Y1 = "Periodo";

        $titulo_grafico = 'Total Centros Poblados';


        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];

        $titulo = $titulo_grafico ;
        $subTitulo = '';
        $titulo_y = 'Cantidad';

        $nombreGraficoBarra = 'Grafico_PorRegion_CP_Periodos'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }


    public function Grafico_tipo_organizacion_comunal( $importacion_id)
    {
        $datos =  DatassRepositorio::datos_PorRegion_tipo_organizacion_comunal($importacion_id);

        $categoria1 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->total_OrgComunal)]);
            $categoria_nombres[] = $lista->tipo_organizacion_comunal;
        }

        $name_Y1 = "Cantidad";

        $titulo_grafico = 'Número de Organizaciones Comunales';


        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];

        $titulo = $titulo_grafico ;
        $subTitulo = '';
        $titulo_y = 'Cantidad';

        $nombreGraficoBarra = 'Grafico_tipo_organizacion_comunal'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.BarraHorizontal',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_Asociados_organizacion_comunal( $importacion_id)
    {
        $datos =  DatassRepositorio::datos_PorRegion_tipo_organizacion_comunal($importacion_id);

        $categoria1 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->total_asociados)]);
            $categoria_nombres[] = $lista->tipo_organizacion_comunal;
        }

        $name_Y1 = "Cantidad";

        $titulo_grafico = 'Total Asociados Organizaciones Comunales';


        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];

        $titulo = $titulo_grafico ;
        $subTitulo = '';
        $titulo_y = 'Cantidad';

        $nombreGraficoBarra = 'Grafico_Asociados_organizacion_comunal'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.BarraHorizontal',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }



    public function datoPrestadorServicio(Request $request)
    {
        $dato['oc'] = CentroPobladoDatassRepositorio::centropoplado_porOrganizacionesComunales($request->fecha, $request->provincia, $request->distrito);
        $dato['ta'] = CentroPobladoDatassRepositorio::centropoplado_porTotalAsociados($request->fecha, $request->provincia, $request->distrito);
        $dato['cf'] = CentroPobladoDatassRepositorio::centropoplado_porCuotaFamiliar($request->fecha, $request->provincia, $request->distrito);
        return response()->json(compact('dato'));
    }
    public function calidadservicio()
    {
        $ingresos = ImportacionRepositorio::Listar_deCentroPobladoDatass();
        $provincias = CentroPobladoDatassRepositorio::listar_provincia();
        //$provincias=CentroPobladoDatassRepositorio::listar_distrito(35);
        return view('vivienda.CentroPobladoDatass.CalidadServicio', compact('ingresos', 'provincias'));
    }
    public function datoCalidadServicio(Request $request)
    {
        $dato['sac'] = CentroPobladoDatassRepositorio::centropoplado_porServicioAguaContinuo($request->fecha, $request->provincia, $request->distrito);
        $dato['rc'] = CentroPobladoDatassRepositorio::centropoplado_porRealizaCloracionAgua($request->fecha, $request->provincia, $request->distrito);
        return response()->json(compact('dato'));
    }
    public function listarDT()
    {
        $data = CentroPobladoDatassRepositorio::listar_ultimo();
        //return response()->json(compact('data'));
        return  datatables()::of($data)
            /*->editColumn('icono', '<i class="{{$icono}}"></i>')
            ->editColumn('estado', function ($data) {
               return '';
            })*/
            //->rawColumns(['action', 'icono', 'estado'])
            ->make(true);
    }
}
