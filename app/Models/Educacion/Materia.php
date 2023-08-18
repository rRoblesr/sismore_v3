<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table='edu_materia';

    protected $fillable=[
        'codigo',
        'descripcion',
    ];
}
