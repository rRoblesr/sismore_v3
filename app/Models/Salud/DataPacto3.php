<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPacto3 extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_data_pacto3";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'cantidad',
        'distrito',
        'mes',
        'anio',
    ];
}
