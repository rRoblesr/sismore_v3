<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SFL extends Model
{
    use HasFactory;

    protected $table = "edu_sfl";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institucioneducativa_id',
        'estado',
        'tipo',
        'partida_electronica',
        'zona_registral',
        'fecha_registro',
        'documento',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
