<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseActividadesProyectosDetalle extends Model
{
    use HasFactory;
    protected $table = 'pres_base_actividadesproyectos_detalle';
    public $timestamps = false;

    protected $fillable = [
        'baseactividadesproyectos_id',
        'gobiernosregionales_id',
        'pia',
        'pim',
        'certificacion',
        'compromiso_anual',
        'compromiso_mensual',
        'devengado',
        'girado',
        'avance',

    ];
}
