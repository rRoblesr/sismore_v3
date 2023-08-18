<?php

namespace App\Http\Controllers\Educacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaAnual;
use App\Models\Educacion\MatriculaAnualDetalle;
use App\Models\Educacion\MatriculaDetalle;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\InstitucionEducativaRepositorio;
use App\Repositories\Educacion\MatriculaRepositorio;
use App\Utilities\Utilitario;

use Exception;
use PhpParser\Node\Expr\FuncCall;

class MatriculaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {  
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();
        
        return view('educacion.Matricula.Importar',compact('mensaje','anios'));
    } 
    
    public function guardar(Request $request)
    { 
        $this->validate($request,['fileInicial' => 'required|mimes:xls,xlsx']);    
        $archivoInicial = $request->file('fileInicial');
        $arrayInicial = (new tablaXImport )-> toArray($archivoInicial);  

        $this->validate($request,['filePrimaria' => 'required|mimes:xls,xlsx']);    
        $archivoPrimaria = $request->file('filePrimaria');
        $arrayPrimaria = (new tablaXImport )-> toArray($archivoPrimaria); 

        $this->validate($request,['fileSecundaria' => 'required|mimes:xls,xlsx']);    
        $archivoSecundaria = $request->file('fileSecundaria');
        $arraySecundaria = (new tablaXImport )-> toArray($archivoSecundaria); 

        $this->validate($request,['fileEBE' => 'required|mimes:xls,xlsx']);    
        $archivoEBE = $request->file('fileEBE');
        $arrayEBE = (new tablaXImport )-> toArray($archivoEBE); 

        $anios = Anio::orderBy('anio', 'desc')->get();

        $i = 0;
        $cadena ='';

        // VALIDACION DE LOS FORMATOS DE LOS 04 NIVELES
        try{
            foreach ($arrayInicial as $key => $value) {
                foreach ($value as $row) {
                   if(++$i > 1) break;
                   $cadena =  $cadena
                   .$row['dre'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito'].$row['centropoblado']
                   .$row['cod_mod'].$row['anexo'].$row['nombre_ie'].$row['nivel'].$row['modalidad'].$row['tipo_ie']
                   .$row['total_estudiantes_matriculados_inicial']                    
                   .$row['matricula_definitiva'].$row['matricula_en_proceso'].$row['dni_validado']
                   .$row['dni_sin_validar'].$row['registrado_sin_dni'].$row['total_grados'].$row['total_secciones']
                   .$row['nominas_generadas'].$row['nominas_aprobadas'].$row['nominas_por_rectificar']                    
                   .$row['cero_anios_hombre'].$row['cero_anios_mujer']
                   .$row['uno_anios_hombre'].$row['uno_anios_mujer'].$row['dos_anios_hombre'].$row['dos_anios_mujer']
                   .$row['tres_anios_hombre'].$row['tres_anios_mujer'].$row['cuatro_anios_hombre'].$row['cuatro_anios_mujer']
                   .$row['cinco_anios_hombre'].$row['cinco_anios_mujer'].$row['masde_cinco_anios_hombre'].$row['masde_cinco_anios_mujer'];              }
            }
        }catch (Exception $e) {
           $mensaje = "Formato de archivo Nivel Inicial no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
           return view('Educacion.Matricula.Importar',compact('mensaje','anios'));            
        }       
        
        $i = 0;
        $cadena ='';

        try{
             foreach ($arrayPrimaria as $key => $value) {
                 foreach ($value as $row) {
                    if(++$i > 1) break;
                    $cadena =  $cadena
                    .$row['dre'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito'].$row['centropoblado']
                    .$row['cod_mod'].$row['anexo'].$row['nombre_ie'].$row['nivel'].$row['modalidad'].$row['tipo_ie']
                    .$row['total_estudiantes_matriculados_primaria'].$row['matricula_definitiva'].$row['matricula_en_proceso']
                    .$row['dni_validado'].$row['dni_sin_validar'].$row['registrado_sin_dni'].$row['total_grados']
                    .$row['total_secciones'].$row['nominas_generadas'].$row['nominas_aprobadas'].$row['nominas_por_rectificar']
                    .$row['primer_grado_hombre'].$row['primer_grado_mujer'].$row['segundo_grado_hombre'].$row['segundo_grado_mujer']
                    .$row['tercer_grado_hombre'].$row['tercer_grado_mujer'].$row['cuarto_grado_hombre'].$row['cuarto_grado_mujer']
                    .$row['quinto_grado_hombre'].$row['quinto_grado_mujer'].$row['sexto_grado_hombre'].$row['sexto_grado_mujer'];             
                }
             }
        }catch (Exception $e) {
            $mensaje = "Formato de archivo Nivel Primaria no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.Matricula.Importar',compact('mensaje','anios'));            
        }  
        
        $i = 0;
        $cadena ='';

        try{
             foreach ($arraySecundaria as $key => $value) {
                 foreach ($value as $row) {
                    if(++$i > 1) break;
                    $cadena =  $cadena
                    .$row['dre'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito'].$row['centropoblado']
                    .$row['cod_mod'].$row['anexo'].$row['nombre_ie'].$row['nivel'].$row['modalidad'].$row['tipo_ie']
                    .$row['total_estudiantes_matriculados_secundaria'].$row['matricula_definitiva'].$row['matricula_en_proceso']
                    .$row['dni_validado'].$row['dni_sin_validar'].$row['registrado_sin_dni'].$row['total_grados']
                    .$row['total_secciones'].$row['nominas_generadas'].$row['nominas_aprobadas'].$row['nominas_por_rectificar']
                    .$row['primer_grado_hombre'].$row['primer_grado_mujer'].$row['segundo_grado_hombre'].$row['segundo_grado_mujer']
                    .$row['tercer_grado_hombre'].$row['tercer_grado_mujer'].$row['cuarto_grado_hombre'].$row['cuarto_grado_mujer']
                    .$row['quinto_grado_hombre'].$row['quinto_grado_mujer'];             
                }
             }
        }catch (Exception $e) {
            $mensaje = "Formato de archivo Nivel Secundaria no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.Matricula.Importar',compact('mensaje','anios'));            
        } 

        $i = 0;
        $cadena ='';

        try{
             foreach ($arrayEBE as $key => $value) {
                 foreach ($value as $row) {
                    if(++$i > 1) break;
                    $cadena =  $cadena
                    .$row['dre'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito'].$row['centropoblado']
                    .$row['cod_mod'].$row['anexo'].$row['nombre_ie'].$row['nivel'].$row['modalidad'].$row['tipo_ie']
                    .$row['total_estudiantes_matriculados_ebe'].$row['matricula_definitiva'].$row['matricula_en_proceso']
                    .$row['dni_validado'].$row['dni_sin_validar'].$row['registrado_sin_dni'].$row['total_grados']
                    .$row['total_secciones'].$row['nominas_generadas'].$row['nominas_aprobadas'].$row['nominas_por_rectificar']
                    .$row['tres_anios_hombre'].$row['tres_anios_mujer'].$row['cuatro_anios_hombre'].$row['cuatro_anios_mujer']
                    .$row['cinco_anios_hombre'].$row['cinco_anios_mujer'].$row['primer_grado_hombre'].$row['primer_grado_mujer']
                    .$row['segundo_grado_hombre'].$row['segundo_grado_mujer'].$row['tercer_grado_hombre'].$row['tercer_grado_mujer']
                    .$row['cuarto_grado_hombre'].$row['cuarto_grado_mujer'].$row['quinto_grado_hombre'].$row['quinto_grado_mujer']
                    .$row['sexto_grado_hombre'].$row['sexto_grado_mujer'];             
                }
             }
        }catch (Exception $e) {
            $mensaje = "Formato de archivo EBE no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.Matricula.Importar',compact('mensaje','anios'));            
        }   
        
         // FIN VALIDACION DE LOS FORMATOS DE LOS 04 NIVELES

        $existeMismaFecha = ImportacionRepositorio :: Importacion_PE($request['fechaActualizacion'],8);

        if( $existeMismaFecha != null)
        {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";          
            return view('Educacion.Matricula.Importar',compact('mensaje','anios'));            
        }

        else
        {
            $creacionExitosa = 1;

            try{
                $importacion = Importacion::Create([
                    'fuenteImportacion_id'=>8, // valor predeterminado
                    'usuarioId_Crea'=> auth()->user()->id,
                    'usuarioId_Aprueba'=>null,
                    'fechaActualizacion'=>$request['fechaActualizacion'],
                    'comentario'=>$request['comentario'],
                    'estado'=>'PE'
                  ]); 
    
                $Matricula = Matricula::Create([
                    'importacion_id'=>$importacion->id, // valor predeterminado
                    'anio_id'=> $request['anio'],
                    'estado'=>'PE'
                  ]); 
               
            }catch (Exception $e) {
                $creacionExitosa = 0;
            }
            
            $mensajeNivel = "";
    
            if($creacionExitosa==1)
            {
                $creacionExitosa = $this->guardar_inicial($arrayInicial,$Matricula->id);
    
                if($creacionExitosa==1)
                {
                    $creacionExitosa = $this->guardar_primaria($arrayPrimaria,$Matricula->id);
                    if($creacionExitosa==1)
                    {
                        $creacionExitosa = $this->guardar_secundaria($arraySecundaria,$Matricula->id);
                        if($creacionExitosa==1)
                        {
                            $creacionExitosa = $this->guardar_EBE($arrayEBE,$Matricula->id);
                            if($creacionExitosa==0)
                            {
                                $mensajeNivel = "EBE";  
                            }
                        }
                        else
                        {
                            $mensajeNivel = "Nivel SECUNDARIA";  
                        }
                    }
                    else
                    {
                        $mensajeNivel = "Nivel PRIMARIA";  
                    }
                }
                else
                { 
                    $mensajeNivel ="Nivel INICIAL";
                }
            }
    
            if($creacionExitosa==0)
            {
                $importacion->estado = 'EL';
                $importacion->save();
    
                $Matricula->estado = 'EL';
                $Matricula->save();
    
                $mensaje = "Error en la carga de ".$mensajeNivel.", verifique los datos de su archivo y/o comuniquese con el administrador del sistema";          
                return view('Educacion.Matricula.Importar',compact('mensaje','anios'));
            }
    
            return redirect()->route('Matricula.Matricula_Lista',$importacion->id);

        }

    }   

    public function guardar_inicial($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $institucion_educativa = InstitucionEducativaRepositorio::InstitucionEducativa_porCodModular($row['cod_mod'])->first();
                    if($institucion_educativa!=null)
                    {
                        $MatriculaDetalle = MatriculaDetalle::Create([
                      
                            'matricula_id'=>$matricula_id,                        
                            'institucioneducativa_id'=>$institucion_educativa->id,
                            'nivel'=>'I',
                            'total_estudiantes_matriculados'=>$row['total_estudiantes_matriculados_inicial'],
                            'matricula_definitiva'=>$row['matricula_definitiva'],
                            'matricula_en_proceso'=>$row['matricula_en_proceso'],
                            'dni_validado'=>$row['dni_validado'],
                            'dni_sin_validar'=>$row['dni_sin_validar'],
                            'registrado_sin_dni'=>$row['registrado_sin_dni'],
                            'total_grados'=>$row['total_grados'],
                            'total_secciones'=>$row['total_secciones'],
                            'nominas_generadas'=>$row['nominas_generadas'],
                            'nominas_aprobadas'=>$row['nominas_aprobadas'],
                            'nominas_por_rectificar'=>$row['nominas_por_rectificar'],
                            'cero_nivel_hombre'=>$row['cero_anios_hombre'],
                            'cero_nivel_mujer'=>$row['cero_anios_mujer'],
                            'primer_nivel_hombre'=>$row['uno_anios_hombre'],
                            'primer_nivel_mujer'=>$row['uno_anios_mujer'],
                            'segundo_nivel_hombre'=>$row['dos_anios_hombre'],
                            'segundo_nivel_mujer'=>$row['dos_anios_mujer'],
                            'tercero_nivel_hombre'=>$row['tres_anios_hombre'],
                            'tercero_nivel_mujer'=>$row['tres_anios_mujer'],
                            'cuarto_nivel_hombre'=>$row['cuatro_anios_hombre'],
                            'cuarto_nivel_mujer'=>$row['cuatro_anios_mujer'],
                            'quinto_nivel_hombre'=>$row['cinco_anios_hombre'],
                            'quinto_nivel_mujer'=>$row['cinco_anios_mujer'],
                            'sexto_nivel_hombre'=>$row['masde_cinco_anios_hombre'],
                            'sexto_nivel_mujer'=>$row['masde_cinco_anios_mujer']
            
                        ]);
                    }
                    
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    public function guardar_primaria($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $institucion_educativa = InstitucionEducativaRepositorio::InstitucionEducativa_porCodModular($row['cod_mod'])->first();
                    if($institucion_educativa!=null)
                    {
                        $MatriculaDetalle = MatriculaDetalle::Create([
                      
                            'matricula_id'=>$matricula_id,                        
                            'institucioneducativa_id'=>$institucion_educativa->id,
                            'nivel'=>'P',
                            'total_estudiantes_matriculados'=>$row['total_estudiantes_matriculados_primaria'],
                            'matricula_definitiva'=>$row['matricula_definitiva'],
                            'matricula_en_proceso'=>$row['matricula_en_proceso'],
                            'dni_validado'=>$row['dni_validado'],
                            'dni_sin_validar'=>$row['dni_sin_validar'],
                            'registrado_sin_dni'=>$row['registrado_sin_dni'],
                            'total_grados'=>$row['total_grados'],
                            'total_secciones'=>$row['total_secciones'],
                            'nominas_generadas'=>$row['nominas_generadas'],
                            'nominas_aprobadas'=>$row['nominas_aprobadas'],
                            'nominas_por_rectificar'=>$row['nominas_por_rectificar'],                            
                            'primer_nivel_hombre'=>$row['primer_grado_hombre'],
                            'primer_nivel_mujer'=>$row['primer_grado_mujer'],
                            'segundo_nivel_hombre'=>$row['segundo_grado_hombre'],
                            'segundo_nivel_mujer'=>$row['segundo_grado_mujer'],
                            'tercero_nivel_hombre'=>$row['tercer_grado_hombre'],
                            'tercero_nivel_mujer'=>$row['tercer_grado_mujer'],
                            'cuarto_nivel_hombre'=>$row['cuarto_grado_hombre'],
                            'cuarto_nivel_mujer'=>$row['cuarto_grado_mujer'],
                            'quinto_nivel_hombre'=>$row['quinto_grado_hombre'],
                            'quinto_nivel_mujer'=>$row['quinto_grado_mujer'],
                            'sexto_nivel_hombre'=>$row['sexto_grado_hombre'],
                            'sexto_nivel_mujer'=>$row['sexto_grado_mujer']
                        ]);
                    }
                    
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    public function guardar_secundaria($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $institucion_educativa = InstitucionEducativaRepositorio::InstitucionEducativa_porCodModular($row['cod_mod'])->first();
                    if($institucion_educativa!=null)
                    {
                        $MatriculaDetalle = MatriculaDetalle::Create([
                      
                            'matricula_id'=>$matricula_id,                        
                            'institucioneducativa_id'=>$institucion_educativa->id,
                            'nivel'=>'S',
                            'total_estudiantes_matriculados'=>$row['total_estudiantes_matriculados_secundaria'],
                            'matricula_definitiva'=>$row['matricula_definitiva'],
                            'matricula_en_proceso'=>$row['matricula_en_proceso'],
                            'dni_validado'=>$row['dni_validado'],
                            'dni_sin_validar'=>$row['dni_sin_validar'],
                            'registrado_sin_dni'=>$row['registrado_sin_dni'],
                            'total_grados'=>$row['total_grados'],
                            'total_secciones'=>$row['total_secciones'],
                            'nominas_generadas'=>$row['nominas_generadas'],
                            'nominas_aprobadas'=>$row['nominas_aprobadas'],
                            'nominas_por_rectificar'=>$row['nominas_por_rectificar'],                            
                            'primer_nivel_hombre'=>$row['primer_grado_hombre'],
                            'primer_nivel_mujer'=>$row['primer_grado_mujer'],
                            'segundo_nivel_hombre'=>$row['segundo_grado_hombre'],
                            'segundo_nivel_mujer'=>$row['segundo_grado_mujer'],
                            'tercero_nivel_hombre'=>$row['tercer_grado_hombre'],
                            'tercero_nivel_mujer'=>$row['tercer_grado_mujer'],
                            'cuarto_nivel_hombre'=>$row['cuarto_grado_hombre'],
                            'cuarto_nivel_mujer'=>$row['cuarto_grado_mujer'],
                            'quinto_nivel_hombre'=>$row['quinto_grado_hombre'],
                            'quinto_nivel_mujer'=>$row['quinto_grado_mujer']                            
                        ]);
                    }
                    
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    public function guardar_EBE($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $institucion_educativa = InstitucionEducativaRepositorio::InstitucionEducativa_porCodModular($row['cod_mod'])->first();
                    if($institucion_educativa!=null)
                    {
                        $MatriculaDetalle = MatriculaDetalle::Create([
                      
                            'matricula_id'=>$matricula_id,                        
                            'institucioneducativa_id'=>$institucion_educativa->id,
                            'nivel'=>'E',
                            'total_estudiantes_matriculados'=>$row['total_estudiantes_matriculados_ebe'],
                            'matricula_definitiva'=>$row['matricula_definitiva'],
                            'matricula_en_proceso'=>$row['matricula_en_proceso'],
                            'dni_validado'=>$row['dni_validado'],
                            'dni_sin_validar'=>$row['dni_sin_validar'],
                            'registrado_sin_dni'=>$row['registrado_sin_dni'],
                            'total_grados'=>$row['total_grados'],
                            'total_secciones'=>$row['total_secciones'],
                            'nominas_generadas'=>$row['nominas_generadas'],
                            'nominas_aprobadas'=>$row['nominas_aprobadas'],
                            'nominas_por_rectificar'=>$row['nominas_por_rectificar'],  

                            'primer_nivel_hombre'=>$row['primer_grado_hombre'],
                            'primer_nivel_mujer'=>$row['primer_grado_mujer'],
                            'segundo_nivel_hombre'=>$row['segundo_grado_hombre'],
                            'segundo_nivel_mujer'=>$row['segundo_grado_mujer'],
                            'tercero_nivel_hombre'=>$row['tercer_grado_hombre'],
                            'tercero_nivel_mujer'=>$row['tercer_grado_mujer'],
                            'cuarto_nivel_hombre'=>$row['cuarto_grado_hombre'],
                            'cuarto_nivel_mujer'=>$row['cuarto_grado_mujer'],
                            'quinto_nivel_hombre'=>$row['quinto_grado_hombre'],
                            'quinto_nivel_mujer'=>$row['quinto_grado_mujer'],
                            'sexto_nivel_hombre'=>$row['sexto_grado_hombre'],
                            'sexto_nivel_mujer'=>$row['sexto_grado_mujer'],
                            
                            'tres_anios_hombre_ebe'=>$row['tres_anios_hombre'],
                            'tres_anios_mujer_ebe'=>$row['tres_anios_mujer'],
                            'cuatro_anios_hombre_ebe'=>$row['cuatro_anios_hombre'],
                            'cuatro_anios_mujer_ebe'=>$row['cuatro_anios_mujer'],
                            'cinco_anios_hombre_ebe'=>$row['cinco_anios_hombre'],
                            'cinco_anios_mujer_ebe'=>$row['cinco_anios_mujer'],
                    
                        ]);
                    }
                    
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        // $Lista = CensoRepositorio::Listar_Por_Importacion_id($importacion_id);
                
        // return  datatables()->of($Lista)->toJson();;
    }
    
    public function ListaImportada($importacion_id)
    {
        $datos_matricula_importada = $this->datos_matricula_importada($importacion_id);  
        return view('Educacion.Matricula.ListaImportada',compact('importacion_id','datos_matricula_importada'));
    }

    public function aprobar($importacion_id)
    {  
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);     
 
        $datos_matricula_importada = $this->datos_matricula_importada($importacion_id);

        return view('educacion.Matricula.Aprobar',compact('importacion_id','importacion','datos_matricula_importada'));
    } 

    public function datos_matricula_importada($importacion_id)
    {
        $matricula = MatriculaRepositorio::matricula_porImportacion($importacion_id);        
        return MatriculaRepositorio::datos_matricula_importada($matricula->first()->id);
    }

    public function procesar($importacion_id)
    {
        $importacion  = Importacion::find($importacion_id);

        $importacion->estado = 'PR';    
        $importacion->usuarioId_Aprueba = auth()->user()->id;    
        $importacion->save();

        $this->elimina_mismaFecha($importacion->fechaActualizacion,$importacion->fuenteImportacion_id,$importacion_id);

        $matricula = MatriculaRepositorio :: matricula_porImportacion($importacion_id)->first();
        $matricula->estado = 'PR';
        $matricula->save();

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

    /************************ConsolidadoAnual********************************/
    public function importarConsolidadoAnual()
    {  
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();
        
        return view('Educacion.Matricula.ImportarConsolidadoAnual',compact('mensaje','anios'));
    } 

    public function guardarConsolidadoAnual(Request $request)
    { 
        $this->validate($request,['fileInicial' => 'required|mimes:xls,xlsx']);    
        $archivoInicial = $request->file('fileInicial');
        $arrayInicial = (new tablaXImport )-> toArray($archivoInicial);  

        $this->validate($request,['filePrimaria' => 'required|mimes:xls,xlsx']);    
        $archivoPrimaria = $request->file('filePrimaria');
        $arrayPrimaria = (new tablaXImport )-> toArray($archivoPrimaria); 

        $this->validate($request,['fileSecundaria' => 'required|mimes:xls,xlsx']);    
        $archivoSecundaria = $request->file('fileSecundaria');
        $arraySecundaria = (new tablaXImport )-> toArray($archivoSecundaria); 

        $anios = Anio::orderBy('anio', 'desc')->get();

        // VALIDACION DE LOS FORMATOS DE LOS 03 NIVELES
        $i = 0;
        $cadena ='';
       
        try{
            foreach ($arrayInicial as $key => $value) {
                foreach ($value as $row) {
                   if(++$i > 1) break;
                   $cadena =  $cadena 

                   .$row['dreu'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito']
                    .$row['centro_poblado'].$row['cod_mod'].$row['nombreie'].$row['nivel'].$row['modalidad']
                    .$row['tipo_ie'].$row['total_grados'].$row['total_secciones'].$row['actas_generadas_regular']
                    .$row['actas_aprobadas_regular'].$row['actas_rectificar_regular'].$row['estado_fase_regular']
                    .$row['estado_anio_escolar'].$row['total_estud_matriculados'].$row['cero_nivel_concluyeron']
                    .$row['cero_nivel_trasladado'].$row['cero_nivel_retirados'].$row['primer_nivel_aprobados']
                    .$row['primer_nivel_trasladados'].$row['primer_nivel_retirados'].$row['segundo_nivel_aprobados']
                    .$row['segundo_nivel_trasladados'].$row['segundo_nivel_retirados'].$row['tercer_nivel_aprobados']
                    .$row['tercer_nivel_trasladados'].$row['tercer_nivel_retirados'].$row['cuarto_nivel_aprobados']
                    .$row['cuarto_nivel_trasladados'].$row['cuarto_nivel_retirados'].$row['quinto_nivel_aprobados']
                    .$row['quinto_nivel_trasladados'].$row['quinto_nivel_retirados'].$row['sexto_nivel_aprobados']
                    .$row['sexto_nivel_trasladados'].$row['sexto_nivel_retirados'];

                }
            }
        }catch (Exception $e) {
           $mensaje = "Formato de archivo Nivel Inicial no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
           return  view('Educacion.Matricula.ImportarConsolidadoAnual',compact('mensaje','anios'));            
        }       
        
        $i = 0;
        $cadena ='';

        try{
             foreach ($arrayPrimaria as $key => $value) {
                 foreach ($value as $row) {
                    if(++$i > 1) break;
                    $cadena =  $cadena
                   
                    .$row['dreu'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito']
                    .$row['centro_poblado'].$row['cod_mod'].$row['nombreie'].$row['nivel'].$row['modalidad']
                    .$row['tipo_ie'].$row['total_grados'].$row['total_secciones'].$row['actas_generadas_regular']
                    .$row['actas_aprobadas_regular'].$row['actas_rectificar_regular'].$row['estado_fase_regular']
                    .$row['actas_generadas_recup'].$row['actas_aprobadas_recup'].$row['actas_por_rectificar_recup']
                    .$row['estado_fase_recuperacion'].$row['estado_anio_escolar'].$row['total_estud_matriculados']
                    .$row['primer_nivel_aprobados'].$row['primer_nivel_trasladados'].$row['primer_nivel_retirados']
                    .$row['primer_nivel_requieren_recup'].$row['primer_nivel_desaprobados'].$row['segundo_nivel_aprobados']
                    .$row['segundo_nivel_trasladados'].$row['segundo_nivel_retirados'].$row['segundo_nivel_requieren_recup']
                    .$row['segundo_nivel_desaprobados'].$row['tercer_nivel_aprobados'].$row['tercer_nivel_trasladados']
                    .$row['tercer_nivel_retirados'].$row['tercer_nivel_requieren_recup'].$row['tercer_nivel_desaprobados']
                    .$row['cuarto_nivel_aprobados'].$row['cuarto_nivel_trasladados'].$row['cuarto_nivel_retirados']
                    .$row['cuarto_nivel_requieren_recup'].$row['cuarto_nivel_desaprobados'].$row['quinto_nivel_aprobados']
                    .$row['quinto_nivel_trasladados'].$row['quinto_nivel_retirados'].$row['quinto_nivel_requieren_recup']
                    .$row['quinto_nivel_desaprobados'].$row['sexto_nivel_aprobados'].$row['sexto_nivel_trasladados']
                    .$row['sexto_nivel_retirados'].$row['sexto_nivel_requieren_recup'].$row['sexto_nivel_desaprobados'];

                }
             }
        }catch (Exception $e) {
            $mensaje = "Formato de archivo Nivel Primaria no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.Matricula.ImportarConsolidadoAnual',compact('mensaje','anios'));            
        }  
        
        $i = 0;
        $cadena ='';

        try{
             foreach ($arraySecundaria as $key => $value) {
                 foreach ($value as $row) {
                    if(++$i > 1) break;
                    $cadena =  $cadena
                    .$row['dreu'].$row['ugel'].$row['departamento'].$row['provincia'].$row['distrito']
                    .$row['centro_poblado'].$row['cod_mod'].$row['nombreie'].$row['nivel'].$row['modalidad']
                    .$row['tipo_ie'].$row['total_grados'].$row['total_secciones'].$row['actas_generadas_regular']
                    .$row['actas_aprobadas_regular'].$row['actas_rectificar_regular'].$row['estado_fase_regular']
                    .$row['actas_generadas_recup'].$row['actas_aprobadas_recup'].$row['actas_por_rectificar_recup']
                    .$row['estado_fase_recuperacion'].$row['estado_anio_escolar'].$row['total_estud_matriculados']
                    .$row['primer_nivel_aprobados'].$row['primer_nivel_trasladados'].$row['primer_nivel_retirados']
                    .$row['primer_nivel_requieren_recup'].$row['primer_nivel_desaprobados'].$row['segundo_nivel_aprobados']
                    .$row['segundo_nivel_trasladados'].$row['segundo_nivel_retirados'].$row['segundo_nivel_requieren_recup']
                    .$row['segundo_nivel_desaprobados'].$row['tercer_nivel_aprobados'].$row['tercer_nivel_trasladados']
                    .$row['tercer_nivel_retirados'].$row['tercer_nivel_requieren_recup'].$row['tercer_nivel_desaprobados']
                    .$row['cuarto_nivel_aprobados'].$row['cuarto_nivel_trasladados'].$row['cuarto_nivel_retirados']
                    .$row['cuarto_nivel_requieren_recup'].$row['cuarto_nivel_desaprobados'].$row['quinto_nivel_aprobados']
                    .$row['quinto_nivel_trasladados'].$row['quinto_nivel_retirados'].$row['quinto_nivel_requieren_recup']
                    .$row['quinto_nivel_desaprobados'];            
                }
             }
        }catch (Exception $e) {
            $mensaje = "Formato de archivo Nivel Secundaria no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.Matricula.ImportarConsolidadoAnual',compact('mensaje','anios'));            
        } 
        
        // FIN VALIDACION DE LOS FORMATOS DE LOS 04 NIVELES

        $existemMismoAnio = MatriculaRepositorio :: busca_ConsolidadoAnual_segunAnio($request['anio']);

        if( $existemMismoAnio->count()>=1)
        {
            $mensaje = "Error, existe un archivo con estado ".$existemMismoAnio->first()->estado." para el año seleccionado";          
            return view('Educacion.Matricula.ImportarConsolidadoAnual',compact('mensaje','anios'));            
        }
        else
        {
            $creacionExitosa = 1;

            try{
                $importacion = Importacion::Create([
                    'fuenteImportacion_id'=>10, // valor predeterminado
                    'usuarioId_Crea'=> auth()->user()->id,
                    'usuarioId_Aprueba'=>null,
                    'fechaActualizacion'=>$request['fechaActualizacion'],
                    'comentario'=>$request['comentario'],
                    'estado'=>'PE'
                  ]); 
    
                $Matricula = MatriculaAnual::Create([
                    'importacion_id'=>$importacion->id, // valor predeterminado
                    'anio_id'=> $request['anio'],
                    'estado'=>'PE'
                  ]); 
               
            }catch (Exception $e) {
                $creacionExitosa = 0;
            }
            
            $mensajeNivel = "";
    
            if($creacionExitosa==1)
            {
                $creacionExitosa = $this->guardar_inicial_anual($arrayInicial,$Matricula->id);
    
                if($creacionExitosa==1)
                {
                    $creacionExitosa = $this->guardar_primaria_anual($arrayPrimaria,$Matricula->id);
                    if($creacionExitosa==1)
                    {
                        $creacionExitosa = $this->guardar_secundaria_anual($arraySecundaria,$Matricula->id);
                        if($creacionExitosa==0)
                        {
                            $mensajeNivel = "Nivel SECUNDARIA";  
                        }
                    }
                    else
                    {
                        $mensajeNivel = "Nivel PRIMARIA";  
                    }
                }
                else
                { 
                    $mensajeNivel ="Nivel INICIAL";
                }
            }
    
            if($creacionExitosa==0)
            {
                $importacion->estado = 'EL';
                $importacion->save();
    
                $Matricula->estado = 'EL';
                $Matricula->save();
    
                $mensaje = "Error en la carga de ".$mensajeNivel.", verifique los datos de su archivo y/o comuniquese con el administrador del sistema";          
                return view('Educacion.Matricula.ImportarConsolidadoAnual',compact('mensaje','anios'));
            }
           
            return redirect()->route('Matricula.Matricula_Lista_ConsolidadoAnual',$importacion->id);
        }       
    }

    public function ListaImportada_ConsolidadoAnual($importacion_id)
    {
        $datos_matricula_importada = $this->datos_matricula_importada_ConsolidadoAnual($importacion_id);  
        return view('Educacion.Matricula.ListaImportada',compact('importacion_id','datos_matricula_importada'));
    }

    public function aprobarConsolidadoAnual($importacion_id)
    {  
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);     

        $matricula = MatriculaRepositorio::matricula_porImportacion_ConsolidadoAnual($importacion_id);
 
        $datos_matricula_importada = $this->datos_matricula_importada_ConsolidadoAnual($importacion_id);    
  
        $anio_matricula = MatriculaRepositorio::anio_matricula_importada_ConsolidadoAnual($matricula->first()->id)->first()->anio;  

        //return $anio_matricula;

        return view('educacion.Matricula.AprobarConsolidadoAnual',compact('importacion_id','importacion','datos_matricula_importada','anio_matricula'));
    } 

    public function datos_matricula_importada_ConsolidadoAnual($importacion_id)
    {           
        return MatriculaRepositorio::datos_matricula_importada_ConsolidadoAnual($importacion_id);
    }

    public function procesarConsolidadoAnual($importacion_id)
    {
        $importacion  = Importacion::find($importacion_id);

        $importacion->estado = 'PR';    
        $importacion->usuarioId_Aprueba = auth()->user()->id;    
        $importacion->save();

        //$this->elimina_mismaFecha($importacion->fechaActualizacion,$importacion->fuenteImportacion_id,$importacion_id);

        $matricula = MatriculaRepositorio :: matricula_porImportacion_ConsolidadoAnual($importacion_id)->first();
        $matricula->estado = 'PR';
        $matricula->save();

        return view('correcto');
    }

    public function guardar_inicial_anual($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $MatriculaDetalle = MatriculaAnualDetalle::Create([
                    
                        'matricula_anual_id'=>$matricula_id,  
                        'nivel'=>'I',
                        'dreu'=>$row['dreu'],
                        'ugel'=>$row['ugel'],
                        'departamento'=>$row['departamento'],
                        'provincia'=>$row['provincia'],
                        'distrito'=>$row['distrito'],
                        'centro_poblado'=>$row['centro_poblado'],
                        'cod_mod'=>$row['cod_mod'],
                        'nombreIE'=>$row['nombreie'],
                        'nivel_especifico'=>$row['nivel'],
                        'modalidad'=>$row['modalidad'],
                        'tipo_ie'=>$row['tipo_ie'],
                        'total_grados'=>$row['total_grados'],
                        'total_secciones'=>$row['total_secciones'],
                        'actas_generadas_regular'=>$row['actas_generadas_regular'],
                        'actas_aprobadas_regular'=>$row['actas_aprobadas_regular'],
                        'actas_rectificar_regular'=>$row['actas_rectificar_regular'],
                        'estado_fase_regular'=>$row['estado_fase_regular'],
                        'estado_anio_escolar'=>$row['estado_anio_escolar'],
                        'total_estud_matriculados'=>$row['total_estud_matriculados'],
                        'cero_nivel_concluyeron'=>$row['cero_nivel_concluyeron'],
                        'cero_nivel_trasladado'=>$row['cero_nivel_trasladado'],
                        'cero_nivel_retirados'=>$row['cero_nivel_retirados'],
                        'primer_nivel_aprobados'=>$row['primer_nivel_aprobados'],
                        'primer_nivel_trasladados'=>$row['primer_nivel_trasladados'],
                        'primer_nivel_retirados'=>$row['primer_nivel_retirados'],
                        'segundo_nivel_aprobados'=>$row['segundo_nivel_aprobados'],
                        'segundo_nivel_trasladados'=>$row['segundo_nivel_trasladados'],
                        'segundo_nivel_retirados'=>$row['segundo_nivel_retirados'],
                        'tercer_nivel_aprobados'=>$row['tercer_nivel_aprobados'],
                        'tercer_nivel_trasladados'=>$row['tercer_nivel_trasladados'],
                        'tercer_nivel_retirados'=>$row['tercer_nivel_retirados'],
                        'cuarto_nivel_aprobados'=>$row['cuarto_nivel_aprobados'],
                        'cuarto_nivel_trasladados'=>$row['cuarto_nivel_trasladados'],
                        'cuarto_nivel_retirados'=>$row['cuarto_nivel_retirados'],
                        'quinto_nivel_aprobados'=>$row['quinto_nivel_aprobados'],
                        'quinto_nivel_trasladados'=>$row['quinto_nivel_trasladados'],
                        'quinto_nivel_retirados'=>$row['quinto_nivel_retirados'],
                        'sexto_nivel_aprobados'=>$row['sexto_nivel_aprobados'],
                        'sexto_nivel_trasladados'=>$row['sexto_nivel_trasladados'],
                        'sexto_nivel_retirados'=>$row['sexto_nivel_retirados'],
        
                    ]);                    
                    
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    public function guardar_primaria_anual($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $MatriculaDetalle = MatriculaAnualDetalle::Create([
                    
                        'matricula_anual_id'=>$matricula_id,  
                        'nivel'=>'P',                        
                        'dreu'=>$row['dreu'],
                        'ugel'=>$row['ugel'],
                        'departamento'=>$row['departamento'],
                        'provincia'=>$row['provincia'],
                        'distrito'=>$row['distrito'],
                        'centro_poblado'=>$row['centro_poblado'],
                        'cod_mod'=>$row['cod_mod'],
                        'nombreIE'=>$row['nombreie'],
                        'nivel_especifico'=>$row['nivel'],
                        'modalidad'=>$row['modalidad'],
                        'tipo_ie'=>$row['tipo_ie'],
                        'total_grados'=>$row['total_grados'],
                        'total_secciones'=>$row['total_secciones'],
                        'actas_generadas_regular'=>$row['actas_generadas_regular'],
                        'actas_aprobadas_regular'=>$row['actas_aprobadas_regular'],
                        'actas_rectificar_regular'=>$row['actas_rectificar_regular'],
                        'estado_fase_regular'=>$row['estado_fase_regular'],
                        'actas_generadas_recup'=>$row['actas_generadas_recup'],
                        'actas_aprobadas_recup'=>$row['actas_aprobadas_recup'],
                        'actas_por_rectificar_recup'=>$row['actas_por_rectificar_recup'],
                        'estado_fase_recuperacion'=>$row['estado_fase_recuperacion'],
                        'estado_anio_escolar'=>$row['estado_anio_escolar'],
                        'total_estud_matriculados'=>$row['total_estud_matriculados'],
                        'primer_nivel_aprobados'=>$row['primer_nivel_aprobados'],
                        'primer_nivel_trasladados'=>$row['primer_nivel_trasladados'],
                        'primer_nivel_retirados'=>$row['primer_nivel_retirados'],
                        'primer_nivel_requieren_recup'=>$row['primer_nivel_requieren_recup'],
                        'primer_nivel_desaprobados'=>$row['primer_nivel_desaprobados'],
                        'segundo_nivel_aprobados'=>$row['segundo_nivel_aprobados'],
                        'segundo_nivel_trasladados'=>$row['segundo_nivel_trasladados'],
                        'segundo_nivel_retirados'=>$row['segundo_nivel_retirados'],
                        'segundo_nivel_requieren_recup'=>$row['segundo_nivel_requieren_recup'],
                        'segundo_nivel_desaprobados'=>$row['segundo_nivel_desaprobados'],
                        'tercer_nivel_aprobados'=>$row['tercer_nivel_aprobados'],
                        'tercer_nivel_trasladados'=>$row['tercer_nivel_trasladados'],
                        'tercer_nivel_retirados'=>$row['tercer_nivel_retirados'],
                        'tercer_nivel_requieren_recup'=>$row['tercer_nivel_requieren_recup'],
                        'tercer_nivel_desaprobados'=>$row['tercer_nivel_desaprobados'],
                        'cuarto_nivel_aprobados'=>$row['cuarto_nivel_aprobados'],
                        'cuarto_nivel_trasladados'=>$row['cuarto_nivel_trasladados'],
                        'cuarto_nivel_retirados'=>$row['cuarto_nivel_retirados'],
                        'cuarto_nivel_requieren_recup'=>$row['cuarto_nivel_requieren_recup'],
                        'cuarto_nivel_desaprobados'=>$row['cuarto_nivel_desaprobados'],
                        'quinto_nivel_aprobados'=>$row['quinto_nivel_aprobados'],
                        'quinto_nivel_trasladados'=>$row['quinto_nivel_trasladados'],
                        'quinto_nivel_retirados'=>$row['quinto_nivel_retirados'],
                        'quinto_nivel_requieren_recup'=>$row['quinto_nivel_requieren_recup'],
                        'quinto_nivel_desaprobados'=>$row['quinto_nivel_desaprobados'],
                        'sexto_nivel_aprobados'=>$row['sexto_nivel_aprobados'],
                        'sexto_nivel_trasladados'=>$row['sexto_nivel_trasladados'],
                        'sexto_nivel_retirados'=>$row['sexto_nivel_retirados'],
                        'sexto_nivel_requieren_recup'=>$row['sexto_nivel_requieren_recup'],
                        'sexto_nivel_desaprobados'=>$row['sexto_nivel_desaprobados'],                        
        
                    ]); 
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    public function guardar_secundaria_anual($array,$matricula_id)
    {
        $creacionExitosa = 1;

        try{
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                   
                    $MatriculaDetalle = MatriculaAnualDetalle::Create([
                    
                        'matricula_anual_id'=>$matricula_id,  
                        'nivel'=>'S',                        
                        'dreu'=>$row['dreu'],
                        'ugel'=>$row['ugel'],
                        'departamento'=>$row['departamento'],
                        'provincia'=>$row['provincia'],
                        'distrito'=>$row['distrito'],
                        'centro_poblado'=>$row['centro_poblado'],
                        'cod_mod'=>$row['cod_mod'],
                        'nombreIE'=>$row['nombreie'],
                        'nivel_especifico'=>$row['nivel'],
                        'modalidad'=>$row['modalidad'],
                        'tipo_ie'=>$row['tipo_ie'],
                        'total_grados'=>$row['total_grados'],
                        'total_secciones'=>$row['total_secciones'],
                        'actas_generadas_regular'=>$row['actas_generadas_regular'],
                        'actas_aprobadas_regular'=>$row['actas_aprobadas_regular'],
                        'actas_rectificar_regular'=>$row['actas_rectificar_regular'],
                        'estado_fase_regular'=>$row['estado_fase_regular'],
                        'actas_generadas_recup'=>$row['actas_generadas_recup'],
                        'actas_aprobadas_recup'=>$row['actas_aprobadas_recup'],
                        'actas_por_rectificar_recup'=>$row['actas_por_rectificar_recup'],
                        'estado_fase_recuperacion'=>$row['estado_fase_recuperacion'],
                        'estado_anio_escolar'=>$row['estado_anio_escolar'],
                        'total_estud_matriculados'=>$row['total_estud_matriculados'],
                        'primer_nivel_aprobados'=>$row['primer_nivel_aprobados'],
                        'primer_nivel_trasladados'=>$row['primer_nivel_trasladados'],
                        'primer_nivel_retirados'=>$row['primer_nivel_retirados'],
                        'primer_nivel_requieren_recup'=>$row['primer_nivel_requieren_recup'],
                        'primer_nivel_desaprobados'=>$row['primer_nivel_desaprobados'],
                        'segundo_nivel_aprobados'=>$row['segundo_nivel_aprobados'],
                        'segundo_nivel_trasladados'=>$row['segundo_nivel_trasladados'],
                        'segundo_nivel_retirados'=>$row['segundo_nivel_retirados'],
                        'segundo_nivel_requieren_recup'=>$row['segundo_nivel_requieren_recup'],
                        'segundo_nivel_desaprobados'=>$row['segundo_nivel_desaprobados'],
                        'tercer_nivel_aprobados'=>$row['tercer_nivel_aprobados'],
                        'tercer_nivel_trasladados'=>$row['tercer_nivel_trasladados'],
                        'tercer_nivel_retirados'=>$row['tercer_nivel_retirados'],
                        'tercer_nivel_requieren_recup'=>$row['tercer_nivel_requieren_recup'],
                        'tercer_nivel_desaprobados'=>$row['tercer_nivel_desaprobados'],
                        'cuarto_nivel_aprobados'=>$row['cuarto_nivel_aprobados'],
                        'cuarto_nivel_trasladados'=>$row['cuarto_nivel_trasladados'],
                        'cuarto_nivel_retirados'=>$row['cuarto_nivel_retirados'],
                        'cuarto_nivel_requieren_recup'=>$row['cuarto_nivel_requieren_recup'],
                        'cuarto_nivel_desaprobados'=>$row['cuarto_nivel_desaprobados'],
                        'quinto_nivel_aprobados'=>$row['quinto_nivel_aprobados'],
                        'quinto_nivel_trasladados'=>$row['quinto_nivel_trasladados'],
                        'quinto_nivel_retirados'=>$row['quinto_nivel_retirados'],
                        'quinto_nivel_requieren_recup'=>$row['quinto_nivel_requieren_recup'],
                        'quinto_nivel_desaprobados'=>$row['quinto_nivel_desaprobados'],                     
        
                    ]); 
                }
            }
        }catch (Exception $e) {            
             $creacionExitosa = 0;            
        }
       
        return $creacionExitosa;
    }

    //**********************************MATRICULA FECHAS EBR ****************************************************** */
    public function principal()
    {
        // EBR tipo = 1
        return $this->presentacion(1);
    }

    public function principal_EIB()
    {        
        // EIB tipo = 2
        return $this->presentacion(2);
    }

    public function principal_EBE()
    {        
        // EIB tipo = 2
        return $this->presentacion(3);
    }

    public function presentacion($tipo)
    {       
        $matricula = MatriculaRepositorio :: matricula_mas_actual()->first();
        $anios =  MatriculaRepositorio ::matriculas_anio( );

        $fechas_matriculas = MatriculaRepositorio ::fechas_matriculas_anio($anios->first()->id);

        return view('educacion.Matricula.Principal',compact('matricula','anios','fechas_matriculas','tipo'));  
    }
    
    public function inicio($matricula_id,$gestion,$tipo)
    {
        $tipoDescrip = '';
        if($tipo == 1){
            $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBR($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));
            $lista_total_matricula_EBR_porUgeles = MatriculaRepositorio::total_matricula_EBR_porUgeles($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
            $tipoDescrip = 'EBR';
        }
        else{
            if($tipo == 2){
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EIB($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));
                $lista_total_matricula_EBR_porUgeles = MatriculaRepositorio::total_matricula_EIB_porUgeles($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
                $tipoDescrip = 'EIB';
            }
            else
            {
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBE($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));
                $lista_total_matricula_EBR_porUgeles = MatriculaRepositorio::total_matricula_EBE_porUgeles($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
                $tipoDescrip = 'EBE';

            }
        }

        
        $fecha_Matricula_texto = $this->fecha_texto($matricula_id); 

        $totalMatriculados = 0;
        foreach($lista_total_matricula_EBR as $item)
        {
            $totalMatriculados+= ($item->hombres + $item->mujeres);
        }
   
        return view('educacion.Matricula.inicio',compact('tipoDescrip','lista_total_matricula_EBR','lista_total_matricula_EBR_porUgeles','fecha_Matricula_texto','totalMatriculados'));
    }

    public function detalle()
    {
        $matricula = MatriculaRepositorio :: matricula_mas_actual()->first();
        $anios =  MatriculaRepositorio ::matriculas_anio( );

        $fechas_matriculas = MatriculaRepositorio ::fechas_matriculas_anio($anios->first()->id);

        return view('educacion.Matricula.detalle',compact('matricula','anios','fechas_matriculas'));
    }
    
    public function reporteUgel($anio_id,$matricula_id,$gestion,$tipo)
    { 
        $tipoDescrip = '';
        if($tipo == 1){
            $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBR($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));      
            $lista_matricula = MatriculaRepositorio::total_matricula_por_Nivel($matricula_id);
            $tipoDescrip = 'EBR';
        }
        else{
            if($tipo == 2){
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EIB($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));      
                $lista_matricula = MatriculaRepositorio::total_matricula_por_Nivel_EIB($matricula_id);
                $tipoDescrip = 'EIB';
        }
            else
            {
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBE($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));      
                $lista_matricula = MatriculaRepositorio::total_matricula_por_Nivel($matricula_id);
                $tipoDescrip = 'EBE';
            }
        }


        $lista_total_matricula_Inicial = $lista_matricula->where('nivel', 'I')->all();    
        $lista_total_matricula_Primaria = $lista_matricula->where('nivel', 'P')->all();  
        $lista_total_matricula_Secundaria = $lista_matricula->where('nivel', 'S')->all();
        $lista_total_matricula_EBE = $lista_matricula->where('nivel', 'E')->all();

        $puntos = [];        
        $total = 0;

        foreach ($lista_total_matricula_EBR as $key => $lista) {
            $total = $total  + $lista->hombres  + $lista->mujeres;
        }
        //->sortByDesc('hombres') solo para dar una variacion a los colores del grafico
        foreach ($lista_total_matricula_EBR->sortByDesc('hombres') as $key => $lista) {
            $puntos[] = ['name'=>$lista->nombre, 'y'=>floatval(($lista->hombres  + $lista->mujeres)*100/$total)];
        }

        $contenedor = 'resumen_por_ugel';//nombre del contenedor para el grafico          
        $fecha_Matricula_texto = $this->fecha_texto($matricula_id);        
        $titulo_grafico = 'Total Matricula '.$tipoDescrip .' al '.$fecha_Matricula_texto;  

        return view('educacion.Matricula.ReporteUgel',["dataCircular"=> json_encode($puntos)],compact('tipoDescrip','lista_total_matricula_EBR','lista_total_matricula_Secundaria',
                    'lista_total_matricula_Primaria','lista_total_matricula_Inicial','lista_total_matricula_EBE','contenedor','titulo_grafico','fecha_Matricula_texto'));
    }

    public function GraficoBarras_MatriculaUgel($matricula_id)
    {
        $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBR($matricula_id,'whereNotIn',0);
      

        /************* GRAFICO BARRAS*******************/
         $categoria_nombres=[];        
         $recorre = 1; 

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($lista_total_matricula_EBR as $key => $lista) {

            $data = [];    
            $data = array_merge($data,[intval ($lista->hombres  + $lista->mujeres) ]);  
            // $data = array_merge($data,[intval($lista->noTitulados)]); 

            $puntos[] = [ 'name'=> $lista->nombre ,'data'=>  $data];
        } 

        $categoria_nombres[] = 'UGEL';  

        
        $titulo = 'MATRICULADOS EBR POR UGELES';
        $subTitulo = 'Fuente: SIAGIE - MINEDU';
        $titulo_y = 'Numero de Matriculados';

        $nombreGraficoBarra = 'barraMatriculaUgel';// este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Barra',["data"=> json_encode($puntos),"categoria_nombres"=> json_encode($categoria_nombres)],
        compact( 'titulo_y','titulo','subTitulo','nombreGraficoBarra'));
    }

    public function GraficoBarras_MatriculaTipoGestion($matricula_id)
    {
        $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBR_porTipoGestion($matricula_id);      

        /************* GRAFICO BARRAS*******************/
         $categoria_nombres=[];        
         $recorre = 1; 

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($lista_total_matricula_EBR as $key => $lista) {

            $data = [];    
            $data = array_merge($data,[intval ($lista->cantidad) ]);  
            // $data = array_merge($data,[intval($lista->noTitulados)]); 

            $puntos[] = [ 'name'=> $lista->tipGes ,'data'=>  $data];
        } 

        $categoria_nombres[] = 'TIPO DE GESTION';  

        
        $titulo = 'MATRICULADOS EBR POR TIPO DE GESTION';
        $subTitulo = 'Fuente: SIAGIE - MINEDU';
        $titulo_y = 'Numero de Matriculados';

        $nombreGraficoBarra = 'barraMatriculaTipoGestion';// este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Barra',["data"=> json_encode($puntos),"categoria_nombres"=> json_encode($categoria_nombres)],
        compact( 'titulo_y','titulo','subTitulo','nombreGraficoBarra'));
    }

    public function reporteDistrito($anio_id,$matricula_id,$gestion,$tipo)
    {  
        $tipoDescrip = '';
        if($tipo == 1){
            $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBR_Provincia($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
            $lista_matricula = MatriculaRepositorio::total_matricula_por_Nivel_Distrito($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));   
            $lista_total_matricula = MatriculaRepositorio::total_matricula_por_Nivel_Provincia($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
            
            $tipoDescrip = 'EBR';
        }
        else{
            if($tipo == 2){
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EIB_Provincia($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
                $lista_matricula = MatriculaRepositorio::total_matricula_por_Nivel_Distrito_EIB($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));   
                $lista_total_matricula = MatriculaRepositorio::total_matricula_por_Nivel_Provincia_EIB($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
                
                $tipoDescrip = 'EIB';
            }
            else{
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_EBE_Provincia($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
                $lista_matricula = MatriculaRepositorio::total_matricula_por_Nivel_Distrito($matricula_id,$this->condicion_filtro($gestion),$this->valor_filtro($gestion));   
                $lista_total_matricula = MatriculaRepositorio::total_matricula_por_Nivel_Provincia($matricula_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
                
                $tipoDescrip = 'EBE';

            }

        }

       
        $lista_total_matricula_Inicial = $lista_matricula->where('nivel', 'I')->all();    
        $lista_total_matricula_Primaria = $lista_matricula->where('nivel', 'P')->all();  
        $lista_total_matricula_Secundaria = $lista_matricula->where('nivel', 'S')->all();       

        // cabeceras y/o totales en las tablas
        
        $lista_matricula_Inicial_cabecera =  $lista_total_matricula->where('nivel', 'I')->all();  
        $lista_matricula_Primaria_cabecera =  $lista_total_matricula->where('nivel', 'P')->all();
        $lista_matricula_Secundaria_cabecera =  $lista_total_matricula->where('nivel', 'S')->all();

        $puntos = [];
        $total = 0;
        foreach ($lista_total_matricula_EBR as $key => $lista) {
            $total = $total  + $lista->hombres  + $lista->mujeres;
        }
        //->sortByDesc('hombres') solo para dar una variacion a los colores del grafico
        foreach ($lista_total_matricula_EBR->sortByDesc('hombres') as $key => $lista) {
            $puntos[] = ['name'=>$lista->provincia, 'y'=>floatval(($lista->hombres  + $lista->mujeres)*100/$total)];
        }

        $fecha_Matricula_texto = $this->fecha_texto($matricula_id);
        $contenedor = 'resumen_por_distrito';
        $titulo_grafico = 'Total Matricula '.$tipoDescrip.' al '.$fecha_Matricula_texto;  

        return view('educacion.Matricula.ReporteDistrito',["dataCircular"=> json_encode($puntos)],compact('tipoDescrip','lista_total_matricula_EBR','lista_total_matricula_Inicial','lista_total_matricula_Primaria',
        'lista_total_matricula_Secundaria','fecha_Matricula_texto','lista_matricula_Inicial_cabecera','lista_matricula_Primaria_cabecera',
        'lista_matricula_Secundaria_cabecera','contenedor','titulo_grafico'));
    }

    public function reporteInstitucion($anio_id,$matricula_id,$gestion,$tipo)
    {  
        $tipoDescrip = '';
        if($tipo == 1){
            $tipoDescrip = 'EBR';
        }
        else{      
            if($tipo == 1)           
                $tipoDescrip = 'EIB';
            else
                $tipoDescrip = 'EBE';
        }

        $fecha_Matricula_texto = $this->fecha_texto($matricula_id);
        return view('educacion.Matricula.ReporteInstitucion',compact('tipoDescrip','tipo','fecha_Matricula_texto','matricula_id','gestion'));
    }

    public function Institucion_DataTable($matricula_id,$nivel,$gestion,$tipo)
    {     
        if($tipo == 1){
            $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_por_Nivel_Institucion($matricula_id,$nivel,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
        }
        else{   
            if($tipo == 2)          
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_por_Nivel_Institucion_EIB($matricula_id,$nivel,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
            else
                $lista_total_matricula_EBR = MatriculaRepositorio::total_matricula_por_Nivel_Institucion($matricula_id,'E',$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
        }

        
        return  datatables()->of($lista_total_matricula_EBR)->toJson();
    }

    public function GraficoBarrasPrincipal($anio_id,$gestion,$tipo)
    {  
        $tipoDescrip = '';
        if($tipo == 1){
            $tipoDescrip = 'EBR';
            $total_matricula_anual = MatriculaRepositorio:: total_matricula_anual($anio_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
        }
        else{   
            if($tipo == 2)     
            {     
                $tipoDescrip = 'EIB';               
                $total_matricula_anual = MatriculaRepositorio:: total_matricula_anual_EIB($anio_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
            }
            else
            {
                $tipoDescrip = 'EBE';               
                $total_matricula_anual = MatriculaRepositorio:: total_matricula_anual_EBE($anio_id,$this->condicion_filtro_formato2($gestion),$this->valor_filtro($gestion));
       
            }
        
        }
        
        
        $categoria1 = [];
        $categoria2 = [];
        $categoria3 = [];
        $categoria4 = [];
        $categoria_nombres=[];
       
        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($total_matricula_anual as $key => $lista) {
            $categoria1 = array_merge($categoria1,[intval($lista->ugel10)]);
            $categoria2 = array_merge($categoria2,[intval($lista->ugel11)]);
            $categoria3 = array_merge($categoria3,[intval($lista->ugel12)]);
            $categoria4 = array_merge($categoria4,[intval($lista->ugel13)]);
            $categoria_nombres[] = Utilitario::fecha_formato_texto_diayMes($lista->fechaactualizacion);      
        } 

        $puntos[] = [ 'name'=>'Coronel Portillo' ,'data'=>  $categoria1];
        $puntos[] = [ 'name'=>'Atalaya', 'data'=> $categoria2];
        $puntos[] = [ 'name'=>'Padre Abad' ,'data'=>  $categoria3];
        $puntos[] = [ 'name'=>'Purus', 'data'=> $categoria4];

        $nombreAnio = Anio::find($anio_id)->anio;

        $titulo = 'Matriculas '.$tipoDescrip.' según UGEL -  '.$nombreAnio;
        $subTitulo = 'Fuente SIAGIE - MINEDU';
        $titulo_y = 'Numero de matriculados';
        $nombreGraficoBarra = 'barra1';// este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Barra',["data"=> json_encode($puntos),"categoria_nombres"=> json_encode($categoria_nombres)],
        compact( 'titulo_y','titulo','subTitulo','nombreGraficoBarra'));
    }

    public function fecha_texto($matricula_id)
    {
        $fecha_Matricula_texto = '--'; 
        $datosMatricula = MatriculaRepositorio::datos_matricula($matricula_id);

        if($datosMatricula->first()!=null)
            $fecha_Matricula_texto = Utilitario::fecha_formato_texto_completo($datosMatricula->first()->fechaactualizacion ); 
            
        return $fecha_Matricula_texto;
    }

    public function Fechas($anio_id)
    {
        $fechas_matriculas = MatriculaRepositorio ::fechas_matriculas_anio($anio_id);      
        return response()->json(compact('fechas_matriculas'));
    }

    public function condicion_filtro($gestion)
    {
        // este valor del filtro se ejecutara en la consulta dentro del repositorio
        // si se eleige como opcion privados realizará un where in con el valor id = 20
        $condicion ='';
       
        if($gestion==3)
        {
            $condicion ='whereIn';           
        }
        else
        {
            $condicion ='whereNotIn';
        }
        return  $condicion;
    }

    public function condicion_filtro_formato2($gestion)
    {
        // este valor del filtro se ejecutara en la consulta dentro del repositorio
        // si se eleige como opcion privados realizará un where in con el valor id = 20
        $condicion ='';
       
        if($gestion==3)
        {
            $condicion ='in';           
        }
        else
        {
            $condicion ='not in';
        }
        return  $condicion;
    }

    public function valor_filtro($gestion)
    {   

        $filtro=0;

        if($gestion==1)
        {           
            $filtro=0;
        }
        else
        {
            $filtro=20;
        }

        return  $filtro;
    }
    
    //********************************** MATRICULA ANUAL CONSOLIDADO ****************************************************** */

    public function principalConsolidadoAnual()
    {
        $matricula = MatriculaRepositorio :: matricula_mas_actual()->first();
        $anios =  MatriculaRepositorio ::matriculas_anio_ConsolidadoAnual( );

        return view('educacion.Matricula.PrincipalConsolidadoAnual',compact('matricula','anios'));  
    }    

    public function ReporteUgelConsolidadoAnual($anio_id,$gestion,$nivel)
    {
        $condicion = 'in';
        if($nivel=="T")
            $condicion = 'not in';

        $total_matricula_ComsolidadoAnual = MatriculaRepositorio :: total_matricula_ComsolidadoAnual(0,$condicion,$nivel,$this->filtro_gestion($gestion));
        $anioConsolidadoAnual = MatriculaRepositorio :: total_matricula_ComsolidadoAnual_porNivel_soloAnios(0,$condicion,$nivel,$this->filtro_gestion($gestion));
        $ugelConsolidadoAnual = MatriculaRepositorio :: total_matricula_ComsolidadoAnual_porNivel_soloUgel(0,$condicion,$nivel,$this->filtro_gestion($gestion));

        $total_matricula_ComsolidadoAnual_totalAnios = MatriculaRepositorio :: total_matricula_ComsolidadoAnual_totalAnios(0,$condicion,$nivel,$this->filtro_gestion($gestion));

        $descripcion_nivel = $this->descripcion_nivel($nivel);


        /************* GRAFICO A*******************/
               
        $data = [];
        $categoria_nombres=[];
        
        $recorre = 1;


        foreach($ugelConsolidadoAnual as $indice => $elemento)  
        {    
            $data = [];                                                      
            for($i=1 ; $i<=$anioConsolidadoAnual->count();$i++)   
            {
               $dato = $total_matricula_ComsolidadoAnual->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadTotal;
               $data = array_merge($data,[intval($dato)]);
            }
        
            $recorre+=1;
            $puntos[] = [ 'name'=> $elemento->ugel ,'data'=>  $data];                       
        }
      
        foreach( $anioConsolidadoAnual as $indice => $elemento)   
        {
            $categoria_nombres[] = $elemento->anio; 
        }

        // $nombreAnio = Anio::find($anio_id)->anio;

        $titulo = 'TOTAL ESTUDIANTES '.$descripcion_nivel;
        $subTitulo = 'Fuente SIAGIE - MINEDU';
        $titulo_y = 'Numero de Matriculados';
        $nombreGraficoBarra = 'barra1';// este nombre va de la mano con el nombre del DIV en la vista

        /********* FIN GRAFICO A *************/


        return view('educacion.Matricula.ReporteUgelConsolidadoAnual',
        ["data"=> json_encode($puntos),"categoria_nombres"=> json_encode($categoria_nombres)],
        compact('total_matricula_ComsolidadoAnual','total_matricula_ComsolidadoAnual_totalAnios','anioConsolidadoAnual','ugelConsolidadoAnual','descripcion_nivel',
        'titulo_y','titulo','subTitulo','nombreGraficoBarra'));
    }

    public function GraficoBarrasPrincipal_consolidadoAnual($anio_id)
    {     

        // return view('graficos.Barra',["data"=> json_encode($puntos),"categoria_nombres"=> json_encode($categoria_nombres)],compact( 'titulo_y','titulo','subTitulo'));
    }

    public function solo_anios_consolidadoAnual($total_matricula_ComsolidadoAnual)
    {
        $array = [];
        $i=1;
        foreach($total_matricula_ComsolidadoAnual as $indice => $item) {
            $array += [
                $i=> ['anio' => $item->anio]
            ];
            $i++;
        }
      
        $arraySinDuplicados = [];
        foreach($array as $indice => $elemento) {
            if (!in_array($elemento, $arraySinDuplicados)) {
                $arraySinDuplicados[$indice] = $elemento;
            }
        }
            
        return $arraySinDuplicados;
    }

    public function filtro_gestion($gestion)
    {
        $filtro = "";

        if($gestion==1)
            $filtro = "NOT( tipo_ie LIKE '%xyz%')";//este filtro hace que la consulta traiga los datos de publicas y privadas
        else
        {
            if($gestion==2)
                $filtro = "NOT( tipo_ie LIKE '%particular%' or tipo_ie LIKE '%privada%')";
            else
                $filtro = "( tipo_ie LIKE '%particular%' or tipo_ie LIKE '%privada%')";
        }

        return  $filtro;
    }

    public function descripcion_nivel($nivel)
    {
        $descripcion = 'INICIAL, PRIMARIA Y SECUNDARIA';

        if($nivel=="I")
            $descripcion = 'NIVEL INICIAL';
        else{
            if($nivel=="P")
                $descripcion = 'NIVEL PRIMARIA';
            else{
                if($nivel=="S")
                    $descripcion = 'NIVEL SECUNDARIA';
            }
        }

        return  $descripcion;
    }
}
