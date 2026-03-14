<?php

namespace App\Exports;

use App\Models\Presupuesto\ImporGastos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ImporGastosExport implements FromQuery, WithHeadings
{
    protected $importacion_id;

    public function __construct($importacion_id)
    {
        $this->importacion_id = $importacion_id;
    }

    public function query()
    {
        return ImporGastos::query()
            ->where('importacion_id', $this->importacion_id)
            ->select([
                'anio',
                'mes',
                'cod_niv_gob',
                'nivel_gobierno',
                'cod_sector',
                'sector',
                'cod_pliego',
                'pliego',
                'cod_ubigeo',
                'sec_ejec',
                'cod_ue',
                'unidad_ejecutora',
                'sec_func',
                'cod_cat_pres',
                'categoria_presupuestal',
                'tipo_prod_proy',
                'cod_prod_proy',
                'producto_proyecto',
                'tipo_act_acc_obra',
                'cod_act_acc_obra',
                'actividad_accion_obra',
                'cod_fun',
                'funcion',
                'cod_div_fun',
                'division_funcional',
                'cod_gru_fun',
                'grupo_funcional',
                'meta',
                'cod_fina',
                'finalidad',
                'cod_fue_fin',
                'fuente_financiamiento',
                'cod_rub',
                'rubro',
                'cod_tipo_rec',
                'tipo_recurso',
                'cod_cat_gas',
                'categoria_gasto',
                'cod_tipo_trans',
                'cod_gen',
                'generica',
                'cod_subgen',
                'subgenerica',
                'cod_subgen_det',
                'subgenerica_detalle',
                'cod_esp',
                'especifica',
                'cod_esp_det',
                'especifica_detalle',
                'pia',
                'pim',
                'certificado',
                'compromiso_anual',
                'compromiso_mensual',
                'devengado',
                'girado',
            ]);
    }

    public function headings(): array
    {
        return [
            'ANIO',
            'MES',
            'COD_NIV_GOB',
            'NIVEL_GOBIERNO',
            'COD_SECTOR',
            'SECTOR',
            'COD_PLIEGO',
            'PLIEGO',
            'COD_UBIGEO',
            'SEC_EJEC',
            'COD_UE',
            'UNIDAD_EJECUTORA',
            'SEC_FUNC',
            'COD_CAT_PRES',
            'CATEGORIA_PRESUPUESTAL',
            'TIPO_PROD_PROY',
            'COD_PROD_PROY',
            'PRODUCTO_PROYECTO',
            'TIPO_ACT_ACC_OBRA',
            'COD_ACT_ACC_OBRA',
            'ACTIVIDAD_ACCION_OBRA',
            'COD_FUN',
            'FUNCION',
            'COD_DIV_FUN',
            'DIVISION_FUNCIONAL',
            'COD_GRU_FUN',
            'GRUPO_FUNCIONAL',
            'META',
            'COD_FINA',
            'FINALIDAD',
            'COD_FUE_FIN',
            'FUENTE_FINANCIAMIENTO',
            'COD_RUB',
            'RUBRO',
            'COD_TIPO_REC',
            'TIPO_RECURSO',
            'COD_CAT_GAS',
            'CATEGORIA_GASTO',
            'COD_TIPO_TRANS',
            'COD_GEN',
            'GENERICA',
            'COD_SUBGEN',
            'SUBGENERICA',
            'COD_SUBGEN_DET',
            'SUBGENERICA_DETALLE',
            'COD_ESP',
            'ESPECIFICA',
            'COD_ESP_DET',
            'ESPECIFICA_DETALLE',
            'PIA',
            'PIM',
            'CERTIFICADO',
            'COMPROMISO_ANUAL',
            'COMPROMISO_MENSUAL',
            'DEVENGADO',
            'GIRADO',
        ];
    }
}
