<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronNominal extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "edu_impor_padron_nominal";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'cod_mod',
        'cod_estudiante',
        'dni',
        'validacion_dni',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'sexo',
        'nacionalidad',
        'fecha_nacimiento',
        'lengua_materna',
        'fecha_matricula',
        'grado',
        'seccion',
    ];

    // protected $hide = [
    //     'created_at',
    //     'updated_at'
    // ];
}
