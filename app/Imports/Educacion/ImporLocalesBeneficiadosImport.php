<?php

namespace App\Imports\Educacion;

use App\Models\educacion\ImporLocalesBeneficiados;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporLocalesBeneficiadosImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected const COLUMNAS_OBLIGATORIAS = [
        'cod_local',
        'region',
        'departamento',
        'provincia',
        'distrito',
        'dre_ugel',
        'nombre_servicios',
        'monto_asignado_mantenimiento_regular',
        'monto_asignado_rutas',
        'monto_asignado_total',
        'numero_servicios',
    ];
    protected $importacionId;
    protected $encabezadosValidados = false;
    protected $distritos;
    protected $ugeles;

    public function __construct($importacionId)
    {
        $this->importacionId = $importacionId;
        // Cargar distritos (clave=nombre, valor=id) solo una vez para optimizar
        // Filtramos por longitud 6 y código que empiece con '25' (Ucayali)
        $this->distritos = DB::table('par_ubigeo')
            ->whereRaw('length(codigo) = 6')
            ->where('codigo', 'like', '25%')
            ->pluck('id', 'nombre') // Clave: Nombre, Valor: ID
            ->toArray();
        // Cargar UGELs (clave=nombre, valor=id) solo una vez para optimizar
        $this->ugeles = DB::table('edu_ugel')->where('estado', 'AC')
            ->pluck('id', 'nombre') // Clave: Nombre, Valor: ID
            ->toArray();
    }

    public function model(array $row)
    {
        if (!$this->encabezadosValidados) {
            $this->validarEncabezados($row);
            $this->encabezadosValidados = true;
        }

        // Buscar el ID del distrito usando el nombre (convertido a mayúsculas para asegurar coincidencia)
        $nombreDistrito = mb_strtoupper(trim($row['distrito'] ?? ''), 'UTF-8');
        $ubigeoId = $this->distritos[$nombreDistrito] ?? null;

        // Buscar el ID de la UGEL usando el nombre (convertido a mayúsculas para asegurar coincidencia)
        $nombreUgel = mb_strtoupper(trim($row['dre_ugel'] ?? ''), 'UTF-8');
        $ugelId = $this->ugeles[$nombreUgel] ?? null;
        // Validar si falta ubigeo_id o ugel_id
        
        return new ImporLocalesBeneficiados([
            'importacion_id' => $this->importacionId,
            'cod_local' =>str_pad($row['cod_local'] ?? null, 6, '0', STR_PAD_LEFT),
            'ubigeo_id' => $ubigeoId,
            'ugel_id' => $ugelId,
            'nombre_servicios' => $row['nombre_servicios'] ?? null,
            'monto_asignado_mantenimiento_regular' => $this->parseMonto($row['monto_asignado_mantenimiento_regular'] ?? null),
            'monto_asignado_rutas' => $this->parseMonto($row['monto_asignado_rutas'] ?? null),
            'numero_servicios' => (int)($row['numero_servicios'] ?? 0),
        ]);
    }

    protected function parseMonto($valor)
    {
        if (empty($valor)) {
            return 0;
        }
        // Limpiar S/, espacios y guiones
        $valor = trim(preg_replace('/[Ss]\/\.?\s?/', '', $valor));

        if ($valor === '-' || $valor === '') {
            return 0;
        }

        // Eliminar separadores de miles (. y ,) para convertir a entero
        // Asumimos que no hay decimales o que se desea la parte entera del monto formateado
        $valor = str_replace([',', '.'], '', $valor);

        return (int)$valor;
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
