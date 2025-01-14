<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorioPN extends Model
{
  use HasFactory;

  protected $table = "sal_directorio_pn";
  //public $timestamps = false;
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
    'profesion',
    'cargo',
    'condicion_laboral',
    'red_id',
    'microred_id',
    'establecimiento_id',
    'nivel',
    'codigo',
    'celular',
    'email',
    //        'created_at',
    //      'updated_at',
  ];
}
