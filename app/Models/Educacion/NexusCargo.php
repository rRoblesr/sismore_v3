<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusCargo extends Model
{
    protected $table = 'edu_nexus_cargo';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
