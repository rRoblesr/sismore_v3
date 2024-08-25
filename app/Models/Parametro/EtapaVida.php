<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaVida extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'par_etapa_vida';

    protected $fillable = [
        'nombre'
    ];

    /* protected $hidden = [
        'created_at',
        'updated_at'
    ]; */
}
