<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;
    protected $table = "edu_caracteristica"; 
    
    protected $fillable = [
        'codigo',
        'nombre'];
}

