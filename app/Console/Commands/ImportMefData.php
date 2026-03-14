<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportMefData extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'import:mef-data';

    /**
     * La descripción del comando de consola.
     *
     * @var string
     */
    protected $description = 'Importa datos desde la API del MEF a la base de datos local';

    /**
     * Crea una nueva instancia del comando.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ejecuta el comando de consola.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando proceso de importación del MEF...');
        Log::info('Cron MEF: Iniciando importación...');

        try {
            // 1. DEFINIR LA CONSULTA SQL PARA LA API
            // Reemplaza esto con tu consulta SQL validada
            // Ejemplo: Obtener datos donde DEPARTAMENTO_EJECUTORA sea 25 (Ucayali)
            // Nota: Asegúrate de usar el resource_id correcto que contenga datos de Ucayali
            $resourceId = '534994e6-2422-4e3e-97aa-bb56acb80c97'; // ID de ejemplo
            $sql = 'SELECT * FROM "' . $resourceId . '" LIMIT 100'; 
            
            $url = 'https://api.datosabiertos.mef.gob.pe/DatosAbiertos/v1/datastore_search_sql';

            $this->comment('Consultando API: ' . $url);

            // 2. REALIZAR LA PETICIÓN HTTP
            $response = Http::timeout(120)->get($url, [
                'sql' => $sql
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Detectar estructura de registros
                $records = [];
                if (isset($data['result']['records'])) {
                    $records = $data['result']['records'];
                } elseif (isset($data['records'])) {
                    $records = $data['records'];
                }

                if (empty($records)) {
                    $this->warn('La API no devolvió registros.');
                    return 0;
                }

                $count = count($records);
                $this->info("Se obtuvieron {$count} registros. Procesando...");

                // 3. GUARDAR EN LA BASE DE DATOS
                // DB::beginTransaction();
                
                $bar = $this->output->createProgressBar($count);
                $bar->start();

                foreach ($records as $row) {
                    // EJEMPLO DE INSERT/UPDATE
                    // Asegúrate de tener creada tu tabla en la base de datos
                    
                    /*
                    DB::table('nombre_de_tu_tabla')->updateOrInsert(
                        // Condiciones para buscar si existe (clave primaria o única)
                        [
                            'sec_ejec' => $row['SEC_EJEC'] ?? null,
                            'anio' => $row['ANO_DOC'] ?? date('Y')
                        ], 
                        // Valores a actualizar o insertar
                        [
                            'pliego' => $row['PLIEGO'] ?? null,
                            'monto_pim' => $row['MONTO_PIM'] ?? 0,
                            'monto_devengado' => $row['MONTO_DEVENGADO'] ?? 0,
                            'updated_at' => now()
                        ]
                    );
                    */

                    $bar->advance();
                }

                $bar->finish();
                // DB::commit();
                
                $this->newLine();
                $this->info('Importación completada exitosamente.');
                Log::info("Cron MEF: Importación finalizada. {$count} registros procesados.");

            } else {
                $this->error('Error al conectar con la API: ' . $response->status());
                Log::error('Cron MEF Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            $this->error('Excepción crítica: ' . $e->getMessage());
            Log::error('Cron MEF Excepción: ' . $e->getMessage());
        }

        return 0;
    }
}
