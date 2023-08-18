<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroPoblado extends Model
{
    use HasFactory;
    protected $table = "par_centroPoblado"; 
    
    protected $fillable = [
        'Ubigeo_id',
        'codINEI',
        'codUEMinedu',
        'nombre'];
}
