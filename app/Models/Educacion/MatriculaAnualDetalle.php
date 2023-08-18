<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaAnualDetalle extends Model
{
    use HasFactory;

    protected $table='edu_matricula_anual_detalle';
    public $timestamps = false;

    protected $fillable=[                    
                        'matricula_anual_id',    
                        'nivel',
                        'dreu',
                        'ugel',
                        'departamento',
                        'provincia',
                        'distrito',
                        'centro_poblado',
                        'cod_mod',
                        'nombreIE',
                        'nivel_especifico',
                        'modalidad',
                        'tipo_ie',
                        'total_grados',
                        'total_secciones',
                        'actas_generadas_regular',
                        'actas_aprobadas_regular',
                        'actas_rectificar_regular',
                        'estado_fase_regular',
                        'actas_generadas_recup',
                        'actas_aprobadas_recup',
                        'actas_por_rectificar_recup',
                        'estado_fase_recuperacion',
                        'estado_anio_escolar',
                        'total_estud_matriculados',
                        'cero_nivel_concluyeron',
                        'cero_nivel_trasladado',
                        'cero_nivel_retirados',
                        'primer_nivel_aprobados',
                        'primer_nivel_trasladados',
                        'primer_nivel_retirados',
                        'primer_nivel_requieren_recup',
                        'primer_nivel_desaprobados',
                        'segundo_nivel_aprobados',
                        'segundo_nivel_trasladados',
                        'segundo_nivel_retirados',
                        'segundo_nivel_requieren_recup',
                        'segundo_nivel_desaprobados',
                        'tercer_nivel_aprobados',
                        'tercer_nivel_trasladados',
                        'tercer_nivel_retirados',
                        'tercer_nivel_requieren_recup',
                        'tercer_nivel_desaprobados',
                        'cuarto_nivel_aprobados',
                        'cuarto_nivel_trasladados',
                        'cuarto_nivel_retirados',
                        'cuarto_nivel_requieren_recup',
                        'cuarto_nivel_desaprobados',
                        'quinto_nivel_aprobados',
                        'quinto_nivel_trasladados',
                        'quinto_nivel_retirados',
                        'quinto_nivel_requieren_recup',
                        'quinto_nivel_desaprobados',
                        'sexto_nivel_aprobados',
                        'sexto_nivel_trasladados',
                        'sexto_nivel_retirados',
                        'sexto_nivel_requieren_recup',
                        'sexto_nivel_desaprobados',   
    ];
}
