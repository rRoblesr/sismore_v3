<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporMatriculaGeneral extends Model
{
    use HasFactory;

    protected $table = "edu_impor_matricula_general";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'id_anio',
        'cod_mod',
        'id_modalidad',
        'id_nivel',
        'gestion',
        'pais_nacimiento',
        'fecha_nacimiento',
        'sexo',
        'lengua_materna',
        'segunda_lengua',
        'id_discapacidad',
        'situacion_matricula',
        'estado_matricula',
        'fecha_matricula',
        'condicion_matricula',
        'id_grado',
        'dsc_grado',
        'id_seccion',
        'dsc_seccion',
        'fecha_registro',
        'fecha_retiro',
        'motivo_retiro',
        'sf_regular',
        'sf_recuperacion',
    ];

    protected $hide = [
        'created_at',
        'updated_at'
    ];
}
