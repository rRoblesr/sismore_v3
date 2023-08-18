<?php

namespace App\Imports;

//use App\ImporGastos;

use App\Models\Educacion\Importacion;
use App\Models\Presupuesto\ImporGastos;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

//class GastosImport implements ToModel
class ImporGastosImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public $importacion;
    public function __construct($fuente, $fecha, $comentario)
    {
        $this->importacion = Importacion::Create([
            'fuenteImportacion_id' => $fuente,
            'usuarioId_Crea' => auth()->user()->id,
            'usuarioId_Aprueba' => null,
            'fechaActualizacion' => $fecha,
            'comentario' => $comentario,
            'estado' => 'PE'
        ]);
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ImporGastos([
            'importacion_id' => $this->importacion->id,
            'anio' => $row['anio'],
            'mes' => $row['mes'],
            'cod_niv_gob' => $row['cod_niv_gob'],
            'nivel_gobierno' => $row['nivel_gobierno'],/*  */
            'cod_sector' => $row['cod_sector'],
            'sector' => $row['sector'],
            'cod_pliego' => $row['cod_pliego'],
            'pliego' => $row['pliego'],
            'cod_ubigeo' => $row['cod_ubigeo'],
            'sec_ejec' => $row['sec_ejec'],/* 10 */
            'cod_ue' => $row['cod_ue'],
            'unidad_ejecutora' => $row['unidad_ejecutora'],
            'sec_func' => $row['sec_func'],
            'cod_cat_pres' => $row['cod_cat_pres'],
            'categoria_presupuestal' => $row['categoria_presupuestal'],/*  */
            'tipo_prod_proy' => $row['tipo_prod_proy'],
            'cod_prod_proy' => $row['cod_prod_proy'],
            'producto_proyecto' => $row['producto_proyecto'],
            'tipo_act_acc_obra' => $row['tipo_act_acc_obra'],
            'cod_act_acc_obra' => $row['cod_act_acc_obra'],/* 20 */
            'actividad_accion_obra' => $row['actividad_accion_obra'],
            'cod_fun' => $row['cod_fun'],
            'funcion' => $row['funcion'],
            'cod_div_fun' => $row['cod_div_fun'],
            'division_funcional' => $row['division_funcional'],/*  */
            'cod_gru_fun' => $row['cod_gru_fun'],
            'grupo_funcional' => $row['grupo_funcional'],
            'meta' => $row['meta'],
            'cod_fina' => $row['cod_fina'],
            'finalidad' => $row['finalidad'],/* 30 */
            'cod_fue_fin' => $row['cod_fue_fin'],
            'fuente_financiamiento' => $row['fuente_financiamiento'],
            'cod_rub' => $row['cod_rub'],
            'rubro' => $row['rubro'],
            'cod_tipo_rec' => $row['cod_tipo_rec'],/*  */
            'tipo_recurso' => $row['tipo_recurso'],
            'cod_cat_gas' => $row['cod_cat_gas'],
            'categoria_gasto' => $row['categoria_gasto'],
            'cod_tipo_trans' => $row['cod_tipo_trans'],
            'cod_gen' => $row['cod_gen'],/* 40 */
            'generica' => $row['generica'],
            'cod_subgen' => $row['cod_subgen'],
            'subgenerica' => $row['subgenerica'],
            'cod_subgen_det' => $row['cod_subgen_det'],
            'subgenerica_detalle' => $row['subgenerica_detalle'],/*  */
            'cod_esp' => $row['cod_esp'],
            'especifica' => $row['especifica'],
            'cod_esp_det' => $row['cod_esp_det'],
            'especifica_detalle' => $row['especifica_detalle'],
            'pia' => $row['pia'],/* 50 */
            'pim' => $row['pim'],
            'certificado' => $row['certificado'],
            'compromiso_anual' => $row['compromiso_anual'],
            'compromiso_mensual' => $row['compromiso_mensual'],
            'devengado' => $row['devengado'],/* 55 */
            'girado' => $row['girado'],
        ]);
    }

    public function batchSize(): int
    {
        return 3000;
    }
    public function chunkSize(): int
    {
        return 3000;
    }
}
