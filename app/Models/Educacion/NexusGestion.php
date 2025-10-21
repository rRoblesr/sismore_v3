<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusGestion extends Model
{
    protected $table = 'edu_nexus_gestion';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
