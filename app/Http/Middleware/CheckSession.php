<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // Guardar la URL actual para redirigir después del login
            if ($request->isMethod('get')) {
                $request->session()->put('url.intended', $request->url());
            }

            // Redirigir al login con mensaje
            return redirect()->route('login')
                ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
        }

        // Verificar si la sesión está activa
        if ($request->session()->has('last_activity')) {
            $last_activity = $request->session()->get('last_activity');
            $session_lifetime = config('session.lifetime') * 60; // en segundos

            if (time() - $last_activity > $session_lifetime) {
                // Cerrar sesión y redirigir
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha caducado por inactividad.');
            }
        }

        // Actualizar el timestamp de última actividad
        $request->session()->put('last_activity', time());
        return $next($request);
    }
}
