<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sexo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'par_sexo';

    protected $fillable = [
        'nombre',
        'nombre2',
        'abreviado',
    ];

    /* protected $hidden = [
        'created_at',
        'updated_at'
    ]; */
}
