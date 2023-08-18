<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoProyecto extends Model
{
    use HasFactory;
    protected $table = 'pres_producto_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
