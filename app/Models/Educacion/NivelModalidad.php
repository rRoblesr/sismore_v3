<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelModalidad extends Model
{
    use HasFactory;
    protected $table = "edu_nivelModalidad";

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo'
    ];
}
