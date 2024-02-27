<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorGeneral extends Model
{
    use HasFactory;

    protected $table = "par_indicador_general";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'instrumento_id',
        'tipo_id',
        'dimension_id',
        'unidad_id',
        'frecuencia_id',
        'fuente_dato',
        'anio_base',
        'valor_base',
        'sector_id',
        'oficina_id',
        'ficha_tecnica',
        'estado'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
