<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronWeb extends Model
{
    use HasFactory;

    protected $table = "edu_padronweb";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'institucioneducativa_id',
        'estadoinsedu_id',
        'total_alumno_m',
        'total_alumno_f',
        'total_alumno',
        'total_docente',
        'total_seccion',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
