<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EceResultado extends Model
{
    use HasFactory;

    protected $table='edu_eceresultado';

    protected $fillable=[
        'ece_id',        
        'institucioneducativa_id',
        'materia_id',
        'lengua',
        'programados',
        'evaluados',
        'previo',
        'inicio',
        'proceso',
        'mediapromedio',
        'satisfactorio',
    ];
}
