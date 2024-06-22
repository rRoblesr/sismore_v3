<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporEvaluacionMuestral extends Model
{
    use HasFactory;
    protected $table = 'edu_impor_evaluacion_muestral';

    protected $fillable = [
        'importacion_id',
        'anio',
        'cod_mod',
        'institucion_educativa',
        'nivel',
        'grado',
        'seccion',
        'gestion',
        'caracteristica',
        'codooii',
        'codgeo',
        'area_geografica',
        'sexo',
        'medida_l',
        'grupo_l',
        'peso_l',
        'medida_m',
        'grupo_m',
        'peso_m',
        'medida_cn',
        'grupo_cn',
        'peso_cn',
        'medida_cs',
        'grupo_cs',
        'peso_cs',
    ];
}
