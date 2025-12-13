{{-- File: resources/views/students/my_courses.blade.php --}}

@extends('layout.layoutSinhVien') 
@section('title', 'Khóa học của tôi')

@section('main-content')
    <div class="container my-5">
        <h1 class="mb-4 text-primary"><i class="fas fa-graduation-cap me-2"></i> Khóa học của tôi</h1>

        {{-- Hiển thị thông báo flash message --}}
        @if (session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        
        <div class="row">
            @forelse ($enrollments as $enrollment)
                @php
                    // Lấy đối tượng Course từ quan hệ Enrollment
                    $course = $enrollment->course;
                    
                    // ĐÃ SỬA: SỬ DỤNG TÊN TRƯỜNG CHÍNH XÁC LÀ 'progress'
                    $progress = $enrollment->progress ?? 0; 
                    
                    // Thiết lập màu cho thanh tiến độ
                    $progress_class = $progress == 100 ? 'bg-success' : 'bg-info';
                @endphp

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Hình ảnh khóa học --}}
                        @if ($course->image)
                            <img src="{{ asset( $course->image) }}" class="card-img-top course-image-sm" alt="{{ $course->title }}">
                        @else
                            <div class="course-image-sm bg-light text-center p-3 text-muted">
                                [Hình ảnh khóa học]
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate">{{ $course->title }}</h5>
                            <p class="card-text text-muted small">
                                <i class="fas fa-user-tie"></i> Giảng viên: {{ $course->instructor->fullname ?? 'N/A' }}
                            </p>
                            
                            {{-- Thanh tiến độ --}}
                            <div class="mb-3 mt-auto">
                                {{-- Tiêu đề hiển thị đúng --}}
                                <p class="small mb-1 fw-bold">Tiến độ hiện tại:</p>
                                
                                {{-- KHỐI PROGRESS BAR ĐÃ ĐƯỢC CHÈN ĐÚNG --}}
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $progress_class }}" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $progress }}%
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Nút hành động --}}
                            <a href="{{ route('student.progress', $course->id) }}" class="btn btn-primary w-100 mt-2">
                                @if ($progress == 100)
                                    <i class="fas fa-certificate"></i> Hoàn thành! Xem lại
                                @else
                                    <i class="fas fa-play-circle"></i> Tiếp tục học
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        <h4 class="alert-heading"><i class="fas fa-info-circle"></i> Chưa có khóa học nào</h4>
                        <p>Bạn chưa đăng ký bất kỳ khóa học nào. Hãy khám phá ngay!</p>
                        <a href="{{ route('student.courses.index') }}" class="btn btn-success mt-2">
                            <i class="fas fa-search"></i> Khám phá Khóa học
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Phân trang (Nếu Controller trả về paginate()) --}}
        @if ($enrollments->lastPage() > 1)
            <div class="d-flex justify-content-center mt-4">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
@endsection