<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forma extends Model
{
    use HasFactory;
    protected $table = "edu_forma"; 
    
    protected $fillable = [
        'nombre'];
}
