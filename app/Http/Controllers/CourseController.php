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
   public function listCourses(Request $request) // <-- Đổi tên cho chức năng Học viên
{
    // Lấy giá trị đầu vào từ URL
    $search = $request->get('search');
    $category_id = $request->get('category');
    
    // Khởi tạo truy vấn và Eager Load (instructor, category)
    $query = Course::with('instructor', 'category')->where("is_deleted", 0);

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
        $isEnrolled = Enrollment::where('user_id', auth()->id())
                                ->where('course_id', $id)
                                ->exists();
    }
    // Ghi chú: Nếu bạn không sử dụng Model Enrollment, bạn cần thay thế
    // bằng logic kiểm tra bảng pivot hoặc quan hệ bạn đã thiết lập.

    // 3. TRUYỀN DỮ LIỆU SANG VIEW
    // Biến $lessons được truy cập qua $course->lessons trong View.
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



  // [get] /instructor/course 
  public function index(Request $request, Response $response)
  {
     $courses = Course::where("is_deleted" , 0)->get();
     $categories = Category::all();
    return view('courses.index', [
      "title" => "Dashboard course",
      "courses"=>$courses
    ]);
  }

  
 
  //Phần của giảng viên
  // 2. Giao diện thêm mới
    public function create()
    {
        // Cần lấy danh mục và giảng viên để chọn trong <select>
        $categories = Category::all();
        $instructors = User::where('role', 1)->get(); // Giả sử role 1 là giảng viên

        return view('courses.create', [
            'title' => 'Thêm khóa học',
            'categories' => $categories,
            'instructors' => $instructors

        ]);
    }

    // 3. Xử lý lưu mới
    public function store(Request $request)
    {
        // Validate dữ liệu (bạn có thể thêm rules tùy ý)
        $data = $request->all();
        $data['is_deleted'] = 0; // Mặc định chưa xóa

        Course::create($data);

        return redirect()->route('courses.index')->with('msg', 'Thêm thành công!');
    }

   public function edit($id)
{
    $course = Course::find($id);
    

    // Nếu không tìm thấy khóa học thì báo lỗi 404
    if (!$course) {
        return redirect()->route('courses.index')->with('msg', 'Không tìm thấy khóa học!');
    }

    $categories = Category::all();
    // Lấy list giảng viên (role = 1)
    $instructors = User::where('role', 1)->get(); 

    return view('courses.edit', [
        'title' => 'Sửa khóa học',
        'course' => $course,
        'categories' => $categories,
        'instructors' => $instructors
    ]);
}
    // 5. Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        $course->update($request->all());

        return redirect()->route('courses.index')->with('msg', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        $course->is_deleted = 1; // Đánh dấu là đã xóa
        $course->save();

        return redirect()->route('courses.index')->with('msg', 'Xóa thành công!');
    }
  
}

