<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lengua extends Model
{
    use HasFactory;
    protected $table = 'par_lengua';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado',
    ];
}
