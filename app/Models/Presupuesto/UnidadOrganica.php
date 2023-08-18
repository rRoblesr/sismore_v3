<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadOrganica extends Model
{
    use HasFactory;
    protected $table='pres_unidadorganica';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'unidadejecutora_id',
    ];
}
