<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecificaGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_especifica_gastos';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'subgenericadetalle_id',
        'nombre',
    ];
}
