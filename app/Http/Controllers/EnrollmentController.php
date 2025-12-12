<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment; 
use App\Models\Course; 
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
use App\Models\LessonCompletion;
// Thêm use cho Facades\DB nếu cần cho truy vấn phức tạp, nhưng không cần ở đây.

class EnrollmentController extends Controller
{

    private function recalculateProgress($courseId, $userId)
    {
        $totalLessons = Lesson::where('course_id', $courseId)->count();
        
        // SỬA: Dùng student_id cho LessonCompletion (để nhất quán với Enrollment)
        $completedLessons = LessonCompletion::where('student_id', $userId) 
                                            ->whereHas('lesson', fn($q) => $q->where('course_id', $courseId))
                                            ->count();
                                            
        $newProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        
        Enrollment::where('student_id', $userId) 
                  ->where('course_id', $courseId)
                  ->update(['progress' => $newProgress]);
                  
        return $newProgress;
    }

    // Yêu cầu: Đăng ký khóa học
    public function enroll($courseId)
    {
        $userId = Auth::id();
        Course::findOrFail($courseId); 

        // Đã sửa: student_id
        $exists = Enrollment::where('student_id', $userId)->where('course_id', $courseId)->exists();
        if ($exists) {
            return back()->with('warning', 'Bạn đã đăng ký khóa học này rồi.');
        }

        Enrollment::create([
            'student_id' => $userId, 
            'course_id' => $courseId,
            'enrolled_date' => Carbon::now(),
            'progress' => 0, 
            'status' => 'active',
        ]);

        return redirect()->route('student.home')->with('success', 'Đăng ký khóa học thành công!');
    }
    
    
    // Chức năng 5: Theo dõi tiến độ học tập của một Khóa học
    public function showProgress($courseId)
    {
        $userId = Auth::id();
        
        // Đã sửa: student_id
        $enrollment = Enrollment::where('student_id', $userId)
                                 ->where('course_id', $courseId)
                                 ->with('course.lessons') 
                                 ->firstOrFail(); 
        
        $progressPercentage = $this->recalculateProgress($courseId, $userId); 
        $lessons = $enrollment->course->lessons;
        
        // SỬA: Dùng student_id cho LessonCompletion
        $completedLessonIds = LessonCompletion::where('student_id', $userId) 
                                             ->whereIn('lesson_id', $lessons->pluck('id'))
                                             ->pluck('lesson_id')
                                             ->toArray();
                                             
        return view('students.cours_progress', compact('enrollment', 'lessons', 'progressPercentage', 'completedLessonIds'));
    }
    
    public function markLessonCompleted($lessonId)
    {
        $userId = Auth::id();
        $lesson = Lesson::findOrFail($lessonId);

        // Đã sửa: student_id
        $isEnrolled = Enrollment::where('student_id', $userId)->where('course_id', $lesson->course_id)->exists();
        if (!$isEnrolled) {
            return back()->with('error', 'Bạn chưa đăng ký khóa học này.');
        }
        
        // SỬA: Dùng student_id trong create (Bạn cần sửa migration/Model để chấp nhận student_id)
        LessonCompletion::firstOrCreate(
            ['student_id' => $userId, 'lesson_id' => $lessonId],
            ['completed_at' => Carbon::now()]
        );

        $this->recalculateProgress($lesson->course_id, $userId); 

        return back()->with('success', 'Bài học đã được đánh dấu hoàn thành!');
    }
}