<?php

namespace App\Models\educacion;

use App\Models\Educacion\Importacion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImporLocalesBeneficiados extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'edu_impor_locales_beneficiados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'cod_local',
        // 'region',
        // 'departamento',
        // 'provincia',
        // 'distrito',
        'ubigeo_id',
        // 'dre_ugel',
        'ugel_id',
        'nombre_servicios',
        'monto_asignado_mantenimiento_regular',
        'monto_asignado_rutas',
        'monto_asignado_total',
        'numero_servicios',
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
