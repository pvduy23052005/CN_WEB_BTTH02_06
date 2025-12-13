@extends('layout.layoutAdmin')
@section('main-content')
<div class="admin-content-wrapper">
    <div class="course-form-card">
        <div class="card-header" style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0; font-size: 24px;">Cập Nhật Khóa Học</h1>
                <div style="color: #666; margin-top: 5px;">
                    Chỉnh sửa thông tin cho khóa học: <b style="color: #17a2b8;">{{ $course->title }}</b>
                </div>
            </div>
            <span style="background: #eee; padding: 5px 10px; border-radius: 4px; font-weight: bold; color: #555;">ID: #{{ $course->id }}</span>
        </div>
        <form action="{{ route('instructor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 

            {{-- 1. Tiêu đề và Danh mục --}}
            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-col" style="flex: 2;">
                    <div class="form-group">
                        <label for="title" style="font-weight: 600;"><i class="fas fa-heading"></i> Tiêu đề khóa học <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" 
                               placeholder="Nhập tiêu đề khóa học..." 
                               value="{{ old('title', $course->title) }}" required>
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="category_id" style="font-weight: 600;"><i class="fas fa-list"></i> Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">-- Chọn danh mục --</option>
                            @if(isset($categories))
                                @foreach($categories as $cat)
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

            {{-- 2. Ảnh đại diện (Có Preview) --}}
            <div class="form-group" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 20px;">
                <label for="image" style="font-weight: 600;"><i class="fas fa-image"></i> Ảnh đại diện</label>
                
                <div style="display: flex; gap: 20px; align-items: flex-start; margin-top: 10px;">
                    <div style="flex: 1;">
                        <input type="file" name="image" id="image" class="form-control" 
                               style="padding: 9px;" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted" style="display: block; margin-top: 5px;">Chỉ chọn ảnh mới nếu bạn muốn thay đổi.</small>
                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div style="width: 150px; height: 100px; border: 1px solid #ddd; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 4px;">
                        <img id="preview-img" 
                             src="{{ $course->image ? asset($course->image) : asset('images/no-image.png') }}" 
                             alt="Ảnh xem trước" 
                             style="max-width: 100%; max-height: 100%; object-fit: cover; display: block;">
                    </div>
                </div>
            </div>

            {{-- 3. Thông tin chi tiết (Giá, Thời lượng, Trình độ) --}}
            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="price" style="font-weight: 600;"><i class="fas fa-tag"></i> Học phí (VNĐ)</label>
                        <input type="number" name="price" id="price" class="form-control" 
                               placeholder="0" value="{{ old('price', $course->price) }}">
                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="duration_weeks" style="font-weight: 600;"><i class="fas fa-clock"></i> Thời lượng (Tuần)</label>
                        <input type="number" name="duration_weeks" id="duration_weeks" class="form-control" 
                               placeholder="VD: 8" value="{{ old('duration_weeks', $course->duration_weeks) }}">
                        @error('duration_weeks') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="level" style="font-weight: 600;"><i class="fas fa-layer-group"></i> Trình độ</label>
                        <select name="level" id="level" class="form-control">
                            <option value="Beginner" {{ old('level', $course->level) == 'Beginner' ? 'selected' : '' }}>Cơ bản (Beginner)</option>
                            <option value="Intermediate" {{ old('level', $course->level) == 'Intermediate' ? 'selected' : '' }}>Trung bình (Intermediate)</option>
                            <option value="Advanced" {{ old('level', $course->level) == 'Advanced' ? 'selected' : '' }}>Nâng cao (Advanced)</option>
                        </select>
                        @error('level') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- 4. Mô tả --}}
            <div class="form-group">
                <label for="description" style="font-weight: 600;"><i class="fas fa-align-left"></i> Mô tả chi tiết</label>
                <textarea name="description" id="description" class="form-control" rows="6" 
                          placeholder="Nhập nội dung chi tiết khóa học...">{{ old('description', $course->description) }}</textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 5. Buttons --}}
            <div class="form-actions" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
                    <i class="fas fa-times"></i> Hủy bỏ
                </a>
                <button type="submit" class="btn btn-primary" style="background: #0d6efd; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-save"></i> Lưu Thay Đổi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT ĐỂ XEM TRƯỚC ẢNH KHI UPLOAD --}}
<script>
    function previewImage(input) {
        var preview = document.getElementById('preview-img');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection