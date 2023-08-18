<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaGasto extends Model
{
    use HasFactory;
    protected $table = 'pres_categoriagasto';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
