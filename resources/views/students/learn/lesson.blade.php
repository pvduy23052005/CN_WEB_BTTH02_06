{{-- File: resources/views/students/learn/lesson.blade.php --}}

@extends('layout.layoutSinhVien') 
{{-- Kế thừa layout chung --}}

@section('title', $lesson->title)

@section('main-content')
    <div class="container-fluid my-5">
        
        {{-- Hiển thị thông báo flash message --}}
        @if (session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        
        <div class="row">
            
            {{-- CỘT NỘI DUNG CHÍNH (VIDEO & TEXT) --}}
            <div class="col-lg-9">
                
                {{-- Thanh điều hướng nhanh --}}
                <p class="text-muted small">
                    <a href="{{ route('student.progress', $lesson->course->id) }}">
                        {{-- ĐÃ SỬA: Dùng $lesson->course->title để khắc phục lỗi Undefined variable $course và lỗi cú pháp $$. --}}
                        <i class="fas fa-chevron-left"></i> Quay lại Tiến độ Khóa học: {{ $lesson->course->title }}
                    </a>
                </p>

                <h1 class="mb-3 text-primary">{{ $lesson->order }}. {{ $lesson->title }}</h1>
                
                <div class="lesson-content bg-white p-4 shadow-sm rounded">
                    
                    {{-- 1. HIỂN THỊ VIDEO --}}
                    @if ($lesson->video_url)
                        <div class="ratio ratio-16x9 mb-4">
                            {{-- Giả định video_url là iframe hoặc link có thể nhúng được --}}
                            <iframe src="{{ $lesson->video_url }}" allowfullscreen 
                                    frameborder="0" allow="autoplay; encrypted-media"></iframe>
                        </div>
                    @endif
                    
                    {{-- 2. NỘI DUNG TEXT/MÔ TẢ --}}
                    <h3 class="text-secondary mt-4">Nội dung Bài học</h3>
                    <div class="content-text mt-3">
                        {!! $lesson->content !!} 
                    </div>
                </div>
                
                {{-- 3. NÚT ĐÁNH DẤU HOÀN THÀNH --}}
                <div class="d-flex justify-content-end mt-4">
                    @if ($isCompleted)
                        <button class="btn btn-success btn-lg" disabled>
                            <i class="fas fa-check-circle"></i> Bài học đã hoàn thành
                        </button>
                    @else
                        {{-- **LOGIC CẬP NHẬT TIẾN ĐỘ:** Form POST gọi EnrollmentController::markLessonCompleted --}}
                        <form method="POST" action="{{ route('student.lesson.complete', $lesson->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-bookmark"></i> Đánh dấu Hoàn thành Bài học
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            {{-- CỘT SIDEBAR (TÀI LIỆU VÀ CẤU TRÚC KHÓA HỌC) --}}
            <div class="col-lg-3">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-paperclip me-1"></i> Tài liệu Kèm theo
                    </div>
                    <ul class="list-group list-group-flush">
                        {{-- ĐÃ SỬA: Dùng $lesson->materials thay vì $materials để khắc phục lỗi Undefined variable $materials --}}
                        @forelse ($lesson->materials as $material) 
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-file-alt me-1"></i> {{ $material->filename }}</span>
                                
                                {{-- Liên kết tải xuống file --}}
                                <a href="{{ asset('assets/uploads/materials/' . $material->file_path) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Không có tài liệu nào.</li>
                        @endforelse
                    </ul>
                </div>
                
                {{-- Gợi ý: Khối hiển thị danh sách bài học khác để điều hướng --}}
            </div>
        </div>
    </div>
@endsection