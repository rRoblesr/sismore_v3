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
        'cod_mod',
        'forma_atencion',
        'lengua_1',
        'lengua_2',
        'lengua_3',
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
