<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusNivelEducativo extends Model
{
    protected $table = 'edu_nexus_nivel_educativo';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
