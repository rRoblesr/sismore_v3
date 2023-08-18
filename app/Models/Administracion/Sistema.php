<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    use HasFactory;
    protected $table = "adm_sistema";

    protected $fillable = [
        'nombre',
        'icono',
        'pos',
        'estado'
    ];
}
