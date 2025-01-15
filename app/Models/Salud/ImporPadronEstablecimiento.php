<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronEstablecimiento extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_impor_padron_establecimiento";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'cod_unico',
        'nombre_establecimiento',
        'responsable',
        'direccion',
        'ruc',
        'ubigeo',
        'telefono',
        'horario',
        'inicio_actividad',
        'categoria',
        'estado',
        'institucion',
        'clasificacion_eess',
        'tipo_eess',
        'sec_ejec',
        'cod_disa',
        'disa',
        'cod_red',
        'red',
        'cod_microrred',
        'microrred',
        'latitud',
        'longitud',
    ];
}
