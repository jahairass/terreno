<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // La verificación la hacemos directamente en el modelo User
        if (!$user->hasPermissionTo($module, $action)) {
            // No tiene permiso, redirigir al dashboard con un error
            return redirect()->route('dashboard')->with('error', "No tienes permisos de {$action} para el módulo de {$module}.");
        }

        return $next($request);
    }
}