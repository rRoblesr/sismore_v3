<?php

namespace App\Http\Controllers\Trabajo;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use Exception;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Models\Trabajo\ProEmpleo;
use App\Models\Trabajo\ProEmpleo_Colocados;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Trabajo\ProEmpleoRepositorio;

class ProEmpleoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        // $padronWebLista = ProEmpleoRepositorio::datos_PorMes_yAnio(2022);

        // return $padronWebLista;
        $mensaje = "";
        $anios = Anio::orderBy('anio','desc')->get();
      
        // $meses = collect([
        //       ['nombre' => 'Enero', 'mes' => 1],
        //       ['nombre' => 'Febrero', 'mes' => 2],
        //       ['nombre' => 'Marzo', 'mes' => 3],
        //       ['nombre' => 'Abril', 'mes' => 4],
        //  ]);        

        return view('trabajo.ProEmpleo.Importar', compact('mensaje', 'anios'));
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
                        $row['ruc'].$row['empresa'].$row['titulo'].$row['provincia'].$row['distrito'].$row['tipo_de_documento'].
                        $row['dni'].$row['nombres'].$row['apellidos'].$row['sexo'].$row['pcd'].$row['email'].$row['telefono1'].
                        $row['telefono2'].$row['colocado'].$row['fuente'].$row['observaciones'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";
            return view('Trabajo.ProEmpleo.Importar', compact('mensaje', 'anios'));
        }        

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request['fechaActualizacion'], 18);

        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            return view('Trabajo.ProEmpleo.Importar', compact('mensaje', 'anios'));
        } else {
            try {
                $importacion = Importacion::Create([
                    'fuenteImportacion_id' => 18, // valor predeterminado
                    'usuarioId_Crea' => auth()->user()->id,
                    'usuarioId_Aprueba' => null,
                    'fechaActualizacion' => now(),
                    'comentario' => $request['comentario'],
                    'estado' => 'PE'
                ]);   

                $ProEmpleo = ProEmpleo::Create([
                    'mes'=>$request['mes'],
                    'importacion_id'=>$importacion->id,
                    'anio_id'=>$request['anio'],
                    'oferta_hombres' => $request['oferta_hombres'],
                    'oferta_mujeres' => $request['oferta_mujeres'],
                    'demanda' => $request['demanda']
                ]);                
                
                foreach ($array as $key => $value) {
                    foreach ($value as $row) {
                        if( $row['ruc']!='' )//para validar que no se registren filas vacias
                        {                        
                            $ProEmpleo_Colocados = ProEmpleo_Colocados::Create([                                        
                                'ruc'=>$row['ruc'],
                                'empresa'=>$row['empresa'],
                                'titulo'=>$row['titulo'],
                                'provincia'=>$row['provincia'],
                                'distrito'=>$row['distrito'],
                                'tipDoc'=>$row['tipo_de_documento'],
                                'documento'=>$row['dni'],
                                'nombres'=>$row['nombres'],
                                'apellidos'=>$row['apellidos'],
                                'sexo'=>$row['sexo'],
                                'per_Con_Discapacidad'=>$row['pcd'],
                                'email'=>$row['email'],
                                'telefono1'=>$row['telefono1'],
                                'telefono2'=>$row['telefono2'],
                                'colocado'=>$row['colocado'],
                                'fuente'=>$row['fuente'],
                                'observaciones'=>$row['observaciones'],                           
                                'proempleo_id'=>$ProEmpleo->id,                            
                            ]);
                        }                        
                    }
                }

            } catch (Exception $e) {
                $importacion->estado = 'EL';
                $importacion->save();

                $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema";
                return view('Trabajo.ProEmpleo.Importar', compact('mensaje', 'anios'));
            }
        }

        return view('correcto');
    }

    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);

        $proEmpleo = ProEmpleoRepositorio:: ProEmpleo_porIdImportacion($importacion_id);
        
        return view('trabajo.ProEmpleo.Aprobar', compact('importacion_id', 'importacion','proEmpleo'));
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

    // public function elimina_mielimina_mismaPeriodosmaFecha($importacion_id)
    // {
    //     $importacion  = ProEmpleoRepositorio::eliminar_mismoPeriodo(  $importacion_id);

    //     if ($importacion != null) {
    //         $importacion->estado = 'EL';
    //         $importacion->save();
    //     }
    // }

    public function ListaImportada_DataTable($importacion_id)
    {
        $padronWebLista = ProEmpleoRepositorio::Listar_Por_Importacion_id($importacion_id);

        return  datatables()->of($padronWebLista)->toJson();;
    }

    public function Grafico_oferta_demanda_colocados($anio_id)
    {
        $demanda = [];
        $cantColocados = [];
        $oferta = [];

        //cuandso el id viene del dashboard viene con CERO y se busca el mas actual 

        if ($anio_id == 0)
            $anio_id = ProEmpleoRepositorio::ProEmpleo_ultimo_anio()->anio_id;
      
        if ($anio_id == 0)
            $nombreAnio = '';                  
        else
            $nombreAnio = Anio::find($anio_id)->anio;

        $datos_PorMes_yAnio = ProEmpleoRepositorio::datos_PorMes_yAnio($anio_id);

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($datos_PorMes_yAnio as $key => $lista) {
            $demanda = array_merge($demanda,[intval ($lista->demanda)]);  
            $cantColocados = array_merge($cantColocados,[intval ($lista->cantColocados)]);  
            $oferta = array_merge($oferta,[intval ($lista->oferta)]);
        } 

        $puntos[] = [ 'name'=> 'Oferta' ,'data'=>  $oferta];
        $puntos[] = [ 'name'=> 'Demanda' ,'data'=>  $demanda];
        $puntos[] = [ 'name'=> 'Colocados' ,'data'=> $cantColocados ];

        $categoria = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Set','Oct','Nov','Dic'];
      
        $titulo = 'Oferta-Demanda-Colocados PROEMPLEO '.$nombreAnio;
        $subTitulo = 'Fuente: DRPE - UCAYALI';
        $titulo_y = 'Número personas';

        $nombreGraficoLineal = 'Grafico_oferta_demanda_colocados'; // este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Lineal', ["dataLineal" => json_encode($puntos),"categoria_nombres" => json_encode($categoria)],
        compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoLineal'));
    }

    /******************************************************************************************************************** */

    public function Principal ()
    {
        // $anios = Anio::orderBy('anio', 'desc')->get();

        $anios = ProEmpleoRepositorio::ProEmpleo_anios();

        return view('Trabajo.ProEmpleo.Principal',compact('anios'));
    }

    public function VariablesMercado ($anio_id)
    {
        $data = ProEmpleoRepositorio::formato_reporte_MTPE($anio_id);
        $anio = Anio::find($anio_id)->anio;

        return view('Trabajo.ProEmpleo.VariablesMercadoParcial',compact('data','anio'));
    }

    public function Grafico_Colocados_Hombres_Vs_Mujeres($anio_id)
    {
        $data = ProEmpleoRepositorio::formato_reporte_MTPE($anio_id);  

        $categoria1 = [];
        $categoria2 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($data as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->cantColocadosM)]);
            $categoria2 = array_merge($categoria2, [intval($lista->cantColocadosF)]);           
            $categoria_nombres[] = $lista->nombreMes;
        }

        $puntos[] = ['name' => 'HOMBRES', 'data' =>  $categoria1];
        $puntos[] = ['name' => 'MUJERES', 'data' => $categoria2];

        // if ($anio_id == 0)
        //     $nombreAnio = Utilitario::anio_deFecha(TabletaRepositorio::tableta_mas_actual()->first()->fechaActualizacion);
        // else
        $nombreAnio = Anio::find($anio_id)->anio;

        $titulo = 'Colocados ProEmpleo Hombres vs Mujeres '. $nombreAnio ;
        $subTitulo = 'Fuente: DRPE - UCAYALI';
        $titulo_y = 'Numero Personas';

        $nombreGraficoBarra = 'Grafico_Colocados_Hombres_Vs_Mujeres'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function Grafico_Colocados_per_Con_Discapacidad($anio_id)
    {
        $data = ProEmpleoRepositorio::formato_reporte_MTPE_Discapacitados($anio_id);  

        $categoria1 = [];    
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($data as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->totalColocados)]);                
            $categoria_nombres[] = $lista->nombreMes;
        }

        $nombreAnio = Anio::find($anio_id)->anio;

        $puntos[] = ['name' => 'Ucayali '.$nombreAnio, 'data' =>  $categoria1];     
        $nombreAnio = Anio::find($anio_id)->anio;

        $titulo = ' Colocados ProEmpleo - Personas con Discapacidad '. $nombreAnio ;
        $subTitulo = 'Fuente: DRPE - UCAYALI';
        $titulo_y = 'Numero Personas';

        $nombreGraficoBarra = 'Grafico_Colocados_per_Con_Discapacidad'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }
}
