<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;

// ----------------------------------------------------
// 1. NHÓM CÔNG KHAI (KHÔNG cần đăng nhập để test)
// ----------------------------------------------------
// Mọi người đều có thể xem danh sách và chi tiết khóa học



// ----------------------------------------------------
// 2. NHÓM BẢO MẬT (YÊU CẦU ĐĂNG NHẬP)
// ----------------------------------------------------
// Chỉ Học viên (Role 0) đã Đăng nhập mới truy cập được các chức năng cá nhân
// app/routes/web.php (Hoặc file routes tương ứng)

// ...
// 2. NHÓM BẢO MẬT (YÊU CẦU ĐĂNG NHẬP VÀ ROLE:0)
Route::middleware(['auth', 'role:0'])->group(function () {

    // Danh sách Khóa học (Trang chủ sau khi đăng nhập)
    Route::get('/', [CourseController::class, 'listCourses'])->name('courses.index'); 

    // Chi tiết Khóa học
    Route::get('/courses/{id}', [CourseController::class, 'showDetail'])->name('courses.detail'); 

    // Khóa học của tôi (Dashboard Học viên)
    Route::get('/my_courses', [CourseController::class, 'showMyCourses'])->name('home'); 

    // Đăng ký Khóa học
    Route::post('/enroll/{courseId}', [EnrollmentController::class, 'enroll'])->name('enroll');

    // Theo dõi Tiến độ, Xem Bài học, Đánh dấu Hoàn thành, v.v.
    Route::get('/progress/{courseId}', [EnrollmentController::class, 'showProgress'])->name('progress');
    Route::get('/learn/{lessonId}', [LessonController::class, 'showLesson'])->name('learn');
    Route::post('/lesson/complete/{lessonId}', [EnrollmentController::class, 'markLessonCompleted'])->name('lesson.complete');
});