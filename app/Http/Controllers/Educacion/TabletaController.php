<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\Tableta;
use App\Models\Educacion\TabletaDetalle;
use App\Models\Parametro\Anio;
use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\InstitucionEducativaRepositorio;
use App\Repositories\Educacion\TabletaRepositorio;
use App\Utilities\Utilitario;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class TabletaController extends Controller
{
    public $mes = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function importar()
    {
        $mensaje = "";
        $anios = Anio::orderBy('anio', 'desc')->get();

        return view('educacion.Tableta.Importar', compact('mensaje', 'anios'));
    }

    public function guardar(Request $request)
    {
        $this->validate($request, ['file' => 'required|mimes:xls,xlsx']);
        $archivo = $request->file('file');
        $array = (new tablaXImport)->toArray($archivo);
        $anios = Anio::orderBy('anio', 'desc')->get();

        $i = 0;
        $cadena = '';

        // VALIDACION DE LOS FORMATOS DE LOS 04 NIVELES
        try {
            foreach ($array as $key => $value) {
                foreach ($value as $row) {
                    if (++$i > 1) break;
                    $cadena =  $cadena .
                        $row['region'] .
                        $row['ugel'] .
                        $row['departamento'] .
                        $row['provincia'] .
                        $row['distritos'] .
                        $row['codlocal'] .
                        $row['codigo_modular'] .
                        $row['anexo'] .
                        $row['institucion_educativa'] .
                        $row['nivel'] .
                        $row['tabletas_estudiantes_adistribuir'] .
                        $row['tabletas_docentes_adistribuir'] .
                        $row['total_tabletas_adistribuir'] .
                        $row['tabletas_estudiantes_despacho'] .
                        $row['tabletas_docentes_despacho'] .
                        $row['total_tabletas_despacho'] .
                        $row['tabletas_estudiantes_recepcion'] .
                        $row['tabletas_docentes_recepcion'] .
                        $row['total_tabletas_recepcion'] .
                        $row['tabletas_estudiantes_asignacion'] .
                        $row['tabletas_docentes_asignacion'] .
                        $row['total_tabletas_asignacion'];
                }
            }
        } catch (Exception $e) {
            $mensaje = "Formato de archivo no reconocido, porfavor verifique si el formato es el correcto y vuelva a importar";
            return view('Educacion.Tableta.Importar', compact('mensaje', 'anios'));
        }

        $existeMismaFecha = ImportacionRepositorio::Importacion_PE($request['fechaActualizacion'], 9);

        if ($existeMismaFecha != null) {
            $mensaje = "Error, Ya existe archivos prendientes de aprobar para la fecha de versión ingresada";
            return view('Educacion.Tableta.Importar', compact('mensaje', 'anios'));
        } else {
            try {
                $importacion = Importacion::Create([
                    'fuenteImportacion_id' => 9, // valor predeterminado
                    'usuarioId_Crea' => auth()->user()->id,
                    // 'usuarioId_Aprueba' => null,
                    'fechaActualizacion' => $request['fechaActualizacion'],
                    // 'comentario' => $request['comentario'],
                    'estado' => 'PE'
                ]);

                $Tableta = Tableta::Create([
                    'importacion_id' => $importacion->id, // valor predeterminado
                    'anio_id' => $request['anio'],
                ]);

                foreach ($array as $key => $value) {
                    foreach ($value as $row) {
                        // echo $row['cen_edu'].'<br>';

                        $institucion_educativa = InstitucionEducativaRepositorio::InstitucionEducativa_porCodModular($row['codigo_modular'])->first();

                        if ($institucion_educativa != null) {
                            $TabletaDetalle = TabletaDetalle::Create([
                                'tableta_id' => $Tableta->id, // valor predeterminado
                                'institucioneducativa_id' => $institucion_educativa->id,
                                'nivel_educativo_dato_adic' => $row['nivel'],
                                'codModular_dato_adic' => $row['codigo_modular'],
                                'codLocal_dato_adic' => $row['codlocal'],
                                'aDistribuir_estudiantes' => $row['tabletas_estudiantes_adistribuir'],
                                'aDistribuir_docentes' => $row['tabletas_docentes_adistribuir'],
                                'despachadas_estudiantes' => $row['tabletas_estudiantes_despacho'],
                                'despachadas_docentes' => $row['tabletas_docentes_despacho'],
                                'recepcionadas_estudiantes' => $row['tabletas_estudiantes_recepcion'],
                                'recepcionadas_docentes' => $row['tabletas_docentes_recepcion'],
                                'asignadas_estudiantes' => $row['tabletas_estudiantes_asignacion'],
                                'asignadas_docentes' => $row['tabletas_docentes_asignacion']

                            ]);
                        }
                    }
                }
            } catch (Exception $e) {
                $importacion->estado = 'EL';
                $importacion->save();

                $mensaje = "Error en la carga de datos, verifique los datos de su archivo y/o comuniquese con el administrador del sistema";
                return view('Educacion.Tableta.Importar', compact('mensaje', 'anios'));
            }
        }

        return view('correcto');
    }

    public function ListaImportada_DataTable($importacion_id)
    {
        // $Lista = CensoRepositorio::Listar_Por_Importacion_id($importacion_id);

        // return  datatables()->of($Lista)->toJson();;
    }

    public function ListaImportada($importacion_id)
    {
        $datos_matricula_importada = $this->datos_matricula_importada($importacion_id);
        return view('Educacion.Matricula.ListaImportada', compact('importacion_id', 'datos_matricula_importada'));
    }

    public function aprobar($importacion_id)
    {
        $importacion = ImportacionRepositorio::ImportacionPor_Id($importacion_id);


        return view('educacion.Tableta.Aprobar', compact('importacion_id', 'importacion'));
    }

    public function procesar($importacion_id)
    {
        $importacion  = Importacion::find($importacion_id);

        $importacion->estado = 'PR';
        // $importacion->usuarioId_Aprueba = auth()->user()->id;
        $importacion->save();

        $this->elimina_mismaFecha($importacion->fechaActualizacion, $importacion->fuenteImportacion_id, $importacion_id);

        return view('correcto');
    }

    public function elimina_mismaFecha($fechaActualizacion, $fuenteImportacion_id, $importacion_id)
    {
        $importacion  = ImportacionRepositorio::Importacion_mismaFecha($fechaActualizacion, $fuenteImportacion_id, $importacion_id);

        if ($importacion != null) {
            $importacion->estado = 'EL';
            $importacion->save();
        }
    }

    //**************************************************************************************** */
    public function principal()
    {
        $actualizado = '';

        $impor = ImportacionRepositorio::ImportacionMax_porfuente(ImporTabletaController::$FUENTE); //nexus $imp3
        $tableta_id = Tableta::where('importacion_id', $impor->id)->first()->id;

        $strTableta = strtotime($impor->fechaActualizacion);
        $actualizado = 'Actualizado al ' . date('d', $strTableta) . ' de ' . $this->mes[date('m', $strTableta) - 1] . ' del ' . date('Y', $strTableta);

        $anios = Tableta::select('v1.*')->join('par_anio as v1', 'v1.id', '=', 'edu_tableta.anio_id')->distinct()->get();
        $maxAnio = Tableta::select(DB::raw('max(v1.anio) as anio'))->join('par_anio as v1', 'v1.id', '=', 'edu_tableta.anio_id')->first()->anio;
        $provincias = Ubigeo::select('v2.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $distritos = Ubigeo::select('v3.*')->join('par_ubigeo as v2', 'v2.dependencia', '=', 'par_ubigeo.id')->join('par_ubigeo as v3', 'v3.dependencia', '=', 'v2.id')->whereNull('par_ubigeo.dependencia')->where('par_ubigeo.codigo', '25')->get();
        $areas = Area::all();
        return view('educacion.Tableta.Principal', compact(
            'tableta_id',
            'actualizado',
            'maxAnio',
            'anios',
            'provincias',
            'distritos',
            'areas',
        ));
    }

    public function principalHead(Request $rq)
    {
        $valor1 = TabletaRepositorio::principalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->area, 1);
        $valor2 = TabletaRepositorio::principalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->area, 2);
        $valor3 = TabletaRepositorio::principalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->area, 3);
        $valor4 = TabletaRepositorio::principalHead($rq->anio, $rq->provincia, $rq->distrito, $rq->area, 4);
        $valor1 = number_format($valor1, 0);
        $valor2 = number_format($valor2, 0);
        $valor3 = number_format($valor3, 0);
        $valor4 = number_format($valor4, 0);
        return response()->json(compact('valor1', 'valor2', 'valor3', 'valor4'));
    }

    public function principalTabla(Request $rq)
    {
        switch ($rq->div) {
            case 'anal1':
                $query = TabletaRepositorio::principalTabla($rq->anio, $rq->provincia, $rq->distrito, $rq->area, $rq->div);
                $info['categoria'] = ['Primaria', 'Secundaria'];
                $dx2 = [(int)$query[0]->pta, (int)$query[0]->sta];
                $dx3 = [(int)$query[0]->pca, (int)$query[0]->sca];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Tabletas', 'color' => '#317eeb', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Cargadores', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('info'));
            case 'anal2':
                $query = TabletaRepositorio::principalTabla($rq->anio, $rq->provincia, $rq->distrito, $rq->area, $rq->div);
                $info['categoria'] = ['Urbana', 'Rural'];
                $dx2 = [(int)$query[0]->uta, (int)$query[0]->rta];
                $dx3 = [(int)$query[0]->uca, (int)$query[0]->rca];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Tabletas', 'color' => '#317eeb', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Cargadores', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('info'));
            case 'anal3':
                $query = TabletaRepositorio::principalTabla($rq->anio, $rq->provincia, $rq->distrito, $rq->area, $rq->div);
                $info['categoria'] = ['Estudiantes', 'Docentes'];
                $dx2 = [(int)$query[0]->ata, (int)$query[0]->dta];
                $dx3 = [(int)$query[0]->aca, (int)$query[0]->dca];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Tabletas', 'color' => '#317eeb', 'data' => $dx2];
                $info['series'][] = ['type' => 'column', 'yAxis' => 0, 'name' => 'Cargadores', 'color' => '#ef5350', 'data' => $dx3];
                return response()->json(compact('info'));
            case 'tabla1':
                $base = TabletaRepositorio::principalTablaTipo2($rq->anio, $rq->provincia, $rq->distrito, $rq->area, $rq->div);
                $foot = clone $base[0];
                $foot->t1 = 0;
                $foot->t2 = 0;
                $foot->t3 = 0;
                $foot->t4 = 0;
                $foot->t5 = 0;
                $foot->c1 = 0;
                $foot->c2 = 0;
                $foot->c3 = 0;
                $foot->c4 = 0;
                $foot->c5 = 0;
                foreach ($base as $key => $value) {
                    $foot->t1 += $value->t1;
                    $foot->t2 += $value->t2;
                    $foot->t4 += $value->t4;
                    $foot->t5 += $value->t5;
                    $foot->c1 += $value->c1;
                    $foot->c2 += $value->c2;
                    $foot->c4 += $value->c4;
                    $foot->c5 += $value->c5;
                }
                $foot->t3 = 100 * $value->t2 / $value->t1;
                $foot->c3 = 100 * $value->c2 / $value->c1;
                $excel = view('educacion.Tableta.PrincipalTabla1excel', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));
            case 'tabla2':
                $base = TabletaRepositorio::principalTablaTipo2($rq->anio, $rq->provincia, $rq->distrito, $rq->area, $rq->div);
                $foot = clone $base[0];
                $foot->te = 0;
                $foot->td = 0;
                $foot->tt = 0;
                $foot->ce = 0;
                $foot->cd = 0;
                $foot->ct = 0;
                foreach ($base as $key => $value) {
                    $foot->te += $value->te;
                    $foot->td += $value->td;
                    $foot->tt += $value->tt;
                    $foot->ce += $value->ce;
                    $foot->cd += $value->cd;
                    $foot->ct += $value->ct;
                }
                $excel = view('educacion.Tableta.PrincipalTabla2excel', compact('base', 'foot'))->render();
                return response()->json(compact('excel'));
            default:
                return [];
        }
    }

    public function GraficoBarrasPrincipal($anio_id)
    {
        // $resumen_tabletas_anio = null;

        if ($anio_id == 0)
            $resumen_tabletas_anio = TabletaRepositorio::tabletas_ultimaActualizacion();
        else
            $resumen_tabletas_anio = TabletaRepositorio::resumen_tabletas_anio($anio_id);


        $categoria1 = [];
        $categoria2 = [];
        $categoria3 = [];
        $categoria4 = [];
        $categoria_nombres = [];

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($resumen_tabletas_anio as $key => $lista) {
            $categoria1 = array_merge($categoria1, [intval($lista->total_aDistribuir)]);
            $categoria2 = array_merge($categoria2, [intval($lista->total_Despachado)]);
            $categoria3 = array_merge($categoria3, [intval($lista->total_Recepcionadas)]);
            $categoria4 = array_merge($categoria4, [intval($lista->total_Asignadas)]);
            $categoria_nombres[] = Utilitario::fecha_formato_texto_diayMes($lista->fechaActualizacion);
        }

        $puntos[] = ['name' => 'A distribuir', 'data' =>  $categoria1];
        $puntos[] = ['name' => 'Despacahadas', 'data' => $categoria2];
        $puntos[] = ['name' => 'Recepcionadas', 'data' =>  $categoria3];
        $puntos[] = ['name' => 'Asignadas', 'data' => $categoria4];

        if ($anio_id == 0)
            $nombreAnio = Utilitario::anio_deFecha(TabletaRepositorio::tableta_mas_actual()->first()->fechaActualizacion);
        else
            $nombreAnio = Anio::find($anio_id)->anio;


        $titulo = 'DISTRIBUCION DE TABLETAS ' . $nombreAnio;
        $subTitulo = 'Fuente: SIAGIE - MINEDU';
        $titulo_y = 'Numero de tabletas';

        $nombreGraficoBarra = 'barra1'; // este nombre va de la mano con el nombre del DIV en la vista

        return view(
            'graficos.Barra',
            ["data" => json_encode($puntos), "categoria_nombres" => json_encode($categoria_nombres)],
            compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoBarra')
        );
    }

    public function reporteUgel($anio_id, $tableta_id)
    {
        $resumen_tabletas_ugel = TabletaRepositorio::resumen_tabletas_ugel($tableta_id);

        $fecha_texto = $this->fecha_texto($tableta_id);


        return view('educacion.Tableta.ReporteUgel', compact('resumen_tabletas_ugel', 'fecha_texto'));
    }

    public function fecha_texto($id)
    {
        $fecha_texto = '--';
        $datos = TabletaRepositorio::datos_tableta($id);

        if ($datos->first() != null)
            $fecha_texto = Utilitario::fecha_formato_texto_completo($datos->first()->fechaactualizacion);

        return $fecha_texto;
    }

    public function Fechas($anio_id)
    {
        $fechas_tabletas = TabletaRepositorio::fechas_tabletas_anio($anio_id);
        return response()->json(compact('fechas_tabletas'));
    }
}
