<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuadroAsigPersonal extends Model
{
    use HasFactory;

    protected $table = "edu_cuadroasigpersonal";
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'unidad_ejecutora',
        'organo_intermedio',
        'provincia',
        'distrito',
        'tipo_ie',
        'gestion',
        'zona',
        'codmod_ie',
        'codigo_local',
        'clave8',
        'nivel_educativo',
        'institucion_educativa',
        'jec',
        'codigo_plaza',
        'tipo_trabajador',
        'sub_tipo_trabajador',
        'cargo',
        'situacion_laboral',
        'motivo_vacante',
        'categoria_remunerativa',
        'descripcion_escala',
        'jornada_laboral',
        'estado',
        'fecha_inicio',
        'fecha_termino',
        'tipo_registro',
        'ley',
        'fecha_ingreso',
        'documento_identidad',
        'codigo_modular',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'fecha_nacimiento',
        'sexo',
        'regimen_pensionario',
        'fecha_afiliacion_rp',
        'codigo_essalud',
        'afp',
        'codigo_afp',
        'fecha_afiliacion_afp',
        'fecha_devengue_afp',
        'mencion',
        'centro_estudios',
        'tipo_estudios',
        'estado_estudios',
        'especialidad_profesional',
        'grado',
        'celular',
        'email',
        'especialidad',
        'fecha_resolucion',
        'numero_resolucion',
        'desc_superior',
        'numero_contrato_cas',
        'numero_adenda_cas',
        'preventiva',
        'referencia_preventiva',
    ];
}
