<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\Educacion\ImporNexusImport;
use App\Models\Educacion\Importacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcesarImportacionNexus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;
    public $tries = 1;

    protected $importacionId;
    protected $archivoPath;

    public function __construct($importacionId, $archivoPath)
    {
        $this->importacionId = $importacionId;
        $this->archivoPath = $archivoPath;
    }

    public function handle()
    {
        $importacion = Importacion::findOrFail($this->importacionId);
        $absolutePath = Storage::disk('local')->path($this->archivoPath);
        $extension = strtolower((string) pathinfo($absolutePath, PATHINFO_EXTENSION));

        try {
            ini_set('memory_limit', '-1');
            set_time_limit(0);
            ignore_user_abort(true);

            $readerType = \Maatwebsite\Excel\Excel::XLSX;
            if ($extension === 'xls') {
                $readerType = \Maatwebsite\Excel\Excel::XLS;
            } elseif ($extension === 'csv') {
                $readerType = \Maatwebsite\Excel\Excel::CSV;
            }

            Excel::import(
                new ImporNexusImport($this->importacionId),
                $absolutePath,
                null,
                $readerType,
                0
            );

            $routine = DB::selectOne("
                SELECT ROUTINE_NAME
                FROM information_schema.ROUTINES
                WHERE ROUTINE_SCHEMA = DATABASE()
                  AND ROUTINE_NAME = 'edu_pa_procesarImporNexus'
                  AND ROUTINE_TYPE = 'PROCEDURE'
                LIMIT 1
            ");
            if (!$routine) {
                throw new \RuntimeException('No existe el procedimiento edu_pa_procesarImporNexus() en la base de datos del servidor.');
            }

            DB::select('call edu_pa_procesarImporNexus(?)', [$this->importacionId]);
            $importacion->update(['estado' => 'PR']);
        } catch (\Throwable $e) {
            $importacion->update(['estado' => 'EL']);
            Log::error('ProcesarImportacionNexus failed', [
                'importacion_id' => $this->importacionId,
                'file' => $this->archivoPath,
                'extension' => $extension,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        } finally {
            if (Storage::disk('local')->exists($this->archivoPath)) {
                Storage::disk('local')->delete($this->archivoPath);
            }
        }
    }
}
