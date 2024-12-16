<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPacto3PadronMaterno extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_cubo_pacto3_padron_materno";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'num_doc',
        'fecha_parto',
        'semana_nac',
        'gest_37sem',
        'codigo_unico',
        'red',
        'microred',
        'eess_parto',
        'provincia',
        'ubigeo_distrito',
        'distrito',
        'distrito_id',
        'provincia_id',
        'denominador',
        'numerador',
        'num_exam_hb',
        'num_exam_sifilis',
        'num_exam_vih',
        'num_exam_bacteriuria',
        'num_perfil_obstetrico',
        'num_exam_aux',
        'num_apn1_1trim',
        'num_apn1_2trim',
        'num_apn2_2trim',
        'num_apn1_3trim',
        'num_apn2_3trim',
        'num_apn3_3trim',
        'num_apn',
        'num_entrega1_sfaf',
        'num_entrega2_sfaf',
        'num_entrega3_sfaf',
        'num_entrega4_sfaf',
        'num_entrega5_sfaf',
        'num_entrega_sfaf',
    ];
}
