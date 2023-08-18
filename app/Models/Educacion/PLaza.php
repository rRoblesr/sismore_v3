<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PLaza extends Model
{
    use HasFactory;
    protected $table = "edu_plaza";

    protected $fillable = [
        'importacion_id', 'estadoEstudio_id', 'zona_id', 'ugel_id', 'ubigeo_id', 'nivelModalidad_id', 'institucionEducativa_id', 'catRemun_id', 'situacionLab_id', 'tipoTrabajador_id', 'cargo_id', 'estadoPlaza_id', 'tipo_registro_id', 'situacion', 'codigoPlaza', 'esTitulado', 'nivel_educativo_dato_adic', 'institucion_educativa_dato_adic', 'documento_identidad', 'codigo_modular', 'apellido_paterno', 'apellido_materno', 'nombres', 'fecha_ingreso', 'fecha_nacimiento', 'fecha_inicio', 'fecha_termino','sexo','desc_superior'
    ];
}
