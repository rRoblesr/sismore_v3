<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Usuario;
use App\Models\Administracion\UsuarioAuditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioAuditoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reporte()
    {
        $data = UsuarioAuditoria::orderBy('id', 'desc')->get();
        foreach ($data as $key => $value) {
            $usu = Usuario::find($value->usuario_responsable);
            $value->usuario_responsable_nombre = $usu->nombre . ' ' . $usu->apellido1 . ' ' . $usu->apellido2;
            $usu2 = Usuario::find($value->usuario_id);

            if ($usu2) {
                // Si el usuario aún existe, usa sus datos actuales
                $value->usuario_id_nombre = $usu2->nombre . ' ' . $usu2->apellido1 . ' ' . $usu2->apellido2;
            } else {
                // 🟢 Si el usuario fue eliminado, recuperar datos de 'datos_anteriores' sin usar json_decode()
                $datos_anteriores = is_array($value->datos_anteriores) ? $value->datos_anteriores : json_decode($value->datos_anteriores, true);
    
                if ($datos_anteriores && isset($datos_anteriores['nombre'])) {
                    $value->usuario_id_nombre = $datos_anteriores['nombre'] . ' ' . ($datos_anteriores['apellido1'] ?? '') . ' ' . ($datos_anteriores['apellido2'] ?? '');
                } else {
                    $value->usuario_id_nombre = 'NO EXISTE USUARIO';
                }
            }
        }
        return view('administracion.UsuarioAuditoria.Reporte', compact('data'));
    }

    public function ajax_edit($id)
    {
        $data = UsuarioAuditoria::find($id);

        return response()->json($data);
    }
}
