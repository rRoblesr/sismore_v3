<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporActividadesProyectos extends Model
{
    use HasFactory;
    protected $table = 'pres_impor_actividadesproyectos';
    public $timestamps = false;

    protected $fillable = [
        'importacion_id',
        //'departamento_inei',
        'cod_gob_reg',
        'gobiernos_regionales',
        'pia',
        'pim',
        'certificacion',
        'compromiso_anual',
        'compromiso_mensual',
        'devengado',
        'girado',
        'avance',
    ];
}
