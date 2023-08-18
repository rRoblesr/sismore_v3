<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporISAdmision extends Model
{
    use HasFactory;

    protected $table = "edu_impor_is_admision";
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
        'modalidad',
        'tipo_modalidad',
        'documento',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'genero',
        'fecha_nacimiento',
        'nacionalidad',
        'raza_etnia',
        'departamento',
        'provincia',
        'distrito',
        'con_discapacidad',
        'cod_modular_ie',
        'institucion_educativa',
        'anio_egreso',
        'ingreso',
    ];
}
