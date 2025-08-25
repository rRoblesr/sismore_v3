<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Red extends Model
{
  use HasFactory;

  protected $table = 'sal_red'; // Nombre de la tabla
  // protected $primaryKey = 'id';
  // public $timestamps = true; // Tiene created_at y updated_at

  protected $fillable = [
    'codigo',
    'nombre',
    'cod_disa',
    'estado'
  ];
}
