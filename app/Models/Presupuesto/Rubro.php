<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $table = 'pres_rubro';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'fuentefinanciamiento_id',
        'nombre',
    ];

    public function fuenteFinanciamiento()
    {
        return $this->belongsTo(FuenteFinanciamiento::class, 'fuentefinanciamiento_id');
    }

    public function getNombreAttribute($value)
    {
        return $this->codigo . ' ' . $value;
    }
}
