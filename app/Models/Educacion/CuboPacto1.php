<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPacto1 extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "edu_cubo_pacto01_matriculados";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion',
        'anio',
        'mes',
        'mes_id',
        'provincia',
        'provincia_id',
        'distrito',
        'distrito_id',
        'cod_modular',
        'nombre_iiee',
        'nivelmodalidad_id',
        'nivelmodalidad_codigo',
        'nivelmodalidad',
        'sexo_id',
        'sexo',
        'edad',
        'total'
    ];
}
