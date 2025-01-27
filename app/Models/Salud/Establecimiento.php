<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_establecimiento";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'id',
        'importacion_id',
        'cod_unico',
        'codigo_unico',
        'nombre_establecimiento',
        'responsable',
        'direccion',
        'ruc',
        'telefono',
        'horario',
        'inicio_actividad',
        'categoria',
        'estado',
        'institucion',
        'clasificacion_eess',
        'tipo_eess',
        'cod_disa',
        'disa',
        'cod_red',
        'red',
        'cod_microrred',
        'microrred',
        'red_id',
        'microrred_id',
        'ue_id',
        'ubigeo_id',
        'latitud',
        'longitud',
        //'created_at',
        //'updated_at'
    ];
}
