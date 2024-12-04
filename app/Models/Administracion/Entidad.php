<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;
    protected $table = "adm_entidad";
    public $timestamps = false;
    protected $fillable = [
        'tipoentidad_id',
        'codigo',
        'nombre',
        'abreviado',
        'dependencia',
        'estado',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
