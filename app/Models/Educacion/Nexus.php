<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nexus extends Model
{
    protected $table = 'edu_nexus';
    public $timestamps = true;

    protected $fillable = [
        'importacion_id',
        'institucioneducativa_id',
        'trabajador_id',
        'cod_plaza',
        'tipotrabajador_id',
        'regimenlaboral_id',
        'cargo_id',
        'situacionlaboral_id',
        'categoria_remunerativa',
        'escala_remunerativa',
        'jec_id',
        'jornada_laboral',
        'estado_id',
        'tiporegistro_id',
        'ley',
        'regimenpensionario_id',
        'fecha_nombramiento',
        'estado'
    ];

    protected $casts = [
        'fecha_nombramiento' => 'date'
    ];

    public function institucionEducativa()
    {
        return $this->belongsTo(NexusInstitucionEducativa::class, 'institucioneducativa_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(NexusTrabajador::class, 'trabajador_id');
    }

    public function tipoTrabajador()
    {
        return $this->belongsTo(NexusRegimenLaboral::class, 'regimenlaboral_id');
    }
}
