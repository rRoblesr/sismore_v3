<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporMatriculaGeneral extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "edu_impor_matricula_general";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'cod_mod',
        'id_mod',
        'id_nivel',
        'id_gestion',
        'id_sexo',
        'fecha_nacimiento',
        'pais_nacimiento',
        'lengua_materna',
        'segunda_lengua',
        'id_discapacidad',
        'discapacidad',
        'situacion_matricula',
        'fecha_matricula',
        'id_grado',
        'grado',
        'id_seccion',
        'seccion',
        'fecha_registro',
        'fecha_retiro',
        'motivo_retiro',
        'sf_regular',
        'sf_recuperacion'
    ];

    // protected $hide = [
    //     'created_at',
    //     'updated_at'
    // ];
}
