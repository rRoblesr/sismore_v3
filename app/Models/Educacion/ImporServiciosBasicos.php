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
        'dpto',
        'prov',
        'dist',
        'area_censo',
        'pub_priv',
        'modalidad',
        'region',
        'agua_final',
        'desague_final',
        'luz_final',
        'internet_final',
        'tres_servicios_final',
        'todo'
    ];

    protected $hide = [
        'created_at',
        'updated_at'
    ];
}
