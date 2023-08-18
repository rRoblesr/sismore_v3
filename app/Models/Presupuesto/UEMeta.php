<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UEMeta extends Model
{
    use HasFactory;
    protected $table='pres_ue_meta';
    public $timestamps = false;

    protected $fillable = [
        'unidadejecutora_id',
        'meta_id',
        'unidadorganica_id',
    ];
}
