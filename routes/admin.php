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

// [get] /admin/category/create
Route::get('/category/create' , [Controller::class, "create"]);

// [post] /admin/category/create
Route::post('/category/create' , [Controller::class, "createPost"]);

// [get] /admin/category/edit/{id}
Route::get("/category/edit/{id}" , [Controller::class  , "edit"]);

// [post] /admin/category/edit/{id}
Route::post("/category/edit/{id}" , [Controller::class  , "editPost"]);

// [get] /admin/users
Route::get("/users" , [Controller::class  , "listUsers"]);