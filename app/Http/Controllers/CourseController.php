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
        // Chỉ lấy những khóa học chưa bị xóa (is_deleted = 0)
        // Sắp xếp ID giảm dần để khóa học mới nhất lên đầu
        $courses = Course::where("is_deleted", 0)->orderBy('id', 'desc')->get();
        
        return view('instructor.course.index', [
            "title" => "Quản lý khóa học",
            "courses" => $courses
        ]);
    }

    // [GET] /instructor/courses/create
    public function create()
    {
        $categories = Category::all();
        // Không cần lấy instructors nữa vì sẽ tự động gán
        
        return view('instructor.course.create', [
            'title' => 'Thêm khóa học',
            'categories' => $categories
        ]);
    }

    // [POST] /instructor/courses/store
    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('image');

        // 2. Tự động lấy giảng viên (Demo hoặc Auth)
        $demoInstructor = User::where('role', 1)->first();
        if ($demoInstructor) {
            $data['instructor_id'] = $demoInstructor->id;
        } else {
            // Fallback: Gán cứng = 1 để test nếu DB rỗng
            $data['instructor_id'] = 1; 
        }
        
        $data['is_deleted'] = 0;

        // 3. Xử lý ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/courses'), $filename);
            $data['image'] = 'uploads/courses/' . $filename; // Lưu đường dẫn đầy đủ cho tiện
        }

        Course::create($data);

        return redirect()->route('instructor.courses.index')->with('msg', 'Thêm khóa học thành công!');
    }

    // [GET] /instructor/courses/{id}/edit
    public function edit($id)
    {
        // Tìm khóa học theo ID
        $course = Course::find($id);

        // Kiểm tra nếu không tìm thấy hoặc khóa học đã bị xóa mềm
        if (!$course || $course->is_deleted == 1) {
            return redirect()->route('instructor.courses.index')->with('msg', 'Khóa học không tồn tại!');
        }

        $categories = Category::all();

        // Trả về view edit (Lưu ý đường dẫn view: instructor.course.edit)
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

        if (!$course) {
            return redirect()->route('instructor.courses.index')->with('msg', 'Lỗi: Khóa học không tồn tại.');
        }

        // 1. Validate (Ảnh không bắt buộc khi sửa)
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        // Lấy dữ liệu ngoại trừ ảnh (vì ảnh xử lý riêng)
        $data = $request->except('image');

        // 2. Xử lý ảnh mới (Nếu người dùng có upload ảnh khác)
        if ($request->hasFile('image')) {
            // (Tùy chọn) Xóa ảnh cũ khỏi server nếu cần
            // if ($course->image && file_exists(public_path($course->image))) {
            //     unlink(public_path($course->image));
            // }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/courses'), $filename);
            
            // Cập nhật đường dẫn ảnh mới vào mảng data
            $data['image'] = 'uploads/courses/' . $filename;
        }
        // Lưu ý: Nếu không up ảnh mới, $data sẽ không có key 'image', Laravel sẽ giữ nguyên ảnh cũ trong DB.

        // 3. Thực hiện Update
        $course->update($data);

        return redirect()->route('instructor.courses.index')->with('msg', 'Cập nhật khóa học thành công!');
    }

    // [DELETE] /instructor/courses/{id}
    public function destroy($id)
    {
        $course = Course::find($id);

        if ($course) {
            // XÓA MỀM: Chuyển is_deleted thành 1
            $course->update(['is_deleted' => 1]);
            
            return redirect()->route('instructor.courses.index')->with('msg', 'Đã xóa khóa học thành công!');
        }

        return redirect()->route('instructor.courses.index')->with('msg', 'Lỗi: Không tìm thấy khóa học.');
    }
  
}

