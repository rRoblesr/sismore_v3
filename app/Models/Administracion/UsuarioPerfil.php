<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPerfil extends Model
{
    use HasFactory;
    protected $table = "adm_usuario_perfil";

    protected $fillable = [
        'usuario_id',
        'perfil_id',
    ];
}
