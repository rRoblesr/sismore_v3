<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGobierno extends Model
{
    use HasFactory;
    protected $table='pres_tipo_gobierno';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'tipogobierno',
    ];
}
