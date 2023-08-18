<?php

namespace App\Http\Controllers\Educacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\TextosEscolares;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\TextosEscolaresRepositorio;
use App\Utilities\Utilitario;
use Exception;


class TextosEscolaresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {  
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();
        
        return view('educacion.TextosEscolares.Importar',compact('mensaje','anios'));
    } 
    
    public function guardar(Request $request)
    {
        $this->validate($request,['file' => 'required|mimes:xls,xlsx']);      
        $archivo = $request->file('file');
        $array = (new tablaXImport )-> toArray($archivo);    
        $anios = Anio::orderBy('anio', 'desc')->get();

        $i = 0;
        $cadena ='';

        // VALIDACION DE LOS FORMATOS DE LOS 04 NIVELES
         try{
         foreach ($array as $key => $value) {
             foreach ($value as $row) {
                if(++$i > 1) break;
                    $cadena =  $cadena
                    .$row['region'].$row['cod_ugel'].$row['ugel'].$row['dotacion'].$row['direccion'].$row['codigo_sigema']
                    .$row['codigo_siga'].$row['material'].$row['beneficiario'].$row['cantidad_ugel'].$row['peso_unitario']
                    .$row['peso_total_kg'].$row['volumen_unitario'].$row['fecha_llegada_ugel'].$row['anio'].$row['mes'].$row['tramo']
                    ;              
                }
             }
         }catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.TextosEscolares.Importar',compact('mensaje','anios'));            
         }   

         $existeMismaFecha = ImportacionRepositorio :: Importacion_PE($request['fechaActualizacion'],17);

         if( $existeMismaFecha != null)
         {
             $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";          
             return view('Educacion.TextosEscolares.Importar',compact('mensaje','anios'));            
         }
 
         else
         {
               try{
                   $importacion = Importacion::Create([
                       'fuenteImportacion_id'=>17, // valor predeterminado
                       'usuarioId_Crea'=> auth()->user()->id,
                       'usuarioId_Aprueba'=>null,
                       'fechaActualizacion'=>$request['fechaActualizacion'],
                       'comentario'=>$request['comentario'],
                       'estado'=>'PE'
                   ]); 

                   foreach ($array as $key => $value) {
                        foreach ($value as $row) {
                            $TextosEscolares = TextosEscolares::Create([
                                'importacion_id'=>$importacion->id, // valor predeterminado
                                'anio_id'=> $request['anio'],

                                'region'=> $row['region'],
                                'cod_ugel'=> $row['cod_ugel'],
                                'ugel'=> $row['ugel'],
                                'dotacion'=> $row['dotacion'],
                                'direccion'=> $row['direccion'],
                                'codigo_sigema'=> $row['codigo_sigema'],
                                'codigo_siga'=> $row['codigo_siga'],
                                'material'=> $row['material'],
                                'beneficiario'=> $row['beneficiario'],
                                'cantidad_ugel'=> $row['cantidad_ugel'],
                                'peso_unitario'=> $row['peso_unitario'],
                                'peso_total_kg'=> $row['peso_total_kg'],
                                'volumen_unitario'=> $row['volumen_unitario'],
                                'fecha_llegada_ugel'=> $row['fecha_llegada_ugel'],
                                'anio'=> $row['anio'],
                                'mes'=> $row['mes'],
                                'tramo'=> $row['tramo'],
                            ]); 
                        }
                    }
                   
                  }catch (Exception $e) {
                      $importacion->estado = 'EL';
                      $importacion->save();
                      
                      $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema";        
                      return view('Educacion.TextosEscolares.Importar',compact('mensaje','anios'));            
                  }
         }

         return view('correcto');

    } 

    public function ListaImportada_DataTable($importacion_id)
    {
        // $Lista = CensoRepositorio::Listar_Por_Importacion_id($importacion_id);
                
        // return  datatables()->of($Lista)->toJson();;
    }
    
    // public function ListaImportada($importacion_id)
    // {
    //     $datos_PadronEIB_importada = $this->datos_matricula_importada($importacion_id);  
    //     return view('Educacion.PadronEIB.ListaImportada',compact('importacion_id','datos_PadronEIB_importada'));
    // }

    // public function aprobar($importacion_id)
    // {
    //     $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);        
        

    //     return view('educacion.PadronEIB.Aprobar',compact('importacion_id','importacion'));
    // } 

    // public function datos_PadronEIB_importada($importacion_id)
    // {
    //     $PadronEIB = MatriculaRepositorio::matricula_porImportacion($importacion_id);        
    //     return $datos_matricula_importada = MatriculaRepositorio::datos_matricula_importada($PadronEIB->first()->id);
    // }

    public function procesar($importacion_id)
    {
        $importacion  = Importacion::find($importacion_id);

        $importacion->estado = 'PR';    
        $importacion->usuarioId_Aprueba = auth()->user()->id;    
        $importacion->save();

        $this->elimina_mismaFecha($importacion->fechaActualizacion,$importacion->fuenteImportacion_id,$importacion_id);

        return view('correcto');
    }


    public function elimina_mismaFecha($fechaActualizacion,$fuenteImportacion_id,$importacion_id)
    {
        $importacion  = ImportacionRepositorio::Importacion_mismaFecha($fechaActualizacion,$fuenteImportacion_id,$importacion_id);

        if($importacion!=null)
        {
            $importacion->estado = 'EL';
            $importacion->save();
        }
        
    }    



    //**************************************************************************************** */
    public function principal()
    {
       
        $anios =  TextosEscolaresRepositorio ::TextosEscolares_anio();

        $fechas = TextosEscolaresRepositorio ::fechas_TextosEscolares_anio($anios->first()->id);
              
        return view('educacion.TextosEscolares.Principal', compact('anios','fechas'));       
    }

    public function Fechas($anio_id)
    {
        $fechas_TextosEscolares = TextosEscolaresRepositorio ::fechas_TextosEscolares_anio($anio_id);      
        return response()->json(compact('fechas_TextosEscolares'));
    }

    public function reporteUgel($importacion_id)
    {
        $data =  TextosEscolaresRepositorio ::total_porBeneficiario($importacion_id);
       
        return view('educacion.TextosEscolares.ReporteUgel',compact('data'));
    }


    
    
}
