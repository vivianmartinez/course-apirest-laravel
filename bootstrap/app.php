<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //Definir los middlewares de Laravel Permission para validar 
        $middleware->alias([ 
            'role' => RoleMiddleware::class, 
            'permission' => PermissionMiddleware::class, 
            'role_or_permission' => RoleOrPermissionMiddleware::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Mensaje personalizado para NotFoundHttpException
        $exceptions->render(function (NotFoundHttpException $e, $request) { 
             // Recuperar la excepción original
            $previous = $e->getPrevious();
            if ($previous instanceof ModelNotFoundException) {
                // Obtener el modelo sin el namespace
                $model = class_basename($previous->getModel());
                return response()->json(['message' => "El recurso solicitado de {$model} no existe."], 404);
            }

            return response()->json([ 'message' => "El recurso solicitado no existe." ], 404); 
        });
        // Mensaje personalizado para MethodNotAllowedHttpException
        $exceptions->render(function(MethodNotAllowedHttpException $e, $request){
            return response()->json([ 'message' => $e->getMessage() ], 405); 
        });

        $exceptions->render(function(AccessDeniedHttpException $e, $request){
            return response()->json([ 'message' => 'Acción no autorizada.' ], 403); 
        });

        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([ 'message' => 'No tienes la autorización requerida para realizar esta solicitud.'],403);
        });

    })->create();
