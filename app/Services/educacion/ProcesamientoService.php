<?php

namespace App\Services\Educacion;

use App\Http\Controllers\Educacion\ImporPadronWebController;
use App\Repositories\Educacion\ImportacionRepositorio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProcesamientoService
{
  // Constantes para evitar números mágicos
  public const PROCESO_PADRON_WEB = 1;
  public const PROCESO_PACTO2     = 2;

  /**
   * Ejecuta un procedimiento almacenado según el código de proceso.
   *
   * @param int $proceso Código del proceso a ejecutar
   * @param int $importacion ID de la importación relacionada
   * @return bool
   * @throws Exception
   */
  public function ejecutarProcesos(int $proceso, int $importacion): bool
  {
    try {
      switch ($proceso) {
        case self::PROCESO_PADRON_WEB:
          $this->procesarPadronWeb($importacion);
          break;

        case self::PROCESO_PACTO2:
          $this->procesarPacto2();
          break;

        default:
          throw new Exception("No se encuentra el proceso seleccionado: {$proceso}.");
      }

      Log::info("✅ Proceso {$proceso} ejecutado correctamente (importación {$importacion}).");
      return true;
    } catch (Exception $e) {
      Log::error("❌ Error en proceso {$proceso} (importación {$importacion}): " . $e->getMessage());
      throw $e;
    }
  }

  /**
   * Ejecuta el procedimiento almacenado del padrón web.
   *
   * @param int $importacion
   */
  private function procesarPadronWeb(int $importacion): void
  {
    DB::statement('CALL edu_pa_procesarPadronWeb(?, ?)', [$importacion, Auth::id()]);
  }

  /**
   * Ejecuta el procedimiento almacenado de pacto 2.
   */
  private function procesarPacto2(): void
  {
    $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE);

    if (!$imp) {
      throw new Exception("No se encontró importación válida para la fuente.");
    }

    DB::statement('CALL edu_pa_procesar_cubo_pacto2_01(?)', [$imp->id]);
  }
}
