@extends('layout.layoutAdmin')

@section('main-content')

<div class="admin-content-wrapper">

    @if(session('msg'))
        <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; background: #d1e7dd; color: #0f5132; border-radius: 8px; border: 1px solid #badbcc;">
            <i class="fas fa-check-circle"></i> {{ session('msg') }}
        </div>
    @endif

    <div class="custom-card">
        <div class="card-header-custom">
            <h1 class="page-title">Danh sách khóa học</h1>
            <a href="{{ route('instructor.courses.create') }}" class="btn-add-new">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Hình ảnh</th>
                        <th width="30%">Tên khóa học</th>
                        <th width="20%">Danh mục</th>
                        <th width="15%">Giá bán</th>
                        <th width="15%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($courses) > 0)
                        @foreach ($courses as $course)
                        <tr>
                            <td><b>#{{ $course->id }}</b></td>
                            <td>
                                <div class="course-img-box">
                                    @if($course->image)
                                        <img src="{{ asset($course->image) }}" alt="Course Image">
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#f0f0f0; color:#ccc; font-size:10px;">
                                            NO IMG
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span style="font-weight: 600; color:#333">{{ $course->title }}</span>
                            </td>
                            <td>
                                <span class="badge-cat">
                                    {{ optional($course->category)->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td>
                                <span class="price-text">{{ number_format($course->price) }} đ</span>
                            </td>
                            <td class="text-center">
                                <div class="action-group">
                                    <a href="{{ route('instructor.courses.edit', $course->id) }}" class="action-btn btn-edit" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('instructor.courses.destroy', $course->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Xóa" onclick="return confirm('Xác nhận xóa khóa học này?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 40px; color: #999;">
                                <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 10px; display:block;"></i>
                                Chưa có khóa học nào.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection