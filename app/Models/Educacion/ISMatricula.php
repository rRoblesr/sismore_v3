<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ISMatricula extends Model
{
    use HasFactory;

    protected $table = "edu_is_matricula";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'institucioneducativa_id',
        'anio_id',
        'semestre',
    ];
}
