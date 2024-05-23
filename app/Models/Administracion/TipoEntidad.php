<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEntidad extends Model
{
    use HasFactory;
    protected $table = "adm_tipo_entidad";
    // public $timestamps = false;
    protected $fillable = [
        'sector_id',
        'codigo',
        'nombre',
        'estado',
    ];

    // protected $hidden = [
    //     'created_at',
    //     'updated_at'
    // ];
}
