<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseGastosDetalle extends Model
{
    use HasFactory;
    protected $table = 'pres_base_gastos_detalle';
    public $timestamps = false;

    protected $fillable = [
        'basegastos_id',
        'unidadejecutora_id',
        'ubigeo_id',
        'meta_id',
        'categoriapresupuestal_id',
        'productoproyecto_id',
        'productos_id',
        'proyectos_id',
        'act_acc_obr_id',
        'obra_id',
        'actividades_id',
        'accion_id',
        'grupofuncional_id',
        'meta',
        'finalidad_id',
        'recursosgastos_id',
        'categoriagasto_id',
        'especificadetalle_id',
        'pia',
        'pim',
        'certificado',
        'compromiso_anual',
        'compromiso_mensual',
        'devengado',
        'girado',
        'avance',
    ];
}
