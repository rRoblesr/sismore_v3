<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaDetalle extends Model
{
    use HasFactory;

    protected $table = 'edu_matricula_detalle';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'institucioneducativa_id',
        'total_estudiantes',
        'matricula_definitiva',
        'matricula_proceso',
        'dni_validado',
        'dni_sin_validar',
        'registrado_sin_dni',
        'total_grados',
        'total_secciones',
        'tres_anios_hombre',
        'tres_anios_mujer',
        'cuatro_anios_hombre',
        'cuatro_anios_mujer',
        'cinco_anios_hombre',
        'cinco_anios_mujer',
        'primero_hombre',
        'primero_mujer',
        'segundo_hombre',
        'segundo_mujer',
        'tercero_hombre',
        'tercero_mujer',
        'cuarto_hombre',
        'cuarto_mujer',
        'quinto_hombre',
        'quinto_mujer',
        'sexto_hombre',
        'sexto_mujer',
        'cero_anios_hombre',
        'cero_anios_mujer',
        'un_anio_hombre',
        'un_anio_mujer',
        'dos_anios_hombre',
        'dos_anios_mujer',
        'mas_cinco_anios_hombre',
        'mas_cinco_anios_mujer'
    ];
}
