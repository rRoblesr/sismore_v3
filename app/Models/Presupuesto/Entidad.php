<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;
    protected $table='pres_entidad';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'entidad',
        'unidadejecutadora_id',
        'dependencia',
        'estado',
        'abreviado',
    ];
}
