<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    use HasFactory;
    protected $table = "par_ubigeo";

    protected $fillable = [
        'codigo',
        'nombre',
        'dependencia',
        'capital',
        'region',
        'macroregion_inei',
        'region_natural',
        'superficie',
        'altitud',
        'latitud',
        'longitud',
        'frontera',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
