<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModificacionDetalle extends Model
{
    use HasFactory;
    protected $table = 'pres_base_modificacion_detalle';
    public $timestamps = false;

    protected $fillable = [
        'basemodificacion_id',
        'unidadejecutora_id',
        'notas',
        'fecha_solicitud',
        'fecha_aprobacion',
        'tipomodificacion_id',
        'documento',
        'referencia',
        'dispositivo_legal',
        'tipo_ingreso',
        'excepcion_limite',
        'justificacion',
        'tipo_financiamiento',
        'entidad_origen',
        'tipo_presupuesto',
        'meta_id',
        'categoriapresupuestal_id',
        'productoproyecto_id',
        'productos_id',
        'proyectos_id',
        'act_acc_obr_id',
        'obra_id',
        'actividad_id',
        'accion_id',
        'meta',
        'finalidad_id',
        'rubro_id',
        'categoriagasto_id',
        'especialidaddetalle_id',
        'anulacion',
        'credito',

    ];
}
