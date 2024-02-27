<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaGeneralDetalle extends Model
{
    use HasFactory;

    protected $table = 'edu_matricula_general_detalle';


    protected $fillable = [
        'importacion_id',
        'anio_id',
        'estado'
    ];
}
