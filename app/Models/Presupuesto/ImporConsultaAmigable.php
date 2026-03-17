<?php

namespace App\Models\Presupuesto;

use App\Models\Educacion\Importacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporConsultaAmigable extends Model
{
    use HasFactory;
    protected $table = 'pres_impor_consulta_amigable';

    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'dia',
        'tipo',
        'cod_gob_reg',
        'gobiernos_regionales',
        'pia',
        'pim',
        'certificacion',
        'compromiso_anual',
        'compromiso_mensual',
        'devengado',
        'girado',
        'avance',
    ];

    protected $casts = [
        'importacion_id' => 'integer',
        'anio' => 'integer',
        'mes' => 'integer',
        'dia' => 'integer',
        'tipo' => 'integer',
        'cod_gob_reg' => 'integer',
        'pia' => 'decimal:2',
        'pim' => 'decimal:2',
        'certificacion' => 'decimal:2',
        'compromiso_anual' => 'decimal:2',
        'compromiso_mensual' => 'decimal:2',
        'devengado' => 'decimal:2',
        'girado' => 'decimal:2',
        'avance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function importacion()
    {
        return $this->belongsTo(Importacion::class, 'importacion_id');
    }
}
