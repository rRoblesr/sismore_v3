<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilAdminSistema extends Model
{
    use HasFactory;
    protected $table = "adm_perfil_admin_sistema";

    protected $fillable = [
        'sistema_id',
        'perfil_id',
    ];
}
