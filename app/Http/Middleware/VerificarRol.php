<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return $request->expectsJson() ? response()->json(['error' => 'No autenticado'], 401) : redirect()->route('login');
        }

        // Extraemos el rol del usuario (1: Admin, 2: Empleado, 3: Cliente)
        $userRoleId = (string) auth()->user()->role_id;

        if (!in_array($userRoleId, $roles)) {
            return $request->expectsJson() 
                ? response()->json(['error' => 'Acceso denegado. Permisos insuficientes.'], 403)
                : abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
