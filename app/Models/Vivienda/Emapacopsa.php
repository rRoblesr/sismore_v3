<?php

namespace App\Models\Vivienda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emapacopsa extends Model
{
    use HasFactory;

    protected $table = "viv_emapacopsa";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'manzana_id',
        "lote",
        "nro_insc",
        "nombres",
        "ruc",
        "direccion",
        "urbanizacion",
        'tipo_servicio_id',
        "estado_conexion_id ",
        "unid_uso",
        "sub_categoria_id ",
        "tar",
        "num_med",
        "lect_sec",
        "rep_sec",
    ];
}
