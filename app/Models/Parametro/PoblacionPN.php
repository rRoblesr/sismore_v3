<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoblacionPN extends Model
{
    use HasFactory;

    protected $table = "par_poblacion_padron_nominal";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes_id',
        'ubigeo_id',
        'cnv',
        'seguro',
        'sexo',
        '28dias',
        '0_5meses',
        '6_11meses',
        '0_12meses',
        '0a',
        '1a',
        '2a',
        '3a',
        '4a',
        '5a',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
