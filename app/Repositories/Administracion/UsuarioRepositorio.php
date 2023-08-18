<?php

namespace App\Repositories\Administracion;

use App\Models\Administracion\Usuario;
use App\Models\Administracion\UsuarioSistema;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Group;

class UsuarioRepositorio
{
    public static function Listar_Usuarios()
    {
        $data = Usuario::select('adm_usuario.id', 'adm_usuario.usuario', 'adm_usuario.email')
            ->join('adm_usuario_sistema as v2', 'v2.usuario_id', '=', 'adm_usuario.id', 'left')
            ->where("adm_usuario.estado", "=", 1)
            ->groupBy('adm_usuario.id', 'adm_usuario.usuario', 'adm_usuario.email')
            // ->join('adm_usuario', 'adm_usuario.id', '=', 'par_importacion.usuarioId_crea')
            // ->join('par_fuenteimportacion', 'par_fuenteimportacion.id', '=', 'par_importacion.fuenteImportacion_id')
            // ->where("par_fuenteimportacion.sistema_id", "=", $sistema_id)
            // ->orderBy('par_importacion.id', 'desc')
            ->orderBy('adm_usuario.id','desc')
            ->get();
        return $data;
    }

    public static function Usuario($id)
    {
        $data = Usuario::select('adm_usuario.id', 'adm_usuario.usuario', 'adm_usuario.dni', 'adm_usuario.password')
                ->where("adm_usuario.id", "=", $id)

            ->get();
        return $data;
    }

    public static function Listar_porperfil($perfil_id)
    {
        $data = DB::table(DB::raw('(select distinct v1.* from adm_usuario as v1
        left join adm_usuario_perfil as v2 on v2.usuario_id=v1.id
        left join adm_perfil as v3 on v3.id=v2.perfil_id
        where v3.sistema_id in (select sistema_id from adm_perfil_admin_sistema where perfil_id='.$perfil_id.') or v3.sistema_id is null
        order by v1.id desc) as usuario'))->get();// v1.estado=1 and
        return $data;
    }

}
