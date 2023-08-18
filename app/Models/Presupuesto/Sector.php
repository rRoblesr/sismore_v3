<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;
    protected $table = 'pres_sector';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'tipogobierno_id',
        'nombre',
    ];
}
