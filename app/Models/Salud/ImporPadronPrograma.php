<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronPrograma extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_impor_padron_programa";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'programa',
        'servicio',
        'anio',
        'mes',
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
        'ubigeo_distrito',
        'ubigeo_ccpp',
        'latitud',
        'longitud',
        'num_doc_a',
        'ape_pat_a',
        'ape_mat_a',
        'nombre_a',
    ];
}
