<?php

namespace App\Http\Controllers\Trabajo;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use Exception;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Trabajo\Anuario_Estadistico;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Trabajo\AnuarioEstadisticoRepositorio;
use App\Repositories\Trabajo\ProEmpleoRepositorio;
use PhpParser\Node\Stmt\Catch_;

use function PHPUnit\Framework\isNull;

class AnuarioEstadisticoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        $anios = Anio::orderBy('anio','desc')->get();             

        return view('trabajo.AnuarioEstadistico.Importar', compact('mensaje', 'anios'));

        // $lista = AnuarioEstadisticoRepositorio:: Promedio_Remuneracion_trab_sector_privado(19,34);

        // return $lista;
        
    }

    public function guardar(Request $request)
    {
        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);
        $anios = Anio::orderBy('anio', 'desc')->get();

        $i = 0;
        $cadena = '';

        // VALIDACION DEL FORMATO
        try {
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    if (++$i > 1) break;
                    $cadena =  $cadena .
                        $row['regiones'].$row['enero'].$row['febrero'].$row['marzo'].$row['abril'].$row['mayo'].
                        $row['junio'].$row['julio'].$row['agosto'].$row['setiembre'].$row['octubre'].$row['noviembre'].$row['diciembre'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";
            return view('trabajo.AnuarioEstadistico.Importar', compact('mensaje', 'anios'));
        }        

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request['fechaActualizacion'], $request['formato_reporte']);

        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            return view('Trabajo.AnuarioEstadistico.Importar', compact('mensaje', 'anios'));
        } else {
            try {
                $importacion = Importacion::Create([
                    'fuenteImportacion_id' => $request['formato_reporte'], // valor predeterminado
                    'usuarioId_Crea' => auth()->user()->id,
                    'usuarioId_Aprueba' => null,
                    'fechaActualizacion' => now(),
                    'comentario' => $request['comentario'],
                    'estado' => 'PE'
                ]);
                
                foreach ($array as $key => $value) {
                    foreach ($value as $row) {
                        if( $row['regiones']!='' )//para validar que no se registren filas vacias
                        { 
                            $ubigeo = UbigeoRepositorio:: buscar_PorNombre($row['regiones']);
                            $ubigeo_id = 0;

                            try{
                                $ubigeo_id  = $ubigeo->first()->id;
                            }catch (Exception $e) {
                                $ubigeo_id = 0;
                            } 

                            if($ubigeo_id!=0)
                            {
                                $Anuario_Estadistico = Anuario_Estadistico::Create([      
                                    'importacion_id'=>$importacion->id,
                                    'anio_id'=>$request['anio'],
                                    'ubigeo_id'=>$ubigeo_id,
                                    'enero'=>$row['enero'],
                                    'febrero'=>$row['febrero'],
                                    'marzo'=>$row['marzo'],
                                    'abril'=>$row['abril'],
                                    'mayo'=>$row['mayo'],
                                    'junio'=>$row['junio'],
                                    'julio'=>$row['julio'],
                                    'agosto'=>$row['agosto'],
                                    'setiembre'=>$row['setiembre'],
                                    'octubre'=>$row['octubre'],
                                    'noviembre'=>$row['noviembre'],
                                    'diciembre'=>$row['diciembre'],
                                ]);
                            }
                        }                        
                    }
                } 

            } catch (Exception $e) {
                $importacion->estado = 'EL';
                $importacion->save();

                $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema";
                return view('Trabajo.AnuarioEstadistico.Importar', compact('mensaje', 'anios'));
            }
        }

        return view('correcto');
    }

    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
     
        $anio_AnuarioEstadistico = AnuarioEstadisticoRepositorio::datos_AnuarioEstadistico($importacion_id)->first()->anio;
        
        return view('trabajo.AnuarioEstadistico.Aprobar', compact('importacion_id', 'importacion','anio_AnuarioEstadistico'));
    }
    
    public function procesar($importacion_id)
    {
        $importacion  = Importacion::find($importacion_id);

        $importacion->estado = 'PR';
        $importacion->usuarioId_Aprueba = auth()->user()->id;
        $importacion->save();
        
        $importacion  = ProEmpleoRepositorio::eliminar_mismoPeriodo($importacion_id);

        return view('correcto');
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = AnuarioEstadisticoRepositorio::Listar_Por_Importacion_id($importacion_id);

        return  datatables()->of($padronWebLista)->toJson();
    }

    public function Grafico_Promedio_Remuneracion ($importacion_id)
    {

        $categoria1 = [];

        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: Promedio_Remuneracion_trab_sector_privado(19,34);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedioAnual)]);
            $categoria_nombres[] = $lista->anio;
        }

        $puntos[] = ['name' => 'REGION DE UCAYALI', 'data' =>  $categoria1];

        $titulo = 'Promedio Mensual de Remuneraciones de Trabajadores Sector Privado ' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Monto S/';

        $nombreGraficoBarra = 'Grafico_Promedio_Remuneracion'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_Promedio_Trabajadores ($importacion_id)
    {
        $categoria1 = [];

        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: Promedio_Remuneracion_trab_sector_privado(20,34);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedioAnual)]);
            $categoria_nombres[] = $lista->anio;
        }

        $puntos[] = ['name' => 'REGION DE UCAYALI', 'data' =>  $categoria1];

        $titulo = 'Promedio Mensual de Trabajadores Sector Privado ' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Cantidad Promedio Mensual';

        $nombreGraficoBarra = 'Grafico_Promedio_Trabajadores'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_Prestadores_Servicio4ta_Publico ($importacion_id)
    {
        $categoria1 = [];

        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: Promedio_Remuneracion_trab_sector_privado(21,34);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedioAnual)]);
            $categoria_nombres[] = $lista->anio;
        }

        $puntos[] = ['name' => 'REGION DE UCAYALI', 'data' =>  $categoria1];

        $titulo = 'Promedio Mensual de Prestadores de Sercicio 4ta Categoria - Sector Público' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Cantidad Promedio Mensual';

        $nombreGraficoBarra = 'Grafico_Prestadores_Servicio4ta_Publico'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_Prestadores_Servicio4ta_Privado ($importacion_id)
    {
        $categoria1 = [];

        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: Promedio_Remuneracion_trab_sector_privado(22,34);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedioAnual)]);
            $categoria_nombres[] = $lista->anio;
        }

        $puntos[] = ['name' => 'REGION DE UCAYALI', 'data' =>  $categoria1];

        $titulo = 'Promedio Mensual de Prestadores de Sercicio 4ta Categoria - Sector Privado' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Cantidad Promedio Mensual';

        $nombreGraficoBarra = 'Grafico_Prestadores_Servicio4ta_Privado'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function rptRemunTrabSectorPrivado ()
    {
        // $data = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones(5,19);
        $anios = AnuarioEstadisticoRepositorio::anios_anuarioEstadistico();

        // return $anios;
        return view('Trabajo.AnuarioEstadistico.rptRemunTrabSectorPrivado',compact('anios'));
    }

    public function rptAnuarioEstadistico_DataTable($fuenteImportacion_id)
    {
        $data = AnuarioEstadisticoRepositorio::datosAnuario_Estadistico($fuenteImportacion_id,34);

        return  datatables()->of($data)->toJson();;
    }

    public function Grafico_Promedio_Remuneracion_porAnio($importacion_id)
    {
        $promedio = [];      
        $categoria = [];

        // $datos_PorMes_yAnio = ProEmpleoRepositorio::datos_PorMes_yAnio(2022);

        $data = AnuarioEstadisticoRepositorio::datosAnuario_Estadistico(19,34);

        $data = $data->reverse();

        foreach ($data  as $key => $lista) {
            $promedio = array_merge($promedio,[intval ($lista->promedioAnual)]);
            $categoria = array_merge($categoria,[intval ($lista->anio)]);
        } 

        $puntos[] = [ 'name'=> 'promedio' ,'data'=>  $promedio];
      
        $titulo = 'Promedio Mensual de Remuneración - Ucayali Sector Privado';
        $subTitulo = 'Fuente - MTPE';
        $titulo_y = 'Monto S/';

        $nombreGraficoLineal = 'Grafico_Promedio_Remuneracion_porAnio'; // este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Lineal', ["dataLineal" => json_encode($puntos),"categoria_nombres" => json_encode($categoria)],
        compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoLineal'));
    }

    public function Grafico_ranking_promedio_remuneracion_regiones($anio_id)
    {
        $categoria1 = [];
        
        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones($anio_id,19);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedio)]);
            $categoria_nombres[] = $lista->posicion.'-'.$lista->region;
        }
        
        $puntos[] = ['name' => 'Regiones del Perú', 'data' =>  $categoria1];

        $titulo = 'Ranking Promedio  Mensual de Remuneración Por Regiones - Sector Privado' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Monto S/';

        $nombreGraficoBarra = 'Grafico_ranking_promedio_remuneracion_regiones'; // este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Barra', ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
                    compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_ranking_promedio_prestadores_servicio4ta()
    {
        $categoria1 = [];
        $categoria2 = [];
        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: ranking_promedio_prestadores_servicio4ta();

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->publico)]);
            $categoria2 = array_merge($categoria2, [intval($lista->privado)]);
            $categoria_nombres[] = ($lista->anio);       
        }

        $puntos[] = ['name' => 'Público', 'data' =>  $categoria1];
        $puntos[] = ['name' => 'Privado', 'data' => $categoria2];

        $titulo = 'PROMEDIO MENSUAL DE PRESTADORES DE SERVICIO DE 4TA CATEGORIA' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Numero de personas';

        $nombreGraficoBarra = 'Grafico_ranking_promedio_prestadores_servicio4ta'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function rptTrabajadoresSectorPrivado()
    {
        // $data = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones(5,19);

        // return $data;
        return view('Trabajo.AnuarioEstadistico.rptTrabajadoresSectorPrivado');
    }

    public function rptPromedioPrestaServ()
    {
        // $data = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones(5,19);
       
        // return $data;
        return view('Trabajo.AnuarioEstadistico.rptPromedioPrestaServ');
    }

    public function rptPrestadoresServ4taCategoria()
    {
        // $data = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones(5,19);
        // $datos = AnuarioEstadisticoRepositorio:: ranking_promedio_prestadores_servicio4ta();
        //  return $datos;

        
        return view('Trabajo.AnuarioEstadistico.rptPrestadoresServ4taCategoria');
    }

    public function rptEmpresasSectorPrivado()
    {
        // $data = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones(5,19);
        $anios = AnuarioEstadisticoRepositorio::anios_anuarioEstadistico();
        // return $data;
        return view('Trabajo.AnuarioEstadistico.rptEmpresasSectorPrivado',compact('anios'));
    }

    public function Grafico_promedio_Empresas_sectorPrivado ($importacion_id)
    {

        $categoria1 = [];

        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: Promedio_Remuneracion_trab_sector_privado(23,34);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedioAnual)]);
            $categoria_nombres[] = $lista->anio;
        }

        $puntos[] = ['name' => 'REGION DE UCAYALI', 'data' =>  $categoria1];

        $titulo = 'Promedio Mensual de Empresas - Ucayali Sector Privado ' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Número promedio de empresas';

        $nombreGraficoBarra = 'Grafico_promedio_Empresas_sectorPrivado'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_ranking_empresas_regiones($anio_id)
    {
        $categoria1 = [];
        
        $categoria_nombres = [];

        $datos = AnuarioEstadisticoRepositorio:: ranking_promedio_remuneracion_regiones($anio_id,23); // cambiar el nombre metodo

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->promedio)]);
            $categoria_nombres[] = $lista->posicion.'-'.$lista->region;
        }
        
        $puntos[] = ['name' => 'Regiones del Perú', 'data' =>  $categoria1];

        $titulo = 'Ranking Promedio Empresas Mensual Por Regiones - Sector Privado' ;
        $subTitulo = 'Fuente: MTPE';
        $titulo_y = 'Número Empresas';

        $nombreGraficoBarra = 'Grafico_ranking_empresas_regiones'; // este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Barra', ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
                    compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

}
