<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
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

        // // Lấy trạng thái hoàn thành để hiển thị trong View
        // $isCompleted = LessonCompletion::where('user_id', $userId)
        //                                ->where('lesson_id', $lessonId)
        //                                ->exists();

        return view('students.learn.lesson', compact('lesson', 'isCompleted'));
    }


  





    // [GET] /instructor/courses/{course}/lessons
    public function index(Course $course)
    {
        // --- 1. CHECK QUYỀN: CHỈ GIẢNG VIÊN ID = 2 MỚI ĐƯỢC XEM ---
        if ($course->instructor_id != 2) {
            return redirect()->route('instructor.courses.index')
                ->with('msg', 'Bạn không có quyền quản lý bài học của khóa này!');
        }

        // 2. Lấy danh sách lesson (chưa xóa mềm)
        $lessons = $course->lessons()
            ->where('is_deleted', 0)
            ->orderBy('id', 'asc') 
            ->get();

        return view('instructor.lessons.index', [
            'title' => "Quản lý bài học: " . $course->title,
            'course' => $course,
            'lessons' => $lessons
        ]);
    }

    // [GET] /instructor/courses/{course}/lessons/create
    public function create(Course $course)
    {
        // --- 1. CHECK QUYỀN ---
        if ($course->instructor_id != 2) {
            return redirect()->route('instructor.courses.index')
                ->with('msg', 'Bạn không có quyền thêm bài vào khóa này!');
        }

        return view('instructor.lessons.create', [
            'title' => 'Thêm bài học mới',
            'course' => $course
        ]);
    }

    // [POST] /instructor/courses/{course}/lessons
    public function store(Request $request, Course $course)
    {
        // --- 1. CHECK QUYỀN ---
        if ($course->instructor_id != 2) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'video_url' => 'nullable|url', // Link (nếu dùng link ngoài)
            'duration' => 'nullable|numeric',
            // 'video_file' => 'nullable|mimetypes:video/mp4...|max:...' // Nếu validate file
        ]);

        $data = $request->all();
        
        $data['course_id'] = $course->id;
        $data['is_deleted'] = 0;

        // Xử lý upload video (nếu có file)
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            // Đặt tên file unique
            $filename = time() . '_' . $file->getClientOriginalName();
            // Lưu vào thư mục public/uploads/lessons
            $file->move(public_path('uploads/lessons'), $filename);
            
            // Lưu đường dẫn vào DB
            $data['video_url'] = 'uploads/lessons/' . $filename; 
        }

        Lesson::create($data);

        return redirect()->route('instructor.lessons.index', $course->id)
            ->with('msg', 'Thêm bài học thành công!');
    }

    // [GET] /instructor/courses/{course}/lessons/{lesson}/edit
    public function edit(Course $course, Lesson $lesson)
    {
        // --- 1. CHECK QUYỀN SỞ HỮU KHÓA HỌC (ID = 2) ---
        if ($course->instructor_id != 2) {
            return redirect()->route('instructor.courses.index')->with('msg', 'Không đủ quyền!');
        }

        // --- 2. CHECK RÀNG BUỘC DỮ LIỆU ---
        // Bài học phải thuộc khóa học này VÀ chưa bị xóa
        if ($lesson->course_id != $course->id || $lesson->is_deleted == 1) {
            return redirect()->route('lessons.index', $course->id)->with('msg', 'Bài học không tồn tại!');
        }

        return view('instructor.lessons.edit', [
            'title' => 'Cập nhật bài học',
            'course' => $course,
            'lesson' => $lesson
        ]);
    }

    // [PUT] /instructor/courses/{course}/lessons/{lesson}
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        // --- 1. CHECK QUYỀN & RÀNG BUỘC ---
        if ($course->instructor_id != 2 || $lesson->course_id != $course->id) {
             abort(403); 
        }

        $request->validate([
            'title' => 'required|max:255',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|numeric',
        ]);

        $data = $request->except('video_file');

        // Xử lý file video mới (nếu người dùng upload lại)
        if ($request->hasFile('video_file')) {
            // (Tùy chọn) Xóa video cũ để tiết kiệm dung lượng server
            // if (!empty($lesson->video_url) && file_exists(public_path($lesson->video_url))) {
            //     @unlink(public_path($lesson->video_url));
            // }

            $file = $request->file('video_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lessons'), $filename);
            $data['video_url'] = 'uploads/lessons/' . $filename;
        }

        $lesson->update($data);

        return redirect()->route('instructor.lessons.index', $course->id)
            ->with('msg', 'Cập nhật bài học thành công!');
    }

    // [DELETE] /instructor/courses/{course}/lessons/{lesson}
    public function destroy(Course $course, Lesson $lesson)
    {
        // --- 1. CHECK QUYỀN ---
        // Phải là giảng viên ID 2 VÀ bài học phải thuộc khóa học này
        if ($course->instructor_id == 2 && $lesson->course_id == $course->id) {
            
            // XÓA MỀM
            $lesson->update(['is_deleted' => 1]);
            
            return redirect()->route('instructor.lessons.index', $course->id)->with('msg', 'Đã xóa bài học!');
        }

        return redirect()->route('instructor.lessons.index', $course->id)->with('msg', 'Lỗi: Không tìm thấy bài học hoặc không đủ quyền.');
    }

}