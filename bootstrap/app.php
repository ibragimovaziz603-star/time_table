<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'    => \App\Http\Middleware\IsAdmin::class,
            'teacher'  => \App\Http\Middleware\IsTeacher::class,
            'starosta' => \App\Http\Middleware\IsStarosta::class,
        ]);

        // Web guruhga TrackVisit qo'shish
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();