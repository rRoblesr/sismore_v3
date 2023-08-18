<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Censo extends Model
{
    use HasFactory;

    protected $table = "edu_censo"; 
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
                'importacion_id', 
                'anio_id',
                'estado',
                        ];
}
