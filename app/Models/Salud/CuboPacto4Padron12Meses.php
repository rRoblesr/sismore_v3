<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPacto4Padron12Meses extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_cubo_pacto4_padron_12meses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'codigo_disa',
        'codigo_red',
        'codigo_unico',
        'tipo_documento',
        'numero_documento_identidad',
        'nombre_nino',
        'tipo_seguro',
        'fecha_nacimiento',
        'edad_mes',
        'edad_dias',
        'fecha_inicio',
        'fecha_final',
        'num_dni30d',
        'num_dni60d',
        'num_cred_rn',
        'num_cred_mensual',
        'cumple_cred',//->
        'num_neumo',
        'num_rota',
        'num_polio',
        'num_penta',
        'cumple_vacuna',//->
        'cumple_esq_4m',
        'cumple_esq_6m',
        'cumple_suplemento',//->
        'cumple_dosaje_hb',
        'cumple_dni_enitido_30d',
        'cumple_dni_enitido_60d',
        'den',
        'num',
        'numero_documento_madre',
        'nombre_madre',
        'nrocel_madre',
        'ubigeo',
        'provincia',
        'distrito',
        'red',
        'microred',
        'eess',
    ];
}
