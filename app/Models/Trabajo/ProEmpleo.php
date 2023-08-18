<?php

namespace App\Models\Trabajo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProEmpleo extends Model
{
    use HasFactory;

    protected $table='tra_proempleo';
    public $timestamps = false;

    protected $fillable=[                    
   
        'oferta_hombres',
        'oferta_mujeres',
        'demanda',
        'mes',
       
        'anio_id',
        'importacion_id',
      
    ];
}
