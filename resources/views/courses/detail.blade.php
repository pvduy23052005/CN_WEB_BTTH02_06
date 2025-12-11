{{-- File: resources/views/courses/detail.blade.php --}}

@extends('layout.layoutSinhVien') 

@section('title', $course->title . ' - Chi tiết Khóa học')

@section('main-content')
    <div class="container my-5">
        
        {{-- Hiển thị thông báo flash message --}}
        @if (session('error'))
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning"><i class="fas fa-exclamation"></i> {{ session('warning') }}</div>
        @endif
        
        <div class="row">
            {{-- CỘT NỘI DUNG CHÍNH (COURSE INFO VÀ LESSONS) --}}
            <div class="col-lg-8">
                <h1 class="text-primary fw-bold mb-3">{{ $course->title }}</h1>
                
                <p class="lead text-muted">
                    <i class="fas fa-tag"></i> {{ $course->category->name ?? 'Chưa phân loại' }}
                    | <i class="fas fa-user-tie"></i> Giảng viên: <strong>{{ $course->instructor->fullname ?? 'N/A' }}</strong>
                </p>
                
                <hr>

                <h3 class="mt-4 mb-3 text-secondary">Mô tả Khóa học</h3>
                <div class="content-body bg-light p-4 rounded border">
                    {{-- Dùng {!! !!} nếu description chứa HTML --}}
                    {!! $course->description !!} 
                </div>

                <h3 class="mt-5 mb-3 text-secondary">Nội dung Khóa học (Cấu trúc)</h3>
                <div class="list-group shadow-sm">
                    {{-- SỬA LỖI 1: Thay $lessons thành $course->lessons --}}
                    @forelse ($course->lessons->sortBy('order') as $lesson)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="me-3 fw-bold">{{ $lesson->order }}.</span>
                                {{ $lesson->title }} 
                            </div>
                            
                            {{-- CHỈ HIỂN THỊ LINK TRUY CẬP NẾU ĐÃ ĐĂNG KÝ --}}
                            @if ($isEnrolled)
                                <a href="{{ route('student.learn', $lesson->id) }}" class="btn btn-info btn-sm text-white">
                                    <i class="fas fa-play-circle"></i> Xem bài học
                                </a>
                            @else
                                <span class="text-warning small"><i class="fas fa-lock"></i> Cần đăng ký</span>
                            @endif
                        </div>
                    @empty
                        <div class="list-group-item text-muted text-center py-3">
                            <i class="fas fa-book-open"></i> Khóa học này chưa có bài học nào được đăng tải.
                        </div>
                    @endforelse
                </div>
            </div>
            
            {{-- CỘT SIDEBAR (THÔNG TIN TỔNG QUAN VÀ NÚT HÀNH ĐỘNG) --}}
            <div class="col-lg-4">
                <div class="card shadow-lg sticky-top" style="top: 20px;">
                    @if ($course->image)
                        <img src="{{ asset('assets/uploads/courses/' . $course->image) }}" alt="{{ $course->title }}" class="card-img-top">
                    @endif
                    
                    <div class="card-body text-center">
                        <h4 class="card-title mb-4 text-danger">{{ number_format($course->price ?? 0, 0, ',', '.') }} VNĐ</h4>
                        
                        {{-- KHỐI NÚT HÀNH ĐỘNG CHÍNH (Giữ nguyên logic isEnrolled và Auth::check) --}}
                        @if ($isEnrolled)
                            {{-- TRƯỜNG HỢP 1: ĐÃ ĐĂNG KÝ --}}
                            <p class="alert alert-success fw-bold">
                                <i class="fas fa-check-circle"></i> Bạn đã đăng ký khóa học này!
                            </p>
                            <a href="{{ route('student.progress', $course->id) }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-graduation-cap"></i> Tiếp tục học & Theo dõi tiến độ
                            </a>
                        @else
                            {{-- TRƯỜNG HỢP 2: CHƯA ĐĂNG KÝ --}}
                            @if (Auth::check()) 
                                {{-- Nếu đã đăng nhập, hiển thị nút đăng ký --}}
                                <form method="POST" action="{{ route('student.enroll', $course->id) }}" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg mb-2">
                                        <i class="fas fa-cart-plus"></i> Đăng ký Khóa học Ngay
                                    </button>
                                </form>
                            @else
                                {{-- Nếu chưa đăng nhập, khuyến khích đăng nhập --}}
                                <a href="{{url('/auth/login') }}" class="btn btn-warning btn-lg w-100 mb-2">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập để Đăng ký
                                </a>
                            @endif
                        @endif
                        
                        <hr class="my-3">
                        
                        {{-- THÔNG SỐ KHÓA HỌC --}}
                        <div class="text-start small">
                            <p class="mb-1"><i class="fas fa-chart-bar fa-fw me-2"></i> Trình độ: **{{ $course->level ?? 'Tất cả' }}**</p>
                            <p class="mb-1"><i class="fas fa-clock fa-fw me-2"></i> Thời lượng: **{{ $course->duration_weeks ?? 'N/A' }}** tuần</p>
                            {{-- SỬA LỖI 2: Thay $lessons->count() thành $course->lessons->count() --}}
                            <p class="mb-1"><i class="fas fa-list-ol fa-fw me-2"></i> Tổng số bài học: **{{ $course->lessons->count() }}**</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection