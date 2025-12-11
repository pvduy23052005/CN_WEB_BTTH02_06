<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Gate; // Có thể dùng Gate/Policy để bảo mật

class LessonController extends Controller
{
    // Chức năng 6: Xem bài học và tài liệu
    // Route: /student/learn/{lessonId} -> student.learn
    public function showLesson($lessonId)
    {
        $lesson = Lesson::with(['materials', 'course'])->findOrFail($lessonId);
        $userId = Auth::id();
        
        // **Bảo mật:** Đảm bảo người dùng đã đăng ký khóa học
        $isEnrolled = Enrollment::where('user_id', $userId)
                                 ->where('course_id', $lesson->course_id)
                                 ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.courses.detail', $lesson->course_id)
                             ->with('error', 'Bạn cần đăng ký khóa học để xem bài học này.');
        }

        // Lấy trạng thái hoàn thành để hiển thị trong View
        $isCompleted = LessonCompletion::where('user_id', $userId)
                                       ->where('lesson_id', $lessonId)
                                       ->exists();

        return view('students.learn.lesson', compact('lesson', 'isCompleted'));
    }
}