@extends('layout.layoutAdmin')

@section('main-content')

<div class="admin-content-wrapper">

    @if(session('msg'))
        <div class="alert alert-success" style="padding: 12px; margin-bottom: 20px; background: #c6f6d5; color: #22543d; border-radius: 8px; border: 1px solid #9ae6b4; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i> {{ session('msg') }}
        </div>
    @endif

    <div class="custom-card">
        {{-- HEADER --}}
        <div class="card-header-custom">
            <h1 class="page-title">Quản lý Khóa học</h1>
            <a href="{{ route('instructor.courses.create') }}" class="btn-add-new">
                <i class="fas fa-plus"></i> Thêm khóa học
            </a>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">ID</th>
                        <th width="12%">Hình ảnh</th>
                        <th width="28%">Tên khóa học</th>
                        <th width="15%">Danh mục</th>
                        <th width="15%">Giá bán</th>
                        <th width="25%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($courses) > 0)
                        @foreach ($courses as $course)
                        <tr>
                            <td class="text-center" style="font-weight: bold; color: #a0aec0;">#{{ $course->id }}</td>
                            
                            {{-- Ảnh --}}
                            <td>
                                <div class="img-box">
                                    @if($course->image)
                                        <img src="{{ asset($course->image) }}" alt="Course Img">
                                    @else
                                        <span style="font-size: 10px; color: #cbd5e0;"><i class="fas fa-image"></i></span>
                                    @endif
                                </div>
                            </td>

                            {{-- Tên khóa học --}}
                            <td>
                                <div style="font-weight: 700; color: #2d3748; font-size: 15px; margin-bottom: 3px;">
                                    {{ $course->title }}
                                </div>
                                <div style="font-size: 12px; color: #718096;">
                                    <i class="far fa-clock"></i> {{ $course->duration_weeks }} tuần 
                                    <span style="margin: 0 5px;">•</span> 
                                    {{ $course->level }}
                                </div>
                            </td>

                            {{-- Danh mục --}}
                            <td>
                                <span class="badge-cat">
                                    {{ optional($course->category)->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>

                            {{-- Giá --}}
                            <td>
                                @if($course->price == 0)
                                    <span style="color: #198754; font-weight: bold;">Miễn phí</span>
                                @else
                                    <span class="price-tag">{{ number_format($course->price) }} đ</span>
                                @endif
                            </td>

                            {{-- HÀNH ĐỘNG --}}
                            <td class="text-center">
                                <div class="action-group">
                                    {{-- 1. Xem Học viên --}}
                                    <a href="{{ route('instructor.courses.students', $course->id) }}" class="btn-icon btn-students" title="Danh sách học viên">
                                        <i class="fas fa-users"></i>
                                    </a>

                                    {{-- 2. Quản lý Bài học --}}
                                    <a href="{{ route('instructor.lessons.index', $course->id) }}" class="btn-icon btn-lessons" title="Quản lý bài học">
                                        <i class="fas fa-list"></i>
                                    </a>

                                    {{-- 3. Sửa --}}
                                    <a href="{{ route('instructor.courses.edit', $course->id) }}" class="btn-icon btn-edit" title="Sửa khóa học">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    {{-- 4. Xóa --}}
                                    <form action="{{ route('instructor.courses.destroy', $course->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa khóa học này không?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 50px; color: #a0aec0;">
                                <div style="margin-bottom: 15px;">
                                    <i class="far fa-folder-open" style="font-size: 48px; opacity: 0.5;"></i>
                                </div>
                                <p>Chưa có khóa học nào được tạo.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection