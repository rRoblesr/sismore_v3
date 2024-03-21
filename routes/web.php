<?php

use App\Http\Controllers\Administracion\EntidadController;
use App\Http\Controllers\Administracion\MenuController;
use App\Http\Controllers\Administracion\PerfilController;
use App\Http\Controllers\Administracion\SistemaController;
use App\Http\Controllers\Administracion\UsuarioController;
use App\Http\Controllers\Educacion\CensoController;
use App\Http\Controllers\Educacion\CensoDocenteController;
use App\Http\Controllers\Educacion\CuadroAsigPersonalController;
use App\Http\Controllers\Educacion\ImportacionController;
use App\Http\Controllers\Educacion\EceController;
use App\Http\Controllers\Educacion\ImporCensoDocenteController;
use App\Http\Controllers\Educacion\ImporCensoMatriculaController;
use App\Http\Controllers\Educacion\ImporISController;
use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Educacion\InstEducativaController;
use App\Http\Controllers\Educacion\MatriculaController;
use App\Http\Controllers\Educacion\PadronEIBController;
use App\Http\Controllers\Educacion\ImporPadronWebController;
use App\Http\Controllers\Educacion\ImporMatriculaController;
use App\Http\Controllers\Educacion\ImporMatriculaGeneralController;
use App\Http\Controllers\Educacion\ImporPadronEibController;
use App\Http\Controllers\Educacion\ImporRERController;
use App\Http\Controllers\Educacion\ImporTabletaController;
use App\Http\Controllers\Educacion\LenguaController;
use App\Http\Controllers\Educacion\MatriculaDetalleController;
use App\Http\Controllers\Educacion\MatriculaGeneralController;
use App\Http\Controllers\Educacion\NivelModalidadController;
use App\Http\Controllers\Educacion\PadronWebController;
use App\Http\Controllers\Educacion\PLazaController;
use App\Http\Controllers\Educacion\PadronRERController;
use App\Http\Controllers\Educacion\RERController;
use App\Http\Controllers\Educacion\ServiciosBasicosController;
use App\Http\Controllers\Educacion\SFLController;
use App\Http\Controllers\Educacion\SuperiorArtisticoController;
use App\Http\Controllers\Educacion\SuperiorPedagogicoController;
use App\Http\Controllers\Educacion\SuperiorTecnologicoController;
use App\Http\Controllers\Educacion\TabletaController;
use App\Http\Controllers\Educacion\TecnicoProductivaController;
use App\Http\Controllers\Educacion\TextosEscolaresController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Parametro\ClasificadorController;
use App\Http\Controllers\Parametro\FuenteImportacionController;
use App\Http\Controllers\Parametro\IconoController;
use App\Http\Controllers\Parametro\ImporPoblacionController;
use App\Http\Controllers\Parametro\IndicadorGeneralController;
use App\Http\Controllers\Parametro\UbigeoController;
use App\Http\Controllers\PowerBiController;
use App\Http\Controllers\Presupuesto\BaseGastosController;
use App\Http\Controllers\Presupuesto\BaseIngresosController;
use App\Http\Controllers\Presupuesto\BaseProyectosController;
use App\Http\Controllers\Presupuesto\BaseSiafWebController;
use App\Http\Controllers\Presupuesto\EspecificaController;
use App\Http\Controllers\Presupuesto\EspecificaDetalleController;
use App\Http\Controllers\Presupuesto\GobiernosRegionalesController;
use App\Http\Controllers\Presupuesto\ImporActividadesProyectosController;
use App\Http\Controllers\Presupuesto\ImporGastosController;
use App\Http\Controllers\Presupuesto\ImporIngresosController;
use App\Http\Controllers\Presupuesto\ImporModificacionesController;
use App\Http\Controllers\Presupuesto\ImporProyectosController;
use App\Http\Controllers\Presupuesto\ImporSiafWebController;
use App\Http\Controllers\Presupuesto\MetaController;
use App\Http\Controllers\Presupuesto\ModificacionesController;
use App\Http\Controllers\Presupuesto\PliegoController;
use App\Http\Controllers\Presupuesto\PruebaController;
use App\Http\Controllers\Presupuesto\SubGenericaController;
use App\Http\Controllers\Presupuesto\SubGenericaDetalleController;
use App\Http\Controllers\Presupuesto\UnidadEjecutoraController;
use App\Http\Controllers\Presupuesto\UnidadOrganicaController;
use App\Http\Controllers\Salud\IndicadoresController;
use App\Http\Controllers\Trabajo\ActividadController;
use App\Http\Controllers\Trabajo\AnuarioEstadisticoController;
use App\Http\Controllers\Trabajo\IndicadorTrabajoController;
use App\Http\Controllers\Trabajo\ProEmpleoController;
use App\Http\Controllers\Vivienda\CentroPobladoController;
use App\Http\Controllers\Vivienda\CentroPobladoDatassController;
use App\Http\Controllers\Vivienda\DatassController;
use App\Http\Controllers\Vivienda\EmapacopsaController;
use App\Http\Controllers\Vivienda\PadronEmapacopsaController;
use App\Models\Educacion\ImporDocentesBilingues;
use App\Models\Educacion\ImporServiciosBasicos;
use App\Models\Educacion\Importacion;
use App\Models\Parametro\Icono;
use App\Models\Presupuesto\BaseActividadesProyectos;
use App\Models\Presupuesto\BaseActividadesProyectosDetalle;
use App\Models\Presupuesto\BaseGastos;
use App\Models\Presupuesto\BaseGastosDetalle;
use App\Models\Presupuesto\BaseIngresos;
use App\Models\Presupuesto\BaseIngresosDetalle;
use App\Models\Presupuesto\BaseModificacion;
use App\Models\Presupuesto\BaseModificacionDetalle;
use App\Models\Presupuesto\BaseProyectos;
use App\Models\Presupuesto\BaseProyectosDetalle;
use App\Models\Presupuesto\BaseSiafWeb;
use App\Models\Presupuesto\BaseSiafWebDetalle;
use App\Models\Presupuesto\ImporActividadesProyectos;
use App\Models\Presupuesto\ImporGastos;
use App\Models\Presupuesto\ImporIngresos;
use App\Models\Presupuesto\ImporModificaciones;
use App\Models\Presupuesto\ImporProyectos;
use App\Models\Presupuesto\ImporSiafWeb;
use App\Models\Presupuesto\SubGenericaGasto;
use App\Models\Presupuesto\UnidadEjecutora;
use App\Models\Vivienda\CentroPobladoDatass;
use App\Repositories\Parametro\UbigeoRepositorio;
use App\Repositories\Presupuesto\BaseActividadesProyectosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', function () {
    //return view('welcome');
    return redirect('/publico');
});

/**************************************** ACCESO PUBLICO ************************************************/
Route::get('/publico', [HomeController::class, 'accesopublico'])->name('acceso.publico');
Route::get('/publico/{sistema_nombre}', [HomeController::class, 'accesopublicomodulo'])->name('acceso.publico.modulo');
//Route::get('/publico/{sistema_id}', [HomeController::class, 'acceso_publico'])->name('acceso_publico');
/**************************************** FIN ACCESO PUBLICO ************************************************/



Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
Route::get('/{sistema_nombre}', [HomeController::class, 'sistema_acceder'])->middleware('auth')->name('sistema_acceder');

Route::get('/AEI', [HomeController::class, 'AEI_tempo'])->name('AEI_tempo');

/**************************************** EDUCACION ************************************************/

################ publico
Route::get('/Home/gra1', [HomeController::class, 'educaciongrafica1'])->name('graficas.home.educacion.1');
Route::get('/Home/gra2', [HomeController::class, 'educaciongrafica2'])->name('graficas.home.educacion.2');
Route::get('/Home/gra3', [HomeController::class, 'educaciongrafica3'])->name('graficas.home.educacion.3');
Route::get('/Home/gra4', [HomeController::class, 'educaciongrafica4'])->name('graficas.home.educacion.4');
Route::get('/Home/gra5', [HomeController::class, 'educaciongrafica5'])->name('graficas.home.educacion.5');
Route::get('/Home/gra6', [HomeController::class, 'educaciongrafica6'])->name('graficas.home.educacion.6');
################# end publico

// Route::get('/Home/PanelControl/01', [IndicadorController::class, 'panelControlEduacionHead'])->name('panelcontrol.educacion.head');
// Route::get('/Home/PanelControl/graficas', [IndicadorController::class, 'panelControlEduacionGraficas'])->name('panelcontrol.educacion.graficas');
Route::get('/Home/PanelControl/01', [HomeController::class, 'panelControlEduacionHead'])->name('panelcontrol.educacion.head');
Route::get('/Home/PanelControl/graficas', [HomeController::class, 'panelControlEduacionGraficas'])->name('panelcontrol.educacion.graficas');

// Route::get('/INDICADOR/Home/01', [IndicadorController::class, 'panelControlEduacionNuevoindicador01'])->name('indicador.nuevos.01');
// Route::get('/INDICADOR/Home/01/Head', [IndicadorController::class, 'panelControlEduacionNuevoindicador01head'])->name('indicador.nuevos.01.head');
// Route::get('/INDICADOR/Home/01/Tabla', [IndicadorController::class, 'panelControlEduacionNuevoindicador01Tabla'])->name('indicador.nuevos.01.tabla');
// Route::get('/INDICADOR/Home/01/Excel/{div}/{anio}/{provincia}/{distrito}/{gestion}/{ugel}', [IndicadorController::class, 'panelControlEduacionNuevoindicador01Download']);
// Route::get('/INDICADOR/Home/01/xxx', [IndicadorController::class, 'pagina'])->name('indicador.nuevos.01.print');

Route::get('/educación/SinDatos', [HomeController::class, 'paginavacio'])->name('educacion.sinruta');

Route::get('/INDICADOR/Home/02', [IndicadorController::class, 'panelControlEduacionNuevoindicador02'])->name('indicador.nuevos.02');

Route::get('/INDICADOR/Home/04', [IndicadorController::class, 'panelControlEduacionNuevoindicador04'])->name('panelcontrol.educacion.indicador.nuevos.04');
Route::get('/INDICADOR/Home/04/head', [IndicadorController::class, 'panelControlEduacionNuevoindicador04Head'])->name('panelcontrol.educacion.indicador.nuevos.04.head');
Route::get('/INDICADOR/Home/04/tabla', [IndicadorController::class, 'panelControlEduacionNuevoindicador04Tabla'])->name('panelcontrol.educacion.indicador.nuevos.04.tabla');
Route::get('/INDICADOR/Home/04/Excel/{anio}/{provincia}/{distrito}/{gestion}', [IndicadorController::class, 'panelControlEduacionNuevoindicador04Download']);
Route::get('/INDICADOR/Home/04/Excel/tabla2/{anio}/{provincia}/{distrito}/{gestion}', [IndicadorController::class, 'panelControlEduacionNuevoindicador04Download2']);

Route::get('/INDICADOR/Home/05', [IndicadorController::class, 'panelControlEduacionNuevoindicador05'])->name('panelcontrol.educacion.indicador.nuevos.05');
Route::get('/INDICADOR/Home/05/head', [IndicadorController::class, 'panelControlEduacionNuevoindicador05Head'])->name('panelcontrol.educacion.indicador.nuevos.05.head');
Route::get('/INDICADOR/Home/05/tabla', [IndicadorController::class, 'panelControlEduacionNuevoindicador05Tabla'])->name('panelcontrol.educacion.indicador.nuevos.05.tabla');
Route::get('/INDICADOR/Home/05/Excel/{anio}/{provincia}/{distrito}/{gestion}', [IndicadorController::class, 'panelControlEduacionNuevoindicador05Download']);
Route::get('/INDICADOR/Home/05/Excel/tabla2/{anio}/{provincia}/{distrito}/{gestion}', [IndicadorController::class, 'panelControlEduacionNuevoindicador05Download2']);

Route::get('/INDICADOR/Home/06', [IndicadorController::class, 'panelControlEduacionNuevoindicador06'])->name('panelcontrol.educacion.indicador.nuevos.06');
Route::get('/INDICADOR/Home/06/head', [IndicadorController::class, 'panelControlEduacionNuevoindicador06Head'])->name('panelcontrol.educacion.indicador.nuevos.06.head');
Route::get('/INDICADOR/Home/06/tabla', [IndicadorController::class, 'panelControlEduacionNuevoindicador06Tabla'])->name('panelcontrol.educacion.indicador.nuevos.06.tabla');
Route::get('/INDICADOR/Home/06/Excel/{anio}/{provincia}/{distrito}/{gestion}', [IndicadorController::class, 'panelControlEduacionNuevoindicador06Download']);
Route::get('/INDICADOR/Home/06/Excel/tabla2/{anio}/{provincia}/{distrito}/{gestion}', [IndicadorController::class, 'panelControlEduacionNuevoindicador06Download2']);

//Route::post('/INDICADOR/Distritos/{provincia}', [IndicadorController::class, 'cargardistritos'])->name('ind.ajax.cargardistritos');
//Route::get('/Home/gra6', [HomeController::class, 'educaciongrafica6'])->name('graficas.home.educacion.6');

Route::get('/Ubigeo/Provincia', [UbigeoController::class, 'cargarprovincia25'])->name('ubigeo.provincia.25');
Route::get('/Ubigeo/Distrito/{provincia}', [UbigeoController::class, 'cargardistrito25'])->name('ubigeo.distrito.25');

Route::get('/NivelModalidad/Buscar/{tipo}', [NivelModalidadController::class, 'buscarnivelmodalidad']);

Route::get('/InstitucionEducativa/Buscar/{local}', [InstEducativaController::class, 'buscar_codmodular'])->name('iiee.codmodular.buscar');
Route::get('/InstitucionEducativa/Distrito/{provincia}', [InstEducativaController::class, 'cargar_distrito'])->name('iiee.cargar.distrito');


Route::get('/educación/Importar/PadronWeb', [ImporPadronWebController::class, 'importar'])->name('ImporPadronWeb.importar');
Route::post('/ImporPadronWeb/Importar', [ImporPadronWebController::class, 'guardar'])->name('ImporPadronWeb.guardar');
Route::post('/ImporPadronWeb/LI/{importacion_id}', [ImporPadronWebController::class, 'ListaImportada'])->name('ImporPadronWeb.listarimportados');
Route::get('/ImporPadronWeb/Listar/ImportarDT', [ImporPadronWebController::class, 'ListarDTImportFuenteTodos'])->name('ImporPadronWeb.listar.importados');
//Route::post('/ImporPadronWeb/ListaImportada', [ImporPadronWebController::class, 'ListaImportada'])->name('imporpadronweb.listarimportados');
Route::get('/ImporPadronWeb/Eliminar/{id}', [ImporPadronWebController::class, 'eliminar'])->name('ImporPadronWeb.eliminar');
Route::get('/ImporPadronWeb/Exportar', [ImporPadronWebController::class, 'exportar'])->name('imporpadronweb.exportar');
Route::get('/ImporPadronWeb/Exportar/PadronWEB', [ImporPadronWebController::class, 'download'])->name('imporpadronweb.download');


//Route::get('/PadronWeb/codigo_modular/{codigo_modular}', [PadronWebController::class, 'buscariiee']);//esta por ver

Route::get('/FuenteImportacion/cargar/{sistema_id}', [FuenteImportacionController::class, 'cargar']);

Route::get('/ImporPadronEIB/Importar', [ImporPadronEibController::class, 'importar'])->name('PadronEIB.importar');
Route::post('/ImporPadronEIB/Importar', [ImporPadronEibController::class, 'guardar'])->name('imporpadroneib.guardar');
Route::get('/ImporPadronEIB/Listar/ImportarDT', [ImporPadronEibController::class, 'ListarDTImportFuenteTodos'])->name('imporpadroneib.listar.importados');
Route::post('/ImporPadronEIB/ListaImportada/{importacion_id}', [ImporPadronEibController::class, 'ListaImportada'])->name('imporpadroneib.listarimportados');
Route::get('/ImporPadronEIB/eliminar/{id}', [ImporPadronEibController::class, 'eliminar']);
Route::get('/ImporPadronEIB/ajax_cargar', [ImporPadronEibController::class, 'ajax_cargarnivel'])->name('padroneib.nivelmodalidad.cargar');


Route::get('/ImporMatricula/Importar', [ImporMatriculaController::class, 'importar'])->name('ImporMatricula.importar');
Route::post('/ImporMatricula/Importar', [ImporMatriculaController::class, 'guardar'])->name('ImporMatricula.guardar');
//Route::post('/ImporMatricula/ListaImportada/{importacion_id}', [ImporMatriculaController::class, 'ListaImportada'])->name('ImporMatricula.listarimportados');
Route::post('/ImporMatricula/ListaImportada', [ImporMatriculaController::class, 'ListaImportada'])->name('ImporMatricula.listarimportados');
Route::get('/ImporMatricula/ListaImportada_DataTable/{importacion_id}', [ImporMatriculaController::class, 'ListaImportada_DataTable'])->name('ImporMatricula.ListaImportada_DataTable');
//Route::get('/ImporMatricula/Aprobar/{importacion_id}', [ImporMatriculaController::class, 'aprobar'])->name('ImporMatricula.aprobar');
//Route::post('/ImporMatricula/Aprobar/procesar/{importacion_id}', [ImporMatriculaController::class, 'procesar'])->name('ImporMatricula.procesar');
Route::get('/ImporMatricula/Listar/ImportarDT', [ImporMatriculaController::class, 'ListarDTImportFuenteTodos'])->name('ImporMatricula.listar.importados');
Route::get('/ImporMatricula/eliminar/{id}', [ImporMatriculaController::class, 'eliminar']);
Route::get('/ImporMatricula/Exportar', [ImporMatriculaController::class, 'exportar'])->name('impormatricula.exportar');
Route::get('/ImporMatricula/Exportar/PadronSiagie', [ImporMatriculaController::class, 'download'])->name('impormatricula.download');

Route::get('/educación/Importar/Matricula', [ImporMatriculaGeneralController::class, 'importar'])->name('impormatriculageneral.importar');
Route::post('/ImporMatriculaGeneral/Importar', [ImporMatriculaGeneralController::class, 'guardar'])->name('impormatriculageneral.guardar');
Route::post('/ImporMatriculaGeneral/ListaImportada/{importacion_id}', [ImporMatriculaGeneralController::class, 'ListaImportada'])->name('impormatriculageneral.listarimportados');
Route::get('/ImporMatriculaGeneral/Listar/ImportarDT', [ImporMatriculaGeneralController::class, 'ListarDTImportFuenteTodos'])->name('impormatriculageneral.listar.importados');
Route::get('/ImporMatriculaGeneral/Eliminar/{id}', [ImporMatriculaGeneralController::class, 'eliminar'])->name('impormatriculageneral.eliminar');
//Route::get('/ImporMatriculaGeneral/Exportar', [ImporMatriculaGeneralController::class, 'exportar'])->name('impormatriculageneral.exportar');
//Route::get('/ImporMatriculaGeneral/Exportar/PadronSiagie', [ImporMatriculaGeneralController::class, 'download'])->name('impormatriculageneral.download');

Route::get('/ImporIS/Importar', [ImporISController::class, 'importar'])->name('imporis.importar');
Route::post('/ImporIS/Admision/Importar', [ImporISController::class, 'guardaradmision'])->name('imporis.guardar.admision');
Route::post('/ImporIS/Matricula/Importar', [ImporISController::class, 'guardarmatricula'])->name('imporis.guardar.matricula');
Route::get('/ImporIS/ListaImportada', [ImporISController::class, 'ListaImportada'])->name('imporis.listarimportados');
Route::get('/ImporIS/ListaImportada_DataTable/{importacion_id}', [ImporISController::class, 'ListaImportada_DataTable'])->name('imporis.ListaImportada_DataTable');
Route::get('/ImporIS/Listar/ImportarDT', [ImporISController::class, 'ListarDTImportFuenteTodos'])->name('imporis.listar.importados');
Route::get('/ImporIS/eliminar/{id}', [ImporISController::class, 'eliminar']);

Route::get('/educación/Importar/Nexus', [CuadroAsigPersonalController::class, 'importar'])->name('CuadroAsigPersonal.importar');
Route::post('/CuadroAsigPersonal/Importar', [CuadroAsigPersonalController::class, 'guardar'])->name('CuadroAsigPersonal.guardar');
/* Route::get('/CuadroAsigPersonal/lidt/{importacion_id}', [CuadroAsigPersonalController::class, 'ListaImportada_DataTable'])->name('CuadroAsigPersonal.ListaImportada_DataTable'); */
//Route::get('/CuadroAsigPersonal/Aprobar/{importacion_id}', [CuadroAsigPersonalController::class, 'aprobar'])->name('CuadroAsigPersonal.aprobar');
Route::post('/CuadroAsigPersonal/Aprobar/procesar/{importacion_id}', [CuadroAsigPersonalController::class, 'procesar'])->name('CuadroAsigPersonal.procesar');
Route::post('/CuadroAsigPersonal/ListaImportada/{importacion_id}', [CuadroAsigPersonalController::class, 'ListaImportada'])->name('CuadroAsigPersonal.listarimportados');
Route::get('/CuadroAsigPersonal/eliminar/{id}', [CuadroAsigPersonalController::class, 'eliminar'])->name('CuadroAsigPersonal.eliminar');

Route::get('/CuadroAsigPersonal/Principal', [CuadroAsigPersonalController::class, 'Principal'])->middleware('auth')->name('CuadroAsigPersonal.principal');
Route::post('/CuadroAsigPersonal/ReporteUgel', [CuadroAsigPersonalController::class, 'ReporteUgel'])->name('CuadroAsigPersonal.ReporteUgel');
Route::post('/CuadroAsigPersonal/ReporteDistrito', [CuadroAsigPersonalController::class, 'ReporteDistrito'])->name('CuadroAsigPersonal.ReporteDistrito');
Route::get('/CuadroAsigPersonal/ReportePedagogico', [CuadroAsigPersonalController::class, 'ReportePedagogico'])->name('CuadroAsigPersonal.ReportePedagogico');
Route::get('/CuadroAsigPersonal/ReporteBilingues', [CuadroAsigPersonalController::class, 'ReporteBilingues'])->name('CuadroAsigPersonal.Bilingues');
Route::post('/CuadroAsigPersonal/ReporteBilingues/GraficoBarrasPrincipal/{anio_id}', [CuadroAsigPersonalController::class, 'GraficoBarrasPrincipal'])->name('CuadroAsigPersonal.BilinguesGraficoBarrasPrincipal');

Route::post('/CuadroAsigPersonal/Docentes/ReportePrincipal/{tipoTrab_id}/{importacion_id}', [CuadroAsigPersonalController::class, 'DocentesReportePrincipal'])->name('Docentes.ReportePrincipal');
Route::post('/CuadroAsigPersonal/Docentes/GraficoBarras_DocentesPrincipal/{tipoTrab_id}/{importacion_id}', [CuadroAsigPersonalController::class, 'GraficoBarras_DocentesPrincipal'])->name('Docentes.GraficoBarras_DocentesPrincipal');
Route::post('/CuadroAsigPersonal/Docentes/GraficoBarras_DocentesNivelEducativo/{tipoTrab_id}/{importacion_id}', [CuadroAsigPersonalController::class, 'GraficoBarras_DocentesNivelEducativo'])->name('Docentes.GraficoBarras_DocentesNivelEducativo');

Route::post('/CuadroAsigPersonal/Docentes/GraficoBarras_Docentes_Ugeles/{importacion_id}', [CuadroAsigPersonalController::class, 'GraficoBarras_Docentes_Ugeles'])->name('Docentes.GraficoBarras_Docentes_Ugeles');
Route::get('/CuadroAsigPersonal/Listar/ImportarDT', [CuadroAsigPersonalController::class, 'ListarDTImportFuenteTodos'])->name('cuadroasigpersonal.listar.importados');

Route::get('/CuadroAsigPersonal/Exportar', [CuadroAsigPersonalController::class, 'exportar'])->name('cuadroasigpersonal.exportar');
Route::post('/CuadroAsigPersonal/ListaImportada', [CuadroAsigPersonalController::class, 'ListaImportada'])->name('cuadroasigpersonal.listarimportados');
Route::get('/CuadroAsigPersonal/Exportar/Nexus', [CuadroAsigPersonalController::class, 'download'])->name('cuadroasigpersonal.download');



Route::get('/Censo/Importar', [CensoController::class, 'importar'])->name('Censo.importar');
Route::post('/Censo/Importar', [CensoController::class, 'guardar'])->name('Censo.guardar');
Route::get('/Censo/ListaImportada/{importacion_id}', [CensoController::class, 'ListaImportada'])->name('Censo.Censo_Lista');
Route::get('/Censo/ListaImportada_DataTable/{importacion_id}', [CensoController::class, 'ListaImportada_DataTable'])->name('Censo.ListaImportada_DataTable');
Route::get('/Censo/Aprobar/{importacion_id}', [CensoController::class, 'aprobar'])->name('Censo.aprobar');
Route::post('/Censo/Aprobar/procesar/{importacion_id}', [CensoController::class, 'procesar'])->name('Censo.procesar');

Route::get('/Matricula/Importar', [MatriculaController::class, 'importar'])->name('Matricula.importar');
Route::post('/Matricula/Importar', [MatriculaController::class, 'guardar'])->name('Matricula.guardar');
Route::get('/Matricula/ListaImportada/{importacion_id}', [MatriculaController::class, 'ListaImportada'])->name('Matricula.Matricula_Lista');
Route::get('/Matricula/Aprobar/{importacion_id}', [MatriculaController::class, 'aprobar'])->name('Matricula.aprobar');
Route::post('/Matricula/Aprobar/procesar/{importacion_id}', [MatriculaController::class, 'procesar'])->name('Matricula.procesar');

Route::get('/Matricula/Principal', [MatriculaController::class, 'principal'])->middleware('auth')->name('Matricula.principal');
Route::post('/Matricula/inicio/{matricula_id}/{gestion}/{tipo}', [MatriculaController::class, 'inicio'])->name('Matricula.inicio');
Route::post('/Matricula/Detalle', [MatriculaController::class, 'Detalle'])->name('Matricula.Detalle');
Route::post('/Matricula/ReporteUgel/{anio_id}/{matricula_id}/{gestion}/{tipo}', [MatriculaController::class, 'ReporteUgel'])->name('Matricula.ReporteUgel');
Route::post('/Matricula/ReporteDistrito/{anio_id}/{matricula_id}/{gestion}/{tipo}', [MatriculaController::class, 'ReporteDistrito'])->name('Matricula.ReporteDistrito');
Route::post('/Matricula/ReporteInstitucion/{anio_id}/{matricula_id}/{gestion}/{tipo}', [MatriculaController::class, 'reporteInstitucion'])->name('Matricula.ReporteInstitucion');

Route::get('/Matricula/EIB', [MatriculaController::class, 'principal_EIB'])->middleware('auth')->name('Matricula.principal_EIB');

Route::post('/Matricula/GraficoBarras_MatriculaUgel/{importacion_id}', [MatriculaController::class, 'GraficoBarras_MatriculaUgel'])->name('Matricula.GraficoBarras_MatriculaUgel');
Route::post('/Matricula/GraficoBarras_MatriculaTipoGestion/{importacion_id}', [MatriculaController::class, 'GraficoBarras_MatriculaTipoGestion'])->name('Matricula.GraficoBarras_MatriculaTipoGestion');

Route::get('/Matricula/EBE', [MatriculaController::class, 'principal_EBE'])->middleware('auth')->name('Matricula.principal_EBE');

/*  */
Route::get('/Matricula/Institucion_DataTable/{matricula_id}/{nivel}/{gestion}/{tipo}', [MatriculaController::class, 'Institucion_DataTable'])->name('Matricula.Institucion_DataTable');

Route::post('/Matricula/Fechas/{anio_id}', [MatriculaController::class, 'Fechas'])->name('Matricula.Fechas');
Route::post('/Matricula/GraficoBarrasPrincipal/{anio_id}/{gestion}/{tipo}', [MatriculaController::class, 'GraficoBarrasPrincipal'])->name('Matricula.GraficoBarrasPrincipal');

Route::get('/Matricula/importarconsolidadoAnual', [MatriculaController::class, 'importarConsolidadoAnual'])->name('Matricula.importarConsolidadoAnual');
Route::post('/Matricula/importarconsolidadoAnual', [MatriculaController::class, 'guardarConsolidadoAnual'])->name('Matricula.guardarConsolidadoAnual');
Route::get('/Matricula/ListaImportadaConsolidadoAnual/{importacion_id}', [MatriculaController::class, 'ListaImportada_ConsolidadoAnual'])->name('Matricula.Matricula_Lista_ConsolidadoAnual');

Route::get('/Matricula/AprobarConsolidadoAnual/{importacion_id}', [MatriculaController::class, 'aprobarConsolidadoAnual'])->name('Matricula.aprobarConsolidadoAnual');
Route::post('/Matricula/AprobarConsolidadoAnual/procesarConsolidadoAnual/{importacion_id}', [MatriculaController::class, 'procesarConsolidadoAnual'])->name('Matricula.procesarConsolidadoAnual');

Route::get('/Matricula/consolidadoAnual/', [MatriculaController::class, 'principalConsolidadoAnual'])->name('Matricula.consolidadoAnual');
Route::post('/Matricula/ReporteUgelConsolidadoAnual/{anio_id}/{gestion}/{nivel}', [MatriculaController::class, 'ReporteUgelConsolidadoAnual'])->name('Matricula.ReporteUgelConsolidadoAnual');

Route::get('/InstEducativa/Principal', [InstEducativaController::class, 'principal'])->middleware('auth')->name('InstEducativa.principal');
Route::post('/InstEducativa/ReporteDistrito', [InstEducativaController::class, 'ReporteDistrito'])->name('InstEducativa.ReporteDistrito');
Route::post('/InstEducativa/GraficoBarras_Instituciones_Distrito', [InstEducativaController::class, 'GraficoBarras_Instituciones_Distrito'])->name('InstEducativa.GraficoBarras_Instituciones_Distrito');
/*  */

Route::get('/educación/AvanceMatricula', [MatriculaGeneralController::class, 'vista0001'])->name('indicador.nuevos.01');
Route::get('/INDICADOR/Home/01/Head', [MatriculaGeneralController::class, 'vista0001head'])->name('indicador.nuevos.01.head');
Route::get('/INDICADOR/Home/01/Tabla', [MatriculaGeneralController::class, 'vista0001Tabla'])->name('indicador.nuevos.01.tabla');
Route::get('/INDICADOR/Home/01/Excel/{div}/{anio}/{provincia}/{distrito}/{gestion}/{ugel}', [MatriculaGeneralController::class, 'vista0001Download']);

Route::get('/MatriculaGeneral/NivelEBR/', [MatriculaGeneralController::class, 'niveleducativoEBR'])->name('matriculageneral.niveleducativo.principal');
Route::get('/MatriculaGeneral/NivelEBR/tablas', [MatriculaGeneralController::class, 'niveleducativoEBRtabla'])->name('matriculageneral.niveleducativo.tablas');
Route::get('/MatriculaGeneral/NivelEBR/Excel/{div}/{anio}/{provincia}/{distrito}/{nivel}', [MatriculaGeneralController::class, 'niveleducativoEBRDownload']);

Route::get('/MatriculaGeneral/NivelEBE/', [MatriculaGeneralController::class, 'niveleducativoEBE'])->name('matriculageneral.niveleducativo.ebe.principal');
Route::get('/MatriculaGeneral/NivelEBE/tablas', [MatriculaGeneralController::class, 'niveleducativoEBEtabla'])->name('matriculageneral.niveleducativo.ebe.tablas');
Route::get('/MatriculaGeneral/NivelEBE/Excel/{div}/{anio}/{provincia}/{distrito}/{nivel}', [MatriculaGeneralController::class, 'niveleducativoEBEDownload']);

Route::get('/MatriculaGeneral/NivelEBA/', [MatriculaGeneralController::class, 'niveleducativoEBA'])->name('matriculageneral.niveleducativo.eba.principal');
Route::get('/MatriculaGeneral/NivelEBA/tablas', [MatriculaGeneralController::class, 'niveleducativoEBAtabla'])->name('matriculageneral.niveleducativo.eba.tablas');
Route::get('/MatriculaGeneral/NivelEBA/Excel/{div}/{anio}/{provincia}/{distrito}/{nivel}', [MatriculaGeneralController::class, 'niveleducativoEBADownload']);

Route::get('/educación/BásicaRegular', [MatriculaGeneralController::class, 'basicaregular'])->name('matriculageneral.ebr.principal');
Route::get('/MatriculaGeneral/EBR/tablas', [MatriculaGeneralController::class, 'basicaregulartabla'])->name('matriculageneral.ebr.tablas');
Route::get('/MatriculaGeneral/EBR/Excel/{div}/{anio}/{ugel}/{gestion}/{area}/{provincia}', [MatriculaGeneralController::class, 'basicaregularDownload']);

Route::get('/educación/BásicaEspecial', [MatriculaGeneralController::class, 'basicaespecial'])->name('matriculageneral.ebe.principal');
Route::get('/MatriculaGeneral/EBE/tablas', [MatriculaGeneralController::class, 'basicaespecialtabla'])->name('matriculageneral.ebe.tablas');
Route::get('/MatriculaGeneral/EBE/Excel/{div}/{anio}/{ugel}/{gestion}/{area}/{provincia}', [MatriculaGeneralController::class, 'basicaespecialDownload']);

Route::get('/educación/BásicaAlternativa', [MatriculaGeneralController::class, 'basicaalternativa'])->name('matriculageneral.eba.principal');
Route::get('/MatriculaGeneral/EBA/tablas', [MatriculaGeneralController::class, 'basicaalternativatabla'])->name('matriculageneral.eba.tablas');
Route::get('/MatriculaGeneral/EBA/Excel/{div}/{anio}/{ugel}/{gestion}/{area}/{provincia}', [MatriculaGeneralController::class, 'basicaalternativaDownload']);

/*  */
Route::get('/MatriculaDetalle/avance/', [MatriculaDetalleController::class, 'avance'])->name('matriculadetalle.avance');
Route::post('/MatriculaDetalle/avance/tabla0', [MatriculaDetalleController::class, 'cargartabla0'])->name('matriculadetalle.avance.tabla0');
Route::post('/MatriculaDetalle/avance/tabla1', [MatriculaDetalleController::class, 'cargartabla1'])->name('matriculadetalle.avance.tabla1');
Route::post('/MatriculaDetalle/avance/grafica1', [MatriculaDetalleController::class, 'cargargrafica1'])->name('matriculadetalle.avance.grafica1');
Route::get('/MatriculaDetalle/rojos/{mes}/{nivel}/{ano}', [MatriculaDetalleController::class, 'rojos']);

Route::get('/MatriculaDetalle/EBR/', [MatriculaDetalleController::class, 'basicaregular'])->name('matriculadetalle.basicaregular');
Route::post('/MatriculaDetalle/EBR/grafica1', [MatriculaDetalleController::class, 'cargarEBRgrafica1'])->name('matriculadetalle.ebr.grafica1');
Route::post('/MatriculaDetalle/EBR/grafica2', [MatriculaDetalleController::class, 'cargarEBRgrafica2'])->name('matriculadetalle.ebr.grafica2');
Route::post('/MatriculaDetalle/EBR/grafica3', [MatriculaDetalleController::class, 'cargarEBRgrafica3'])->name('matriculadetalle.ebr.grafica3');
Route::post('/MatriculaDetalle/EBR/grafica4', [MatriculaDetalleController::class, 'cargarEBRgrafica4'])->name('matriculadetalle.ebr.grafica4');
Route::post('/MatriculaDetalle/EBR/grafica5', [MatriculaDetalleController::class, 'cargarEBRgrafica5'])->name('matriculadetalle.ebr.grafica5');
Route::post('/MatriculaDetalle/EBR/grafica6', [MatriculaDetalleController::class, 'cargarEBRgrafica6'])->name('matriculadetalle.ebr.grafica6');
Route::post('/MatriculaDetalle/EBR/grafica7', [MatriculaDetalleController::class, 'cargarEBRgrafica7'])->name('matriculadetalle.ebr.grafica7');
Route::post('/MatriculaDetalle/EBR/tabla1', [MatriculaDetalleController::class, 'cargarEBRtabla1'])->name('matriculadetalle.ebr.tabla1');
Route::post('/MatriculaDetalle/EBR/tabla2', [MatriculaDetalleController::class, 'cargarEBRtabla2'])->name('matriculadetalle.ebr.tabla2');
Route::post('/MatriculaDetalle/EBR/tabla3', [MatriculaDetalleController::class, 'cargarEBRtabla3'])->name('matriculadetalle.ebr.tabla3');
Route::post('/MatriculaDetalle/EBR/tabla3_1', [MatriculaDetalleController::class, 'cargarEBRtabla3_1'])->name('matriculadetalle.ebr.tabla3_1');
Route::post('/MatriculaDetalle/EBR/tabla3_2', [MatriculaDetalleController::class, 'cargarEBRtabla3_2'])->name('matriculadetalle.ebr.tabla3_2');
Route::post('/MatriculaDetalle/EBR/tabla3_3', [MatriculaDetalleController::class, 'cargarEBRtabla3_3'])->name('matriculadetalle.ebr.tabla3_3');
Route::post('/MatriculaDetalle/EBR/tabla4', [MatriculaDetalleController::class, 'cargarEBRtabla4'])->name('matriculadetalle.ebr.tabla4');
Route::post('/MatriculaDetalle/EBR/tabla4_1', [MatriculaDetalleController::class, 'cargarEBRtabla4_1'])->name('matriculadetalle.ebr.tabla4_1');
Route::post('/MatriculaDetalle/EBR/tabla4_2', [MatriculaDetalleController::class, 'cargarEBRtabla4_2'])->name('matriculadetalle.ebr.tabla4_2');
//Route::post('/MatriculaDetalle/EBR/tabla4_3', [MatriculaDetalleController::class, 'cargarEBRtabla4_3'])->name('matriculadetalle.ebr.tabla4_3');
Route::post('/MatriculaDetalle/EBR/tabla5', [MatriculaDetalleController::class, 'cargarEBRtabla5'])->name('matriculadetalle.ebr.tabla5');
Route::post('/MatriculaDetalle/EBR/tabla5_1', [MatriculaDetalleController::class, 'cargarEBRtabla5_1'])->name('matriculadetalle.ebr.tabla5_1');
Route::post('/MatriculaDetalle/EBR/tabla5_2', [MatriculaDetalleController::class, 'cargarEBRtabla5_2'])->name('matriculadetalle.ebr.tabla5_2');
//Route::post('/MatriculaDetalle/EBR/tabla5_3', [MatriculaDetalleController::class, 'cargarEBRtabla5_3'])->name('matriculadetalle.ebr.tabla5_3');
Route::post('/MatriculaDetalle/EBR/tabla6', [MatriculaDetalleController::class, 'cargarEBRtabla6'])->name('matriculadetalle.ebr.tabla6');

Route::get('/MatriculaDetalle/EBE/', [MatriculaDetalleController::class, 'basicaespecial'])->name('matriculadetalle.basicaespecial');
Route::post('/MatriculaDetalle/EBE/grafica1', [MatriculaDetalleController::class, 'cargarEBEgrafica1'])->name('matriculadetalle.ebe.grafica1');
Route::post('/MatriculaDetalle/EBE/grafica2', [MatriculaDetalleController::class, 'cargarEBEgrafica2'])->name('matriculadetalle.ebe.grafica2');
Route::post('/MatriculaDetalle/EBE/grafica3', [MatriculaDetalleController::class, 'cargarEBEgrafica3'])->name('matriculadetalle.ebe.grafica3');
Route::post('/MatriculaDetalle/EBE/grafica4', [MatriculaDetalleController::class, 'cargarEBEgrafica4'])->name('matriculadetalle.ebe.grafica4');
Route::post('/MatriculaDetalle/EBE/tabla1', [MatriculaDetalleController::class, 'cargarEBEtabla1'])->name('matriculadetalle.ebe.tabla1');
Route::post('/MatriculaDetalle/EBE/tabla2', [MatriculaDetalleController::class, 'cargarEBEtabla2'])->name('matriculadetalle.ebe.tabla2');

Route::get('/educación/InterculturalBilingue', [MatriculaDetalleController::class, 'interculturalbilingue'])->name('matriculadetalle.interculturalbilingue');
Route::post('/MatriculaDetalle/EIB/grafica1', [MatriculaDetalleController::class, 'cargarEIBgrafica1'])->name('matriculadetalle.eib.grafica1');
Route::post('/MatriculaDetalle/EIB/grafica2', [MatriculaDetalleController::class, 'cargarEIBgrafica2'])->name('matriculadetalle.eib.grafica2');
Route::post('/MatriculaDetalle/EIB/grafica3', [MatriculaDetalleController::class, 'cargarEIBgrafica3'])->name('matriculadetalle.eib.grafica3');
Route::post('/MatriculaDetalle/EIB/grafica4', [MatriculaDetalleController::class, 'cargarEIBgrafica4'])->name('matriculadetalle.eib.grafica4');
Route::post('/MatriculaDetalle/EIB/tabla1', [MatriculaDetalleController::class, 'cargarEIBtabla1'])->name('matriculadetalle.eib.tabla1');
Route::post('/MatriculaDetalle/EIB/tabla2', [MatriculaDetalleController::class, 'cargarEIBtabla2'])->name('matriculadetalle.eib.tabla2');

Route::get('/educación/Importar/CensoDocente', [ImporCensoDocenteController::class, 'importar'])->name('imporcensodocente.importar');
Route::post('/ImporCensoDocente/Importar', [ImporCensoDocenteController::class, 'guardar'])->name('imporcensodocente.guardar');
Route::get('/ImporCensoDocente/Listar/ImportarDT', [ImporCensoDocenteController::class, 'ListarDTImportFuenteTodos'])->name('imporcensodocente.listar.importados');
Route::get('/ImporCensoDocente/LI/{importacion_id}', [ImporCensoDocenteController::class, 'ListaImportada'])->name('imporcensodocente.listarimportados');
Route::get('/ImporCensoDocente/eliminar/{id}', [ImporCensoDocenteController::class, 'eliminar'])->name('imporcensodocente.eliminar');

Route::get('/educación/Importar/CensoMatricula', [ImporCensoMatriculaController::class, 'importar'])->name('imporcensomatricula.importar');
Route::post('/ImporCensoMatricula/Importar', [ImporCensoMatriculaController::class, 'guardar'])->name('imporcensomatricula.guardar');
Route::get('/ImporCensoMatricula/L/ImportarDT', [ImporCensoMatriculaController::class, 'ListarDTImportFuenteTodos'])->name('imporcensomatricula.listar.importados');
Route::post('/ImporCensoMatricula/LI/{importacion_id}', [ImporCensoMatriculaController::class, 'ListaImportada'])->name('imporcensomatricula.listarimportados');
Route::get('/ImporCensoMatricula/E/{id}', [ImporCensoMatriculaController::class, 'eliminar'])->name('imporcensomatricula.eliminar');

Route::get('/educación/Importar/Tableta', [ImporTabletaController::class, 'importar'])->name('importableta.importar');
Route::post('/ImporTableta/Importar', [ImporTabletaController::class, 'guardar'])->name('importableta.guardar');
Route::get('/ImporTableta/Listar/ImportarDT', [ImporTabletaController::class, 'ListarDTImportFuenteTodos'])->name('importableta.listar.importados');
Route::get('/ImporTableta/ListaImportada/{importacion_id}', [ImporTabletaController::class, 'ListaImportada'])->name('importableta.listarimportados');
Route::get('/ImporTableta/eliminar/{id}', [ImporTabletaController::class, 'eliminar'])->name('importableta.eliminar');
//Route::get('/ImporPoblacion/Exportar', [ImporPoblacionController::class, 'exportar'])->name('imporpoblacion.exportar');
//Route::get('/ImporPoblacion/Exportar/PadronSiagie', [ImporPoblacionController::class, 'download'])->name('imporpoblacion.download');

//Route::get('/Tableta/Aprobar/{importacion_id}', [TabletaController::class, 'aprobar'])->name('Tableta.aprobar');
//Route::post('/Tableta/Aprobar/procesar/{importacion_id}', [TabletaController::class, 'procesar'])->name('Tableta.procesar');

Route::get('/educación/Tableta', [TabletaController::class, 'principal'])->middleware('auth')->name('tableta.principal');
Route::get('/Tableta/Principal/Head', [TabletaController::class, 'principalHead'])->name('tableta.principal.head');
Route::get('/Tableta/Principal/Tabla', [TabletaController::class, 'principalTabla'])->name('tableta.principal.tabla');

Route::post('/Tableta/Fechas/{anio_id}', [TabletaController::class, 'Fechas'])->name('Tableta.Fechas');
Route::post('/Tableta/ReporteUgel/{anio_id}/{tableta_id}', [TabletaController::class, 'ReporteUgel'])->name('Tableta.ReporteUgel');
Route::post('/Tableta/GraficoBarrasPrincipal/{anio_id}', [TabletaController::class, 'GraficoBarrasPrincipal'])->name('Tableta.GraficoBarrasPrincipal');

/*  */

Route::get('/ImporTextosEscolares/Importar', [ImporTabletaController::class, 'importar'])->name('importextoescolar.importar');
Route::post('/ImporTextosEscolares/Importar', [ImporTabletaController::class, 'guardar'])->name('importextoescolar.guardar');
Route::get('/ImporTextosEscolares/Listar/ImportarDT', [ImporTabletaController::class, 'ListarDTImportFuenteTodos'])->name('importextoescolar.listar.importados');
Route::get('/ImporTextosEscolares/ListaImportada/{importacion_id}', [ImporTabletaController::class, 'ListaImportada'])->name('importextoescolar.listarimportados');
Route::get('/ImporTextosEscolares/eliminar/{id}', [ImporTabletaController::class, 'eliminar'])->name('importextoescolar.eliminar');

/* bloquear */
Route::get('/TextosEscolares/Importar', [TextosEscolaresController::class, 'importar'])->name('TextosEscolares.importar');
Route::post('/TextosEscolares/Importar', [TextosEscolaresController::class, 'guardar'])->name('TextosEscolares.guardar');
Route::get('/TextosEscolares/Principal', [TextosEscolaresController::class, 'principal'])->middleware('auth')->name('TextosEscolares.principal');
Route::post('/TextosEscolares/Fechas/{anio_id}', [TextosEscolaresController::class, 'Fechas'])->name('TextosEscolares.Fechas');
Route::post('/TextosEscolares/ReporteUgel/{importacion_id}', [TextosEscolaresController::class, 'ReporteUgel'])->name('TextosEscolares.ReporteUgel');


/* ************ */
Route::get('/educación/TecnicoProductiva', [TecnicoProductivaController::class, 'principal'])->middleware('auth')->name('tecnicoproductiva.principal');
Route::get('/TecnicoProductiva/Principal/Head', [TecnicoProductivaController::class, 'principalHead'])->name('tecnicoproductiva.principal.head');
Route::get('/TecnicoProductiva/Principal/Tabla', [TecnicoProductivaController::class, 'principalTabla'])->name('tecnicoproductiva.principal.tabla');
Route::get('/TecnicoProductiva/Ugeles', [TecnicoProductivaController::class, 'ugel'])->name('tecnicoproductiva.ugel');
Route::get('/TecnicoProductiva/Areas', [TecnicoProductivaController::class, 'area'])->name('tecnicoproductiva.area');
Route::get('/TecnicoProductiva/Exportar/Excel/{anio}/{ugel}/{area}/{gestion}', [TecnicoProductivaController::class, 'download']);

/* Route::post('/TecnicoProductiva/Fechas/{anio_id}', [TecnicoProductivaController::class, 'Fechas'])->name('tecnicoproductiva.Fechas');
Route::post('/TecnicoProductiva/ReporteUgel/{anio_id}/{tableta_id}', [TecnicoProductivaController::class, 'ReporteUgel'])->name('tecnicoproductiva.ReporteUgel');
Route::post('/TecnicoProductiva/GraficoBarrasPrincipal/{anio_id}', [TecnicoProductivaController::class, 'GraficoBarrasPrincipal'])->name('tecnicoproductiva.GraficoBarrasPrincipal'); */

/*
Route::get('/TecnicoProductiva/Principal', [TabletaController::class, 'principal'])->middleware('auth')->name('tecnicoproductiva.principal');
Route::get('/TecnicoProductiva/Principal/Head', [TabletaController::class, 'principalHead'])->name('tecnicoproductiva.principal.head');
Route::get('/TecnicoProductiva/Principal/Tabla', [TabletaController::class, 'principalTabla'])->name('tecnicoproductiva.principal.tabla');

Route::post('/TecnicoProductiva/Fechas/{anio_id}', [TabletaController::class, 'Fechas'])->name('tecnicoproductiva.Fechas');
Route::post('/TecnicoProductiva/ReporteUgel/{anio_id}/{tableta_id}', [TabletaController::class, 'ReporteUgel'])->name('tecnicoproductiva.ReporteUgel');
Route::post('/TecnicoProductiva/GraficoBarrasPrincipal/{anio_id}', [TabletaController::class, 'GraficoBarrasPrincipal'])->name('tecnicoproductiva.GraficoBarrasPrincipal');
*/
/***************** */

Route::get('/educación/SuperiorPedagogico', [SuperiorPedagogicoController::class, 'principal'])->middleware('auth')->name('superiorpedagogico.principal');
Route::get('/SuperiorPedagogico/Principal/Head', [SuperiorPedagogicoController::class, 'principalHead'])->name('superiorpedagogico.principal.head');
Route::get('/SuperiorPedagogico/Principal/Tabla', [SuperiorPedagogicoController::class, 'principalTabla'])->name('superiorpedagogico.principal.tabla');
Route::get('/SuperiorPedagogico/Ugeles', [SuperiorPedagogicoController::class, 'ugel'])->name('superiorpedagogico.ugel');
Route::get('/SuperiorPedagogico/Areas', [SuperiorPedagogicoController::class, 'area'])->name('superiorpedagogico.area');
Route::get('/SuperiorPedagogico/Exportar/Excel/{anio}/{ugel}/{area}/{gestion}', [SuperiorPedagogicoController::class, 'download']);

Route::get('/educación/SuperiorTecnologico', [SuperiorTecnologicoController::class, 'principal'])->middleware('auth')->name('superiortecnologico.principal');
Route::get('/SuperiorTecnologico/Principal/Head', [SuperiorTecnologicoController::class, 'principalHead'])->name('superiortecnologico.principal.head');
Route::get('/SuperiorTecnologico/Principal/Tabla', [SuperiorTecnologicoController::class, 'principalTabla'])->name('superiortecnologico.principal.tabla');
Route::get('/SuperiorTecnologico/Ugeles', [SuperiorTecnologicoController::class, 'ugel'])->name('superiortecnologico.ugel');
Route::get('/SuperiorTecnologico/Areas', [SuperiorTecnologicoController::class, 'area'])->name('superiortecnologico.area');
Route::get('/SuperiorTecnologico/Exportar/Excel/{anio}/{ugel}/{area}/{gestion}', [SuperiorTecnologicoController::class, 'download']);

Route::get('/educación/SuperiorArtistico', [SuperiorArtisticoController::class, 'principal'])->middleware('auth')->name('superiorartistico.principal');
Route::get('/SuperiorArtistico/Principal/Head', [SuperiorArtisticoController::class, 'principalHead'])->name('superiorartistico.principal.head');
Route::get('/SuperiorArtistico/Principal/Tabla', [SuperiorArtisticoController::class, 'principalTabla'])->name('superiorartistico.principal.tabla');
Route::get('/SuperiorArtistico/Ugeles', [SuperiorArtisticoController::class, 'ugel'])->name('superiorartistico.ugel');
Route::get('/SuperiorArtistico/Areas', [SuperiorArtisticoController::class, 'area'])->name('superiorartistico.area');
Route::get('/SuperiorArtistico/Exportar/Excel/{anio}/{ugel}/{area}/{gestion}', [SuperiorArtisticoController::class, 'download']);

Route::get('/educación/ServiciosBasicos', [ServiciosBasicosController::class, 'principal'])->name('serviciosbasicos.principal');
Route::get('/ServiciosBasicos/Tablas', [ServiciosBasicosController::class, 'principalTabla'])->name('serviciosbasicos.principal.tablas');
Route::get('/ServiciosBasicos/Excel/{div}/{anio}/{ugel}/{gestion}/{area}/{servicio}', [ServiciosBasicosController::class, 'principalDownload']);

Route::get('/Importacion', [ImportacionController::class, 'inicio'])->name('importacion.inicio');
Route::get('/Importacion/importaciones_DataTable/', [ImportacionController::class, 'importacionesLista_DataTable'])->name('importacion.importacionesLista_DataTable');
Route::get('/Importacion/Eliminar/{id}', [ImportacionController::class, 'eliminar'])->name('importacion.Eliminar');
Route::get('/Importacion/GetEliminar/{id}', [ImportacionController::class, 'setEliminar']);
Route::post('/Importacion/Importados/', [ImportacionController::class, 'ListarDTImportFuenteTodos'])->name('importacion.listar.importados');

Route::get('/ECE/Importar', [EceController::class, 'importar'])->name('ece.importar');
Route::get('/ECE/Importar/Aprobar/{importacion_id}', [EceController::class, 'importarAprobar'])->name('ece.importar.aprobar');
Route::get('/ECE/Importar/Aprobar/Guardar/{importacion}', [EceController::class, 'importarAprobarGuardar'])->name('ece.importar.aprobar.guardar');
Route::post('/ECE/ImportarGuardar', [EceController::class, 'importarGuardar'])->name('ece.importar.store');
Route::get('/ECE/listadoDT/{importacion_id}', [EceController::class, 'importarListadoDT'])->name('ece.importar.listadoDT');
Route::get('/ECE/Listado/{importacion_id}', [EceController::class, 'importarListado'])->name('ece.importar.listado');
Route::get('/ECE/Listar/ImportarDT', [EceController::class, 'ListarEceImportadosDT'])->name('ece.listar.importados');
Route::get('/ECE/Eliminar/ImportarDT/{id}', [EceController::class, 'EliminarImportados']);
Route::post('/ECE/CargarGrados', [EceController::class, 'cargargrados'])->name('ece.ajax.cargargrados');
Route::get('/INDICADOR/Menu/{clasificador}', [IndicadorController::class, 'indicadorEducacionMenu'])->name('indicador.menu');
Route::get('/Clasificador/{clase_codigo}', [ClasificadorController::class, 'menu_porClase'])->name('Clasificador.menu');

Route::get('/INDICADOR/ece/{indicador}', [IndicadorController::class, 'indicadorEducacion'])->name('indicador.01');
Route::get('/INDICADOR/drvcs/{indicador}', [IndicadorController::class, 'indicadorDRVCS'])->name('indicador.02');
Route::get('/INDICADOR/pdrc/{indicador}', [IndicadorController::class, 'indicadorPDRC'])->name('indicador.04');
Route::get('/INDICADOR/obj/{indicador}', [IndicadorController::class, 'indicadorOEI'])->name('indicador.05');

Route::get('/INDICADOR/dece/{indicador_id}/{grado}/{tipo}/{materia}', [IndicadorController::class, 'indDetEdu'])->name('ind.det.edu');
Route::get('/INDICADOR/rece/{indicador_id}/{grado}/{tipo}/{materia}', [IndicadorController::class, 'indResEdu'])->name('ind.res.edu');

Route::post('/INDICADOR/Satisfactorio', [IndicadorController::class, 'reporteSatisfactorioMateria'])->name('ind.ajax.satisfactorio');
Route::post('/INDICADOR/ReporteUbigeoAjax', [IndicadorController::class, 'reporteUbigeoAjax'])->name('ind.ajax.reporteubigeo');
Route::get('/INDICADOR/ReporteGestionAreaDT/{anio}/{grado}/{tipo}/{materia}/{gestion}/{area}', [IndicadorController::class, 'reporteGestionAreaDT']);
Route::get('/INDICADOR/ReporteCPVivDT/{provincia}/{distrito}/{importacion_id}/{indicador_id}', [IndicadorController::class, 'ReporteCPVivDT']);
Route::post('/INDICADOR/Distritos/{provincia}', [IndicadorController::class, 'cargardistritos'])->name('ind.ajax.cargardistritos');
Route::post('/INDICADOR/PNSR1/{provincia}/{distrito}/{indicador_id}/{fecha}', [IndicadorController::class, 'indicadorvivpnsrcab'])->name('ind.ajax.pnsr1');
Route::post('/INDICADOR/PNSR2/{provincia}/{distrito}/{indicador_id}/{fecha}', [IndicadorController::class, 'indicadorviv2pnsrcab'])->name('ind.ajax.pnsr2');
Route::post('/INDICADOR/ece5/{provincia}/{distrito}/{indicador_id}/{anio_id}', [IndicadorController::class, 'ajaxEdu5v1'])->name('ind.ajax.edu5.1');
Route::post('/INDICADOR/ece6/{provincia}/{distrito}/{indicador_id}/{anio_id}', [IndicadorController::class, 'ajaxEdu6v1'])->name('ind.ajax.edu6.1');

Route::post('/INDICADOR/ece/plaza', [PLazaController::class, 'datoIndicadorPLaza'])->name('ind01.plaza.dato');

Route::get('/INDICADOR/Principal', [IndicadorController::class, 'principal'])->middleware('auth')->name('indicador.principal');
Route::get('/INDICADOR/AJAX/LISTAR', [IndicadorController::class, 'ListarDT'])->name('indicador.ajax.listar');

Route::get('/educación/PersonalDocente', [CensoDocenteController::class, 'PersonalDocente'])->middleware('auth')->name('Docentes.principal');
Route::get('/Plaza/Docentes/Principal222', [CensoDocenteController::class, 'PersonalDocenteTabla'])->middleware('auth')->name('censodocente.personaldocente');

//Route::get('/Plaza/Docentes/Principal', [PLazaController::class, 'DocentesPrincipal'])->middleware('auth')->name('Docentes.principal');
Route::get('/Plaza/Distritos/{provincia}', [PLazaController::class, 'cargardistritos'])->name('plaza.cargardistritos');
//Route::get('/Plaza/Mes/{anio}', [PLazaController::class, 'cargarmes']);
//Route::get('/Plaza/UltimoImportado/{anio}/{mes}', [PLazaController::class, 'cargarultimoimportado']);
////Route::get('/Plaza/Docentes/{importacion_id}/{anio}', [PLazaController::class, 'menuDocentes']);
//Route::post('/Plaza/Docentes/DocentePrincial', [PLazaController::class, 'DocentesPrincipalHead'])->name('nexus.contratacion.head');
//Route::post('/Plaza/Docentes/DocentePrincial/gra1', [PLazaController::class, 'DocentesPrincipalgra1'])->name('nexus.contratacion.gra1');
//Route::post('/Plaza/Docentes/DocentePrincial/gra2', [PLazaController::class, 'DocentesPrincipalgra2'])->name('nexus.contratacion.gra2');
//Route::post('/Plaza/Docentes/DocentePrincial/gra3', [PLazaController::class, 'DocentesPrincipalgra3'])->name('nexus.contratacion.gra3');
//Route::post('/Plaza/Docentes/DocentePrincial/gra4', [PLazaController::class, 'DocentesPrincipalgra4'])->name('nexus.contratacion.gra4');
//Route::post('/Plaza/Docentes/DocentePrincial/gra5', [PLazaController::class, 'DocentesPrincipalgra5'])->name('nexus.contratacion.gra5');
//Route::post('/Plaza/Docentes/DocentePrincial/gra6', [PLazaController::class, 'DocentesPrincipalgra6'])->name('nexus.contratacion.gra6');
//Route::post('/Plaza/Docentes/DocentePrincial/gra7', [PLazaController::class, 'DocentesPrincipalgra7'])->name('nexus.contratacion.gra7');
//Route::post('/Plaza/Docentes/DocentePrincial/gra8', [PLazaController::class, 'DocentesPrincipalgra8'])->name('nexus.contratacion.gra8');
//Route::post('/Plaza/Docentes/DocentePrincial/gra9', [PLazaController::class, 'DocentesPrincipalgra9'])->name('nexus.contratacion.gra9');
//Route::post('/Plaza/Docentes/DocentePrincial/gra10', [PLazaController::class, 'DocentesPrincipalgra10'])->name('nexus.contratacion.gra10');
//Route::post('/Plaza/Docentes/DocentePrincial/DT1', [PLazaController::class, 'DocentesPrincipalDT1'])->name('nexus.contratacion.dt1');
//Route::post('/Plaza/Docentes/DocentePrincial/DT2', [PLazaController::class, 'DocentesPrincipalDT2'])->name('nexus.contratacion.dt2');
//Route::post('/Plaza/Docentes/DocentePrincial/DT3', [PLazaController::class, 'DocentesPrincipalDT3'])->name('nexus.contratacion.dt3');
//Route::post('/Plaza/Docentes/DocentePrincial/DT4', [PLazaController::class, 'DocentesPrincipalDT4'])->name('nexus.contratacion.dt4');
//Route::post('/Plaza/Docentes/DocentePrincial/DT5', [PLazaController::class, 'DocentesPrincipalDT5'])->name('nexus.contratacion.dt5');
//Route::post('/Plaza/Docentes/DocentePrincial/DT6', [PLazaController::class, 'DocentesPrincipalDT6'])->name('nexus.contratacion.dt6');

Route::get('/Plaza/Docentes/CoberturaDePlaza', [PLazaController::class, 'coberturaplaza'])->name('nexus.cobertura.head');
Route::post('/Plaza/Plazas/tabla1', [PLazaController::class, 'cargarcoberturaplazatabla1'])->name('nexus.cobertura.tabla1');
Route::post('/Plaza/Plazas/tabla2', [PLazaController::class, 'cargarcoberturaplazatabla2'])->name('nexus.cobertura.tabla2');
Route::post('/Plaza/Plazas/grafica1', [PLazaController::class, 'cargarcoberturaplazagrafica1'])->name('nexus.cobertura.grafica1');


Route::get('/ImporRER/Importar', [ImporRERController::class, 'importar'])->name('imporrer.importar');
Route::post('/ImporRER/Importar', [ImporRERController::class, 'guardar'])->name('imporrer.guardar');
Route::post('/ImporRER/ListaImportada/{importacion_id}', [ImporRERController::class, 'ListaImportada'])->name('imporrer.listarimportados');

Route::get('/educación/Mantenimiento/RER', [RERController::class, 'principal'])->middleware('auth')->name('mantenimiento.rer.principal');
Route::post('/Mantenimiento/RER/Importados/', [RERController::class, 'ListarDTImportFuenteTodos'])->name('mantenimiento.rer.listar.importados');
Route::get('/Mantenimiento/RER/ajax_edit/{id}', [RERController::class, 'ajax_edit']);
Route::post('/Mantenimiento/RER/ajax_add/', [RERController::class, 'ajax_add']);
Route::post('/Mantenimiento/RER/ajax_update/', [RERController::class, 'ajax_update']);
Route::get('/Mantenimiento/RER/ajax_estado/{id}', [RERController::class, 'ajax_estado']);
Route::get('/Mantenimiento/RER/ajax_delete/{id}', [RERController::class, 'ajax_delete']);

Route::get('/RER/ajax_cargar', [RERController::class, 'ajax_cargar'])->name('rer.cargar');

Route::get('/educación/Mantenimiento/PadronRER', [PadronRERController::class, 'principal'])->middleware('auth')->name('mantenimiento.padronrer.principal');
Route::post('/Mantenimiento/PadronRER/Importados/', [PadronRERController::class, 'ListarDTImportFuenteTodos'])->name('mantenimiento.padronrer.listar.importados');
Route::get('/Mantenimiento/PadronRER/ajax_edit/{id}', [PadronRERController::class, 'ajax_edit']);
Route::post('/Mantenimiento/PadronRER/ajax_add/', [PadronRERController::class, 'ajax_add']);
Route::post('/Mantenimiento/PadronRER/ajax_update/', [PadronRERController::class, 'ajax_update']);
//Route::get('/Mantenimiento/PadronRER/ajax_estado/{id}', [PadronRERController::class, 'ajax_estado']);
Route::get('/Mantenimiento/PadronRER/ajax_delete/{id}', [PadronRERController::class, 'ajax_delete']);
Route::get('/Mantenimiento/PadronRER/RedEducativa/autocompletar', [RERController::class, 'completarred'])->name('mantenimiento.padronrer.completar.rer');
Route::get('/Mantenimiento/PadronRER/IIEE/autocompletar', [InstEducativaController::class, 'completariiee'])->name('mantenimiento.padronrer.completar.iiee');
Route::get('/Mantenimiento/PadronRER/ajax_cargar', [PadronRERController::class, 'ajax_cargarnivel'])->name('padronrer.nivelmodalidad.cargar');

Route::get('/educación/Mantenimiento/Lengua', [LenguaController::class, 'principal'])->middleware('auth')->name('mantenimiento.lengua.principal');
Route::get('/Mantenimiento/Lengua/listar/', [LenguaController::class, 'ListarDTImportFuenteTodos'])->name('mantenimiento.lengua.listar');
Route::get('/Mantenimiento/Lengua/ajax_edit/{id}', [LenguaController::class, 'ajax_edit']);
Route::post('/Mantenimiento/Lengua/ajax_add/', [LenguaController::class, 'ajax_add']);
Route::post('/Mantenimiento/Lengua/ajax_update/', [LenguaController::class, 'ajax_update']);
Route::get('/Mantenimiento/Lengua/ajax_estado/{id}', [LenguaController::class, 'ajax_estado']);
Route::get('/Mantenimiento/Lengua/ajax_delete/{id}', [LenguaController::class, 'ajax_delete']);

Route::get('/Man/INDICADORGENERAL/Principal', [IndicadorGeneralController::class, 'principal'])->middleware('auth')->name('mantenimiento.indicadorgeneral.principal');
Route::get('/Man/INDICADORGENERAL/Listar/', [IndicadorGeneralController::class, 'ListarDT'])->name('mantenimiento.indicadorgeneral.listar');
Route::post('/Man/INDICADORGENERAL/AjaxAdd', [IndicadorGeneralController::class, 'ajax_add'])->middleware('auth')->name('mantenimiento.indicadorgeneral.guardar');
Route::get('/Man/INDICADORGENERAL/AjaxEdit/{id}', [IndicadorGeneralController::class, 'ajax_edit'])->middleware('auth')->name('mantenimiento.indicadorgeneral.editar');
Route::post('/Man/INDICADORGENERAL/AjaxUpdate', [IndicadorGeneralController::class, 'ajax_update'])->middleware('auth')->name('mantenimiento.indicadorgeneral.modificar');
Route::get('/Man/INDICADORGENERAL/AjaxDelete/{id}', [IndicadorGeneralController::class, 'ajax_delete'])->middleware('auth')->name('mantenimiento.indicadorgeneral.eliminar');
Route::get('/Man/INDICADORGENERAL/Exportar/{id}', [IndicadorGeneralController::class, 'exportarPDF'])->name('mantenimiento.indicadorgeneral.exportar.pdf');
Route::get('/Man/INDICADORGENERAL/Buscar/{id}', [IndicadorGeneralController::class, 'buscar'])->middleware('auth')->name('mantenimiento.indicadorgeneral.buscar.1');

Route::get('/Mantenimiento/Indicador', [IndicadorGeneralController::class, 'principalEducacion'])->middleware('auth')->name('mantenimiento.indicadorgeneral.principal.educacion');

Route::get('/Mantenimiento/Indicador/Listar', [IndicadorGeneralController::class, 'ListarDTMeta'])->name('mantenimiento.indicadorgeneralmeta.listar');
Route::get('/Mantenimiento/Indicador/Listar/DIT', [IndicadorGeneralController::class, 'ListarDTMeta_dit'])->name('mantenimiento.indicadorgeneralmeta.listar.dit');
Route::post('/Mantenimiento/Indicador/Add', [IndicadorGeneralController::class, 'ajax_add_meta'])->middleware('auth')->name('mantenimiento.indicadorgeneralmeta.guardar');
Route::post('/Mantenimiento/Indicador/Add/DIT', [IndicadorGeneralController::class, 'ajax_add_meta_dit'])->middleware('auth')->name('mantenimiento.indicadorgeneralmeta.guardar.dit');
Route::post('/Mantenimiento/Indicador/Find/DIT', [IndicadorGeneralController::class, 'ajax_find_meta_dit'])->middleware('auth')->name('mantenimiento.indicadorgeneralmeta.find.dit');
Route::get('/Mantenimiento/Indicador/Delete/{id}', [IndicadorGeneralController::class, 'ajax_delete_meta'])->middleware('auth')->name('mantenimiento.indicadorgeneralmeta.eliminar');

Route::get('/educación/Mantenimiento/PadronEIB', [PadronEIBController::class, 'principal'])->middleware('auth')->name('padroneib.principal');
Route::post('/PadronEIB/ajax_add_opt1/', [PadronEIBController::class, 'ajax_add_opt1'])->name('padroneib.ajax.add.opt1');
Route::get('/PadronEIB/ajax_delete_opt1/{idpadroneib}', [PadronEIBController::class, 'ajax_delete_opt1']);

//Route::post('/PadronEIB/Importados/', [PadronEIBController::class, 'ListarDTImportFuenteTodos'])->name('padroneib.listar.importados');
Route::get('/PadronEIB/Importados/', [PadronEIBController::class, 'ListarDTImportFuenteTodos'])->name('padroneib.listar.importados');
Route::get('/PadronEIB/ajax_edit/{id}', [PadronEIBController::class, 'ajax_edit']);
Route::post('/PadronEIB/ajax_add/', [PadronEIBController::class, 'ajax_add']);
Route::post('/PadronEIB/ajax_update/', [PadronEIBController::class, 'ajax_update']);
Route::get('/PadronEIB/ajax_delete/{id}', [PadronEIBController::class, 'ajax_delete']);
Route::get('/PadronEIB/IIEE/autocompletar', [InstEducativaController::class, 'completariiee2'])->name('padroneib.completar.iiee');


Route::get('/educación/PadronRER/Avance', [PadronRERController::class, 'avance'])->name('padronrer.avance');
Route::get('/PadronRER/Grafica1', [PadronRERController::class, 'grafica1'])->name('padronrer.avance.graficas1');
Route::get('/PadronRER/Grafica2', [PadronRERController::class, 'grafica2'])->name('padronrer.avance.graficas2');

Route::get('/educación/Mantenimiento/SFL', [SFLController::class, 'principal'])->middleware('auth')->name('mantenimiento.sfl.principal');
Route::get('/Man/SFL/Listar/', [SFLController::class, 'ListarDT'])->name('mantenimiento.sfl.listar');
Route::post('/Man/SFL/AjaxAdd', [SFLController::class, 'ajax_add'])->middleware('auth')->name('mantenimiento.sfl.guardar');
Route::get('/Man/SFL/AjaxEdit/{id}', [SFLController::class, 'ajax_edit'])->middleware('auth')->name('mantenimiento.sfl.editar');
Route::post('/Man/SFL/AjaxUpdate', [SFLController::class, 'ajax_update'])->middleware('auth')->name('mantenimiento.sfl.modificar');
Route::get('/Man/SFL/AjaxDelete/{id}', [SFLController::class, 'ajax_delete'])->middleware('auth')->name('mantenimiento.sfl.eliminar');
Route::get('/Man/SFL/Buscar/Modular/{modular}', [SFLController::class, 'ajax_modular'])->middleware('auth')->name('mantenimiento.sfl.buscar.modular');
Route::get('/Man/SFL/Listar/Modular', [SFLController::class, 'ListarDTModular'])->name('mantenimiento.sfl.modular.listar');
Route::get('/Man/SFL/Listar/Modular2', [SFLController::class, 'ListarDTModular2'])->name('mantenimiento.sfl.modular.listar.2');
Route::post('/Man/SFL/AjaxUpdate/Modular', [SFLController::class, 'ajax_update_modulares'])->middleware('auth')->name('mantenimiento.sfl.modular.modificar');
Route::get('/Man/SFL/Download/PDF/{id}', [SFLController::class, 'exportarPDF'])->name('mantenimiento.sfl.exportar.pdf');
Route::get('/Man/SFL/Download/EXCEL/{ugel}/{provincia}/{distrito}/{estado}', [SFLController::class, 'Download']);

/* especiales */
Route::get('/presupuesto/Principal', [MatriculaDetalleController::class, 'cargarpresupuestoxxx'])->name('educacion.xxx');
Route::get('/presupuesto/Principal/vista1', [MatriculaDetalleController::class, 'cargarpresupuestoview1'])->name('educacion.view1');
Route::get('/presupuesto/Principal/vista2', [MatriculaDetalleController::class, 'cargarpresupuestoview2'])->name('educacion.view2');
Route::get('/presupuesto/Principal/vista3', [MatriculaDetalleController::class, 'cargarpresupuestoview3'])->name('educacion.view3');

Route::get('/presupuesto/pres/vista1', [MatriculaDetalleController::class, 'cargarpresupuestoview11'])->name('presupuesto.view1'); //Unidades Ejecutoras - UNIDADES EJECUTORAS
Route::get('/presupuesto/pres/vista2', [MatriculaDetalleController::class, 'cargarpresupuestoview12'])->name('presupuesto.view2'); //Proyectos - Proyectos
Route::get('/presupuesto/pres/vista3', [MatriculaDetalleController::class, 'cargarpresupuestoview13'])->name('presupuesto.view3'); //Programas Presupuestales - PROGRAMAS PRESUPUESTALES
Route::get('/presupuesto/pres/vista4', [MatriculaDetalleController::class, 'cargarpresupuestoview14'])->name('presupuesto.view4'); //Fuente de Financiamiento - FUENTE DE FIN. Y GENERICA
Route::get('/presupuesto/pres/vista5', [MatriculaDetalleController::class, 'cargarpresupuestoview15'])->name('presupuesto.view5'); //Actividades - Actividades


//Route::get('/presupuesto/Principall', [MatriculaDetalleController::class, 'cargarpresupuestoxxx'])->name('educacionl.xxx');

Route::get('/INDICADOR/SINRUTA', function () {
    //return 'Ruta no definida';
    return view('paginavacio');
})->name('sinruta');

Route::get('fechax', function () {
    $diaSemana = date("w");
    # Calcular el tiempo (no la fecha) de cuándo fue el inicio de semana
    $tiempoDeInicioDeSemana = strtotime("-" . $diaSemana . " days"); # Restamos -X days
    # Y formateamos ese tiempo
    $fechaInicioSemana = date("Y-m-d", $tiempoDeInicioDeSemana);
    # Ahora para el fin, sumamos
    $tiempoDeFinDeSemana = strtotime("+" . $diaSemana . " days", $tiempoDeInicioDeSemana); # Sumamos +X days, pero partiendo del tiempo de inicio
    # Y formateamos
    $fechaFinSemana = date("Y-m-d", $tiempoDeFinDeSemana);

    # Listo. Hora de imprimir
    echo "Hoy es " . date("Y-m-d") . ". ";
    echo "El inicio de semana es $fechaInicioSemana y el fin es $fechaFinSemana";
});



/**************************************** FIN EDUCACION ************************************************/

/**************************************** VIVIENDA ************************************************/
Route::get('/Datass/Importar', [DatassController::class, 'importar'])->name('Datass.importar');
Route::post('/Datass/Importar', [DatassController::class, 'guardar'])->name('Datass.guardar');
Route::get('/Datass/ListaImportada/{importacion_id}', [DatassController::class, 'ListaImportada'])->name('Datass.Datass_Lista');
Route::get('/Datass/ListaImportada_DataTable/{importacion_id}', [DatassController::class, 'ListaImportada_DataTable'])->name('Datass.ListaImportada_DataTable');
Route::get('/Datass/Aprobar/{importacion_id}', [DatassController::class, 'aprobar'])->name('Datass.aprobar');
Route::post('/Datass/Aprobar/procesar/{importacion_id}', [DatassController::class, 'procesar'])->name('Datass.procesar');

Route::post('/Datass/Grafico_IndicadorRegional/{id}/{importacion_id}', [DatassController::class, 'Grafico_IndicadorRegional'])->name('Datass.Grafico_IndicadorRegional');
Route::post('/Datass/Grafico_IndicadorRegional_Periodos/{id}', [DatassController::class, 'Grafico_IndicadorRegional_Periodos'])->name('Datass.Grafico_IndicadorRegional_Periodos');
Route::post('/Datass/Grafico_IndicadorProvincial/{id}/{importacion_id}', [DatassController::class, 'Grafico_IndicadorProvincial'])->name('Datass.Grafico_IndicadorProvincial');
Route::post('/Datass/Grafico_IndicadorProvincial_masDistrital/{id}/{importacion_id}', [DatassController::class, 'Grafico_IndicadorProvincial_masDistrital'])->name('Datass.Grafico_IndicadorProvincial_masDistrital');
Route::post('/Datass/mapa_basico/{id}', [DatassController::class, 'mapa_basico'])->name('Datass.mapa_basico');


Route::get('/PEmapacopsa/Importar', [PadronEmapacopsaController::class, 'importar'])->name('pemapacopsa.importar');
Route::post('/PEmapacopsa/Importar', [PadronEmapacopsaController::class, 'importarGuardar'])->name('pemapacopsa.guardar');
Route::get('/PEmapacopsa/Listado/{importacion_id}', [PadronEmapacopsaController::class, 'importarListado'])->name('pemapacopsa.listado');
Route::get('/PEmapacopsa/listadoDT/{importacion_id}', [PadronEmapacopsaController::class, 'importarListadoDT'])->name('pemapacopsa.listadoDT');
Route::get('/PEmapacopsa/Aprobar/{importacion_id}', [PadronEmapacopsaController::class, 'importarAprobar'])->name('pemapacopsa.aprobar');
Route::post('/PEmapacopsa/Aprobar/procesar/{importacion_id}', [PadronEmapacopsaController::class, 'importarAprobarGuardar'])->name('pemapacopsa.procesar');
Route::post('/PEmapacopsa/Distritos/{provincia}', [EmapacopsaController::class, 'cargardistritos'])->name('emapacopsa.ajax.cargardistritos');

Route::get('/CentroPobladoDatass/Saneamiento', [CentroPobladoDatassController::class, 'saneamiento'])->name('centropobladodatass.saneamiento');
Route::get('/CentroPobladoDatass/Distritos/{provincia}', [CentroPobladoDatassController::class, 'cargardistrito'])->name('centropobladodatass.cargardistritos');
Route::post('/CentroPobladoDatass/Saneamiento/datos', [CentroPobladoDatassController::class, 'datosSaneamiento'])->name('centropobladodatass.saneamiento.info');
Route::get('/CentroPobladoDatass/Saneamiento/DT/{provincia}/{distrito}/{importacion_id}', [CentroPobladoDatassController::class, 'DTsaneamiento']);

Route::get('/CentroPobladoDatass/infraestructurasanitaria', [CentroPobladoDatassController::class, 'infraestructurasanitaria'])->name('centropobladodatass.infraestructurasanitaria');
Route::post('/CentroPobladoDatass/infraestructurasanitaria/datos', [CentroPobladoDatassController::class, 'datoInfraestructuraSanitaria'])->name('centropobladodatass.infraestructurasanitaria.info');

Route::get('/CentroPobladoDatass/prestadorservicio', [CentroPobladoDatassController::class, 'prestadorservicio'])->name('centropobladodatass.prestadorservicio');
Route::post('/CentroPobladoDatass/prestadorservicio/datos', [CentroPobladoDatassController::class, 'datoPrestadorServicio'])->name('centropobladodatass.prestadorservicio.info');

Route::get('/CentroPobladoDatass/calidadservicio', [CentroPobladoDatassController::class, 'calidadservicio'])->name('centropobladodatass.calidadservicio');
Route::post('/CentroPobladoDatass/calidadservicio/datos', [CentroPobladoDatassController::class, 'datoCalidadServicio'])->name('centropobladodatass.calidadservicio.info');

Route::get('/CentroPobladoDatass/listarDT', [CentroPobladoDatassController::class, 'listarDT'])->name('centropobladodatass.listarDT');


Route::post('/CentroPobladoDatass/Grafico_PorRegion_segunColumna/{columnaBD}', [CentroPobladoDatassController::class, 'Grafico_PorRegion_segunColumna'])->name('centropobladodatass.Grafico_PorRegion_segunColumna');
Route::post('/CentroPobladoDatass/Grafico_PorRegion_CP_Periodos', [CentroPobladoDatassController::class, 'Grafico_PorRegion_CP_Periodos'])->name('centropobladodatass.Grafico_PorRegion_CP_Periodos');
Route::post('/CentroPobladoDatass/Grafico_tipo_organizacion_comunal/{id}', [CentroPobladoDatassController::class, 'Grafico_tipo_organizacion_comunal'])->name('centropobladodatass.Grafico_tipo_organizacion_comunal');
Route::post('/CentroPobladoDatass/Grafico_Asociados_organizacion_comunal/{id}', [CentroPobladoDatassController::class, 'Grafico_Asociados_organizacion_comunal'])->name('centropobladodatass.Grafico_Asociados_organizacion_comunal');
/**************************************** FIN VIVIENDA ************************************************/

/**************************************** ADMINISTRADOR ************************************************/
Route::get('/administrador/Sistema/Principal', [SistemaController::class, 'principal'])->middleware('auth')->name('sistema.principal');
Route::get('/Sistema/listarDT', [SistemaController::class, 'listarDT'])->name('sistema.listarDT');
Route::get('/Sistema/ajax_edit/{sistema_id}', [SistemaController::class, 'ajax_edit']);
Route::post('/Sistema/ajax_add', [SistemaController::class, 'ajax_add']);
Route::post('/Sistema/ajax_update', [SistemaController::class, 'ajax_update']);
Route::get('/Sistema/ajax_delete/{sistema_id}', [SistemaController::class, 'ajax_delete']);
Route::get('/Sistema/ajax_estado/{sistema_id}', [SistemaController::class, 'ajax_estado']);

Route::get('/administrador/Menu/Principal', [MenuController::class, 'principal'])->middleware('auth')->name('menu.principal');
Route::get('/Menu/listarDT/{sistema_id}', [MenuController::class, 'listarDT'])->name('menu.listarDT');
Route::get('/Menu/cargarGrupo/{sistema_id}', [MenuController::class, 'cargarGrupo']);
Route::get('/Menu/ajax_edit/{menu_id}', [MenuController::class, 'ajax_edit']);
Route::post('/Menu/ajax_add', [MenuController::class, 'ajax_add']);
Route::post('/Menu/ajax_update', [MenuController::class, 'ajax_update']);
Route::get('/Menu/ajax_delete/{menu_id}', [MenuController::class, 'ajax_delete']);
Route::get('/Menu/ajax_estado/{menu_id}', [MenuController::class, 'ajax_estado']);

Route::get('/administrador/Menu/Principal/Link', [MenuController::class, 'principallink'])->middleware('auth')->name('menu.principal.link');
Route::get('/Menu/listarDTLink/{sistema_id}', [MenuController::class, 'listarDTLink'])->name('menu.listar.link');
Route::post('/Menu/ajax_add_link', [MenuController::class, 'ajax_add_link']);
Route::post('/Menu/ajax_update_link', [MenuController::class, 'ajax_update_link']);


Route::get('/administrador/Usuario/Principal', [UsuarioController::class, 'principal'])->middleware('auth')->name('Usuario.principal');
Route::get('/Usuario/Usuario_DataTable/', [UsuarioController::class, 'Lista_DataTable'])->name('Usuario.Lista_DataTable');
Route::get('/Usuario/Eliminar/{id}', [UsuarioController::class, 'eliminar'])->name('Usuario.Eliminar');

Route::get('/Usuario/ajax_edit/{usuario_id}', [UsuarioController::class, 'ajax_edit']);
Route::get('/Usuario/ajax_edit_basico/{usuario_id}', [UsuarioController::class, 'ajax_edit_basico']);
Route::post('/Usuario/ajax_add/', [UsuarioController::class, 'ajax_add']);
Route::post('/Usuario/ajax_update/', [UsuarioController::class, 'ajax_update']);
Route::post('/Usuario/ajax_updateaux/', [UsuarioController::class, 'ajax_updateaux'])->name('usuario.updatedperfil');
Route::post('/Usuario/ajax_update_password/', [UsuarioController::class, 'ajax_updatepassword'])->name('usuario.updatedpassword');
Route::get('/Usuario/ajax_estadousuario/{usuario_id}', [UsuarioController::class, 'ajax_estadoUsuario']);
Route::get('/Usuario/ajax_delete/{usuario_id}', [UsuarioController::class, 'ajax_delete']);
Route::get('/Usuario/DTSistemasAsignados/{usuario_id}', [UsuarioController::class, 'listarSistemasAsignados']);

Route::get('/Usuario/CargarPerfil/{sistema_id}/{usuario_id}', [UsuarioController::class, 'cargarPerfil']);
Route::post('/Usuario/ajax_add_perfil/', [UsuarioController::class, 'ajax_add_perfil']);
Route::get('/Usuario/ajax_delete_perfil/{usuario_id}/{perfil_id}', [UsuarioController::class, 'ajax_delete_perfil']);

Route::get('/Icono/listarDT', [IconoController::class, 'listarDT'])->name('icono.listar');
Route::get('/Icono/corregir', function () {
    // \u00a0
    echo strlen('mdi mdi-qrcode-minus') . '<br>';
    $iconos = Icono::all();
    foreach ($iconos as $key => $value) {
        echo $value->icon . ' - ';
        echo strlen($value->icon) . ' - ';
        $cad = substr($value->icon, -strlen($value->icon) + 2);
        echo $cad . ' - ' . strlen($cad);
        echo '<br>';
    }
});

Route::get('/administrador/Perfil/Principal', [PerfilController::class, 'principal'])->middleware('auth')->name('perfil.principal');
Route::get('/Perfil/listarDT/{sistema_id}', [PerfilController::class, 'listarDT'])->name('perfil.listarDT');
Route::get('/Perfil/ajax_edit/{perfil_id}', [PerfilController::class, 'ajax_edit']);
Route::post('/Perfil/ajax_add', [PerfilController::class, 'ajax_add']);
Route::post('/Perfil/ajax_update', [PerfilController::class, 'ajax_update']);
Route::get('/Perfil/ajax_delete/{perfil_id}', [PerfilController::class, 'ajax_delete']);
Route::get('/Perfil/ajax_estado/{perfil_id}', [PerfilController::class, 'ajax_estado']);

Route::get('/Perfil/listarmenu/{perfil_id}/{sistema_id}', [PerfilController::class, 'listarmenu']);
Route::post('/Perfil/ajax_add_menu', [PerfilController::class, 'ajax_add_menu']);

Route::get('/Perfil/listarsistema/{perfil_id}/{sistema_id}', [PerfilController::class, 'listarsistema']);
Route::post('/Perfil/ajax_add_sistema', [PerfilController::class, 'ajax_add_sistema']);

Route::get('/administrador/Entidad/Principal', [EntidadController::class, 'principal'])->middleware('auth')->name('entidad.principal');
Route::get('/Entidad/listar', [EntidadController::class, 'ListarJSON'])->name('entidad.listar.json');
Route::post('/Entidad/ajax_add_entidad/', [EntidadController::class, 'ajax_add_entidad'])->name('entidad.ajax.addentidad');
Route::get('/Entidad/ajax_edit_entidad/{entidad}', [EntidadController::class, 'ajax_edit_entidad'])->name('entidad.ajax.edit');
Route::post('/Entidad/ajax_update_entidad/', [EntidadController::class, 'ajax_update_entidad'])->name('entidad.ajax.updateentidad');
Route::get('/Entidad/ajax_delete/{entidad_id}', [EntidadController::class, 'ajax_delete_entidad'])->name('entidad.ajax.delete');
//Route::get('/Entidad/listar/{unidadejecutora_id}/{dependencia}', [EntidadController::class, 'listarDT']);

Route::get('/Entidad/CargarEntidad/', [EntidadController::class, 'cargarEntidad'])->name('entidad.ajax.cargar');
Route::get('/Entidad/CargarEntidad/AUTOCOMPLETE', [EntidadController::class, 'autocompletarEntidad'])->name('entidad.autocomplete');
//Route::get('/PadronEIB/IIEE/autocompletar', [InstEducativaController::class, 'completariiee2'])->name('padroneib.completar.iiee');

Route::get('/administrador/Entidad/Gerencia', [EntidadController::class, 'gerencia'])->name('entidad.gerencia');
Route::get('/Entidad/ajax_edit_gerencia/{gerencia_id}', [EntidadController::class, 'ajax_edit_gerencia'])->name('entidad.ajax.edit.gerencia');

Route::get('/Importado/resumen', [ImportacionController::class, 'resumenimportados'])->name('importar.importados');
/**************************************** FIN ADMINISTRADOR ************************************************/

/**************************************** PRESUPUESTO ************************************************/
Route::get('/Home/Presupuesto/cuadros', [HomeController::class, 'presupuestocuadros'])->name('home.presupuesto.cuadros');

Route::get('/Home/Presupuesto/gra1/{importacion_id}', [HomeController::class, 'presupuestografica1'])->name('graficas.home.presupuesto.1');
Route::get('/Home/Presupuesto/gra2', [HomeController::class, 'presupuestografica2'])->name('graficas.home.presupuesto.2');
Route::get('/Home/Presupuesto/gra3', [HomeController::class, 'presupuestografica3'])->name('graficas.home.presupuesto.3');
Route::get('/Home/Presupuesto/gra4', [HomeController::class, 'presupuestografica4'])->name('graficas.home.presupuesto.4');
Route::get('/Home/Presupuesto/gra5', [HomeController::class, 'presupuestografica5'])->name('graficas.home.presupuesto.5');
Route::get('/Home/Presupuesto/gra6', [HomeController::class, 'presupuestografica6'])->name('graficas.home.presupuesto.6');
Route::get('/Home/Presupuesto/gra7', [HomeController::class, 'presupuestografica7'])->name('graficas.home.presupuesto.7');

Route::get('/Home/Presupuesto/tabla1/{importacion_id}', [HomeController::class, 'presupuestotabla1'])->name('tabla.home.presupuesto.1');
Route::get('/Home/Presupuesto/tabla2/{importacion_id}', [HomeController::class, 'presupuestotabla2'])->name('tabla.home.presupuesto.2');
Route::get('/Home/Presupuesto/tabla3/{importacion_id}', [HomeController::class, 'presupuestotabla3'])->name('tabla.home.presupuesto.3');

Route::get('/Home/Presupuesto/tabla', [HomeController::class, 'presupuestotabla'])->name('tabla.home.presupuesto');

Route::get('/IMPORGASTOS/Gastos/Importar', [ImporGastosController::class, 'importar'])->name('pres.gastos.importar');
Route::post('/IMPORGASTOS/Gastos/Importar', [ImporGastosController::class, 'importarGuardar'])->name('imporgastos.gastos.guardar');
Route::get('/IMPORGASTOS/Listar/ImportarDT', [ImporGastosController::class, 'ListarDTImportFuenteTodos'])->name('imporgastos.listar.importados');
Route::get('/IMPORGASTOS/eliminar/{id}', [ImporGastosController::class, 'eliminar']);
Route::post('/IMPORGASTOS/ListaImportada/{importacion_id}', [ImporGastosController::class, 'ListaImportada'])->name('imporgastos.listarimportados');

Route::get('/IMPORGASTOS/Gastos/Importar2', [ImporGastosController::class, 'importar2'])->name('pres.gastos.importar2');
Route::post('/IMPORGASTOS/Gastos/Importar2', [ImporGastosController::class, 'importarGuardar2'])->name('imporgastos.gastos.guardar2');

Route::get('/IMPORINGRESO/ingresos/Importar', [ImporIngresosController::class, 'importar'])->name('pres.ingresos.importar');
Route::post('/IMPORINGRESO/ingresos/Importar', [ImporIngresosController::class, 'guardar'])->name('imporingresos.guardar');
Route::get('/IMPORINGRESO/Listar/ImportarDT', [ImporIngresosController::class, 'ListarDTImportFuenteTodos'])->name('imporingresos.listar.importados');
Route::get('/IMPORINGRESO/eliminar/{id}', [ImporIngresosController::class, 'eliminar']);
Route::post('/IMPORINGRESO/ListaImportada/{importacion_id}', [ImporIngresosController::class, 'ListaImportada'])->name('imporingresos.listarimportados');

Route::get('/IMPORSIAFWEB/SiafWeb/Importar', [ImporSiafWebController::class, 'importar'])->name('imporsiafweb.importar');
Route::post('/IMPORSIAFWEB/SiafWeb/Importar', [ImporSiafWebController::class, 'importarGuardar'])->name('imporsiafweb.guardar');
Route::get('/IMPORSIAFWEB/Listar/ImportarDT', [ImporSiafWebController::class, 'ListarDTImportFuenteTodos'])->name('imporsiafweb.listar.importados');
Route::get('/IMPORSIAFWEB/eliminar/{id}', [ImporSiafWebController::class, 'eliminar']);
Route::post('/IMPORSIAFWEB/ListaImportada/{importacion_id}', [ImporSiafWebController::class, 'ListaImportada'])->name('imporsiafweb.listarimportados');

Route::get('/IMPORPROYECTOS/Proyectos/Importar', [ImporProyectosController::class, 'importar'])->name('imporproyectos.importar');
Route::post('/IMPORPROYECTOS/Proyectos/Importar', [ImporProyectosController::class, 'importarGuardar'])->name('imporproyectos.guardar');
Route::get('/IMPORPROYECTOS/Listar/ImportarDT', [ImporProyectosController::class, 'ListarDTImportFuenteTodos'])->name('imporproyectos.listar.importados');
Route::get('/IMPORPROYECTOS/eliminar/{id}', [ImporProyectosController::class, 'eliminar']);
Route::post('/IMPORPROYECTOS/ListaImportada/{importacion_id}', [ImporProyectosController::class, 'ListaImportada'])->name('imporproyectos.listarimportados');

Route::get('/IMPORACTSPROYS/ActsProys/Importar', [ImporActividadesProyectosController::class, 'importar'])->name('imporactividadesproyectos.importar');
Route::post('/IMPORACTSPROYS/ActsProys/Importar', [ImporActividadesProyectosController::class, 'importarGuardar'])->name('imporactividadesproyectos.guardar');
Route::get('/IMPORACTSPROYS/Listar/ImportarDT', [ImporActividadesProyectosController::class, 'ListarDTImportFuenteTodos'])->name('imporactividadesproyectos.listar.importados');
Route::get('/IMPORACTSPROYS/eliminar/{id}', [ImporActividadesProyectosController::class, 'eliminar']);
Route::post('/IMPORACTSPROYS/ListaImportada/{importacion_id}', [ImporActividadesProyectosController::class, 'ListaImportada'])->name('imporactividadesproyectos.listarimportados');

Route::get('/PRES/Covid/Importar', [ImporGastosController::class, 'importar'])->name('pres.covid.importar');    //no sirve
Route::get('/PRES/Regiones/Importar', [ImporGastosController::class, 'importar'])->name('pres.regiones.importar'); //no sirve

Route::get('/SiafGastos/NivelGobiernos', [BaseGastosController::class, 'nivelgobiernos'])->middleware('auth')->name('basegastos.nivelgobiernos');
Route::get('/SiafGastos/ajax_sector', [BaseGastosController::class, 'cargarsector'])->name('basegastos.cargarsector');
Route::get('/SiafGastos/ajax_unidadejecutora', [BaseGastosController::class, 'cargarue'])->name('basegastos.cargarue');
Route::get('/SiafGastos/ajax_subgenerica', [BaseGastosController::class, 'cargarsubgenerica'])->name('basegastos.cargarsubgenerica');
Route::get('/SiafGastos/grafico01', [BaseGastosController::class, 'nivelgobiernosgrafica01'])->name('basegastos.nivelgobiernos.grafica01');
Route::get('/SiafGastos/grafico02', [BaseGastosController::class, 'nivelgobiernosgrafica02'])->name('basegastos.nivelgobiernos.grafica02');
Route::get('/SiafGastos/tabla01', [BaseGastosController::class, 'nivelgobiernostabla01'])->name('basegastos.nivelgobiernos.tabla01');
Route::get('/SiafGastos/tabla02', [BaseGastosController::class, 'nivelgobiernostabla02'])->name('basegastos.nivelgobiernos.tabla02');


Route::get('/GastosP/NivelesGobiernos', [BaseGastosController::class, 'nivelesgobiernos'])->name('basegastos.nivelesgobiernos');
Route::get('/GastosP/Cards', [BaseGastosController::class, 'nivelesgobiernoscards'])->name('basegastos.nivelesgobiernos.cards');
Route::get('/GastosP/Exportar/Excel/{div}/{basegastos}', [BaseGastosController::class, 'download'])->name('basegastos.nivelesgobiernos.download.excel');


Route::get('/SiafIngresos/IngresoPresupuestal', [BaseIngresosController::class, 'ingresopresupuestal'])->name('baseingresos.ingresopresupuestal');
Route::get('/SiafIngresos/Ingreso/grafico01', [BaseIngresosController::class, 'ingresopresupuestalgrafica1'])->name('baseingresos.ingresopresupuestal.grafica01');
Route::get('/SiafIngresos/Ingreso/grafico02', [BaseIngresosController::class, 'ingresopresupuestalgrafica2'])->name('baseingresos.ingresopresupuestal.grafica02');
Route::get('/SiafIngresos/Ingreso/grafico03', [BaseIngresosController::class, 'ingresopresupuestalgrafica3'])->name('baseingresos.ingresopresupuestal.grafica03');
Route::get('/SiafIngresos/Ingreso/grafico04', [BaseIngresosController::class, 'ingresopresupuestalgrafica4'])->name('baseingresos.ingresopresupuestal.grafica04');

Route::get('/SiafIngresos/SectoresGobiernos', [BaseIngresosController::class, 'sectoresgobiernos'])->name('baseingresos.sectoresgobiernos');
Route::get('/SiafIngresos/SectoresGobiernos/tb1', [BaseIngresosController::class, 'sectoresgobiernostabla01'])->name('baseingresos.secgob.rpt1.tabla01');
Route::get('/SiafIngresos/SectoresGobiernos/Exportar/excel/{ano}/{gobierno}/{financiamiento}', [BaseIngresosController::class, 'sectoresgobiernosdownload']);

Route::get('/SiafIngresos/Resumen', [BaseIngresosController::class, 'resumenporanios'])->name('baseingresos.resumen.anios');

Route::get('/BaseProyectos/AvancePresupuestal', [BaseProyectosController::class, 'avancepresupuestal'])->name('baseproyectos.avancepresupuestal');
Route::get('/BaseProyectos/mapa1/{importacion_id}', [BaseProyectosController::class, 'avancepresupuestalmapa1'])->name('baseproyectos.mapa.1');
Route::get('/BaseProyectos/gra3', [BaseProyectosController::class, 'avancepresupuestalgrafica3'])->name('baseproyectos.grafica.1');
Route::get('/BaseProyectos/gra4', [BaseProyectosController::class, 'avancepresupuestalgrafica4'])->name('baseproyectos.grafica.2');
Route::get('/BaseProyectos/gra5', [BaseProyectosController::class, 'avancepresupuestalgrafica5'])->name('baseproyectos.grafica.3');
Route::get('/BaseProyectos/gra6', [BaseProyectosController::class, 'avancepresupuestalgrafica6'])->name('baseproyectos.grafica.4');
Route::get('/BaseProyectos/gra7', [BaseProyectosController::class, 'avancepresupuestalgrafica7'])->name('baseproyectos.grafica.5');

Route::get('/IMPORMODS/Modificaciones/Importar', [ImporModificacionesController::class, 'importar'])->name('impormodificaciones.importar');
Route::post('/IMPORMODS/Modificaciones/Importar', [ImporModificacionesController::class, 'importarGuardar'])->name('impormodificaciones.guardar');
Route::get('/IMPORMODS/Listar/ImportarDT', [ImporModificacionesController::class, 'ListarDTImportFuenteTodos'])->name('impormodificaciones.listar.importados');
Route::get('/IMPORMODS/eliminar/{id}', [ImporModificacionesController::class, 'eliminar']);
Route::post('/IMPORMODS/ListaImportada/{importacion_id}', [ImporModificacionesController::class, 'ListaImportada'])->name('impormodificaciones.listarimportados');

Route::get('/GobsRegs/Principal', [GobiernosRegionalesController::class, 'principal'])->middleware('auth')->name('gobsregs.principal');
Route::get('/GobsRegs/cargarmes', [GobiernosRegionalesController::class, 'cargarmes'])->name('gobsregs.cargarmes');
Route::get('/GobsRegs/tabla01', [GobiernosRegionalesController::class, 'principaltabla01'])->name('gobsregs.tabla01');
Route::get('/GobsRegs/Exportar/excel/principal01', [GobiernosRegionalesController::class, 'download'])->name('gobsregs.download.excel.principal01');
Route::get('/GobsRegs/Exportar/excel/principal01/{ano}/{mes}/{tipo}', [GobiernosRegionalesController::class, 'download']);

Route::get('/Modificaciones/Principal', [ModificacionesController::class, 'principal_gasto'])->middleware('auth')->name('modificaciones.principal.gastos');
Route::get('/Modificaciones/cargarmes', [ModificacionesController::class, 'cargarmes'])->name('modificaciones.cargarmes');
Route::get('/Modificaciones/cargarproductoproyecto', [ModificacionesController::class, 'cargarproductoproyecto'])->name('modificaciones.cargarproductoproyecto');
Route::get('/Modificaciones/cargarunidadejecutora', [ModificacionesController::class, 'cargarunidadejecutora'])->name('modificaciones.cargarunidadejecutora');
Route::get('/Modificaciones/cargartipos', [ModificacionesController::class, 'cargartipos'])->name('modificaciones.cargartipos');
Route::get('/Modificaciones/cargardispositivolegal', [ModificacionesController::class, 'cargardispositivolegal'])->name('modificaciones.cargardispositivolegal');
Route::get('/Modificaciones/tabla01', [ModificacionesController::class, 'principalgastotabla01'])->name('modificaciones.tabla01');
Route::get('/Modificaciones/DT/tabla01', [ModificacionesController::class, 'principalgastotabla01_DT'])->name('modificaciones.dt.tabla01');
Route::get('/Modificaciones/DT/tabla01/foot', [ModificacionesController::class, 'principalgastotabla01_foot'])->name('modificaciones.dt.tabla01.foot');
Route::get('/Modificaciones/ExportarG/excel/tabla01/{ano}/{mes}/{articulo}/{tipo}/{dispositivo}/{ue}', [ModificacionesController::class, 'downloadgasto']);

Route::get('/Modificaciones/Principal/Ingresos', [ModificacionesController::class, 'principal_ingreso'])->middleware('auth')->name('modificaciones.principal.ingresos');
Route::get('/Modificaciones/ingreso/tabla01', [ModificacionesController::class, 'principalingresotabla01'])->name('modificaciones.ingreso.tabla01');
Route::get('/Modificaciones/ExportarI/excel/tabla01/{ano}/{mes}/{tipo}/{ue}', [ModificacionesController::class, 'downloadingreso']);

Route::get('/SiafGastos/reportes1', [BaseSiafWebController::class, 'reporte1'])->name('basesiafweb.reporte1');
Route::get('/SiafGastos/reportes1/tb1', [BaseSiafWebController::class, 'reporte1tabla01'])->name('basesiafweb.rpt1.tabla01');
Route::get('/SiafGastos/reportes1/Exportar/excel/{ano}/{articulo}/{categoria}', [BaseSiafWebController::class, 'reporte1download']);
Route::get('/SiafGastos/reportes1/gra1', [BaseSiafWebController::class, 'reporte1grafica1'])->name('basesiafweb.rpt1.gra.1');

Route::get('/SiafGastos/reportes2', [BaseSiafWebController::class, 'reporte2'])->name('basesiafweb.reporte2');
Route::get('/SiafGastos/reportes2/tb1', [BaseSiafWebController::class, 'reporte2tabla01'])->name('basesiafweb.rpt2.tabla01');
Route::get('/SiafGastos/reportes2/Exportar/excel/{ano}/{articulo}/{ue}/{tc}', [BaseSiafWebController::class, 'reporte2download']);
Route::get('/SiafGastos/reportes2/gra1', [BaseSiafWebController::class, 'reporte2grafica1'])->name('basesiafweb.rpt2.gra.1');

Route::get('/SiafGastos/reportes3', [BaseSiafWebController::class, 'reporte3'])->name('basesiafweb.reporte3');
Route::get('/SiafGastos/reportes3/tb1', [BaseSiafWebController::class, 'reporte3tabla01'])->name('basesiafweb.rpt3.tabla01');
Route::get('/SiafGastos/reportes3/Exportar/excel/{ano}/{articulo}/{ue}/{ff}', [BaseSiafWebController::class, 'reporte3download']);
Route::get('/SiafGastos/reportes3/gra1', [BaseSiafWebController::class, 'reporte3grafica1'])->name('basesiafweb.rpt3.gra.1');

Route::get('/SiafGastos/reportes4', [BaseSiafWebController::class, 'reporte4'])->name('basesiafweb.reporte4');
Route::get('/SiafGastos/reportes4/tb1', [BaseSiafWebController::class, 'reporte4tabla01'])->name('basesiafweb.rpt4.tabla01');
Route::get('/SiafGastos/reportes4/Exportar/excel/{ano}/{articulo}/{ue}', [BaseSiafWebController::class, 'reporte4download']);
Route::get('/SiafGastos/reportes4/gra1', [BaseSiafWebController::class, 'reporte4grafica1'])->name('basesiafweb.rpt4.gra.1');

Route::get('/SiafGastos/reportes5', [BaseSiafWebController::class, 'reporte5'])->name('basesiafweb.reporte5');
Route::get('/SiafGastos/reportes5/tb1', [BaseSiafWebController::class, 'reporte5tabla01'])->name('basesiafweb.rpt5.tabla01');
Route::get('/SiafGastos/reportes5/Exportar/excel/{ano}/{articulo}/{ue}', [BaseSiafWebController::class, 'reporte5download']);
Route::get('/SiafGastos/reportes5/gra1', [BaseSiafWebController::class, 'reporte5grafica1'])->name('basesiafweb.rpt5.gra.1');
Route::get('/SiafGastos/reportes5/gra2', [BaseSiafWebController::class, 'reporte5grafica2'])->name('basesiafweb.rpt5.gra.2');
Route::get('/SiafGastos/reportes5/gra3', [BaseSiafWebController::class, 'reporte5grafica3'])->name('basesiafweb.rpt5.gra.3');

Route::get('/SiafGastos/reportes6', [BaseSiafWebController::class, 'reporte6'])->name('basesiafweb.reporte6');
Route::get('/SiafGastos/reportes6/tb1', [BaseSiafWebController::class, 'reporte6tabla01'])->name('basesiafweb.rpt6.tabla01');
Route::get('/SiafGastos/reportes6/Exportar/excel/{ano}/{articulo}/{ue}', [BaseSiafWebController::class, 'reporte6download']);
Route::get('/SiafGastos/reportes6/gra1', [BaseSiafWebController::class, 'reporte6grafica1'])->name('basesiafweb.rpt6.gra.1');
Route::get('/SiafGastos/reportes6/gra2', [BaseSiafWebController::class, 'reporte6grafica2'])->name('basesiafweb.rpt6.gra.2');

Route::get('/SiafGastos/reportes7', [BaseSiafWebController::class, 'reporte7'])->name('basesiafweb.reporte7');
Route::get('/SiafGastos/reportes7/tb1', [BaseSiafWebController::class, 'reporte7tabla01'])->name('basesiafweb.rpt7.tabla01');
Route::get('/SiafGastos/reportes7/Exportar/excel/{ano}/{articulo}/{categoria}/{ff}/{gg}/{partidas}', [BaseSiafWebController::class, 'reporte7download']);
Route::get('/SiafGastos/reportes7/gra1', [BaseSiafWebController::class, 'reporte7grafica1'])->name('basesiafweb.rpt7.gra.1');

Route::get('/SiafGastos/Utilitarios1', [BaseSiafWebController::class, 'cargar_productoproyecto'])->name('basesiafweb.cargar.productoproyecto');
Route::get('/SiafGastos/Utilitarios2', [BaseSiafWebController::class, 'cargar_unidadejecutora'])->name('basesiafweb.cargar.unidadejecutora');

Route::get('/UnidadEjecutora/Principal', [UnidadEjecutoraController::class, 'principal'])->middleware('auth')->name('unidadejecutora.principal');
Route::get('/UnidadEjecutora/listar', [UnidadEjecutoraController::class, 'listar'])->name('unidadejecutora.listar');
Route::post('/UnidadEjecutora/ajax_add', [UnidadEjecutoraController::class, 'ajax_add']);
Route::post('/UnidadEjecutora/ajax_update', [UnidadEjecutoraController::class, 'ajax_update']);
Route::get('/UnidadEjecutora/ajax_edit/{ue_id}', [UnidadEjecutoraController::class, 'ajax_edit']);
Route::get('/UnidadEjecutora/ajax_delete/{ue_id}', [UnidadEjecutoraController::class, 'ajax_delete']);

Route::get('/UnidadOrganica/Principal', [UnidadOrganicaController::class, 'principal'])->middleware('auth')->name('unidadorganica.principal');
Route::get('/UnidadOrganica/listar', [UnidadOrganicaController::class, 'listar'])->name('unidadorganica.listar');
Route::post('/UnidadOrganica/ajax_add', [UnidadOrganicaController::class, 'ajax_add']);
Route::post('/UnidadOrganica/ajax_update', [UnidadOrganicaController::class, 'ajax_update']);
Route::get('/UnidadOrganica/ajax_edit/{uo_id}', [UnidadOrganicaController::class, 'ajax_edit']);
Route::get('/UnidadOrganica/ajax_delete/{uo_id}', [UnidadOrganicaController::class, 'ajax_delete']);
Route::get('/UnidadOrganica/metas/listar', [UnidadOrganicaController::class, 'listarmetas'])->name('unidadorganica.metas.listar');
Route::get('/UnidadOrganica/metas/asignar', [UnidadOrganicaController::class, 'asignarmeta'])->name('unidadorganica.metas.asignar');
Route::get('/UnidadOrganica/metas/quitar', [UnidadOrganicaController::class, 'quitarmeta'])->name('unidadorganica.metas.quitar');
Route::get('/unidadorganica/cargaruo', [UnidadOrganicaController::class, 'cargaruo'])->name('unidadorganica.cargaruo');
Route::get('/unidadorganica/EjecucionGasto', [UnidadOrganicaController::class, 'ejecuciongasto'])->name('unidadorganica.ejecuciongasto');
Route::get('/unidadorganica/EjecucionGasto/tb1', [UnidadOrganicaController::class, 'ejecuciongastotabla01'])->name('unidadorganica.eg.tabla01');
Route::get('/unidadorganica/EjecucionGasto/Exportar/excel/{anio}/{articulo}/{ue}/{cg}', [UnidadOrganicaController::class, 'ejecuciongastodownload']); //falta
Route::get('/unidadorganica/EjecucionGasto/gra1', [UnidadOrganicaController::class, 'ejecuciongastografica1'])->name('unidadorganica.eg.gra.1'); //falta


Route::get('/Meta/Principal', [MetaController::class, 'principal'])->middleware('auth')->name('meta.principal');
Route::get('/Meta/listar', [MetaController::class, 'listar'])->name('meta.listar');
Route::post('/Meta/ajax_add', [MetaController::class, 'ajax_add']);
Route::post('/Meta/ajax_update', [MetaController::class, 'ajax_update']);
Route::get('/Meta/ajax_edit/{meta_id}', [MetaController::class, 'ajax_edit']);
Route::get('/Meta/ajax_delete/{uemeta_id}', [MetaController::class, 'ajax_delete']);

Route::get('/pliego/cargarpliego', [PliegoController::class, 'cargarpliego'])->name('pliego.cargarpliego');

Route::get('/unidadejecutora/cargarue', [UnidadEjecutoraController::class, 'cargarue'])->name('unidadejecutora.cargarue');



Route::get('/EspecificaDetalle/Restringidas', [EspecificaDetalleController::class, 'partidasrestringidas'])->name('espdet.restringidas');
Route::get('/EspecificaDetalle/Restringidas/PartidasRestringidas/listar', [EspecificaDetalleController::class, 'listarpartidasrestringidas'])->name('espdet.partidasrestringidas.listar');
Route::get('/EspecificaDetalle/Restringidas/listar', [EspecificaDetalleController::class, 'listar'])->name('espdet.listar');
//Route::get('/EspecificaDetalle/Restringidas/asignar', [EspecificaDetalleController::class, 'asignarpartidasrestringidas'])->name('espdet.partidasrestringidas.asignar');
//Route::get('/EspecificaDetalle/Restringidas/quitar', [EspecificaDetalleController::class, 'quitarpartidasrestringidas'])->name('espdet.partidasrestringidas.quitar');
Route::get('/EspecificaDetalle/Restringidas/Guardar', [EspecificaDetalleController::class, 'guardarpartidasrestringidas'])->name('espdet.partidasrestringidas.guardar');
Route::get('/EspecificaDetalle/Restringidas/Borrar', [EspecificaDetalleController::class, 'borrarpartidasrestringidas'])->name('espdet.partidasrestringidas.borrar');


Route::get('/SubGenerica/cargarsubgenerica', [SubGenericaController::class, 'cargarsg'])->name('subgenerica.cargarsg');
Route::get('/Especifica/cargarespecifica', [EspecificaController::class, 'cargar'])->name('especifica.cargar');
Route::get('/SubGenericaDetalle/cargarsubgenericadetalle', [SubGenericaDetalleController::class, 'cargar'])->name('subgenericadetalle.cargar');


/**************************************** FIN PRESUPUESTO ***************************************************/


/**************************************** TRABAJO DESDE GAMB ************************************************/
Route::get('/ProEmpleo/Principal', [ProEmpleoController::class, 'Principal'])->middleware('auth')->name('ProEmpleo.Principal');


Route::get('/ProEmpleo/Importar', [ProEmpleoController::class, 'importar'])->name('ProEmpleo.importar');
Route::post('/ProEmpleo/Importar', [ProEmpleoController::class, 'guardar'])->name('ProEmpleo.guardar');

Route::get('/IndicadorTrabajo/Importar', [IndicadorTrabajoController::class, 'importar'])->name('trabajo.indicador');
Route::post('/IndicadorTrabajo/Grafico_PEA/{id}', [IndicadorTrabajoController::class, 'Grafico_PEA'])->name('trabajo.Grafico_PEA');
Route::post('/IndicadorTrabajo/Grafico_PEA_IPM/{id}', [IndicadorTrabajoController::class, 'Grafico_PEA_IPM'])->name('trabajo.Grafico_PEA_IPM');

Route::get('/ProEmpleo/Aprobar/{importacion_id}', [ProEmpleoController::class, 'aprobar'])->name('ProEmpleo.aprobar');
Route::post('/ProEmpleo/Aprobar/procesar/{importacion_id}', [ProEmpleoController::class, 'procesar'])->name('ProEmpleo.procesar');
Route::get('/ProEmpleo/ListaImportada_DataTable/{importacion_id}', [ProEmpleoController::class, 'ListaImportada_DataTable'])->name('ProEmpleo.ListaImportada_DataTable');

Route::post('/ProEmpleo/Grafico_oferta_demanda_colocados/{id}', [ProEmpleoController::class, 'Grafico_oferta_demanda_colocados'])->name('ProEmpleo.Grafico_oferta_demanda_colocados');
Route::post('/ProEmpleo/Grafico_Colocados_Hombres_Vs_Mujeres/{id}', [ProEmpleoController::class, 'Grafico_Colocados_Hombres_Vs_Mujeres'])->name('ProEmpleo.Grafico_Colocados_Hombres_Vs_Mujeres');
Route::post('/ProEmpleo/Grafico_Colocados_per_Con_Discapacidad/{id}', [ProEmpleoController::class, 'Grafico_Colocados_per_Con_Discapacidad'])->name('ProEmpleo.Grafico_Colocados_per_Con_Discapacidad');
Route::post('/ProEmpleo/VariablesMercado/{id}', [ProEmpleoController::class, 'VariablesMercado'])->name('ProEmpleo.VariablesMercado');

Route::get('/AnuarioEstadistico/Importar', [AnuarioEstadisticoController::class, 'importar'])->name('AnuarioEstadistico.importar');
Route::post('/AnuarioEstadistico/Importar', [AnuarioEstadisticoController::class, 'guardar'])->name('AnuarioEstadistico.guardar');


Route::get('/AnuarioEstadistico/Aprobar/{importacion_id}', [AnuarioEstadisticoController::class, 'aprobar'])->name('AnuarioEstadistico.aprobar');
Route::post('/AnuarioEstadistico/Aprobar/procesar/{importacion_id}', [AnuarioEstadisticoController::class, 'procesar'])->name('AnuarioEstadistico.procesar');
Route::get('/AnuarioEstadistico/ListaImportada_DataTable/{importacion_id}', [AnuarioEstadisticoController::class, 'ListaImportada_DataTable'])->name('AnuarioEstadistico.ListaImportada_DataTable');

Route::post('/AnuarioEstadistico/Grafico_Promedio_Remuneracion/{id}', [AnuarioEstadisticoController::class, 'Grafico_Promedio_Remuneracion']);
Route::post('/AnuarioEstadistico/Grafico_Promedio_Trabajadores/{id}', [AnuarioEstadisticoController::class, 'Grafico_Promedio_Trabajadores']);
Route::post('/AnuarioEstadistico/Grafico_Prestadores_Servicio4ta_Publico/{id}', [AnuarioEstadisticoController::class, 'Grafico_Prestadores_Servicio4ta_Publico']);
Route::post('/AnuarioEstadistico/Grafico_Prestadores_Servicio4ta_Privado/{id}', [AnuarioEstadisticoController::class, 'Grafico_Prestadores_Servicio4ta_Privado']);

Route::get('/AnuarioEstadistico/rptRemunTrabSectorPrivado/', [AnuarioEstadisticoController::class, 'rptRemunTrabSectorPrivado'])->name('AnuarioEstadistico.rptRemunTrabSectorPrivado');
Route::get('/AnuarioEstadistico/rptRemunTrabSectorPrivado_DataTable/{id}', [AnuarioEstadisticoController::class, 'rptAnuarioEstadistico_DataTable'])->name('AnuarioEstadistico.rptAnuarioEstadistico_DataTable');
Route::post('/AnuarioEstadistico/Grafico_Promedio_Remuneracion_porAnio/{id}', [AnuarioEstadisticoController::class, 'Grafico_Promedio_Remuneracion_porAnio'])->name('AnuarioEstadistico.Grafico_Promedio_Remuneracion_porAnio');
Route::post('/AnuarioEstadistico/Grafico_ranking_promedio_remuneracion_regiones/{id}', [AnuarioEstadisticoController::class, 'Grafico_ranking_promedio_remuneracion_regiones'])->name('AnuarioEstadistico.Grafico_ranking_promedio_remuneracion_regiones');


Route::get('/AnuarioEstadistico/rptTrabajadoresSectorPrivado/', [AnuarioEstadisticoController::class, 'rptTrabajadoresSectorPrivado'])->name('AnuarioEstadistico.rptTrabajadoresSectorPrivado');
Route::get('/AnuarioEstadistico/rptEmpresasSectorPrivado/', [AnuarioEstadisticoController::class, 'rptEmpresasSectorPrivado'])->name('AnuarioEstadistico.rptEmpresasSectorPrivado');

Route::get('/AnuarioEstadistico/rptPrestadoresServ4taCategoria/', [AnuarioEstadisticoController::class, 'rptPrestadoresServ4taCategoria'])->name('AnuarioEstadistico.rptPrestadoresServ4taCategoria');
Route::post('/AnuarioEstadistico/Grafico_ranking_promedio_prestadores_servicio4ta/{id}', [AnuarioEstadisticoController::class, 'Grafico_ranking_promedio_prestadores_servicio4ta'])->name('AnuarioEstadistico.Grafico_ranking_promedio_prestadores_servicio4ta');
Route::post('/AnuarioEstadistico/Grafico_ranking_empresas_regiones/{id}', [AnuarioEstadisticoController::class, 'Grafico_ranking_empresas_regiones'])->name('AnuarioEstadistico.Grafico_ranking_empresas_regiones');
Route::post('/AnuarioEstadistico/Grafico_promedio_Empresas_sectorPrivado/{id}', [AnuarioEstadisticoController::class, 'Grafico_promedio_Empresas_sectorPrivado'])->name('AnuarioEstadistico.Grafico_promedio_Empresas_sectorPrivado');

Route::get('/Actividades/Principal', [ActividadController::class, 'Principal'])->middleware('auth')->name('Actividades.Principal');

Route::get('/Trabajo/PowerBi/PlantillaElectronica', [PowerBiController::class, 'trabajoPlanillaElectronica'])->name('powerbi.trabajo.plantillaelectronica');
Route::get('/Trabajo/PowerBi/EmpleoFormal', [PowerBiController::class, 'trabajoEmpleoFormal'])->name('powerbi.trabajo.empleoformal');
Route::get('/Trabajo/PowerBi/EmpleoInformal', [PowerBiController::class, 'trabajoEmpleoInformal'])->name('powerbi.trabajo.empleoinformal');



// Route::get('/AnuarioEstadistico/rptPrestadoresServ4taPrivado/', [AnuarioEstadisticoController::class, 'rptPrestadoresServ4taPrivado'])->name('AnuarioEstadistico.rptPrestadoresServ4taPrivado');


/*********************************************** SALUD ****************************************************/
Route::get('/salud/PowerBi/Covid19', [PowerBiController::class, 'saludCovid19'])->name('powerbi.salud.covid19');
Route::get('/salud/PowerBi/{id}', [PowerBiController::class, 'saludMenu'])->name('powerbi.salud.menu');
Route::get('/salud/pactoregional', [IndicadoresController::class, 'PactoRegional'])->name('salud.indicador.pactoregional');
Route::get('/salud/pactoregional/{id}', [IndicadoresController::class, 'PactoRegionalDetalle'])->name('salud.indicador.pactoregional.detalle');
Route::get('/salud/conveniofed', [IndicadoresController::class, 'ConvenioFED'])->name('salud.indicador.conveniofed');
Route::get('/salud/pruebas', function () {
    return view('pruebas3');
})->name('salud.indicador.conveniofed');
/******************************************** FIN SALUD ***************************************************/



/*********************************************** PARAMETRO ************************************************/
Route::get('/ImporPoblacion/Importar', [ImporPoblacionController::class, 'importar'])->name('imporpoblacion.importar');
Route::post('/ImporPoblacion/Importar', [ImporPoblacionController::class, 'guardar'])->name('imporpoblacion.guardar');
Route::get('/ImporPoblacion/Listar/ImportarDT', [ImporPoblacionController::class, 'ListarDTImportFuenteTodos'])->name('imporpoblacion.listar.importados');
Route::post('/ImporPoblacion/ListaImportada', [ImporPoblacionController::class, 'ListaImportada'])->name('imporpoblacion.listarimportados');
Route::get('/ImporPoblacion/eliminar/{id}', [ImporPoblacionController::class, 'eliminar'])->name('imporpoblacion.eliminar');

/****************************************** FIN PARAMETRO ***************************************************/


/*********************************************** EJEMPLOS Y PRUEBAS ************************************************/
Route::get('/pruebas', [PruebaController::class, 'prueba1']);

Route::get('/recursos/login1', function () {
    $data = ImporDocentesBilingues::where(DB::raw('length(dni)'), '!=', 8)->get();
    foreach ($data as $key => $value) {
        echo $value->dni . '    -    ';
        $ceros = '';
        for ($i = 0; $i < 8 - strlen($value->dni); $i++) {
            $ceros .= '0';
        }
        // $idb = ImporDocentesBilingues::find($value->id);
        // $idb->dni = $ceros . $value->dni;
        // $idb->save();
        echo $ceros . $value->dni;
        echo '<br>';
    }
    //echo date('Y-m-d', strtotime("1900-01-01 + 10 days - 2 days"));
    //return view('prueba/login');
});

Route::get('/recursos/serviciosbasicos', function () {
    $data = ImporServiciosBasicos::all();
    foreach ($data as $key => $value) {
        // echo $value->codlocal.'<br>';
        $ceros = '';
        for ($i = 0; $i < 6 - strlen($value->codlocal); $i++) {
            $ceros .= '0';
        }
        //echo $ceros . $value->codlocal . '<br>';
        $ceros1 = '';
        for ($i = 0; $i < 6 - strlen($value->codgeo); $i++) {
            $ceros1 .= '0';
        }
        //echo $ceros . $value->codgeo . '<br>';
        $idb = ImporServiciosBasicos::find($value->id);
        $idb->codlocal = $ceros . $value->codlocal;
        $idb->codgeo = $ceros1 . $value->codgeo;
        $idb->save();
    }
});

Route::get('/recursos/highcharts', function () {
    return view('graficos/Highcharts');
});

//Route::get('/indicadores/{codigo}', [Indicador-GeneralController::class, 'generarCodigo']);//prueba

/* Route::get('/juego', function () {
    $v = [1, 3, 5, 7, 6, 11, 13, 15];
    foreach ($v as $key => $va) {
        foreach ($v as $key => $vb) {
            foreach ($v as $key => $vc) {
                $s = $va + $vb + $vc;
                if ($s == 30)
                    echo $va .'+'.$vb .'+'.$vc.'='.$s . 'aquiiiiiiiii<br>';
                else
                    echo $s . '<br>';
            }
        }
    }
})->name('sinruta'); */

Route::get('/ciencias/e1', function () {
    $cc = DB::table('cienciasxxx')->select(
        'tipo_acta',
        'nombre_periodo_lectivo',
        'codigo_modular',
        'nombre_sede_institucion',
        'nivel_formacion',
        'nombre_carrera',
        'nombre_plan_estudio',
        'semestre_academico',
        'turno',
        'seccion',
        'cantidad_alumno'
    )
        ->groupBy(
            'tipo_acta',
            'nombre_periodo_lectivo',
            'codigo_modular',
            'nombre_sede_institucion',
            'nivel_formacion',
            'nombre_carrera',
            'nombre_plan_estudio',
            'semestre_academico',
            'turno',
            'seccion',
            'cantidad_alumno'
        )
        ->get();
    $data = [];
    foreach ($cc as $key => $value) {
        $persona = [];
        $per = DB::table('cienciasxxx')->distinct()->select(
            'tipo_documento',
            'numero_documento',
            'nombre_completo',
            'sexo',
            'edad',
            'tiene_discapacidad'
        )
            ->where('semestre_academico', $value->semestre_academico)->where('turno', $value->turno)->where('seccion', $value->seccion)->where('cantidad_alumno', $value->cantidad_alumno)
            ->get();
        $curso = [];
        foreach ($per as $key => $itemper) {
            $cur = DB::table('cienciasxxx')->select(
                'nombre_unidad_didactica',
                'nota',
            )
                ->where('semestre_academico', $value->semestre_academico)->where('turno', $value->turno)->where('seccion', $value->seccion)->where('cantidad_alumno', $value->cantidad_alumno)
                ->where('numero_documento', $itemper->numero_documento)->where('nombre_completo', $itemper->nombre_completo)->where('sexo', $itemper->sexo)->where('edad', $itemper->edad)
                ->get();
            $itemper->unidad_didactica = $cur;
            $persona[] = $itemper;
        }

        $data[] = [
            'tipo_acta' => $value->tipo_acta,
            'nombre_periodo_lectivo' => $value->nombre_periodo_lectivo,
            'codigo_modular' => $value->codigo_modular,
            'nombre_sede_institucion' => $value->nombre_sede_institucion,
            'nivel_formacion' => $value->nivel_formacion,
            'nombre_carrera' => $value->nombre_carrera,
            'nombre_plan_estudio' => $value->nombre_plan_estudio,
            'semestre_academico' => $value->semestre_academico,
            'turno' => $value->turno,
            'seccion' => $value->seccion,
            'cantidad_alumno' => $value->cantidad_alumno,
            'persona' => $persona,
        ];
    }
    return $data;
});

/****************************************** FIN EJEMPLOS Y PRUEBAS ***************************************************/
