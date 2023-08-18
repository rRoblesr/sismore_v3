<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pliego extends Model
{
    use HasFactory;
    protected $table = 'pres_pliego';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'sector_id',
        'nombre',
    ];
}
