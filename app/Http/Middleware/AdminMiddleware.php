<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * @param  string[]  ...$roles (1=Admin, 2=Empleado, 3=Cliente)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check()) {
            $userRoleId = (string) auth()->user()->role_id;
            
            // Si el rol del usuario está dentro de los roles permitidos en la ruta, lo dejamos pasar
            if (in_array($userRoleId, $roles)) {
                return $next($request);
            }
            
            // Si es un cliente intentando entrar al panel administrativo, lo mandamos a su portal
            if ($userRoleId === '3') {
                return redirect()->route('portal.index');
            }
        }

        abort(403, 'Acceso denegado. No tienes permisos para ver este módulo.');
    }
}