<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseIngresosDetalle extends Model
{
    use HasFactory;
    protected $table = 'pres_base_ingresos_detalle';
    public $timestamps = false;

    protected $fillable = [
        'baseingresos_id',
        'unidadejecutora_id',
        'ubigeo_id',
        'recursosingreso_id',
        'especificadetalle_id',
        'pia',
        'pim',
        'recaudado',
    ];
}
