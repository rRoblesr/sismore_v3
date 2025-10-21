<?php

namespace App\Models\Educacion;

use App\Models\Parametro\Sexo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NexusTrabajador extends Model
{
    protected $table = 'edu_nexus_trabajador';
    public $timestamps = true;

    protected $fillable = [
        'num_documento',
        'apellidos_nombres',
        'sexo_id',
        'fecha_nacimiento',
        'afp',
        'tipoestudios_id',
        'profesion',
        'grado_id',
        'estado'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function sexo()
    {
        return $this->belongsTo(Sexo::class, 'sexo_id');
    }

    public function tipoEstudios()
    {
        return $this->belongsTo(NexusTipoEstudios::class, 'tipoestudios_id');
    }

    public function grado()
    {
        return $this->belongsTo(NexusGrado::class, 'grado_id');
    }
}
