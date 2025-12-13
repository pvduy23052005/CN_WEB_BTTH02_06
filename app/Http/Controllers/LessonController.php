<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Gate; // Có thể dùng Gate/Policy để bảo mật
use App\Models\LessonCompletion;
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

        // 1. CHECK QUYỀN: So khớp ID giảng viên

        if ($course->instructor_id != Auth::id()) {

            return redirect()->route('instructor.courses.index')

                ->with('msg', 'Bạn không có quyền quản lý bài học của khóa này!');

        }



        // 2. Lấy danh sách lesson (chưa xóa mềm)

        $lessons = $course->lessons()

            ->where('is_deleted', 0)

            ->orderBy('order', 'asc') // Sắp xếp theo thứ tự hiển thị (hoặc id)

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



        // 2. Validate dữ liệu đầu vào

        $request->validate([

            'title' => 'required|max:255',

            'video_url' => 'nullable|url',

            'duration' => 'nullable|numeric',

            // Validate tài liệu (Max 10MB)

            'document_file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',

        ]);



        $data = $request->all();

       

        $data['course_id'] = $course->id;

        $data['is_deleted'] = 0;



        // 3. Xử lý upload VIDEO

        if ($request->hasFile('video_file')) {

            $file = $request->file('video_file');

            // Thêm tiền tố _vid_ để tránh trùng tên

            $filename = time() . '_vid_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/lessons'), $filename);

           

            $data['video_url'] = 'uploads/lessons/' . $filename;

        }



        // 4. Xử lý upload TÀI LIỆU (Mới thêm)

        if ($request->hasFile('document_file')) {

            $docFile = $request->file('document_file');

            // Thêm tiền tố _doc_

            $docName = time() . '_doc_' . $docFile->getClientOriginalName();

            $docFile->move(public_path('uploads/documents'), $docName);

           

            $data['document_url'] = 'uploads/documents/' . $docName;

        }



        Lesson::create($data);



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

        // 1. Check quyền & ràng buộc

        if ($course->instructor_id != Auth::id() || $lesson->course_id != $course->id) {

             abort(403);

        }



        $request->validate([

            'title' => 'required|max:255',

            'video_url' => 'nullable|url',

            'duration' => 'nullable|numeric',

            'document_file' => 'nullable|mimes:pdf,doc,docx,zip,rar|max:10240',

        ]);



        // Lấy hết dữ liệu trừ file ra để xử lý riêng

        $data = $request->except(['video_file', 'document_file']);



        // 2. Xử lý VIDEO mới (nếu có upload)

        if ($request->hasFile('video_file')) {

            // (Tùy chọn: Xóa file cũ để dọn rác server)

            // if ($lesson->video_url && file_exists(public_path($lesson->video_url))) {

            //    @unlink(public_path($lesson->video_url));

            // }



            $file = $request->file('video_file');

            $filename = time() . '_vid_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/lessons'), $filename);

            $data['video_url'] = 'uploads/lessons/' . $filename;

        }



        // 3. Xử lý TÀI LIỆU mới (nếu có upload)

        if ($request->hasFile('document_file')) {

            $docFile = $request->file('document_file');

            $docName = time() . '_doc_' . $docFile->getClientOriginalName();

            $docFile->move(public_path('uploads/documents'), $docName);

           

            $data['document_url'] = 'uploads/documents/' . $docName;

        }



        $lesson->update($data);



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