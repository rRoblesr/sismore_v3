<?php

namespace App\Services\Educacion;

use App\Models\Educacion\CuboPacto2;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class EduCuboPacto2ReportService
{
  public static function exportar_excel_locales($ugel = 0, $provincia = 0, $distrito = 0, $estado = 0)
  {
    return CuboPacto2::select(
      'local',
      'ugel',
      'provincia',
      'distrito',
      'area',
      'fecha_inscripcion',
      DB::raw('(case estado when 1 then "SANEADO" when 2 then "NO SANEADO" when 3 then "NO REGISTRADO" when 4 then "EN PROCESO" else "" end) as estado'),
    )
      ->when($ugel > 0, function ($query) use ($ugel) {
        return $query->where('ugel_id', $ugel);
      })
      ->when($provincia > 0, function ($query) use ($provincia) {
        return $query->where('provincia_id', $provincia);
      })
      ->when($distrito > 0, function ($query) use ($distrito) {
        return $query->where('distrito_id', $distrito);
      })
      ->when($estado > 0, function ($query) use ($estado) {
        return $query->where('estado', $estado);
      })
      ->get();
  }
}
