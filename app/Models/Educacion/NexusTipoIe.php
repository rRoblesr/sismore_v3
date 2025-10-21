<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusTipoIe extends Model
{
    protected $table = 'edu_nexus_tipo_ie';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
