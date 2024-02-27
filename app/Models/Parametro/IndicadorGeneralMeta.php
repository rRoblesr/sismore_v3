<?php

namespace App\Models\Parametro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorGeneralMeta extends Model
{
    use HasFactory;

    protected $table = "par_indicador_general_meta";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'indicadorgeneral',
        'periodo',
        'anio',
        'valor'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
