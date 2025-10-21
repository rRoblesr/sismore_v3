<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Model;

class NexusTipoRegistro extends Model
{
    protected $table = 'edu_nexus_tipo_registro';
    public $timestamps = true;

    protected $fillable = ['nombre', 'estado'];
}
