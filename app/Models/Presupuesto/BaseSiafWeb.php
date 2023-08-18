<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseSiafWeb extends Model
{
    use HasFactory;
    protected $table = 'pres_base_siafweb';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'dia',
    ];
}
