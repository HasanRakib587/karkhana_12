<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\VerifySslCommerz;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ValidateSignature;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('signed',[
            VerifySslCommerz::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'sslcommerz/success',
            'sslcommerz/fail',
            'sslcommerz/cancel',
            'sslcommerz/ipn',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
