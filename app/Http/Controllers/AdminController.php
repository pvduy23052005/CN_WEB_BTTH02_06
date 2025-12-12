<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
  // [get] /admin/dashboard . 
  public function index()
  {
    // 1. Thống kê số lượng
    $totalStudents = User::where('role', 0)->count();      // Số học viên
    $totalInstructors = User::where('role', 1)->count();   // Số giảng viên
    $totalCourses = Course::count();                       // Tổng khóa học
    $pendingCoursesCount = Course::where('is_active', 0)->count(); // Khóa chờ duyệt

    // 2. Lấy danh sách 5 khóa học mới nhất đang chờ duyệt (để Admin xử lý nhanh)
    $pendingCourses = Course::where('is_active', 0)
      ->with('instructor') // Load thông tin giảng viên
      ->orderBy('id', 'desc')
      ->take(5)
      ->get();

    return view('admin.dashboard', [
      'title' => 'Tổng quan hệ thống',
      'totalStudents' => $totalStudents,
      'totalInstructors' => $totalInstructors,
      'totalCourses' => $totalCourses,
      'pendingCoursesCount' => $pendingCoursesCount,
      'pendingCourses' => $pendingCourses
    ]);
  }
  // [get] /admin/category .
  public function category()
  {

    $categories = Category::where("deleted", 0)->get();

    return view('admin.categories.list', [
      "title" => "Categories",
      "categories" => $categories,
    ]);
  }

  // [get] /admin/category/create
  public function create(Request  $request)
  {
    return view('admin.categories.create', [
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
  public function edit($id)
  {
    $category = Category::findOrFail($id);
    return view("admin.categories.edit", [
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
  public function listUsers()
  {
    $users = User::all();

    return view("admin.users.manage", [
      "title" => "List users",
      "users" => $users,
    ]);
  }

  // [get] /admin/courses
  public function listCourses()
  {
    $courses = Course::where("is_deleted" , 0 )
      ->orderBy("id" , "desc")
      ->get();
    return view("admin.courses.index", [
      "title" => "List courses",
      "courses" => $courses,
    ]);
  }

  public function approveCourse($id)
  {
    $course = Course::findOrFail($id);
    $course->is_active = 1;
    $course->save();

    return redirect()->route('admin.courses')->with('success', 'Course approved successfully.');
  }
}
