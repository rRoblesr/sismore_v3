<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPacto2 extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "edu_cubo_pacto02_local";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'local',
        'ugel',
        'provincia',
        'distrito',
        'area',
        'ugel_id',
        'provincia_id',
        'distrito_id',
        'area_id',
        'nivel_id',
        'modalidad',
        'fecha_inscripcion',
        'estado'
    ];
}
