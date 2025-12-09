<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController as Controller;

// [get] /admin/dashboard
Route::get(
  '/dashboard',
  [Controller::class, "index"]
);

// [get] /admin/category
Route::get('/category' , [Controller::class, "category"]);
