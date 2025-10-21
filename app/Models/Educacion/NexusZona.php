<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusZona extends Model
{
    protected $table = 'edu_nexus_zona';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
