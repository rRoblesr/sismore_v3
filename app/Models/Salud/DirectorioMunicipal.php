<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorioMunicipal extends Model
{
  use HasFactory;

  protected $table = "sal_directorio_municipal";
  // public $timestamps = false;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'dni',
    'nombres',
    'apellido_paterno',
    'apellido_materno',
    'sexo',
    'profesion',
    'cargo',
    'condicion_laboral',
    'provincia_id',
    'distrito_id',
    'celular',
    'email'
    //        'created_at',
    //      'updated_at',
  ];
}
