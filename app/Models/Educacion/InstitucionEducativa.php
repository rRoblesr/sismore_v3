<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionEducativa extends Model
{
    use HasFactory;

    protected $table = "edu_institucionEducativa"; 
    
    protected $fillable = [
        'NivelModalidad_id',
        'Forma_id',
        'Caracteristica_id',
        'Genero_id',
        'TipoGestion_id',
        'TipoPrograma_id',
        'Ugel_id',
        'Area_id',
        'EstadoInsEdu_id',
        'Turno_id',
        'CentroPoblado_id',       
        'codModular',
        'anexo',
        'codLocal',
        'nombreInstEduc',
        'nombreDirector',
        'telefono',
        'email',
        'paginaWeb',
        'direccion',
        'referencia',
        'coorGeoLatitud',
        'coordGeoLongitud',
        'fechaReg',
        'fechaAct',
        'created_at',
        'updated_at'];
}

