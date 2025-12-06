<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
  // [get] /admin/dashboard . 
  public function index(Request $request, Response $response)
  {
    return view('admin.dashboard', [
      "title" => "Dashboard admin"
    ]);
  }
}
