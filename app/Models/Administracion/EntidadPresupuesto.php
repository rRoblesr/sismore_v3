<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadPresupuesto extends Model
{
    use HasFactory;
    protected $table = "pres_entidad";
    public $timestamps = false;
    protected $fillable = [
        'codigo',
        'abreviado',
        'entidad',
        'unidadejecutadora_id',
        'dependencia',
        'dependencia',
    ];
}
