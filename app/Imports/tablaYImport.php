<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class tablaXImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        return $row; // Retorna cada fila como array para el procesamiento
    }

    public function chunkSize(): int
    {
        return 500; // Ajusta el tamaño del fragmento según la capacidad del sistema
    }
}
