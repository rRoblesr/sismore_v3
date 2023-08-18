<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronEib extends Model
{
    use HasFactory;

    protected $table = "edu_impor_padron_eib";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'dre',
        'ugel',
        'departamento',
        'provincia',
        'distrito',
        'centro_poblado',
        'cod_mod',
        'cod_local',
        'institucion_educativa',
        'cod_nivelmod',
        'nivel_modalidad',
        'forma_atencion',
        'cod_lengua',
        'lengua_uno',
        'lengua_dos',
        'lengua_3',
    ];
}
