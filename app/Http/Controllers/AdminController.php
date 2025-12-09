<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
  // [get] /admin/dashboard . 
  public function index(Request $request, Response $response)
  {
    return view('admin.dashboard', [
      "title" => "Dashboard admin",
    ]);
  }

  // [get] /admin/category .
  public function category(){

    $categories = Category::where("deleted" , 0)->get();
    

    return view('admin.categories.list' , [
      "title" => "Categories",
      "categories" => $categories,
    ]);
  }
}
