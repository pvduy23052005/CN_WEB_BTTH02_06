{{-- File: resources/views/students/course_progress.blade.php --}} 

@extends('layout.layoutSinhVien') 
@section('title', 'Tiến độ Khóa học')

@section('main-content')
    <div class="container my-5">

        {{-- KHỐI TÍNH TOÁN SỐ LƯỢNG BÀI HỌC BẮT BUỘC --}}
        @php
            // $lessons là Collection các bài học trong khóa (được truyền từ Controller)
            $totalLessonsCount = $lessons->count(); 
            
            // $completedLessonIds là mảng ID các bài đã hoàn thành
            $completedLessonsCount = count($completedLessonIds ?? []); 
            
            // Đặt màu dựa trên tiến độ: success nếu 100%
            $progress_class = ($progressPercentage ?? 0) == 100 ? 'bg-success' : 'bg-info';
        @endphp
        
        <h1 class="mb-4 text-primary"><i class="fas fa-chart-line me-2"></i> Tiến độ Khóa học: {{ $enrollment->course->course_name ?? $enrollment->course->title }}</h1> 
        
        {{-- Nút Quay lại --}}
        <p class="text-muted small">
            <a href="{{ route('student.home') }}"><i class="fas fa-chevron-left"></i> Quay lại Khóa học của tôi</a>
        </p>

        {{-- Hiển thị thông báo flash message --}}
        @if (session('success'))
            <div class="alert alert-success mt-3"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        
        {{-- Tổng quan Tiến độ --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                
                {{-- ĐÃ SỬA: Dùng biến đã tính toán để khắc phục lỗi 0/0 --}}
                <h4 class="card-title text-secondary">
                    Tổng quan: {{ $completedLessonsCount }} / {{ $totalLessonsCount }} bài đã hoàn thành
                </h4>
                
                <div class="progress mb-3" style="height: 30px;">
                    <div class="progress-bar {{ $progress_class }}" role="progressbar" style="width: {{ $progressPercentage ?? 0 }}%;">
                        <span class="fw-bold fs-5">{{ $progressPercentage ?? 0 }}% Hoàn thành</span>
                    </div>
                </div>
                
                @if (($progressPercentage ?? 0) == 100)
                    <div class="alert alert-success text-center mt-3">Xin chúc mừng! Bạn đã hoàn thành khóa học này.</div>
                @endif
            </div>
        </div>

        <h3 class="mt-5 text-secondary">Danh sách Bài học</h3>
        <div class="list-group shadow-sm">
            @forelse ($lessons->sortBy('order') as $lesson)
                @php
                    // SỬ DỤNG LOGIC TỪ CONTROLLER: Kiểm tra ID bài học trong mảng đã hoàn thành
                    $isCompleted = in_array($lesson->id, $completedLessonIds ?? []); 
                    // Thêm class 'disabled' để làm mờ bài đã hoàn thành
                    $item_class = $isCompleted ? 'bg-light text-muted' : ''; 
                @endphp

                <div class="list-group-item d-flex justify-content-between align-items-center {{ $item_class }}">
                    <div>
                        <span class="me-3 fw-bold">{{ $lesson->order }}.</span>
                        {{ $lesson->title }}
                    </div>
                    <div>
                        @if ($isCompleted)
                            <span class="badge bg-success me-3"><i class="fas fa-check-circle"></i> Đã hoàn thành</span>
                        @else
                            <a href="{{ route('student.learn', $lesson->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-play-circle"></i> Bắt đầu học
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="list-group-item text-muted text-center py-3">
                    <i class="fas fa-book-open"></i> Khóa học chưa có bài học nào được thêm.
                </div>
            @endforelse
        </div>
    </div>
@endsection