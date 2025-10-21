<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusEstado extends Model
{
    protected $table = 'edu_nexus_estado';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
