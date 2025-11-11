<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporPadronEib extends Model
{
    use HasFactory;

    protected $table = "edu_impor_padron_eib";
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'periodo',
        'ugel',
        'departamento',
        'provincia',
        'distrito',
        'centro_poblado',
        'cod_mod',
        'cod_local',
        'institucion_educativa',
        'cod_nivelmod',
        'nivel_modalidad',
        'forma_atencion',
        'cod_lengua',
        'lengua_1',
        'lengua_2',
        'lengua_3',
        'estado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'periodo' => 'integer',
        'importacion_id' => 'integer',
    ];
}
