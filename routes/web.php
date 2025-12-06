<?php

use Illuminate\Support\Facades\Route;

Route::get(
  '/keep-alive',
  function () {
    return "";
  }
);
