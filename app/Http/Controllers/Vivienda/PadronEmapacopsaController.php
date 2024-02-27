<?php

namespace App\Http\Controllers\Vivienda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Importacion;
use App\Models\Vivienda\PadronEmapacopsa;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Vivienda\PadronEmapacopsaRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PadronEmapacopsaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        return view('Vivienda.PadronEmapacopsa.Importar', compact('mensaje'));
    }

    public function importarGuardar(Request $request)
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
                        $row['cod_dist'] .
                        $row['distrito'] .
                        $row['cod_sector'] .
                        $row['cod_manzana'] .
                        $row['manzana_nombre'] .
                        $row['lote'] .
                        $row['nro_insc'] .
                        $row['nombres'] .
                        $row['ruc'] .
                        $row['direccion'] .
                        $row['urbanizacion'] .
                        $row['tipo_serv'] .
                        $row['tipo_servicio_nombre'] .
                        $row['est_conex'] .
                        $row['estado_conexion_nombre'] .
                        $row['unid_uso'] .
                        $row['sub_categ'] .
                        $row['sub_categoria_nombre'] .
                        $row['tar'] .
                        $row['num_med'] .
                        $row['lect_sec'] .
                        $row['rep_sec'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";
            return view('Vivienda.PadronEmapacopsa.Importar', compact('mensaje'));
        }
        try {
            $importacion = Importacion::Create([
                'fuenteImportacion_id' => 11, // valor predeterminado
                'usuarioId_Crea' => auth()->user()->id,
                // 'usuarioId_Aprueba' => null,
                'fechaActualizacion' => $request['fechaActualizacion'],
                // 'comentario' => $request['comentario'],
                'estado' => 'PE'
            ]);

            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    $Emapacopsa = PadronEmapacopsa::Create([
                        'importacion_id' => $importacion->id,
                        'cod_dist' => $row['cod_dist'],
                        'distrito' => $row['distrito'],
                        'cod_sector' => $row['cod_sector'],
                        'cod_manzana' => $row['cod_manzana'],
                        'manzana_nombre' => $row['manzana_nombre'],
                        'lote' => $row['lote'],
                        'nro_insc' => $row['nro_insc'],
                        'nombres' => $row['nombres'],
                        'ruc' => $row['ruc'],
                        'direccion' => $row['direccion'],
                        'urbanizacion' => $row['urbanizacion'],
                        'tipo_serv' => $row['tipo_serv'],
                        'tipo_servicio_nombre' => $row['tipo_servicio_nombre'],
                        'est_conex' => $row['est_conex'],
                        'estado_conexion_nombre' => $row['estado_conexion_nombre'],
                        'unid_uso' => $row['unid_uso'],
                        'sub_categ' => $row['sub_categ'],
                        'sub_categoria_nombre' => $row['sub_categoria_nombre'],
                        'tar' => $row['tar'],
                        'num_med' => $row['num_med'],
                        'lect_sec' => $row['lect_sec'],
                        'rep_sec' => $row['rep_sec'],
                    ]);
                }
            }
        } catch (Exception $e) {
            $importacion->estado = 'EL';
            $importacion->save();
            $mensaje = "Error en la carga de datos, comuniquese con el administrador del sistema \n" . $e->getMessage();
            /* PadronEmapacopsa::where('importacion_id', $importacion->id)->delete(); //elimina emapacopsa cargados
            DB::statement('ALTER TABLE viv_padronemapacopsa AUTO_INCREMENT 1'); //*-*
            $importacion->delete(); // elimina la importacion creada */
            return view('Vivienda.PadronEmapacopsa.Importar', compact('mensaje'));
        }

        return redirect()->route('pemapacopsa.listado', $importacion->id);
    }
    public function importarListado($importacion_id)
    {
        return view('Vivienda.PadronEmapacopsa.ListaImportada', compact('importacion_id'));
    }
    public function importarListadoDT($importacion_id)
    {
        $Lista = PadronEmapacopsaRepositorio::ListarImportados($importacion_id);

        return  datatables()->of($Lista)->toJson();
    }
    public function importarAprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);
        return  view('Vivienda.PadronEmapacopsa.Aprobar', compact('importacion_id', 'importacion'));
    }

    public function importarAprobarGuardar($importacion_id)
    {
        $procesar = DB::select('call viv_pa_procesarEmapacopsa(?)', [$importacion_id]); // que sera esto :o :o :o  XDXDXD
        return view('correcto');
    }
}
