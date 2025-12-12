<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Prefix: /auth, Name: auth.*
// Không cần middleware auth vì đây là trang đăng nhập/đăng ký

// /auth/login -> auth.login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost1'])->name('login.post');

// /auth/register -> auth.register
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

// /auth/logout -> auth.logout (cần đăng nhập)
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
