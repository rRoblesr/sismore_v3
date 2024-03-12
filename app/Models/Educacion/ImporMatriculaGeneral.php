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
        'modalidad',
        'id_nivel',
        'id_gestion',
        'pais_nacimiento',
        'fecha_nacimiento',
        'sexo',
        'lengua_materna',
        'segunda_lengua',
        'di_leve',
        'di_moderada',
        'di_severo',
        'discapacidad_fisica',
        'trastorno_espectro_autista',
        'dv_baja_vision',
        'dv_ceguera',
        'da_hipoacusia',
        'da_sordera',
        'sordoceguera',
        'otra_discapacidad',
        'situacion_matricula',
        'estado_matricula',
        'fecha_matricula',
        'id_grado',
        'dsc_grado',
        'id_seccion',
        'dsc_seccion',
        'fecha_registro',
        'fecha_retiro',
        'motivo_retiro',
        'sf_regular',
        'sf_promocion_guiada',

    ];

    protected $hide = [
        'created_at',
        'updated_at'
    ];
}
