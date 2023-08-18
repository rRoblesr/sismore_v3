<?php

namespace App\Models\Vivienda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoConexion extends Model
{
    use HasFactory;

    protected $table = "viv_estado_conexion";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
        "estado",
    ];
}
