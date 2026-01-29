<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Administracion\Sistema;
use Carbon\Carbon;

class VisitasDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar visitas anteriores si se desea, pero mejor solo agregar
        // DB::table('adm_visitas')->truncate(); 

        $sistemas = Sistema::pluck('id')->toArray();
        // Aseguramos que tenemos IDs, si no hay, usamos null (SALUD)
        if (empty($sistemas)) {
            $sistemas = [null];
        } else {
            // Agregamos null también para simular visitas sin sistema (SALUD)
            $sistemas[] = null;
        }

        $data = [];
        $now = Carbon::now();

        // Generar datos para los últimos 30 días
        for ($i = 0; $i < 30; $i++) {
            $date = $now->copy()->subDays($i);
            
            // Para cada día, generar visitas aleatorias para cada sistema
            foreach ($sistemas as $sistemaId) {
                // Cantidad aleatoria de visitas entre 5 y 50 por sistema por día
                $count = rand(5, 50); 
                
                for ($j = 0; $j < $count; $j++) {
                    // Variar horas dentro del día
                    $visitTime = $date->copy()->setTime(rand(0, 23), rand(0, 59), rand(0, 59));
                    
                    $data[] = [
                        'ip' => '192.168.1.' . rand(1, 255),
                        'url' => 'http://demo.com/page/' . rand(1, 10),
                        'user_agent' => 'Mozilla/5.0 (Demo Bot)',
                        'sistema_id' => $sistemaId,
                        'created_at' => $visitTime,
                        'updated_at' => $visitTime,
                    ];
                }
            }
        }

        // Insertar en lotes para no sobrecargar
        foreach (array_chunk($data, 1000) as $chunk) {
            DB::table('adm_visitas')->insert($chunk);
        }
        
        $this->command->info('Se han generado ' . count($data) . ' visitas de prueba para los últimos 30 días.');
    }
}
