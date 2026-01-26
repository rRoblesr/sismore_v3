<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;
    protected $table = "adm_perfil";
    protected $fillable = [
        'sistema_id',
        'nombre',
        'estado',
    ];

    public const PERFIL_PUBLICOS = [46, 47, 48, 49, 50];
}
