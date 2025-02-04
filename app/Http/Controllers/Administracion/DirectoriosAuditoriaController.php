<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\DirectoriosAuditoria;
use App\Models\Administracion\Usuario;
use App\Models\Administracion\UsuarioAuditoria;
use App\Models\Salud\DirectorioMunicipal;
use App\Models\Salud\DirectorioPN;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DirectoriosAuditoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function reportemunicipios()
    {
        $data = DirectoriosAuditoria::where('tipo', 'MUNICIPIOS')->orderBy('id', 'desc')->get();
        foreach ($data as $key => $value) {
            $usu = Usuario::find($value->usuario_responsable);
            $value->usuario_responsable_nombre = $usu->nombre . ' ' . $usu->apellido1 . ' ' . $usu->apellido2;
            $usu2 = DirectorioMunicipal::find($value->responsable_id);

            if ($usu2) {
                // Si el usuario aún existe, usa sus datos actuales
                $value->usuario_id_nombre = $usu2->nombres . ' ' . $usu2->apellido_paterno . ' ' . $usu2->apellido_materno;
            } else {
                // 🟢 Si el usuario fue eliminado, recuperar datos de 'datos_anteriores' sin usar json_decode()
                $datos_anteriores = is_array($value->datos_anteriores) ? $value->datos_anteriores : json_decode($value->datos_anteriores, true);

                if ($datos_anteriores && isset($datos_anteriores['nombres'])) {
                    $value->usuario_id_nombre = $datos_anteriores['nombres'] . ' ' . ($datos_anteriores['apellido_paterno'] ?? '') . ' ' . ($datos_anteriores['apellido_materno'] ?? '');
                } else {
                    $value->usuario_id_nombre = 'NO EXISTE USUARIO';
                }
            }
        }
        return view('administracion.DirectoriosAuditoria.ReporteMunicipios', compact('data'));
    }

    public function reportepadronnominal()
    {
        $data = DirectoriosAuditoria::where('tipo', 'PADRON_NOMINAL')->orderBy('id', 'desc')->get();
        foreach ($data as $key => $value) {
            $usu = Usuario::find($value->usuario_responsable);
            $value->usuario_responsable_nombre = $usu->nombre . ' ' . $usu->apellido1 . ' ' . $usu->apellido2;
            $usu2 = DirectorioPN::find($value->responsable_id);

            if ($usu2) {
                // Si el usuario aún existe, usa sus datos actuales
                $value->usuario_id_nombre = $usu2->nombres . ' ' . $usu2->apellido_paterno . ' ' . $usu2->apellido_materno;
            } else {
                // 🟢 Si el usuario fue eliminado, recuperar datos de 'datos_anteriores' sin usar json_decode()
                $datos_anteriores = is_array($value->datos_anteriores) ? $value->datos_anteriores : json_decode($value->datos_anteriores, true);

                if ($datos_anteriores && isset($datos_anteriores['nombres'])) {
                    $value->usuario_id_nombre = $datos_anteriores['nombres'] . ' ' . ($datos_anteriores['apellido_paterno'] ?? '') . ' ' . ($datos_anteriores['apellido_materno'] ?? '');
                } else {
                    $value->usuario_id_nombre = 'NO EXISTE USUARIO';
                }
            }
        }
        return view('administracion.DirectoriosAuditoria.ReportePN', compact('data'));
    }


    public function ajax_edit($id)
    {
        $data = DirectoriosAuditoria::find($id);

        return response()->json($data);
    }
}
