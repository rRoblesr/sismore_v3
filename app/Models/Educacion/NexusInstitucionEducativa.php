<?php

namespace App\Models\Educacion;

use App\Models\Parametro\Ubigeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusInstitucionEducativa extends Model
{
    protected $table = 'edu_nexus_institucion_educativa';
    public $timestamps = true;

    protected $fillable = [
        'ugel_id',
        'ubigeo_id',
        'tipo_ie_id',
        'gestion_id',
        'zona_id',
        'cod_mod',
        'cod_local',
        'nivel_educativo_id',
        'institucion_educativa',
        'estado'
    ];

    public function ugel()
    {
        return $this->belongsTo(NexusUgel::class, 'ugel_id');
    }

    public function distrito()
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeo_id');
    }
}
