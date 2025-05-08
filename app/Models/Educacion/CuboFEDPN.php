<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboFEDPN extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "edu_cubo_fed_pn";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'dni',
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'sexo',
        'fecha_nacimiento',
        'edad',
        'tipo_edad',
        'direccion',
        'ubigeo',
        'centro_poblado',
        'centro_poblado_nombre',
        'area_ccpp',
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
        'distrito_id',
        'distrito',
        'dependencia',
        'provincia',
        'ugel',
        'codmod_salud',
        'iiee_salud',
        'codmod_educacion',
        'iiee_educacion',
        'den',
        'num',
        'numx',
    ];
}
