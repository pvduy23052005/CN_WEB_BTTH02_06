<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
  // [get] /auth/login
  public function login()
  {
    return view("auth.login");
  }

  // [post] /auth/login
  public function loginPost1(Request $request)
  {
    // 1. Validate dữ liệu
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:6'
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && $user->password === $request->password) {

      Auth::login($user);

      if ($user->role == 0) {
        // Học viên: Chuyển hướng đến Dashboard/Home học viên
        return redirect()->route('student.courses.index')->with('success', 'Đăng nhập thành công!');
      }

      if ($user->role == 1) {
        return redirect('instructor/courses')->with('success', 'Đăng nhập thành công!');
      }

      if ($user->role == 2) {
        return redirect('/admin/dashboard')->with('success', 'Đăng nhập thành công!');
      }

      return redirect('/')->with('error', 'Đăng nhập thành công, nhưng không có quyền truy cập.');
    }

    return back()->with('error', 'Email hoặc mật khẩu không chính xác.');
  }

  // [get] /auth/register
  public function register()
  {
    return view("auth.register");
  }


  public function registerPost(Request $req)
  {
    if (User::where('email', $req->email)->exists()) {
      return back()->withErrors(['email' => 'Email đã được sử dụng!'])->withInput();
    }

    if (User::where('username', $req->username)->exists()) {
      return back()->withErrors(['username' => 'Tên đăng nhập đã tồn tại!'])->withInput();
    }


    // Validate các trường khác
    $validated = $req->validate([
      'fullname' => 'required|string|max:255',
      'email' => 'required|email',
      'username' => 'required|string|min:3',
      'password' => 'required|min:6|confirmed',
      'role' => 'required|in:0,1,2',
    ]);

    // Tạo user
    User::create([
      'fullname' => $validated['fullname'],
      'email' => $validated['email'],
      'username' => $validated['username'],
      'password' => $validated['password'],
      'role' => $validated['role'],
    ]);

    return redirect('/auth/login')->with('success', 'Đăng ký thành công!');
  }


  public function logout(Request $request)
  {
    // Đăng xuất người dùng hiện tại
    Auth::logout();

    // Hủy session của người dùng
    $request->session()->invalidate();

    // Tái tạo token CSRF mới
    $request->session()->regenerateToken();

    // Chuyển hướng người dùng về trang đăng nhập hoặc trang chủ
    return redirect('/auth/login')->with('success', 'Bạn đã đăng xuất thành công.');
  }
}
