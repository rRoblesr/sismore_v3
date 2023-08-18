<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadEjecutora extends Model
{
    use HasFactory;
    protected $table='pres_unidadejecutora';
    public $timestamps = false;

    protected $fillable = [
        'codigo_ue',
        'tipogobierno',
        'unidad_ejecutora',
        'nombre_ejecutora',
        'abreviatura',
    ];
}
