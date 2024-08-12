<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoblacionProyectada extends Model
{
    use HasFactory;

    protected $table = "par_poblacion_proyectada";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'fuente',
        'departamento',
        'edad',
        'rango',
        'mujer',
        'hombre',
        'total',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
