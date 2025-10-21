<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusSituacionLaboral extends Model
{
    protected $table = 'edu_nexus_situacion_laboral';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
