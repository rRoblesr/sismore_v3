<?php

namespace App\Http\Controllers\Educacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Censo;
use App\Models\Educacion\CensoResultado;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\CensoRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Exception;

class CensoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {  
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();
        
        return view('educacion.Censo.Importar',compact('mensaje','anios'));
    } 
    
    public function guardar(Request $request)
    {  
        $this->validate($request,['file' => 'required|mimes:xls,xlsx']);      
        $archivo = $request->file('file');
        $array = (new tablaXImport )-> toArray($archivo);    
        $anios = Anio::orderBy('anio', 'desc')->get();    

        $i = 0;
        $cadena ='';

        try{
             foreach ($array as $key => $value) {
                 foreach ($value as $row) {
                    if(++$i > 1) break;
                    $cadena =  $cadena
                    .$row['codlocal'].$row['codigosmodulares'].$row['nombreinstitucion'].$row['codigogestion']
                    .$row['descripciongestion'].$row['codigoorganointer'].$row['nombredre_ugel'].$row['codigoubigeo']
                    .$row['departamento'].$row['provincia'].$row['distrito'].$row['centopoblado'].$row['direccion']
                    .$row['areageo'].$row['estadocenso'].$row['totalaulas'].$row['aulasbuenas'].$row['aulasregulares']
                    .$row['aulasmalas'].$row['nopuedeprecisarestadoaulas'].$row['ellocales'].$row['propietariolocal']
                    .$row['cuenta_con_itse'].$row['plan_contingencia'].$row['plan_desastre'].$row['plandesastre_act']
                    .$row['compuescri_operativos'].$row['compuescri_inoperativos'].$row['compuporta_operativos']
                    .$row['compuporta_inoperativos'].$row['lapto_operativos'].$row['lapto_inoperativos'].$row['tieneinternet']
                    .$row['tipoconexion'].$row['fuenteenergiaelectrica'].$row['empresaenergiaelect'].$row['tieneenergiaelecttododia']
                    .$row['fuenteagua'].$row['empresaagua'].$row['tieneaguapottododia'].$row['desagueinfo'];     
                    }
             }
        }catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";           
            return view('Educacion.Censo.Importar',compact('mensaje','anios'));            
        }
       
        try{
            $importacion = Importacion::Create([
                'fuenteImportacion_id'=>6, // valor predeterminado
                'usuarioId_Crea'=> auth()->user()->id,
                'usuarioId_Aprueba'=>null,
                'fechaActualizacion'=>$request['fechaActualizacion'],
                'comentario'=>$request['comentario'],
                'estado'=>'PE'
              ]); 

            $censo = Censo::Create([
                'importacion_id'=>$importacion->id, // valor predeterminado
                'anio_id'=> $request['anio'],
                'estado'=>'PE'
              ]); 

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $CensoResultado = CensoResultado::Create([
                        'censo_id'=>$censo->id,
                        'codLocal'=>$row['codlocal'],
                        'codigosModulares'=>$row['codigosmodulares'],
                        'nombreInstitucion'=>$row['nombreinstitucion'],
                        'codigoGestion'=>$row['codigogestion'],
                        'descripcionGestion'=>$row['descripciongestion'],
                        'codigoOrganoInter'=>$row['codigoorganointer'],
                        'nombreDre_Ugel'=>$row['nombredre_ugel'],
                        'codigoUbigeo'=>$row['codigoubigeo'],
                        'Departamento'=>$row['departamento'],
                        'Provincia'=>$row['provincia'],
                        'Distrito'=>$row['distrito'],
                        'centoPoblado'=>$row['centopoblado'],
                        'direccion'=>$row['direccion'],
                        'areaGeo'=>$row['areageo'],
                        'estadoCenso'=>$row['estadocenso'],
                        'totalAulas'=>$row['totalaulas'],
                        'aulasBuenas'=>$row['aulasbuenas'],
                        'aulasRegulares'=>$row['aulasregulares'],
                        'aulasMalas'=>$row['aulasmalas'],
                        'noPuedePrecisarEstadoAulas'=>$row['nopuedeprecisarestadoaulas'],
                        'elLocalEs'=>$row['ellocales'],
                        'propietarioLocal'=>$row['propietariolocal'],
                        'cuenta_con_itse'=>$row['cuenta_con_itse'],
                        'plan_contingencia'=>$row['plan_contingencia'],
                        'plan_desastre'=>$row['plan_desastre'],
                        'plandesastre_act'=>$row['plandesastre_act'],
                        'compuEscri_operativos'=>$row['compuescri_operativos'],
                        'compuEscri_inoperativos'=>$row['compuescri_inoperativos'],
                        'compuPorta_operativos'=>$row['compuporta_operativos'],
                        'compuPorta_inoperativos'=>$row['compuporta_inoperativos'],
                        'lapto_operativos'=>$row['lapto_operativos'],
                        'lapto_inoperativos'=>$row['lapto_inoperativos'],
                        'tieneInternet'=>$row['tieneinternet'],
                        'tipoConexion'=>$row['tipoconexion'],
                        'fuenteEnergiaElectrica'=>$row['fuenteenergiaelectrica'],
                        'empresaEnergiaElect'=>$row['empresaenergiaelect'],
                        'tieneEnergiaElectTodoDia'=>$row['tieneenergiaelecttododia'],
                        'fuenteAgua'=>$row['fuenteagua'],
                        'empresaAgua'=>$row['empresaagua'],
                        'tieneAguaPotTodoDia'=>$row['tieneaguapottododia'],
                        'desagueInfo'=>$row['desagueinfo']             
                    ]);
                }
            }
        }catch (Exception $e) {

            $censo->estado = 'EL';
            $censo->save();
                 
            $importacion->estado = 'EL';
            $importacion->save();
            
            $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema";          
            return view('Educacion.Censo.Importar',compact('mensaje','anios'));            
        }
       
        return redirect()->route('Censo.Censo_Lista',$importacion->id);
    }

    public function ListaImportada($importacion_id)
    {
        return view('Educacion.Censo.ListaImportada',compact('importacion_id'));
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        $Lista = CensoRepositorio::Listar_Por_Importacion_id($importacion_id);
                
        return  datatables()->of($Lista)->toJson();;
    }
    
    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
        $anioCenso = CensoRepositorio :: censo_Por_Importacion_id($importacion_id)->first()->anio;

        return view('educacion.Censo.Aprobar',compact('importacion_id','importacion','anioCenso'));
    } 

    public function procesar($importacion_id)
    {
        $importacion  = Importacion::find($importacion_id);
        $importacion->usuarioId_Aprueba = auth()->user()->id; 
        $importacion->estado = 'PR';        
        $importacion->save();

        $Censo = CensoRepositorio :: censo_Por_Importacion_id($importacion_id)->first();        
        $Censo->estado = 'PR';
        $Censo->save();

        $ultimoCenso_mismoAnio = CensoRepositorio :: censo_Por_anio_estado($Censo->anio,'PR')->first();
        $ultimoCenso_mismoAnio->estado = 'EL';
        $ultimoCenso_mismoAnio->save();

        return view('correcto');
    }
    
}
