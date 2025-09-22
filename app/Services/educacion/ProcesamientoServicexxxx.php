<?php

namespace App\Services\educacion;

use App\Http\Controllers\Educacion\ImporPadronWebController;
use App\Repositories\Educacion\ImportacionRepositorio;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProcesamientoServicexxx
{
  /**
   * Ejecuta un procedimiento almacenado según el código de proceso.
   *
   * @param int $proceso Código del proceso a ejecutar (1, 2, ...)
   * @param int $importacion ID de la importación relacionada
   * @return bool
   * @throws Exception Si el proceso no existe o falla la ejecución
   */
  public function ejecutarProcesos(int $proceso, int $importacion): bool
  {
    try {
      switch ($proceso) {
        case 1:
          DB::select('CALL edu_pa_procesarPadronWeb(?, ?)', [$importacion,  Auth::id()]);
          break;

        case 2:
          $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE);
          DB::statement('CALL edu_pa_procesar_cubo_pacto2_01(?)', [$imp->id]);
          break;

        default:
          throw new Exception("No se encuentra el proceso seleccionado: {$proceso}.");
      }

      Log::info("Proceso {$proceso} ejecutado correctamente para importación {$importacion}.");

      return true;
    } catch (Exception $e) {
      Log::error("Error ejecutando proceso {$proceso} para importación {$importacion}: " . $e->getMessage());
      throw $e; // Deja que el controlador decida cómo manejarlo (rollback, notificación, etc.)
    }
  }
}
