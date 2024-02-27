<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporDocentesBilingues extends Model
{
    use HasFactory;

    protected $table = "edu_impor_docentes_bilingues";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'dni',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'anio_ingreso',
        'anio_eval_l1',
        'anio_venc_l1',
        'lengua_originaria1',
        'lengua_originaria1_sin_variante',
        'dre_eval_oral_l1',
        'ugel_eval_oral_l1',
        'oral_l1',
        'escrito_l1',
        'anio_eval_l2',
        'anio_venc_l2',
        'lengua_originaria2',
        'lengua_originaria2_sin_variante',
        'dre_eval_oral_l2',
        'ugel_eval_oral_l2',
        'oral_l2',
        'escrito_l2',
        'total_lenguas'
    ];

    protected $hide = [
        'created_at',
        'updated_at'
    ];
}
