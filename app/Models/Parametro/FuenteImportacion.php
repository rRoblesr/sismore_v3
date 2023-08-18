<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuenteImportacion extends Model
{
    use HasFactory;
    protected $table = "par_fuenteimportacion";

    protected $fillable = [
        'sistema_id',
        'codigo',
        'nombre',
        'formato',
    ];
}
