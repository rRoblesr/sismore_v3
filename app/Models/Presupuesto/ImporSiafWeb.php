<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporSiafWeb extends Model
{
    use HasFactory;
    protected $table = 'pres_impor_siafweb';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        'sec_ejec',
        'cod_ue',
        'sec_func',
        'cod_cat_pres',
        'tipo_prod_proy',
        'cod_prod_proy',
        'tipo_act_acc_obra',
        'cod_act_acc_obra',
        'cod_fun',
        'cod_div_fun',
        'cod_gru_fun',
        'meta',
        'cod_fina',
        'unidad_medida',
        'valor',
        'cod_rub',
        'cod_cat_gas',
        'cod_tipo_trans',
        'cod_gen',
        'cod_subgen',
        'cod_subgen_det',
        'cod_esp',
        'cod_esp_det',
        'pia',
        'pim',
        'certificado',
        'compromiso_anual',
        'devengado',
    ];
}
