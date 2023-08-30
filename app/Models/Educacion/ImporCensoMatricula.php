<?php

namespace App\Models\Educacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImporCensoMatricula extends Model
{
    use HasFactory;

    protected $table = "edu_impor_censomatricula";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importacion_id',
        'codooii',
        'codgeo',
        'codlocal',
        'cod_mod',
        'nroced',
        'cuadro',
        'tipdato',
        'niv_mod',
        'ges_dep',
        'area_censo',
        'tipoprog',
        'd01',
        'd02',
        'd03',
        'd04',
        'd05',
        'd06',
        'd07',
        'd08',
        'd09',
        'd10',
        'd11',
        'd12',
        'd13',
        'd14',
        'd15',
        'd16',
        'd17',
        'd18',
        'd19',
        'd20',
        'd21',
        'd22',
        'd23',
        'd24',
        'd25',
        'd26',
        'd27',
        'd28',
        'd29',
        'd30',
        'd31',
        'd32',
        'd33',
        'd34',
        'd35',
        'd36',
        'd37',
        'd38',
        'd39',
        'd40'
    ];

    protected $hide = [
        'created_at',
        'updated_at'
    ];
}
