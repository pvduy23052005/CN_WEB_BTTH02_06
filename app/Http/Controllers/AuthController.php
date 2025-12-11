<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- CẦN THÊM DÒNG NÀY ĐỂ DÙNG Auth::attempt

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
        // 1. Validate dữ liệu
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // XÓA LỆNH DD NÀY ĐI
        // dd($request->all()); 
        
        // 2. TẠO THÔNG TIN XÁC THỰC
        $credentials = $request->only('email', 'password');

        // 3. XÁC THỰC BẰNG HASHING CHUẨN CỦA LARAVEL
        if (Auth::attempt($credentials)) {
            
            // XÁC THỰC THÀNH CÔNG
            $user = Auth::user();

            // CHUYỂN HƯỚNG DỰA TRÊN ROLE
            if ($user->role == 0) {
                // Học viên: Chuyển hướng đến Dashboard/Home học viên
              return redirect()->route('student.home')->with('success', 'Đăng nhập thành công!');            }

            if ($user->role == 1) {
                // Giảng viên: Chuyển hướng đến Dashboard giảng viên (admin/dashboard)
                return redirect('/admin/dashboard')->with('success', 'Đăng nhập thành công!');
            }
            
            // Trường hợp lỗi role:
            return redirect('/')->with('error', 'Đăng nhập thành công, nhưng không có quyền truy cập.');

        } 
        
        // XÁC THỰC THẤT BẠI
        return back()->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    // [get] /auth/register
    public function register()
    {
        return view("auth.register");
    }
}