<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoResultado extends Model
{
    use HasFactory;

    protected $table = "edu_censoresultado"; 
    public $timestamps = false;

    /**
     * The attributes that are mass assignable,
     *
     * @var array
     */
    protected $fillable = [
                'censo_id',                
                'codLocal',
                'codigosModulares',
                'nombreInstitucion',
                'codigoGestion',
                'descripcionGestion',
                'codigoOrganoInter',
                'nombreDre_Ugel',
                'codigoUbigeo',
                'Departamento',
                'Provincia',
                'Distrito',
                'centoPoblado',
                'direccion',
                'areaGeo',
                'estadoCenso',
                'totalAulas',
                'aulasBuenas',
                'aulasRegulares',
                'aulasMalas',
                'noPuedePrecisarEstadoAulas',
                'elLocalEs',
                'propietarioLocal',
                'cuenta_con_itse',
                'plan_contingencia',
                'plan_desastre',
                'plandesastre_act',
                'compuEscri_operativos',
                'compuEscri_inoperativos',
                'compuPorta_operativos',
                'compuPorta_inoperativos',
                'lapto_operativos',
                'lapto_inoperativos',
                'tieneInternet',
                'tipoConexion',
                'fuenteEnergiaElectrica',
                'empresaEnergiaElect',
                'tieneEnergiaElectTodoDia',
                'fuenteAgua',
                'empresaAgua',
                'tieneAguaPotTodoDia',
                'desagueInfo',
                            ];
}
