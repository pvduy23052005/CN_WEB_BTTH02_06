<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as Controller;

// [get] /student
Route::get(
  '/',
  [Controller::class, "index"]
);
