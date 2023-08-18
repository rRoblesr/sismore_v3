<?php

namespace App\Models\Vivienda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroPobladoDatass extends Model
{
    use HasFactory;

    protected $table = "viv_centropoblado_datass";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'ubigeo_id',
        'predomina_primera_lengua_id',
        'prestador_Servicio_Agua_id',
        'tipo_sistema_agua_id',
        'nombre',
        'total_viviendas',
        'viviendas_habitadas',
        'total_poblacion',
        'tiene_energia_electrica',
        'tiene_internet',
        'tiene_establecimiento_salud',
        'pronoei',
        'primaria',
        'secundaria',
        'sistema_agua',
        'sistema_disposicion_excretas',
        'cuota_familiar',
        'servicio_agua_continuo',
        'sistema_cloracion',
        'realiza_cloracion_agua',
    ];
}
