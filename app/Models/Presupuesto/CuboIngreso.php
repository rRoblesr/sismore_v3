<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboIngreso extends Model
{
    use HasFactory;
    protected $table = 'pres_cubo_ingreso';
    public $timestamps = false;

    protected $fillable = [
        'anio',
        'mes',
        'unidadejecutora_id',
        'fuentefinanciamiento_id',
        'rubro_id',
        'pia',
        'pim',
        'certificado',
        'compromiso_anual',
        'devengado',
        'girado',
    ];
}

