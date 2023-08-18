<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenericaGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_generica_gastos';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'tipotransaccion_id',
        'nombre',
    ];
}
