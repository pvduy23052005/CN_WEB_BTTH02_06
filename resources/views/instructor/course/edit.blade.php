@extends('layout.layoutAdmin')

@section('main-content')

<div class="admin-content-wrapper">
    
    <div class="course-form-card">
        <h1>Cập Nhật Khóa Học</h1>
        <p style="text-align: center; color: #666;">ID: #{{ $course->id }}</p>
        <form action="{{ route('instructor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 

            <div class="form-group">
                <label for="title">Tiêu đề khóa học <span class="text-danger">*</span></label>
                {{-- Sử dụng old('title', $course->title) để lấy giá trị cũ --}}
                <input type="text" name="title" id="title" class="form-control" 
                       placeholder="Nhập tiêu đề khóa học..." 
                       value="{{ old('title', $course->title) }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="image">Ảnh đại diện (Chọn ảnh mới nếu muốn thay đổi)</label>
                
                <input type="file" name="image" id="image" class="form-control" 
                       style="padding: 9px;" accept="image/*" onchange="previewImage(this)">
                <img id="preview-img" 
                     src="{{ $course->image ? asset($course->image) : '' }}" 
                     alt="Ảnh xem trước" 
                     class="img-preview"
                     style="{{ $course->image ? 'display:block' : 'display:none' }}">
                
                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="price">Học phí (VNĐ)</label>
                        <input type="number" name="price" id="price" class="form-control" 
                               placeholder="0" value="{{ old('price', $course->price) }}">
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="duration_weeks">Thời lượng (Tuần)</label>
                        <input type="number" name="duration_weeks" id="duration_weeks" class="form-control" 
                               placeholder="VD: 8" value="{{ old('duration_weeks', $course->duration_weeks) }}">
                        @error('duration_weeks') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="level">Trình độ</label>
                        <select name="level" id="level" class="form-control">
                            {{-- Kiểm tra giá trị cũ để thêm thuộc tính 'selected' --}}
                            <option value="Beginner" {{ old('level', $course->level) == 'Beginner' ? 'selected' : '' }}>Cơ bản (Beginner)</option>
                            <option value="Intermediate" {{ old('level', $course->level) == 'Intermediate' ? 'selected' : '' }}>Trung bình (Intermediate)</option>
                            <option value="Advanced" {{ old('level', $course->level) == 'Advanced' ? 'selected' : '' }}>Nâng cao (Advanced)</option>
                        </select>
                        @error('level') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="category_id">Danh mục khóa học <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">-- Chọn danh mục --</option>
                            @if(isset($categories))
                                @foreach($categories as $cat)
                                    {{-- Kiểm tra ID danh mục để 'selected' --}}
                                    <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>
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
                {{-- Textarea thì giá trị cũ nằm giữa cặp thẻ đóng mở --}}
                <textarea name="description" id="description" class="form-control" rows="5"
                          placeholder="Nhập nội dung chi tiết khóa học...">{{ old('description', $course->description) }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection