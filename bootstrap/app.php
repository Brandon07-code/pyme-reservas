<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Redirigir a los usuarios autenticados que intenten acceder a rutas de invitados
        $middleware->redirectUsersTo(fn (Request $request) => '/');

        // Registro del middleware de rol
        $middleware->alias([
            'rol' => \App\Http\Middleware\VerificarRol::class,
            'admin' => \App\Http\Middleware\VerificarRol::class,
        ]);
    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Manejo global de excepciones JSON para la API (Regla Módulo IV)
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->is('cron/*')
        );

        // Captura global para evitar respuestas JSON gigantes (Whoops debug) en el cron job
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/cron/*') || $request->is('cron/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => substr($e->getMessage(), 0, 500),
                    'note' => 'Posible timeout de base de datos o spin-up de Render'
                ], 200); // Retornamos 200 para que cron-job.org no lo marque como fallo y lo deshabilite
            }
        });

        // Captura de errores 404 (ModelNotFound) para devolver JSON limpio en la API
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'El recurso solicitado no existe o fue eliminado.'
                ], 404);
            }
        });

    })->create();