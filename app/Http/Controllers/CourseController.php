<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{

     // [get] /course 
  public function index(Request $request, Response $response)
  {
    return view('courses.index', [
      "title" => "Dashboard course",
    ]);
  }
}
