<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusGrado extends Model
{
    protected $table = 'edu_nexus_grado';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
