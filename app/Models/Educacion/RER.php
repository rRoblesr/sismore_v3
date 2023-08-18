<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RER extends Model
{
    use HasFactory;

    protected $table = "edu_rer";
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo_rer',
        'nombre',
        'anio_creacion',
        'anio_implementacion',
        'fecha_resolucion',
        'numero_resolucion',
        'presupuesto',
        'ambito',
        'estado',
    ];
}
