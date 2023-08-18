<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = "adm_menu";
    protected $fillable = [
        'sistema_id',
        'dependencia',
        'nombre',
        'url',
        'posicion',
        'icono',
        'parametro',
        'estado',
    ];
}
