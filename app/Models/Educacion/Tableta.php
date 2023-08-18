<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tableta extends Model
{
    use HasFactory;

    protected $table='edu_tableta';
    public $timestamps = false;

    protected $fillable=[
                    
        'importacion_id',
        'anio_id'        
    ];
}
