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
        'clasificador',
        'tipotransaccion_id',
        'generica_id',
        'subgenerica_id',
        'subgenericadetalle_id',
        'especifica_id',
        'especificadetalle_id',
        'pia',
        'pim',
        'recaudado',
    ];
}
