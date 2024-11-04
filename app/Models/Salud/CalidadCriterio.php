<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalidadCriterio extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_calidad_criterio";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'criterio',
        'padron',
        'num_doc',
        'tipo_doc',
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'genero',
        'fecha_nacimiento',
        'edad',
        'tipo_edad',
        'direccion',
        'ubigeo',
        'provincia_id',
        'distrito_id',
        'centro_poblado',
        'centro_poblado_nombre',
        'area_ccpp',
        'cui_atencion',
        'establecimiento_id',
        'microred_id',
        'red_id',
        'seguro_id',
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
