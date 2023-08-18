<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ece extends Model
{
    use HasFactory;
    protected $table='edu_ece';

    protected $fillable=[
        'importacion_id',
        'grado_id',
        'anio_id',
        'tipo',
    ];
}
