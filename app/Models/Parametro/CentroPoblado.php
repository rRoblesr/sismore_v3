<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroPoblado extends Model
{
    use HasFactory;
    protected $table = "par_centropoblado";

    protected $fillable = [
        'ubigeo_id',
        'ubigeo_cp',
        'centro_poblado',
        'tipo_administrativo',
        'categoria',
        'pueblo_indigena',
        'zonautmenwgs84',
        'coordenadaseste',
        'coordenadasnorte',
        'altitud',
        'longitud',
        'latitud',
        'area_residencia'
    ];
}
