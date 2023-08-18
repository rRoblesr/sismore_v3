<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextoEscolarDetalle extends Model
{
    use HasFactory;

    protected $table='edu_textoescolar_detalle';
    public $timestamps = false;

    protected $fillable=[

        'tableta_id',
        'institucioneducativa_id',
        'nivel_educativo_dato_adic',
        'codModular_dato_adic',
        'codLocal_dato_adic',
        'aDistribuir_estudiantes',
        'aDistribuir_docentes',
        'despachadas_estudiantes',
        'despachadas_docentes',
        'recepcionadas_estudiantes',
        'recepcionadas_docentes',
        'asignadas_estudiantes',
        'asignadas_docentes',

    ];
}
