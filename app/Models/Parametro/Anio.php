<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anio extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'par_anio';

    protected $fillable = [
        'anio',
    ];

    /* protected $hidden = [
        'created_at',
        'updated_at'
    ]; */
}
