<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextosEscolares extends Model
{
    use HasFactory;

    protected $table = "edu_textos_escolares"; 
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                'importacion_id',  
                'anio_id',            
                'region',
                'cod_ugel',
                'ugel',
                'dotacion',
                'direccion',
                'codigo_sigema',
                'codigo_siga',
                'material',
                'beneficiario',
                'cantidad_ugel',
                'peso_unitario',
                'peso_total_kg',
                'volumen_unitario',
                'fecha_llegada_ugel',
                'anio',
                'mes',
                'tramo',
    ];
}
