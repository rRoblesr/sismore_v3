<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoModificacion extends Model
{
    use HasFactory;
    protected $table = 'pres_tipomodificacion';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
