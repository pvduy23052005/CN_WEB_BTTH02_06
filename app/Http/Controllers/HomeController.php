<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
  // [get] /student.
  public function index(Request $request)
  {
    return view('students.dashboard', [
      "title" => "Dashboard student"
    ]);
  }
}
