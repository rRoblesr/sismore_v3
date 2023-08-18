<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronRER extends Model
{
    use HasFactory;

    protected $table = "edu_padron_rer";
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rer_id',
        'institucioneducativa_id',
        'total_estudiantes',
        'total_docentes',
        'total_administrativo',
        'tiempo_tras_per',
        'tiempo_tras_per_ugel',
        'tipo_transporte',
//        'created_at',
  //      'updated_at',
    ];
}
