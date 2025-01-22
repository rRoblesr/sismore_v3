<?php

namespace App\Imports\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgregarMetasImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new IndicadorGeneralMeta([
            'indicadorgeneral' => $row['indicador'],
            'periodo' => '',
            'distrito' => $row['distrito'],
            'anio_base' => 0,
            'valor_base' => '',
            'anio' => $row['anio'],
            'valor' => $row['meta'],
        ]);
    }
}
