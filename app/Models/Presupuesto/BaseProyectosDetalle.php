<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseProyectosDetalle extends Model
{
    use HasFactory;
    protected $table = 'pres_base_proyectos_detalle';
    public $timestamps = false;

    protected $fillable = [
        'baseproyectos_id',
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
