<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LenguaAmbito extends Model
{
    use HasFactory;

    protected $table = 'par_lengua_ambito';
    public $timestamps = false;
    protected $fillable = ['codigo', 'nombre', 'descripcion'];
}
