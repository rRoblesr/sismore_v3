<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microrred extends Model
{
  use HasFactory;

  protected $table = "sal_microrred";
  // protected $primaryKey = 'id';
  // public $timestamps = false;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'codigo',
    'nombre',
    'red_id',
    'cod_disa',
    'estado'
  ];
}
