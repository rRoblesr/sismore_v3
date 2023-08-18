<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;
    protected $table='pres_meta';
    public $timestamps = false;

    protected $fillable = [
        'anio',
        'sec_fun',
        'unidadorganica_id',
    ];
}
