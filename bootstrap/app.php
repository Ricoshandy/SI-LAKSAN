<?php

use App\Http\Middleware\ComiteMiddleware;
use App\Http\Middleware\DosenMiddleware;
use App\Http\Middleware\GuestMiddleware;
use App\Http\Middleware\KepegawaianMiddleware;
use App\Http\Middleware\SenatMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('web')
                ->group(glob(base_path('routes/web/*.php')));
            Route::middleware('web')
                ->group(glob(base_path('routes/web.php')));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'superadmin' => SuperAdminMiddleware::class,
            'dosen' => DosenMiddleware::class,
            'kepegawaian' => KepegawaianMiddleware::class,
            'comite' => ComiteMiddleware::class,
            'senat' => SenatMiddleware::class,
            'guest' => GuestMiddleware::class,
             'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
