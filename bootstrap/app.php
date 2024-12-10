<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'headClinic' => App\Http\Middleware\HeadClinicMiddleware::class,
            'doctor' => App\Http\Middleware\DoctorMiddleware::class,
            'beutician' => App\Http\Middleware\BeauticianMiddleware::class,
            'cs' => App\Http\Middleware\CustomerServiceMiddleware::class,
            'cashier' => App\Http\Middleware\CashierMiddleware::class,
            'admin' => App\Http\Middleware\AdminMiddleware::class,
            'customer' => App\Http\Middleware\CustomerMiddleware::class,
            
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
