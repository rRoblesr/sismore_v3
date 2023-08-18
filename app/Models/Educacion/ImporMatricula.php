<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporMatricula extends Model
{
    use HasFactory;

    protected $table = "edu_impor_matricula";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricula_id',/* * */
        'dre',
        'ugel',
        'departamento',
        'provincia',
        'distrito',/*  */
        'centro_poblado',
        'cod_mod',
        'institucion_educativa',
        'cod_nivelmod',
        'nivel_modalidad',/* 10 */
        'cod_ges_dep',
        'gestion_dependencia',
        'total_estudiantes',
        'matricula_definitiva',
        'matricula_proceso',/*  */
        'dni_validado',
        'dni_sin_validar',
        'registrado_sin_dni',
        'total_grados',
        'total_secciones',/* 20 */
        /* 'nominas_generadas',
        'nominas_aprobadas',
        'nominas_por_rectificar', */
        'tres_anios_hombre',
        'tres_anios_mujer',
        'cuatro_anios_hombre',
        'cuatro_anios_mujer',
        'cinco_anios_hombre',/*  */
        'cinco_anios_mujer',
        'primero_hombre',
        'primero_mujer',
        'segundo_hombre',
        'segundo_mujer',/* 30 */
        'tercero_hombre',
        'tercero_mujer',
        'cuarto_hombre',
        'cuarto_mujer',
        'quinto_hombre',/*  */
        'quinto_mujer',
        'sexto_hombre',
        'sexto_mujer',
        'cero_anios_hombre',
        'cero_anios_mujer',/* 40 */
        'un_anio_hombre',
        'un_anio_mujer',
        'dos_anios_hombre',
        'dos_anios_mujer',
        'mas_cinco_anios_hombre',/* 45 */
        'mas_cinco_anios_mujer'
    ];
}
