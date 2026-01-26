<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Administracion\Visita;

class RegistrarVisita
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
        $response = $next($request);

        // Only track GET requests from guests (public side)
        if ($request->isMethod('get') && !Auth::check()) {
            try {
                Visita::create([
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                    'sistema_id' => session('sistema_id'),
                ]);
            } catch (\Exception $e) {
                // Silent fail to avoid disrupting the user experience
            }
        }

        return $response;
    }
}
