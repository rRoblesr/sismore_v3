<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoriosAuditoria extends Model
{
    use HasFactory;
    protected $table = "adm_directorios_auditoria";

    protected $fillable = [
        'responsable_id',
        'tipo',
        'accion',
        'datos_anteriores',
        'datos_nuevos',
        'usuario_responsable',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos' => 'array',
    ];
}
