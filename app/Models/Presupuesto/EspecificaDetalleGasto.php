<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecificaDetalleGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_especificadetalle_gastos';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'especifica_id',
        'nombre',
    ];
}
