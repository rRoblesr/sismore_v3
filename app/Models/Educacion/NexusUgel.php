<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusUgel extends Model
{
    protected $table = 'edu_nexus_ugel';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
