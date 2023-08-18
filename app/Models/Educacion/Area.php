<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = "edu_area";

    protected $fillable = [
        'codigo',
        'nombre'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
