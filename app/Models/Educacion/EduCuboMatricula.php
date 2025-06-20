<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EduCuboMatricula extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "edu_cubo_matricula";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'clocal',
        'cmodular',
        'institucion_educativa',
        'id_provincia',
        'provincia',
        'id_distrito',
        'distrito',
        'id_ugel',
        'ugel',
        'id_area',
        'area',
        'id_mod',
        'modalidad',
        'id_nivel',
        'nivel',
        'id_gestion',
        'gestion',
        'fecha_nacimiento',
        'edad',
        'id_sexo',
        'sexo',
        'lengua_materna',
        'segunda_lengua',
        'id_discapacidad',
        'discapacidad',
        'fecha_matricula',
        'id_grado',
        'grado'
    ];
}
