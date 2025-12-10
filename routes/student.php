

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
// Bạn có thể giữ hoặc bỏ HomeController tùy theo nhu cầu sử dụng route khác

// Đặt route cho trang Tiến độ khóa học
Route::get(
    '/my_courses',
    [CourseController::class, "showMyCourses"]
);