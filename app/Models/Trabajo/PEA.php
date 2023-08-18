<?php

namespace App\Models\Trabajo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PEA extends Model
{
    use HasFactory;

    protected $table='tra_pea';
    public $timestamps = false;

    protected $fillable=[                    
   
        'resultado',
        'sexo',
        'tipo',
       
        'anio_id',      
    ];
}
