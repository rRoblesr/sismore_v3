<?php

namespace App\Imports;

use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\ImporGastos;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImporGastosImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public $importacion;

    public function __construct($importacion = null, $fuente = null, $fecha = null, $comentario = null)
    {
        if ($importacion instanceof Importacion) {
            $this->importacion = $importacion;
        } else {
            $this->importacion = Importacion::create([
                'fuenteImportacion_id' => $fuente,
                'usuarioId_Crea' => auth()->user()->id,
                'fechaActualizacion' => $fecha,
                'estado' => 'PE'
            ]);
        }
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validar campos mínimos
        if (!isset($row['anio']) || !isset($row['mes'])) {
            return null;
        }

        return new ImporGastos([
            'importacion_id' => $this->importacion->id,
            'anio' => $row['anio'],
            'mes' => $row['mes'],
            'cod_niv_gob' => $row['cod_niv_gob'] ?? null,
            'nivel_gobierno' => $row['nivel_gobierno'] ?? null,
            'cod_sector' => $row['cod_sector'] ?? null,
            'sector' => $row['sector'] ?? null,
            'cod_pliego' => $row['cod_pliego'] ?? null,
            'pliego' => $row['pliego'] ?? null,
            'cod_ubigeo' => $row['cod_ubigeo'] ?? null,
            'sec_ejec' => $row['sec_ejec'] ?? null,
            'cod_ue' => $row['cod_ue'] ?? null,
            'unidad_ejecutora' => $row['unidad_ejecutora'] ?? null,
            'sec_func' => $row['sec_func'] ?? null,
            'cod_cat_pres' => $row['cod_cat_pres'] ?? null,
            'categoria_presupuestal' => $row['categoria_presupuestal'] ?? null,
            'tipo_prod_proy' => $row['tipo_prod_proy'] ?? null,
            'cod_prod_proy' => $row['cod_prod_proy'] ?? null,
            'producto_proyecto' => $row['producto_proyecto'] ?? null,
            'tipo_act_acc_obra' => $row['tipo_act_acc_obra'] ?? null,
            'cod_act_acc_obra' => $row['cod_act_acc_obra'] ?? null,
            'actividad_accion_obra' => $row['actividad_accion_obra'] ?? null,
            'cod_fun' => $row['cod_fun'] ?? null,
            'funcion' => $row['funcion'] ?? null,
            'cod_div_fun' => $row['cod_div_fun'] ?? null,
            'division_funcional' => $row['division_funcional'] ?? null,
            'cod_gru_fun' => $row['cod_gru_fun'] ?? null,
            'grupo_funcional' => $row['grupo_funcional'] ?? null,
            'meta' => $row['meta'] ?? null,
            'cod_fina' => $row['cod_fina'] ?? null,
            'finalidad' => $row['finalidad'] ?? null,
            'cod_fue_fin' => $row['cod_fue_fin'] ?? null,
            'fuente_financiamiento' => $row['fuente_financiamiento'] ?? null,
            'cod_rub' => $row['cod_rub'] ?? null,
            'rubro' => $row['rubro'] ?? null,
            'cod_tipo_rec' => $row['cod_tipo_rec'] ?? null,
            'tipo_recurso' => $row['tipo_recurso'] ?? null,
            'cod_cat_gas' => $row['cod_cat_gas'] ?? null,
            'categoria_gasto' => $row['categoria_gasto'] ?? null,
            'cod_tipo_trans' => $row['cod_tipo_trans'] ?? null,
            'cod_gen' => $row['cod_gen'] ?? null,
            'generica' => $row['generica'] ?? null,
            'cod_subgen' => $row['cod_subgen'] ?? null,
            'subgenerica' => $row['subgenerica'] ?? null,
            'cod_subgen_det' => $row['cod_subgen_det'] ?? null,
            'subgenerica_detalle' => $row['subgenerica_detalle'] ?? null,
            'cod_esp' => $row['cod_esp'] ?? null,
            'especifica' => $row['especifica'] ?? null,
            'cod_esp_det' => $row['cod_esp_det'] ?? null,
            'especifica_detalle' => $row['especifica_detalle'] ?? null,
            'pia' => $row['pia'] ?? 0,
            'pim' => $row['pim'] ?? 0,
            'certificado' => $row['certificado'] ?? 0,
            'compromiso_anual' => $row['compromiso_anual'] ?? 0,
            'compromiso_mensual' => $row['compromiso_mensual'] ?? 0,
            'devengado' => $row['devengado'] ?? 0,
            'girado' => $row['girado'] ?? 0,
        ]);
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
