<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
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

  // [get] /admin/category/create
  public function create(Request  $request  ){
    return view('admin.categories.create' , [
      "title" => "Create category",
    ]);
  }

  // [post] /admin/category/create
  public function createPost(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    Category::create([
      'name' => $validated['name'],
      'description' => $validated['description'] ?? null,
      'created_at' => now(),
      'deleted' => 0,
    ]);

    return redirect('/admin/category');
  }

  // [get] /admin/category/edit/:id
  public function edit($id){
    $category = Category::findOrFail($id);
    return view("admin.categories.edit" ,[
      "title" => "Edit Category",
      "category" => $category,
    ]);
  }

  // [post] /admin/category/edit/:id
  public function editPost(Request $request, $id)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    $category = Category::findOrFail($id);
    $category->update([
      'name' => $validated['name'],
      'description' => $validated['description'] ?? null,
    ]);

    return redirect('/admin/category')->with('success', 'Category updated successfully.');
  }

  // [get] /admin/users
  public function listUsers(){
    $users = User::all();

    return view("admin.users.manage" , [
      "title" => "List users" , 
      "users" => $users,
    ]);
  }
}
