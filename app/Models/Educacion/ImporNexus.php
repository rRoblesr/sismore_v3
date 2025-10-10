<?php

namespace App\Models\educacion;

use App\Models\Educacion\Importacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImporNexus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'edu_impor_nexus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'ugel',
        'provincia',
        'distrito',
        'tipo_ie',
        'gestion',
        'zona',
        'cod_mod',
        'cod_local',
        'nivel_educativo',
        'institucion_educativa',
        'cod_plaza',
        'tipo_trabajador',
        'subtipo_trabajador',
        'cargo',
        'situacion_laboral',
        'categoria_remunerativa',
        'escala_remunerativa',
        'jec',
        'jornada_laboral',
        'estado',
        'tipo_registro',
        'num_documento',
        'apellidos_nombres',
        'sexo',
        'fecha_nacimiento',
        'afp',
        'tipo_estudios',
        'profesion',
        'grado',
        'ley',
        'regimen_pensionario',
        'fecha_nombramiento',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_nombramiento' => 'date',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the importacion that owns this record.
     */
    public function importacion(): BelongsTo
    {
        return $this->belongsTo(Importacion::class, 'importacion_id');
    }
}
