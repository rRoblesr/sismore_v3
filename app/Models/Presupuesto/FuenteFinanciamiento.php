<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuenteFinanciamiento extends Model
{
    use HasFactory;
    protected $table = 'pres_fuentefinanciamiento';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
    ];
}
