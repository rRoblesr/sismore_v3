<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporGastos extends Model
{
    use HasFactory;
    protected $table = 'pres_impor_gastos';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
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
    ];
}
