<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Salud\SaludPadronDatosExport;
use App\Exports\Salud\SaludPadronSeguimientoExport;

class SaludPadronExportar extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function descargarExcel($codigo_calidad)
  {
    $sector = session('usuario_sector');
    $nombre_columna = ($sector == 'SA') ? "cod_eess_atencion" : "ubigeo";
    $codigo_institucion = session('usuario_codigo_institucion');

    return Excel::download(new SaludPadronDatosExport($codigo_calidad, $nombre_columna, $codigo_institucion), 'datos_padron_calidad.xlsx');
  }

  public function descargarExcelSeguimiento($id_grupo)
  {
    ini_set('memory_limit', '1024M');
    $sector = session('usuario_sector');
    $nombre_columna = ($sector == 'SA') ? "renaes" : "ubigeo";
    $codigo_institucion = session('usuario_codigo_institucion');

    return Excel::download(new SaludPadronSeguimientoExport($id_grupo, $nombre_columna, $codigo_institucion), 'datos_seguimiento_nino.xlsx');
  }
}
