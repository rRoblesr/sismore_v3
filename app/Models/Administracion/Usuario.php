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
        'layouts',
        'dni',
        'nombre',
        'apellido1',
        'apellido2',
        'sexo',
        'celular',
        'entidad',
        'cargo',
        'sector', //-> esta para eliminar
        'nivel', //-> esta para eliminar
        'codigo_institucion', //-> esta para eliminar
        'estado'
    ];
}
