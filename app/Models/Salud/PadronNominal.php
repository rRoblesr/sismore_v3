<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronNominal extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_padron_nominal";

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
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'genero',
        'fecha_nacimiento',
        'direccion',
        'ubigeo',
        'centro_poblado',
        'codigo_unico_nacimiento',
        'codigo_unico_atencion',
        'seguro',
        'tipo_doc_madre',
        'num_doc_madre',
        'apellido_paterno_madre',
        'celular_ma',
        'lengua_ma',
        'visita',
        'menor_encontrado'
    ];
}
