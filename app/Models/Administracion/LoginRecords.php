<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginRecords extends Model
{
    use HasFactory;
    protected $table = "adm_login_records";

    protected $fillable = [
        'usuario',
        'login',
        'logout'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function usuarioRel()
    {
        return $this->belongsTo(Usuario::class, 'usuario');
    }
}
