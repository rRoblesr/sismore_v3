<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGenericaDetalleGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_subgenericadetalle_gastos';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'subgenerica_id',
        'nombre',
    ];
}
