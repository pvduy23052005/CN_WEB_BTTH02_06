<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Gate; // Có thể dùng Gate/Policy để bảo mật
use App\Models\LessonCompletion;
use Carbon\Carbon;
use App\Models\Material;
class LessonController extends Controller
{
    
    public function showLesson($lessonId)

    {
        $lesson = Lesson::with(['materials', 'course'])->findOrFail($lessonId);
        $userId = Auth::id();
        
        // **Bảo mật:** Đảm bảo người dùng đã đăng ký khóa học
        $isEnrolled = Enrollment::where('student_id', $userId)
                                 ->where('course_id', $lesson->course_id)
                                 ->exists();

        if (!$isEnrolled) {
            return redirect()->route('student.courses.detail', $lesson->course_id)
                             ->with('error', 'Bạn cần đăng ký khóa học để xem bài học này.');
        }


        // Lấy trạng thái hoàn thành để hiển thị trong View
        $isCompleted = LessonCompletion::where('student_id', $userId)
                                       ->where('lesson_id', $lessonId)
                                       ->exists();
        return view('students.learn.lesson', compact('lesson', 'isCompleted'));
    }

     //phần instructor
    // [GET] /instructor/courses/{course}/lessons
public function index(Course $course)
{
    if ($course->instructor_id != Auth::id()) {
        return redirect()->route('instructor.courses.index')
            ->with('msg', 'Bạn không có quyền quản lý bài học của khóa này!');
    }

    // Lấy lessons kèm theo materials (Eager Loading)
    $lessons = $course->lessons()
        ->with('materials') // <--- QUAN TRỌNG: Load thêm bảng materials
        ->where('is_deleted', 0)
        ->orderBy('order', 'asc')
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

        if ($course->instructor_id != Auth::id()) {

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
        // 1. Check quyền
        if ($course->instructor_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Validate
        $request->validate([
            'title' => 'required|max:255',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:500000',
            'document_file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
        ]);

        $data = $request->except(['video_file', 'document_file']);
        $data['course_id'] = $course->id;
        $data['is_deleted'] = 0;

        // 3. Xử lý Video (Giữ nguyên)
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = time() . '_vid_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lessons'), $filename); 
            $data['video_url'] = 'uploads/lessons/' . $filename;
        }

        // 4. TẠO LESSON TRƯỚC (QUAN TRỌNG: Để lấy được ID bài học)
        $newLesson = Lesson::create($data);

        // 5. Xử lý Tài liệu (SỬA ĐOẠN NÀY)
        if ($request->hasFile('document_file')) {
            $docFile = $request->file('document_file');
            $originalName = $docFile->getClientOriginalName();
            $extension = $docFile->getClientOriginalExtension();
            
            // --- TẠO ĐƯỜNG DẪN THEO ID BÀI HỌC ---
            // Ví dụ lesson id = 15 thì folder là: materials/lesson15
            $folderName = 'materials/lesson' . $newLesson->id;
            
            // Đường dẫn tuyệt đối trên server
            $destinationPath = public_path($folderName);

            // Kiểm tra nếu folder chưa có thì tạo mới
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            // Di chuyển file vào thư mục lesson{id}
            // Giữ nguyên tên file gốc cho đẹp (hoặc thêm time() nếu muốn)
            $docFile->move($destinationPath, $originalName);
            
            // Lưu vào DB
            Material::create([
                'lesson_id'   => $newLesson->id,
                'filename'    => $originalName,
                // Đường dẫn lưu DB: /materials/lesson{id}/ten_file.pdf
                'file_path'   => '/' . $folderName . '/' . $originalName, 
                'file_type'   => $extension,
                'uploaded_at' => Carbon::now()
            ]);
        }

        return redirect()->route('instructor.lessons.index', $course->id)
            ->with('msg', 'Thêm bài học thành công!');
    }



    // [GET] /instructor/courses/{course}/lessons/{lesson}/edit

    public function edit(Course $course, Lesson $lesson)

    {

        // 1. Check quyền sở hữu khóa học

        if ($course->instructor_id != Auth::id()) {

            return redirect()->route('instructor.courses.index')->with('msg', 'Không đủ quyền!');

        }



        // 2. Check bài học có thuộc khóa học này không

        if ($lesson->course_id != $course->id || $lesson->is_deleted == 1) {

            return redirect()->route('instructor.lessons.index', $course->id)->with('msg', 'Bài học không tồn tại!');

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
        // 1. Check quyền
        if ($course->instructor_id != Auth::id() || $lesson->course_id != $course->id) {
             abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'document_file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
        ]);

        $data = $request->except(['video_file', 'document_file']);

        // Xử lý video (Giữ nguyên)
        if ($request->hasFile('video_file')) {
             $file = $request->file('video_file');
             $filename = time() . '_vid_' . $file->getClientOriginalName();
             $file->move(public_path('uploads/lessons'), $filename);
             $data['video_url'] = 'uploads/lessons/' . $filename;
        }

        // Cập nhật thông tin cơ bản của Lesson
        $lesson->update($data);

        // Xử lý Tài liệu Mới (SỬA LẠI GIỐNG HÀM STORE)
        if ($request->hasFile('document_file')) {
            $docFile = $request->file('document_file');
            $originalName = $docFile->getClientOriginalName();
            $extension = $docFile->getClientOriginalExtension();
            
            // --- TẠO ĐƯỜNG DẪN THEO ID BÀI HỌC (lesson + id) ---
            $folderName = 'materials/lesson' . $lesson->id;
            
            // Đường dẫn tuyệt đối trên server
            $destinationPath = public_path($folderName);

            // Kiểm tra nếu folder chưa tồn tại thì tạo mới
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            // Di chuyển file vào thư mục lesson{id}
            $docFile->move($destinationPath, $originalName);
            
            // Tạo bản ghi mới trong bảng materials
            // Lưu ý: Code này là THÊM file mới vào danh sách. 
            // Nếu bạn muốn thay thế file cũ, bạn cần tìm và xóa bản ghi cũ trước.
            Material::create([
                'lesson_id'   => $lesson->id,
                'filename'    => $originalName,
                // Đường dẫn: /materials/lesson{id}/ten_file.pdf
                'file_path'   => '/' . $folderName . '/' . $originalName, 
                'file_type'   => $extension,
                'uploaded_at' => Carbon::now()
            ]);
        }

        return redirect()->route('instructor.lessons.index', $course->id)
            ->with('msg', 'Cập nhật bài học thành công!');
    }


    // [DELETE] /instructor/courses/{course}/lessons/{lesson}

    public function destroy(Course $course, Lesson $lesson)

    {

        // 1. Check quyền

        if ($course->instructor_id == Auth::id() && $lesson->course_id == $course->id) {

           

            // XÓA MỀM (Yêu cầu model Lesson phải có 'is_deleted' trong $fillable)

            $lesson->update(['is_deleted' => 1]);

           

            return redirect()->route('instructor.lessons.index', $course->id)->with('msg', 'Đã xóa bài học!');

        }



        return redirect()->route('instructor.lessons.index', $course->id)->with('msg', 'Lỗi: Không tìm thấy bài học hoặc không đủ quyền.');

    }
}