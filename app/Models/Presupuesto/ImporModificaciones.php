<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporModificaciones extends Model
{
    use HasFactory;
    protected $table = 'pres_impor_modificacion';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        'cod_pliego',
        'cod_ue',
        'notas',
        'fecha_solicitud',
        'fecha_aprobacion',
        'cod_tipo_mod',
        'tipo_modificacion',
        'documento',
        'referencia',
        'dispositivo_legal',
        'tipo_ingreso',
        'excepcion_limite',
        'justificacion',
        'tipo_financiamiento',
        'entidad_origen',
        'tipo_presupuesto',
        'sec_func',
        'cod_cat_pres',
        'tipo_prod_proy',
        'cod_prod_proy',
        'tipo_act_acc_obra',
        'cod_act_acc_obra',
        'meta',
        'cod_fina',
        'cod_rub',
        'cod_cat_gas',
        'cod_tipo_trans',
        'cod_gen',
        'cod_subgen',
        'cod_subgen_det',
        'cod_esp',
        'cod_esp_det',
        'anulacion',
        'credito',
    ];
}
