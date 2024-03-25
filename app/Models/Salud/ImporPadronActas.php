<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronActas extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "edu_impor_padron_actas";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'nombre_municipio',
        'departamento',
        'provincia',
        'distrito',
        'fecha_inicial',
        'fecha_final',
        'fecha_envio',
        'dni_usuario_envio',
        'primer_apellido',
        'segundo_apellido',
        'prenombres',
        'numero_archivos'
    ];
}
