<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusJec extends Model
{
    protected $table = 'edu_nexus_jec';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
