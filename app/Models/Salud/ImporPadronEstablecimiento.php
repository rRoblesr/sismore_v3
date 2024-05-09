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
        'telefono',
        'horario',
        'doc_categorizacion',
        'numero_documento',
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
        'sec_ejec',
        'ubigeo',
        'norte',
        'este',
        'cota',
        'camas'
    ];
}
