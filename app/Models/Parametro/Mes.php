<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'par_mes';

    protected $fillable = [];

    /* protected $hidden = [
        'created_at',
        'updated_at'
    ]; */
}
