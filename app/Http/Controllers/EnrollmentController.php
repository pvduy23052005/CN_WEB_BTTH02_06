<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment; 
use App\Models\Course; 
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{

    private function recalculateProgress($courseId, $userId)
    {
        $totalLessons = Lesson::where('course_id', $courseId)->count();
        $completedLessons = LessonCompletion::where('user_id', $userId)
                                           ->whereHas('lesson', fn($q) => $q->where('course_id', $courseId))
                                           ->count();
                                           
        $newProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
        
        Enrollment::where('user_id', $userId)
                  ->where('course_id', $courseId)
                  ->update(['progress_percentage' => $newProgress]);
                  
        return $newProgress;
    }

    // Yêu cầu: Đăng ký khóa học
    // Chức năng 4: Đăng ký khóa học
    // Route: POST /student/enroll/{courseId} -> student.enroll
    public function enroll($courseId)
    {
        $userId = Auth::id();
        Course::findOrFail($courseId); 

        $exists = Enrollment::where('user_id', $userId)->where('course_id', $courseId)->exists();
        if ($exists) {
            return back()->with('warning', 'Bạn đã đăng ký khóa học này rồi.');
        }

        Enrollment::create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => Carbon::now(), 
            'progress_percentage' => 0, 
        ]);

        return redirect()->route('student.home')->with('success', 'Đăng ký khóa học thành công!');
    }
    
   
    // Chức năng 5: Theo dõi tiến độ học tập của một Khóa học
    // Route: /student/progress/{courseId} -> student.progress
    // Yêu cầu: Theo dõi tiến độ học tập của một Khóa học
    public function showProgress($courseId)
    {
        $userId = Auth::id();
        $enrollment = Enrollment::where('user_id', $userId)
                                 ->where('course_id', $courseId)
                                 ->with('course.lessons') 
                                 ->firstOrFail(); 
        
        $progressPercentage = $this->recalculateProgress($courseId, $userId); 
        $lessons = $enrollment->course->lessons;
        
        $completedLessonIds = LessonCompletion::where('user_id', $userId)
                                                ->whereIn('lesson_id', $lessons->pluck('id'))
                                                ->pluck('lesson_id')
                                                ->toArray();
                                                
        return view('students.progress.show', compact('enrollment', 'lessons', 'progressPercentage', 'completedLessonIds'));
    }
    public function markLessonCompleted($lessonId)
    {
        $userId = Auth::id();
        $lesson = Lesson::findOrFail($lessonId);

        $isEnrolled = Enrollment::where('user_id', $userId)->where('course_id', $lesson->course_id)->exists();
        if (!$isEnrolled) {
            return back()->with('error', 'Bạn chưa đăng ký khóa học này.');
        }
        
        LessonCompletion::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['completed_at' => Carbon::now()]
        );

        $this->recalculateProgress($lesson->course_id, $userId); 

        return back()->with('success', 'Bài học đã được đánh dấu hoàn thành!');
    }
}
