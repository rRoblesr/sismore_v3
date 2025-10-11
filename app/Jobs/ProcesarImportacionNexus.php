<?php

namespace App\Jobs;

use App\Imports\Educacion\ImporNexusImport;
use App\Models\Educacion\Importacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ProcesarImportacionNexus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hora (ajusta según tamaño del Excel)
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

        try {
            // Importar SOLO la primera hoja
            Excel::import(
                new ImporNexusImport($this->importacionId),
                $this->archivoPath,
                null,
                \Maatwebsite\Excel\Excel::XLSX,
                0
            );

            $importacion->update(['estado' => 'PR']);
        } catch (\Exception $e) {
            $importacion->update(['estado' => 'EL']);
            \Log::error('Error en Job ProcesarImportacionNexus: ' . $e->getMessage());
            throw $e; // opcional: re-lanzar para que se marque como failed
        } finally {
            // Opcional: eliminar el archivo temporal
            if (file_exists($this->archivoPath)) {
                unlink($this->archivoPath);
            }
        }
    }
}
