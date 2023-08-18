<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menuperfil extends Model
{
    use HasFactory;
    protected $table = "adm_menu_perfil";
    protected $fillable = [
        'menu_id',
        'perfil_id',
    ];
}
