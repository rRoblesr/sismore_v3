<?php

namespace App\Models\Vivienda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronEmapacopsa extends Model
{
    use HasFactory;

    protected $table = "viv_padronemapacopsa";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        "cod_dist",
        "distrito",
        "cod_sector",
        "cod_manzana",
        "manzana_nombre",
        "lote",
        "nro_insc",
        "nombres",
        "ruc",
        "direccion",
        "urbanizacion",
        "tipo_serv",
        "tipo_servicio_nombre",
        "est_conex",
        "estado_conexion_nombre",
        "unid_uso",
        "sub_categ",
        "sub_categoria_nombre",
        "tar",
        "num_med",
        "lect_sec",
        "rep_sec",
    ];
}
