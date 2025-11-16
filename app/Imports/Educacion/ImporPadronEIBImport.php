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
        'cod_mod',
        'forma_atencion',
        'lengua_1',
        'lengua_2',
        'lengua_3',
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

        $normalizarLengua = function ($lengua) {
            if (empty($lengua)) {
                return null;
            }
            $lengua = trim(strtolower($lengua));
            return ucfirst($lengua);
        };
        return new ImporPadronEib([
            'importacion_id'    => $this->importacionId,
            'periodo'           => $row['periodo'] ?? null,
            'cod_mod'           => $row['cod_mod'] ?? null,
            'forma_atencion'    => $row['forma_atencion'] ?? null,            
            'lengua_1'          => ($row['lengua_1'] ?? null),
            'lengua_2'          => ($row['lengua_2'] ?? null),
            'lengua_3'          => ($row['lengua_3'] ?? null),
            // 'lengua_1'          => $this->normalizarLengua($row['lengua_1'] ?? null),
            // 'lengua_2'          => $this->normalizarLengua($row['lengua_2'] ?? null),
            // 'lengua_3'          => $this->normalizarLengua($row['lengua_3'] ?? null),
        ]);
    }

    // protected function normalizarLengua($lengua)
    // {
    //     if (empty($lengua)) return null;

    //     $lengua = trim(strtolower($lengua));

    //     // 🔹 Mapeo explícito: cada variante → forma oficial
    //     $mapeo = [
    //         // Ashaninka (R.M. 303-2015-MINEDU)
    //         'ashaninka'   => 'Ashaninka',
    //         'ashaninca'   => 'Ashaninka',
    //         'ashanincca'  => 'Ashaninka',
    //         'ashanicca'   => 'Ashaninka',
    //         'ashanica'    => 'Ashaninka',
    //         'ashaninkua'  => 'Ashaninka',
    //         'ashanincqa'  => 'Ashaninka',
    //         'ashaninqua'  => 'Ashaninka',
    //         'ashaninkka'  => 'Ashaninka',
    //         'ashaninca'   => 'Ashaninka', // duplicado intencional (si hay typo)

    //         // Asheninka (R.M. 199-2019-MINEDU) — ¡lengua distinta!
    //         'asheninka'   => 'Asheninka',
    //         'asheninca'   => 'Asheninka',
    //         'ashenica'    => 'Asheninka',
    //         'ashenincca'  => 'Asheninka',

    //         // Shipibo-Konibo
    //         'shipibo'            => 'Shipibo-Konibo',
    //         'konibo'             => 'Shipibo-Konibo',
    //         'shipibo konibo'     => 'Shipibo-Konibo',
    //         'shipibo-konibo'     => 'Shipibo-Konibo',
    //         'shipibokonibo'      => 'Shipibo-Konibo',
    //         'xipibo'             => 'Shipibo-Konibo',
    //         'xipibo-konibo'      => 'Shipibo-Konibo',

    //         // Yine
    //         'yine'               => 'Yine',
    //         'yine piro'          => 'Yine',
    //         'yine-mashco piro'   => 'Yine',
    //         'yines'              => 'Yine',
    //         'yiné'               => 'Yine',

    //         // Yaminahua
    //         'yaminahua'          => 'Yaminahua',
    //         'yaminawa'           => 'Yaminahua',
    //         'yaminagua'          => 'Yaminahua',
    //         'yaminahúa'          => 'Yaminahua',

    //         // Cashinahua
    //         'cashinahua'         => 'Cashinahua',
    //         'kashinawa'          => 'Cashinahua',
    //         'kashinahua'         => 'Cashinahua',
    //         'cashinawa'          => 'Cashinahua',

    //         // Sharanahua
    //         'sharanahua'         => 'Sharanahua',
    //         'sharanawa'          => 'Sharanahua',
    //         'saranahua'          => 'Sharanahua',
    //         'sharanaua'          => 'Sharanahua',

    //         // Matsigenka
    //         'matsigenka'         => 'Matsigenka',
    //         'matsiguenga'        => 'Matsigenka',

    //         // Amahuaca
    //         'amahuaca'           => 'Amahuaca',
    //         'amahuaka'           => 'Amahuaca',

    //         // Kakataibo
    //         'kakataibo'          => 'Kakataibo',
    //         'cacataibo'          => 'Kakataibo',
    //     ];

    //     // ✅ Búsqueda directa
    //     if (isset($mapeo[$lengua])) {
    //         return $mapeo[$lengua];
    //     }

    //     // 🔹 Fallback: ucfirst (caso no mapeado)
    //     return ucfirst($lengua);
    // }

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
