<?php



use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


// Bổ sung middleware 'role:0'
Route::middleware(['auth', 'role:0'])->prefix('student')->group(base_path('routes/student.php'));

// Định nghĩa nhóm route cho Giảng viên (Instructor - Role 1)
// Bổ sung middleware 'role:1'
Route::middleware(['auth', 'role:1'])->prefix('instructor')->group(base_path('routes/instructor.php')); 

// Định nghĩa nhóm route cho Quản trị viên (Admin - Role 2)
// Bổ sung middleware 'role:2'
Route::middleware(['auth', 'role:2'])->prefix('admin')->group(base_path('routes/admin.php'));