<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPacto1PadronNominal extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_cubo_pacto1_padron_nominal";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion',
        'anio',
        'mes',
        'tipo_doc',
        'num_doc',
        'nombre_completo',
        'fecha_nacimiento',
        'edad',
        'tipo_edad',
        'direccion',
        'distrito_id',
        'provincia_id',
        'distrito',
        'seguro',
        'cui_atencion',
        'nombre_establecimiento',
        'num_doc_madre',
        'nombre_completo_madre',
        'lengua_madre',
        'critero01',
        'critero02',
        'critero03',
        'critero04',
        'critero05',
        'critero06',
        'critero07',
        'critero08',
        'critero09',
        'critero10',
        'num',
        'den',
    ];
}
