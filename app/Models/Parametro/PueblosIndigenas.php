<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PueblosIndigenas extends Model
{
    use HasFactory;

    protected $table = "par_pueblos_indigenas";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'nombre_localidad',
        'tipo_localidad',
        'ambito_pueblo_indígena',
        'nombre_pueblo_indígena',
        'referencia',
        'ubigeo',
        'ubigeo_cp',
        'fuente',
        'resolucion_reconocimiento',
        'fecha_reconocimiento',
        'resolucion_plano',
        'fecha_plano',
        'resolución_titulo',
        'fecha_titulo',
        'nro_titulo',
        'partida_electronica',
        'area_titulada',
        'area_uso',
        'area_protegida',
        'total_superfie',
        'estado',
        'observacion',
        'edad_0_4',
        'edad_5_14',
        'edad_15_29',
        'edad_30_64',
        'edad_65_mas',
        'poblacion_hombre',
        'poblacion_mujer',
        'poblacion_total',
        'hogares',
        'viviendas',
        'viviendas_sin_agua',
        'viviendas_sin_desague',
        'viviendas_sin_electricidad',
        'codigo_ipress',
        'nombre_ipress',
        'categoría_ipress',
        'estado_ipress',
        'tipo_ipress',
        'red_salud',
        'codigo_modulo',
        'niveles_educativos',
        'inicial',
        'primaria',
        'secundaria',
        'modalidad',
        'total_EIB',
        'total_EBR',
        'forma',
        'escolarizada',
        'no_escolarizada',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
