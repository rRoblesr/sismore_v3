<?php

namespace App\Http\Middleware;

use App\Models\Administracion\Menu;
use App\Models\Administracion\Menuperfil;
use App\Models\Administracion\Perfil;
use App\Models\Administracion\Sistema;
use App\Models\Administracion\UsuarioPerfil;
use App\Repositories\Administracion\EntidadRepositorio;
use App\Repositories\Administracion\MenuRepositorio;
use App\Repositories\Educacion\ImportacionRepositorio;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MenuAccess
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Verificar Autenticación e Inicializar Sesión Base
        if (Auth::check()) {
            $this->inicializarSesionUsuario();
        } else {
            // Si no está logueado, dejar pasar (probablemente irá al login o será rebotado por otro middleware 'auth')
            return $next($request);
        }

        $route = $request->route();
        if (!$route) return $next($request);

        $routeName = $route->getName();
        if (!$routeName) return $next($request);

        // Rutas exentas de verificación de menú
        if ($routeName === 'sistema_acceder') return $next($request);

        // 2. Identificar el Menú solicitado
        $menu = null;
        $params = $route->parameters();

        // Caso Especial: Menús de PowerBI por ID
        if (isset($params['id']) && Str::startsWith($routeName, 'powerbi.') && Str::endsWith($routeName, '.menu')) {
            $menu = Menu::find($params['id']);
            if (!$menu || $menu->estado != 1) abort(403, 'Menú PowerBI no válido o inactivo.');
        } 
        // Caso General: Menús por Nombre de Ruta
        else {
            $menu = Menu::where('url', $routeName)->where('estado', 1)->first();
        }

        // 3. Validación de Acceso
        if ($menu) {
            // A) Si es un menú registrado, verificar estrictamente permiso en base de datos
            if (!$this->usuarioTienePermisoMenu(Auth::id(), $menu->id)) {
                // Log::warning("Acceso denegado: Usuario " . Auth::id() . " intentó acceder al menú ID " . $menu->id . " (" . $routeName . ") sin permiso en adm_menu_perfil.");
                abort(403, 'Acceso denegado. No tiene permisos para este menú.');
            }

            // B) Gestión de Contexto/Sesión (Sin cambios forzosos si ya existe sesión)
            $this->gestionarContextoSesion($menu->sistema_id);
            
            return $next($request);

        } else {
            // 4. Si NO es un menú registrado (Rutas internas, AJAX, detalles, etc.)
            // Verificar si pertenece a módulos protegidos
            $modulePrefixes = ['salud.', 'educacion.', 'vivienda.', 'trabajo.', 'presupuesto.', 'administrador.'];
            
            $esRutaProtegida = false;
            foreach ($modulePrefixes as $prefix) {
                if (Str::startsWith($routeName, $prefix)) {
                    $esRutaProtegida = true;
                    break;
                }
            }

            if ($esRutaProtegida) {
                // Lista blanca de acciones internas permitidas sin menú explícito
                $internalAllowed = ['reporte', 'reportes', 'listar', 'listarDT', 'download', 'ajax', 'cargar', 'tabla', 'grafico', 'grafica', 'exportar', 'detalle', 'contenido', 'select', 'buscar', 'guardar', 'editar', 'eliminar', 'update', 'store', 'create'];
                
                if (!Str::contains($routeName, $internalAllowed)) {
                    // Opcional: Podríamos ser estrictos aquí, pero por ahora logueamos y dejamos pasar si es ambiguo, 
                    // o abortamos si queremos seguridad estricta. Siguiendo la lógica anterior:
                    // abort(403, 'Ruta protegida no registrada en menús y no permitida internamente.');
                    // Para evitar romper cosas existentes, asumimos que si llegó aquí y es "educacion.algo", 
                    // verificamos si el usuario tiene ALGUN perfil con acceso al sistema "Educación".
                    
                    if (!$this->usuarioTieneAccesoAlSistemaDelPrefijo($routeName)) {
                         abort(403, 'No tiene acceso al sistema correspondiente a esta ruta.');
                    }
                }
            }
            
            return $next($request);
        }
    }

    private function usuarioTienePermisoMenu($userId, $menuId)
    {
        // Verifica en adm_usuario_perfil JOIN adm_menu_perfil
        return UsuarioPerfil::join('adm_menu_perfil', 'adm_menu_perfil.perfil_id', '=', 'adm_usuario_perfil.perfil_id')
            ->where('adm_usuario_perfil.usuario_id', $userId)
            ->where('adm_menu_perfil.menu_id', $menuId)
            ->exists();
    }

    private function usuarioTieneAccesoAlSistemaDelPrefijo($routeName)
    {
        // Mapeo simple para validación de rutas no-menú (fallback de seguridad)
        $prefixSistemaMap = [
            'educacion.' => 1,
            'vivienda.' => 2,
            'salud.' => 3,
            'administrador.' => 4,
            'presupuesto.' => 5,
            'trabajo.' => 6,
        ];

        $targetSistemaId = null;
        foreach ($prefixSistemaMap as $prefix => $sid) {
            if (Str::startsWith($routeName, $prefix)) {
                $targetSistemaId = $sid;
                break;
            }
        }

        if (!$targetSistemaId) return true; // Si no es de un sistema conocido, dejar pasar (o manejar según política)

        $userId = Auth::id();
        
        // Verificar si el usuario tiene algún perfil asociado a este sistema
        // Ya sea directamente o a través de menús asignados de ese sistema
        
        // Opción 1: Tiene un perfil que "Administra" ese sistema (adm_perfil_admin_sistema)
        $tienePerfilAdmin = UsuarioPerfil::join('adm_perfil_admin_sistema', 'adm_perfil_admin_sistema.perfil_id', '=', 'adm_usuario_perfil.perfil_id')
            ->where('adm_usuario_perfil.usuario_id', $userId)
            ->where('adm_perfil_admin_sistema.sistema_id', $targetSistemaId)
            ->exists();

        if ($tienePerfilAdmin) return true;

        // Opción 2: Tiene al menos un menú de ese sistema asignado (Lógica de acceso cruzado)
        $tieneMenuDelSistema = UsuarioPerfil::join('adm_menu_perfil', 'adm_menu_perfil.perfil_id', '=', 'adm_usuario_perfil.perfil_id')
            ->join('adm_menu', 'adm_menu.id', '=', 'adm_menu_perfil.menu_id')
            ->where('adm_usuario_perfil.usuario_id', $userId)
            ->where('adm_menu.sistema_id', $targetSistemaId)
            ->exists();
            
        return $tieneMenuDelSistema;
    }

    private function gestionarContextoSesion($targetSistemaId)
    {
        // Solo inicializar sesión del sistema si NO existe ninguna sesión activa.
        // Esto preserva el contexto actual (ej. Salud) cuando se visita un reporte de otro módulo (ej. Educación).
        if (!session()->has('sistema_id')) {
            $sistema = Sistema::find($targetSistemaId);
            if ($sistema) {
                session(['sistema_id' => $sistema->id]);
                session(['sistema_nombre' => $sistema->nombre]);
                
                // Cargar datos adicionales necesarios para la vista (Sidebar, etc.)
                $userId = Auth::id();
                
                // Intentar encontrar el perfil más adecuado para este sistema
                $perfilUsuario = UsuarioPerfil::join('adm_menu_perfil', 'adm_menu_perfil.perfil_id', '=', 'adm_usuario_perfil.perfil_id')
                    ->join('adm_menu', 'adm_menu.id', '=', 'adm_menu_perfil.menu_id')
                    ->where('adm_usuario_perfil.usuario_id', $userId)
                    ->where('adm_menu.sistema_id', $targetSistemaId)
                    ->select('adm_usuario_perfil.perfil_id')
                    ->first();
                    
                if ($perfilUsuario) {
                    $perfil = Perfil::find($perfilUsuario->perfil_id);
                    if ($perfil) {
                        session(['perfil_sistema_id' => $perfil->id]);
                        session(['perfil_sistema_nombre' => $perfil->nombre]);
                    }
                }

                // Cargar Menús
                $menuNivel01 = MenuRepositorio::Listar_Nivel01_porUsuario_Sistema($userId, $targetSistemaId);
                session(['menuNivel01' => $menuNivel01]);
                $menuNivel02 = MenuRepositorio::Listar_Nivel02_porUsuario_Sistema($userId, $targetSistemaId);
                session(['menuNivel02' => $menuNivel02]);
                $menuNivel03 = MenuRepositorio::Listar_Nivel03_porUsuario_Sistema($userId, $targetSistemaId);
                session(['menuNivel03' => $menuNivel03]);

                // Notificaciones (Importaciones)
                $nimp = ImportacionRepositorio::noti_importaciones($targetSistemaId, date('Y'));
                session(['nimp' => $nimp]);
                session(['ncon' => $nimp->count()]);
            }
        }
    }

    private function inicializarSesionUsuario()
    {
        if (!session()->has('usuario_id')) {
            session()->put(['usuario_id' => Auth::id()]);
        }
        if (!session()->has('entidad_nombre') && Auth::user()->entidad) {
            $oficina = EntidadRepositorio::migas(Auth::user()->entidad);
            if ($oficina) {
                session()->put(['entidad_nombre' => $oficina->entidadn]);
                session()->put(['usuario_sector' => $oficina->sector]);
                session()->put(['usuario_nivel' => $oficina->tipo]);
                if ($oficina->sector == 14 && $oficina->tipo == 4) {
                    session()->put(['usuario_codigo_institucion' => '0' . $oficina->codigo]);
                } else {
                    session()->put(['usuario_codigo_institucion' => $oficina->codigo]);
                }
                session()->put(['usuario_codigo' => (int)$oficina->codigo]);
            }
        }
    }
}
