<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporTextoEscolar extends Model
{
    use HasFactory;

    protected $table = "edu_impor_textoescolar";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'ugel',
        'provincia',
        'distrito',
        'cod_mod',
        'institucion_educativa',
        'estado',
        'tabletas_programadas',
        'cargadores_programadas',
        'tabletas_chip',
        'tabletas_pecosa',
        'cargadores_pecosa',
        'tabletas_pecosa_siga',
        'cargadores_pecosa_siga',
        'tabletas_entregadas_sigema',
        'cargadores_entregadas_sigema',
        'tabletas_recepcionadas',
        'cargadores_recepcionadas',
        'tabletas_asignadas',
        'tabletas_asignadas_estudiantes',
        'tabletas_asignadas_docentes',
        'cargadores_asignadas',
        'cargadores_asignadas_estudiantes',
        'cargadores_asignadas_docentes',
        'tabletas_devueltas',
        'cargadores_devueltos',
        'tabletas_perdidas',
        'cargadores_perdidos',
    ];
}
