<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAuditoria extends Model
{
    use HasFactory;
    protected $table = "adm_usuario_auditoria";

    protected $fillable = [
        'usuario_id',
        'accion',
        'datos_anteriores',
        'datos_nuevos',
        'usuario_responsable',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
    ];
}
