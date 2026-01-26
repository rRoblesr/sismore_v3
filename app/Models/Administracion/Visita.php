<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;
    protected $table = "adm_visitas";

    protected $fillable = [
        'ip',
        'url',
        'user_agent',
        'sistema_id',
    ];
}
