<?php

namespace App\Models\Trabajo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anuario_Estadistico extends Model
{
    use HasFactory;

    protected $table='tra_anuario_estadistico';
    public $timestamps = false;

    protected $fillable=[
        'importacion_id',
        'anio_id',
        'ubigeo_id',
        'enero',
        'febrero',
        'marzo',
        'abril',    
        'mayo',
        'junio',
        'julio',
        'agosto' , 
        'setiembre',
        'octubre',
        'noviembre',
        'diciembre',  
    ];
}
