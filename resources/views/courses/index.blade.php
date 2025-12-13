{{-- File: resources/views/courses/index.blade.php --}}

@extends('layout.layoutSinhVien') 
@section('title', 'Danh sách Khóa học')

@section('main-content')
    <div class="container my-5">
        {{-- Tiêu đề và Form Tìm kiếm (Đã được khôi phục) --}}
        <h1 class="mb-4 text-center text-primary">Khám phá Khóa học Online</h1>
        
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-10">
                <form method="GET" action="{{ route('student.courses.index') }}" class="row g-3 align-items-center bg-light p-3 rounded shadow-sm">
                    {{-- Trường tìm kiếm --}}
                    <div class="col-md-5">
                        <label for="searchInput" class="visually-hidden">Tìm kiếm</label>
                        <input type="text" name="search" id="searchInput" class="form-control" placeholder="Tìm kiếm theo tên khóa học..." value="{{ $search ?? '' }}">
                    </div>
                    
                    {{-- Trường lọc theo Danh mục --}}
                    <div class="col-md-4">
                        <label for="categorySelect" class="visually-hidden">Danh mục</label>
                        <select name="category" id="categorySelect" class="form-select">
                            <option value="">Tất cả Danh mục</option>
                            @foreach ($categories as $category) 
                                <option value="{{ $category->id }}" {{ ($selected_category ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Nút hành động --}}
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-info text-white flex-fill me-2">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        @if (!empty($search) || !empty($selected_category))
                            <a href="{{ route('student.courses.index') }}" class="btn btn-secondary">Đặt lại</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Danh sách Khóa học --}}
        <div class="row">
            @forelse ($courses as $course)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 transition-300">
                        
                        {{-- SỬA LỖI HIỂN THỊ HÌNH ẢNH --}}
                        @if ($course->image)
                            {{-- Giả định: $course->image chỉ chứa tên file (ví dụ: 'image.jpg') --}}
                            {{-- Và file được lưu trong thư mục public/assets/uploads/courses/ --}}
                            <img src="{{ asset($course->image) }}" 
                                 class="card-img-top course-image-sm" 
                                 alt="{{ $course->title }}">
                        @else
                            <div class="course-image-sm bg-light text-center p-5 text-muted">
                                [Hình ảnh minh họa]
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate">{{ $course->title }}</h5>
                            
                            {{-- Thông tin phụ --}}
                            <div class="mb-2">
                                <span class="badge bg-primary text-white me-2">{{ $course->category->name ?? 'Không rõ' }}</span>
                                <span class="text-muted small">
                                    <i class="fas fa-user-tie"></i> GV: {{ $course->instructor->fullname ?? 'N/A' }} 
                                </span>
                            </div>

                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($course->description, 90) }}</p>

                            {{-- Giá và Nút --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <span class="fs-5 fw-bold text-success">
                                    {{ number_format($course->price, 0, ',', '.') }} VNĐ
                                </span>
                                
                                <a href="{{ route('student.courses.detail', $course->id) }}" class="btn btn-primary btn-sm">
                                    Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle"></i> Không tìm thấy khóa học nào phù hợp với tiêu chí tìm kiếm của bạn.
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Phần phân trang --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $courses->appends(request()->input())->links() }} 
        </div>
    </div>
@endsection