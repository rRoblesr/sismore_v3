<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcion extends Model
{
    use HasFactory;
    protected $table = 'pres_funcion';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
