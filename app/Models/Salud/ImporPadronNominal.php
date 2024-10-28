<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronNominal extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_impor_padron_nominal";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'padron',
        'cnv',
        'cui',
        'dni',
        'num_doc',
        'tipo_doc',
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'genero',
        'fecha_nacimiento',
        'direccion',
        'ubigeo',
        'centro_poblado',
        'area_ccpp',
        'cui_nacimiento',
        'cui_atencion',
        'seguro',
        'programa_social',
        'visita',
        'menor_encontrado',
        'codigo_ie',
        'nombre_ie',
        'tipo_doc_madre',
        'num_doc_madre',
        'apellido_paterno_madre',
        'apellido_materno_madre',
        'nombres_madre',
        'celular_madre',
        'grado_instruccion',
        'lengua_madre',
        'repetido'
    ];
}
