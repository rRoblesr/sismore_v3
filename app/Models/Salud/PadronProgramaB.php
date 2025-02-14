<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronProgramaB extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_padron_programa_b";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'impor_padron_programa_id',
        'importacion_id',
        'tipo_doc',
        'num_doc_m',
        'ape_pat_m',
        'ape_mat_m',
        'nombre_m',
        'sexo',
        'fec_nac_m',
        'telefono',
        'direccion',
        'referencia',
        'ubigeo',
        'ubigeo_ccpp',
        'latitud',
        'longitud',
        'num_doc_a',
        'ape_pat_a',
        'ape_mat_a',
        'nombre_a'
    ];
}
