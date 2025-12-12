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
      Route::middleware('web')
        ->prefix('admin')
        ->name('admin.')
        ->group(base_path('routes/admin.php'));

      // /student
      Route::middleware('web')
        ->prefix('student')
        ->name('student.')
        ->group(base_path('routes/student.php'));

      // /instructor
      Route::middleware('web')
        ->prefix('instructor')
        ->name('instructor.')
        ->group(base_path('routes/instructor.php'));

      // /auth
      Route::middleware('web')
        ->prefix('auth')
        ->name('auth.')
        ->group(base_path('routes/auth.php'));
    }
  )
  ->withMiddleware(function (Middleware $middleware): void {
    // Đăng ký middleware alias
    $middleware->alias([
      'role' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
    $middleware->redirectGuestsTo('/auth/login');
  })
  ->withExceptions(function (Exceptions $exceptions): void {
    //
  })
  ->create();
