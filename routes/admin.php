<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;

use App\Http\Controllers\AuthController;

// Tất cả route trong file này tự động có prefix: /admin
// Và name prefix: admin.

Route::middleware(['auth', 'role:2'])->group(function () {

  // /admin/dashboard -> route name: admin.dashboard
  Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

  // Category routes
  Route::prefix('category')->name('category.')->group(function () {
    // /admin/category -> admin.category.index
    Route::get('/', [AdminController::class, 'category'])->name('index');

    // /admin/category/create -> admin.category.create
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/create', [AdminController::class, 'createPost'])->name('store');

    // /admin/category/edit/{id} -> admin.category.edit
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('edit');
    Route::post('/edit/{id}', [AdminController::class, 'editPost'])->name('update');
  });

  Route::get('/courses', [AdminController::class, 'listCourses'])->name('courses');

  Route::post("/course/approve/{id}", [AdminController::class, "approveCourse"])->name("course.approve");

  // /admin/report  
  Route::get("/report", [AdminController::class, "report"]);
  
  // /admin/users -> admin.users
  Route::get('/users', [AdminController::class, 'listUsers'])->name('users');
});
