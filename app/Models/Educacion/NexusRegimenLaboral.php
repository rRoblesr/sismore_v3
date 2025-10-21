<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusRegimenLaboral extends Model
{
    protected $table = 'edu_nexus_regimen_laboral';
    public $timestamps = true;

    protected $fillable = ['nombre', 'dependencia', 'estado'];

    public function padre()
    {
        return $this->belongsTo(NexusRegimenLaboral::class, 'dependencia');
    }

    public function hijos()
    {
        return $this->hasMany(NexusRegimenLaboral::class, 'dependencia');
    }
}
