<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronPvica extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_padron_pvica";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];

    protected $fillable = [
        'importacion_id',
        'ubigeo_ccpp',
        'nombre_ccpp',
        'departamento_cpp',
        'distrito_ccpp',
        'ambito_ccpp',
        'nombre_ipress',
        'diresa_ipress',
        'red_ipress',
        'microred_ipress',
        'ubicacion_lugar_muestreo',
        'nombre_lugar_muestreo',
        'horas_dia_continuidad',
        'dias_semana_continuidad',
        'cloro_parametros_decreto',
        'conductividad_parametros_decreto',
        'ph_parametros_decreto',
        'temperatura_parametros_decreto',
        'turbiedad_parametros_decreto',
    ];
}
