<?php

namespace App\Models\Vivienda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datass extends Model
{
    use HasFactory;

    protected $table = "viv_datass"; 
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
                'importacion_id', 
                'departamento',
                'provincia',
                'distrito',
                'ubigeo_cp',
                'centro_poblado',

                'zona_utm_wgs84',
                'coordenadas_este',
                'coordenadas_norte',
                'altitud',

                'total_viviendas',
                'viviendas_habitadas',
                'total_poblacion',
                'predomina_primera_lengua',
                'tiene_energia_electrica',
                'tiene_internet',
                'tiene_establecimiento_salud',
                'pronoei',
                'primaria',
                'secundaria',

                'establecimiento_salud_agua',
                'pronoei_agua',
                'primaria_agua',
                'secundaria_agua',
                'funciona_establecimiento_salud',
                'funciona_pronoei',
                'funciona_primaria',
                'funciona_secundaria',
                'establecimiento_salud_banios',
                'pronoei_banios',
                'primaria_banios',
                'secundaria_banios',

                'sistema_agua',/**/

                'viviendas_conexion',
                'poblacion_servicio_agua',

                'sistema_disposicion_excretas',
                'prestador_codigo',
                'prestador_de_servicio_agua',
                'tipo_organizacion_comunal',

                'total_asociados',

                'cuota_familiar',/** */

                'tipo_cobro',
                'codigo_fuente',
                'fuente_principal',
                'tipo_fuente',


                'servicio_agua_continuo',
                'sistema_cloracion',
                'realiza_cloracion_agua',
                'tipo_sistema_agua',  
                'tipo_sistema_cloracion'        

            ];
}
