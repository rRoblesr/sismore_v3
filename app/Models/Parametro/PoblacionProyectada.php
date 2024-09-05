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
        'anio',
        'fuente',
        'codigo',
        'departamento',
        'edad',
        'grupo_etareo',
        'etapa_vida',
        'mujer',
        'hombre',
        'total',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
