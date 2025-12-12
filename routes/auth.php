

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// [get] /auth/login
Route::get(
  '/login',
  [AuthController::class, "login"]
);

// [post] /auth/login
Route::post(
  "/login" ,
  [AuthController::class , "loginPost"]
);

// [get] /auth/register
Route::get(
  '/register',
  [AuthController::class, "register"]
);

// [post] /auth/logout
Route::post(
    '/logout',
    [AuthController::class, "logout"] 
)->name('logout');