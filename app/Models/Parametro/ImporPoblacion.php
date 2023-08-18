<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPoblacion extends Model
{
    use HasFactory;

    protected $table = "par_impor_poblacion";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'ubigeo',
        'departamento',
        'provincia',
        'distrito',
        'sexo',
        'edad',
        'total',
    ];
}
