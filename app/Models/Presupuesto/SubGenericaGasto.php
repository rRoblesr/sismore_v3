<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGenericaGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_subgenerica_gastos';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'generica_id',
        'nombre',
    ];
}
