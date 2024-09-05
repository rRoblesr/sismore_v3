<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoblacionDiresa extends Model
{
    use HasFactory;

    protected $table = "par_poblacion_diresa";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'ubigeo_id',
        'sexo_id',
        'edad',
        'grupo_etareo',
        'etapa_vida',
        'total',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
