<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPacto3Denominador extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_data_pacto3_denominador";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ubigeo_id',
        'anio',
        'codigo',
        'distrito',
        'meta',
        
    ];
}
