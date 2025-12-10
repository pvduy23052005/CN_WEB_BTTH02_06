<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController as Controller;

// [get] /course
Route::get(
  '/',
  [Controller::class, "index"]
);
