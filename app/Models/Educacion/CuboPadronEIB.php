<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPadronEIB extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "edu_cubo_padron_eib";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'anio_peib',
        'anio_pw',
        'ie_id',
        'modular',
        'nombre_ie',
        'nivelmodalidad_id',
        'tipogestion_id',
        'area_id',
        'distrito_id',
        'provincia_id',
        'forma_atencion',
        'lengua_id',
        'matriculados',
        'docentes',
        'auxiliar',
        'administrativo',
        'promotor',
    ];

     protected $dates = [
        'created_at',
        'updated_at',
    ];
}
