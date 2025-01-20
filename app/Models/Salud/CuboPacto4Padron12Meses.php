<?php

namespace App\Models\Salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuboPacto4Padron12Meses extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = "sal_cubo_pacto4_padron_12meses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'anio',
        'mes',
        'mesl',
        'tipo_doc',
        'num_doc',
        'fecha_nac',
        'sexo',
        'ubigeo',
        'seguro',
        'edad_dias',
        'edad_mes',
        'peso_cnv',
        'semana_gest_cnv',
        'denominador',
        'numerador',
        'numerador_sindni',
        'num_cred_rn',
        'fecha_cred_rn1',
        'fecha_cred_rn2',
        'fecha_cred_rn3',
        'fecha_cred_rn4',
        'num_cred_mensual',
        'fecha_cred_mes1',
        'fecha_cred_mes2',
        'fecha_cred_mes3',
        'fecha_cred_mes4',
        'fecha_cred_mes5',
        'fecha_cred_mes6',
        'fecha_cred_mes7',
        'fecha_cred_mes8',
        'fecha_cred_mes9',
        'fecha_cred_mes10',
        'fecha_cred_mes11',
        'num_vac',
        'num_vac_antineumococica',
        'fecha_vac_antineumococica1',
        'fecha_vac_antineumococica2',
        'num_vac_antipolio',
        'fecha_vac_antipolio1',
        'fecha_vac_antipolio2',
        'fecha_vac_antipolio3',
        'num_vac_pentavalente',
        'fecha_vac_pentavalente1',
        'fecha_vac_pentavalente2',
        'fecha_vac_pentavalente3',
        'num_vac_rotavirus',
        'fecha_vac_rotavirus1',
        'fecha_vac_rotavirus2',
        'num_esq',
        'num_esq4m',
        'fecha_esq4m_sup_e1',
        'num_esq6m',
        'num_esq6m_sup',
        'fecha_esq6m_sup_e1',
        'fecha_esq6m_sup_e2',
        'num_esq6m_trat',
        'fecha_esq6m_trat_e1',
        'fecha_esq6m_trat_e2',
        'fecha_esq6m_trat_e3',
        'num_esq6m_multi',
        'fecha_esq6m_multi_e1',
        'fecha_esq6m_multi_e2',
        'fecha_esq6m_multi_e3',
        'fecha_esq6m_multi_e4',
        'fecha_esq6m_multi_e5',
        'fecha_esq6m_multi_e6',
        'num_hb',
        'fecha_hb',
        'num_dniemision',
        'fecha_dniemision',
        'num_dniemision_30d',
        'num_dniemision_60d',        
        'cod_unico',
        'eess',
        'departamento',
        'provincia',
        'distrito',
        'establecimiento_id',
        'provincia_id',
        'distrito_id',
    ];
}
