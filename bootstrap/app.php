<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
    then: function (): void {
      // /admin
      Route::middleware('web') // Áp dụng middleware web (session, csrf...)
        ->prefix('admin')    // Tiền tố đường dẫn: /admin
        ->name('admin.')     // Tiền tố tên route: admin.
        ->group(base_path('routes/admin.php'));

      // student
      Route::middleware('web')
        ->prefix('student')
        ->name('student.')
        ->group(base_path('routes/student.php'));
    }
  )
  ->withMiddleware(function (Middleware $middleware): void {
    //
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    //
  })->create();
