<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Lesson;

class CourseController extends Controller
{
    //Phần sinh viên
   public function listCourses(Request $request) 
{
    // Lấy giá trị đầu vào từ URL
    $search = $request->get('search');
    $category_id = $request->get('category');
    
    // Khởi tạo truy vấn và Eager Load (instructor, category)
    $query = Course::with('instructor', 'category')
                   ->where("is_deleted", 0)
                   ->where("is_active", 1);

    // Áp dụng Tìm kiếm
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }
    
    // Áp dụng Lọc theo Danh mục
    if ($category_id) {
        $query->where('category_id', $category_id);
    }

    // Lấy dữ liệu và PHÂN TRANG (Đã khắc phục lỗi Appends)
    $courses = $query->paginate(9);
    
    // Lấy tất cả danh mục (Đã khắc phục lỗi Undefined $categories)
    $categories = Category::all(); 

    // TRUYỀN DỮ LIỆU SANG VIEW (Sử dụng with() để rõ ràng hơn)
    return view('courses.index')->with([
        'courses' => $courses,
        'categories' => $categories,
        
        // 1. ĐỔI TÊN BIẾN để khớp với logic trong courses/index.blade.php
        'search' => $search,
        'selected_category' => $category_id // Truyền category_id dưới tên selected_category
    ]);
}
    public function showDetail($id) 
{
    // 1. Eager Load các quan hệ (instructor, category) và Tải Bài học (lessons)
    $course = Course::with(['instructor', 'category', 'lessons'])
        ->where('id', $id)
        ->where('is_deleted', 0) // Chỉ lấy khóa học chưa bị xóa
        ->firstOrFail(); // Trả về 404 nếu không tìm thấy

    // 2. LOGIC KIỂM TRA ĐĂNG KÝ
    $isEnrolled = false;
    if (auth()->check()) {
        // Kiểm tra xem người dùng đã đăng nhập có enrollment cho khóa học này không
        $isEnrolled = Enrollment::where('student_id', auth()->id())
                                ->where('course_id', $id)
                                ->exists();
    }
    
    return view('courses.detail', [
        'title' => 'Chi tiết Khóa học: ' . $course->title,
        'course' => $course,
        'isEnrolled' => $isEnrolled, // Biến này quan trọng cho nút Đăng ký/Học
    ]);
}

    // [get] /student/my_courses
    public function showMyCourses()
    {
        $student_id = Auth::id();
        $enrollments = Enrollment::with('course') 
                                  ->where('student_id', $student_id)
                                  ->orderBy('enrolled_date', 'desc')
                                  ->paginate(10);

        return view('students.my_courses', compact('enrollments'));
    }

  //Phần của giảng viên

   // [GET] /instructor/courses
    public function index()
    {
        // Lấy ID của giảng viên đang đăng nhập
        $instructorId = Auth::id();

        // Chỉ lấy các khóa học của giảng viên ĐANG ĐĂNG NHẬP
        $courses = Course::where('instructor_id', $instructorId)
            ->where("is_deleted", 0)
            ->orderBy('id', 'desc')
            ->get();
        
        return view('instructor.course.index', [
            "title" => "Quản lý khóa học của tôi",
            "courses" => $courses
        ]);
    }

    // [GET] /instructor/courses/create
    public function create()
    {
        $categories = Category::all();
        return view('instructor.course.create', [
            'title' => 'Thêm khóa học',
            'categories' => $categories
        ]);
    }

    // [POST] /instructor/courses/store
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        // --- GÁN CHO GIẢNG VIÊN ĐANG LOGIN ---
        $data['instructor_id'] = Auth::id(); 
        
        $data['is_deleted'] = 0;

        // Xử lý ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/courses'), $filename);
            $data['image'] = 'uploads/courses/' . $filename;
        }

        Course::create($data);

        return redirect()->route('instructor.courses.index')->with('msg', 'Thêm khóa học thành công!');
    }

    // [GET] /instructor/courses/{id}/edit
    public function edit($id)
    {
        $course = Course::find($id);
        $currentUserId = Auth::id(); // Lấy ID người đang login

        // Kiểm tra: Khóa học phải tồn tại, chưa xóa VÀ thuộc về người đang login
        if (!$course || $course->is_deleted == 1 || $course->instructor_id != $currentUserId) {
            return redirect()->route('instructor.courses.index')->with('msg', 'Bạn không có quyền sửa khóa học này!');
        }

        $categories = Category::all();

        return view('instructor.course.edit', [
            'title' => 'Cập nhật khóa học',
            'course' => $course,
            'categories' => $categories
        ]);
    }

    // [PUT] /instructor/courses/{id}
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        $currentUserId = Auth::id();

        // Kiểm tra quyền sở hữu
        if (!$course || $course->instructor_id != $currentUserId) {
            return redirect()->route('instructor.courses.index')->with('msg', 'Lỗi: Khóa học không tồn tại hoặc không đủ quyền.');
        }

        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        // Xử lý ảnh mới
        if ($request->hasFile('image')) {
            // (Tùy chọn) Xóa ảnh cũ...
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/courses'), $filename);
            $data['image'] = 'uploads/courses/' . $filename;
        }

        $course->update($data);

        return redirect()->route('instructor.courses.index')->with('msg', 'Cập nhật khóa học thành công!');
    }

    // [DELETE] /instructor/courses/{id}
    public function destroy($id)
    {
        $course = Course::find($id);
        $currentUserId = Auth::id();

        // Kiểm tra quyền sở hữu trước khi xóa
        if ($course && $course->instructor_id == $currentUserId) {
            // XÓA MỀM
            $course->update(['is_deleted' => 1]);
            return redirect()->route('instructor.courses.index')->with('msg', 'Đã xóa khóa học thành công!');
        }

        return redirect()->route('instructor.courses.index')->with('msg', 'Lỗi: Không tìm thấy khóa học hoặc không đủ quyền.');
    }
  // [GET] /instructor/courses/{id}/students
public function students($id)
{
    // 1. Lấy thông tin khóa học và Eager Load quan hệ enrollments + student
    $course = Course::with(['enrollments.student'])
        ->where('id', $id)
        ->first();

    // 2. Kiểm tra tồn tại và quyền sở hữu
    if (!$course || $course->is_deleted == 1) {
        return redirect()->back()->with('msg', 'Khóa học không tồn tại.');
    }

    if ($course->instructor_id != Auth::id()) {
        abort(403, 'Bạn không có quyền xem danh sách học viên của khóa này.');
    }

    return view('instructor.course.students', [
        'title' => 'Danh sách học viên',
        'course' => $course,
        'enrollments' => $course->enrollments // List ghi danh
    ]);
}
}

