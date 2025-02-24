<?php

use App\Http\Middleware\EnsureEmailIsNOTVerified;
use App\Http\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verified' => EnsureEmailIsVerified::class,
            'not.verified' => EnsureEmailIsNOTVerified::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $notFoundHttpException) {
            return send_response(
                false,
                [],
                $notFoundHttpException->getMessage(),
                $notFoundHttpException->getStatusCode()
            );
        });

        $exceptions->render(function (AuthenticationException $authenticationException) {
            return send_response(
                false,
                [],
                $authenticationException->getMessage(),
                401
            );
        });

        $exceptions->render(function (MethodNotAllowedHttpException $methodNotAllowedHttpException) {
            return send_response(
                false,
                [],
                $methodNotAllowedHttpException->getMessage(),
                405
            );
        });
    })->create();
