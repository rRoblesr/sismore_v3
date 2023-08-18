<?php

namespace App\Http\Controllers\Vivienda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Vivienda\Datass;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Vivienda\DatassRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DatassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        return view('Vivienda.Datass.Importar', compact('mensaje'));
    }

    public function guardar(Request $request)
    {
        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);

        $i = 0;
        $cadena = '';

        try {
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    if (++$i > 1) break;
                    $cadena =  $cadena .
                        $row['departamento'] .
                        $row['provincia'] .
                        $row['distrito'] .
                        $row['ubigeo_cp'] .
                        $row['centro_poblado']                        ./*  */
                        $row['zona_utm_wgs84']                        .
                        $row['coordenadas_este']                        .
                        $row['coordenadas_norte']                        .
                        $row['altitud']                        .
                        $row['total_viviendas'] ./* 10 */
                        $row['viviendas_habitadas'] .
                        $row['total_poblacion']                        .
                        $row['lengua_predominante'] .
                        $row['energia_electrica'] .
                        $row['internet']                        ./*  */
                        $row['establecimiento_salud'] .
                        $row['pronoei'] .
                        $row['primaria'] .
                        $row['secundaria']                        .
                        $row['establecimiento_salud_agua']                        ./* 20 */
                        $row['pronoei_agua']                        .
                        $row['primaria_agua']                        .
                        $row['secundaria_agua']                        .
                        $row['funciona_establecimiento_salud']                        .
                        $row['funciona_pronoei']                        ./*  */
                        $row['funciona_primaria']                        .
                        $row['funciona_secundaria']                        .
                        $row['establecimiento_salud_banios']                        .
                        $row['pronoei_banios']                        .
                        $row['primaria_banios']                        ./* 30 */
                        $row['secundaria_banios']                        .
                        $row['sistema_agua']                        .
                        $row['viviendas_conexion']                        .
                        $row['poblacion_servicio_agua']                        .
                        $row['sistema_disposicion_excretas'] ./*  */
                        $row['codigo_ps'] .
                        $row['prestador_servicio'] .
                        $row['tipo_organizacion_comunal'] .
                        $row['total_asociados'] .
                        $row['cuota_familiar'] ./* 40 */
                        $row['tipo_cobro'] .
                        $row['codigo_fuente'] .
                        $row['fuente_principal'] .
                        $row['tipo_fuente'] .
                        $row['servicio_agua_continuo'] ./*  */
                        $row['sistema_cloracion'] .
                        $row['realiza_cloracion'] .
                        $row['tipo_sistema_agua'] .
                        $row['tipo_sistema_cloracion'];/* 49 */
                        //$row['estado_infraestructura']
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";
            return view('Vivienda.Datass.Importar', compact('mensaje'));
        }

        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => 7, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    // echo $row['cen_edu'].'<br>';
                    $Datass = Datass::Create([
                        'importacion_id' => $importacion->id,

                        'departamento' => $row['departamento'],
                        'provincia' => $row['provincia'],
                        'distrito' => $row['distrito'],
                        'ubigeo_cp' => $row['ubigeo_cp'],
                        'centro_poblado' => $row['centro_poblado'],/*  */

                        'zona_utm_wgs84' => $row['zona_utm_wgs84'],
                        'coordenadas_este' => $row['coordenadas_este'],
                        'coordenadas_norte' => $row['coordenadas_norte'],
                        'altitud' => $row['altitud'],
                        'total_viviendas' => $row['total_viviendas'],/*  */

                        'viviendas_habitadas' => $row['viviendas_habitadas'],
                        'total_poblacion' => $row['total_poblacion'],
                        'predomina_primera_lengua' => $row['lengua_predominante'],
                        'tiene_energia_electrica' => $row['energia_electrica'],
                        'tiene_internet' => $row['internet'],/*  */

                        'tiene_establecimiento_salud' => $row['establecimiento_salud'],
                        'pronoei' => $row['pronoei'],
                        'primaria' => $row['primaria'],
                        'secundaria' => $row['secundaria'],
                        'establecimiento_salud_agua' => $row['establecimiento_salud_agua'],/*  */

                        'pronoei_agua' => $row['pronoei_agua'],
                        'primaria_agua' => $row['primaria_agua'],
                        'secundaria_agua' => $row['secundaria_agua'],
                        'funciona_establecimiento_salud' => $row['funciona_establecimiento_salud'],
                        'funciona_pronoei' => $row['funciona_pronoei'],/*  */

                        'funciona_primaria' => $row['funciona_primaria'],
                        'funciona_secundaria' => $row['funciona_secundaria'],
                        'establecimiento_salud_banios' => $row['establecimiento_salud_banios'],
                        'pronoei_banios' => $row['pronoei_banios'],
                        'primaria_banios' => $row['primaria_banios'],/*  */

                        'secundaria_banios' => $row['secundaria_banios'],
                        'sistema_agua' => $row['sistema_agua'],
                        'viviendas_conexion' => $row['viviendas_conexion'],
                        'poblacion_servicio_agua' => $row['poblacion_servicio_agua'],
                        'sistema_disposicion_excretas' => $row['sistema_disposicion_excretas'],/*  */

                        'prestador_codigo' => $row['codigo_ps'],
                        'prestador_de_servicio_agua' => $row['prestador_servicio'],
                        'tipo_organizacion_comunal' => $row['tipo_organizacion_comunal'],
                        'total_asociados' => $row['total_asociados'],
                        'cuota_familiar' => $row['cuota_familiar'],/*  */

                        'tipo_cobro' => $row['tipo_cobro'],
                        'codigo_fuente' => $row['codigo_fuente'],
                        'fuente_principal' => $row['fuente_principal'],
                        'tipo_fuente' => $row['tipo_fuente'],
                        'servicio_agua_continuo' => $row['servicio_agua_continuo'],/*  */

                        'sistema_cloracion' => $row['sistema_cloracion'],
                        'realiza_cloracion_agua' => $row['realiza_cloracion'],
                        'tipo_sistema_agua' => $row['tipo_sistema_agua'],
                        'tipo_sistema_cloracion' => $row['tipo_sistema_cloracion'],
                        //'estado_infraestructura' => $row['estado_infraestructura'],
                    ]);
                }
            }
        } catch (Exception $e) {
            //$importacion->delete(); // elimina la importacion creada
            $importacion->estado = 'EL';
            $importacion->save();
            $mensaje = "Error en la carga de datos, comuniquese con el administrador del sistema";
            return view('Vivienda.Datass.Importar', compact('mensaje'));
        }

        //return 'ok';
        return redirect()->route('Datass.Datass_Lista', $importacion->id);
    }

    public function ListaImportada($importacion_id)
    {
        return view('Vivienda.Datass.ListaImportada', compact('importacion_id'));
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $Lista = DatassRepositorio::Listar_Por_Importacion_id($importacion_id);

        return  datatables()->of($Lista)->toJson();;
    }

    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);

        return  view('Vivienda.Datass.Aprobar', compact('importacion_id', 'importacion'));
    }

    public function procesar($importacion_id)
    {
        $procesar = DB::select('call viv_pa_procesarDatass(?,?)', [$importacion_id, auth()->user()->id]);
        return view('correcto');
    }

    public function Grafico_IndicadorRegional_Periodos($indicador)
    {
        $datos = DatassRepositorio::datos_PorDepartamento_periodos($indicador);

        $categoria1 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->INDICADOR_SI_porcentaje)]);
            $categoria_nombres[] = $lista->Periodo . '(' . $lista->INDICADOR_SI . ' de ' . ($lista->INDICADOR_SI + $lista->INDICADOR_NO) . ')';
        }

        $name_Y1 = "Periodo";

        $titulo_grafico = '';

        switch ($indicador) {
            case 1:
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Agua - Region Ucayali";
                break;
            case 2:
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Cloracion";
                break;
            case 3:
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Agua por Red Pública";
                break;
            case 4:
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Alcantarillado u Otras Formas de Disposición Sanitaria de Excretas";
                break;
            case 5:
                $titulo_grafico = "Porcentaje de Hogares en Ambito Rural que Consume Agua Segura (Clorada)";
                break;
            default:
                $name_Y1 = "SI";
                break;
        }


        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];

        $titulo = $titulo_grafico;
        $subTitulo = '';
        $titulo_y = 'Porcentaje';

        $nombreGraficoBarra = 'Grafico_IndicadorRegional_Periodos'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.BarraPorcentual',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_IndicadorRegional($indicador, $importacion_id)
    {
        //$importacion_id = ImportacionRepositorio::Max_porfuente('7');
        $datos = DatassRepositorio::datos_PorDepartamento($importacion_id, $indicador);

        $suma1 = 0;
        $suma2 = 0;
        $puntos = [];

        $name_Y1 = "";
        $name_Y2 = "";
        $titulo_grafico = '';

        foreach ($datos as $key => $item) {
            $suma1 += $item->INDICADOR_SI_porcentaje;;
            $suma2 += $item->INDICADOR_NO_porcentaje;;
        }

        // PONER EN UN METODO PARA REUTILZAR ABAJO
        switch ($indicador) {
            case 1:
                $name_Y1 = "Con Sistema de Agua";
                $name_Y2 = "Sin Sistema de Agua";
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Agua";
                break;
            case 2:
                $name_Y1 = "Con Sistema de Cloracion";
                $name_Y2 = "Sin Sistema de Cloracion";
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Cloracion";
                break;
            case 3:
                $name_Y1 = "Con Cobertura de Agua";
                $name_Y2 = "Sin Cobertura de Agua";
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Agua por Red Pública";
                break;
            case 4:
                $name_Y1 = "Con Cobert. de Alcantar.";
                $name_Y2 = "Sin Cobert. de Alcantar.";
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Alcantarillado u Otras Formas de Disposición Sanitaria de Excretas";
                break;
            case 5:
                $name_Y1 = "Consume Agua Segura";
                $name_Y2 = "No Consume Agua Segura";
                $titulo_grafico = "Porcentaje de Hogares en Ambito Rural que Consume Agua Segura (Clorada)";
                break;
            default:
                $name_Y1 = "SI";
                break;
        }

        $puntos[] = ['name' => $name_Y1, 'y' => floatval($suma1)];
        $puntos[] = ['name' => $name_Y2, 'y' => floatval($suma2)];

        $contenedor = 'Grafico_IndicadorRegional'; //nombre del contenedor para el grafico
        $subtitulo_grafico = 'REGION UCAYALI';

        return view(
            'graficos.Circular',
            ["dataCircular" => json_encode($puntos)],
            compact('contenedor', 'titulo_grafico', 'subtitulo_grafico')
        );
    }


    public function Grafico_IndicadorProvincial($indicador, $importacion_id)
    {
        $datos = DatassRepositorio::datos_PorProvincia($importacion_id, $indicador);

        $categoria1 = [];
        $categoria_nombres = [];
        $totalCP = 0;


        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->total)]);
            $categoria_nombres[] = $lista->Provincia;
            $totalCP = $totalCP  + $lista->total;
        }

        $name_Y1 = "Provincia";

        $titulo_grafico = 'Grafico_IndicadorRegional';

        switch ($indicador) {
            case 1:
            case 2:
                $titulo_grafico = "Centros Poblados Rurales por Provincias";
                $name_Y1 = "Total Centros Poblados(" . $totalCP . ")";
                break;
            case 3:
            case 4:
            case 5:
                $titulo_grafico = "Hogares en el Ambito Rural";
                break;
            default:
                $name_Y1 = "SI";
                break;
        }

        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];

        $titulo = $titulo_grafico;
        $subTitulo = '';
        $titulo_y = 'Cantidad';

        $nombreGraficoBarra = 'Grafico_IndicadorProvincial'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }


    public function Grafico_IndicadorProvincial2($indicador, $importacion_id)
    {
        $datos = DatassRepositorio::datos_PorProvincia($importacion_id, $indicador);

        $categoria1 = [];
        $categoria2 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->INDICADOR_SI_porcentaje)]);
            $categoria2 = array_merge($categoria2, [intval($lista->INDICADOR_NO_porcentaje)]);
            $categoria_nombres[] = $lista->Provincia;
        }

        $name_Y1 = "si";
        $name_Y2 = "no";
        $titulo_grafico = 'Grafico_IndicadorRegional';

        switch ($indicador) {
            case 1:
                $name_Y1 = "Con Sistema de Agua";
                $name_Y2 = "Sin Sistema de Agua";
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Agua";
                break;
            case 2:
                $name_Y1 = "Con Sistema de Cloracion";
                $name_Y2 = "Sin Sistema de Cloracion";
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Cloracion";
                break;
            case 3:
                $name_Y1 = "Con Cobertura de Agua";
                $name_Y2 = "Sin Cobertura de Agua";
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Agua por Red Pública";
                break;
            case 4:
                $name_Y1 = "Con Cobert. de Alcantar.";
                $name_Y2 = "Sin Cobert. de Alcantar.";
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Alcantarillado u Otras Formas de Disposición Sanitaria de Excretas";
                break;
            case 5:
                $name_Y1 = "Consume Agua Segura";
                $name_Y2 = "No Consume Agua Segura";
                $titulo_grafico = "Porcentaje de Hogares en Ambito Rural que Consume Agua Segura (Clorada)";
                break;
            default:
                $name_Y1 = "SI";
                break;
        }

        $puntos[] = ['name' => $name_Y1, 'data' =>  $categoria1];
        $puntos[] = ['name' => $name_Y2, 'data' => $categoria2];

        $titulo = $titulo_grafico;
        $subTitulo = 'PORCENTAJE POR PROVINCIAS';
        $titulo_y = 'Porcentaje';

        $nombreGraficoBarra = 'Grafico_IndicadorProvincial'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.BarraPorcentual',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }


    public function Grafico_IndicadorProvincial_masDistrital($indicador, $importacion_id)
    {
        $datos = DatassRepositorio::datos_PorProvincia($importacion_id, $indicador);

        $suma1 = 0;
        $suma2 = 0;
        $puntos = [];

        $puntosAtalaya = [];
        $puntosCrnlPortillo = [];
        $puntosPadreAbad = [];
        $puntosPurus = [];

        $name_Y1 = "";
        $name_Y2 = "";

        foreach ($datos as $key => $item) {
            $puntos[] = ['name' => ($item->Provincia) . ' (' . ($item->INDICADOR_SI) . ')', 'y' => floatval($item->INDICADOR_SI_porcentaje), 'drilldown' => $item->Provincia];
        }

        $datosAtalaya = DatassRepositorio::datos_Distrito_PorProvincia($importacion_id, $indicador, 'ATALAYA');

        foreach ($datosAtalaya as $key => $item) {
            $puntosAtalaya[] = ['name' => ($item->Distrito) . ' (' . ($item->INDICADOR_SI) . ' de ' . ($item->INDICADOR_SI + $item->INDICADOR_NO) . ')', 'y' => floatval($item->INDICADOR_SI_porcentaje)];
        }

        $datosCrnlPortillo = DatassRepositorio::datos_Distrito_PorProvincia($importacion_id, $indicador, 'CORONEL PORTILLO');

        foreach ($datosCrnlPortillo as $key => $item) {
            $puntosCrnlPortillo[] = ['name' => ($item->Distrito) . ' (' . ($item->INDICADOR_SI) . ' de ' . ($item->INDICADOR_SI + $item->INDICADOR_NO) . ')', 'y' => floatval($item->INDICADOR_SI_porcentaje)];
        }

        $datosPadreAbad = DatassRepositorio::datos_Distrito_PorProvincia($importacion_id, $indicador, 'PADRE ABAD');

        foreach ($datosPadreAbad as $key => $item) {
            $puntosPadreAbad[] = ['name' => ($item->Distrito) . ' (' . ($item->INDICADOR_SI) . ' de ' . ($item->INDICADOR_SI + $item->INDICADOR_NO) . ')', 'y' => floatval($item->INDICADOR_SI_porcentaje)];
        }

        $datosPurus = DatassRepositorio::datos_Distrito_PorProvincia($importacion_id, $indicador, 'PURUS');

        foreach ($datosPurus as $key => $item) {
            $puntosPurus[] = ['name' => ($item->Distrito) . ' (' . ($item->INDICADOR_SI) . ' de ' . ($item->INDICADOR_SI + $item->INDICADOR_NO) . ')', 'y' => floatval($item->INDICADOR_SI_porcentaje)];
        }


        $titulo_grafico = 'Grafico_IndicadorRegional';

        switch ($indicador) {
            case 1:
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Agua";
                break;
            case 2:
                $titulo_grafico = "Centros Poblados Rurales con Sistema de Cloracion";
                break;
            case 3:
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Agua por Red Pública";
                break;
            case 4:
                $titulo_grafico = "Porcentaje de Hogares con Cobertura de Alcantarillado u Otras Formas de Disposición Sanitaria de Excretas";
                break;
            case 5:
                $titulo_grafico = "Porcentaje de Hogares en Ambito Rural que Consume Agua Segura (Clorada)";
                break;
            default:
                $name_Y1 = "SI";
                break;
        }

        $titulo =  $titulo_grafico;
        $subTitulo = '';
        $titulo_y = 'Porcentaje';

        $nombreGraficoBarraHijo = 'Grafico_IndicadorProvincial_masDistrital'; // este nombre va de la mano con el nombre del DIV en la vista


        return view(
            'graficos.BarraPorcentualHijos',
            [
                "dataGrafico" => json_encode($puntos), "dataAtalaya" => json_encode($puntosAtalaya), "dataCrnlPortillo" => json_encode($puntosCrnlPortillo), "dataPadreAbad" => json_encode($puntosPadreAbad), "dataPurus" => json_encode($puntosPurus)
            ],
            compact('titulo', 'subTitulo', 'nombreGraficoBarraHijo')
        );
    }


    public function mapa_basico($indicador)
    {

        $vUrl = "";

        switch ($indicador) {
            case 1:
                $vUrl = "https://datastudio.google.com/embed/reporting/6c73c567-559b-4dd6-8608-64a0b502c85c/page/XXx8C";
                break;
            case 2:
                $vUrl = "https://datastudio.google.com/embed/reporting/efecbc31-61b0-4b6d-9638-b659a8f99672/page/XXx8C";
                break;
            case 3:
                $vUrl = "https://datastudio.google.com/embed/reporting/3616025f-5a6f-4915-af77-ae59755a4591/page/XXx8C";
                break;
            case 4:
                $vUrl = "https://datastudio.google.com/embed/reporting/a8a36b94-54cf-4bc8-ba99-d75a403330e0/page/XXx8C";
                break;
            case 5:
                $vUrl = "https://datastudio.google.com/embed/reporting/a09ee8a7-f80e-495c-8eff-64ea5785d1ba/page/XXx8C";
                break;
            default:
                $vUrl = "";
                break;
        }

        return view(
            'mapa.Basico',
            compact('vUrl')
        );
    }
}
