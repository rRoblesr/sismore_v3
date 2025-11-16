<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lengua extends Model
{
    use HasFactory;
    protected $table = 'par_lengua';
    public $timestamps = false;
    protected $fillable = [
        'codigo',
        'nombre',
        'familia_linguistica',
        'ambito_id',
        'pueblos_hablantes',
        'resolucion_ministerial'
    ];

    public function ambito()
    {
        return $this->belongsTo(LenguaAmbito::class, 'ambito_id');
    }
}
