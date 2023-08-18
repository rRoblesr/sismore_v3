<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporIngresos extends Model
{
    use HasFactory;
    protected $table = 'pres_impor_ingresos';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'cod_tipo_gob',
        'tipo_gobierno',
        'cod_sector',
        'sector',
        'cod_pliego',
        'pliego',
        'cod_ubigeo',
        'sec_ejec',
        'cod_ue',
        'unidad_ejecutora',
        'cod_fue_fin',
        'fuente_financiamiento',
        'cod_rub',
        'rubro',
        'cod_tipo_rec',
        'tipo_recurso',
        'cod_tipo_trans',
        'cod_gen',
        'generica',
        'cod_subgen',
        'subgenerica',
        'cod_subgen_det',
        'subgenerica_detalle',
        'cod_esp',
        'especifica',
        'cod_esp_det',
        'especifica_detalle',
        'pia',
        'pim',
        'recaudado',

    ];
}
