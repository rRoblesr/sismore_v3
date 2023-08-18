<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PadronEIB extends Model
{
    use HasFactory;

    protected $table = "edu_padron_EIB";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio_id',
        'institucioneducativa_id',
        'forma_atencion',
        'cod_lengua',
        'lengua1_id',
        'lengua2_id',
        'lengua3_id',
    ];
}
