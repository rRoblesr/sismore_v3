<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoblacionDetalle extends Model
{
    use HasFactory;

    protected $table = "par_poblacion_detalle";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'poblacion_id',
        'ubigeo_id',
        'sexo',
        'edad',
        'total',
    ];
}
