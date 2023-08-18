<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseProyectos extends Model
{
    use HasFactory;
    protected $table = 'pres_base_proyectos';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'dia',
    ];
}
