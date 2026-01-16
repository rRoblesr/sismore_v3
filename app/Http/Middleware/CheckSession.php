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
        $route = $request->route();
        $middlewares = $route && method_exists($route, 'middleware') ? $route->middleware() : [];
        $requiresAuth = in_array('auth', $middlewares, true);

        if ($requiresAuth) {
            if (!Auth::check()) {
                if ($request->isMethod('get')) {
                    $request->session()->put('url.intended', $request->url());
                }
                return redirect()->route('login')
                    ->with('error', 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
            }

            if ($request->session()->has('last_activity')) {
                $last_activity = $request->session()->get('last_activity');
                $session_lifetime = config('session.lifetime') * 60;

                if (time() - $last_activity > $session_lifetime) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('error', 'Tu sesión ha caducado por inactividad.');
                }
            }
            $request->session()->put('last_activity', time());
        }

        return $next($request);
    }
}
