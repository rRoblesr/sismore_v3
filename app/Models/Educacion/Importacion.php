<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importacion extends Model
{
    use HasFactory;
    protected $table = "par_importacion"; 
    
    protected $fillable = [
        'fuenteImportacion_id',
        'usuarioId_Crea',
        'usuarioId_Aprueba',
        'fechaActualizacion',
        'comentario',
        'estado'];
}