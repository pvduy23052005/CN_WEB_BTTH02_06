<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  // [get] /auth/login
  public function login()
  {
    return view("auth.login");
  }

  // [post] /auth/login
  public function loginPost(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:6'
    ],);

    dd($request->all());

    $user = User::where('email', $request->email)
      ->where('password', $request->password)
      ->first();

    echo $user;

    if ($user) {
      session(['user_id' => $user->id]);
      session(['user_email' => $user->email]);
      session(['user_name' => $user->fulname]);
      return redirect('/admin/dashboard')->with('success', 'Đăng nhập thành công!');
    }

    return redirect("/auth/login");
  }

  // [get] /auth/register
  public function register()
  {
    return view("auth.register");
  }
}
