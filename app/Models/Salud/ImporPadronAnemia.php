<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronAnemia extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_impor_padron_anemia";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'ubigeo',
        'cod_unico',
        'num_doc',
        'fecha_nac',
        'seguro',
        'fecha_dx',
        'fecha_supt1',
        'num_supt1',
        'fecha_supt3',
        'num_supt3',
        'fecha_recup',
        'num_recup',
        'fecha_dosaje',
        'num_dosaje',
        'den',
        'num'
    ];
}
