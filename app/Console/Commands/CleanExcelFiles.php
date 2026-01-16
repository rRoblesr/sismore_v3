<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanExcelFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar archivos temporales generados por Laravel Excel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = storage_path('framework/laravel-excel');

        if (!File::exists($path)) {
            $this->info('El directorio no existe: ' . $path);
            return 0;
        }

        $files = File::files($path);
        $count = count($files);

        if ($count === 0) {
            $this->info('No hay archivos para limpiar.');
            return 0;
        }

        $this->info("Se encontraron {$count} archivos. Limpiando...");

        if (File::cleanDirectory($path)) {
            $this->info('Archivos temporales de Laravel Excel eliminados correctamente.');
        } else {
            $this->error('Hubo un error al intentar limpiar el directorio.');
            return 1;
        }

        return 0;
    }
}
