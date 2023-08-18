<?php

namespace App\Models\Trabajo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProEmpleo_Colocados extends Model
{
    use HasFactory;

    protected $table='tra_proempleo_colocados';
    public $timestamps = false;

    protected $fillable=[                    
   
        'ruc',
        'empresa',
        'titulo',
        'provincia',
        'distrito',
        'tipDoc',
        'documento',
        'nombres',
        'apellidos',
        'sexo',
        'per_Con_Discapacidad',
        'email',
        'telefono1',
        'telefono2',
        'colocado',
        'fuente',
        'observaciones',
        
        'proempleo_id',
      
    ];
}
