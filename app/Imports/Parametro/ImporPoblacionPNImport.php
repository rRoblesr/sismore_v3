<?php

namespace App\Imports\Parametro;

use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\Sexo;
use App\Models\Parametro\Ubigeo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporPoblacionPNImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected const COLUMNAS_OBLIGATORIAS = [
        'anio',
        'mes',
        'ubigeo',
        'sexo',
        'seguro',
        'cnv',
        '0a',
        '1a',
        '2a',
        '3a',
        '4a',
        '5a',
        '28dias',
        '0_5meses',
        '6_11meses',
    ];
    protected $importacionId;
    protected $encabezadosValidados = false;
    protected $meses;
    protected $ubigeos;
    protected $sexos;

    public function __construct($importacionId)
    {
        $this->importacionId = $importacionId;
        $this->meses = Mes::pluck('id', 'codigo');
        $this->ubigeos = Ubigeo::whereRaw('length(codigo) = 6')->where('codigo', 'like', '25%')->pluck('id', 'codigo');
        $this->sexos = Sexo::pluck('id', 'id');
    }

    public function model(array $row)
    {
        if (!$this->encabezadosValidados) {
            $this->validarEncabezados($row);
            $this->encabezadosValidados = true;
        }
        return new PoblacionPN([
            'importacion_id' => $this->importacionId,
            'anio' => $row['anio'] ?? null,
            'mes_id' => $this->meses[$row['mes']] ?? null,
            'ubigeo_id' => $this->ubigeos[$row['ubigeo']] ?? null,
            'sexo_id' => $this->sexos[$row['sexo']] ?? null,
            'seguro' => $row['seguro'] ?? null,
            'cnv' => $row['cnv'] ?? null,
            '0a' => $row['0a'] ?? null,
            '1a' => $row['1a'] ?? null,
            '2a' => $row['2a'] ?? null,
            '3a' => $row['3a'] ?? null,
            '4a' => $row['4a'] ?? null,
            '5a' => $row['5a'] ?? null,
            '28dias' => $row['28dias'] ?? null,
            '0_5meses' => $row['0_5meses'] ?? null,
            '6_11meses' => $row['6_11meses'] ?? null,
        ]);
    }

    protected function parseFecha($valor)
    {
        if (empty($valor)) {
            return null;
        }
        if (is_string($valor) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
            return $valor;
        }
        if (is_numeric($valor)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
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
