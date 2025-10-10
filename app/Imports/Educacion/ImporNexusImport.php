<?php

namespace App\Imports\Educacion;

use App\Models\educacion\ImporNexus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporNexusImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected const COLUMNAS_OBLIGATORIAS = [
        'ugel',
        'provincia',
        'distrito',
        'tipo_ie',
        'gestion',
        'zona',
        'cod_mod',
        'cod_local',
        'nivel_educativo',
        'institucion_educativa',
        'cod_plaza',
        'tipo_trabajador',
        'subtipo_trabajador',
        'cargo',
        'situacion_laboral',
        'jec',
        'jornada_laboral',
        'estado',
        'tipo_registro',
        'num_documento',
        'apellidos_nombres',
        'sexo',
        'fecha_nacimiento',
        'afp',
        'tipo_estudios',
        'profesion',
        'grado',
        'ley',
        'regimen_pensionario',
        'fecha_nombramiento',
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
        return new ImporNexus([
            'importacion_id' => $this->importacionId,
            'ugel' => $row['ugel'] ?? null,
            'provincia' => $row['provincia'] ?? null,
            'distrito' => $row['distrito'] ?? null,
            'tipo_ie' => $row['tipo_ie'] ?? null,
            'gestion' => $row['gestion'] ?? null,
            'zona' => $row['zona'] ?? null,
            'cod_mod' => $row['cod_mod'] ?? null,
            'cod_local' => $row['cod_local'] ?? null,
            'nivel_educativo' => $row['nivel_educativo'] ?? null,
            'institucion_educativa' => $row['institucion_educativa'] ?? null,
            'cod_plaza' => $row['cod_plaza'] ?? null,
            'tipo_trabajador' => $row['tipo_trabajador'] ?? null,
            'subtipo_trabajador' => $row['subtipo_trabajador'] ?? null,
            'cargo' => $row['cargo'] ?? null,
            'situacion_laboral' => $row['situacion_laboral'] ?? null,
            'categoria_remunerativa' => $row['categoria_remunerativa'] ?? null,
            'escala_remunerativa' => $row['escala_remunerativa'] ?? null,
            'jec' => $row['jec'] ?? null,
            'jornada_laboral' => $row['jornada_laboral'] ?? null,
            'estado' => $row['estado'] ?? null, // este es el "ESTADO" del Excel
            'tipo_registro' => $row['tipo_registro'] ?? null,
            'num_documento' => $row['num_documento'] ?? null,
            'apellidos_nombres' => $row['apellidos_nombres'] ?? null,
            'sexo' => $row['sexo'] ?? null,
            'fecha_nacimiento' => $this->parseFecha($row['fecha_nacimiento'] ?? null),
            'afp' => $row['afp'] ?? null,
            'tipo_estudios' => $row['tipo_estudios'] ?? null,
            'profesion' => $row['profesion'] ?? null,
            'grado' => $row['grado'] ?? null,
            'ley' => $row['ley'] ?? null,
            'regimen_pensionario' => $row['regimen_pensionario'] ?? null,
            'fecha_nombramiento' =>$this->parseFecha($row['fecha_nombramiento'] ?? null),
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
