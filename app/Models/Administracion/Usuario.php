<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = "adm_usuario";

    protected $fillable = [
        'usuario',
        'email',
        'password',
        'remember_token',
        'tipo',
        'cargo',
        'layouts',
        'dni',
        'nombre',
        'apellido1',
        'apellido2',
        'sexo',
        'celular',
        'entidad',
        'estado',
    ];
}
