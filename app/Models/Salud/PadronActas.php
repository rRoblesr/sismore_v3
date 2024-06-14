<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronActas extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_padron_actas";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];

    protected $fillable = [
        'ubigeo_id',
        'establecimiento_id',
        'usuario_id',
        'fecha_inicial',
        'fecha_final',
        'fecha_envio',
        'nro_archivos'
    ];
}
