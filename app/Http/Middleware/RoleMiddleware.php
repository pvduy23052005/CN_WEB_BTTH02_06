<?php

// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response // <-- Nhận tham số $role
    {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect('/auth/login'); // Chuyển về trang đăng nhập nếu chưa
        }

        $user = Auth::user();

        // 2. Kiểm tra vai trò (role)
        // Nếu role của người dùng KHÔNG khớp với role yêu cầu
        if ($user->role != $role) {
            // Có thể chuyển hướng đến trang lỗi 403 hoặc trang chủ
            return redirect('/')->with('error', 'Bạn không có quyền truy cập chức năng này.');
        }

        // Nếu khớp role, tiếp tục xử lý request
        return $next($request);
    }
}