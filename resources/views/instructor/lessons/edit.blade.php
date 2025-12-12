@extends('layout.layoutAdmin')

@section('main-content')

<div class="admin-content-wrapper">
    
    <div class="course-form-card">
        <h1>Cập Nhật Bài Học</h1>
        {{-- Thông tin ngữ cảnh --}}
        <div style="text-align: center; color: #666; margin-bottom: 20px;">
            <span>Khóa học: <b>{{ $course->title }}</b></span> | 
            <span>ID Bài: #{{ $lesson->id }}</span>
        </div>

        {{-- Form gửi đến route lessons.update với 2 tham số bắt buộc --}}
        <form action="{{ route('instructor.lessons.update', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Bắt buộc để nhận diện là request Update --}}

            {{-- 1. Tiêu đề bài học --}}
            <div class="form-group">
                <label for="title">Tiêu đề bài học <span class="text-danger">*</span></label>
                {{-- Sử dụng old() với tham số thứ 2 là giá trị hiện tại trong DB --}}
                <input type="text" name="title" id="title" class="form-control" 
                       placeholder="Nhập tiêu đề bài học..." 
                       value="{{ old('title', $lesson->title) }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 2. Thứ tự và Thời lượng (Layout 2 cột) --}}
            <div class="form-row">
                {{-- Cột Thứ tự --}}
                <div class="form-col">
                    <div class="form-group">
                        <label for="order">Thứ tự hiển thị (Số nguyên)</label>
                        <input type="number" name="order" id="order" class="form-control" 
                               placeholder="VD: 1, 2, 3..." 
                               value="{{ old('order', $lesson->order) }}">
                        <small class="text-muted">Nhập số để sắp xếp lại vị trí.</small>
                        @error('order') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Cột Thời lượng (Tùy chọn) --}}
                <div class="form-col">
                    <div class="form-group">
                        <label for="duration">Thời lượng (Phút)</label>
                        <input type="number" name="duration" id="duration" class="form-control" 
                               placeholder="VD: 45" 
                               value="{{ old('duration', $lesson->duration) }}">
                    </div>
                </div>
            </div>

            {{-- 3. Xử lý Video (Phức tạp hơn phần create) --}}
            <div class="form-group" style="background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px dashed #ccc;">
                <label style="font-weight: bold; color: #17a2b8;">Nội dung Video</label>
                
                {{-- [MỚI] Hiển thị video hiện tại nếu có trong DB --}}
                @if(!empty($lesson->video_url))
                    <div style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee;">
                        <span style="font-size: 13px; color: #666; margin-right: 10px;">Video đang sử dụng:</span>
                        <a href="{{ asset($lesson->video_url) }}" target="_blank" style="color: #d63384; font-weight: 600; text-decoration: none;">
                            <i class="fas fa-play-circle"></i> Bấm để xem video hiện tại
                        </a>
                    </div>
                @endif

                <div class="form-row">
                    {{-- Upload file mới --}}
                    <div class="form-col">
                        <label for="video_file">Upload Video mới (Sẽ thay thế video cũ)</label>
                        <input type="file" name="video_file" id="video_file" class="form-control" 
                               style="padding: 9px;" accept="video/*">
                    </div>
                    
                    {{-- Hoặc nhập Link mới --}}
                    <div class="form-col">
                        <label for="video_url">Hoặc dán Link mới (Youtube/Vimeo)</label>
                        {{-- Hiển thị link cũ nếu có --}}
                        <input type="text" name="video_url" id="video_url" class="form-control" 
                               placeholder="https://..." value="{{ old('video_url', $lesson->video_url) }}">
                    </div>
                </div>
                <small class="text-muted">Chỉ chọn file hoặc nhập link nếu bạn muốn thay đổi video hiện tại.</small>
                @error('video_url') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 4. Nội dung bài học --}}
            <div class="form-group">
                <label for="content">Nội dung chi tiết (Văn bản)</label>
                {{-- Nội dung cũ trong textarea đặt giữa cặp thẻ đóng mở --}}
                <textarea name="content" id="content" class="form-control" rows="8"
                          placeholder="Nhập nội dung bài giảng...">{{ old('content', $lesson->content) }}</textarea>
                @error('content') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 5. Nút bấm hành động --}}
            <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    {{-- Nút quay lại danh sách bài học --}}
                    <a href="{{ route('instructor.lessons.index', $course->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                
                <div style="display: flex; gap: 10px; align-items: center;">
                    {{-- (Tùy chọn) Nút Xóa ngay tại trang Edit --}}
                    {{-- Nếu muốn dùng thì bỏ comment dòng dưới và thêm form ẩn ở cuối file --}}
                    {{-- <button type="button" class="btn btn-danger" onclick="confirmDeleteLesson()">
                        <i class="fas fa-trash"></i> Xóa bài này
                    </button> --}}

                    {{-- Nút Reset form về trạng thái ban đầu --}}
                    <button type="reset" class="btn btn-warning" style="color: white;">
                        <i class="fas fa-sync"></i> Khôi phục
                    </button>
                    
                    {{-- Nút Lưu thay đổi --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- (Tùy chọn) Form ẩn và Script để xử lý nút Xóa nếu bạn uncomment nút xóa ở trên --}}
{{-- 
<form id="delete-lesson-form-edit-page" action="{{ route('lessons.destroy', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDeleteLesson() {
        if(confirm('CẢNH BÁO:\nBạn có chắc chắn muốn xóa bài học này không?\nHành động này không thể hoàn tác!')) {
            document.getElementById('delete-lesson-form-edit-page').submit();
        }
    }
</script> 
--}}

@endsection