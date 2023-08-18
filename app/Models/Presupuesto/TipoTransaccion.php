<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTransaccion extends Model
{
    use HasFactory;
    protected $table = 'pres_tipotransaccion';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
