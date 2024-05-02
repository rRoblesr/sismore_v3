<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporServiciosBasicos extends Model
{
    use HasFactory;

    protected $table = "edu_impor_serviciosbasicos";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'codlocal',
        'codgeo',
        'provincia',
        'distrito',
        'ugel',
        'cod_area',
        'area_censo',
        'cod_gest',
        'gestion',
        'modalidad',
        'agua_final',
        'desague_final',
        'luz_final',
        'internet_final',
        'tres_servicios_final'
    ];

    protected $hide = [
        'created_at',
        'updated_at'
    ];
}
