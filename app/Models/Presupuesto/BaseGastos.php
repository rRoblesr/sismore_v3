<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseGastos extends Model
{
    use HasFactory;
    protected $table = 'pres_base_gastos';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'dia',
    ];
}
