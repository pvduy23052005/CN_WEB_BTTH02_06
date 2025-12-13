<?php

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
   * @param  mixed ...$roles  Nhận nhiều role (0, 1, 2)
   */
  public function handle(Request $request, Closure $next, ...$roles): Response
  {
    // 1. Kiểm tra đã đăng nhập chưa
    if (!Auth::check()) {
      return redirect('/auth/login')->with('error', 'Vui lòng đăng nhập.');
    }

    // 2. Lấy user hiện tại
    $user = Auth::user();

    // Vì từ route truyền vào là string: 'role:0,1,2'
    $allowedRoles = array_map('intval', $roles);

    // 4. Kiểm tra user có role phù hợp không
    if (in_array($user->role, $allowedRoles)) {
      return $next($request); // Cho phép truy cập
    }

    // 5. Nếu không đủ quyền
    abort(403, 'Bạn không có quyền truy cập trang này.');
  }
}
