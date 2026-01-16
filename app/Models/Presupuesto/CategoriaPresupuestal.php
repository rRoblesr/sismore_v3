<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaPresupuestal extends Model
{
    use HasFactory;
    protected $table = 'pres_categoriapresupuestal';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'categoria_presupuestal',
        'tipo_categoria_presupuestal'
    ];

    public function getNombreAttribute()
    {
        $nombre = $this->codigo . ' ' . $this->categoria_presupuestal;
        return trim($nombre) == '' ? 'SIN INFORMACION' : $nombre;
    }
}
