<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidasRestringidas extends Model
{
    use HasFactory;
    protected $table='pres_partidasrestringidas';
    public $timestamps = false;

    protected $fillable = [
        'anio',
        'especificadetalle_id',
        'codigo',
        'estado',
    ];
}
