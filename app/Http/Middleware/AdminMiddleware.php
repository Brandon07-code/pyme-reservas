<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica que esté logueado y que su role_id sea 1 (Administrador)
        if (auth()->check() && auth()->user()->role_id === 1) {
            return $next($request);
        }

        // Si es un empleado intentando entrar donde no debe, lanza un error 403 (Acceso Denegado)
        abort(403, 'Acceso denegado. Solo los administradores pueden entrar a este módulo.');
    }
}