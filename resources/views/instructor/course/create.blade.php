@extends('layout.layoutAdmin')
@section('main-content')

<div class="admin-content-wrapper">
    
    <div class="course-form-card">
        <h1>Thêm Khóa Học Mới</h1>
        <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Tiêu đề khóa học <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" 
                       placeholder="Nhập tiêu đề khóa học..." value="{{ old('title') }}">
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="image">Ảnh đại diện</label>
                <input type="file" name="image" id="image" class="form-control" style="padding: 9px;" onchange="previewImage(this)">
                <img id="preview-img" src="" alt="Ảnh xem trước" class="img-preview">
                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="price">Học phí (VNĐ)</label>
                        <input type="number" name="price" id="price" class="form-control" 
                               placeholder="0" value="{{ old('price') }}">
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="duration_weeks">Thời lượng (Tuần)</label>
                        <input type="number" name="duration_weeks" id="duration_weeks" class="form-control" 
                               placeholder="VD: 8" value="{{ old('duration_weeks') }}">
                        @error('duration_weeks') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="level">Trình độ</label>
                        <select name="level" id="level" class="form-control">
                            <option value="Beginner" {{ old('level') == 'Beginner' ? 'selected' : '' }}>Cơ bản (Beginner)</option>
                            <option value="Intermediate" {{ old('level') == 'Intermediate' ? 'selected' : '' }}>Trung bình (Intermediate)</option>
                            <option value="Advanced" {{ old('level') == 'Advanced' ? 'selected' : '' }}>Nâng cao (Advanced)</option>
                        </select>
                        @error('level') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="category_id">Danh mục khóa học <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">-- Chọn danh mục --</option>
                            @if(isset($categories))
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Mô tả chi tiết</label>
                <textarea name="description" id="description" class="form-control" rows="5"
                          placeholder="Nhập nội dung chi tiết khóa học...">{{ old('description') }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu khóa học</button>
            </div>
        </form>
    </div>
</div>


@endsection