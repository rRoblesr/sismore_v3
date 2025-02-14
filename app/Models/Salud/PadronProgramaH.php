<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronProgramaH extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_padron_programa_h";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'programa',
        'servicio',
        'anio',
        'mes',
    ];
}
