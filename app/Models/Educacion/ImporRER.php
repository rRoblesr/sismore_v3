<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporRER extends Model
{
    use HasFactory;

    protected $table = "edu_impor_rer";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'region',
        'provincia',
        'distrito',
        'dre',
        'nombre_ugel',
        'codigo_modular',
        'area',
        'codigo_local',
        'institucion_educativa',
        'nivel_ciclo',
        'caracteristica',
        'estudantes',
        'docentes',
        'administrativos',
        'codigo_sede_rer',
        'nombre_rer',
        'tiempo_rer',
        'tiempo_rer_ugel',
        'tipo_transporte',
        'anio_creacion',
        'anio_implementacion',
        'resolucion',
    ];
}
