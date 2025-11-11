<?php

namespace App\Imports\Educacion;

use App\Models\educacion\ImporPadronEib;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporPadronEIBImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected const COLUMNAS_OBLIGATORIAS = [
        'periodo',
        'dre',
        'ugel',
        'departamento',
        'provincia',
        'distrito',
        'centro_poblado',
        'cod_mod',
        'cod_local',
        'institucion_educativa',
        'cod_nivelmod',
        'nivel_modalidad',
        'forma_atencion',
        'cod_lengua',
        'lengua_1',
        'lengua_2',
        'lengua_3',
        'estado',
    ];
    protected $importacionId;
    protected $encabezadosValidados = false;

    public function __construct($importacionId)
    {
        $this->importacionId = $importacionId;
    }

    public function model(array $row)
    {
        if (!$this->encabezadosValidados) {
            $this->validarEncabezados($row);
            $this->encabezadosValidados = true;
        }
        // COD_LOCAL: vacío o "0" → null
        $codLocal = empty($row['cod_local']) || $row['cod_local'] == '0' ? null : $row['cod_local'];
        // Estado: limpiar y estandarizar
        $estado = isset($row['estado']) ? strtoupper(trim($row['estado'])) : 'ACTIVA';
        return new ImporPadronEib([
            'importacion_id' => $this->importacionId,
            'periodo'           => intval($row['periodo'] ?? 2019),
            'ugel'              => $row['ugel'] ?? null,
            'departamento'      => 'UCAYALI',
            'provincia'         => $row['provincia'] ?? null,
            'distrito'          => $row['distrito'] ?? null,
            'centro_poblado'    => $row['centro_poblado'] ?? null,
            'cod_mod'           => $row['cod_mod'] ?? null,
            'cod_local'         => $codLocal,
            'institucion_educativa' => $row['institucion_educativa'] ?? null,
            'cod_nivelmod'      => $row['cod_nivelmod'] ?? null,
            'nivel_modalidad'   => $row['nivel_modalidad'] ?? null,
            'forma_atencion'    => $row['forma_atencion'] ?? null,
            'cod_lengua'        => null, // no viene en el Excel
            'lengua_1'          => $normalizarLengua($row['lengua_1'] ?? null),
            'lengua_2'          => $normalizarLengua($row['lengua_2'] ?? null),
            'lengua_3'          => $normalizarLengua($row['lengua_3'] ?? null),
            'estado'            => $estado,
        ]);
    }

    protected function validarEncabezados($row)
    {
        $encabezados = array_map('strtolower', array_keys($row));
        $faltantes = array_diff(self::COLUMNAS_OBLIGATORIAS, $encabezados);
        if (!empty($faltantes)) {
            throw new \InvalidArgumentException(
                'El archivo Excel no contiene las siguientes columnas obligatorias: ' . implode(', ', $faltantes)
            );
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
