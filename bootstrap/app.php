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
        
        // Registro del middleware de rol
        $middleware->alias([
            'rol' => \App\Http\Middleware\VerificarRol::class,
        ]);
    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Manejo global de excepciones JSON para la API (Regla Módulo IV)
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*')
        );

        // Captura de errores 404 (ModelNotFound) para devolver JSON limpio en la API
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'El recurso solicitado no existe o fue eliminado.'
                ], 404);
            }
        });

    })->create();