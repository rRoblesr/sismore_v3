<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporReportePN05 extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_reporte_pn05";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [];

    protected $fillable = [
        'importacion_id',
        'distrito',
        'centro_poblado',
        'nro_ninios',
    ];
}
