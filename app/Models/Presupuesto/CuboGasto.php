<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_cubo_gasto';
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

