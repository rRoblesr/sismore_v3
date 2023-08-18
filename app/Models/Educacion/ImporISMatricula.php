<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporISMatricula extends Model
{
    use HasFactory;

    protected $table = "edu_impor_is_matricula";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'cod_mod',
        'cod_local',
        'instituto_superior',
        'cod_carrera',
        'carrera_especialidad',
        'tipo_matricula',
        'semestre',
        'ciclo',
        'turno',
        'seccion',
        'codigo_estudiante',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'genero',
        'fecha_nacimiento',
        'nacionalidad',
        'raza_etnia',
        'con_discapacidad',

    ];
}
